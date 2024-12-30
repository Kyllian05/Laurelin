<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;

class ImageSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $all_products = Storage::directories("images/produits");
        foreach ($all_products as $product) {
            $images_root_url = Storage::files($product);
            foreach ($images_root_url as $image) {

                $url = '/'.str_replace('images', 'pictures', $image);
                $id_prod = pathinfo($product, PATHINFO_BASENAME);

                DB::table('Image')->insert([
                    "URL" => $url,
                    "ID_PRODUIT" => $id_prod,
                ]);
            }
        }
    }
}
