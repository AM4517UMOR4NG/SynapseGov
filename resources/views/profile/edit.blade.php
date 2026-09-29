@extends('layouts.dashboard')

@section('title', 'Edit Profil')

@section('content')
<style>
    .profile-wrap {
        max-width: 900px;
        margin: 0 auto;
        padding-bottom: 4rem;
        font-family: 'Plus Jakarta Sans', sans-serif;
    }
    
    /* Gradient Header */
    .edit-header-clean {
        display: flex;
        align-items: center;
        justify-content: space-between;
        margin-bottom: 2rem;
        padding: 2.5rem;
        border-radius: 12px;
        background-color: #b71c1c;
        background-image: 
            url("data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' viewBox='0 0 200 100' preserveAspectRatio='none'%3E%3Cpath d='M200,0 L200,100 L30,100 C130,100 80,0 160,0 Z' fill='%23333333'/%3E%3C/svg%3E"),
            linear-gradient(115deg, transparent 35%, rgba(0,0,0,0.1) 36%, rgba(0,0,0,0.1) 55%, transparent 56%),
            linear-gradient(65deg, rgba(255,255,255,0.05) 25%, transparent 26%, transparent 65%, rgba(0,0,0,0.1) 66%),
            linear-gradient(135deg, #b71c1c 0%, #d32f2f 50%, #991b1b 100%);
        background-position: right center, center, center, center;
        background-size: 35% 100%, cover, cover, cover;
        background-repeat: no-repeat;
        border: none;
        border-bottom: 4px solid #f59e0b;
        box-shadow: 0 8px 25px -8px rgba(183, 28, 28, 0.45);
        flex-wrap: wrap;
        gap: 1.5rem;
        position: relative;
        overflow: hidden;
        color: #ffffff;
    }
    
    .edit-header-clean h1 {
        font-size: 2rem;
        font-weight: 800;
        color: #ffffff;
        margin: 0;
        letter-spacing: -0.02em;
    }
    .edit-header-clean h1 i { color: #ffffff; }
    
    .edit-header-clean .btn-outline-clean {
        background: #ffffff;
        color: #000000;
        border: none;
        box-shadow: 0 4px 12px rgba(0,0,0,0.15);
    }
    .edit-header-clean .btn-outline-clean:hover {
        background: #f8fafc;
        color: #000000;
        transform: translateY(-2px);
    }
    
    .btn-clean {
        display: inline-flex;
        align-items: center;
        gap: 0.5rem;
        padding: 0.8rem 1.5rem;
        border-radius: 100px;
        font-size: 0.95rem;
        font-weight: 600;
        text-decoration: none;
        transition: all 0.2s ease;
        border: none;
        cursor: pointer;
    }
    .btn-outline-clean {
        background: transparent;
        color: #000000;
        border: 2px solid #000000;
    }
    .btn-outline-clean:hover { background: transparent; color: #000000; }
    
    .btn-primary-clean {
        background: #000000;
        color: #ffffff;
        border: 2px solid #000000;
    }
    .btn-primary-clean:hover { background: transparent; color: #000000; }
    
    .btn-danger-clean {
        background: transparent;
        color: #b71c1c;
        border: 2px solid #b71c1c;
    }
    .btn-danger-clean:hover { background: #b71c1c; color: #ffffff; }

    /* Card */
    .card-clean {
        background: transparent;
        border-radius: 24px;
        padding: 2.5rem;
        border: 2px solid #000000;
        box-shadow: none;
    }

    .card-header-clean {
        font-size: 1.25rem;
        font-weight: 700;
        color: #000000;
        margin-bottom: 2.5rem;
        display: flex;
        align-items: center;
        gap: 0.75rem;
    }
    .card-header-clean i { color: #000000; }

    /* Forms */
    .form-section-title {
        font-size: 1.1rem;
        font-weight: 700;
        color: #000000;
        margin-bottom: 1.5rem;
        display: flex;
        align-items: center;
        gap: 0.5rem;
        padding-bottom: 0.75rem;
        border-bottom: 2px solid #000000;
    }
    .form-section-title i { color: #000000; }

    .form-label-clean {
        font-size: 0.85rem;
        text-transform: uppercase;
        letter-spacing: 0.05em;
        color: #000000;
        font-weight: 700;
        margin-bottom: 0.5rem;
        display: block;
    }

    .form-control-clean {
        width: 100%;
        border: 2px solid #000000;
        border-radius: 12px;
        padding: 0.8rem 1rem;
        background: transparent;
        color: #000000;
        font-family: inherit;
        font-weight: 600;
        transition: all 0.3s ease;
    }
    
    .form-control-clean:focus {
        outline: none;
        box-shadow: 4px 4px 0px #000000;
    }

    .form-control-clean::placeholder {
        color: rgba(0,0,0,0.4);
    }
    
    .invalid-feedback { color: #b71c1c; font-weight: 600; font-size: 0.85rem; margin-top: 0.5rem; }
    .text-danger { color: #b71c1c; }
    .text-muted-clean { color: #64748b; font-size: 0.85rem; margin-top: 0.5rem; display: block; font-weight: 500;}
    
    /* Avatar Dropzone */
    .avatar-dropzone-clean {
        border: 2px dashed #000000;
        border-radius: 20px;
        padding: 3rem 2rem;
        text-align: center;
        cursor: pointer;
        transition: all 0.3s ease;
        margin-bottom: 2rem;
        display: flex;
        flex-direction: column;
        align-items: center;
        justify-content: center;
    }
    
    .avatar-dropzone-clean:hover, .avatar-dropzone-clean.dragover {
        background: rgba(0,0,0,0.02);
    }
    
    .avatar-preview-wrapper {
        position: relative;
        margin-bottom: 1.5rem;
        display: inline-block;
    }

    .avatar-clean {
        width: 130px;
        height: 130px;
        border-radius: 50%;
        background-color: transparent;
        color: #b71c1c; /* UG color is red */
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 3rem;
        font-weight: 700;
        object-fit: cover;
        border: 2px solid #000000;
        flex-shrink: 0;
    }
    
    .avatar-edit-indicator {
        position: absolute;
        right: 0;
        bottom: 0;
        width: 44px;
        height: 44px;
        border-radius: 50%;
        background: #ffffff;
        border: 2px solid #000000;
        color: #000000;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 1.2rem;
    }
    
    .form-actions-clean {
        display: flex;
        gap: 1rem;
        justify-content: flex-end;
        padding-top: 2rem;
        border-top: 2px solid #000000;
        margin-top: 2rem;
    }

    @media (max-width: 768px) {
        .edit-header-clean {
            flex-direction: column;
            text-align: center;
            justify-content: center;
        }
        .form-actions-clean {
            flex-direction: column;
        }
        .form-actions-clean .btn-clean {
            width: 100%;
            justify-content: center;
        }
    }
    
    /* Dark Mode Rules */
    html.dark .edit-header-clean,
    html.dark .card-clean,
    html.dark .btn-outline-clean,
    html.dark .form-section-title,
    html.dark .form-control-clean,
    html.dark .avatar-dropzone-clean,
    html.dark .avatar-clean,
    html.dark .avatar-edit-indicator,
    html.dark .form-actions-clean {
        border-color: #ffffff !important;
    }
    
    html.dark .card-header-clean,
    html.dark .card-header-clean i,
    html.dark .btn-outline-clean,
    html.dark .form-section-title,
    html.dark .form-section-title i,
    html.dark .form-label-clean,
    html.dark .form-control-clean,
    html.dark .avatar-edit-indicator,
    html.dark .text-muted-clean {
        color: #ffffff !important;
    }
    
    html.dark .btn-outline-clean:hover { background: rgba(255,255,255,0.1); }
    html.dark .form-control-clean::placeholder { color: rgba(255,255,255,0.5); }
    html.dark .form-control-clean:focus { box-shadow: 4px 4px 0px #ffffff; }
    html.dark .avatar-dropzone-clean:hover { background: rgba(255,255,255,0.05); }
    html.dark .avatar-edit-indicator { background: #0f172a; } /* Solid background to prevent line collision */
    
    html.dark .btn-primary-clean {
        background: #ffffff;
        color: #000000;
        border-color: #ffffff;
    }
    html.dark .btn-primary-clean:hover {
        background: transparent;
        color: #ffffff;
    }
</style>

<div class="container-fluid profile-wrap">
    
    <!-- Header -->
    <div class="edit-header-clean">
        <h1><i class="fas fa-user-edit me-3"></i>Edit Profil</h1>
        <a href="{{ route('profile.show') }}" class="btn-clean btn-outline-clean">
            <i class="fas fa-arrow-left"></i> Kembali
        </a>
    </div>

    <!-- Main Card -->
    <div class="card-clean">
        <h2 class="card-header-clean">
            <i class="fas fa-edit"></i> Formulir Perubahan Data
        </h2>
        
        <form action="{{ route('profile.update') }}" method="POST" enctype="multipart/form-data">
            @csrf
            @method('PUT')

            <!-- Avatar Dropzone -->
            <div id="avatarDropzone" class="avatar-dropzone-clean">
                <div class="avatar-preview-wrapper">
                    @php
                        $avatarPath = $user->avatar;
                        $avatarUrl = $avatarPath ? route('avatar.show', basename($avatarPath)) : null;
                        $initials = $user->getAvatarInitials();
                    @endphp

                    @if($avatarUrl)
                        <img id="avatarPreview" src="{{ $avatarUrl }}" alt="Avatar" class="avatar-clean"
                            onerror="this.style.display='none'; this.nextElementSibling.style.display='flex';">
                        <div id="avatarPlaceholder" class="avatar-clean" style="display:none;">{{ $initials }}</div>
                    @else
                        <div id="avatarPlaceholder" class="avatar-clean">{{ $initials }}</div>
                        <img id="avatarPreview" src="" alt="Avatar" class="avatar-clean d-none">
                    @endif
                    <span class="avatar-edit-indicator" title="Ganti foto"><i class="fas fa-camera"></i></span>
                </div>
                
                <p class="text-muted-clean mb-3" style="font-size: 1rem; margin-top: 0;">Seret & letakkan foto di sini, atau klik untuk memilih file.</p>
                
                <div class="d-flex gap-2 justify-content-center">
                    <label for="avatar" class="btn-clean btn-outline-clean" style="padding: 0.5rem 1rem;">
                        <i class="fas fa-upload"></i> Pilih Foto
                    </label>
                    @if($user->avatar)
                    <button id="deleteAvatarBtn" type="button" class="btn-clean btn-danger-clean" style="padding: 0.5rem 1rem;">
                        <i class="fas fa-trash"></i> Hapus
                    </button>
                    @endif
                </div>
                <input type="file" id="avatar" name="avatar" class="d-none" accept="image/*">
                <span class="text-muted-clean mt-3">Format: JPG, PNG, GIF. Maksimal 2MB.</span>
                <div id="avatarError" class="invalid-feedback" style="display:none;"></div>
            </div>

            <div class="row">
                <!-- Data Diri -->
                <div class="col-md-6 mb-4">
                    <div class="form-section-title">
                        <i class="fas fa-id-card"></i> Identitas Diri
                    </div>
                    
                    <div class="mb-4">
                        <label for="name" class="form-label-clean">Nama Lengkap <span class="text-danger">*</span></label>
                        <input type="text" class="form-control-clean @error('name') is-invalid @enderror" id="name" name="name" value="{{ old('name', $user->name) }}" required>
                        @error('name') <div class="invalid-feedback">{{ $message }}</div> @enderror
                    </div>

                    <div class="mb-4">
                        <label for="email" class="form-label-clean">Alamat Email <span class="text-danger">*</span></label>
                        <input type="email" class="form-control-clean @error('email') is-invalid @enderror" id="email" name="email" value="{{ old('email', $user->email) }}" required>
                        @error('email') <div class="invalid-feedback">{{ $message }}</div> @enderror
                    </div>

                    <div class="mb-4" id="emailPasswordConfirmBlock">
                        <label for="current_password" class="form-label-clean">Konfirmasi Password Saat Ini <small class="text-muted fw-normal">(Diperlukan jika mengubah email)</small></label>
                        <input type="password" class="form-control-clean @error('current_password') is-invalid @enderror" id="current_password" name="current_password" placeholder="Masukkan password jika ingin mengubah email">
                        @error('current_password') <div class="invalid-feedback">{{ $message }}</div> @enderror
                    </div>

                    <div class="mb-4">
                        <label for="birth_date" class="form-label-clean">Tanggal Lahir</label>
                        <input type="date" class="form-control-clean @error('birth_date') is-invalid @enderror" id="birth_date" name="birth_date" value="{{ old('birth_date', $user->birth_date?->format('Y-m-d')) }}">
                        @error('birth_date') <div class="invalid-feedback">{{ $message }}</div> @enderror
                    </div>

                    <div class="mb-4">
                        <label for="gender" class="form-label-clean">Jenis Kelamin</label>
                        <select class="form-control-clean @error('gender') is-invalid @enderror" id="gender" name="gender">
                            <option value="">Pilih Jenis Kelamin</option>
                            <option value="male" {{ old('gender', $user->gender) === 'male' ? 'selected' : '' }}>Laki-laki</option>
                            <option value="female" {{ old('gender', $user->gender) === 'female' ? 'selected' : '' }}>Perempuan</option>
                        </select>
                        @error('gender') <div class="invalid-feedback">{{ $message }}</div> @enderror
                    </div>
                </div>

                <!-- Pekerjaan & Kontak -->
                <div class="col-md-6 mb-4">
                    <div class="form-section-title">
                        <i class="fas fa-briefcase"></i> Pekerjaan & Kontak
                    </div>

                    @if(!$user->isCitizen())
                    <div class="mb-4">
                        <label for="position" class="form-label-clean">Jabatan Instansi</label>
                        <input type="text" class="form-control-clean @error('position') is-invalid @enderror" id="position" name="position" value="{{ old('position', $user->position) }}">
                        @error('position') <div class="invalid-feedback">{{ $message }}</div> @enderror
                    </div>
                    @endif

                    <div class="mb-4">
                        <label for="phone" class="form-label-clean">Nomor Telepon</label>
                        <input type="tel" class="form-control-clean @error('phone') is-invalid @enderror" id="phone" name="phone" value="{{ old('phone', $user->phone) }}">
                        @error('phone') <div class="invalid-feedback">{{ $message }}</div> @enderror
                    </div>

                    <div class="mb-4">
                        <label for="address" class="form-label-clean">Alamat Domisili</label>
                        <textarea class="form-control-clean @error('address') is-invalid @enderror" id="address" name="address" rows="3" placeholder="Masukkan alamat lengkap...">{{ old('address', $user->address) }}</textarea>
                        @error('address') <div class="invalid-feedback">{{ $message }}</div> @enderror
                    </div>

                    <div class="mb-4">
                        <label for="bio" class="form-label-clean">Bio / Deskripsi Singkat</label>
                        <textarea class="form-control-clean @error('bio') is-invalid @enderror" id="bio" name="bio" rows="3" placeholder="Deskripsikan diri Anda...">{{ old('bio', $user->bio) }}</textarea>
                        @error('bio') <div class="invalid-feedback">{{ $message }}</div> @enderror
                        <span class="text-muted-clean">Maksimal 1000 karakter.</span>
                    </div>
                </div>
            </div>

            <div class="form-actions-clean">
                <a href="{{ route('profile.show') }}" class="btn-clean btn-outline-clean">
                    <i class="fas fa-times"></i> Batal
                </a>
                <button type="submit" class="btn-clean btn-primary-clean">
                    <i class="fas fa-save"></i> Simpan Perubahan
                </button>
            </div>
        </form>

        @if($user->avatar)
        <form id="deleteAvatarForm" action="{{ route('profile.avatar.delete') }}" method="POST" class="d-none">
            @csrf
            @method('DELETE')
        </form>
        @endif
    </div>
</div>

<script>
(function(){
    const fileInput = document.getElementById('avatar');
    const dropzone = document.getElementById('avatarDropzone');
    const previewImg = document.getElementById('avatarPreview');
    const placeholder = document.getElementById('avatarPlaceholder');
    const errorBox = document.getElementById('avatarError');
    const maxSize = 2 * 1024 * 1024; // 2MB

    function showError(msg){ if(!errorBox) return; errorBox.style.display='block'; errorBox.textContent = msg; }
    function clearError(){ if(!errorBox) return; errorBox.style.display='none'; errorBox.textContent=''; }

    function handleFiles(file){
        clearError();
        if(!file) return;
        const validTypes = ['image/jpeg','image/png','image/gif'];
        if(!validTypes.includes(file.type)){
            showError('Format tidak didukung. Gunakan JPG, PNG, atau GIF.');
            fileInput.value = '';
            return;
        }
        if(file.size > maxSize){
            showError('Ukuran file melebihi 2MB.');
            fileInput.value = '';
            return;
        }
        const reader = new FileReader();
        reader.onload = (ev)=>{
            if(previewImg){
                previewImg.src = ev.target.result;
                previewImg.classList.remove('d-none');
            }
            if(placeholder){ placeholder.classList.add('d-none'); }
        };
        reader.readAsDataURL(file);
    }

    if(fileInput){
        fileInput.addEventListener('change', (e)=> handleFiles(e.target.files[0]));
    }
    if(dropzone){
        dropzone.addEventListener('click', (e)=> {
            if(e.target.closest('#deleteAvatarBtn')) return;
            if(fileInput) fileInput.click();
        });
        dropzone.addEventListener('dragover', (e)=>{ e.preventDefault(); dropzone.classList.add('dragover'); });
        dropzone.addEventListener('dragleave', ()=> dropzone.classList.remove('dragover'));
        dropzone.addEventListener('drop', (e)=>{
            e.preventDefault();
            dropzone.classList.remove('dragover');
            const file = e.dataTransfer.files && e.dataTransfer.files[0];
            if(file){
                const dt = new DataTransfer();
                dt.items.add(file);
                fileInput.files = dt.files;
                handleFiles(file);
            }
        });
    }

    const delBtn = document.getElementById('deleteAvatarBtn');
    const delForm = document.getElementById('deleteAvatarForm');
    if(delBtn && delForm){
        delBtn.addEventListener('click', (e)=>{
            e.stopPropagation();
            if(confirm('Apakah Anda yakin ingin menghapus foto profil?')) {
                delForm.submit();
            }
        });
    }
})();
</script>
@endsection
