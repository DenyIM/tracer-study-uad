{{-- resources/views/admin/questionnaire/general_questionnaires.blade.php --}}
@extends('admin.views.layouts.app')

@section('title', 'Manajemen Kuesioner Umum')
@section('page-title', 'Kuesioner Umum')

@section('content')
    <style>
        /* CSS khusus untuk halaman general questionnaires */
        .general-question-form-full {
            width: 100%;
            max-width: 1200px;
            margin: 0 auto;
        }

        .general-question-form-full .card {
            border-radius: 8px;
            box-shadow: 0 2px 8px rgba(0, 0, 0, 0.1);
        }

        .general-question-form-full .col-md-6 {
            padding: 0 15px;
        }

        .general-question-form-full textarea.form-control {
            min-height: 100px;
        }

        .general-question-form-full .card-body {
            padding: 25px;
        }

        .general-question-form-full .form-control,
        .general-question-form-full .form-select {
            border-radius: 6px;
            border: 1px solid #ced4da;
            padding: 10px 12px;
            font-size: 14px;
        }

        .general-question-form-full .form-label {
            font-weight: 500;
            margin-bottom: 6px;
            color: #495057;
        }

        .general-question-form-full pre {
            background-color: #f8f9fa;
            border-radius: 4px;
            padding: 10px;
            font-size: 12px;
            margin-top: 8px;
            border: 1px solid #e9ecef;
        }

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
                                            <a href="{{ route('admin.questionnaire.general-questions.create', $generalQuestionnaire->id) }}"
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
                                                                <a href="{{ route('admin.questionnaire.general-questions.edit', ['questionnaireId' => $generalQuestionnaire->id, 'id' => $q->id]) }}"
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
                    <div class="general-question-form-full mt-4">
                        @include('admin.views.questionnaire.question_form', [
                            'question' => request()->has('edit_question')
                                ? $questions->firstWhere('id', request()->get('edit_question'))
                                : null,
                            'questions' => $questions,
                            'formType' => 'general',
                            'generalQuestionnaire' => $generalQuestionnaire,
                            'selectedCategory' => $selectedCategory,
                            'routeStore' => route(
                                'admin.questionnaire.general-questions.store',
                                $generalQuestionnaire->id),
                            'routeUpdate' => request()->has('edit_question')
                                ? route('admin.questionnaire.general-questions.update', [
                                    'questionnaireId' => $generalQuestionnaire->id,
                                    'id' => request()->get('edit_question'),
                                ])
                                : null,
                            'routeBack' => route('admin.questionnaire.general-questionnaires', [
                                'category_id' => $selectedCategory->id,
                            ]),
                        ])
                    </div>
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
