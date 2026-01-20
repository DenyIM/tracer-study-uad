{{-- resources/views/admin/questionnaire/general_questionnaires.blade.php --}}
@extends('admin.views.layouts.app')

@section('title', 'Manajemen Kuesioner Umum')
@section('page-title', 'Kuesioner Umum')

@section('content')
    <div class="row">
        <!-- Sidebar Kategori -->
        <div class="col-md-3 mb-4">
            <div class="card dashboard-card">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h5 class="mb-0">Pilih Kategori</h5>
                    <a href="{{ route('admin.questionnaire.categories') }}" class="btn btn-sm btn-secondary">
                        <i class="fas fa-arrow-left"></i> Kembali
                    </a>
                </div>
                <div class="card-body p-0">
                    <div class="list-group list-group-flush">
                        @foreach ($categories as $cat)
                            <a href="{{ route('admin.questionnaire.general-questionnaires', ['category_id' => $cat->id]) }}"
                                class="list-group-item list-group-item-action d-flex align-items-center py-3 {{ $selectedCategory && $selectedCategory->id == $cat->id ? 'active' : '' }}">
                                <div class="me-3">
                                    @if ($cat->icon)
                                        <i class="{{ $cat->icon }} fa-lg"></i>
                                    @else
                                        <i class="fas fa-folder fa-lg"></i>
                                    @endif
                                </div>
                                <div class="flex-grow-1">
                                    <div class="fw-bold">{{ $cat->name }}</div>
                                    @if ($cat->description)
                                        <small class="text-muted d-block">{{ Str::limit($cat->description, 30) }}</small>
                                    @endif
                                </div>
                                @if ($selectedCategory && $selectedCategory->id == $cat->id)
                                    <i class="fas fa-chevron-right ms-2"></i>
                                @endif
                            </a>
                        @endforeach
                    </div>
                </div>
            </div>
        </div>

        <!-- Konten Utama -->
        <div class="col-md-9">
            @if (!$selectedCategory)
                <div class="card dashboard-card">
                    <div class="card-body text-center py-5">
                        <i class="fas fa-folder-open display-4 text-muted mb-4"></i>
                        <h4 class="mb-3">Pilih Kategori</h4>
                        <p class="text-muted mb-4">Silakan pilih kategori dari menu di samping untuk mengelola kuesioner
                            umum.</p>
                        <div class="d-flex justify-content-center gap-3">
                            @foreach ($categories->take(3) as $cat)
                                <a href="{{ route('admin.questionnaire.general-questionnaires', ['category_id' => $cat->id]) }}"
                                    class="btn btn-outline-primary">
                                    <i class="{{ $cat->icon ?? 'fas fa-folder' }} me-2"></i>
                                    {{ $cat->name }}
                                </a>
                            @endforeach
                        </div>
                    </div>
                </div>
            @else
                <!-- Informasi Kategori -->
                <div class="card dashboard-card mb-4">
                    <div class="card-body">
                        <div class="d-flex justify-content-between align-items-center">
                            <div class="d-flex align-items-center">
                                @if ($selectedCategory->icon)
                                    <i class="{{ $selectedCategory->icon }} fa-2x text-primary me-3"></i>
                                @endif
                                <div>
                                    <h4 class="mb-0">{{ $selectedCategory->name }}</h4>
                                    @if ($selectedCategory->description)
                                        <p class="text-muted mb-0">{{ $selectedCategory->description }}</p>
                                    @endif
                                </div>
                            </div>
                            <div>
                                <span class="badge bg-{{ $selectedCategory->is_active ? 'success' : 'secondary' }}">
                                    {{ $selectedCategory->is_active ? 'Aktif' : 'Nonaktif' }}
                                </span>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Custom CSS untuk Tabs -->
                <style>
                    .custom-nav-tabs {
                        border-bottom: 2px solid #dee2e6;
                    }

                    .custom-nav-tabs .nav-link {
                        color: #6c757d;
                        background-color: #f8f9fa;
                        border: 1px solid #dee2e6;
                        border-bottom: none;
                        margin-bottom: -1px;
                        padding: 0.75rem 1.25rem;
                        transition: all 0.3s ease;
                    }

                    .custom-nav-tabs .nav-link:hover {
                        color: #495057;
                        background-color: #e9ecef;
                        border-color: #dee2e6;
                    }

                    .custom-nav-tabs .nav-link.active {
                        color: #0d6efd;
                        background-color: white;
                        border-color: #dee2e6 #dee2e6 white;
                        border-top: 3px solid #0d6efd;
                        font-weight: 600;
                    }

                    .custom-nav-tabs .nav-link:not(.active) {
                        border-top: 3px solid transparent;
                    }

                    .tab-content {
                        background-color: white;
                        border: 1px solid #dee2e6;
                        border-top: none;
                        padding: 1.5rem;
                        border-radius: 0 0 0.375rem 0.375rem;
                    }
                </style>

                <!-- Tabs untuk Navigasi -->
                <ul class="nav custom-nav-tabs mb-0" id="questionnaireTabs" role="tablist">
                    <li class="nav-item" role="presentation">
                        <button class="nav-link active" id="questions-tab" data-bs-toggle="tab" data-bs-target="#questions"
                            type="button" role="tab" aria-controls="questions" aria-selected="true">
                            <i class="fas fa-question-circle me-2"></i> Daftar Pertanyaan
                            <span class="badge bg-primary ms-1">{{ $questions->count() }}</span>
                        </button>
                    </li>
                    <li class="nav-item" role="presentation">
                        <button class="nav-link" id="settings-tab" data-bs-toggle="tab" data-bs-target="#settings"
                            type="button" role="tab" aria-controls="settings" aria-selected="false">
                            <i class="fas fa-cog me-2"></i> Pengaturan Kuesioner
                        </button>
                    </li>
                </ul>

                <div class="tab-content" id="questionnaireTabsContent">
                    <!-- Tab Daftar Pertanyaan -->
                    <div class="tab-pane fade show active" id="questions" role="tabpanel" aria-labelledby="questions-tab">
                        @if ($generalQuestionnaire)
                            <div class="mb-4">
                                <div class="d-flex justify-content-between align-items-center mb-3">
                                    <div>
                                        <h5 class="mb-0">Daftar Pertanyaan Kuesioner Umum</h5>
                                        <small class="text-muted">Kategori: {{ $selectedCategory->name }}</small>
                                    </div>
                                    <div class="d-flex gap-2">
                                        @if (!request()->has('create_question') && !request()->has('edit_question'))
                                            <button type="button" class="btn btn-sm btn-danger"
                                                id="deleteSelectedGeneralQuestions" disabled>
                                                <i class="fas fa-trash me-1"></i> Hapus Terpilih
                                            </button>
                                            <a href="?category_id={{ $selectedCategory->id }}&create_question=true"
                                                class="btn btn-success btn-sm">
                                                <i class="fas fa-plus-circle me-2"></i> Tambah Pertanyaan
                                            </a>
                                        @endif
                                    </div>
                                </div>

                                @if ($questions->isEmpty())
                                    <div class="text-center py-5 border rounded bg-light">
                                        <i class="fas fa-question-circle fa-3x text-muted mb-3"></i>
                                        <h5 class="mb-3">Belum ada pertanyaan</h5>
                                        <p class="text-muted mb-4">Mulai dengan menambahkan pertanyaan pertama untuk
                                            kuesioner umum ini.</p>
                                        <a href="?category_id={{ $selectedCategory->id }}&create_question=true"
                                            class="btn btn-primary">
                                            <i class="fas fa-plus-circle me-2"></i> Tambah Pertanyaan Pertama
                                        </a>
                                    </div>
                                @else
                                    <div class="table-responsive">
                                        <table class="table table-hover mb-0">
                                            <thead class="table-light">
                                                <tr>
                                                    <th width="30">
                                                        <input type="checkbox" id="selectAllGeneralQuestions">
                                                    </th>
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
                                                    <tr>
                                                        <td>
                                                            <input type="checkbox" class="general-question-checkbox"
                                                                value="{{ $q->id }}">
                                                        </td>
                                                        <td class="fw-bold">{{ $q->order }}</td>
                                                        <td>
                                                            <div class="fw-semibold">
                                                                {{ Str::limit($q->question_text, 70) }}</div>
                                                            @if ($q->description)
                                                                <small
                                                                    class="text-muted d-block">{{ Str::limit($q->description, 50) }}</small>
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
                                                            <div class="d-flex gap-1">
                                                                <a href="?category_id={{ $selectedCategory->id }}&edit_question={{ $q->id }}"
                                                                    class="btn btn-sm btn-warning px-2" title="Edit">
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
                        @endif
                    </div>

                    <!-- Tab Pengaturan -->
                    <div class="tab-pane fade" id="settings" role="tabpanel" aria-labelledby="settings-tab">
                        @if ($generalQuestionnaire)
                            <div>
                                <h5 class="mb-3">Pengaturan Kuesioner Umum</h5>
                                <form
                                    action="{{ route('admin.questionnaire.general-questionnaires.update', $generalQuestionnaire->id) }}"
                                    method="POST">
                                    @csrf
                                    @method('PUT')

                                    <div class="mb-3">
                                        <label class="form-label">Nama Bagian</label>
                                        <input type="text" class="form-control" name="name"
                                            value="{{ old('name', $generalQuestionnaire->name) }}" required>
                                    </div>

                                    <div class="mb-3">
                                        <label class="form-label">Slug</label>
                                        <input type="text" class="form-control" name="slug"
                                            value="{{ old('slug', $generalQuestionnaire->slug) }}" required>
                                    </div>

                                    <div class="mb-3">
                                        <label class="form-label">Deskripsi</label>
                                        <textarea class="form-control" name="description" rows="3">{{ old('description', $generalQuestionnaire->description) }}</textarea>
                                    </div>

                                    <div class="mb-3">
                                        <div class="form-check form-switch">
                                            <input class="form-check-input" type="checkbox" name="is_required"
                                                id="isRequired" {{ $generalQuestionnaire->is_required ? 'checked' : '' }}>
                                            <label class="form-check-label" for="isRequired">
                                                Wajib Diisi
                                            </label>
                                        </div>
                                    </div>

                                    <div class="d-flex justify-content-between">
                                        <button type="button" class="btn btn-secondary"
                                            onclick="window.location.href='{{ route('admin.questionnaire.general-questionnaires', ['category_id' => $selectedCategory->id]) }}'">
                                            <i class="fas fa-times-circle me-2"></i> Batal
                                        </button>
                                        <button type="submit" class="btn btn-primary">
                                            <i class="fas fa-save me-2"></i> Simpan Perubahan
                                        </button>
                                    </div>
                                </form>
                            </div>
                        @endif
                    </div>
                </div>



                @if (request()->has('create_question') || request()->has('edit_question'))
                    <div class="card dashboard-card mt-4">
                        <div class="card-header d-flex justify-content-between align-items-center">
                            <h5 class="mb-0">{{ request()->has('edit_question') ? 'Edit' : 'Tambah' }} Pertanyaan</h5>
                            <a href="{{ route('admin.questionnaire.general-questionnaires', ['category_id' => $selectedCategory->id]) }}"
                                class="btn btn-sm btn-secondary">
                                <i class="fas fa-arrow-left me-1"></i> Kembali ke Daftar
                            </a>
                        </div>
                        <div class="card-body">
                            @php
                                $question = null;
                                if (request()->has('edit_question')) {
                                    $questionId = request()->get('edit_question');
                                    $question = $questions->firstWhere('id', $questionId);
                                    if (!$question) {
                                        $question = \App\Models\Question::find($questionId);
                                    }
                                }

                                // Format data untuk form
                                $optionsText = '';
                                $rowItemsText = '';
                                $scaleOptionsText = '';

                                if ($question) {
                                    if ($question->options && is_array($question->options)) {
                                        $optionsText = implode("\n", $question->options);
                                    } elseif (is_string($question->options) && $question->options) {
                                        $options = json_decode($question->options, true);
                                        $optionsText = is_array($options) ? implode("\n", $options) : '';
                                    }

                                    if ($question->row_items) {
                                        if (is_array($question->row_items)) {
                                            $rowItemsArray = $question->row_items;
                                        } else {
                                            $rowItemsArray = json_decode($question->row_items, true) ?? [];
                                        }
                                        if ($rowItemsArray) {
                                            $rowItemsText = implode(
                                                "\n",
                                                array_map(
                                                    function ($key, $value) {
                                                        if (is_array($value) && isset($value['text'])) {
                                                            return $key . '|' . $value['text'];
                                                        }
                                                        return $key . '|' . $value;
                                                    },
                                                    array_keys($rowItemsArray),
                                                    $rowItemsArray,
                                                ),
                                            );
                                        }
                                    }

                                    if ($question->scale_options) {
                                        if (is_array($question->scale_options)) {
                                            $scaleOptionsText = implode(', ', $question->scale_options);
                                        } else {
                                            $scaleOptionsArray = json_decode($question->scale_options, true) ?? [];
                                            $scaleOptionsText = implode(', ', $scaleOptionsArray);
                                        }
                                    }
                                }
                            @endphp

                            <form
                                action="{{ request()->has('edit_question')
                                    ? route('admin.questionnaire.general-questions.update', [
                                        'questionnaireId' => $generalQuestionnaire->id,
                                        'id' => $question->id,
                                    ])
                                    : route('admin.questionnaire.general-questions.store', $generalQuestionnaire->id) }}"
                                method="POST">
                                @csrf
                                @if (request()->has('edit_question'))
                                    @method('PUT')
                                @endif

                                <div class="row">
                                    <!-- Kolom Kiri - Informasi Dasar -->
                                    <div class="col-md-6">
                                        <div class="mb-3">
                                            <label class="form-label">Teks Pertanyaan <span
                                                    class="text-danger">*</span></label>
                                            <textarea class="form-control" name="question_text" rows="3" required
                                                placeholder="Masukkan teks pertanyaan di sini" id="questionText">{{ old('question_text', $question->question_text ?? '') }}</textarea>
                                            <small class="text-muted">Contoh: "Seberapa puas Anda dengan kualitas
                                                pengajaran?"</small>
                                        </div>

                                        <div class="mb-3">
                                            <label class="form-label">Tipe Pertanyaan <span
                                                    class="text-danger">*</span></label>
                                            <select class="form-select" name="question_type" id="questionType" required>
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
                                                <optgroup label="Skala Rating">
                                                    <option value="likert_scale"
                                                        {{ old('question_type', $question->question_type ?? '') == 'likert_scale' ? 'selected' : '' }}>
                                                        Skala Likert (1-5)
                                                    </option>
                                                    <option value="competency_scale"
                                                        {{ old('question_type', $question->question_type ?? '') == 'competency_scale' ? 'selected' : '' }}>
                                                        Skala Kompetensi
                                                    </option>
                                                </optgroup>
                                                <optgroup label="Format Tabel/Matriks">
                                                    <option value="radio_per_row"
                                                        {{ old('question_type', $question->question_type ?? '') == 'radio_per_row' ? 'selected' : '' }}>
                                                        Radio per Baris (Tabel)
                                                    </option>
                                                    <option value="checkbox_per_row"
                                                        {{ old('question_type', $question->question_type ?? '') == 'checkbox_per_row' ? 'selected' : '' }}>
                                                        Checkbox per Baris (Tabel)
                                                    </option>
                                                    <option value="likert_per_row"
                                                        {{ old('question_type', $question->question_type ?? '') == 'likert_per_row' ? 'selected' : '' }}>
                                                        Likert per Baris (Skala Tabel)
                                                    </option>
                                                </optgroup>
                                            </select>
                                            <div class="mt-2">
                                                <small class="text-info d-block" id="questionTypeHelp">
                                                    <i class="fas fa-info-circle"></i> Pilih tipe pertanyaan untuk
                                                    menampilkan opsi yang sesuai
                                                </small>
                                            </div>
                                        </div>

                                        <div class="mb-3">
                                            <label class="form-label">Deskripsi (Optional)</label>
                                            <textarea class="form-control" name="description" rows="2"
                                                placeholder="Penjelasan tambahan tentang pertanyaan">{{ old('description', $question->description ?? '') }}</textarea>
                                            <small class="text-muted">Contoh: "Jawaban ini akan membantu kami meningkatkan
                                                kualitas"</small>
                                        </div>
                                    </div>

                                    <!-- Kolom Kanan - Pengaturan -->
                                    <div class="col-md-6">
                                        <div class="mb-3">
                                            <label class="form-label">Urutan</label>
                                            <input type="number" class="form-control" name="order"
                                                value="{{ old('order', $question->order ?? $questions->count() + 1) }}"
                                                min="1" step="1">
                                            <small class="text-muted">Urutan tampil pertanyaan (1 = pertama)</small>
                                        </div>

                                        <div class="mb-3">
                                            <label class="form-label">Points</label>
                                            <input type="number" class="form-control" name="points"
                                                value="{{ old('points', $question->points ?? 0) }}" min="0"
                                                step="1">
                                            <small class="text-muted">Poin untuk jawaban yang benar (opsional)</small>
                                        </div>

                                        <div class="mb-3">
                                            <label class="form-label">Helper Text</label>
                                            <textarea class="form-control" name="helper_text" rows="2" placeholder="Teks bantuan untuk pengisi">{{ old('helper_text', $question->helper_text ?? '') }}</textarea>
                                            <small class="text-muted">Contoh: "Pilih semua yang sesuai" atau "Isi dengan
                                                format DD/MM/YYYY"</small>
                                        </div>

                                        <div class="mb-3">
                                            <div class="row">
                                                <div class="col-md-4">
                                                    <div class="form-check form-switch">
                                                        <input class="form-check-input" type="checkbox"
                                                            name="is_required" id="isRequiredForm"
                                                            {{ old('is_required', $question->is_required ?? true) ? 'checked' : '' }}>
                                                        <label class="form-check-label" for="isRequiredForm">
                                                            Wajib Diisi
                                                        </label>
                                                    </div>
                                                </div>
                                                <div class="col-md-4">
                                                    <div class="form-check form-switch">
                                                        <input class="form-check-input" type="checkbox"
                                                            name="has_other_option" id="hasOtherOption"
                                                            {{ old('has_other_option', $question->has_other_option ?? false) ? 'checked' : '' }}>
                                                        <label class="form-check-label" for="hasOtherOption">
                                                            Opsi Lain
                                                        </label>
                                                    </div>
                                                </div>
                                                <div class="col-md-4">
                                                    <div class="form-check form-switch">
                                                        <input class="form-check-input" type="checkbox"
                                                            name="has_none_option" id="hasNoneOption"
                                                            {{ old('has_none_option', $question->has_none_option ?? false) ? 'checked' : '' }}>
                                                        <label class="form-check-label" for="hasNoneOption">
                                                            Opsi Tidak Ada
                                                        </label>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <!-- Container untuk semua tipe input -->
                                <div id="inputContainers">
                                    <!-- Opsi untuk Radio, Dropdown, Checkbox -->
                                    <div id="optionsContainer" class="mb-3" style="display: none;">
                                        <div class="card">
                                            <div
                                                class="card-header bg-light d-flex justify-content-between align-items-center">
                                                <label class="form-label mb-0">Pilihan Jawaban</label>
                                                <button type="button" class="btn btn-sm btn-outline-primary"
                                                    onclick="addOptionExample()">
                                                    <i class="fas fa-plus me-1"></i> Contoh
                                                </button>
                                            </div>
                                            <div class="card-body">
                                                <textarea class="form-control" name="options" rows="6" id="optionsInput"
                                                    placeholder="Masukkan setiap pilihan dalam baris terpisah">{{ old('options', $optionsText) }}</textarea>
                                                <div class="mt-2">
                                                    <small class="text-muted d-block">Format: Setiap baris = satu
                                                        pilihan</small>
                                                    <small class="text-info d-block">Contoh untuk
                                                        Radio/Dropdown/Checkbox:</small>
                                                    <pre class="bg-light p-2 mt-1 small">
                                                        Sangat Puas
                                                        Puas
                                                        Cukup Puas
                                                        Kurang Puas
                                                        Tidak Puas</pre>
                                                    <small class="text-info d-block">Contoh dengan nilai khusus:</small>
                                                    <pre class="bg-light p-2 mt-1 small">
                                                        Ya, email|Ya, silakan kirim informasi via email
                                                        Ya, WhatsApp|Ya, silakan hubungi via WhatsApp
                                                        Tidak, terima kasih</pre>
                                                </div>
                                            </div>
                                        </div>
                                    </div>

                                    <!-- Item Baris untuk format tabel -->
                                    <div id="rowItemsContainer" class="mb-3" style="display: none;">
                                        <div class="card">
                                            <div
                                                class="card-header bg-light d-flex justify-content-between align-items-center">
                                                <label class="form-label mb-0">Item Baris (Untuk format tabel)</label>
                                                <button type="button" class="btn btn-sm btn-outline-primary"
                                                    onclick="addRowItemExample()">
                                                    <i class="fas fa-plus me-1"></i> Contoh
                                                </button>
                                            </div>
                                            <div class="card-body">
                                                <textarea class="form-control" name="row_items" rows="6" id="rowItemsInput"
                                                    placeholder="Masukkan setiap item dalam baris terpisah, format: key|label">{{ old('row_items', $rowItemsText) }}</textarea>
                                                <div class="mt-2">
                                                    <small class="text-muted d-block">Format: key|label (pisahkan dengan
                                                        |)</small>
                                                    <small class="text-info d-block">Contoh untuk Likert per Baris:</small>
                                                    <pre class="bg-light p-2 mt-1 small">
                                                        ethics|Etika
                                                        expertise|Keahlian Bidang Ilmu
                                                        english|Bahasa Inggris
                                                        it_skills|Penggunaan Teknologi Informasi
                                                        communication|Komunikasi</pre>
                                                    <small class="text-info d-block">Contoh untuk Radio per Baris:</small>
                                                    <pre class="bg-light p-2 mt-1 small">
                                                        lecture|Perkuliahan
                                                        practice|Praktikum
                                                        discussion|Diskusi
                                                        research|Penelitian
                                                        internship|Magang</pre>
                                                </div>
                                            </div>
                                        </div>
                                    </div>

                                    <!-- Opsi Skala -->
                                    <div id="scaleOptionsContainer" class="mb-3" style="display: none;">
                                        <div class="card">
                                            <div
                                                class="card-header bg-light d-flex justify-content-between align-items-center">
                                                <label class="form-label mb-0">Opsi Skala</label>
                                                <button type="button" class="btn btn-sm btn-outline-primary"
                                                    onclick="addScaleExample()">
                                                    <i class="fas fa-plus me-1"></i> Contoh
                                                </button>
                                            </div>
                                            <div class="card-body">
                                                <div class="row">
                                                    <div class="col-md-6">
                                                        <textarea class="form-control" name="scale_options" rows="3" id="scaleOptionsInput"
                                                            placeholder="Masukkan opsi skala, pisahkan dengan koma">{{ old('scale_options', $scaleOptionsText) }}</textarea>
                                                        <small class="text-muted mt-2 d-block">Format: angka atau label,
                                                            pisahkan dengan koma</small>
                                                    </div>
                                                    <div class="col-md-6">
                                                        <div class="mb-3">
                                                            <label class="form-label">Label Skala Rendah</label>
                                                            <input type="text" class="form-control"
                                                                name="scale_label_low"
                                                                value="{{ old('scale_label_low', $question->scale_label_low ?? 'Sangat Rendah') }}"
                                                                placeholder="Contoh: Sangat Rendah">
                                                        </div>
                                                        <div class="mb-0">
                                                            <label class="form-label">Label Skala Tinggi</label>
                                                            <input type="text" class="form-control"
                                                                name="scale_label_high"
                                                                value="{{ old('scale_label_high', $question->scale_label_high ?? 'Sangat Tinggi') }}"
                                                                placeholder="Contoh: Sangat Tinggi">
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="mt-3">
                                                    <small class="text-info d-block">Contoh untuk Skala Likert:</small>
                                                    <pre class="bg-light p-2 mt-1 small">1, 2, 3, 4, 5 (Skala 1-5 dengan angka)</pre>
                                                    <small class="text-info d-block">Contoh dengan label:</small>
                                                    <pre class="bg-light p-2 mt-1 small">Sangat Tidak Setuju, Tidak Setuju, Netral, Setuju, Sangat Setuju</pre>
                                                    <small class="text-info d-block">Contoh untuk Skala Kompetensi:</small>
                                                    <pre class="bg-light p-2 mt-1 small">Pemula, Dasar, Menengah, Mahir, Expert</pre>
                                                </div>
                                            </div>
                                        </div>
                                    </div>

                                    <!-- Pengaturan tambahan untuk tipe tertentu -->
                                    <div id="additionalSettings" class="mb-3" style="display: none;">
                                        <div class="card">
                                            <div class="card-header bg-light">
                                                <label class="form-label mb-0">Pengaturan Tambahan</label>
                                            </div>
                                            <div class="card-body">
                                                <div class="row">
                                                    <div id="minMaxContainer" style="display: none;">
                                                        <div class="col-md-6 mb-3">
                                                            <label class="form-label">Nilai Minimum</label>
                                                            <input type="number" class="form-control" name="min_value"
                                                                value="{{ old('min_value', $question->min_value ?? '') }}"
                                                                placeholder="Contoh: 0">
                                                        </div>
                                                        <div class="col-md-6 mb-3">
                                                            <label class="form-label">Nilai Maksimum</label>
                                                            <input type="number" class="form-control" name="max_value"
                                                                value="{{ old('max_value', $question->max_value ?? '') }}"
                                                                placeholder="Contoh: 100">
                                                        </div>
                                                    </div>
                                                    <div id="maxLengthContainer" style="display: none;">
                                                        <div class="col-md-6 mb-3">
                                                            <label class="form-label">Maksimal Panjang Teks</label>
                                                            <input type="number" class="form-control" name="max_length"
                                                                value="{{ old('max_length', $question->max_length ?? '') }}"
                                                                placeholder="Contoh: 255 karakter">
                                                        </div>
                                                    </div>
                                                    <div id="rowsContainer" style="display: none;">
                                                        <div class="col-md-6 mb-3">
                                                            <label class="form-label">Jumlah Baris Textarea</label>
                                                            <input type="number" class="form-control" name="rows"
                                                                value="{{ old('rows', $question->rows ?? 4) }}"
                                                                min="1" max="10" placeholder="Contoh: 4">
                                                        </div>
                                                    </div>
                                                    <div id="maxSelectionsContainer" style="display: none;">
                                                        <div class="col-md-6 mb-3">
                                                            <label class="form-label">Maksimal Pilihan (Checkbox)</label>
                                                            <input type="number" class="form-control"
                                                                name="max_selections"
                                                                value="{{ old('max_selections', $question->max_selections ?? '') }}"
                                                                min="1" placeholder="Contoh: 3">
                                                            <small class="text-muted">Kosongkan jika tidak dibatasi</small>
                                                        </div>
                                                    </div>
                                                    <div id="placeholderContainer" style="display: none;">
                                                        <div class="col-12 mb-3">
                                                            <label class="form-label">Placeholder Text</label>
                                                            <input type="text" class="form-control" name="placeholder"
                                                                value="{{ old('placeholder', $question->placeholder ?? '') }}"
                                                                placeholder="Contoh: Masukkan nama perusahaan">
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>

                                    <!-- Preview Pertanyaan -->
                                    <div id="questionPreview" class="mb-3" style="display: none;">
                                        <div class="card">
                                            <div class="card-header bg-light">
                                                <label class="form-label mb-0">
                                                    <i class="fas fa-eye me-2"></i> Preview Pertanyaan
                                                </label>
                                            </div>
                                            <div class="card-body">
                                                <div id="previewContent" class="p-3 border rounded bg-white">
                                                    <p class="text-muted mb-0">Preview akan muncul di sini...</p>
                                                </div>
                                                <small class="text-muted mt-2 d-block">
                                                    <i class="fas fa-info-circle"></i> Preview ini menunjukkan bagaimana
                                                    pertanyaan akan terlihat bagi alumni
                                                </small>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <div class="d-flex justify-content-between mt-4">
                                    <a href="{{ route('admin.questionnaire.general-questionnaires', ['category_id' => $selectedCategory->id]) }}"
                                        class="btn btn-secondary">
                                        <i class="fas fa-times-circle me-2"></i> Batal
                                    </a>
                                    <button type="submit" class="btn btn-primary">
                                        <i class="fas fa-save me-2"></i>
                                        {{ request()->has('edit_question') ? 'Update' : 'Simpan' }}
                                    </button>
                                </div>
                            </form>
                        </div>
                    </div>

                    @push('scripts')
                        <script>
                            document.addEventListener('DOMContentLoaded', function() {
                                const questionType = document.getElementById('questionType');
                                const optionsContainer = document.getElementById('optionsContainer');
                                const rowItemsContainer = document.getElementById('rowItemsContainer');
                                const scaleOptionsContainer = document.getElementById('scaleOptionsContainer');
                                const additionalSettings = document.getElementById('additionalSettings');
                                const questionPreview = document.getElementById('questionPreview');
                                const previewContent = document.getElementById('previewContent');
                                const questionText = document.getElementById('questionText');

                                // Helper elements
                                const minMaxContainer = document.getElementById('minMaxContainer');
                                const maxLengthContainer = document.getElementById('maxLengthContainer');
                                const rowsContainer = document.getElementById('rowsContainer');
                                const maxSelectionsContainer = document.getElementById('maxSelectionsContainer');
                                const placeholderContainer = document.getElementById('placeholderContainer');
                                const questionTypeHelp = document.getElementById('questionTypeHelp');

                                // Function to update question type help text
                                function updateQuestionTypeHelp() {
                                    const type = questionType.value;
                                    const helpTexts = {
                                        'text': 'Input teks singkat untuk jawaban pendek',
                                        'textarea': 'Input teks panjang untuk jawaban deskriptif',
                                        'number': 'Input angka untuk data numerik',
                                        'date': 'Input tanggal untuk data waktu',
                                        'radio': 'Pilihan tunggal (hanya satu jawaban)',
                                        'dropdown': 'Dropdown menu untuk pilihan tunggal',
                                        'checkbox': 'Pilihan ganda (bisa pilih banyak)',
                                        'likert_scale': 'Skala 1-5 untuk mengukur sikap/opini',
                                        'competency_scale': 'Skala untuk mengukur kompetensi/keahlian',
                                        'radio_per_row': 'Tabel dengan pilihan radio untuk setiap baris',
                                        'checkbox_per_row': 'Tabel dengan pilihan checkbox untuk setiap baris',
                                        'likert_per_row': 'Tabel dengan skala likert untuk setiap baris'
                                    };

                                    if (type && helpTexts[type]) {
                                        questionTypeHelp.innerHTML = `<i class="fas fa-info-circle"></i> ${helpTexts[type]}`;
                                    }
                                }

                                // Function to update form fields based on question type
                                function updateFormFields() {
                                    if (!questionType) return;

                                    const selectedType = questionType.value;

                                    // Hide all containers
                                    optionsContainer.style.display = 'none';
                                    rowItemsContainer.style.display = 'none';
                                    scaleOptionsContainer.style.display = 'none';
                                    additionalSettings.style.display = 'none';
                                    questionPreview.style.display = 'none';

                                    // Hide all additional settings
                                    minMaxContainer.style.display = 'none';
                                    maxLengthContainer.style.display = 'none';
                                    rowsContainer.style.display = 'none';
                                    maxSelectionsContainer.style.display = 'none';
                                    placeholderContainer.style.display = 'none';

                                    // Show relevant containers based on question type
                                    switch (selectedType) {
                                        case 'radio':
                                        case 'dropdown':
                                        case 'checkbox':
                                            optionsContainer.style.display = 'block';
                                            additionalSettings.style.display = 'block';
                                            placeholderContainer.style.display = 'block';
                                            if (selectedType === 'checkbox') {
                                                maxSelectionsContainer.style.display = 'block';
                                            }
                                            break;

                                        case 'likert_scale':
                                        case 'competency_scale':
                                            scaleOptionsContainer.style.display = 'block';
                                            break;

                                        case 'radio_per_row':
                                        case 'checkbox_per_row':
                                            optionsContainer.style.display = 'block';
                                            rowItemsContainer.style.display = 'block';
                                            additionalSettings.style.display = 'block';
                                            break;

                                        case 'likert_per_row':
                                            rowItemsContainer.style.display = 'block';
                                            scaleOptionsContainer.style.display = 'block';
                                            break;

                                        case 'text':
                                            additionalSettings.style.display = 'block';
                                            maxLengthContainer.style.display = 'block';
                                            placeholderContainer.style.display = 'block';
                                            break;

                                        case 'textarea':
                                            additionalSettings.style.display = 'block';
                                            maxLengthContainer.style.display = 'block';
                                            rowsContainer.style.display = 'block';
                                            placeholderContainer.style.display = 'block';
                                            break;

                                        case 'number':
                                            additionalSettings.style.display = 'block';
                                            minMaxContainer.style.display = 'block';
                                            placeholderContainer.style.display = 'block';
                                            break;

                                        case 'date':
                                            additionalSettings.style.display = 'block';
                                            placeholderContainer.style.display = 'block';
                                            break;
                                    }

                                    // Show preview if we have a question type
                                    if (selectedType) {
                                        questionPreview.style.display = 'block';
                                        updatePreview();
                                    }

                                    // Update help text
                                    updateQuestionTypeHelp();
                                }

                                // Function to update preview
                                function updatePreview() {
                                    const type = questionType.value;
                                    const text = questionText.value;

                                    if (!type || !text) {
                                        previewContent.innerHTML = '<p class="text-muted mb-0">Preview akan muncul di sini...</p>';
                                        return;
                                    }

                                    let previewHTML = `<h6 class="fw-bold">${text}</h6>`;

                                    switch (type) {
                                        case 'text':
                                            previewHTML += `
                            <div class="mt-2">
                                <input type="text" class="form-control" placeholder="Jawaban..." disabled>
                            </div>`;
                                            break;

                                        case 'textarea':
                                            previewHTML += `
                            <div class="mt-2">
                                <textarea class="form-control" rows="3" placeholder="Jawaban..." disabled></textarea>
                            </div>`;
                                            break;

                                        case 'number':
                                            previewHTML += `
                            <div class="mt-2">
                                <input type="number" class="form-control" placeholder="Angka..." disabled>
                            </div>`;
                                            break;

                                        case 'date':
                                            previewHTML += `
                            <div class="mt-2">
                                <input type="date" class="form-control" disabled>
                            </div>`;
                                            break;

                                        case 'radio':
                                            previewHTML += `
                            <div class="mt-2">
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
                            </div>`;
                                            break;

                                        case 'dropdown':
                                            previewHTML += `
                            <div class="mt-2">
                                <select class="form-select" disabled>
                                    <option>Pilih opsi...</option>
                                    <option>Opsi 1</option>
                                    <option>Opsi 2</option>
                                    <option>Opsi 3</option>
                                </select>
                            </div>`;
                                            break;

                                        case 'checkbox':
                                            previewHTML += `
                            <div class="mt-2">
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
                            </div>`;
                                            break;

                                        case 'likert_scale':
                                            previewHTML += `
                            <div class="mt-2">
                                <div class="d-flex justify-content-between">
                                    <div class="text-center">
                                        <input type="radio" name="preview_likert" disabled><br>
                                        <small>1</small>
                                    </div>
                                    <div class="text-center">
                                        <input type="radio" name="preview_likert" disabled><br>
                                        <small>2</small>
                                    </div>
                                    <div class="text-center">
                                        <input type="radio" name="preview_likert" disabled><br>
                                        <small>3</small>
                                    </div>
                                    <div class="text-center">
                                        <input type="radio" name="preview_likert" disabled><br>
                                        <small>4</small>
                                    </div>
                                    <div class="text-center">
                                        <input type="radio" name="preview_likert" disabled><br>
                                        <small>5</small>
                                    </div>
                                </div>
                            </div>`;
                                            break;

                                        case 'likert_per_row':
                                            previewHTML += `
                            <div class="mt-2">
                                <table class="table table-sm">
                                    <tr>
                                        <td>Kompetensi 1</td>
                                        <td class="text-center">
                                            <input type="radio" name="preview_likert_row_1" disabled>
                                            <input type="radio" name="preview_likert_row_1" disabled>
                                            <input type="radio" name="preview_likert_row_1" disabled>
                                            <input type="radio" name="preview_likert_row_1" disabled>
                                            <input type="radio" name="preview_likert_row_1" disabled>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td>Kompetensi 2</td>
                                        <td class="text-center">
                                            <input type="radio" name="preview_likert_row_2" disabled>
                                            <input type="radio" name="preview_likert_row_2" disabled>
                                            <input type="radio" name="preview_likert_row_2" disabled>
                                            <input type="radio" name="preview_likert_row_2" disabled>
                                            <input type="radio" name="preview_likert_row_2" disabled>
                                        </td>
                                    </tr>
                                </table>
                            </div>`;
                                            break;
                                    }

                                    previewContent.innerHTML = previewHTML;
                                }

                                // Event listeners
                                if (questionType) {
                                    updateFormFields();
                                    questionType.addEventListener('change', updateFormFields);
                                }

                                if (questionText) {
                                    questionText.addEventListener('input', updatePreview);
                                }

                                // Auto-suggest examples
                                window.addOptionExample = function() {
                                    const type = questionType.value;
                                    let example = '';

                                    switch (type) {
                                        case 'radio':
                                        case 'dropdown':
                                            example =
                                                `Sangat Puas
                                                Puas
                                                Cukup Puas
                                                Kurang Puas
                                                Tidak Puas`;
                                            break;
                                        case 'checkbox':
                                            example =
                                                `Website Karir Kampus
                                                LinkedIn
                                                Job Fair
                                                Rekomendasi Dosen
                                                Media Sosial`;
                                            break;
                                        case 'radio_per_row':
                                        case 'checkbox_per_row':
                                            example =
                                                `Sering
                                                Kadang
                                                Jarang
                                                Tidak Pernah`;
                                            break;
                                    }

                                    if (example) {
                                        const textarea = document.getElementById('optionsInput');
                                        textarea.value = example;
                                        updatePreview();
                                    }
                                };

                                window.addRowItemExample = function() {
                                    const type = questionType.value;
                                    let example = '';

                                    switch (type) {
                                        case 'likert_per_row':
                                            example =
                                                `ethics|Etika
                                                expertise|Keahlian Bidang Ilmu
                                                english|Bahasa Inggris
                                                it_skills|Penggunaan Teknologi Informasi
                                                communication|Komunikasi`;
                                            break;
                                        case 'radio_per_row':
                                            example =
                                                `lecture|Perkuliahan
                                                practice|Praktikum
                                                discussion|Diskusi
                                                research|Penelitian
                                                internship|Magang`;
                                            break;
                                        case 'checkbox_per_row':
                                            example =
                                                `communication|Komunikasi
                                                teamwork|Kerja Sama
                                                leadership|Kepemimpinan
                                                technical|Teknis`;
                                            break;
                                    }

                                    if (example) {
                                        const textarea = document.getElementById('rowItemsInput');
                                        textarea.value = example;
                                        updatePreview();
                                    }
                                };

                                window.addScaleExample = function() {
                                    const type = questionType.value;
                                    let example = '';

                                    switch (type) {
                                        case 'likert_scale':
                                            example = '1, 2, 3, 4, 5';
                                            break;
                                        case 'competency_scale':
                                            example = 'Pemula, Dasar, Menengah, Mahir, Expert';
                                            break;
                                        case 'likert_per_row':
                                            example = '1, 2, 3, 4, 5';
                                            break;
                                    }

                                    if (example) {
                                        const textarea = document.getElementById('scaleOptionsInput');
                                        textarea.value = example;
                                        updatePreview();
                                    }
                                };
                            });
                        </script>
                    @endpush
                @endif
            @endif
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
                    <button type="button" class="btn btn-danger" id="confirmDeleteGeneralQuestions">Ya, Hapus</button>
                </div>
            </div>
        </div>
    </div>

    @push('scripts')
        <script>
            document.addEventListener('DOMContentLoaded', function() {
                // Aktifkan tab pertama
                const firstTab = new bootstrap.Tab(document.querySelector('#questions-tab'));
                firstTab.show();

                // Show/hide containers based on question type
                const questionType = document.getElementById('questionType');
                const optionsContainer = document.getElementById('optionsContainer');
                const rowItemsContainer = document.getElementById('rowItemsContainer');
                const scaleOptionsContainer = document.getElementById('scaleOptionsContainer');

                function updateFormFields() {
                    if (!questionType) return;

                    const selectedType = questionType.value;

                    // Hide all containers
                    if (optionsContainer) optionsContainer.style.display = 'none';
                    if (rowItemsContainer) rowItemsContainer.style.display = 'none';
                    if (scaleOptionsContainer) scaleOptionsContainer.style.display = 'none';

                    // Show relevant containers
                    switch (selectedType) {
                        case 'radio':
                        case 'dropdown':
                        case 'checkbox':
                        case 'likert_scale':
                        case 'competency_scale':
                            if (optionsContainer) optionsContainer.style.display = 'block';
                            break;
                        case 'radio_per_row':
                        case 'checkbox_per_row':
                        case 'likert_per_row':
                            if (rowItemsContainer) rowItemsContainer.style.display = 'block';
                            break;
                    }

                    // Show scale options for scale-based questions
                    if (selectedType === 'likert_scale' || selectedType === 'competency_scale' || selectedType ===
                        'likert_per_row') {
                        if (scaleOptionsContainer) scaleOptionsContainer.style.display = 'block';
                    }
                }

                if (questionType) {
                    updateFormFields();
                    questionType.addEventListener('change', updateFormFields);
                }

                // Select all checkbox for general questions
                const selectAll = document.getElementById('selectAllGeneralQuestions');
                const generalQuestionCheckboxes = document.querySelectorAll('.general-question-checkbox');
                const deleteSelectedBtn = document.getElementById('deleteSelectedGeneralQuestions');

                function updateDeleteButton() {
                    const checkedCount = document.querySelectorAll('.general-question-checkbox:checked').length;
                    if (deleteSelectedBtn) {
                        deleteSelectedBtn.disabled = checkedCount === 0;
                    }
                }

                if (selectAll && generalQuestionCheckboxes.length > 0) {
                    selectAll.addEventListener('change', function() {
                        generalQuestionCheckboxes.forEach(checkbox => {
                            checkbox.checked = this.checked;
                        });
                        updateDeleteButton();
                    });
                }

                if (generalQuestionCheckboxes.length > 0) {
                    generalQuestionCheckboxes.forEach(checkbox => {
                        checkbox.addEventListener('change', updateDeleteButton);
                    });
                }

                // Handle delete selected general questions
                if (deleteSelectedBtn) {
                    deleteSelectedBtn.addEventListener('click', function() {
                        const selectedIds = [];
                        document.querySelectorAll('.general-question-checkbox:checked').forEach(checkbox => {
                            selectedIds.push(checkbox.value);
                        });

                        if (selectedIds.length === 0) {
                            return;
                        }

                        document.getElementById('deleteCount').textContent = selectedIds.length + ' pertanyaan';

                        const deleteModal = new bootstrap.Modal(document.getElementById('deleteModal'));
                        deleteModal.show();

                        document.getElementById('confirmDeleteGeneralQuestions').onclick = function() {
                            fetch("{{ $generalQuestionnaire ? route('admin.questionnaire.general-questions.delete-selected', $generalQuestionnaire->id) : '' }}", {
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
            });
        </script>
    @endpush
@endsection
