<header class="dh-heading">
    <div>
        <div class="dh-eyebrow">{{ $eyebrow ?? (auth()->user()->role === 'citizen' ? 'PORTAL WARGA' : (auth()->user()->isDepartmentHead() ? 'RUANG KERJA KEPALA DINAS' : 'RUANG KERJA STAF')) }}</div>
        <h1>{{ $heading }}</h1>
        <p>{{ $description }}</p>
    </div>
    @if(auth()->user()->role !== 'citizen')
    <div class="dh-department">
        <i class="fas fa-building-columns" aria-hidden="true"></i>
        <span>{{ auth()->user()->department?->name ?? 'Departemen belum diatur' }}</span>
    </div>
    @endif
</header>
@if(!auth()->user()->department_id && auth()->user()->role !== 'citizen')
<div class="alert alert-warning" role="alert">Akun Anda belum terhubung ke departemen. Hubungi administrator untuk mengatur departemen Anda.</div>
@endif
