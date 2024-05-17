<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Product;

class ProductsTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Create a new product using the Product model's create method
        Product::create([
            'product_name' => 'Product 1',
            'product_description' => 'Description for Product 1',
            'product_price' => '299.99',
            'product_discount_price' => '279.99',
            'product_quantity' => 80,
            'product_images' => 'product1_image1.jpg,product1_image2.jpg',
            'product_manufacturer' => 'Product 1 Manufacturer',
            'product_status' => 'A',
            'product_slug' => 'product-1',
        ]);

        // Create another product using the Product model's create method
        Product::create([
            'product_name' => 'Product 2',
            'product_description' => 'Description for Product 2',
            'product_price' => '399.99',
            'product_discount_price' => '379.99',
            'product_quantity' => 90,
            'product_images' => 'product2_image1.jpg,product2_image2.jpg',
            'product_manufacturer' => 'Product 2 Manufacturer',
            'product_status' => 'A',
            'product_slug' => 'product-2',
        ]);
    }
}
