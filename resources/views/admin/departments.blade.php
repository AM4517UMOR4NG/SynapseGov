@extends('layouts.dashboard')

@section('title', 'Manajemen Departemen')

@section('content')
<!-- Header -->
<div class="departments-header">
    <div class="d-flex justify-content-between align-items-center flex-wrap gap-3">
        <div>
            <h1>
                <i class="fas fa-sitemap me-2"></i>Manajemen Departemen
            </h1>
            <p class="mb-0 opacity-90">Kelola master data dinas, kementerian, dan instansi dinas pelayanan publik</p>
        </div>
        <button type="button" class="btn btn-light fw-bold text-primary shadow-sm" data-bs-toggle="modal" data-bs-target="#createDepartmentModal">
            <i class="fas fa-plus me-1"></i> Tambah Departemen
        </button>
    </div>
</div>

@if(session('success'))
<div class="alert alert-success alert-dismissible fade show" role="alert">
    <i class="fas fa-check-circle me-2"></i>{{ session('success') }}
    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
</div>
@endif

@if(session('error'))
<div class="alert alert-danger alert-dismissible fade show" role="alert">
    <i class="fas fa-exclamation-triangle me-2"></i>{{ session('error') }}
    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
</div>
@endif

@if($errors->any())
<div class="alert alert-danger alert-dismissible fade show" role="alert">
    <i class="fas fa-exclamation-circle me-2"></i><strong>Terjadi kesalahan input:</strong>
    <ul class="mb-0 mt-1">
        @foreach($errors->all() as $error)
            <li>{{ $error }}</li>
        @endforeach
    </ul>
    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
</div>
@endif

<!-- Departments Table -->
<div class="departments-card">
    <div class="departments-card-header">
        <div class="d-flex align-items-center gap-2">
            <i class="fas fa-list"></i>
            <h3>Daftar Departemen ({{ $departments->count() }} Total)</h3>
        </div>
        <span class="badge bg-white text-primary fw-bold">
            {{ $departments->where('is_active', true)->count() }} Aktif
        </span>
    </div>
    <div class="card-body p-0">
        <div class="table-responsive">
            <table class="table mb-0">
                <thead>
                    <tr>
                        <th style="width: 60px;">ID</th>
                        <th>Departemen & Kode</th>
                        <th>Kepala Departemen</th>
                        <th>Kontak & Alamat</th>
                        <th>Status</th>
                        <th class="text-center">Staf</th>
                        <th class="text-center">Laporan</th>
                        <th class="text-center">Keluhan</th>
                        <th class="text-end" style="min-width: 140px;">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($departments as $department)
                    <tr>
                        <td class="text-muted fw-bold">#{{ $department->id }}</td>
                        <td>
                            <div class="d-flex align-items-center gap-2">
                                <div>
                                    <strong class="text-dark">{{ $department->name }}</strong>
                                    <span class="badge bg-secondary ms-1">{{ $department->code }}</span>
                                    @if($department->description)
                                    <div class="text-muted small text-truncate" style="max-width: 250px;">
                                        {{ $department->description }}
                                    </div>
                                    @endif
                                </div>
                            </div>
                        </td>
                        <td>
                            @if($department->head)
                                <span class="fw-semibold text-primary"><i class="fas fa-user-tie me-1"></i>{{ $department->head->name }}</span>
                                <div class="text-muted small">{{ $department->head->email }}</div>
                            @else
                                <span class="text-muted fst-italic"><i class="fas fa-user-slash me-1"></i>Belum ditunjuk</span>
                            @endif
                        </td>
                        <td>
                            @if($department->email)
                                <div class="small"><i class="fas fa-envelope text-muted me-1"></i>{{ $department->email }}</div>
                            @endif
                            @if($department->phone)
                                <div class="small"><i class="fas fa-phone text-muted me-1"></i>{{ $department->phone }}</div>
                            @endif
                            @if(!$department->email && !$department->phone)
                                <span class="text-muted small">-</span>
                            @endif
                        </td>
                        <td>
                            <form action="{{ route('admin.departments.toggle_status', $department->id) }}" method="POST" class="d-inline">
                                @csrf
                                <button type="submit" class="btn btn-sm p-0 border-0 bg-transparent" title="Klik untuk mengubah status">
                                    <span class="badge bg-{{ $department->is_active ? 'success' : 'danger' }} cursor-pointer">
                                        {{ $department->is_active ? 'Aktif' : 'Nonaktif' }}
                                    </span>
                                </button>
                            </form>
                        </td>
                        <td class="text-center">
                            <span class="badge bg-info text-dark">{{ $department->users_count }}</span>
                        </td>
                        <td class="text-center">
                            <span class="badge bg-primary">{{ $department->reports_count }}</span>
                        </td>
                        <td class="text-center">
                            <span class="badge bg-warning text-dark">{{ $department->complaints_count }}</span>
                        </td>
                        <td class="text-end">
                            <div class="btn-group" role="group">
                                <button type="button" class="btn btn-sm btn-outline-primary" data-bs-toggle="modal" data-bs-target="#viewDepartmentModal{{ $department->id }}" title="Lihat Detail">
                                    <i class="fas fa-eye"></i>
                                </button>
                                <button type="button" class="btn btn-sm btn-outline-warning" data-bs-toggle="modal" data-bs-target="#editDepartmentModal{{ $department->id }}" title="Edit Departemen">
                                    <i class="fas fa-edit"></i>
                                </button>
                                <form action="{{ route('admin.departments.destroy', $department->id) }}" method="POST" class="d-inline" onsubmit="return confirm('Apakah Anda yakin ingin menghapus departemen {{ $department->name }}?');">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn btn-sm btn-outline-danger" title="Hapus Departemen">
                                        <i class="fas fa-trash-alt"></i>
                                    </button>
                                </form>
                            </div>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="9" class="text-center py-4 text-muted">
                            <i class="fas fa-folder-open fa-2x mb-2 d-block"></i>
                            Belum ada departemen yang terdaftar.
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>

<!-- Modal: Tambah Departemen -->
<div class="modal fade" id="createDepartmentModal" tabindex="-1" aria-labelledby="createDepartmentModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <form action="{{ route('admin.departments.store') }}" method="POST">
            @csrf
            <div class="modal-content">
                <div class="modal-header bg-primary text-white">
                    <h5 class="modal-title" id="createDepartmentModalLabel"><i class="fas fa-plus-circle me-2"></i>Tambah Departemen Baru</h5>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <div class="row g-3">
                        <div class="col-md-8">
                            <label for="create_name" class="form-label fw-semibold">Nama Departemen <span class="text-danger">*</span></label>
                            <input type="text" class="form-control" id="create_name" name="name" required placeholder="Contoh: Dinas Kesehatan">
                        </div>
                        <div class="col-md-4">
                            <label for="create_code" class="form-label fw-semibold">Kode <span class="text-danger">*</span></label>
                            <input type="text" class="form-control text-uppercase" id="create_code" name="code" required maxlength="20" placeholder="Contoh: DINKES">
                        </div>
                        <div class="col-md-6">
                            <label for="create_email" class="form-label fw-semibold">Email Departemen</label>
                            <input type="email" class="form-control" id="create_email" name="email" placeholder="dinkes@pemerintah.go.id">
                        </div>
                        <div class="col-md-6">
                            <label for="create_phone" class="form-label fw-semibold">Nomor Telepon</label>
                            <input type="text" class="form-control" id="create_phone" name="phone" placeholder="021-12345678">
                        </div>
                        <div class="col-md-12">
                            <label for="create_head_id" class="form-label fw-semibold">Kepala Departemen</label>
                            <select class="form-select" id="create_head_id" name="head_id">
                                <option value="">-- Belum Ditentukan --</option>
                                @if(isset($potentialHeads))
                                    @foreach($potentialHeads as $potentialHead)
                                        <option value="{{ $potentialHead->id }}">{{ $potentialHead->name }} ({{ ucfirst($potentialHead->role) }})</option>
                                    @endforeach
                                @endif
                            </select>
                        </div>
                        <div class="col-md-12">
                            <label for="create_address" class="form-label fw-semibold">Alamat Kantor</label>
                            <textarea class="form-control" id="create_address" name="address" rows="2" placeholder="Jl. Merdeka No. 1..."></textarea>
                        </div>
                        <div class="col-md-12">
                            <label for="create_description" class="form-label fw-semibold">Deskripsi / Tugas Fungsi</label>
                            <textarea class="form-control" id="create_description" name="description" rows="3" placeholder="Uraian tanggung jawab departemen..."></textarea>
                        </div>
                        <div class="col-md-12">
                            <div class="form-check form-switch mt-2">
                                <input class="form-check-input" type="checkbox" id="create_is_active" name="is_active" value="1" checked>
                                <label class="form-check-label fw-semibold" for="create_is_active">Status Aktif (Departemen siap menerima laporan & keluhan)</label>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                    <button type="submit" class="btn btn-primary"><i class="fas fa-save me-1"></i> Simpan Departemen</button>
                </div>
            </div>
        </form>
    </div>
</div>

<!-- Modals: Detail & Edit Departemen -->
@foreach($departments as $department)
<!-- Detail Modal -->
<div class="modal fade" id="viewDepartmentModal{{ $department->id }}" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header bg-primary text-white">
                <h5 class="modal-title"><i class="fas fa-info-circle me-2"></i>Detail Departemen: {{ $department->name }}</h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <div class="row g-3">
                    <div class="col-md-6">
                        <div class="p-3 bg-light rounded">
                            <div class="text-muted small">Nama Departemen</div>
                            <div class="fw-bold fs-5 text-dark">{{ $department->name }}</div>
                            <div class="badge bg-secondary mt-1">Kode: {{ $department->code }}</div>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="p-3 bg-light rounded">
                            <div class="text-muted small">Status Operasional</div>
                            <div class="mt-1">
                                <span class="badge bg-{{ $department->is_active ? 'success' : 'danger' }} fs-6">
                                    {{ $department->is_active ? 'Aktif' : 'Tidak Aktif' }}
                                </span>
                            </div>
                            <div class="text-muted small mt-2">Kepala Departemen: <strong>{{ $department->head ? $department->head->name : 'Belum Ada' }}</strong></div>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <p class="mb-1"><strong><i class="fas fa-envelope text-primary me-2"></i>Email:</strong> {{ $department->email ?: '-' }}</p>
                        <p class="mb-1"><strong><i class="fas fa-phone text-primary me-2"></i>Telepon:</strong> {{ $department->phone ?: '-' }}</p>
                    </div>
                    <div class="col-md-6">
                        <p class="mb-1"><strong><i class="fas fa-map-marker-alt text-primary me-2"></i>Alamat:</strong> {{ $department->address ?: '-' }}</p>
                    </div>
                    <div class="col-12">
                        <div class="border-top pt-2 mt-2">
                            <strong>Deskripsi:</strong>
                            <p class="text-muted mb-0">{{ $department->description ?: 'Tidak ada deskripsi.' }}</p>
                        </div>
                    </div>
                    <div class="col-12">
                        <div class="row text-center mt-2 g-2">
                            <div class="col-4">
                                <div class="p-2 border rounded bg-white">
                                    <div class="fs-4 fw-bold text-info">{{ $department->users_count }}</div>
                                    <div class="small text-muted">Total Staf</div>
                                </div>
                            </div>
                            <div class="col-4">
                                <div class="p-2 border rounded bg-white">
                                    <div class="fs-4 fw-bold text-primary">{{ $department->reports_count }}</div>
                                    <div class="small text-muted">Laporan Masuk</div>
                                </div>
                            </div>
                            <div class="col-4">
                                <div class="p-2 border rounded bg-white">
                                    <div class="fs-4 fw-bold text-warning">{{ $department->complaints_count }}</div>
                                    <div class="small text-muted">Keluhan Masuk</div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Tutup</button>
            </div>
        </div>
    </div>
</div>

<!-- Edit Modal -->
<div class="modal fade" id="editDepartmentModal{{ $department->id }}" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <form action="{{ route('admin.departments.update', $department->id) }}" method="POST">
            @csrf
            @method('PUT')
            <div class="modal-content">
                <div class="modal-header bg-warning text-dark">
                    <h5 class="modal-title"><i class="fas fa-edit me-2"></i>Edit Departemen: {{ $department->name }}</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <div class="row g-3">
                        <div class="col-md-8">
                            <label class="form-label fw-semibold">Nama Departemen <span class="text-danger">*</span></label>
                            <input type="text" class="form-control" name="name" value="{{ old('name', $department->name) }}" required>
                        </div>
                        <div class="col-md-4">
                            <label class="form-label fw-semibold">Kode <span class="text-danger">*</span></label>
                            <input type="text" class="form-control text-uppercase" name="code" value="{{ old('code', $department->code) }}" required maxlength="20">
                        </div>
                        <div class="col-md-6">
                            <label class="form-label fw-semibold">Email Departemen</label>
                            <input type="email" class="form-control" name="email" value="{{ old('email', $department->email) }}">
                        </div>
                        <div class="col-md-6">
                            <label class="form-label fw-semibold">Nomor Telepon</label>
                            <input type="text" class="form-control" name="phone" value="{{ old('phone', $department->phone) }}">
                        </div>
                        <div class="col-md-12">
                            <label class="form-label fw-semibold">Kepala Departemen</label>
                            <select class="form-select" name="head_id">
                                <option value="">-- Belum Ditentukan --</option>
                                @if(isset($potentialHeads))
                                    @foreach($potentialHeads as $potentialHead)
                                        <option value="{{ $potentialHead->id }}" {{ $department->head_id == $potentialHead->id ? 'selected' : '' }}>
                                            {{ $potentialHead->name }} ({{ ucfirst($potentialHead->role) }})
                                        </option>
                                    @endforeach
                                @endif
                            </select>
                        </div>
                        <div class="col-md-12">
                            <label class="form-label fw-semibold">Alamat Kantor</label>
                            <textarea class="form-control" name="address" rows="2">{{ old('address', $department->address) }}</textarea>
                        </div>
                        <div class="col-md-12">
                            <label class="form-label fw-semibold">Deskripsi</label>
                            <textarea class="form-control" name="description" rows="3">{{ old('description', $department->description) }}</textarea>
                        </div>
                        <div class="col-md-12">
                            <div class="form-check form-switch mt-2">
                                <input class="form-check-input" type="checkbox" name="is_active" id="edit_is_active_{{ $department->id }}" value="1" {{ $department->is_active ? 'checked' : '' }}>
                                <label class="form-check-label fw-semibold" for="edit_is_active_{{ $department->id }}">Status Aktif</label>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                    <button type="submit" class="btn btn-warning"><i class="fas fa-save me-1"></i> Simpan Perubahan</button>
                </div>
            </div>
        </form>
    </div>
</div>
@endforeach
@endsection
