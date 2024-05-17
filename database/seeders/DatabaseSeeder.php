<?php
 
namespace Database\Seeders;
 
use Illuminate\Database\Seeder;

 
class DatabaseSeeder extends Seeder
{
    /**
     * Run the database seeders.
     */
    public function run(): void
    {
        // Call the ProductsTableSeeder class to seed the 'products' table
        $this->call([
            ProductsTableSeeder::class,
        ]);     
    }
}  