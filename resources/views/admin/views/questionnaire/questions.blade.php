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
                        <a href="{{ route('admin.questionnaire.questions', [$category->id, $questionnaire->id]) }}"
                            class="btn btn-sm btn-secondary">
                            <i class="bi bi-arrow-left"></i> Back
                        </a>
                    </div>
                    <div class="card-body">
                        <form
                            action="{{ isset($question) ? route('admin.questionnaire.questions.update', [$category->id, $questionnaire->id, $question->id]) : route('admin.questionnaire.questions.store', [$category->id, $questionnaire->id]) }}"
                            method="POST" id="questionForm">
                            @csrf
                            @if (isset($question))
                                @method('PUT')
                            @endif

                            <div class="mb-3">
                                <label class="form-label">Teks Pertanyaan <span class="text-danger">*</span></label>
                                <textarea class="form-control @error('question_text') is-invalid @enderror" name="question_text" rows="3" required
                                    placeholder="Masukkan teks pertanyaan di sini">{{ old('question_text', $question->question_text ?? '') }}</textarea>
                                @error('question_text')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="mb-3">
                                <label class="form-label">Tipe Pertanyaan <span class="text-danger">*</span></label>
                                <select class="form-select @error('question_type') is-invalid @enderror"
                                    name="question_type" id="questionType" required>
                                    <option value="">Pilih Tipe Pertanyaan</option>
                                    <option value="text"
                                        {{ old('question_type', $question->question_type ?? '') == 'text' ? 'selected' : '' }}>
                                        Text (Input Teks Singkat)</option>
                                    <option value="textarea"
                                        {{ old('question_type', $question->question_type ?? '') == 'textarea' ? 'selected' : '' }}>
                                        Text Area (Input Teks Panjang)</option>
                                    <option value="number"
                                        {{ old('question_type', $question->question_type ?? '') == 'number' ? 'selected' : '' }}>
                                        Number (Input Angka)</option>
                                    <option value="date"
                                        {{ old('question_type', $question->question_type ?? '') == 'date' ? 'selected' : '' }}>
                                        Date (Input Tanggal)</option>
                                    <option value="radio"
                                        {{ old('question_type', $question->question_type ?? '') == 'radio' ? 'selected' : '' }}>
                                        Radio Button (Pilihan Tunggal)</option>
                                    <option value="dropdown"
                                        {{ old('question_type', $question->question_type ?? '') == 'dropdown' ? 'selected' : '' }}>
                                        Dropdown (Pilihan Dropdown)</option>
                                    <option value="checkbox"
                                        {{ old('question_type', $question->question_type ?? '') == 'checkbox' ? 'selected' : '' }}>
                                        Checkbox (Pilihan Ganda)</option>
                                    <option value="likert_scale"
                                        {{ old('question_type', $question->question_type ?? '') == 'likert_scale' ? 'selected' : '' }}>
                                        Skala Likert (Skala 1-5)</option>
                                    <option value="competency_scale"
                                        {{ old('question_type', $question->question_type ?? '') == 'competency_scale' ? 'selected' : '' }}>
                                        Skala Kompetensi</option>
                                    <option value="radio_per_row"
                                        {{ old('question_type', $question->question_type ?? '') == 'radio_per_row' ? 'selected' : '' }}>
                                        Radio per Baris (Matriks Radio)</option>
                                    <option value="checkbox_per_row"
                                        {{ old('question_type', $question->question_type ?? '') == 'checkbox_per_row' ? 'selected' : '' }}>
                                        Checkbox per Baris (Matriks Checkbox)</option>
                                    <option value="likert_per_row"
                                        {{ old('question_type', $question->question_type ?? '') == 'likert_per_row' ? 'selected' : '' }}>
                                        Likert per Baris (Matriks Skala)</option>
                                </select>
                                <small class="text-muted">Pilih jenis pertanyaan sesuai kebutuhan</small>
                                @error('question_type')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="mb-3">
                                <label class="form-label">Deskripsi (Optional)</label>
                                <textarea class="form-control @error('description') is-invalid @enderror" name="description" rows="2"
                                    placeholder="Penjelasan tambahan tentang pertanyaan">{{ old('description', $question->description ?? '') }}</textarea>
                                @error('description')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <!-- Options Container (for radio, dropdown, checkbox) -->
                            <div class="card mb-3" id="optionsContainer" style="display: none;">
                                <div class="card-header">
                                    <label class="form-label mb-0">Pilihan Jawaban</label>
                                </div>
                                <div class="card-body">
                                    <p class="text-muted small mb-2">Masukkan setiap pilihan dalam baris terpisah</p>
                                    <textarea class="form-control @error('options') is-invalid @enderror" name="options" id="optionsTextarea" rows="5"
                                        placeholder="Contoh:
Sangat Setuju
Setuju
Netral
Tidak Setuju
Sangat Tidak Setuju">{{ old('options', $question->options ? implode("\n", json_decode($question->options, true)) : '') }}</textarea>
                                    @error('options')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>

                            <!-- Row Items Container (for per_row types) -->
                            <div class="card mb-3" id="rowItemsContainer" style="display: none;">
                                <div class="card-header">
                                    <label class="form-label mb-0">Item Baris</label>
                                </div>
                                <div class="card-body">
                                    <p class="text-muted small mb-2">Format: key|value (satu per baris)</p>
                                    <textarea class="form-control @error('row_items') is-invalid @enderror" name="row_items" id="rowItemsTextarea"
                                        rows="5"
                                        placeholder="Format: key|value
Contoh:
ethics|Etika
expertise|Keahlian Bidang Ilmu
english|Bahasa Inggris">{{ old(
    'row_items',
    $question && $question->row_items
        ? implode(
            "\n",
            array_map(
                function ($key, $value) {
                    return $key . '|' . $value;
                },
                array_keys(json_decode($question->row_items, true)),
                json_decode($question->row_items, true),
            ),
        )
        : '',
) }}</textarea>
                                    @error('row_items')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>

                            <!-- Scale Options Container -->
                            <div class="card mb-3" id="scaleOptionsContainer" style="display: none;">
                                <div class="card-header">
                                    <label class="form-label mb-0">Opsi Skala</label>
                                </div>
                                <div class="card-body">
                                    <input type="text" class="form-control @error('scale_options') is-invalid @enderror"
                                        name="scale_options" id="scaleOptionsInput"
                                        value="{{ old(
                                            'scale_options',
                                            $question && $question->scale_options ? implode(',', json_decode($question->scale_options, true)) : '1,2,3,4,5',
                                        ) }}"
                                        placeholder="1,2,3,4,5">
                                    <small class="text-muted">Pisahkan dengan koma (contoh: 1,2,3,4,5)</small>
                                    @error('scale_options')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>

                            <div class="row">
                                <div class="col-md-6 mb-3">
                                    <label class="form-label">Urutan</label>
                                    <input type="number" class="form-control @error('order') is-invalid @enderror"
                                        name="order"
                                        value="{{ old('order', $question->order ?? $questions->count() + 1) }}"
                                        min="1">
                                    @error('order')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>

                                <div class="col-md-6 mb-3">
                                    <label class="form-label">Points</label>
                                    <input type="number" class="form-control @error('points') is-invalid @enderror"
                                        name="points" value="{{ old('points', $question->points ?? 0) }}"
                                        min="0">
                                    @error('points')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>

                            <div class="row">
                                <div class="col-md-4 mb-3">
                                    <div class="form-check form-switch">
                                        <input class="form-check-input" type="checkbox" name="is_required"
                                            id="isRequired"
                                            {{ old('is_required', $question->is_required ?? true) ? 'checked' : '' }}>
                                        <label class="form-check-label" for="isRequired">
                                            Wajib Diisi
                                        </label>
                                    </div>
                                </div>

                                <div class="col-md-4 mb-3">
                                    <div class="form-check form-switch">
                                        <input class="form-check-input" type="checkbox" name="has_other_option"
                                            id="hasOtherOption"
                                            {{ old('has_other_option', $question->has_other_option ?? false) ? 'checked' : '' }}>
                                        <label class="form-check-label" for="hasOtherOption">
                                            Punya Opsi Lain
                                        </label>
                                    </div>
                                </div>

                                <div class="col-md-4 mb-3">
                                    <div class="form-check form-switch">
                                        <input class="form-check-input" type="checkbox" name="has_none_option"
                                            id="hasNoneOption"
                                            {{ old('has_none_option', $question->has_none_option ?? false) ? 'checked' : '' }}>
                                        <label class="form-check-label" for="hasNoneOption">
                                            Punya Opsi Tidak Ada
                                        </label>
                                    </div>
                                </div>
                            </div>

                            <div class="mb-3">
                                <label class="form-label">Placeholder (untuk text/textarea)</label>
                                <input type="text" class="form-control @error('placeholder') is-invalid @enderror"
                                    name="placeholder" value="{{ old('placeholder', $question->placeholder ?? '') }}"
                                    placeholder="Contoh: Masukkan jawaban Anda di sini...">
                                @error('placeholder')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="mb-3">
                                <label class="form-label">Helper Text</label>
                                <textarea class="form-control @error('helper_text') is-invalid @enderror" name="helper_text" rows="2"
                                    placeholder="Teks bantuan untuk pengisi">{{ old('helper_text', $question->helper_text ?? '') }}</textarea>
                                @error('helper_text')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="d-flex justify-content-between">
                                <a href="{{ route('admin.questionnaire.questions', [$category->id, $questionnaire->id]) }}"
                                    class="btn btn-secondary">
                                    <i class="bi bi-x-circle me-2"></i> Batal
                                </a>
                                <button type="submit" class="btn btn-primary">
                                    <i class="bi bi-save me-2"></i> {{ isset($question) ? 'Update' : 'Simpan' }}
                                </button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        @endif

        <div
            class="{{ isset($question) || request()->has('create') || request()->has('edit') ? 'col-md-7' : 'col-md-12' }}">
            <div class="card dashboard-card">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <div>
                        <h5 class="mb-0">Daftar Pertanyaan</h5>
                        <p class="text-muted mb-0">Kuesioner: {{ $questionnaire->name }}</p>
                    </div>
                    <div class="d-flex align-items-center gap-2">
                        @if (!isset($question) && !request()->has('create') && !request()->has('edit'))
                            <a href="?create=true" class="btn btn-success">
                                <i class="bi bi-plus-circle me-2"></i> Tambah Pertanyaan
                            </a>
                        @endif
                        <a href="{{ route('admin.questionnaire.questionnaires', $category->id) }}"
                            class="btn btn-secondary">
                            <i class="bi bi-arrow-left me-2"></i> Kembali ke Kuesioner
                        </a>
                        @if ($questions->isNotEmpty())
                            <button type="button" class="btn btn-danger" data-bs-toggle="modal"
                                data-bs-target="#deleteAllQuestionsModal">
                                <i class="bi bi-trash me-1"></i> Hapus Semua
                            </button>
                        @endif
                        <span class="badge bg-primary ms-2">{{ $questions->count() }} pertanyaan</span>
                    </div>
                </div>
                <div class="card-body">
                    @if ($questions->isEmpty())
                        <div class="text-center py-4">
                            <i class="bi bi-question-circle display-4 text-muted"></i>
                            <p class="mt-2 text-muted">Belum ada pertanyaan</p>
                            <a href="?create=true" class="btn btn-primary mt-2">
                                <i class="bi bi-plus-circle me-2"></i> Buat Pertanyaan Pertama
                            </a>
                        </div>
                    @else
                        <div class="table-responsive">
                            <table class="table table-hover" id="questionTable">
                                <thead>
                                    <tr>
                                        <th width="50">#</th>
                                        <th>Pertanyaan</th>
                                        <th>Tipe</th>
                                        <th>Required</th>
                                        <th>Points</th>
                                        <th>Aksi</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($questions as $q)
                                        <tr data-id="{{ $q->id }}">
                                            <td>{{ $q->order }}</td>
                                            <td>
                                                <div class="fw-bold">{{ Str::limit($q->question_text, 80) }}</div>
                                                @if ($q->description)
                                                    <small
                                                        class="text-muted">{{ Str::limit($q->description, 50) }}</small>
                                                @endif
                                            </td>
                                            <td>
                                                <span class="badge bg-info">{{ $q->question_type }}</span>
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
                                                    <a href="?edit={{ $q->id }}" class="btn btn-action btn-edit"
                                                        title="Edit">
                                                        <i class="bi bi-pencil"></i>
                                                    </a>
                                                    <button type="button" class="btn btn-action btn-delete"
                                                        data-question-id="{{ $q->id }}"
                                                        data-question-text="{{ Str::limit($q->question_text, 50) }}"
                                                        title="Hapus">
                                                        <i class="bi bi-trash"></i>
                                                    </button>
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
                    <h5 class="modal-title" id="deleteModalLabel">Konfirmasi Hapus</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <p>Apakah Anda yakin ingin menghapus pertanyaan ini?</p>
                    <p><strong id="questionTextToDelete"></strong></p>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                    <form id="deleteForm" method="POST" style="display: inline;">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="btn btn-danger">Hapus</button>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <!-- Delete All Questions Modal -->
    <div class="modal fade" id="deleteAllQuestionsModal" tabindex="-1" aria-labelledby="deleteAllQuestionsModalLabel"
        aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="deleteAllQuestionsModalLabel">Konfirmasi Hapus Semua</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <p>Apakah Anda yakin ingin menghapus <strong>semua pertanyaan</strong> dalam kuesioner ini?</p>
                    <p class="text-danger"><small><i class="bi bi-exclamation-triangle"></i> Tindakan ini tidak dapat
                            dikembalikan!</small></p>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                    <form
                        action="{{ route('admin.questionnaire.questions.delete-all', [$category->id, $questionnaire->id]) }}"
                        method="POST" id="deleteAllQuestionsForm">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="btn btn-danger">Hapus Semua</button>
                    </form>
                </div>
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

                // Function to show/hide containers based on question type
                function updateFormFields() {
                    if (!questionType) return;

                    const selectedType = questionType.value;

                    // Hide all containers first
                    if (optionsContainer) optionsContainer.style.display = 'none';
                    if (rowItemsContainer) rowItemsContainer.style.display = 'none';
                    if (scaleOptionsContainer) scaleOptionsContainer.style.display = 'none';

                    // Show relevant containers
                    switch (selectedType) {
                        case 'radio':
                        case 'dropdown':
                        case 'checkbox':
                            if (optionsContainer) optionsContainer.style.display = 'block';
                            break;
                        case 'radio_per_row':
                        case 'checkbox_per_row':
                        case 'likert_per_row':
                            if (rowItemsContainer) rowItemsContainer.style.display = 'block';
                            if (scaleOptionsContainer) scaleOptionsContainer.style.display = 'block';
                            break;
                        case 'likert_scale':
                        case 'competency_scale':
                            if (scaleOptionsContainer) scaleOptionsContainer.style.display = 'block';
                            break;
                    }
                }

                // Initial update
                if (questionType) {
                    updateFormFields();
                    questionType.addEventListener('change', updateFormFields);
                }

                // Handle delete confirmation
                document.querySelectorAll('.btn-delete').forEach(button => {
                    button.addEventListener('click', function() {
                        const questionId = this.getAttribute('data-question-id');
                        const questionText = this.getAttribute('data-question-text');

                        document.getElementById('questionTextToDelete').textContent = questionText;
                        document.getElementById('deleteForm').action =
                            "{{ route('admin.questionnaire.questions.destroy', [$category->id, $questionnaire->id, '']) }}/" +
                            questionId;

                        const deleteModal = new bootstrap.Modal(document.getElementById('deleteModal'));
                        deleteModal.show();
                    });
                });

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
