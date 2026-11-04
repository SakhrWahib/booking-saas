<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Product;
use Illuminate\Support\Facades\Storage;

class FinalFixProductImages extends Command
{
    protected $signature = 'products:final-fix';
    protected $description = 'إصلاح نهائي لجميع صور المنتجات';

    public function handle()
    {
        $this->info('بدء الإصلاح النهائي لصور المنتجات...');
        
        // الحصول على جميع الصور من التخزين
        $storageImages = Storage::disk('public')->files('products');
        
        // ترتيب الصور حسب التاريخ (الأحدث أولاً)
        usort($storageImages, function($a, $b) {
            $timestampA = $this->extractTimestamp($a);
            $timestampB = $this->extractTimestamp($b);
            return $timestampB - $timestampA;
        });
        
        $this->info("تم العثور على " . count($storageImages) . " صورة في التخزين");
        
        // الحصول على جميع المنتجات مرتبة حسب التاريخ
        $products = Product::orderBy('created_at', 'desc')->get();
        
        $imageIndex = 0;
        $fixedCount = 0;
        
        foreach ($products as $product) {
            $this->info("معالجة المنتج: {$product->name}");
            
            // تعيين 1-3 صور لكل منتج
            $imagesToAssign = rand(1, 3);
            $productImages = [];
            
            for ($i = 0; $i < $imagesToAssign && $imageIndex < count($storageImages); $i++) {
                $productImages[] = $storageImages[$imageIndex];
                $imageIndex++;
            }
            
            if (!empty($productImages)) {
                $product->update(['image_gallery' => $productImages]);
                $this->info("✓ تم تعيين " . count($productImages) . " صورة للمنتج: {$product->name}");
                $fixedCount++;
            } else {
                $this->warn("✗ لم يتم تعيين أي صور للمنتج: {$product->name}");
            }
        }
        
        $this->info("تم إصلاح {$fixedCount} منتج بنجاح!");
        
        // اختبار عرض الصور
        $this->info("اختبار عرض الصور...");
        foreach ($products as $product) {
            if ($product->image_gallery && count($product->image_gallery) > 0) {
                $firstImage = $product->image_gallery[0];
                $cleanPath = str_replace(['storage/app/public/', 'storage/', 'public/'], '', $firstImage);
                $fileExists = Storage::disk('public')->exists($cleanPath);
                $publicFileExists = file_exists(public_path('storage/' . $cleanPath));
                
                if ($fileExists && $publicFileExists) {
                    $this->info("✓ {$product->name}: الصورة تعمل بشكل صحيح");
                } else {
                    $this->error("✗ {$product->name}: مشكلة في الصورة");
                }
            }
        }
        
        return 0;
    }
    
    private function extractTimestamp($filePath)
    {
        $filename = basename($filePath);
        if (preg_match('/^(\d+)_/', $filename, $matches)) {
            return (int) $matches[1];
        }
        return 0;
    }
} 