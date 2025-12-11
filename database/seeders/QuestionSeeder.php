<?php

namespace Database\Seeders;

use App\Models\Question;
use App\Models\QuestionOption;
use App\Models\Questionnaire;
use Illuminate\Database\Seeder;

class QuestionSeeder extends Seeder
{
    public function run(): void
    {
        // Kosongkan tabel terlebih dahulu
        Question::truncate();
        QuestionOption::truncate();

        // Ambil semua kuesioner
        $questionnaires = Questionnaire::all();

        foreach ($questionnaires as $questionnaire) {
            // Tentukan pertanyaan berdasarkan urutan kuesioner
            switch ($questionnaire->order) {
                case 1: // Kuesioner 1 (Umum)
                    $this->seedGeneralQuestionnaire($questionnaire);
                    break;

                case 2: // Kuesioner 2
                    $this->seedQuestionnaire2($questionnaire);
                    break;

                case 3: // Kuesioner 3
                    $this->seedQuestionnaire3($questionnaire);
                    break;

                case 4: // Kuesioner 4
                    $this->seedQuestionnaire4($questionnaire);
                    break;
            }
        }

        $this->command->info('Questions and options seeded successfully!');
        $this->command->info('Total questions created: ' . Question::count());
        $this->command->info('Total options created: ' . QuestionOption::count());
    }

    /**
     * Seed questions for General Questionnaire (Kuesioner 1).
     */
    private function seedGeneralQuestionnaire(Questionnaire $questionnaire): void
    {
        // ================= PERTANYAAN 1: F8 =================
        $question1 = Question::create([
            'questionnaire_id' => $questionnaire->id,
            'code' => 'F8',
            'question_text' => 'Jelaskan status Anda saat ini?',
            'description' => 'Pertanyaan Pemisah Rute - Pilih satu opsi yang sesuai',
            'type' => 'dropdown',
            'order' => 1,
            'is_required' => true,
            'has_other_option' => false,
            'validation_rules' => null,
        ]);

        // Opsi untuk F8
        $f8Options = [
            ['option_text' => 'Bekerja (full time/part time) di perusahaan/instansi', 'value' => 1, 'order' => 1],
            ['option_text' => 'Wiraswasta/Pemilik Usaha', 'value' => 2, 'order' => 2],
            ['option_text' => 'Melanjutkan Pendidikan', 'value' => 3, 'order' => 3],
            ['option_text' => 'Tidak Kerja, tetapi sedang mencari kerja', 'value' => 4, 'order' => 4],
            ['option_text' => 'Belum memungkinkan bekerja / Tidak mencari kerja', 'value' => 5, 'order' => 5],
        ];

        foreach ($f8Options as $option) {
            QuestionOption::create(array_merge($option, ['question_id' => $question1->id]));
        }

        // ================= PERTANYAAN 2: F502 =================
        $question2 = Question::create([
            'questionnaire_id' => $questionnaire->id,
            'code' => 'F502',
            'question_text' => 'Berapa lama Anda mendapat pekerjaan sebagai karyawan/wirausaha pertama kali?',
            'description' => 'Pilih satu opsi yang sesuai',
            'type' => 'radio',
            'order' => 2,
            'is_required' => false, // Tidak wajib, tergantung jawaban F8
            'has_other_option' => false,
            'validation_rules' => null,
        ]);

        // Opsi untuk F502
        $f502Options = [
            ['option_text' => 'Belum mendapat pekerjaan', 'value' => 1, 'order' => 1],
            ['option_text' => '0-<3 bulan', 'value' => 2, 'order' => 2],
            ['option_text' => '3-<6 bulan', 'value' => 3, 'order' => 3],
            ['option_text' => '6-<9 bulan', 'value' => 4, 'order' => 4],
            ['option_text' => '9-<12 bulan', 'value' => 5, 'order' => 5],
            ['option_text' => '>12 bulan', 'value' => 6, 'order' => 6],
        ];

        foreach ($f502Options as $option) {
            QuestionOption::create(array_merge($option, ['question_id' => $question2->id]));
        }

        // ================= PERTANYAAN 3: F12 =================
        $question3 = Question::create([
            'questionnaire_id' => $questionnaire->id,
            'code' => 'F12',
            'question_text' => 'Sebutkan sumber dana utama pembiayaan kuliah S1 Anda?',
            'description' => '',
            'type' => 'dropdown',
            'order' => 3,
            'is_required' => true,
            'has_other_option' => true,
            'validation_rules' => null,
        ]);

        // Opsi untuk F12
        $f12Options = [
            ['option_text' => 'Biaya Sendiri/Keluarga', 'value' => 1, 'order' => 1],
            ['option_text' => 'Beasiswa ADIK', 'value' => 2, 'order' => 2],
            ['option_text' => 'Beasiswa BIDIKMISI', 'value' => 3, 'order' => 3],
            ['option_text' => 'Beasiswa PPA', 'value' => 4, 'order' => 4],
            ['option_text' => 'Beasiswa AFIRMASI', 'value' => 5, 'order' => 5],
            ['option_text' => 'Beasiswa Perusahaan/Swasta', 'value' => 6, 'order' => 6],
            ['option_text' => 'Lainnya, sebutkan!', 'value' => 999, 'order' => 7],
        ];

        foreach ($f12Options as $option) {
            QuestionOption::create(array_merge($option, ['question_id' => $question3->id]));
        }

        // ================= PERTANYAAN 4: F17.A =================
        $question4 = Question::create([
            'questionnaire_id' => $questionnaire->id,
            'code' => 'F17.A',
            'question_text' => 'Pada saat lulus, pada tingkat mana Anda menguasai kompetensi berikut?',
            'description' => '1 = Sangat Rendah, 5 = Sangat Tinggi',
            'type' => 'competency_scale',
            'order' => 4,
            'is_required' => true,
            'has_other_option' => false,
            'validation_rules' => null,
        ]);

        // Opsi skala untuk F17.A (1-5)
        $scaleOptions = [
            ['option_text' => 'Sangat Rendah', 'value' => 1, 'label' => 'Sangat Rendah', 'order' => 1],
            ['option_text' => 'Rendah', 'value' => 2, 'label' => 'Rendah', 'order' => 2],
            ['option_text' => 'Cukup', 'value' => 3, 'label' => 'Cukup', 'order' => 3],
            ['option_text' => 'Tinggi', 'value' => 4, 'label' => 'Tinggi', 'order' => 4],
            ['option_text' => 'Sangat Tinggi', 'value' => 5, 'label' => 'Sangat Tinggi', 'order' => 5],
        ];

        foreach ($scaleOptions as $option) {
            QuestionOption::create(array_merge($option, ['question_id' => $question4->id]));
        }

        // Item kompetensi (disimpan sebagai opsi tambahan untuk referensi)
        $competencyItems = [
            'Etika',
            'Keahlian Bidang Ilmu',
            'Bahasa Inggris',
            'Penggunaan IT',
            'Komunikasi',
            'Kerja Sama Tim',
            'Pengembangan Diri',
        ];

        foreach ($competencyItems as $index => $item) {
            QuestionOption::create([
                'question_id' => $question4->id,
                'option_text' => $item,
                'value' => $index + 100, // Value khusus untuk item kompetensi
                'order' => $index + 6, // Setelah scale options
            ]);
        }

        // ================= PERTANYAAN 5: F21-F27 =================
        $question5 = Question::create([
            'questionnaire_id' => $questionnaire->id,
            'code' => 'F21-F27',
            'question_text' => 'Menurut Anda, seberapa besar penekanan metode pembelajaran berikut di prodi Anda?',
            'description' => '',
            'type' => 'matrix',
            'order' => 5,
            'is_required' => true,
            'has_other_option' => false,
            'validation_rules' => null,
        ]);

        // Opsi untuk metode pembelajaran (baris)
        $learningMethods = [
            'Perkuliahan',
            'Demonstrasi',
            'Partisipasi Proyek Riset',
            'Magang',
            'Praktikum',
            'Kerja Lapangan',
            'Diskusi',
        ];

        foreach ($learningMethods as $index => $method) {
            QuestionOption::create([
                'question_id' => $question5->id,
                'option_text' => $method,
                'value' => $index + 1,
                'order' => $index + 1,
            ]);
        }

        // Opsi skala untuk kolom (disimpan sebagai opsi terpisah)
        $scaleLabels = [
            'Sangat Besar',
            'Besar',
            'Cukup',
            'Kurang',
            'Tidak Sama Sekali',
        ];

        foreach ($scaleLabels as $index => $label) {
            QuestionOption::create([
                'question_id' => $question5->id,
                'option_text' => $label,
                'value' => $index + 1,
                'label' => $label,
                'order' => $index + 8, // Setelah learning methods
            ]);
        }

        // ================= PERTANYAAN 6: F30 (Opsional untuk General) =================
        $question6 = Question::create([
            'questionnaire_id' => $questionnaire->id,
            'code' => 'F30',
            'question_text' => 'Seberapa relevan pekerjaan Anda saat ini dengan bidang studi Anda?',
            'description' => '1 = Tidak Relevan, 5 = Sangat Relevan',
            'type' => 'scale',
            'order' => 6,
            'is_required' => false,
            'has_other_option' => false,
            'validation_rules' => null,
        ]);

        // Opsi skala untuk F30
        $relevanceScale = [
            ['option_text' => 'Tidak Relevan', 'value' => 1, 'label' => 'Tidak Relevan', 'order' => 1],
            ['option_text' => 'Kurang Relevan', 'value' => 2, 'label' => 'Kurang Relevan', 'order' => 2],
            ['option_text' => 'Cukup Relevan', 'value' => 3, 'label' => 'Cukup Relevan', 'order' => 3],
            ['option_text' => 'Relevan', 'value' => 4, 'label' => 'Relevan', 'order' => 4],
            ['option_text' => 'Sangat Relevan', 'value' => 5, 'label' => 'Sangat Relevan', 'order' => 5],
        ];

        foreach ($relevanceScale as $option) {
            QuestionOption::create(array_merge($option, ['question_id' => $question6->id]));
        }
    }

    /**
     * Seed questions for Questionnaire 2.
     */
    private function seedQuestionnaire2(Questionnaire $questionnaire): void
    {
        // ================= PERTANYAAN 1: Posisi/Jabatan =================
        $question1 = Question::create([
            'questionnaire_id' => $questionnaire->id,
            'code' => null,
            'question_text' => 'Apa posisi/jabatan pertama Anda setelah lulus?',
            'description' => '',
            'type' => 'text',
            'order' => 1,
            'is_required' => true,
            'has_other_option' => false,
            'validation_rules' => ['min:2', 'max:100'],
        ]);

        // ================= PERTANYAAN 2: Nama Perusahaan =================
        $question2 = Question::create([
            'questionnaire_id' => $questionnaire->id,
            'code' => null,
            'question_text' => 'Di perusahaan/instansi mana Anda pertama kali bekerja?',
            'description' => '',
            'type' => 'text',
            'order' => 2,
            'is_required' => true,
            'has_other_option' => false,
            'validation_rules' => ['min:2', 'max:150'],
        ]);

        // ================= PERTANYAAN 3: F31 - Gaji =================
        $question3 = Question::create([
            'questionnaire_id' => $questionnaire->id,
            'code' => 'F31',
            'question_text' => 'Berapa gaji/penghasilan pertama Anda setelah lulus?',
            'description' => 'Dalam Rupiah per bulan',
            'type' => 'number',
            'order' => 3,
            'is_required' => false,
            'has_other_option' => false,
            'validation_rules' => ['numeric', 'min:0', 'max:1000000000'],
        ]);

        // ================= PERTANYAAN 4: Jenis Pekerjaan =================
        $question4 = Question::create([
            'questionnaire_id' => $questionnaire->id,
            'code' => null,
            'question_text' => 'Apa jenis pekerjaan Anda saat ini?',
            'description' => '',
            'type' => 'dropdown',
            'order' => 4,
            'is_required' => true,
            'has_other_option' => true,
            'validation_rules' => null,
        ]);

        // Opsi untuk jenis pekerjaan
        $jobTypes = [
            ['option_text' => 'Pegawai Negeri Sipil (PNS)', 'value' => 1, 'order' => 1],
            ['option_text' => 'Pegawai BUMN', 'value' => 2, 'order' => 2],
            ['option_text' => 'Pegawai Swasta', 'value' => 3, 'order' => 3],
            ['option_text' => 'Wirausaha', 'value' => 4, 'order' => 4],
            ['option_text' => 'Freelancer/Kontrak', 'value' => 5, 'order' => 5],
            ['option_text' => 'Lainnya, sebutkan!', 'value' => 999, 'order' => 6],
        ];

        foreach ($jobTypes as $option) {
            QuestionOption::create(array_merge($option, ['question_id' => $question4->id]));
        }

        // ================= PERTANYAAN 5: Tingkat Kepuasan =================
        $question5 = Question::create([
            'questionnaire_id' => $questionnaire->id,
            'code' => null,
            'question_text' => 'Seberapa puas Anda dengan pekerjaan pertama Anda?',
            'description' => '1 = Sangat Tidak Puas, 5 = Sangat Puas',
            'type' => 'scale',
            'order' => 5,
            'is_required' => true,
            'has_other_option' => false,
            'validation_rules' => null,
        ]);

        // Opsi skala kepuasan
        $satisfactionScale = [
            ['option_text' => 'Sangat Tidak Puas', 'value' => 1, 'label' => 'Sangat Tidak Puas', 'order' => 1],
            ['option_text' => 'Tidak Puas', 'value' => 2, 'label' => 'Tidak Puas', 'order' => 2],
            ['option_text' => 'Cukup Puas', 'value' => 3, 'label' => 'Cukup Puas', 'order' => 3],
            ['option_text' => 'Puas', 'value' => 4, 'label' => 'Puas', 'order' => 4],
            ['option_text' => 'Sangat Puas', 'value' => 5, 'label' => 'Sangat Puas', 'order' => 5],
        ];

        foreach ($satisfactionScale as $option) {
            QuestionOption::create(array_merge($option, ['question_id' => $question5->id]));
        }
    }

    /**
     * Seed questions for Questionnaire 3.
     */
    private function seedQuestionnaire3(Questionnaire $questionnaire): void
    {
        // ================= PERTANYAAN 1: Keterampilan Bermanfaat =================
        $question1 = Question::create([
            'questionnaire_id' => $questionnaire->id,
            'code' => null,
            'question_text' => 'Keterampilan apa yang paling bermanfaat dari pendidikan di UAD?',
            'description' => 'Pilih maksimal 3 opsi',
            'type' => 'checkbox',
            'order' => 1,
            'is_required' => true,
            'has_other_option' => true,
            'validation_rules' => ['array', 'max:3'],
        ]);

        // Opsi keterampilan
        $skills = [
            ['option_text' => 'Pemrograman/Software Development', 'value' => 1, 'order' => 1],
            ['option_text' => 'Analisis Data/Data Science', 'value' => 2, 'order' => 2],
            ['option_text' => 'Jaringan Komputer', 'value' => 3, 'order' => 3],
            ['option_text' => 'Basis Data', 'value' => 4, 'order' => 4],
            ['option_text' => 'Manajemen Proyek TI', 'value' => 5, 'order' => 5],
            ['option_text' => 'Komunikasi dan Presentasi', 'value' => 6, 'order' => 6],
            ['option_text' => 'Kerja Tim dan Kolaborasi', 'value' => 7, 'order' => 7],
            ['option_text' => 'Pemecahan Masalah', 'value' => 8, 'order' => 8],
            ['option_text' => 'Lainnya', 'value' => 999, 'order' => 9],
        ];

        foreach ($skills as $option) {
            QuestionOption::create(array_merge($option, ['question_id' => $question1->id]));
        }

        // ================= PERTANYAAN 2: Tingkat Persiapan =================
        $question2 = Question::create([
            'questionnaire_id' => $questionnaire->id,
            'code' => null,
            'question_text' => 'Seberapa baik pendidikan di UAD mempersiapkan Anda untuk dunia kerja?',
            'description' => '1 = Sangat Tidak Baik, 5 = Sangat Baik',
            'type' => 'scale',
            'order' => 2,
            'is_required' => true,
            'has_other_option' => false,
            'validation_rules' => null,
        ]);

        // Opsi skala persiapan
        $preparationScale = [
            ['option_text' => 'Sangat Tidak Baik', 'value' => 1, 'label' => 'Sangat Tidak Baik', 'order' => 1],
            ['option_text' => 'Tidak Baik', 'value' => 2, 'label' => 'Tidak Baik', 'order' => 2],
            ['option_text' => 'Cukup Baik', 'value' => 3, 'label' => 'Cukup Baik', 'order' => 3],
            ['option_text' => 'Baik', 'value' => 4, 'label' => 'Baik', 'order' => 4],
            ['option_text' => 'Sangat Baik', 'value' => 5, 'label' => 'Sangat Baik', 'order' => 5],
        ];

        foreach ($preparationScale as $option) {
            QuestionOption::create(array_merge($option, ['question_id' => $question2->id]));
        }

        // ================= PERTANYAAN 3: Fasilitas Kampus =================
        $question3 = Question::create([
            'questionnaire_id' => $questionnaire->id,
            'code' => null,
            'question_text' => 'Bagaimana tingkat kepuasan Anda terhadap fasilitas kampus?',
            'description' => 'Pilih skala 1-5',
            'type' => 'scale',
            'order' => 3,
            'is_required' => true,
            'has_other_option' => false,
            'validation_rules' => null,
        ]);

        // Opsi skala fasilitas (gunakan skala yang sama)
        foreach ($preparationScale as $option) {
            QuestionOption::create(array_merge($option, ['question_id' => $question3->id]));
        }

        // ================= PERTANYAAN 4: Saran Kurikulum =================
        $question4 = Question::create([
            'questionnaire_id' => $questionnaire->id,
            'code' => null,
            'question_text' => 'Saran Anda untuk pengembangan kurikulum program studi?',
            'description' => 'Tuliskan saran Anda',
            'type' => 'textarea',
            'order' => 4,
            'is_required' => false,
            'has_other_option' => false,
            'validation_rules' => ['max:1000'],
        ]);

        // ================= PERTANYAAN 5: Mata Kuliah Paling Berguna =================
        $question5 = Question::create([
            'questionnaire_id' => $questionnaire->id,
            'code' => null,
            'question_text' => 'Mata kuliah apa yang paling berguna untuk karir Anda?',
            'description' => 'Sebutkan 1-3 mata kuliah',
            'type' => 'text',
            'order' => 5,
            'is_required' => false,
            'has_other_option' => false,
            'validation_rules' => ['max:200'],
        ]);
    }

    /**
     * Seed questions for Questionnaire 4.
     */
    private function seedQuestionnaire4(Questionnaire $questionnaire): void
    {
        // ================= PERTANYAAN 1: Rekomendasi =================
        $question1 = Question::create([
            'questionnaire_id' => $questionnaire->id,
            'code' => null,
            'question_text' => 'Apakah Anda akan merekomendasikan UAD kepada calon mahasiswa?',
            'description' => '',
            'type' => 'radio',
            'order' => 1,
            'is_required' => true,
            'has_other_option' => false,
            'validation_rules' => null,
        ]);

        // Opsi rekomendasi
        $recommendOptions = [
            ['option_text' => 'Ya, pasti', 'value' => 1, 'order' => 1],
            ['option_text' => 'Mungkin', 'value' => 2, 'order' => 2],
            ['option_text' => 'Tidak', 'value' => 3, 'order' => 3],
            ['option_text' => 'Tidak yakin', 'value' => 4, 'order' => 4],
        ];

        foreach ($recommendOptions as $option) {
            QuestionOption::create(array_merge($option, ['question_id' => $question1->id]));
        }

        // ================= PERTANYAAN 2: Alasan Rekomendasi =================
        $question2 = Question::create([
            'questionnaire_id' => $questionnaire->id,
            'code' => null,
            'question_text' => 'Alasan utama rekomendasi/tidak merekomendasikan?',
            'description' => 'Jelaskan secara singkat',
            'type' => 'textarea',
            'order' => 2,
            'is_required' => false,
            'has_other_option' => false,
            'validation_rules' => ['max:500'],
        ]);

        // ================= PERTANYAAN 3: Kontribusi ke Almamater =================
        $question3 = Question::create([
            'questionnaire_id' => $questionnaire->id,
            'code' => null,
            'question_text' => 'Seberapa besar kemungkinan Anda akan berkontribusi kepada almamater?',
            'description' => '1 = Sangat Kecil, 5 = Sangat Besar',
            'type' => 'scale',
            'order' => 3,
            'is_required' => true,
            'has_other_option' => false,
            'validation_rules' => null,
        ]);

        // Opsi skala kontribusi
        $contributionScale = [
            ['option_text' => 'Sangat Kecil', 'value' => 1, 'label' => 'Sangat Kecil', 'order' => 1],
            ['option_text' => 'Kecil', 'value' => 2, 'label' => 'Kecil', 'order' => 2],
            ['option_text' => 'Cukup Besar', 'value' => 3, 'label' => 'Cukup Besar', 'order' => 3],
            ['option_text' => 'Besar', 'value' => 4, 'label' => 'Besar', 'order' => 4],
            ['option_text' => 'Sangat Besar', 'value' => 5, 'label' => 'Sangat Besar', 'order' => 5],
        ];

        foreach ($contributionScale as $option) {
            QuestionOption::create(array_merge($option, ['question_id' => $question3->id]));
        }

        // ================= PERTANYAAN 4: Bentuk Kontribusi =================
        $question4 = Question::create([
            'questionnaire_id' => $questionnaire->id,
            'code' => null,
            'question_text' => 'Dalam bentuk apa Anda ingin berkontribusi?',
            'description' => 'Pilih semua yang sesuai',
            'type' => 'checkbox',
            'order' => 4,
            'is_required' => false,
            'has_other_option' => true,
            'validation_rules' => null,
        ]);

        // Opsi bentuk kontribusi
        $contributionTypes = [
            ['option_text' => 'Donasi Dana', 'value' => 1, 'order' => 1],
            ['option_text' => 'Menjadi Mentor/Mahasiswa', 'value' => 2, 'order' => 2],
            ['option_text' => 'Memberikan Kuliah Tamu', 'value' => 3, 'order' => 3],
            ['option_text' => 'Menerima Mahasiswa Magang', 'value' => 4, 'order' => 4],
            ['option_text' => 'Berbagi Lowongan Kerja', 'value' => 5, 'order' => 5],
            ['option_text' => 'Ikut Asosiasi Alumni', 'value' => 6, 'order' => 6],
            ['option_text' => 'Lainnya', 'value' => 999, 'order' => 7],
        ];

        foreach ($contributionTypes as $option) {
            QuestionOption::create(array_merge($option, ['question_id' => $question4->id]));
        }

        // ================= PERTANYAAN 5: Harapan ke Depan =================
        $question5 = Question::create([
            'questionnaire_id' => $questionnaire->id,
            'code' => null,
            'question_text' => 'Harapan Anda terhadap pengembangan UAD ke depan?',
            'description' => 'Tuliskan harapan Anda',
            'type' => 'textarea',
            'order' => 5,
            'is_required' => false,
            'has_other_option' => false,
            'validation_rules' => ['max:1000'],
        ]);
    }
}
