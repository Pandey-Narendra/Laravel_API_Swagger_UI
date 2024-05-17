<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

/**
 * @OA\Schema(
 *     schema="Product",
 *     type="object",
 *     title="Product",
 *     description="Product Model",
 *     required={"product_name", "product_description", "product_price", "product_quantity", "product_images", "product_manufacturer", "product_status", "product_slug"},
 *     ...
 * )
 */
class Product extends Model
{
    use HasFactory;

    // Specify the fillable attributes to allow mass assignment
    protected $fillable = [
        'product_name',
        'product_description',
        'product_price',
        'product_discount_price',
        'product_quantity',
        'product_images',
        'product_manufacturer',
        'product_status',
        'product_slug',
    ];

    // Define attribute casts to automatically convert data types
    protected $casts = [
        'product_discount_price' => 'decimal:2', // Convert product_discount_price to a decimal with two decimal places
    ];
    
    // Hide attributes from being included in JSON responses
    protected $hidden = [
        'created_at',
        'updated_at',
    ];
}
