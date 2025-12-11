<?php

namespace Database\Seeders;

use App\Models\Category;
use App\Models\Questionnaire;
use Illuminate\Database\Seeder;

class QuestionnaireSeeder extends Seeder
{
    public function run(): void
    {
        // Kosongkan tabel terlebih dahulu
        Questionnaire::truncate();

        // Ambil semua kategori
        $categories = Category::all();

        foreach ($categories as $category) {
            // Kuesioner 1 (Umum) - Wajib untuk semua kategori
            Questionnaire::create([
                'category_id' => $category->id,
                'name' => 'Kuesioner 1 (Umum)',
                'title' => 'Data Diri & Pendidikan',
                'description' => 'Kuesioner tentang informasi pribadi dan riwayat pendidikan di UAD. Wajib diisi oleh seluruh alumni.',
                'order' => 1,
                'is_required' => true,
                'is_active' => true,
            ]);

            // Kuesioner 2 - Spesifik berdasarkan kategori
            $questionnaire2Data = $this->getQuestionnaire2Data($category->name);
            Questionnaire::create([
                'category_id' => $category->id,
                'name' => 'Kuesioner 2',
                'title' => $questionnaire2Data['title'],
                'description' => $questionnaire2Data['description'],
                'order' => 2,
                'is_required' => false,
                'is_active' => true,
            ]);

            // Kuesioner 3 - Spesifik berdasarkan kategori
            $questionnaire3Data = $this->getQuestionnaire3Data($category->name);
            Questionnaire::create([
                'category_id' => $category->id,
                'name' => 'Kuesioner 3',
                'title' => $questionnaire3Data['title'],
                'description' => $questionnaire3Data['description'],
                'order' => 3,
                'is_required' => false,
                'is_active' => true,
            ]);

            // Kuesioner 4 - Spesifik berdasarkan kategori
            $questionnaire4Data = $this->getQuestionnaire4Data($category->name);
            Questionnaire::create([
                'category_id' => $category->id,
                'name' => 'Kuesioner 4',
                'title' => $questionnaire4Data['title'],
                'description' => $questionnaire4Data['description'],
                'order' => 4,
                'is_required' => false,
                'is_active' => true,
            ]);
        }
    }

    /**
     * Get questionnaire 2 data based on category.
     */
    private function getQuestionnaire2Data(string $categoryName): array
    {
        return match ($categoryName) {
            'BEKERJA DI PERUSAHAAN/INSTANSI' => [
                'title' => 'Pengalaman Kerja & Karir',
                'description' => 'Kuesioner tentang pengalaman kerja di perusahaan/instansi, posisi, tanggung jawab, dan perkembangan karir.',
            ],
            'WIRAUSAHA/PEMILIK USAHA' => [
                'title' => 'Profil Usaha & Pengembangan',
                'description' => 'Kuesioner tentang jenis usaha, skala, perkembangan, dan tantangan dalam berwirausaha.',
            ],
            'MELANJUTKAN PENDIDIKAN' => [
                'title' => 'Studi Lanjut & Persiapan',
                'description' => 'Kuesioner tentang program studi lanjut, persiapan, dan motivasi melanjutkan pendidikan.',
            ],
            'PENCARI KERJA' => [
                'title' => 'Pencarian Kerja & Hambatan',
                'description' => 'Kuesioner tentang proses pencarian kerja, hambatan yang dihadapi, dan strategi yang digunakan.',
            ],
            'TIDAK BEKERJA & TIDAK MENCARI' => [
                'title' => 'Situasi & Aktivitas Saat Ini',
                'description' => 'Kuesioner tentang situasi saat ini, aktivitas yang dilakukan, dan alasan tidak bekerja.',
            ],
            default => [
                'title' => 'Pengalaman Kerja & Karir',
                'description' => 'Kuesioner tentang pengalaman kerja setelah lulus dari UAD.',
            ],
        };
    }

    /**
     * Get questionnaire 3 data based on category.
     */
    private function getQuestionnaire3Data(string $categoryName): array
    {
        return match ($categoryName) {
            'BEKERJA DI PERUSAHAAN/INSTANSI' => [
                'title' => 'Keterampilan di Tempat Kerja',
                'description' => 'Kuesioner tentang penerapan keterampilan yang diperoleh selama kuliah di tempat kerja.',
            ],
            'WIRAUSAHA/PEMILIK USAHA' => [
                'title' => 'Keterampilan Kewirausahaan',
                'description' => 'Kuesioner tentang keterampilan yang dibutuhkan dalam berwirausaha dan penerapannya.',
            ],
            'MELANJUTKAN PENDIDIKAN' => [
                'title' => 'Keterampilan Akademik',
                'description' => 'Kuesioner tentang keterampilan akademik yang diperoleh dan pengembangannya.',
            ],
            'PENCARI KERJA' => [
                'title' => 'Kesiapan Kerja & Keterampilan',
                'description' => 'Kuesioner tentang kesiapan memasuki dunia kerja dan keterampilan yang perlu dikembangkan.',
            ],
            'TIDAK BEKERJA & TIDAK MENCARI' => [
                'title' => 'Keterampilan & Minat',
                'description' => 'Kuesioner tentang keterampilan yang dimiliki dan minat untuk pengembangan diri.',
            ],
            default => [
                'title' => 'Keterampilan & Kompetensi',
                'description' => 'Kuesioner tentang keterampilan yang diperoleh selama kuliah dan pengembangannya.',
            ],
        };
    }

    /**
     * Get questionnaire 4 data based on category.
     */
    private function getQuestionnaire4Data(string $categoryName): array
    {
        return match ($categoryName) {
            'BEKERJA DI PERUSAHAAN/INSTANSI' => [
                'title' => 'Kepuasan Kerja & Saran',
                'description' => 'Kuesioner tentang kepuasan terhadap pekerjaan saat ini dan saran untuk pengembangan karir.',
            ],
            'WIRAUSAHA/PEMILIK USAHA' => [
                'title' => 'Kepuasan & Saran Pengembangan',
                'description' => 'Kuesioner tentang kepuasan sebagai wirausaha dan saran untuk pengembangan usaha.',
            ],
            'MELANJUTKAN PENDIDIKAN' => [
                'title' => 'Kepuasan & Rencana Masa Depan',
                'description' => 'Kuesioner tentang kepuasan terhadap pendidikan lanjut dan rencana karir setelah lulus.',
            ],
            'PENCARI KERJA' => [
                'title' => 'Harapan & Dukungan yang Dibutuhkan',
                'description' => 'Kuesioner tentang harapan terhadap pekerjaan dan dukungan yang dibutuhkan dari almamater.',
            ],
            'TIDAK BEKERJA & TIDAK MENCARI' => [
                'title' => 'Rencana Masa Depan & Dukungan',
                'description' => 'Kuesioner tentang rencana masa depan dan jenis dukungan yang dibutuhkan dari almamater.',
            ],
            default => [
                'title' => 'Kepuasan & Saran',
                'description' => 'Kuesioner tentang kepuasan terhadap pendidikan di UAD dan saran pengembangan.',
            ],
        };
    }
}
