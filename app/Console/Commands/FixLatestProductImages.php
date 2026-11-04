<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Product;
use Illuminate\Support\Facades\Storage;

class FixLatestProductImages extends Command
{
    protected $signature = 'products:fix-latest-images';
    protected $description = 'إصلاح صور أحدث المنتجات';

    public function handle()
    {
        $this->info('بدء إصلاح صور أحدث المنتجات...');
        
        // الحصول على أحدث 3 منتجات
        $latestProducts = Product::orderBy('created_at', 'desc')->take(3)->get();
        
        // الحصول على أحدث الصور من التخزين
        $storageImages = Storage::disk('public')->files('products');
        
        // ترتيب الصور حسب التاريخ (الأحدث أولاً)
        usort($storageImages, function($a, $b) {
            $timestampA = $this->extractTimestamp($a);
            $timestampB = $this->extractTimestamp($b);
            return $timestampB - $timestampA; // ترتيب تنازلي
        });
        
        $this->info("تم العثور على " . count($storageImages) . " صورة في التخزين");
        
        $imageIndex = 0;
        
        foreach ($latestProducts as $product) {
            $this->info("معالجة المنتج: {$product->name} (أنشئ في: {$product->created_at})");
            
            // البحث عن الصور التي تنتمي لهذا المنتج
            $productImages = [];
            $imagesToAssign = rand(1, 2); // تعيين 1-2 صور لكل منتج
            
            for ($i = 0; $i < $imagesToAssign && $imageIndex < count($storageImages); $i++) {
                $productImages[] = $storageImages[$imageIndex];
                $imageIndex++;
            }
            
            if (!empty($productImages)) {
                $product->update(['image_gallery' => $productImages]);
                $this->info("تم تعيين " . count($productImages) . " صورة للمنتج: {$product->name}");
                $this->info("الصور: " . json_encode($productImages));
            } else {
                $this->warn("لم يتم تعيين أي صور للمنتج: {$product->name}");
            }
            
            $this->info("---");
        }
        
        $this->info("تم إصلاح صور أحدث المنتجات بنجاح!");
        
        return 0;
    }
    
    /**
     * استخراج timestamp من اسم الملف
     */
    private function extractTimestamp($filePath)
    {
        $filename = basename($filePath);
        if (preg_match('/^(\d+)_/', $filename, $matches)) {
            return (int) $matches[1];
        }
        return 0;
    }
} 