<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Barang;

class BarangSeeder extends Seeder
{
    public function run(): void
    {
        Barang::insert([
            [
                'nama' => 'Buku Tulis',
                'harga' => 5000,
                'stok' => 99,
            ],
            [
                'nama' => 'Pulpen',
                'harga' => 3000,
                'stok' => 99,
            ],
            [
                'nama' => 'Penggaris',
                'harga' => 4000,
                'stok' => 99,
            ],
        ]);
    }
}
