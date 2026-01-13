{{-- resources/views/admin/questionnaire/categories.blade.php --}}
@extends('admin.views.layouts.app')

@section('title', 'Manajemen Kategori Kuesioner')
@section('page-title', 'Kategori Kuesioner')

@section('content')
    <div class="row">
        <div class="col-md-4 mb-4">
            <div class="card dashboard-card">
                <div class="card-header">
                    <h5 class="mb-0">{{ isset($category) ? 'Edit' : 'Tambah' }} Kategori</h5>
                </div>
                <div class="card-body">
                    <form
                        action="{{ isset($category) ? route('admin.questionnaire.categories.update', $category->id) : route('admin.questionnaire.categories.store') }}"
                        method="POST" id="categoryForm">
                        @csrf
                        @if (isset($category))
                            @method('PUT')
                        @endif

                        <div class="mb-3">
                            <label class="form-label">Nama Kategori <span class="text-danger">*</span></label>
                            <input type="text" class="form-control @error('name') is-invalid @enderror" name="name"
                                value="{{ old('name', $category->name ?? '') }}" required>
                            @error('name')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="mb-3">
                            <label class="form-label">Slug <span class="text-danger">*</span></label>
                            <input type="text" class="form-control @error('slug') is-invalid @enderror" name="slug"
                                value="{{ old('slug', $category->slug ?? '') }}" required>
                            <small class="text-muted">URL-friendly version (gunakan tanda hubung, contoh:
                                bekerja-di-perusahaan)</small>
                            @error('slug')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="mb-3">
                            <label class="form-label">Deskripsi</label>
                            <textarea class="form-control @error('description') is-invalid @enderror" name="description" rows="3">{{ old('description', $category->description ?? '') }}</textarea>
                            @error('description')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="mb-3">
                            <label class="form-label">Icon (Bootstrap Icons)</label>
                            <div class="input-group">
                                <span class="input-group-text"><i id="iconPreview"
                                        class="{{ $category->icon ?? 'bi-folder' }}"></i></span>
                                <input type="text" class="form-control @error('icon') is-invalid @enderror"
                                    name="icon" value="{{ old('icon', $category->icon ?? '') }}" placeholder="bi-folder"
                                    id="iconInput">
                            </div>
                            <small class="text-muted">Contoh: bi-folder, bi-graduation-cap, bi-briefcase. Lihat <a
                                    href="https://icons.getbootstrap.com/" target="_blank">Bootstrap Icons</a></small>
                            @error('icon')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label class="form-label">Urutan</label>
                                <input type="number" class="form-control @error('order') is-invalid @enderror"
                                    name="order" value="{{ old('order', $category->order ?? 0) }}" min="0">
                                @error('order')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="col-md-6 mb-3">
                                <label class="form-label">Status</label>
                                <div class="form-check form-switch mt-2">
                                    <input class="form-check-input" type="checkbox" name="is_active" id="isActive"
                                        {{ old('is_active', $category->is_active ?? true) ? 'checked' : '' }}>
                                    <label class="form-check-label" for="isActive">
                                        Aktif
                                    </label>
                                </div>
                            </div>
                        </div>

                        <div class="d-flex justify-content-end gap-2">
                            @if (isset($category))
                                <a href="{{ route('admin.questionnaire.categories') }}" class="btn btn-secondary">
                                    <i class="bi bi-x-circle me-2"></i> Batal
                                </a>
                            @endif
                            <button type="submit" class="btn btn-primary">
                                <i class="bi bi-save me-2"></i> {{ isset($category) ? 'Update' : 'Simpan' }}
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>

        <div class="col-md-8">
            <div class="card dashboard-card">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h5 class="mb-0">Daftar Kategori</h5>
                    <div>
                        <span class="badge bg-primary">{{ $categories->count() }} kategori</span>
                        @if ($categories->isNotEmpty())
                            <button type="button" class="btn btn-sm btn-danger ms-2" data-bs-toggle="modal"
                                data-bs-target="#deleteAllModal">
                                <i class="bi bi-trash me-1"></i> Hapus Semua
                            </button>
                        @endif
                    </div>
                </div>
                <div class="card-body">
                    @if ($categories->isEmpty())
                        <div class="text-center py-4">
                            <i class="bi bi-folder display-4 text-muted"></i>
                            <p class="mt-2 text-muted">Belum ada kategori</p>
                            <a href="?create=true" class="btn btn-primary mt-2">
                                <i class="bi bi-plus-circle me-2"></i> Buat Kategori Pertama
                            </a>
                        </div>
                    @else
                        <div class="table-responsive">
                            <table class="table table-hover">
                                <thead>
                                    <tr>
                                        <th width="50">#</th>
                                        <th>Nama Kategori</th>
                                        <th>Slug</th>
                                        <th>Kuesioner</th>
                                        <th>Status</th>
                                        <th>Aksi</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($categories as $cat)
                                        <tr data-category-id="{{ $cat->id }}">
                                            <td>{{ $loop->iteration }}</td>
                                            <td>
                                                <div class="d-flex align-items-center">
                                                    @if ($cat->icon)
                                                        <i class="bi {{ $cat->icon }} me-2"></i>
                                                    @endif
                                                    <div>
                                                        <div class="fw-bold">{{ $cat->name }}</div>
                                                        <small
                                                            class="text-muted">{{ Str::limit($cat->description, 50) }}</small>
                                                    </div>
                                                </div>
                                            </td>
                                            <td><code>{{ $cat->slug }}</code></td>
                                            <td>
                                                <span class="badge bg-info">{{ $cat->questionnaires_count ?? 0 }}</span>
                                            </td>
                                            <td>
                                                @if ($cat->is_active)
                                                    <span class="badge bg-success">Aktif</span>
                                                @else
                                                    <span class="badge bg-secondary">Nonaktif</span>
                                                @endif
                                            </td>
                                            <td>
                                                <div class="d-flex flex-wrap gap-1">
                                                    <a href="{{ route('admin.questionnaire.questionnaires', $cat->id) }}"
                                                        class="btn btn-action btn-view" title="Lihat Kuesioner">
                                                        <i class="bi bi-eye"></i>
                                                    </a>
                                                    <a href="{{ route('admin.questionnaire.categories') }}?edit={{ $cat->id }}"
                                                        class="btn btn-action btn-edit" title="Edit">
                                                        <i class="bi bi-pencil"></i>
                                                    </a>
                                                    <button type="button" class="btn btn-action btn-delete"
                                                        data-category-id="{{ $cat->id }}"
                                                        data-category-name="{{ $cat->name }}" title="Hapus">
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
                    <p>Apakah Anda yakin ingin menghapus kategori <strong id="categoryNameToDelete"></strong>?</p>
                    <p class="text-danger"><small><i class="bi bi-exclamation-triangle"></i> Tindakan ini akan menghapus
                            semua kuesioner dan pertanyaan di dalamnya!</small></p>
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

    <!-- Delete All Modal -->
    <div class="modal fade" id="deleteAllModal" tabindex="-1" aria-labelledby="deleteAllModalLabel"
        aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="deleteAllModalLabel">Konfirmasi Hapus Semua</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <p>Apakah Anda yakin ingin menghapus <strong>semua kategori</strong>?</p>
                    <p class="text-danger"><small><i class="bi bi-exclamation-triangle"></i> Tindakan ini akan menghapus
                            semua data kuesioner dan tidak dapat dikembalikan!</small></p>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                    <form action="{{ route('admin.questionnaire.categories.delete-all') }}" method="POST"
                        id="deleteAllForm">
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
                // Icon preview
                const iconInput = document.getElementById('iconInput');
                const iconPreview = document.getElementById('iconPreview');

                if (iconInput && iconPreview) {
                    iconInput.addEventListener('input', function() {
                        const iconClass = this.value.trim();
                        if (iconClass) {
                            iconPreview.className = 'bi ' + iconClass;
                        }
                    });
                }

                // Handle delete confirmation
                document.querySelectorAll('.btn-delete').forEach(button => {
                    button.addEventListener('click', function() {
                        const categoryId = this.getAttribute('data-category-id');
                        const categoryName = this.getAttribute('data-category-name');

                        document.getElementById('categoryNameToDelete').textContent = categoryName;
                        document.getElementById('deleteForm').action =
                            "{{ route('admin.questionnaire.categories.destroy', '') }}/" + categoryId;

                        const deleteModal = new bootstrap.Modal(document.getElementById('deleteModal'));
                        deleteModal.show();
                    });
                });

                // Scroll to form if editing
                const urlParams = new URLSearchParams(window.location.search);
                if (urlParams.has('edit') || urlParams.has('create')) {
                    const formSection = document.querySelector('.col-md-4');
                    if (formSection) {
                        formSection.scrollIntoView({
                            behavior: 'smooth'
                        });
                    }
                }

                // Auto generate slug from name
                const nameInput = document.querySelector('input[name="name"]');
                const slugInput = document.querySelector('input[name="slug"]');

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
            });
        </script>
    @endpush
@endsection
