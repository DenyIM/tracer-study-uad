{{-- resources/views/admin/questionnaire/questionnaires.blade.php --}}
@extends('admin.views.layouts.app')

@section('title', 'Manajemen Kuesioner Spesifik')
@section('page-title', 'Kuesioner Spesifik - ' . $category->name)

@section('content')
    <div class="row">
        <!-- Form Kuesioner -->
        @if (isset($questionnaire) || request()->has('create'))
            <div class="col-md-4 mb-4">
                <div class="card dashboard-card">
                    <div class="card-header d-flex justify-content-between align-items-center">
                        <h5 class="mb-0">{{ isset($questionnaire) ? 'Edit' : 'Tambah' }} Kuesioner</h5>
                        <div>
                            <a href="{{ route('admin.questionnaire.categories') }}" class="btn btn-sm btn-secondary me-2">
                                <i class="fas fa-arrow-left"></i> Kategori
                            </a>
                        </div>
                    </div>
                    <div class="card-body">
                        <form
                            action="{{ isset($questionnaire) ? route('admin.questionnaire.questionnaires.update', [$category->id, $questionnaire->id]) : route('admin.questionnaire.questionnaires.store', $category->id) }}"
                            method="POST" id="questionnaireForm">
                            @csrf
                            @if (isset($questionnaire))
                                @method('PUT')
                            @endif

                            <div class="mb-3">
                                <label class="form-label">Nama Bagian <span class="text-danger">*</span></label>
                                <input type="text" class="form-control @error('name') is-invalid @enderror"
                                    name="name" value="{{ old('name', $questionnaire->name ?? '') }}" required
                                    placeholder="Contoh: Bagian 1 - Informasi Karir Awal">
                                @error('name')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="mb-3">
                                <label class="form-label">Slug <span class="text-danger">*</span></label>
                                <input type="text" class="form-control @error('slug') is-invalid @enderror"
                                    name="slug" value="{{ old('slug', $questionnaire->slug ?? '') }}" required
                                    placeholder="Contoh: bagian-1-informasi-karir">
                                <small class="text-muted">URL-friendly version</small>
                                @error('slug')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="mb-3">
                                <label class="form-label">Deskripsi</label>
                                <textarea class="form-control @error('description') is-invalid @enderror" name="description" rows="3"
                                    placeholder="Deskripsi singkat tentang bagian kuesioner ini">{{ old('description', $questionnaire->description ?? '') }}</textarea>
                                @error('description')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="mb-3">
                                <label class="form-label">Urutan</label>
                                <input type="number" class="form-control @error('order') is-invalid @enderror"
                                    name="order"
                                    value="{{ old('order', isset($questionnaire) ? $questionnaire->order : $questionnaires->count() + 1) }}"
                                    min="1" step="1">
                                @error('order')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="mb-3">
                                <div class="form-check form-switch">
                                    <input class="form-check-input" type="checkbox" name="is_required" id="isRequired"
                                        {{ old('is_required', isset($questionnaire) ? $questionnaire->is_required : true) ? 'checked' : '' }}>
                                    <label class="form-check-label" for="isRequired">
                                        Wajib Diisi
                                    </label>
                                </div>
                            </div>

                            <div class="d-flex justify-content-between">
                                <a href="{{ route('admin.questionnaire.questionnaires', $category->id) }}"
                                    class="btn btn-secondary">
                                    <i class="fas fa-times-circle me-2"></i> Batal
                                </a>
                                <button type="submit" class="btn btn-primary">
                                    <i class="fas fa-save me-2"></i> {{ isset($questionnaire) ? 'Update' : 'Simpan' }}
                                </button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        @endif

        <!-- Daftar Kuesioner -->
        <div class="{{ isset($questionnaire) || request()->has('create') ? 'col-md-8' : 'col-md-12' }}">
            <div class="card dashboard-card">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <div>
                        <h5 class="mb-0">Daftar Kuesioner Spesifik</h5>
                        <p class="text-muted mb-0">Kategori: {{ $category->name }}</p>
                        <small class="text-info">
                            <i class="fas fa-info-circle"></i> Kuesioner umum dikelola di halaman khusus
                        </small>
                    </div>
                    <div class="d-flex gap-2">
                        <a href="{{ route('admin.questionnaire.categories') }}" class="btn btn-secondary">
                            <i class="fas fa-arrow-left me-2"></i> Kategori
                        </a>
                        @if (!isset($questionnaire) && !request()->has('create'))
                            <a href="{{ route('admin.questionnaire.general-questionnaires', ['category_id' => $category->id]) }}"
                                class="btn btn-info">
                                <i class="fas fa-cog me-2"></i> Kelola Umum
                            </a>
                            <a href="?create=true" class="btn btn-success">
                                <i class="fas fa-plus-circle me-2"></i> Tambah Kuesioner
                            </a>
                        @endif
                        @if ($questionnaires->isNotEmpty() && !isset($questionnaire) && !request()->has('create'))
                            <button type="button" class="btn btn-danger" id="deleteSelectedQuestionnaires" disabled>
                                <i class="fas fa-trash me-1"></i> Hapus Terpilih
                            </button>
                        @endif
                    </div>
                </div>
                <div class="card-body">
                    @if ($questionnaires->isEmpty())
                        <div class="text-center py-4">
                            <i class="fas fa-clipboard display-4 text-muted"></i>
                            <p class="mt-2 text-muted">Belum ada kuesioner spesifik</p>
                            @if (!isset($questionnaire) && !request()->has('create'))
                                <a href="?create=true" class="btn btn-primary mt-2">
                                    <i class="fas fa-plus-circle me-2"></i> Buat Kuesioner Pertama
                                </a>
                            @endif
                        </div>
                    @else
                        <div class="table-responsive">
                            <table class="table table-hover" id="questionnaireTable">
                                <thead>
                                    <tr>
                                        @if (!isset($questionnaire) && !request()->has('create'))
                                            <th width="30">
                                                <input type="checkbox" id="selectAllQuestionnaires">
                                            </th>
                                        @endif
                                        <th width="50">#</th>
                                        <th>Nama Bagian</th>
                                        <th>Slug</th>
                                        <th>Pertanyaan</th>
                                        <th>Status</th>
                                        <th width="120">Aksi</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($questionnaires as $q)
                                        <tr data-id="{{ $q->id }}">
                                            @if (!isset($questionnaire) && !request()->has('create'))
                                                <td>
                                                    <input type="checkbox" class="questionnaire-checkbox"
                                                        value="{{ $q->id }}">
                                                </td>
                                            @endif
                                            <td>{{ $q->order }}</td>
                                            <td>
                                                <div class="fw-bold">{{ $q->name }}</div>
                                                @if ($q->description)
                                                    <small
                                                        class="text-muted">{{ Str::limit($q->description, 60) }}</small>
                                                @endif
                                            </td>
                                            <td><code>{{ $q->slug }}</code></td>
                                            <td>
                                                <span class="badge bg-primary">{{ $q->questions_count ?? 0 }}</span>
                                            </td>
                                            <td>
                                                @if ($q->is_required)
                                                    <span class="badge bg-danger">Wajib</span>
                                                @else
                                                    <span class="badge bg-secondary">Opsional</span>
                                                @endif
                                            </td>
                                            <td>
                                                <div class="d-flex flex-wrap gap-1">
                                                    <a href="{{ route('admin.questionnaire.questions', [$category->id, $q->id]) }}"
                                                        class="btn btn-sm btn-action btn-primary"
                                                        title="Kelola Pertanyaan">
                                                        <i class="fas fa-list"></i>
                                                    </a>
                                                    <a href="{{ route('admin.questionnaire.questionnaires', [$category->id]) }}?edit={{ $q->id }}"
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
                    <h5 class="modal-title" id="deleteModalLabel">Konfirmasi Hapus Kuesioner</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <p>Apakah Anda yakin ingin menghapus <span id="deleteCount" class="fw-bold"></span> kuesioner yang
                        dipilih?</p>
                    <p class="text-muted"><small>Data yang dihapus tidak dapat dikembalikan.</small></p>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                    <button type="button" class="btn btn-danger" id="confirmDeleteQuestionnaires">Ya, Hapus</button>
                </div>
            </div>
        </div>
    </div>

    @push('scripts')
        <script>
            document.addEventListener('DOMContentLoaded', function() {
                @if (!isset($questionnaire) && !request()->has('create'))
                    // Select all checkbox
                    const selectAll = document.getElementById('selectAllQuestionnaires');
                    const questionnaireCheckboxes = document.querySelectorAll('.questionnaire-checkbox');
                    const deleteSelectedBtn = document.getElementById('deleteSelectedQuestionnaires');

                    function updateDeleteButton() {
                        const checkedCount = document.querySelectorAll('.questionnaire-checkbox:checked').length;
                        if (deleteSelectedBtn) {
                            deleteSelectedBtn.disabled = checkedCount === 0;
                        }
                    }

                    if (selectAll) {
                        selectAll.addEventListener('change', function() {
                            questionnaireCheckboxes.forEach(checkbox => {
                                checkbox.checked = this.checked;
                            });
                            updateDeleteButton();
                        });
                    }

                    questionnaireCheckboxes.forEach(checkbox => {
                        checkbox.addEventListener('change', updateDeleteButton);
                    });

                    // Handle delete selected questionnaires
                    deleteSelectedBtn.addEventListener('click', function() {
                        const selectedIds = [];
                        document.querySelectorAll('.questionnaire-checkbox:checked').forEach(checkbox => {
                            selectedIds.push(checkbox.value);
                        });

                        if (selectedIds.length === 0) {
                            return;
                        }

                        document.getElementById('deleteCount').textContent = selectedIds.length + ' kuesioner';

                        const deleteModal = new bootstrap.Modal(document.getElementById('deleteModal'));
                        deleteModal.show();

                        document.getElementById('confirmDeleteQuestionnaires').onclick = function() {
                            fetch("{{ route('admin.questionnaire.questionnaires.delete-selected', $category->id) }}", {
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
                @endif

                // Auto generate slug from name
                const nameInput = document.querySelector('#questionnaireForm input[name="name"]');
                const slugInput = document.querySelector('#questionnaireForm input[name="slug"]');

                if (nameInput && slugInput && !slugInput.value) {
                    nameInput.addEventListener('blur', function() {
                        if (!slugInput.value) {
                            const slug = this.value
                                .toLowerCase()
                                .replace(/[^\w\s]/gi, '')
                                .replace(/\s+/g, '-');
                            slugInput.value = slug;
                        }
                    });
                }

                // Get URL parameters
                const urlParams = new URLSearchParams(window.location.search);
                if (urlParams.has('edit') || urlParams.has('create')) {
                    const formSection = document.querySelector('.col-md-4');
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
