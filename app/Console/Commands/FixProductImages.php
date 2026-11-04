<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Product;
use Illuminate\Support\Facades\Storage;

class FixProductImages extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'products:fix-images';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'إصلاح مسارات الصور للمنتجات الموجودة في قاعدة البيانات';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $this->info('بدء إصلاح مسارات الصور للمنتجات...');
        
        $products = Product::all();
        $fixedCount = 0;
        
        foreach ($products as $product) {
            $this->info("معالجة المنتج: {$product->name}");
            
            // التحقق من وجود صور في مجلد storage
            $storageImages = Storage::disk('public')->files('products');
            $productImages = [];
            
            foreach ($storageImages as $imagePath) {
                // استخراج اسم الملف من المسار
                $filename = basename($imagePath);
                
                // التحقق من أن الصورة تنتمي لهذا المنتج (بناءً على timestamp)
                if ($this->imageBelongsToProduct($filename, $product)) {
                    $productImages[] = $imagePath;
                }
            }
            
            if (!empty($productImages)) {
                $product->update(['image_gallery' => $productImages]);
                $this->info("تم إصلاح {$product->name} - عدد الصور: " . count($productImages));
                $fixedCount++;
            } else {
                $this->warn("لم يتم العثور على صور للمنتج: {$product->name}");
            }
        }
        
        $this->info("تم إصلاح {$fixedCount} منتج بنجاح!");
        
        return 0;
    }
    
    /**
     * التحقق من أن الصورة تنتمي للمنتج
     */
    private function imageBelongsToProduct($filename, $product)
    {
        // استخراج timestamp من اسم الملف
        if (preg_match('/^(\d+)_/', $filename, $matches)) {
            $imageTimestamp = (int) $matches[1];
            $productTimestamp = $product->created_at->timestamp;
            
            // التحقق من أن الصورة تم إنشاؤها في نفس اليوم أو بعده
            return $imageTimestamp >= $productTimestamp - 86400; // 24 ساعة
        }
        
        return false;
    }
} 