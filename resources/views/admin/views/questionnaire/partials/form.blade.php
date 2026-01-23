{{-- resources/views/admin/views/questionnaire/partials/form.blade.php --}}
@php
    // Helper function untuk format data dengan check function_exists
    if (!function_exists('formatQuestionOptions')) {
        function formatQuestionOptions($options)
        {
            if (!$options) {
                return '';
            }

            if (is_string($options)) {
                $options = json_decode($options, true) ?? [];
            }

            if (!is_array($options)) {
                return '';
            }

            $lines = [];
            foreach ($options as $key => $value) {
                if (is_array($value) && isset($value['text'])) {
                    $lines[] = $value['text'];
                } elseif (is_string($value)) {
                    $lines[] = $value;
                } elseif (is_numeric($key)) {
                    $lines[] = $value;
                } else {
                    $lines[] = $key . '|' . $value;
                }
            }

            return implode("\n", $lines);
        }
    }

    if (!function_exists('formatQuestionRowItems')) {
        function formatQuestionRowItems($rowItems)
        {
            if (!$rowItems) {
                return '';
            }

            if (is_string($rowItems)) {
                $rowItems = json_decode($rowItems, true) ?? [];
            }

            if (!is_array($rowItems)) {
                return '';
            }

            $lines = [];
            foreach ($rowItems as $key => $value) {
                if (is_array($value) && isset($value['text'])) {
                    $lines[] = $key . '|' . $value['text'];
                } elseif (is_string($value)) {
                    $lines[] = $key . '|' . $value;
                } else {
                    $lines[] = $key . '|' . $value;
                }
            }

            return implode("\n", $lines);
        }
    }

    if (!function_exists('formatQuestionScaleOptions')) {
        function formatQuestionScaleOptions($scaleOptions)
        {
            if (!$scaleOptions) {
                return '';
            }

            if (is_string($scaleOptions)) {
                $scaleOptions = json_decode($scaleOptions, true) ?? [];
            }

            if (!is_array($scaleOptions)) {
                return '';
            }

            return implode(', ', $scaleOptions);
        }
    }

    if (!function_exists('formatQuestionScaleInformation')) {
        function formatQuestionScaleInformation($scaleInformation)
        {
            if (!$scaleInformation) {
                return '';
            }

            if (is_string($scaleInformation)) {
                $scaleInformation = json_decode($scaleInformation, true) ?? [];
            }

            if (!is_array($scaleInformation)) {
                return '';
            }

            $lines = [];
            foreach ($scaleInformation as $key => $value) {
                $lines[] = $key . '|' . $value;
            }

            return implode("\n", $lines);
        }
    }

    // Format data untuk form
    $optionsText = '';
    $rowItemsText = '';
    $scaleOptionsText = '';
    $scaleInformationText = '';

    if (isset($question)) {
        $optionsText = formatQuestionOptions($question->options);
        $rowItemsText = formatQuestionRowItems($question->row_items);
        $scaleOptionsText = formatQuestionScaleOptions($question->scale_options);
        $scaleInformationText = formatQuestionScaleInformation($question->scale_information);
    }
@endphp

<style>
    #optionsContainer,
    #rowItemsContainer,
    #scaleOptionsContainer,
    #scaleInformationContainer,
    #otherNoneOptionsContainer,
    #additionalSettings {
        display: none;
    }
</style>

<form action="{{ isset($question) ? $routeUpdate : $routeStore }}" method="POST" id="questionForm" class="needs-validation"
    novalidate>
    @csrf
    @if (isset($question))
        @method('PUT')
    @endif

    <div class="row">
        <!-- Kolom Kiri - Informasi Dasar -->
        <div class="col-md-6">
            <div class="mb-4">
                <label class="form-label fw-bold">Teks Pertanyaan <span class="text-danger">*</span></label>
                <textarea class="form-control @error('question_text') is-invalid @enderror" name="question_text" rows="4" required
                    placeholder="Masukkan teks pertanyaan di sini (gunakan &lt;br&gt; untuk baris baru)" id="questionText">{{ old('question_text', $question->question_text ?? '') }}</textarea>
                <div class="form-text">
                    <i class="fas fa-info-circle"></i> Gunakan &lt;br&gt; untuk membuat baris baru dalam pertanyaan
                </div>
                @error('question_text')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>

            <div class="mb-4">
                <label class="form-label fw-bold">Tipe Pertanyaan <span class="text-danger">*</span></label>
                <select class="form-select @error('question_type') is-invalid @enderror" name="question_type"
                    id="questionType" required>
                    <option value="">Pilih Tipe Pertanyaan</option>
                    <optgroup label="Input Teks">
                        <option value="text"
                            {{ old('question_type', $question->question_type ?? '') == 'text' ? 'selected' : '' }}>
                            Teks Singkat (Short Text)
                        </option>
                        <option value="textarea"
                            {{ old('question_type', $question->question_type ?? '') == 'textarea' ? 'selected' : '' }}>
                            Teks Panjang (Paragraph)
                        </option>
                    </optgroup>
                    <optgroup label="Input Angka & Tanggal">
                        <option value="number"
                            {{ old('question_type', $question->question_type ?? '') == 'number' ? 'selected' : '' }}>
                            Angka (Number)
                        </option>
                        <option value="date"
                            {{ old('question_type', $question->question_type ?? '') == 'date' ? 'selected' : '' }}>
                            Tanggal (Date)
                        </option>
                    </optgroup>
                    <optgroup label="Pilihan Tunggal">
                        <option value="radio"
                            {{ old('question_type', $question->question_type ?? '') == 'radio' ? 'selected' : '' }}>
                            Radio Button (Pilih satu)
                        </option>
                        <option value="dropdown"
                            {{ old('question_type', $question->question_type ?? '') == 'dropdown' ? 'selected' : '' }}>
                            Dropdown (Pilih satu)
                        </option>
                    </optgroup>
                    <optgroup label="Pilihan Ganda">
                        <option value="checkbox"
                            {{ old('question_type', $question->question_type ?? '') == 'checkbox' ? 'selected' : '' }}>
                            Checkbox (Pilih banyak)
                        </option>
                    </optgroup>
                    <optgroup label="Format Tabel/Matriks">
                        <option value="likert_per_row"
                            {{ old('question_type', $question->question_type ?? '') == 'likert_per_row' ? 'selected' : '' }}>
                            Likert per Baris (Skala Tabel)
                        </option>
                    </optgroup>
                </select>
                <div class="form-text" id="questionTypeHelp">
                    <i class="fas fa-info-circle"></i> Pilih tipe pertanyaan untuk menampilkan opsi yang sesuai
                </div>
                @error('question_type')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>

            <div class="mb-4">
                <label class="form-label fw-bold">Deskripsi (Optional)</label>
                <textarea class="form-control @error('description') is-invalid @enderror" name="description" rows="2"
                    placeholder="Penjelasan tambahan tentang pertanyaan">{{ old('description', $question->description ?? '') }}</textarea>
                <div class="form-text">Contoh: "Jawaban ini akan membantu kami meningkatkan kualitas"</div>
                @error('description')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>
        </div>

        <!-- Kolom Kanan - Pengaturan -->
        <div class="col-md-6">
            <div class="row mb-4">
                <div class="col-md-6">
                    <label class="form-label fw-bold">Urutan</label>
                    <input type="number" class="form-control @error('order') is-invalid @enderror" name="order"
                        value="{{ old('order', $question->order ?? $questions->count() + 1) }}" min="1"
                        step="1">
                    <div class="form-text">Urutan tampil pertanyaan (1 = pertama)</div>
                    @error('order')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <div class="col-md-6">
                    <label class="form-label fw-bold">Points</label>
                    <input type="number" class="form-control @error('points') is-invalid @enderror" name="points"
                        value="{{ old('points', $question->points ?? 0) }}" min="0" step="1">
                    <div class="form-text">Poin untuk jawaban yang benar (opsional)</div>
                    @error('points')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>
            </div>

            <div class="mb-4">
                <label class="form-label fw-bold">Helper Text</label>
                <textarea class="form-control @error('helper_text') is-invalid @enderror" name="helper_text" rows="2"
                    placeholder="Teks bantuan untuk pengisi">{{ old('helper_text', $question->helper_text ?? '') }}</textarea>
                <div class="form-text">Contoh: "Pilih semua yang sesuai" atau "Isi dengan format DD/MM/YYYY"</div>
                @error('helper_text')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>

            <!-- Toggle untuk opsi lain dan tidak ada -->
            <div class="mb-4" id="otherNoneOptionsContainer" style="display: none;">
                <label class="form-label fw-bold d-block mb-3">Opsi Tambahan</label>
                <div class="row">
                    <div class="col-md-6">
                        <div class="form-check form-switch mb-3">
                            <input class="form-check-input" type="checkbox" name="has_other_option" id="hasOtherOption"
                                {{ old('has_other_option', $question->has_other_option ?? false) ? 'checked' : '' }}>
                            <label class="form-check-label" for="hasOtherOption">Opsi Lain (dengan input teks)</label>
                            <div class="form-text small">Menambahkan pilihan "Lainnya" dengan input teks</div>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="form-check form-switch mb-3" id="noneOptionContainer">
                            <input class="form-check-input" type="checkbox" name="has_none_option" id="hasNoneOption"
                                {{ old('has_none_option', $question->has_none_option ?? false) ? 'checked' : '' }}>
                            <label class="form-check-label" for="hasNoneOption">Opsi Tidak Ada</label>
                            <div class="form-text small">Menambahkan pilihan "Tidak Ada"</div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Wajib Diisi -->
            <div class="mb-4">
                <div class="form-check form-switch">
                    <input class="form-check-input" type="checkbox" name="is_required" id="isRequired"
                        {{ old('is_required', isset($question) ? $question->is_required : true) ? 'checked' : '' }}>
                    <label class="form-check-label fw-bold" for="isRequired">Wajib Diisi</label>
                </div>
            </div>
        </div>
    </div>

    <!-- Container untuk semua tipe input -->
    <div id="inputContainers">
        <!-- Opsi untuk Radio, Dropdown, Checkbox -->
        <div id="optionsContainer" class="mb-4" style="display: none;">
            <div class="card border">
                <div class="card-header bg-light">
                    <label class="form-label fw-bold mb-0">Pilihan Jawaban <span class="text-danger">*</span></label>
                </div>
                <div class="card-body">
                    <textarea class="form-control @error('options') is-invalid @enderror" name="options" rows="5"
                        id="optionsInput" placeholder="Masukkan setiap pilihan dalam baris terpisah">{{ old('options', $optionsText) }}</textarea>
                    <div class="form-text mt-2">
                        <div>Format: Setiap baris = satu pilihan</div>
                        <div class="mt-1">Contoh untuk Radio/Dropdown/Checkbox:</div>
                        <pre class="bg-light p-2 mt-1 small mb-2">Sangat Puas
Puas
Cukup Puas
Kurang Puas
Tidak Puas</pre>
                        <div>Contoh dengan nilai khusus:</div>
                        <pre class="bg-light p-2 mt-1 small">Ya, email|Ya, silakan kirim informasi via email
Ya, WhatsApp|Ya, silakan hubungi via WhatsApp
Tidak, terima kasih</pre>
                    </div>
                    @error('options')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>
            </div>
        </div>

        <!-- Item Baris untuk format tabel -->
        <div id="rowItemsContainer" class="mb-4" style="display: none;">
            <div class="card border">
                <div class="card-header bg-light">
                    <label class="form-label fw-bold mb-0">Item Baris (Untuk format tabel) <span
                            class="text-danger">*</span></label>
                </div>
                <div class="card-body">
                    <textarea class="form-control @error('row_items') is-invalid @enderror" name="row_items" rows="5"
                        id="rowItemsInput" placeholder="Masukkan setiap item dalam baris terpisah, format: key|label">{{ old('row_items', $rowItemsText) }}</textarea>
                    <div class="form-text mt-2">
                        <div>Format: key|label (pisahkan dengan |)</div>
                        <div class="mt-1">Contoh untuk Likert per Baris:</div>
                        <pre class="bg-light p-2 mt-1 small">ethics|Etika
expertise|Keahlian Bidang Ilmu
english|Bahasa Inggris
it_skills|Penggunaan Teknologi Informasi
communication|Komunikasi</pre>
                    </div>
                    @error('row_items')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>
            </div>
        </div>

        <!-- Opsi Skala untuk Likert per Baris -->
        <div id="scaleOptionsContainer" class="mb-4" style="display: none;">
            <div class="card border">
                <div class="card-header bg-light">
                    <label class="form-label fw-bold mb-0">Opsi Skala (1-5, maksimal 5) <span
                            class="text-danger">*</span></label>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label class="form-label">Opsi Skala</label>
                                <textarea class="form-control @error('scale_options') is-invalid @enderror" name="scale_options" rows="3"
                                    id="scaleOptionsInput" placeholder="Masukkan opsi skala, pisahkan dengan koma (contoh: 1, 2, 3, 4, 5)">{{ old('scale_options', $scaleOptionsText) }}</textarea>
                                <div class="form-text">
                                    <i class="fas fa-info-circle"></i> Hanya angka 1-5, maksimal 5 opsi
                                </div>
                                <div id="scaleOptionsError" class="text-danger small mt-1" style="display: none;">
                                </div>
                                @error('scale_options')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label class="form-label">Label Skala Rendah</label>
                                <input type="text"
                                    class="form-control @error('scale_label_low') is-invalid @enderror"
                                    name="scale_label_low"
                                    value="{{ old('scale_label_low', $question->scale_label_low ?? 'Sangat Rendah') }}"
                                    placeholder="Contoh: Sangat Rendah">
                                @error('scale_label_low')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                            <div class="mb-0">
                                <label class="form-label">Label Skala Tinggi</label>
                                <input type="text"
                                    class="form-control @error('scale_label_high') is-invalid @enderror"
                                    name="scale_label_high"
                                    value="{{ old('scale_label_high', $question->scale_label_high ?? 'Sangat Tinggi') }}"
                                    placeholder="Contoh: Sangat Tinggi">
                                @error('scale_label_high')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                    </div>
                    <div class="form-text">
                        <div>Contoh untuk Likert per Baris:</div>
                        <pre class="bg-light p-2 mt-1 small">1, 2, 3, 4, 5 (Skala 1-5 dengan angka)</pre>
                    </div>
                </div>
            </div>
        </div>

        <!-- Keterangan Opsi Skala -->
        <div id="scaleInformationContainer" class="mb-4" style="display: none;">
            <div class="card border">
                <div class="card-header bg-light">
                    <label class="form-label fw-bold mb-0">Keterangan Opsi Skala</label>
                </div>
                <div class="card-body">
                    <textarea class="form-control @error('scale_information') is-invalid @enderror" name="scale_information"
                        rows="4" id="scaleInformationInput"
                        placeholder="Masukkan keterangan untuk setiap opsi skala, format: angka|keterangan">{{ old('scale_information', $scaleInformationText) }}</textarea>
                    <div class="form-text mt-2">
                        <div>Format: angka|keterangan (contoh: 1|Sangat Tidak Puas)</div>
                        <div class="mt-1">Contoh untuk Likert per Baris:</div>
                        <pre class="bg-light p-2 mt-1 small">1|Sangat Tidak Puas
2|Tidak Puas
3|Cukup Puas
4|Puas
5|Sangat Puas</pre>
                        <div>
                            <i class="fas fa-info-circle"></i> Keterangan ini akan ditampilkan di halaman hasil
                            kuesioner alumni. Pastikan jumlah keterangan sama dengan jumlah opsi skala di atas!
                        </div>
                    </div>
                    @error('scale_information')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>
            </div>
        </div>

        <!-- Pengaturan tambahan -->
        <div id="additionalSettings" class="mb-4" style="display: none;">
            <div class="card border">
                <div class="card-header bg-light">
                    <label class="form-label fw-bold mb-0">Pengaturan Tambahan</label>
                </div>
                <div class="card-body">
                    <div class="row">
                        <!-- Placeholder -->
                        <div id="placeholderContainer" style="display: none;">
                            <div class="col-12 mb-3">
                                <label class="form-label">Placeholder Text</label>
                                <input type="text" class="form-control @error('placeholder') is-invalid @enderror"
                                    name="placeholder" value="{{ old('placeholder', $question->placeholder ?? '') }}"
                                    placeholder="Contoh: Masukkan nama perusahaan">
                                <div class="form-text">Teks yang ditampilkan di dalam input sebelum diisi</div>
                                @error('placeholder')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                        <!-- Max Length -->
                        <div id="maxLengthContainer" style="display: none;">
                            <div class="col-md-6 mb-3">
                                <label class="form-label">Maksimal Panjang Teks</label>
                                <input type="number" class="form-control @error('max_length') is-invalid @enderror"
                                    name="max_length" value="{{ old('max_length', $question->max_length ?? '') }}"
                                    min="1" max="10000" placeholder="Contoh: 255 karakter">
                                <div class="form-text">Jumlah maksimal karakter yang dapat diinput</div>
                                @error('max_length')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                        <!-- Rows -->
                        <div id="rowsContainer" style="display: none;">
                            <div class="col-md-6 mb-3">
                                <label class="form-label">Jumlah Baris Textarea</label>
                                <input type="number" class="form-control @error('rows') is-invalid @enderror"
                                    name="rows" value="{{ old('rows', $question->rows ?? 4) }}" min="1"
                                    max="20" placeholder="Contoh: 4">
                                <div class="form-text">Tinggi textarea dalam jumlah baris</div>
                                @error('rows')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                        <!-- Min Max -->
                        <div id="minMaxContainer" style="display: none;">
                            <div class="col-md-6 mb-3">
                                <label class="form-label">Nilai Minimum</label>
                                <input type="number" class="form-control @error('min_value') is-invalid @enderror"
                                    name="min_value" value="{{ old('min_value', $question->min_value ?? '') }}"
                                    placeholder="Contoh: 0">
                                <div class="form-text">Nilai terkecil yang dapat diinput</div>
                                @error('min_value')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                            <div class="col-md-6 mb-3">
                                <label class="form-label">Nilai Maksimum</label>
                                <input type="number" class="form-control @error('max_value') is-invalid @enderror"
                                    name="max_value" value="{{ old('max_value', $question->max_value ?? '') }}"
                                    placeholder="Contoh: 100">
                                <div class="form-text">Nilai terbesar yang dapat diinput</div>
                                @error('max_value')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                        <!-- Max Selections -->
                        <div id="maxSelectionsContainer" style="display: none;">
                            <div class="col-md-6 mb-3">
                                <label class="form-label">Maksimal Pilihan (Checkbox)</label>
                                <input type="number"
                                    class="form-control @error('max_selections') is-invalid @enderror"
                                    name="max_selections"
                                    value="{{ old('max_selections', $question->max_selections ?? '') }}"
                                    min="1" placeholder="Contoh: 3">
                                <div class="form-text">Jumlah maksimal opsi yang dapat dipilih (kosongkan jika tidak
                                    dibatasi)</div>
                                @error('max_selections')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="d-flex justify-content-between mt-5 pt-4 border-top">
        <a href="{{ $routeBack }}" class="btn btn-secondary btn-lg">
            <i class="fas fa-times-circle me-2"></i> Batal
        </a>
        <button type="submit" class="btn btn-primary btn-lg px-4" id="submitButton">
            <i class="fas fa-save me-2"></i> {{ isset($question) ? 'Update' : 'Simpan' }}
        </button>
    </div>
</form>

@push('scripts')
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const questionType = document.getElementById('questionType');

            // Fungsi untuk menampilkan/menyembunyikan container
            function toggleContainers() {
                const selectedType = questionType.value;

                // Semua container yang akan dikontrol
                const containers = {
                    'optionsContainer': ['radio', 'dropdown', 'checkbox'],
                    'rowItemsContainer': ['likert_per_row'],
                    'scaleOptionsContainer': ['likert_per_row'],
                    'scaleInformationContainer': ['likert_per_row'],
                    'otherNoneOptionsContainer': ['radio', 'dropdown', 'checkbox'],
                    'additionalSettings': ['text', 'textarea', 'number', 'date', 'radio', 'dropdown',
                        'checkbox'
                    ]
                };

                // Atur display untuk setiap container
                for (const [containerId, types] of Object.entries(containers)) {
                    const container = document.getElementById(containerId);
                    if (container) {
                        if (types.includes(selectedType)) {
                            container.style.display = containerId === 'otherNoneOptionsContainer' ? 'flex' :
                                'block';
                        } else {
                            container.style.display = 'none';
                        }
                    }
                }

                // Atur display untuk additional settings
                toggleAdditionalSettings(selectedType);

                // Atur visibility opsi tidak ada
                toggleNoneOption();
            }

            // Fungsi untuk additional settings
            function toggleAdditionalSettings(selectedType) {
                const settings = {
                    'minMaxContainer': ['number'],
                    'maxLengthContainer': ['text', 'textarea'],
                    'rowsContainer': ['textarea'],
                    'maxSelectionsContainer': ['checkbox'],
                    'placeholderContainer': ['text', 'textarea', 'number', 'date']
                };

                // Sembunyikan semua dulu
                for (const settingId in settings) {
                    const element = document.getElementById(settingId);
                    if (element) {
                        element.style.display = 'none';
                    }
                }

                // Tampilkan yang sesuai
                for (const [settingId, types] of Object.entries(settings)) {
                    if (types.includes(selectedType)) {
                        const element = document.getElementById(settingId);
                        if (element) {
                            element.style.display = 'block';
                        }
                    }
                }
            }

            // Pastikan additional settings tampil saat edit
            if (questionType) {
                // Trigger change event untuk menampilkan pengaturan yang sesuai
                questionType.dispatchEvent(new Event('change'));
            }



            // Validasi scale options
            function validateScaleOptions() {
                const scaleOptionsInput = document.getElementById('scaleOptionsInput');
                const scaleOptionsError = document.getElementById('scaleOptionsError');

                if (!scaleOptionsInput || questionType.value !== 'likert_per_row') {
                    return true;
                }

                const value = scaleOptionsInput.value.trim();
                if (!value) {
                    if (scaleOptionsError) {
                        scaleOptionsError.textContent = 'Opsi skala wajib diisi';
                        scaleOptionsError.style.display = 'block';
                    }
                    return false;
                }

                if (scaleOptionsError) {
                    scaleOptionsError.style.display = 'none';
                }
                return true;
            }

            // Event listener untuk perubahan tipe pertanyaan
            if (questionType) {
                // Panggil sekali saat load
                toggleContainers();

                // Tambahkan event listener
                questionType.addEventListener('change', function() {
                    toggleContainers();
                    toggleNoneOption();
                });
            }

            // Event listener untuk validasi scale options
            const scaleOptionsInput = document.getElementById('scaleOptionsInput');
            if (scaleOptionsInput) {
                scaleOptionsInput.addEventListener('input', validateScaleOptions);
            }

            // Event listener untuk form submit
            // Validasi sebelum submit form
            const form = document.getElementById('questionForm');
            if (form) {
                form.addEventListener('submit', function(e) {
                    // Validasi max length jika ada
                    const maxLengthInput = document.querySelector('input[name="max_length"]');
                    if (maxLengthInput && maxLengthInput.value) {
                        const maxLength = parseInt(maxLengthInput.value);
                        if (maxLength < 1 || maxLength > 10000) {
                            e.preventDefault();
                            alert('Maksimal panjang teks harus antara 1 dan 10000 karakter');
                            maxLengthInput.focus();
                            return false;
                        }
                    }

                    // Validasi min/max value
                    const minValueInput = document.querySelector('input[name="min_value"]');
                    const maxValueInput = document.querySelector('input[name="max_value"]');
                    if (minValueInput && maxValueInput &&
                        minValueInput.value && maxValueInput.value) {
                        const minValue = parseFloat(minValueInput.value);
                        const maxValue = parseFloat(maxValueInput.value);
                        if (minValue > maxValue) {
                            e.preventDefault();
                            alert('Nilai minimum tidak boleh lebih besar dari nilai maksimum');
                            minValueInput.focus();
                            return false;
                        }
                    }

                    // Validasi max selections
                    const maxSelectionsInput = document.querySelector('input[name="max_selections"]');
                    if (maxSelectionsInput && maxSelectionsInput.value) {
                        const maxSelections = parseInt(maxSelectionsInput.value);
                        if (maxSelections < 1) {
                            e.preventDefault();
                            alert('Maksimal pilihan harus lebih besar dari 0');
                            maxSelectionsInput.focus();
                            return false;
                        }
                    }

                    // Validasi rows
                    const rowsInput = document.querySelector('input[name="rows"]');
                    if (rowsInput && rowsInput.value) {
                        const rows = parseInt(rowsInput.value);
                        if (rows < 1 || rows > 20) {
                            e.preventDefault();
                            alert('Jumlah baris harus antara 1 dan 20');
                            rowsInput.focus();
                            return false;
                        }
                    }

                    // Nonaktifkan tombol submit untuk hindari double submit
                    const submitBtn = document.getElementById('submitButton');
                    if (submitBtn) {
                        submitBtn.disabled = true;
                        submitBtn.innerHTML = '<i class="fas fa-spinner fa-spin me-2"></i> Menyimpan...';
                    }
                });
            }
        });

        // Fungsi untuk mengatur visibility opsi "Tidak Ada"
        function toggleNoneOption() {
            const selectedType = questionType.value;
            const noneOptionContainer = document.getElementById('noneOptionContainer');

            if (noneOptionContainer) {
                // Sembunyikan untuk checkbox, tampilkan untuk radio dan dropdown
                if (selectedType === 'checkbox') {
                    noneOptionContainer.style.display = 'none';
                    // Uncheck opsi tidak ada jika checkbox
                    const hasNoneOption = document.getElementById('hasNoneOption');
                    if (hasNoneOption) hasNoneOption.checked = false;
                } else if (selectedType === 'radio' || selectedType === 'dropdown') {
                    noneOptionContainer.style.display = 'block';
                }
            }
        }
    </script>
@endpush
