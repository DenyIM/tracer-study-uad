{{-- resources/views/admin/questionnaire/questionnaires.blade.php --}}
@extends('admin.views.layouts.app')

@section('title', 'Manajemen Kuesioner')
@section('page-title', 'Kuesioner - ' . $category->name)

@section('content')
    <div class="row">
        <div class="col-md-4 mb-4">
            <div class="card dashboard-card">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h5 class="mb-0">{{ isset($questionnaire) ? 'Edit' : 'Tambah' }} Kuesioner</h5>
                    <a href="{{ route('admin.questionnaire.categories') }}" class="btn btn-sm btn-secondary">
                        <i class="bi bi-arrow-left"></i> Back
                    </a>
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
                            <input type="text" class="form-control @error('name') is-invalid @enderror" name="name"
                                value="{{ old('name', $questionnaire->name ?? '') }}" required
                                placeholder="Contoh: Bagian 1 - Informasi Karir Awal">
                            @error('name')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="mb-3">
                            <label class="form-label">Slug <span class="text-danger">*</span></label>
                            <input type="text" class="form-control @error('slug') is-invalid @enderror" name="slug"
                                value="{{ old('slug', $questionnaire->slug ?? '') }}" required
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

                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label class="form-label">Urutan</label>
                                <input type="number" class="form-control @error('order') is-invalid @enderror"
                                    name="order"
                                    value="{{ old('order', $questionnaire->order ?? $questionnaires->count() + 1) }}"
                                    min="0">
                                @error('order')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="col-md-6 mb-3">
                                <label class="form-label">Estimasi Waktu (menit)</label>
                                <input type="number" class="form-control @error('time_estimate') is-invalid @enderror"
                                    name="time_estimate"
                                    value="{{ old('time_estimate', $questionnaire->time_estimate ?? 5) }}" min="1">
                                @error('time_estimate')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <div class="form-check form-switch">
                                    <input class="form-check-input" type="checkbox" name="is_required" id="isRequired"
                                        {{ old('is_required', $questionnaire->is_required ?? true) ? 'checked' : '' }}>
                                    <label class="form-check-label" for="isRequired">
                                        Wajib Diisi
                                    </label>
                                </div>
                            </div>

                            <div class="col-md-6 mb-3">
                                <div class="form-check form-switch">
                                    <input class="form-check-input" type="checkbox" name="is_general" id="isGeneral"
                                        {{ old('is_general', $questionnaire->is_general ?? false) ? 'checked' : '' }}>
                                    <label class="form-check-label" for="isGeneral">
                                        Bagian Umum
                                    </label>
                                </div>
                            </div>
                        </div>

                        <div class="d-flex justify-content-between">
                            <a href="{{ route('admin.questionnaire.questionnaires', $category->id) }}"
                                class="btn btn-secondary">
                                <i class="bi bi-x-circle me-2"></i> Batal
                            </a>
                            <button type="submit" class="btn btn-primary">
                                <i class="bi bi-save me-2"></i> {{ isset($questionnaire) ? 'Update' : 'Simpan' }}
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>

        <div class="col-md-8">
            <div class="card dashboard-card">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <div>
                        <h5 class="mb-0">Daftar Kuesioner</h5>
                        <p class="text-muted mb-0">Kategori: {{ $category->name }}</p>
                    </div>
                    <div class="d-flex gap-2">
                        @if ($questionnaires->isNotEmpty())
                            <button type="button" class="btn btn-sm btn-danger" data-bs-toggle="modal"
                                data-bs-target="#deleteAllQuestionnairesModal">
                                <i class="bi bi-trash me-1"></i> Hapus Semua
                            </button>
                        @endif
                        <a href="{{ route('admin.questionnaire.questions', [$category->id, 'create']) }}"
                            class="btn btn-success">
                            <i class="bi bi-plus-circle me-2"></i> Tambah Pertanyaan
                        </a>
                    </div>
                </div>
                <div class="card-body">
                    @if ($questionnaires->isEmpty())
                        <div class="text-center py-4">
                            <i class="bi bi-clipboard display-4 text-muted"></i>
                            <p class="mt-2 text-muted">Belum ada kuesioner</p>
                            <a href="?create=true" class="btn btn-primary mt-2">
                                <i class="bi bi-plus-circle me-2"></i> Buat Kuesioner Pertama
                            </a>
                        </div>
                    @else
                        <div class="table-responsive">
                            <table class="table table-hover" id="questionnaireTable">
                                <thead>
                                    <tr>
                                        <th width="50">#</th>
                                        <th>Nama Bagian</th>
                                        <th>Slug</th>
                                        <th>Pertanyaan</th>
                                        <th>Tipe</th>
                                        <th>Aksi</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($questionnaires as $q)
                                        <tr data-id="{{ $q->id }}">
                                            <td>{{ $q->order }}</td>
                                            <td>
                                                <div class="fw-bold">{{ $q->name }}</div>
                                                @if ($q->description)
                                                    <small
                                                        class="text-muted">{{ Str::limit($q->description, 60) }}</small>
                                                @endif
                                                @if ($q->is_general)
                                                    <span class="badge bg-info mt-1">Umum</span>
                                                @endif
                                            </td>
                                            <td><code>{{ $q->slug }}</code></td>
                                            <td>
                                                <span class="badge bg-primary">{{ $q->questions_count ?? 0 }}</span>
                                            </td>
                                            <td>
                                                @if ($q->is_general)
                                                    <span class="badge bg-info">Umum</span>
                                                @else
                                                    <span class="badge bg-success">Spesifik</span>
                                                @endif
                                            </td>
                                            <td>
                                                <div class="d-flex flex-wrap gap-1">
                                                    <a href="{{ route('admin.questionnaire.questions', [$category->id, $q->id]) }}"
                                                        class="btn btn-action btn-view" title="Lihat Pertanyaan">
                                                        <i class="bi bi-eye"></i>
                                                    </a>
                                                    <a href="{{ route('admin.questionnaire.questionnaires', [$category->id]) }}?edit={{ $q->id }}"
                                                        class="btn btn-action btn-edit" title="Edit">
                                                        <i class="bi bi-pencil"></i>
                                                    </a>
                                                    <button type="button" class="btn btn-action btn-delete"
                                                        data-questionnaire-id="{{ $q->id }}"
                                                        data-questionnaire-name="{{ $q->name }}" title="Hapus">
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
                    <p>Apakah Anda yakin ingin menghapus kuesioner <strong id="questionnaireNameToDelete"></strong>?</p>
                    <p class="text-danger"><small><i class="bi bi-exclamation-triangle"></i> Tindakan ini akan menghapus
                            semua pertanyaan di dalamnya!</small></p>
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

    <!-- Delete All Questionnaires Modal -->
    <div class="modal fade" id="deleteAllQuestionnairesModal" tabindex="-1"
        aria-labelledby="deleteAllQuestionnairesModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="deleteAllQuestionnairesModalLabel">Konfirmasi Hapus Semua</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <p>Apakah Anda yakin ingin menghapus <strong>semua kuesioner</strong> dalam kategori ini?</p>
                    <p class="text-danger"><small><i class="bi bi-exclamation-triangle"></i> Tindakan ini akan menghapus
                            semua pertanyaan dan tidak dapat dikembalikan!</small></p>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                    <form action="{{ route('admin.questionnaire.questionnaires.delete-all', $category->id) }}"
                        method="POST" id="deleteAllQuestionnairesForm">
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
                // Handle delete confirmation for questionnaires
                document.querySelectorAll('.btn-delete').forEach(button => {
                    button.addEventListener('click', function() {
                        const questionnaireId = this.getAttribute('data-questionnaire-id');
                        const questionnaireName = this.getAttribute('data-questionnaire-name');

                        document.getElementById('questionnaireNameToDelete').textContent =
                            questionnaireName;
                        document.getElementById('deleteForm').action =
                            "{{ route('admin.questionnaire.questionnaires.destroy', [$category->id, '']) }}/" +
                            questionnaireId;

                        const deleteModal = new bootstrap.Modal(document.getElementById('deleteModal'));
                        deleteModal.show();
                    });
                });

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
