<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Product;

class TestImageDisplay extends Command
{
    protected $signature = 'products:test-images';
    protected $description = 'اختبار عرض الصور';

    public function handle()
    {
        $this->info('اختبار عرض الصور...');
        
        $products = Product::all();
        
        foreach ($products as $product) {
            $this->info("المنتج: {$product->name}");
            $this->info("الصور في قاعدة البيانات: " . json_encode($product->image_gallery));
            
            if ($product->image_gallery && count($product->image_gallery) > 0) {
                $firstImage = $product->image_gallery[0];
                $this->info("الصورة الأولى: {$firstImage}");
                
                // اختبار دالة getProductImageUrl
                $imageUrl = getProductImageUrl($firstImage);
                $this->info("URL الصورة: {$imageUrl}");
                
                // اختبار دالة getFirstProductImage
                $firstImageUrl = getFirstProductImage($product->image_gallery);
                $this->info("URL الصورة الأولى: {$firstImageUrl}");
                
                // التحقق من وجود الملف
                $cleanPath = str_replace(['storage/app/public/', 'storage/', 'public/'], '', $firstImage);
                $fileExists = \Illuminate\Support\Facades\Storage::disk('public')->exists($cleanPath);
                $this->info("الملف موجود في التخزين: " . ($fileExists ? 'نعم' : 'لا'));
                
                $publicFileExists = file_exists(public_path('storage/' . $cleanPath));
                $this->info("الملف موجود في public/storage: " . ($publicFileExists ? 'نعم' : 'لا'));
            } else {
                $this->warn("لا توجد صور لهذا المنتج");
            }
            
            $this->info("---");
        }
        
        return 0;
    }
} 