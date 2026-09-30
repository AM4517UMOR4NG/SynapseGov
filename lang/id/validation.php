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

    'accepted' => ':Attribute harus disetujui.',
    'after' => ':Attribute harus berupa tanggal setelah :date.',
    'after_or_equal' => ':Attribute harus berupa tanggal setelah atau sama dengan :date.',
    'alpha' => ':Attribute hanya boleh berisi huruf.',
    'alpha_dash' => ':Attribute hanya boleh berisi huruf, angka, tanda hubung, dan garis bawah.',
    'alpha_num' => ':Attribute hanya boleh berisi huruf dan angka.',
    'array' => ':Attribute harus berupa daftar.',
    'before' => ':Attribute harus berupa tanggal sebelum :date.',
    'before_or_equal' => ':Attribute harus berupa tanggal sebelum atau sama dengan :date.',
    'between' => [
        'array' => ':Attribute harus berisi antara :min dan :max item.',
        'file' => ':Attribute harus berukuran antara :min dan :max kilobyte.',
        'numeric' => ':Attribute harus bernilai antara :min dan :max.',
        'string' => ':Attribute harus terdiri dari :min sampai :max karakter.',
    ],
    'boolean' => ':Attribute harus bernilai ya atau tidak.',
    'confirmed' => 'Konfirmasi :attribute tidak cocok.',
    'current_password' => 'Kata sandi saat ini salah.',
    'date' => ':Attribute bukan tanggal yang valid.',
    'date_format' => ':Attribute harus sesuai format :format.',
    'different' => ':Attribute dan :other harus berbeda.',
    'digits' => ':Attribute harus terdiri dari :digits digit.',
    'digits_between' => ':Attribute harus terdiri dari :min sampai :max digit.',
    'dimensions' => 'Dimensi gambar :attribute tidak valid.',
    'email' => ':Attribute harus berupa alamat email yang valid.',
    'exists' => ':Attribute yang dipilih tidak valid.',
    'file' => ':Attribute harus berupa berkas.',
    'filled' => ':Attribute wajib diisi.',
    'image' => ':Attribute harus berupa gambar.',
    'in' => ':Attribute yang dipilih tidak valid.',
    'integer' => ':Attribute harus berupa bilangan bulat.',
    'max' => [
        'array' => ':Attribute maksimal berisi :max item.',
        'file' => ':Attribute maksimal berukuran :max kilobyte.',
        'numeric' => ':Attribute maksimal bernilai :max.',
        'string' => ':Attribute maksimal :max karakter.',
    ],
    'mimes' => ':Attribute harus berupa berkas bertipe: :values.',
    'mimetypes' => ':Attribute harus berupa berkas bertipe: :values.',
    'min' => [
        'array' => ':Attribute minimal berisi :min item.',
        'file' => ':Attribute minimal berukuran :min kilobyte.',
        'numeric' => ':Attribute minimal bernilai :min.',
        'string' => ':Attribute minimal :min karakter.',
    ],
    'not_in' => ':Attribute yang dipilih tidak valid.',
    'numeric' => ':Attribute harus berupa angka.',
    'present' => ':Attribute wajib ada.',
    'regex' => 'Format :attribute tidak valid.',
    'required' => ':Attribute wajib diisi.',
    'required_if' => ':Attribute wajib diisi jika :other adalah :value.',
    'required_with' => ':Attribute wajib diisi jika :values diisi.',
    'same' => ':Attribute dan :other harus sama.',
    'size' => [
        'array' => ':Attribute harus berisi :size item.',
        'file' => ':Attribute harus berukuran :size kilobyte.',
        'numeric' => ':Attribute harus bernilai :size.',
        'string' => ':Attribute harus terdiri dari :size karakter.',
    ],
    'string' => ':Attribute harus berupa teks.',
    'timezone' => ':Attribute harus berupa zona waktu yang valid.',
    'unique' => ':Attribute sudah terdaftar.',
    'uploaded' => ':Attribute gagal diunggah.',
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
