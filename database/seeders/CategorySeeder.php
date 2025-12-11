<?php

namespace Database\Seeders;

use App\Models\Category;
use Illuminate\Database\Seeder;

class CategorySeeder extends Seeder
{
    public function run(): void
    {
        // Kosongkan tabel terlebih dahulu
        Category::truncate();

        $categories = [
            [
                'name' => 'BEKERJA DI PERUSAHAAN/INSTANSI',
                'icon' => 'fas fa-building',
                'description' => 'Alumni yang sedang bekerja di perusahaan, instansi pemerintah, atau organisasi lainnya.',
                'is_active' => true,
            ],
            [
                'name' => 'WIRAUSAHA/PEMILIK USAHA',
                'icon' => 'fas fa-briefcase',
                'description' => 'Alumni yang memiliki usaha sendiri atau berwirausaha.',
                'is_active' => true,
            ],
            [
                'name' => 'MELANJUTKAN PENDIDIKAN',
                'icon' => 'fas fa-graduation-cap',
                'description' => 'Alumni yang sedang melanjutkan pendidikan ke jenjang yang lebih tinggi.',
                'is_active' => true,
            ],
            [
                'name' => 'PENCARI KERJA',
                'icon' => 'fas fa-search',
                'description' => 'Alumni yang sedang mencari pekerjaan atau belum mendapatkan pekerjaan.',
                'is_active' => true,
            ],
            [
                'name' => 'TIDAK BEKERJA & TIDAK MENCARI',
                'icon' => 'fas fa-user-clock',
                'description' => 'Alumni yang tidak bekerja dan tidak sedang mencari pekerjaan.',
                'is_active' => true,
            ],
        ];

        foreach ($categories as $category) {
            Category::create($category);
        }
    }
}
