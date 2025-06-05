<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class ProductSeeder extends Seeder
{
    public function run(): void
    {
        $products = [
            [
                'name' => 'Notebook Dell Inspiron',
                'description' => 'Notebook com processador Intel Core i5, 8GB RAM e SSD 256GB.',
                'price' => 3899.90,
                'stock' => 15,
            ],
            [
                'name' => 'Smartphone Samsung Galaxy S21',
                'description' => 'Tela de 6.2", 128GB, Câmera Tripla.',
                'price' => 2999.00,
                'stock' => 25,
            ],
            [
                'name' => 'Mouse Gamer Logitech G403',
                'description' => 'Sensor HERO 25K, RGB e ultra leve.',
                'price' => 239.99,
                'stock' => 40,
            ],
            [
                'name' => 'Monitor LG Ultrawide 29"',
                'description' => 'Resolução 2560x1080, IPS, HDMI.',
                'price' => 1249.00,
                'stock' => 10,
            ],
            [
                'name' => 'Teclado Mecânico Redragon Kumara',
                'description' => 'Switch Blue, LED vermelho, ABNT2.',
                'price' => 199.90,
                'stock' => 30,
            ],
                [
                'name' => 'Notebook Dell Inspiron',
                'description' => 'Notebook com processador Intel Core i5, 8GB RAM e SSD 256GB.',
                'price' => 3899.90,
                'stock' => 15,
            ],
            [
                'name' => 'Smartphone Samsung Galaxy S21',
                'description' => 'Tela de 6.2", 128GB, Câmera Tripla.',
                'price' => 2999.00,
                'stock' => 25,
            ],
            [
                'name' => 'Mouse Gamer Logitech G403',
                'description' => 'Sensor HERO 25K, RGB e ultra leve.',
                'price' => 239.99,
                'stock' => 40,
            ],
            [
                'name' => 'Monitor LG Ultrawide 29"',
                'description' => 'Resolução 2560x1080, IPS, HDMI.',
                'price' => 1249.00,
                'stock' => 10,
            ],
            [
                'name' => 'Teclado Mecânico Redragon Kumara',
                'description' => 'Switch Blue, LED vermelho, ABNT2.',
                'price' => 199.90,
                'stock' => 30,
            ],
                [
                'name' => 'Notebook Dell Inspiron',
                'description' => 'Notebook com processador Intel Core i5, 8GB RAM e SSD 256GB.',
                'price' => 3899.90,
                'stock' => 15,
            ],
            [
                'name' => 'Smartphone Samsung Galaxy S21',
                'description' => 'Tela de 6.2", 128GB, Câmera Tripla.',
                'price' => 2999.00,
                'stock' => 25,
            ],
            [
                'name' => 'Mouse Gamer Logitech G403',
                'description' => 'Sensor HERO 25K, RGB e ultra leve.',
                'price' => 239.99,
                'stock' => 40,
            ],
            [
                'name' => 'Monitor LG Ultrawide 29"',
                'description' => 'Resolução 2560x1080, IPS, HDMI.',
                'price' => 1249.00,
                'stock' => 10,
            ],
            [
                'name' => 'Teclado Mecânico Redragon Kumara',
                'description' => 'Switch Blue, LED vermelho, ABNT2.',
                'price' => 199.90,
                'stock' => 30,
            ],
        ];

        foreach ($products as $product) {
            DB::table('products')->insert([
                'name' => $product['name'],
                'description' => $product['description'],
                'price' => $product['price'],
                'stock' => $product['stock'],
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        }
    }
}
