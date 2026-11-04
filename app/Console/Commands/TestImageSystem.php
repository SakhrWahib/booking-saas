<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Product;
use Illuminate\Support\Facades\File;

class TestImageSystem extends Command
{
    protected $signature = 'test:images';
    protected $description = 'اختبار نظام الصور';

    public function handle()
    {
        $this->info('🔍 اختبار نظام الصور...');
        
        // اختبار الرابط الرمزي
        $linkPath = public_path('storage');
        $targetPath = storage_path('app/public');
        
        $this->info("الرابط الرمزي موجود: " . (File::exists($linkPath) ? '✅ نعم' : '❌ لا'));
        $this->info("مجلد التخزين موجود: " . (File::exists($targetPath) ? '✅ نعم' : '❌ لا'));
        
        // اختبار المنتجات
        $products = Product::all();
        $this->info("عدد المنتجات: " . $products->count());
        
        foreach ($products as $product) {
            $this->info("المنتج: {$product->name}");
            
            if ($product->image_gallery && count($product->image_gallery) > 0) {
                $firstImage = $product->image_gallery[0];
                $cleanPath = str_replace(['storage/app/public/', 'storage/', 'public/'], '', $firstImage);
                
                $storageExists = \Illuminate\Support\Facades\Storage::disk('public')->exists($cleanPath);
                $publicExists = file_exists(public_path('storage/' . $cleanPath));
                
                $this->info("  الصورة: {$firstImage}");
                $this->info("  موجودة في التخزين: " . ($storageExists ? '✅ نعم' : '❌ لا'));
                $this->info("  موجودة في public/storage: " . ($publicExists ? '✅ نعم' : '❌ لا'));
                
                if ($storageExists && $publicExists) {
                    $this->info("  ✅ الصورة تعمل بشكل صحيح");
                } else {
                    $this->error("  ❌ مشكلة في الصورة");
                }
            } else {
                $this->warn("  ⚠️ لا توجد صور لهذا المنتج");
            }
            
            $this->info("---");
        }
        
        $this->info("🎉 انتهى اختبار نظام الصور!");
        
        return 0;
    }
} 