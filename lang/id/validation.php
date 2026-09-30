<?php

return [

    /*
    |--------------------------------------------------------------------------
    | Validation Language Lines (Bahasa Indonesia)
    |--------------------------------------------------------------------------
    |
    | Rules that are not listed here fall back to the framework's English lines.
    |
    */

    'accepted' => ':attribute harus disetujui.',
    'after' => ':attribute harus berupa tanggal setelah :date.',
    'after_or_equal' => ':attribute harus berupa tanggal setelah atau sama dengan :date.',
    'alpha' => ':attribute hanya boleh berisi huruf.',
    'alpha_dash' => ':attribute hanya boleh berisi huruf, angka, tanda hubung, dan garis bawah.',
    'alpha_num' => ':attribute hanya boleh berisi huruf dan angka.',
    'array' => ':attribute harus berupa daftar.',
    'before' => ':attribute harus berupa tanggal sebelum :date.',
    'before_or_equal' => ':attribute harus berupa tanggal sebelum atau sama dengan :date.',
    'between' => [
        'array' => ':attribute harus berisi antara :min dan :max item.',
        'file' => ':attribute harus berukuran antara :min dan :max kilobyte.',
        'numeric' => ':attribute harus bernilai antara :min dan :max.',
        'string' => ':attribute harus terdiri dari :min sampai :max karakter.',
    ],
    'boolean' => ':attribute harus bernilai ya atau tidak.',
    'confirmed' => 'Konfirmasi :attribute tidak cocok.',
    'current_password' => 'Kata sandi saat ini salah.',
    'date' => ':attribute bukan tanggal yang valid.',
    'date_format' => ':attribute harus sesuai format :format.',
    'different' => ':attribute dan :other harus berbeda.',
    'digits' => ':attribute harus terdiri dari :digits digit.',
    'digits_between' => ':attribute harus terdiri dari :min sampai :max digit.',
    'dimensions' => 'Dimensi gambar :attribute tidak valid.',
    'email' => ':attribute harus berupa alamat email yang valid.',
    'exists' => ':attribute yang dipilih tidak valid.',
    'file' => ':attribute harus berupa berkas.',
    'filled' => ':attribute wajib diisi.',
    'image' => ':attribute harus berupa gambar.',
    'in' => ':attribute yang dipilih tidak valid.',
    'integer' => ':attribute harus berupa bilangan bulat.',
    'max' => [
        'array' => ':attribute maksimal berisi :max item.',
        'file' => ':attribute maksimal berukuran :max kilobyte.',
        'numeric' => ':attribute maksimal bernilai :max.',
        'string' => ':attribute maksimal :max karakter.',
    ],
    'mimes' => ':attribute harus berupa berkas bertipe: :values.',
    'mimetypes' => ':attribute harus berupa berkas bertipe: :values.',
    'min' => [
        'array' => ':attribute minimal berisi :min item.',
        'file' => ':attribute minimal berukuran :min kilobyte.',
        'numeric' => ':attribute minimal bernilai :min.',
        'string' => ':attribute minimal :min karakter.',
    ],
    'not_in' => ':attribute yang dipilih tidak valid.',
    'numeric' => ':attribute harus berupa angka.',
    'present' => ':attribute wajib ada.',
    'regex' => 'Format :attribute tidak valid.',
    'required' => ':attribute wajib diisi.',
    'required_if' => ':attribute wajib diisi jika :other adalah :value.',
    'required_with' => ':attribute wajib diisi jika :values diisi.',
    'same' => ':attribute dan :other harus sama.',
    'size' => [
        'array' => ':attribute harus berisi :size item.',
        'file' => ':attribute harus berukuran :size kilobyte.',
        'numeric' => ':attribute harus bernilai :size.',
        'string' => ':attribute harus terdiri dari :size karakter.',
    ],
    'string' => ':attribute harus berupa teks.',
    'timezone' => ':attribute harus berupa zona waktu yang valid.',
    'unique' => ':attribute sudah terdaftar.',
    'uploaded' => ':attribute gagal diunggah.',
    'url' => 'Format :attribute tidak valid.',

    /*
    |--------------------------------------------------------------------------
    | Custom Validation Attributes
    |--------------------------------------------------------------------------
    */

    'attributes' => [
        'name' => 'nama',
        'email' => 'email',
        'password' => 'kata sandi',
        'current_password' => 'kata sandi saat ini',
        'phone' => 'nomor telepon',
        'address' => 'alamat',
        'id_number' => 'NIK',
        'birth_date' => 'tanggal lahir',
        'gender' => 'jenis kelamin',
        'avatar' => 'foto profil',
        'bio' => 'bio',
        'position' => 'jabatan',
        'title' => 'judul',
        'description' => 'deskripsi',
        'category' => 'kategori',
        'priority' => 'prioritas',
        'location' => 'lokasi',
        'department_id' => 'OPD',
        'code' => 'kode',
        'status' => 'status',
        'assigned_to' => 'petugas',
        'attachments' => 'lampiran',
        'attachments.*' => 'lampiran',
        'notes' => 'catatan',
        'reason' => 'alasan',
        'content' => 'isi komentar',
        'information' => 'informasi tambahan',
        'feedback' => 'umpan balik',
        'completion_notes' => 'catatan penyelesaian',
        'resolution_notes' => 'catatan resolusi',
        'final_notes' => 'catatan akhir',
        'rejection_reason' => 'alasan penolakan',
    ],

];
