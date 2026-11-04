<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Product;
use Illuminate\Support\Facades\Storage;

class AssignImagesToProducts extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'products:assign-images';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'تعيين الصور للمنتجات بناءً على تاريخ الإنشاء';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $this->info('بدء تعيين الصور للمنتجات...');
        
        // الحصول على جميع الصور من مجلد storage
        $storageImages = Storage::disk('public')->files('products');
        
        // ترتيب الصور حسب التاريخ (الأقدم أولاً)
        usort($storageImages, function($a, $b) {
            $timestampA = $this->extractTimestamp($a);
            $timestampB = $this->extractTimestamp($b);
            return $timestampA - $timestampB;
        });
        
        $this->info("تم العثور على " . count($storageImages) . " صورة في التخزين");
        
        // الحصول على المنتجات مرتبة حسب تاريخ الإنشاء
        $products = Product::orderBy('created_at', 'asc')->get();
        
        $imageIndex = 0;
        $assignedCount = 0;
        
        foreach ($products as $product) {
            $this->info("معالجة المنتج: {$product->name} (أنشئ في: {$product->created_at})");
            
            $productImages = [];
            $imagesToAssign = rand(1, 3); // تعيين 1-3 صور لكل منتج
            
            for ($i = 0; $i < $imagesToAssign && $imageIndex < count($storageImages); $i++) {
                $productImages[] = $storageImages[$imageIndex];
                $imageIndex++;
            }
            
            if (!empty($productImages)) {
                $product->update(['image_gallery' => $productImages]);
                $this->info("تم تعيين " . count($productImages) . " صورة للمنتج: {$product->name}");
                $assignedCount++;
            } else {
                $this->warn("لم يتم تعيين أي صور للمنتج: {$product->name}");
            }
        }
        
        $this->info("تم تعيين الصور لـ {$assignedCount} منتج بنجاح!");
        
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