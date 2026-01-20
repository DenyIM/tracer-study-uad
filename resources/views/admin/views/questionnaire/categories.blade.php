{{-- resources/views/admin/questionnaire/categories.blade.php --}}
@extends('admin.views.layouts.app')

@section('title', 'Manajemen Kategori Kuesioner')
@section('page-title', 'Kategori Kuesioner')

@section('content')
    <div class="row">
        <!-- Form Kategori -->
        @if (isset($category) || request()->has('create'))
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
                                <input type="text" class="form-control @error('name') is-invalid @enderror"
                                    name="name" value="{{ old('name', $category->name ?? '') }}" required>
                                @error('name')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="mb-3">
                                <label class="form-label">Slug <span class="text-danger">*</span></label>
                                <input type="text" class="form-control @error('slug') is-invalid @enderror"
                                    name="slug" value="{{ old('slug', $category->slug ?? '') }}" required>
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
                                <label class="form-label">Icon (Font Awesome)</label>
                                <div class="input-group">
                                    <span class="input-group-text">
                                        <i id="iconPreview"
                                            class="{{ isset($category) && $category->icon ? $category->icon : 'fas fa-folder' }}"></i>
                                    </span>
                                    <input type="text" class="form-control @error('icon') is-invalid @enderror"
                                        name="icon" value="{{ old('icon', $category->icon ?? '') }}"
                                        placeholder="fas fa-folder" id="iconInput">
                                </div>
                                <small class="text-muted">Contoh: fas fa-folder, fas fa-graduation-cap, fas fa-briefcase,
                                    fas
                                    fa-building. Lihat <a href="https://fontawesome.com/icons" target="_blank">Font Awesome
                                        Icons</a></small>
                                @error('icon')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="row">
                                <div class="col-md-6 mb-3">
                                    <label class="form-label">Urutan</label>
                                    <input type="number" class="form-control @error('order') is-invalid @enderror"
                                        name="order" value="{{ old('order', $category->order ?? 0) }}" min="0"
                                        step="1">
                                    @error('order')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>

                                <div class="col-md-6 mb-3">
                                    <label class="form-label">Status</label>
                                    <div class="form-check form-switch mt-2">
                                        <input class="form-check-input" type="checkbox" name="is_active" id="isActive"
                                            {{ old('is_active', isset($category) ? $category->is_active : true) ? 'checked' : '' }}>
                                        <label class="form-check-label" for="isActive">
                                            Aktif
                                        </label>
                                    </div>
                                </div>
                            </div>

                            <div class="d-flex justify-content-end gap-2">
                                <a href="{{ route('admin.questionnaire.categories') }}" class="btn btn-secondary">
                                    <i class="fas fa-times-circle me-2"></i> Batal
                                </a>
                                <button type="submit" class="btn btn-primary">
                                    <i class="fas fa-save me-2"></i> {{ isset($category) ? 'Update' : 'Simpan' }}
                                </button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        @endif

        <!-- Daftar Kategori -->
        <div class="{{ isset($category) || request()->has('create') ? 'col-md-8' : 'col-md-12' }}">
            <div class="card dashboard-card">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <div>
                        <h5 class="mb-0">Daftar Kategori</h5>
                        <div class="d-flex align-items-center mt-1">
                            <a href="{{ route('admin.questionnaire.statistics') }}" class="btn btn-sm btn-info me-2">
                                <i class="fas fa-chart-bar me-1"></i> Statistik
                            </a>
                        </div>
                    </div>
                    <div>
                        <span class="badge bg-primary">{{ $categories->count() }} kategori</span>
                        @if (!isset($category) && !request()->has('create'))
                            <a href="?create=true" class="btn btn-success ms-2">
                                <i class="fas fa-plus-circle me-2"></i> Tambah Kategori
                            </a>
                            @if ($categories->isNotEmpty())
                                <button type="button" class="btn btn-danger ms-2" id="deleteSelectedCategories" disabled>
                                    <i class="fas fa-trash me-1"></i> Hapus Terpilih
                                </button>
                            @endif
                        @endif
                    </div>
                </div>
                <div class="card-body">
                    @if ($categories->isEmpty())
                        <div class="text-center py-4">
                            <i class="fas fa-folder display-4 text-muted"></i>
                            <p class="mt-2 text-muted">Belum ada kategori</p>
                            @if (!isset($category) && !request()->has('create'))
                                <a href="?create=true" class="btn btn-primary mt-2">
                                    <i class="fas fa-plus-circle me-2"></i> Buat Kategori Pertama
                                </a>
                            @endif
                        </div>
                    @else
                        <div class="table-responsive">
                            <table class="table table-hover">
                                <thead>
                                    <tr>
                                        @if (!isset($category) && !request()->has('create'))
                                            <th width="30">
                                                <input type="checkbox" id="selectAllCategories">
                                            </th>
                                        @endif
                                        <th width="50">#</th>
                                        <th width="80">Logo</th>
                                        <th>Nama Kategori</th>
                                        <th>Slug</th>
                                        <th>Kuesioner</th>
                                        <th>Status</th>
                                        <th width="140">Aksi</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($categories as $cat)
                                        <tr data-category-id="{{ $cat->id }}">
                                            @if (!isset($category) && !request()->has('create'))
                                                <td>
                                                    <input type="checkbox" class="category-checkbox"
                                                        value="{{ $cat->id }}">
                                                </td>
                                            @endif
                                            <td>{{ $cat->order }}</td>
                                            <td class="text-center">
                                                @if ($cat->icon)
                                                    <i class="{{ $cat->icon }} fa-lg text-primary"></i>
                                                @else
                                                    <i class="fas fa-folder fa-lg text-muted"></i>
                                                @endif
                                            </td>
                                            <td>
                                                <div class="fw-bold">{{ $cat->name }}</div>
                                                <small class="text-muted">{{ Str::limit($cat->description, 50) }}</small>
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
                                                    <a href="{{ route('admin.questionnaire.general-questionnaires', ['category_id' => $cat->id]) }}"
                                                        class="btn btn-sm btn-action btn-info"
                                                        title="Kelola Kuesioner Umum">
                                                        <i class="fas fa-eye"></i>
                                                    </a>
                                                    <a href="{{ route('admin.questionnaire.questionnaires', $cat->id) }}"
                                                        class="btn btn-sm btn-action btn-primary"
                                                        title="Kelola Kuesioner Spesifik">
                                                        <i class="fas fa-list"></i>
                                                    </a>
                                                    <a href="{{ route('admin.questionnaire.categories') }}?edit={{ $cat->id }}"
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
                    <h5 class="modal-title" id="deleteModalLabel">Konfirmasi Hapus Kategori</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <p>Apakah Anda yakin ingin menghapus <span id="deleteCount" class="fw-bold"></span> kategori yang
                        dipilih?</p>
                    <p class="text-muted"><small>Data yang dihapus tidak dapat dikembalikan.</small></p>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                    <button type="button" class="btn btn-danger" id="confirmDeleteCategories">Ya, Hapus</button>
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
                            // Pastikan ada prefix 'fas' jika tidak ada
                            if (!iconClass.startsWith('fa-')) {
                                if (iconClass.includes('fa-')) {
                                    // Jika sudah ada 'fa-' tapi tidak ada 'fas/fa-solid'
                                    iconPreview.className = 'fas ' + iconClass;
                                } else {
                                    iconPreview.className = 'fas fa-' + iconClass;
                                }
                            } else {
                                // Jika dimulai dengan 'fa-', tambahkan 'fas'
                                iconPreview.className = 'fas ' + iconClass;
                            }
                        } else {
                            iconPreview.className = 'fas fa-folder';
                        }
                    });
                }

                @if (!isset($category) && !request()->has('create'))
                    // Select all checkbox
                    const selectAll = document.getElementById('selectAllCategories');
                    const categoryCheckboxes = document.querySelectorAll('.category-checkbox');
                    const deleteSelectedBtn = document.getElementById('deleteSelectedCategories');

                    function updateDeleteButton() {
                        const checkedCount = document.querySelectorAll('.category-checkbox:checked').length;
                        if (deleteSelectedBtn) {
                            deleteSelectedBtn.disabled = checkedCount === 0;
                        }
                    }

                    if (selectAll) {
                        selectAll.addEventListener('change', function() {
                            categoryCheckboxes.forEach(checkbox => {
                                checkbox.checked = this.checked;
                            });
                            updateDeleteButton();
                        });
                    }

                    categoryCheckboxes.forEach(checkbox => {
                        checkbox.addEventListener('change', updateDeleteButton);
                    });

                    // Handle delete selected categories
                    deleteSelectedBtn.addEventListener('click', function() {
                        const selectedIds = [];
                        document.querySelectorAll('.category-checkbox:checked').forEach(checkbox => {
                            selectedIds.push(checkbox.value);
                        });

                        if (selectedIds.length === 0) {
                            return;
                        }

                        document.getElementById('deleteCount').textContent = selectedIds.length + ' kategori';

                        const deleteModal = new bootstrap.Modal(document.getElementById('deleteModal'));
                        deleteModal.show();

                        document.getElementById('confirmDeleteCategories').onclick = function() {
                            fetch("{{ route('admin.questionnaire.categories.delete-selected') }}", {
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
            });
        </script>
    @endpush
@endsection
