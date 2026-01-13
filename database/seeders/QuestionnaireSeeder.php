<?php

namespace Database\Seeders;

use App\Models\Category;
use App\Models\Question;
use App\Models\Questionnaire;
use App\Models\QuestionnaireSequence;
use Illuminate\Database\Seeder;

class QuestionnaireSeeder extends Seeder
{
    public function run()
    {
        $categories = Category::all();

        foreach ($categories as $category) {
            // 1. Bagian Umum (sama untuk semua kategori)
            $generalQuestionnaire = $this->createGeneralQuestionnaire($category);
            
            // 2. Bagian spesifik berdasarkan kategori
            $specificQuestionnaires = $this->createSpecificQuestionnaires($category);
            
            // 3. Buat urutan
            $this->createSequences($category, $generalQuestionnaire, $specificQuestionnaires);
        }
    }

    private function createGeneralQuestionnaire($category): Questionnaire
    {
        $questionnaire = Questionnaire::create([
            'category_id' => $category->id,
            'name' => 'Bagian Umum',
            'slug' => 'bagian-umum',
            'description' => 'Wajib diisi oleh semua alumni',
            'order' => 1,
            'is_required' => true,
            'is_general' => true,
            'time_estimate' => 10,
        ]);

        // Pertanyaan 1: Kompetensi dengan skala Likert per baris
        Question::create([
            'questionnaire_id' => $questionnaire->id,
            'question_text' => 'Pada saat lulus, pada tingkat mana Anda menguasai kompetensi berikut?',
            'question_type' => 'likert_per_row',
            'row_items' => [
                'ethics' => 'Etika',
                'expertise' => 'Keahlian Bidang Ilmu',
                'english' => 'Bahasa Inggris',
                'it_skills' => 'Penggunaan IT',
                'communication' => 'Komunikasi',
                'teamwork' => 'Kerja Sama Tim',
                'self_development' => 'Pengembangan Diri',
            ],
            'scale_options' => [1, 2, 3, 4, 5],
            'scale_label_low' => 'Sangat Rendah',
            'scale_label_high' => 'Sangat Tinggi',
            'is_required' => true,
            'order' => 1,
            'points' => 1000,
            'helper_text' => 'Pilih skala 1-5 (1=Sangat Rendah, 2=Rendah, 3=Cukup, 4=Tinggi, 5=Sangat Tinggi) untuk setiap kompetensi',
        ]);

        // Pertanyaan 2: Metode pembelajaran dengan skala Likert per baris
        Question::create([
            'questionnaire_id' => $questionnaire->id,
            'question_text' => 'Menurut Anda, seberapa besar penekanan metode pembelajaran berikut di prodi Anda?',
            'question_type' => 'likert_per_row',
            'row_items' => [
                'lecture' => 'Perkuliahan',
                'demonstration' => 'Demonstrasi',
                'research' => 'Partisipasi Proyek Riset',
                'internship' => 'Magang',
                'practice' => 'Praktikum',
                'field_work' => 'Kerja Lapangan',
                'discussion' => 'Diskusi',
            ],
            'scale_options' => [1, 2, 3, 4, 5],
            'scale_label_low' => 'Tidak Sama Sekali',
            'scale_label_high' => 'Sangat Besar',
            'is_required' => true,
            'order' => 2,
            'points' => 1000,
            'helper_text' => 'Pilih skala 1-5 (1=Tidak Sama Sekali, 2=Kurang, 3=Cukup, 4=Besar, 5=Sangat Besar) untuk setiap metode pembelajaran',
        ]);

        return $questionnaire;
    }

    private function createSpecificQuestionnaires($category): array
    {
        $questionnaires = [];
        
        switch ($category->slug) {
            case 'bekerja':
                $questionnaires = $this->createBekerjaQuestionnaires($category);
                break;
            case 'wirausaha':
                $questionnaires = $this->createWirausahaQuestionnaires($category);
                break;
            case 'pendidikan':
                $questionnaires = $this->createPendidikanQuestionnaires($category);
                break;
            case 'pencari':
                $questionnaires = $this->createPencariQuestionnaires($category);
                break;
            case 'tidak-kerja':
                $questionnaires = $this->createTidakKerjaQuestionnaires($category);
                break;
        }
        
        return $questionnaires;
    }

    private function createBekerjaQuestionnaires($category): array
    {
        $questionnaires = [];
        
        // ============ BAGIAN 1: Informasi Karir Awal ============
        $bagian1 = Questionnaire::create([
            'category_id' => $category->id,
            'name' => 'Bagian 1: Informasi Karir Awal',
            'slug' => 'bagian-1',
            'description' => 'Informasi awal karir setelah lulus',
            'order' => 2,
            'is_required' => true,
            'is_general' => false,
            'time_estimate' => 5,
        ]);

        // Pertanyaan 1
        Question::create([
            'questionnaire_id' => $bagian1->id,
            'question_text' => 'Sejak lulus, berapa lama Anda mendapat pekerjaan sebagai karyawan untuk pertama kali?',
            'question_type' => 'radio',
            'options' => ['3-<6 bulan', '6-<9 bulan', '9-<12 bulan', '>12 bulan'],
            'is_required' => true,
            'order' => 1,
            'points' => 1000,
            'helper_text' => 'Pilih satu opsi yang sesuai',
        ]);

        // Pertanyaan 2
        Question::create([
            'questionnaire_id' => $bagian1->id,
            'question_text' => 'Rata-rata pendapatan bersih (take home pay) per bulan?',
            'question_type' => 'radio',
            'options' => ['0-<3 juta', '3-<6 juta', '6-<10 juta', '>10 juta'],
            'is_required' => true,
            'order' => 2,
            'points' => 1000,
        ]);

        $questionnaires[] = $bagian1;
        
        // ============ BAGIAN 2: Identitas Perusahaan & Bidang Kerja ============
        $bagian2 = Questionnaire::create([
            'category_id' => $category->id,
            'name' => 'Bagian 2: Identitas Perusahaan & Bidang Kerja',
            'slug' => 'bagian-2',
            'order' => 3,
            'is_required' => true,
            'is_general' => false,
            'time_estimate' => 8,
        ]);

        // Pertanyaan 3
        Question::create([
            'questionnaire_id' => $bagian2->id,
            'question_text' => 'Nama perusahaan/kantor tempat bekerja?',
            'question_type' => 'text',
            'is_required' => true,
            'order' => 1,
            'placeholder' => 'Contoh: PT. Teknologi Indonesia Maju',
        ]);

        // Pertanyaan 4
        Question::create([
            'questionnaire_id' => $bagian2->id,
            'question_text' => 'Tingkat/skala perusahaan?',
            'question_type' => 'radio',
            'options' => ['Lokal/Wilayah', 'Nasional', 'Multinasional/Internasional'],
            'is_required' => true,
            'order' => 2,
        ]);

        // Pertanyaan 5
        Question::create([
            'questionnaire_id' => $bagian2->id,
            'question_text' => 'Tuliskan bidang pekerjaan Anda (dokter, programmer, guru, dsb)!',
            'question_type' => 'text',
            'is_required' => true,
            'order' => 3,
            'placeholder' => 'Contoh: Software Developer / Backend Engineer',
        ]);

        $questionnaires[] = $bagian2;
        
        // ============ BAGIAN 3: Relevansi Studi & Dukungan Kurikulum ============
        $bagian3 = Questionnaire::create([
            'category_id' => $category->id,
            'name' => 'Bagian 3: Relevansi Studi & Dukungan Kurikulum',
            'slug' => 'bagian-3',
            'order' => 4,
            'is_required' => true,
            'is_general' => false,
            'time_estimate' => 7,
        ]);

        // Pertanyaan 6
        Question::create([
            'questionnaire_id' => $bagian3->id,
            'question_text' => 'Seberapa erat hubungan bidang studi dengan pekerjaan?',
            'question_type' => 'radio',
            'options' => ['Sangat Erat', 'Erat', 'Kurang', 'Tidak Sama Sekali'],
            'is_required' => true,
            'order' => 1,
        ]);

        // Pertanyaan 7
        Question::create([
            'questionnaire_id' => $bagian3->id,
            'question_text' => 'Tuliskan 3 (tiga) mata kuliah yang mendukung pekerjaan',
            'question_type' => 'textarea',
            'is_required' => true,
            'order' => 2,
            'rows' => 4,
            'placeholder' => 'Tuliskan setiap mata kuliah dalam baris terpisah',
            'helper_text' => 'Contoh: \n1. Pemrograman Berorientasi Objek\n2. Basis Data\n3. Rekayasa Perangkat Lunak',
        ]);

        $questionnaires[] = $bagian3;
        
        // ============ BAGIAN 4: Pengembangan Kompetensi Setelah Lulus ============
        $bagian4 = Questionnaire::create([
            'category_id' => $category->id,
            'name' => 'Bagian 4: Pengembangan Kompetensi Setelah Lulus',
            'slug' => 'bagian-4',
            'order' => 5,
            'is_required' => true,
            'is_general' => false,
            'time_estimate' => 10,
        ]);

        // Pertanyaan 8
        Question::create([
            'questionnaire_id' => $bagian4->id,
            'question_text' => 'Kompetensi spesifik apa yang Anda kembangkan setelah lulus yang paling penting dalam pekerjaan?',
            'question_type' => 'textarea',
            'is_required' => true,
            'order' => 1,
            'rows' => 5,
            'placeholder' => 'Contoh: \n- Penggunaan framework Laravel\n- Kemampuan DevOps\n- Cloud computing',
        ]);

        // Pertanyaan 9
        Question::create([
            'questionnaire_id' => $bagian4->id,
            'question_text' => 'Sertifikat / kompetensi yang Anda peroleh dari perusahaan?',
            'question_type' => 'textarea',
            'is_required' => false,
            'order' => 2,
            'rows' => 4,
            'placeholder' => 'Contoh: \n- AWS Certified Developer\n- Laravel Certified Developer',
            'helper_text' => 'Opsional: Isi jika ada sertifikat yang diperoleh',
        ]);

        $questionnaires[] = $bagian4;

        return $questionnaires;
    }

    private function createWirausahaQuestionnaires($category): array
    {
        $questionnaires = [];
        
        // ============ BAGIAN 1: Awal & Identitas Usaha ============
        $bagian1 = Questionnaire::create([
            'category_id' => $category->id,
            'name' => 'Bagian 1: Awal & Identitas Usaha',
            'slug' => 'bagian-1',
            'description' => 'Informasi awal usaha',
            'order' => 2,
            'is_required' => true,
            'is_general' => false,
            'time_estimate' => 6,
        ]);

        // Pertanyaan 1
        Question::create([
            'questionnaire_id' => $bagian1->id,
            'question_text' => 'Sejak lulus, berapa lama Anda melakukan wirausaha untuk pertama kali?',
            'question_type' => 'radio',
            'options' => ['3-<6 bulan', '6-<9 bulan', '9-<12 bulan', '>12 bulan'],
            'is_required' => true,
            'order' => 1,
            'helper_text' => 'Pilih satu opsi yang sesuai',
        ]);

        // Pertanyaan 2
        Question::create([
            'questionnaire_id' => $bagian1->id,
            'question_text' => 'Nama perusahaan/tempat wirausaha?',
            'question_type' => 'text',
            'is_required' => true,
            'order' => 2,
            'placeholder' => 'Contoh: CV. Kuliner Nusantara',
        ]);

        // Pertanyaan 3
        Question::create([
            'questionnaire_id' => $bagian1->id,
            'question_text' => 'Posisi/jabatan Anda dalam usaha ini?',
            'question_type' => 'text',
            'is_required' => true,
            'order' => 3,
            'placeholder' => 'Contoh: Pemilik, Direktur Utama',
        ]);

        $questionnaires[] = $bagian1;
        
        // ============ BAGIAN 2: Bidang & Perkembangan Usaha ============
        $bagian2 = Questionnaire::create([
            'category_id' => $category->id,
            'name' => 'Bagian 2: Bidang & Perkembangan Usaha',
            'slug' => 'bagian-2',
            'order' => 3,
            'is_required' => true,
            'is_general' => false,
            'time_estimate' => 7,
        ]);

        // Pertanyaan 4
        Question::create([
            'questionnaire_id' => $bagian2->id,
            'question_text' => 'Bidang/jenis usaha yang dijalankan? (Contoh: Kuliner, IT Konsultan, Retail Fashion)',
            'question_type' => 'text',
            'is_required' => true,
            'order' => 1,
            'placeholder' => 'Contoh: Kuliner, Retail Fashion, IT Konsultan',
        ]);

        // Pertanyaan 5
        Question::create([
            'questionnaire_id' => $bagian2->id,
            'question_text' => 'Tingkat perkembangan usaha?',
            'question_type' => 'radio',
            'options' => [
                'Lokal (Belum berbadan hukum)',
                'Nasional (Sudah berbadan hukum: PT/CV)',
                'Memiliki jaringan/ekspor internasional'
            ],
            'is_required' => true,
            'order' => 2,
        ]);

        $questionnaires[] = $bagian2;
        
        // ============ BAGIAN 3: Relevansi Studi dengan Usaha ============
        $bagian3 = Questionnaire::create([
            'category_id' => $category->id,
            'name' => 'Bagian 3: Relevansi Studi dengan Usaha',
            'slug' => 'bagian-3',
            'order' => 4,
            'is_required' => true,
            'is_general' => false,
            'time_estimate' => 5,
        ]);

        // Pertanyaan 6
        Question::create([
            'questionnaire_id' => $bagian3->id,
            'question_text' => 'Seberapa erat hubungan bidang studi dengan usaha ini?',
            'question_type' => 'radio',
            'options' => ['Sangat Erat', 'Erat', 'Cukup', 'Kurang', 'Tidak Sama Sekali'],
            'is_required' => true,
            'order' => 1,
        ]);

        $questionnaires[] = $bagian3;
        
        // ============ BAGIAN 4: Kompetensi & Peran Kampus ============
        $bagian4 = Questionnaire::create([
            'category_id' => $category->id,
            'name' => 'Bagian 4: Kompetensi & Peran Kampus',
            'slug' => 'bagian-4',
            'order' => 5,
            'is_required' => true,
            'is_general' => false,
            'time_estimate' => 8,
        ]);

        // Pertanyaan 7
        Question::create([
            'questionnaire_id' => $bagian4->id,
            'question_text' => 'Kompetensi spesifik apa yang Anda kembangkan setelah lulus yang paling penting dalam berwirausaha?',
            'question_type' => 'textarea',
            'is_required' => true,
            'order' => 1,
            'rows' => 5,
            'placeholder' => 'Contoh: \n- Manajemen keuangan\n- Digital marketing\n- Networking',
        ]);

        // Pertanyaan 8
        Question::create([
            'questionnaire_id' => $bagian4->id,
            'question_text' => 'Seberapa besar peran kampus (kuliah, pelatihan, inkubasi) mempersiapkan Anda berwirausaha?',
            'question_type' => 'radio',
            'options' => ['Sangat Besar', 'Besar', 'Cukup', 'Kurang', 'Tidak Sama Sekali'],
            'is_required' => true,
            'order' => 2,
        ]);

        $questionnaires[] = $bagian4;

        return $questionnaires;
    }

    private function createPendidikanQuestionnaires($category): array
    {
        $questionnaires = [];
        
        // ============ BAGIAN 1: Pendanaan & Identitas Studi ============
        $bagian1 = Questionnaire::create([
            'category_id' => $category->id,
            'name' => 'Bagian 1: Pendanaan & Identitas Studi',
            'slug' => 'bagian-1',
            'description' => 'Informasi pendanaan dan identitas studi lanjut',
            'order' => 2,
            'is_required' => true,
            'is_general' => false,
            'time_estimate' => 8,
        ]);

        // Pertanyaan 1
        Question::create([
            'questionnaire_id' => $bagian1->id,
            'question_text' => 'Sebutkan sumber dana utama pembiayaan kuliah Anda?',
            'question_type' => 'dropdown',
            'options' => [
                'Biaya Sendiri/Keluarga',
                'Beasiswa ADIK',
                'Beasiswa BIDIKMISI',
                'Beasiswa PPA',
                'Beasiswa AFIRMASI',
                'Beasiswa Perusahaan/Swasta',
            ],
            'has_other_option' => true,
            'is_required' => true,
            'order' => 1,
            'helper_text' => 'Pilih satu opsi yang sesuai',
        ]);

        // Pertanyaan 2
        Question::create([
            'questionnaire_id' => $bagian1->id,
            'question_text' => 'Nama Perguruan Tinggi tujuan?',
            'question_type' => 'text',
            'is_required' => true,
            'order' => 2,
            'placeholder' => 'Contoh: Universitas Gadjah Mada',
        ]);

        // Pertanyaan 3
        Question::create([
            'questionnaire_id' => $bagian1->id,
            'question_text' => 'Nama Program Studi yang ditempuh?',
            'question_type' => 'text',
            'is_required' => true,
            'order' => 3,
            'placeholder' => 'Contoh: Magister Ilmu Komputer',
        ]);

        $questionnaires[] = $bagian1;
        
        // ============ BAGIAN 2: Kronologi & Jenjang Studi ============
        $bagian2 = Questionnaire::create([
            'category_id' => $category->id,
            'name' => 'Bagian 2: Kronologi & Jenjang Studi',
            'slug' => 'bagian-2',
            'order' => 3,
            'is_required' => true,
            'is_general' => false,
            'time_estimate' => 5,
        ]);

        // Pertanyaan 4
        Question::create([
            'questionnaire_id' => $bagian2->id,
            'question_text' => 'Tanggal mulai studi lanjut?',
            'question_type' => 'date',
            'is_required' => true,
            'order' => 1,
            'placeholder' => 'Pilih tanggal',
        ]);

        // Pertanyaan 5
        Question::create([
            'questionnaire_id' => $bagian2->id,
            'question_text' => 'Jenjang pendidikan yang sedang ditempuh?',
            'question_type' => 'radio',
            'options' => ['Profesi/Spesialis', 'Magister (S2)', 'Doktor (S3)'],
            'is_required' => true,
            'order' => 2,
        ]);

        $questionnaires[] = $bagian2;
        
        // ============ BAGIAN 3: Relevansi & Dukungan Kurikulum ============
        $bagian3 = Questionnaire::create([
            'category_id' => $category->id,
            'name' => 'Bagian 3: Relevansi & Dukungan Kurikulum',
            'slug' => 'bagian-3',
            'order' => 4,
            'is_required' => true,
            'is_general' => false,
            'time_estimate' => 7,
        ]);

        // Pertanyaan 6
        Question::create([
            'questionnaire_id' => $bagian3->id,
            'question_text' => 'Seberapa erat hubungan bidang studi S1 dengan studi lanjut ini?',
            'question_type' => 'radio',
            'options' => ['Sangat Erat', 'Erat', 'Cukup', 'Kurang', 'Tidak Sama Sekali'],
            'is_required' => true,
            'order' => 1,
        ]);

        // Pertanyaan 7
        Question::create([
            'questionnaire_id' => $bagian3->id,
            'question_text' => 'Tuliskan 3 (tiga) mata kuliah yang mendukung pencapaian studi saat ini!',
            'question_type' => 'textarea',
            'is_required' => true,
            'order' => 2,
            'rows' => 4,
            'placeholder' => 'Tuliskan setiap mata kuliah dalam baris terpisah',
        ]);

        $questionnaires[] = $bagian3;
        
        // ============ BAGIAN 4: Rencana Masa Depan ============
        $bagian4 = Questionnaire::create([
            'category_id' => $category->id,
            'name' => 'Bagian 4: Rencana Masa Depan',
            'slug' => 'bagian-4',
            'order' => 5,
            'is_required' => true,
            'is_general' => false,
            'time_estimate' => 5,
        ]);

        // Pertanyaan 8
        Question::create([
            'questionnaire_id' => $bagian4->id,
            'question_text' => 'Rencana utama setelah lulus dari studi lanjut ini?',
            'question_type' => 'radio',
            'options' => [
                'Bekerja',
                'Berwirausaha',
                'Lanjut studi lagi',
                'Menjadi peneliti/akademisi',
                'Belum pasti'
            ],
            'is_required' => true,
            'order' => 1,
        ]);

        $questionnaires[] = $bagian4;

        return $questionnaires;
    }

    private function createPencariQuestionnaires($category): array
    {
        $questionnaires = [];
        
        // ============ BAGIAN 1: Status & Waktu Mencari ============
        $bagian1 = Questionnaire::create([
            'category_id' => $category->id,
            'name' => 'Bagian 1: Status & Waktu Mencari',
            'slug' => 'bagian-1',
            'description' => 'Status pencarian pekerjaan',
            'order' => 2,
            'is_required' => true,
            'is_general' => false,
            'time_estimate' => 6,
        ]);

        // Pertanyaan 1
        Question::create([
            'questionnaire_id' => $bagian1->id,
            'question_text' => 'Apakah Anda aktif mencari pekerjaan dalam 4 minggu terakhir?',
            'question_type' => 'radio',
            'options' => [
                'Tidak',
                'Tidak, tapi sedang menunggu hasil lamaran',
                'Ya, saya akan mulai bekerja dalam 2 minggu',
                'Ya, tapi belum pasti bekerja dalam 2 minggu'
            ],
            'is_required' => true,
            'order' => 1,
        ]);

        // Pertanyaan 2
        Question::create([
            'questionnaire_id' => $bagian1->id,
            'question_text' => 'Kapan Anda mulai mencari pekerjaan?',
            'question_type' => 'radio',
            'options' => ['Sebelum Lulus', 'Sesudah Lulus'],
            'is_required' => true,
            'order' => 2,
        ]);

        // Pertanyaan 3
        Question::create([
            'questionnaire_id' => $bagian1->id,
            'question_text' => 'Berapa bulan (sebelum/sesudah lulus) Anda mulai mencari?',
            'question_type' => 'number',
            'is_required' => true,
            'order' => 3,
            'min_value' => 0,
            'max_value' => 36,
            'placeholder' => 'Masukkan angka (bulan)',
        ]);

        $questionnaires[] = $bagian1;
        
        // ============ BAGIAN 2: Strategi & Hasil Lamaran ============
        $bagian2 = Questionnaire::create([
            'category_id' => $category->id,
            'name' => 'Bagian 2: Strategi & Hasil Lamaran',
            'slug' => 'bagian-2',
            'order' => 3,
            'is_required' => true,
            'is_general' => false,
            'time_estimate' => 8,
        ]);

        // Pertanyaan 4
        Question::create([
            'questionnaire_id' => $bagian2->id,
            'question_text' => 'Bagaimana cara Anda mencari pekerjaan? (Boleh lebih dari satu)',
            'question_type' => 'checkbox',
            'options' => [
                'Lowongan di internet',
                'Melalui kenalan/relasi',
                'Mengunjungi perusahaan langsung',
                'Melalui perusahaan pencari kerja',
                'Membuat lamaran langsung',
                'Mengikuti job fair',
                'Melalui sosial media/LinkedIn',
                'Melalui iklan di media cetak'
            ],
            'allow_multiple_selection' => true,
            'has_other_option' => true,
            'is_required' => true,
            'order' => 1,
        ]);

        // Pertanyaan 5
        Question::create([
            'questionnaire_id' => $bagian2->id,
            'question_text' => 'Berapa banyak perusahaan yang telah Anda lamar?',
            'question_type' => 'number',
            'is_required' => true,
            'order' => 2,
            'min_value' => 0,
            'placeholder' => 'Masukkan jumlah perusahaan',
        ]);

        $questionnaires[] = $bagian2;
        
        // ============ BAGIAN 3: Respons & Proses Seleksi ============
        $bagian3 = Questionnaire::create([
            'category_id' => $category->id,
            'name' => 'Bagian 3: Respons & Proses Seleksi',
            'slug' => 'bagian-3',
            'order' => 4,
            'is_required' => true,
            'is_general' => false,
            'time_estimate' => 6,
        ]);

        // Pertanyaan 6
        Question::create([
            'questionnaire_id' => $bagian3->id,
            'question_text' => 'Berapa banyak yang merespons?',
            'question_type' => 'number',
            'is_required' => true,
            'order' => 1,
            'min_value' => 0,
            'placeholder' => 'Masukkan jumlah respons',
        ]);

        // Pertanyaan 7
        Question::create([
            'questionnaire_id' => $bagian3->id,
            'question_text' => 'Berapa banyak yang mengundang wawancara?',
            'question_type' => 'number',
            'is_required' => true,
            'order' => 2,
            'min_value' => 0,
            'placeholder' => 'Masukkan jumlah undangan wawancara',
        ]);

        $questionnaires[] = $bagian3;
        
        // ============ BAGIAN 4: Hambatan & Harapan ============
        $bagian4 = Questionnaire::create([
            'category_id' => $category->id,
            'name' => 'Bagian 4: Hambatan & Harapan',
            'slug' => 'bagian-4',
            'order' => 5,
            'is_required' => true,
            'is_general' => false,
            'time_estimate' => 10,
        ]);

        // Pertanyaan 8
        Question::create([
            'questionnaire_id' => $bagian4->id,
            'question_text' => 'Menurut Anda, apa hambatan utama dalam mencari pekerjaan? (Boleh lebih dari satu)',
            'question_type' => 'checkbox',
            'options' => [
                'Kurangnya lowongan di bidang saya',
                'Persaingan ketat',
                'Kurang pengalaman kerja',
                'Kemampuan bahasa asing kurang',
                'Keterampilan teknis kurang',
                'Tidak memiliki jaringan/koneksi',
                'Lokasi yang diinginkan terbatas',
            ],
            'allow_multiple_selection' => true,
            'has_other_option' => true,
            'is_required' => true,
            'order' => 1,
        ]);

        // Pertanyaan 9
        Question::create([
            'questionnaire_id' => $bagian4->id,
            'question_text' => 'Jenis pekerjaan seperti apa yang Anda cari?',
            'question_type' => 'textarea',
            'is_required' => true,
            'order' => 2,
            'rows' => 4,
            'placeholder' => 'Deskripsikan jenis pekerjaan yang Anda inginkan',
        ]);

        // Pertanyaan 10
        Question::create([
            'questionnaire_id' => $bagian4->id,
            'question_text' => 'Apa harapan Anda terhadap kampus dalam membantu alumni mencari kerja?',
            'question_type' => 'textarea',
            'is_required' => true,
            'order' => 3,
            'rows' => 4,
            'placeholder' => 'Tuliskan harapan Anda',
        ]);

        $questionnaires[] = $bagian4;

        return $questionnaires;
    }

    private function createTidakKerjaQuestionnaires($category): array
    {
        $questionnaires = [];
        
        // ============ BAGIAN 1: Alasan & Rencana ============
        $bagian1 = Questionnaire::create([
            'category_id' => $category->id,
            'name' => 'Bagian 1: Alasan & Rencana',
            'slug' => 'bagian-1',
            'description' => 'Alasan tidak bekerja dan rencana ke depan',
            'order' => 2,
            'is_required' => true,
            'is_general' => false,
            'time_estimate' => 6,
        ]);

        // Pertanyaan 1
        Question::create([
            'questionnaire_id' => $bagian1->id,
            'question_text' => 'Apa alasan utama Anda belum memungkinkan untuk bekerja/mencari kerja?',
            'question_type' => 'radio',
            'options' => [
                'Mengurus rumah tangga/keluarga',
                'Kesehatan (diri/sendiri)',
                'Menunggu panggilan/nominasi (e.g., CPNS, PPPK)',
                'Mempersiapkan diri untuk studi lanjut',
                'Mempersiapkan usaha/wirausaha',
            ],
            'has_other_option' => true,
            'is_required' => true,
            'order' => 1,
        ]);

        // Pertanyaan 2
        Question::create([
            'questionnaire_id' => $bagian1->id,
            'question_text' => 'Apakah Anda berencana untuk masuk ke dunia kerja (bekerja/wirausaha) dalam 1 tahun ke depan?',
            'question_type' => 'radio',
            'options' => ['Ya', 'Tidak', 'Belum pasti'],
            'is_required' => true,
            'order' => 2,
        ]);

        $questionnaires[] = $bagian1;
        
        // ============ BAGIAN 2: Analisis Kebutuhan Kompetensi ============
        $bagian2 = Questionnaire::create([
            'category_id' => $category->id,
            'name' => 'Bagian 2: Analisis Kebutuhan Kompetensi',
            'slug' => 'bagian-2',
            'order' => 3,
            'is_required' => true,
            'is_general' => false,
            'time_estimate' => 8,
        ]);

        // Pertanyaan 3
        Question::create([
            'questionnaire_id' => $bagian2->id,
            'question_text' => 'Menurut Anda, tingkat kebutuhan kompetensi berikut jika Anda akan bekerja/wirausaha nanti? (Skala 1-5, 1=Sangat Rendah, 5=Sangat Tinggi)',
            'question_type' => 'likert_per_row',
            'row_items' => [
                'ethics' => 'Etika',
                'expertise' => 'Keahlian Bidang Ilmu',
                'english' => 'Bahasa Inggris',
                'it_skills' => 'Penggunaan IT',
                'communication' => 'Komunikasi',
                'teamwork' => 'Kerja Sama Tim',
                'self_development' => 'Pengembangan Diri',
            ],
            'scale_options' => [1, 2, 3, 4, 5],
            'scale_label_low' => 'Sangat Rendah',
            'scale_label_high' => 'Sangat Tinggi',
            'is_required' => true,
            'order' => 1,
            'helper_text' => 'Pilih skala 1-5 untuk setiap kompetensi (dalam konteks rencana masa depan)',
        ]);

        $questionnaires[] = $bagian2;
        
        // ============ BAGIAN 3: Pengembangan & Dukungan ============
        $bagian3 = Questionnaire::create([
            'category_id' => $category->id,
            'name' => 'Bagian 3: Pengembangan & Dukungan',
            'slug' => 'bagian-3',
            'order' => 4,
            'is_required' => true,
            'is_general' => false,
            'time_estimate' => 7,
        ]);

        // Pertanyaan 4
        Question::create([
            'questionnaire_id' => $bagian3->id,
            'question_text' => 'Kompetensi apa yang sedang/telah Anda kembangkan selama periode ini?',
            'question_type' => 'textarea',
            'is_required' => true,
            'order' => 1,
            'rows' => 5,
            'placeholder' => 'Tuliskan kompetensi yang sedang/telah dikembangkan',
        ]);

        // Pertanyaan 5
        Question::create([
            'questionnaire_id' => $bagian3->id,
            'question_text' => 'Dukungan atau informasi apa dari kampus yang dapat membantu situasi Anda? (Boleh lebih dari satu)',
            'question_type' => 'checkbox',
            'options' => [
                'Informasi lowongan kerja',
                'Pelatihan keterampilan teknis',
                'Konseling karir',
                'Informasi beasiswa studi lanjut',
                'Pelatihan kewirausahaan',
                'Informasi komunitas/alumni',
            ],
            'allow_multiple_selection' => true,
            'has_other_option' => true,
            'is_required' => true,
            'order' => 2,
        ]);

        $questionnaires[] = $bagian3;
        
        // ============ BAGIAN 4: Kesiapan Dihubungi ============
        $bagian4 = Questionnaire::create([
            'category_id' => $category->id,
            'name' => 'Bagian 4: Kesiapan Dihubungi',
            'slug' => 'bagian-4',
            'order' => 5,
            'is_required' => true,
            'is_general' => false,
            'time_estimate' => 5,
        ]);

        // Pertanyaan 6
        Question::create([
            'questionnaire_id' => $bagian4->id,
            'question_text' => 'Apakah Anda bersedia dihubungi jika ada kesempatan yang sesuai?',
            'question_type' => 'radio',
            'options' => [
                'Ya, email: _____',
                'Ya, nomor WhatsApp: _____',
                'Tidak'
            ],
            'is_required' => true,
            'order' => 1,
            'helper_text' => 'Jika memilih Ya, silakan isi email atau nomor WhatsApp',
        ]);

        $questionnaires[] = $bagian4;

        return $questionnaires;
    }

    private function createSequences($category, $general, $specifics): void
    {
        $order = 1;
        
        // Urutan: Bagian Umum dulu
        QuestionnaireSequence::create([
            'category_id' => $category->id,
            'questionnaire_id' => $general->id,
            'order' => $order++,
            'is_required' => true,
            'unlocks_next' => true,
        ]);
        
        // Kemudian bagian spesifik
        foreach ($specifics as $specific) {
            QuestionnaireSequence::create([
                'category_id' => $category->id,
                'questionnaire_id' => $specific->id,
                'order' => $order++,
                'is_required' => true,
                'unlocks_next' => true,
            ]);
        }
    }
}