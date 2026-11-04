<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Product;

class CheckProductImages extends Command
{
    protected $signature = 'products:check-images';
    protected $description = 'التحقق من صور المنتجات';

    public function handle()
    {
        $products = Product::all();
        
        foreach ($products as $product) {
            $this->info("المنتج: {$product->name}");
            $this->info("الصور: " . json_encode($product->image_gallery));
            $this->info("---");
        }
        
        return 0;
    }
} 