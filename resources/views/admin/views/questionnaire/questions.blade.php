{{-- resources/views/admin/views/questionnaire/questions.blade.php --}}
@extends('admin.views.layouts.app')

@section('title', 'Manajemen Pertanyaan')
@section('page-title', 'Pertanyaan - ' . $questionnaire->name)

@section('content')
    <style>
        /* CSS khusus untuk halaman questions */
        .question-list-container {
            width: 50%;
        }

        .question-form-wrapper {
            width: 50%;
        }

        @media (max-width: 992px) {

            .question-list-container,
            .question-form-wrapper {
                width: 100%;
            }
        }

        .dashboard-card {
            border-radius: 8px;
            box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
            border: 1px solid #e9ecef;
        }

        .dashboard-card .card-header {
            border-bottom: 1px solid #e9ecef;
            background-color: #f8f9fa;
        }

        .table th {
            background-color: #f8f9fa;
            font-weight: 600;
            color: #495057;
        }

        .badge {
            font-size: 12px;
            padding: 4px 8px;
        }
    </style>

    <div class="row">
        @if (isset($question) || request()->has('create') || request()->has('edit'))
            <div class="question-form-wrapper">
                @include('admin.views.questionnaire.question_form', [
                    'question' => $question ?? null,
                    'questions' => $questions,
                    'formType' => 'specific',
                    'category' => $category,
                    'questionnaire' => $questionnaire,
                    'routeStore' => route('admin.questionnaire.questions.store', [
                        $category->id,
                        $questionnaire->id,
                    ]),
                    'routeUpdate' => isset($question)
                        ? route('admin.questionnaire.questions.update', [
                            $category->id,
                            $questionnaire->id,
                            $question->id,
                        ])
                        : null,
                    'routeBack' => route('admin.questionnaire.questions', [$category->id, $questionnaire->id]),
                ])
            </div>
        @endif

        <div
            class="{{ isset($question) || request()->has('create') || request()->has('edit') ? 'question-list-container' : 'col-12' }}">
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
                            <a href="{{ route('admin.questionnaire.questions.create', [$category->id, $questionnaire->id]) }}"
                                class="btn btn-success">
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
                                                    <small class="text-muted">{{ Str::limit($q->description, 50) }}</small>
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
                                                    <a href="{{ route('admin.questionnaire.questions.edit', [$category->id, $questionnaire->id, $q->id]) }}"
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
                    const formSection = document.querySelector('.question-form-wrapper');
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
