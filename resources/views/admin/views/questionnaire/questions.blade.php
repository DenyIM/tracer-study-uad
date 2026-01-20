{{-- resources/views/admin/views/questionnaire/questions.blade.php --}}
@extends('admin.views.layouts.app')

@section('title', 'Manajemen Pertanyaan')
@section('page-title', 'Pertanyaan - ' . $questionnaire->name)

@section('content')
    <div class="row">
        @if (isset($question) || request()->has('create') || request()->has('edit'))
            <div class="col-md-5 mb-4">
                <div class="card dashboard-card">
                    <div class="card-header d-flex justify-content-between align-items-center">
                        <h5 class="mb-0">{{ isset($question) ? 'Edit' : 'Tambah' }} Pertanyaan</h5>
                        <div>
                            <a href="{{ route('admin.questionnaire.questionnaires', $category->id) }}"
                                class="btn btn-sm btn-secondary">
                                <i class="fas fa-arrow-left"></i> Kuesioner
                            </a>
                        </div>
                    </div>
                    <div class="card-body">
                        <form
                            action="{{ isset($question) ? route('admin.questionnaire.questions.update', [$category->id, $questionnaire->id, $question->id]) : route('admin.questionnaire.questions.store', [$category->id, $questionnaire->id]) }}"
                            method="POST" id="questionForm">
                            @csrf
                            @if (isset($question))
                                @method('PUT')
                            @endif

                            <!-- Kolom Kiri -->
                            <div class="mb-3">
                                <label class="form-label">Teks Pertanyaan <span class="text-danger">*</span></label>
                                <textarea class="form-control @error('question_text') is-invalid @enderror" name="question_text" rows="3" required
                                    placeholder="Masukkan teks pertanyaan di sini">{{ old('question_text', isset($question) ? $question->question_text : '') }}</textarea>
                                @error('question_text')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="mb-3">
                                <label class="form-label">Tipe Pertanyaan <span class="text-danger">*</span></label>
                                <select class="form-select @error('question_type') is-invalid @enderror"
                                    name="question_type" id="questionType" required>
                                    <option value="">Pilih Tipe Pertanyaan</option>
                                    <optgroup label="Input Teks">
                                        <option value="text"
                                            {{ old('question_type', isset($question) ? $question->question_type : '') == 'text' ? 'selected' : '' }}>
                                            Teks Singkat
                                        </option>
                                        <option value="textarea"
                                            {{ old('question_type', isset($question) ? $question->question_type : '') == 'textarea' ? 'selected' : '' }}>
                                            Teks Panjang
                                        </option>
                                    </optgroup>
                                    <optgroup label="Input Angka & Tanggal">
                                        <option value="number"
                                            {{ old('question_type', isset($question) ? $question->question_type : '') == 'number' ? 'selected' : '' }}>
                                            Angka
                                        </option>
                                        <option value="date"
                                            {{ old('question_type', isset($question) ? $question->question_type : '') == 'date' ? 'selected' : '' }}>
                                            Tanggal
                                        </option>
                                    </optgroup>
                                    <optgroup label="Pilihan Tunggal">
                                        <option value="radio"
                                            {{ old('question_type', isset($question) ? $question->question_type : '') == 'radio' ? 'selected' : '' }}>
                                            Radio Button
                                        </option>
                                        <option value="dropdown"
                                            {{ old('question_type', isset($question) ? $question->question_type : '') == 'dropdown' ? 'selected' : '' }}>
                                            Dropdown
                                        </option>
                                    </optgroup>
                                    <optgroup label="Pilihan Ganda">
                                        <option value="checkbox"
                                            {{ old('question_type', isset($question) ? $question->question_type : '') == 'checkbox' ? 'selected' : '' }}>
                                            Checkbox
                                        </option>
                                    </optgroup>
                                    <optgroup label="Skala Rating">
                                        <option value="likert_scale"
                                            {{ old('question_type', isset($question) ? $question->question_type : '') == 'likert_scale' ? 'selected' : '' }}>
                                            Skala Likert (1-5)
                                        </option>
                                        <option value="competency_scale"
                                            {{ old('question_type', isset($question) ? $question->question_type : '') == 'competency_scale' ? 'selected' : '' }}>
                                            Skala Kompetensi
                                        </option>
                                    </optgroup>
                                    <optgroup label="Format Tabel/Matriks">
                                        <option value="likert_per_row"
                                            {{ old('question_type', isset($question) ? $question->question_type : '') == 'likert_per_row' ? 'selected' : '' }}>
                                            Likert per Baris
                                        </option>
                                    </optgroup>
                                </select>
                                <small class="text-muted mt-1 d-block">
                                    <i class="fas fa-info-circle"></i>
                                    <span id="typeDescription">Pilih tipe yang sesuai dengan jawaban yang diharapkan</span>
                                </small>
                                @error('question_type')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="mb-3">
                                <label class="form-label">Deskripsi (Optional)</label>
                                <textarea class="form-control @error('description') is-invalid @enderror" name="description" rows="2"
                                    placeholder="Penjelasan tambahan tentang pertanyaan">{{ old('description', isset($question) ? $question->description : '') }}</textarea>
                                @error('description')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <!-- Opsi Container (muncul sesuai tipe) -->
                            <div class="mb-3" id="optionsContainer" style="display: none;">
                                <label class="form-label">Pilihan Jawaban <span class="text-danger">*</span></label>
                                <textarea class="form-control @error('options') is-invalid @enderror" name="options" rows="4"
                                    placeholder="Masukkan setiap pilihan dalam baris terpisah">{{ old('options', isset($question) && $question->options ? implode("\n", $question->options) : '') }}</textarea>
                                <small class="text-muted">Format: Setiap baris = satu pilihan</small>
                                <div class="mt-2">
                                    <button type="button" class="btn btn-sm btn-outline-primary"
                                        onclick="addExample('options')">
                                        <i class="fas fa-plus me-1"></i> Tambah Contoh
                                    </button>
                                </div>
                                @error('options')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <!-- Row Items Container -->
                            <div class="mb-3" id="rowItemsContainer" style="display: none;">
                                <label class="form-label">Item Baris (Format: key|label) <span
                                        class="text-danger">*</span></label>
                                <textarea class="form-control @error('row_items') is-invalid @enderror" name="row_items" rows="4"
                                    placeholder="Masukkan setiap item dalam baris terpisah, format: key|label">{{ old(
                                        'row_items',
                                        isset($question) && $question->row_items
                                            ? implode(
                                                "\n",
                                                array_map(
                                                    function ($key, $value) {
                                                        if (is_array($value) && isset($value['text'])) {
                                                            return $key . '|' . $value['text'];
                                                        }
                                                        return $key . '|' . $value;
                                                    },
                                                    array_keys($question->row_items),
                                                    $question->row_items,
                                                ),
                                            )
                                            : '',
                                    ) }}</textarea>
                                <small class="text-muted">Contoh: ethics|Etika, expertise|Keahlian Bidang Ilmu</small>
                                <div class="mt-2">
                                    <button type="button" class="btn btn-sm btn-outline-primary"
                                        onclick="addExample('row_items')">
                                        <i class="fas fa-plus me-1"></i> Tambah Contoh
                                    </button>
                                </div>
                                @error('row_items')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <!-- Scale Options Container -->
                            <div class="mb-3" id="scaleOptionsContainer" style="display: none;">
                                <label class="form-label">Opsi Skala (pisahkan dengan koma)</label>
                                <textarea class="form-control @error('scale_options') is-invalid @enderror" name="scale_options" rows="2"
                                    placeholder="Masukkan opsi skala, pisahkan dengan koma">{{ old('scale_options', isset($question) && $question->scale_options ? implode(', ', $question->scale_options) : '') }}</textarea>
                                <small class="text-muted">Contoh: 1, 2, 3, 4, 5 atau Sangat Rendah, Rendah, Cukup, Tinggi,
                                    Sangat Tinggi</small>
                                <div class="mt-2">
                                    <button type="button" class="btn btn-sm btn-outline-primary"
                                        onclick="addExample('scale_options')">
                                        <i class="fas fa-plus me-1"></i> Tambah Contoh
                                    </button>
                                </div>
                                @error('scale_options')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <!-- Kolom Kanan -->
                            <div class="row">
                                <div class="col-md-6 mb-3">
                                    <label class="form-label">Urutan</label>
                                    <input type="number" class="form-control @error('order') is-invalid @enderror"
                                        name="order"
                                        value="{{ old('order', isset($question) ? $question->order : $questions->count() + 1) }}"
                                        min="1" step="1">
                                    @error('order')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>

                                <div class="col-md-6 mb-3">
                                    <label class="form-label">Points</label>
                                    <input type="number" class="form-control @error('points') is-invalid @enderror"
                                        name="points"
                                        value="{{ old('points', isset($question) ? $question->points : 0) }}"
                                        min="0" step="1">
                                    @error('points')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>

                            <div class="mb-3">
                                <label class="form-label">Helper Text</label>
                                <textarea class="form-control @error('helper_text') is-invalid @enderror" name="helper_text" rows="2"
                                    placeholder="Teks bantuan untuk pengisi">{{ old('helper_text', isset($question) ? $question->helper_text : '') }}</textarea>
                                @error('helper_text')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <!-- Pengaturan Tambahan -->
                            <div class="mb-3">
                                <div class="d-flex flex-wrap gap-3">
                                    <div class="form-check form-switch">
                                        <input class="form-check-input" type="checkbox" name="is_required"
                                            id="isRequired"
                                            {{ old('is_required', isset($question) ? $question->is_required : true) ? 'checked' : '' }}>
                                        <label class="form-check-label" for="isRequired">
                                            Wajib Diisi
                                        </label>
                                    </div>
                                    <div class="form-check form-switch">
                                        <input class="form-check-input" type="checkbox" name="has_other_option"
                                            id="hasOtherOption"
                                            {{ old('has_other_option', isset($question) ? $question->has_other_option : false) ? 'checked' : '' }}>
                                        <label class="form-check-label" for="hasOtherOption">
                                            Opsi Lain
                                        </label>
                                    </div>
                                    <div class="form-check form-switch">
                                        <input class="form-check-input" type="checkbox" name="has_none_option"
                                            id="hasNoneOption"
                                            {{ old('has_none_option', isset($question) ? $question->has_none_option : false) ? 'checked' : '' }}>
                                        <label class="form-check-label" for="hasNoneOption">
                                            Opsi Tidak Ada
                                        </label>
                                    </div>
                                </div>
                            </div>

                            <!-- Preview Section -->
                            <div class="mb-3 border-top pt-3">
                                <label class="form-label">Preview:</label>
                                <div id="previewContainer" class="p-3 border rounded bg-light">
                                    <p class="text-muted mb-0">Preview akan muncul di sini setelah memilih tipe pertanyaan
                                    </p>
                                </div>
                            </div>

                            <div class="d-flex justify-content-between">
                                <a href="{{ route('admin.questionnaire.questions', [$category->id, $questionnaire->id]) }}"
                                    class="btn btn-secondary">
                                    <i class="fas fa-times-circle me-2"></i> Batal
                                </a>
                                <button type="submit" class="btn btn-primary">
                                    <i class="fas fa-save me-2"></i> {{ isset($question) ? 'Update' : 'Simpan' }}
                                </button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>

            @push('scripts')
                <script>
                    document.addEventListener('DOMContentLoaded', function() {
                        const questionType = document.getElementById('questionType');
                        const optionsContainer = document.getElementById('optionsContainer');
                        const rowItemsContainer = document.getElementById('rowItemsContainer');
                        const scaleOptionsContainer = document.getElementById('scaleOptionsContainer');
                        const previewContainer = document.getElementById('previewContainer');
                        const typeDescription = document.getElementById('typeDescription');

                        // Deskripsi untuk setiap tipe
                        const typeDescriptions = {
                            'text': 'Input teks singkat untuk jawaban pendek (nama, email, dll)',
                            'textarea': 'Input teks panjang untuk jawaban deskriptif atau penjelasan',
                            'number': 'Input angka untuk data numerik (usia, gaji, jumlah, dll)',
                            'date': 'Input tanggal untuk data waktu',
                            'radio': 'Pilihan tunggal - hanya satu jawaban yang bisa dipilih',
                            'dropdown': 'Pilihan tunggal dalam dropdown - hemat space untuk opsi banyak',
                            'checkbox': 'Pilihan ganda - bisa memilih lebih dari satu jawaban',
                            'likert_scale': 'Skala 1-5 untuk mengukur sikap, persetujuan, atau frekuensi',
                            'competency_scale': 'Skala untuk mengukur tingkat kompetensi atau keahlian',
                            'likert_per_row': 'Format tabel dengan skala Likert untuk setiap baris item'
                        };

                        function updateFormFields() {
                            if (!questionType) return;

                            const selectedType = questionType.value;

                            // Update description
                            if (typeDescription && typeDescriptions[selectedType]) {
                                typeDescription.textContent = typeDescriptions[selectedType];
                            }

                            // Hide all containers
                            optionsContainer.style.display = 'none';
                            rowItemsContainer.style.display = 'none';
                            scaleOptionsContainer.style.display = 'none';

                            // Show relevant containers
                            switch (selectedType) {
                                case 'radio':
                                case 'dropdown':
                                case 'checkbox':
                                    optionsContainer.style.display = 'block';
                                    break;
                                case 'likert_scale':
                                case 'competency_scale':
                                    scaleOptionsContainer.style.display = 'block';
                                    break;
                                case 'likert_per_row':
                                    rowItemsContainer.style.display = 'block';
                                    scaleOptionsContainer.style.display = 'block';
                                    break;
                            }

                            // Update preview
                            updatePreview();
                        }

                        function updatePreview() {
                            const type = questionType.value;
                            if (!type) {
                                previewContainer.innerHTML =
                                    '<p class="text-muted mb-0">Preview akan muncul di sini setelah memilih tipe pertanyaan</p>';
                                return;
                            }

                            let previewHTML = '<div class="mb-2"><strong>Contoh tampilan:</strong></div>';

                            switch (type) {
                                case 'text':
                                    previewHTML += `
                            <div class="form-group">
                                <input type="text" class="form-control" placeholder="Jawaban..." disabled>
                                <small class="text-muted">Input teks singkat</small>
                            </div>`;
                                    break;
                                case 'textarea':
                                    previewHTML += `
                            <div class="form-group">
                                <textarea class="form-control" rows="3" placeholder="Jawaban..." disabled></textarea>
                                <small class="text-muted">Input teks panjang</small>
                            </div>`;
                                    break;
                                case 'number':
                                    previewHTML += `
                            <div class="form-group">
                                <input type="number" class="form-control" placeholder="Angka..." disabled>
                                <small class="text-muted">Input angka</small>
                            </div>`;
                                    break;
                                case 'date':
                                    previewHTML += `
                            <div class="form-group">
                                <input type="date" class="form-control" disabled>
                                <small class="text-muted">Input tanggal</small>
                            </div>`;
                                    break;
                                case 'radio':
                                    previewHTML += `
                            <div class="form-group">
                                <div class="form-check">
                                    <input class="form-check-input" type="radio" name="preview_radio" disabled>
                                    <label class="form-check-label">Opsi 1</label>
                                </div>
                                <div class="form-check">
                                    <input class="form-check-input" type="radio" name="preview_radio" disabled>
                                    <label class="form-check-label">Opsi 2</label>
                                </div>
                                <div class="form-check">
                                    <input class="form-check-input" type="radio" name="preview_radio" disabled>
                                    <label class="form-check-label">Opsi 3</label>
                                </div>
                                <small class="text-muted">Pilihan tunggal (radio button)</small>
                            </div>`;
                                    break;
                                case 'dropdown':
                                    previewHTML += `
                            <div class="form-group">
                                <select class="form-select" disabled>
                                    <option>Pilih opsi...</option>
                                    <option>Opsi 1</option>
                                    <option>Opsi 2</option>
                                    <option>Opsi 3</option>
                                </select>
                                <small class="text-muted">Pilihan tunggal (dropdown)</small>
                            </div>`;
                                    break;
                                case 'checkbox':
                                    previewHTML += `
                            <div class="form-group">
                                <div class="form-check">
                                    <input class="form-check-input" type="checkbox" disabled>
                                    <label class="form-check-label">Pilihan 1</label>
                                </div>
                                <div class="form-check">
                                    <input class="form-check-input" type="checkbox" disabled>
                                    <label class="form-check-label">Pilihan 2</label>
                                </div>
                                <div class="form-check">
                                    <input class="form-check-input" type="checkbox" disabled>
                                    <label class="form-check-label">Pilihan 3</label>
                                </div>
                                <small class="text-muted">Pilihan ganda (checkbox)</small>
                            </div>`;
                                    break;
                                case 'likert_scale':
                                    previewHTML += `
                            <div class="form-group">
                                <div class="d-flex justify-content-between align-items-center">
                                    <small>1</small>
                                    <div>
                                        <input type="radio" name="preview_likert" disabled>
                                        <input type="radio" name="preview_likert" disabled>
                                        <input type="radio" name="preview_likert" disabled>
                                        <input type="radio" name="preview_likert" disabled>
                                        <input type="radio" name="preview_likert" disabled>
                                    </div>
                                    <small>5</small>
                                </div>
                                <small class="text-muted">Skala Likert 1-5</small>
                            </div>`;
                                    break;
                                case 'competency_scale':
                                    previewHTML += `
                            <div class="form-group">
                                <div class="d-flex justify-content-between">
                                    <div class="text-center">
                                        <input type="radio" name="preview_comp" disabled><br>
                                        <small>Pemula</small>
                                    </div>
                                    <div class="text-center">
                                        <input type="radio" name="preview_comp" disabled><br>
                                        <small>Dasar</small>
                                    </div>
                                    <div class="text-center">
                                        <input type="radio" name="preview_comp" disabled><br>
                                        <small>Menengah</small>
                                    </div>
                                    <div class="text-center">
                                        <input type="radio" name="preview_comp" disabled><br>
                                        <small>Mahir</small>
                                    </div>
                                    <div class="text-center">
                                        <input type="radio" name="preview_comp" disabled><br>
                                        <small>Expert</small>
                                    </div>
                                </div>
                                <small class="text-muted">Skala Kompetensi</small>
                            </div>`;
                                    break;
                                case 'likert_per_row':
                                    previewHTML += `
                            <div class="form-group">
                                <table class="table table-sm">
                                    <tr>
                                        <td>Item 1</td>
                                        <td class="text-center">
                                            <input type="radio" name="preview_row1" disabled>
                                            <input type="radio" name="preview_row1" disabled>
                                            <input type="radio" name="preview_row1" disabled>
                                            <input type="radio" name="preview_row1" disabled>
                                            <input type="radio" name="preview_row1" disabled>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td>Item 2</td>
                                        <td class="text-center">
                                            <input type="radio" name="preview_row2" disabled>
                                            <input type="radio" name="preview_row2" disabled>
                                            <input type="radio" name="preview_row2" disabled>
                                            <input type="radio" name="preview_row2" disabled>
                                            <input type="radio" name="preview_row2" disabled>
                                        </td>
                                    </tr>
                                </table>
                                <small class="text-muted">Likert per Baris (format tabel)</small>
                            </div>`;
                                    break;
                            }

                            previewContainer.innerHTML = previewHTML;
                        }

                        // Add example function
                        window.addExample = function(fieldType) {
                            const textareas = {
                                'options': {
                                    'radio': 'Sangat Puas\nPuas\nCukup Puas\nKurang Puas\nTidak Puas',
                                    'dropdown': 'Pilih salah satu\nOpsi 1\nOpsi 2\nOpsi 3\nLainnya',
                                    'checkbox': 'Website Karir Kampus\nLinkedIn\nJob Fair\nRekomendasi Dosen\nMedia Sosial'
                                },
                                'row_items': {
                                    'likert_per_row': 'ethics|Etika\nexpertise|Keahlian Bidang Ilmu\nenglish|Bahasa Inggris\nit_skills|Penggunaan IT\ncommunication|Komunikasi'
                                },
                                'scale_options': {
                                    'likert_scale': '1, 2, 3, 4, 5',
                                    'competency_scale': 'Pemula, Dasar, Menengah, Mahir, Expert',
                                    'likert_per_row': '1, 2, 3, 4, 5'
                                }
                            };

                            const currentType = questionType.value;
                            if (textareas[fieldType] && textareas[fieldType][currentType]) {
                                const textarea = document.querySelector(`[name="${fieldType}"]`);
                                if (textarea) {
                                    textarea.value = textareas[fieldType][currentType];
                                }
                            }
                        };

                        // Initialize
                        if (questionType) {
                            updateFormFields();
                            questionType.addEventListener('change', updateFormFields);
                        }
                    });
                </script>
            @endpush
        @endif

        <div
            class="{{ isset($question) || request()->has('create') || request()->has('edit') ? 'col-md-7' : 'col-md-12' }}">
            <div class="card dashboard-card">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <div>
                        <h5 class="mb-0">Daftar Pertanyaan</h5>
                        <p class="text-muted mb-0">Kuesioner: {{ $questionnaire->name }}</p>
                        <small class="text-info">
                            <i class="fas fa-info-circle"></i> Kuesioner spesifik untuk kategori: {{ $category->name }}
                        </small>
                    </div>
                    <div class="d-flex align-items-center gap-2">
                        <a href="{{ route('admin.questionnaire.categories') }}" class="btn btn-secondary">
                            <i class="fas fa-arrow-left me-2"></i> Kategori
                        </a>
                        @if (!isset($question) && !request()->has('create') && !request()->has('edit'))
                            <a href="{{ route('admin.questionnaire.questionnaires', $category->id) }}"
                                class="btn btn-secondary">
                                <i class="fas fa-arrow-left me-2"></i> Kuesioner
                            </a>
                            <a href="?create=true" class="btn btn-success">
                                <i class="fas fa-plus-circle me-2"></i> Tambah Pertanyaan
                            </a>
                        @endif
                        @if ($questions->isNotEmpty() && !isset($question) && !request()->has('create') && !request()->has('edit'))
                            <button type="button" class="btn btn-danger" id="deleteSelectedQuestions" disabled>
                                <i class="fas fa-trash me-1"></i> Hapus Terpilih
                            </button>
                        @endif
                        <span class="badge bg-primary ms-2">{{ $questions->count() }} pertanyaan</span>
                    </div>
                </div>
                <div class="card-body">
                    @if ($questions->isEmpty())
                        <div class="text-center py-4">
                            <i class="fas fa-question-circle display-4 text-muted"></i>
                            <p class="mt-2 text-muted">Belum ada pertanyaan</p>
                            @if (!isset($question) && !request()->has('create'))
                                <a href="?create=true" class="btn btn-primary mt-2">
                                    <i class="fas fa-plus-circle me-2"></i> Buat Pertanyaan Pertama
                                </a>
                            @endif
                        </div>
                    @else
                        <div class="table-responsive">
                            <table class="table table-hover" id="questionTable">
                                <thead>
                                    <tr>
                                        @if (!isset($question) && !request()->has('create') && !request()->has('edit'))
                                            <th width="30">
                                                <input type="checkbox" id="selectAllQuestions">
                                            </th>
                                        @endif
                                        <th width="50">#</th>
                                        <th>Pertanyaan</th>
                                        <th width="120">Tipe</th>
                                        <th width="90">Required</th>
                                        <th width="80">Points</th>
                                        <th width="70">Aksi</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($questions as $q)
                                        <tr data-id="{{ $q->id }}">
                                            @if (!isset($question) && !request()->has('create') && !request()->has('edit'))
                                                <td>
                                                    <input type="checkbox" class="question-checkbox"
                                                        value="{{ $q->id }}">
                                                </td>
                                            @endif
                                            <td>{{ $q->order }}</td>
                                            <td>
                                                <div class="fw-bold">{{ Str::limit($q->question_text, 80) }}</div>
                                                @if ($q->description)
                                                    <small
                                                        class="text-muted">{{ Str::limit($q->description, 50) }}</small>
                                                @endif
                                            </td>
                                            <td>
                                                <span class="badge bg-info">{{ $q->type_label }}</span>
                                            </td>
                                            <td>
                                                @if ($q->is_required)
                                                    <span class="badge bg-danger">Wajib</span>
                                                @else
                                                    <span class="badge bg-secondary">Opsional</span>
                                                @endif
                                            </td>
                                            <td>
                                                <span class="badge bg-success">{{ $q->points }} pts</span>
                                            </td>
                                            <td>
                                                <div class="d-flex flex-wrap gap-1">
                                                    <a href="?edit={{ $q->id }}"
                                                        class="btn btn-sm btn-action btn-warning" title="Edit">
                                                        <i class="fas fa-edit"></i>
                                                    </a>
                                                </div>
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>

    <!-- Delete Confirmation Modal -->
    <div class="modal fade" id="deleteModal" tabindex="-1" aria-labelledby="deleteModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="deleteModalLabel">Konfirmasi Hapus Pertanyaan</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <p>Apakah Anda yakin ingin menghapus <span id="deleteCount" class="fw-bold"></span> pertanyaan yang
                        dipilih?</p>
                    <p class="text-muted"><small>Data yang dihapus tidak dapat dikembalikan.</small></p>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                    <button type="button" class="btn btn-danger" id="confirmDeleteQuestions">Ya, Hapus</button>
                </div>
            </div>
        </div>
    </div>

    @push('scripts')
        <script>
            document.addEventListener('DOMContentLoaded', function() {
                // Show/hide options container based on question type
                const questionType = document.getElementById('questionType');
                const optionsContainer = document.getElementById('optionsContainer');

                function updateFormFields() {
                    if (!questionType) return;

                    const selectedType = questionType.value;

                    if (optionsContainer) {
                        // Show options container for specific question types
                        const showForTypes = ['radio', 'dropdown', 'checkbox', 'likert_scale', 'competency_scale'];
                        if (showForTypes.includes(selectedType)) {
                            optionsContainer.style.display = 'block';
                        } else {
                            optionsContainer.style.display = 'none';
                        }
                    }
                }

                if (questionType) {
                    updateFormFields();
                    questionType.addEventListener('change', updateFormFields);
                }

                @if (!isset($question) && !request()->has('create') && !request()->has('edit'))
                    // Select all checkbox for questions
                    const selectAll = document.getElementById('selectAllQuestions');
                    const questionCheckboxes = document.querySelectorAll('.question-checkbox');
                    const deleteSelectedBtn = document.getElementById('deleteSelectedQuestions');

                    function updateDeleteButton() {
                        const checkedCount = document.querySelectorAll('.question-checkbox:checked').length;
                        if (deleteSelectedBtn) {
                            deleteSelectedBtn.disabled = checkedCount === 0;
                        }
                    }

                    if (selectAll) {
                        selectAll.addEventListener('change', function() {
                            questionCheckboxes.forEach(checkbox => {
                                checkbox.checked = this.checked;
                            });
                            updateDeleteButton();
                        });
                    }

                    questionCheckboxes.forEach(checkbox => {
                        checkbox.addEventListener('change', updateDeleteButton);
                    });

                    // Handle delete selected questions
                    if (deleteSelectedBtn) {
                        deleteSelectedBtn.addEventListener('click', function() {
                            const selectedIds = [];
                            document.querySelectorAll('.question-checkbox:checked').forEach(checkbox => {
                                selectedIds.push(checkbox.value);
                            });

                            if (selectedIds.length === 0) {
                                return;
                            }

                            document.getElementById('deleteCount').textContent = selectedIds.length +
                                ' pertanyaan';

                            const deleteModal = new bootstrap.Modal(document.getElementById('deleteModal'));
                            deleteModal.show();

                            document.getElementById('confirmDeleteQuestions').onclick = function() {
                                fetch("{{ route('admin.questionnaire.questions.delete-selected', [$category->id, $questionnaire->id]) }}", {
                                        method: 'DELETE',
                                        headers: {
                                            'Content-Type': 'application/json',
                                            'X-CSRF-TOKEN': '{{ csrf_token() }}'
                                        },
                                        body: JSON.stringify({
                                            ids: selectedIds
                                        })
                                    })
                                    .then(response => response.json())
                                    .then(data => {
                                        if (data.success) {
                                            location.reload();
                                        } else {
                                            alert(data.message || 'Terjadi kesalahan');
                                        }
                                    })
                                    .catch(error => {
                                        console.error('Error:', error);
                                        alert('Terjadi kesalahan');
                                    });
                            };
                        });
                    }
                @endif

                // Get URL parameters
                const urlParams = new URLSearchParams(window.location.search);
                if (urlParams.has('edit') || urlParams.has('create')) {
                    const formSection = document.querySelector('.col-md-5');
                    if (formSection) {
                        formSection.scrollIntoView({
                            behavior: 'smooth'
                        });
                    }
                }
            });
        </script>
    @endpush
@endsection
