<?php

namespace Database\Seeders;

use App\Models\Category;
use Illuminate\Database\Seeder;

class CategorySeeder extends Seeder
{
    public function run()
    {
        $categories = [
            [
                'name' => 'Bekerja di Perusahaan/Instansi',
                'slug' => 'bekerja',
                'description' => 'Alumni yang sedang bekerja di perusahaan, instansi pemerintah, atau organisasi lainnya.',
                'icon' => 'fa-building',
                'order' => 1,
                'is_active' => true,
            ],
            [
                'name' => 'Wirausaha/Pemilik Usaha',
                'slug' => 'wirausaha',
                'description' => 'Alumni yang memiliki usaha sendiri atau berwirausaha.',
                'icon' => 'fa-briefcase',
                'order' => 2,
                'is_active' => true,
            ],
            [
                'name' => 'Melanjutkan Pendidikan',
                'slug' => 'pendidikan',
                'description' => 'Alumni yang sedang melanjutkan pendidikan ke jenjang yang lebih tinggi.',
                'icon' => 'fa-graduation-cap',
                'order' => 3,
                'is_active' => true,
            ],
            [
                'name' => 'Pencari Kerja',
                'slug' => 'pencari',
                'description' => 'Alumni yang sedang mencari pekerjaan atau belum mendapatkan pekerjaan.',
                'icon' => 'fa-search',
                'order' => 4,
                'is_active' => true,
            ],
            [
                'name' => 'Tidak Bekerja & Tidak Mencari',
                'slug' => 'tidak-kerja',
                'description' => 'Alumni yang tidak bekerja dan tidak sedang mencari pekerjaan.',
                'icon' => 'fa-user-clock',
                'order' => 5,
                'is_active' => true,
            ],
        ];

        foreach ($categories as $category) {
            Category::create($category);
        }
    }
}