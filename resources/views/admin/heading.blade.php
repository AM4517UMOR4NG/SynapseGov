@php
    $adminSection = request()->route()->getName();
    $adminPages = [
        'admin.dashboard' => ['Ringkasan layanan', 'Pantau aktivitas, antrean laporan, dan kinerja layanan publik.'],
        'admin.reports' => ['Laporan masyarakat', 'Tinjau laporan masuk dan tentukan tindak lanjut yang tepat.'],
        'admin.complaints' => ['Keluhan & aspirasi', 'Kelola masukan warga untuk pelayanan yang lebih baik.'],
        'admin.users' => ['Manajemen pengguna', 'Temukan pengguna dan tinjau peran serta status akunnya.'],
        'admin.departments' => ['Organisasi perangkat daerah', 'Kelola instansi, penanggung jawab, dan informasi layanan.'],
        'admin.monitoring' => ['Monitoring & SLA', 'Tinjau batas waktu penanganan dan perkembangan setiap dinas.'],
    ];
    [$adminTitle, $adminDescription] = $adminPages[$adminSection];
@endphp
<div class="admin-page-heading">
    <div><span class="admin-eyebrow">RUANG KERJA ADMINISTRATOR</span><h1>{{ $adminTitle }}</h1><p>{{ $adminDescription }}</p></div>
    @if($adminSection === 'admin.departments')
        <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#createDepartmentModal"><i class="fas fa-plus me-2" aria-hidden="true"></i>Tambah departemen</button>
    @else
        <span class="admin-date"><i class="far fa-calendar me-2" aria-hidden="true"></i>{{ now()->translatedFormat('d F Y') }}</span>
    @endif
</div>
@if(in_array($adminSection, ['admin.reports', 'admin.complaints', 'admin.users', 'admin.departments']))
<form method="GET" action="{{ route($adminSection) }}" class="admin-search-bar" role="search">
    <div><label for="admin-search">{{ $adminSection === 'admin.users' ? 'Cari nama atau email' : ($adminSection === 'admin.departments' ? 'Cari nama atau kode departemen' : 'Cari judul atau nomor tiket') }}</label><div class="admin-search-input"><i class="fas fa-search" aria-hidden="true"></i><input class="form-control" type="search" id="admin-search" name="q" value="{{ request('q') }}" placeholder="Ketik kata kunci…" maxlength="100"></div></div>
    <button type="submit" class="btn btn-primary">Cari data</button>
    @if(request()->filled('q'))<a class="btn btn-outline-secondary" href="{{ route($adminSection) }}">Reset</a>@endif
</form>
@endif
