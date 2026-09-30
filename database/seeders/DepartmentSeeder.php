<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class DepartmentSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $departments = [
            [
                'name' => 'Dinas Pekerjaan Umum dan Penataan Ruang',
                'description' => 'Menangani jalan, jembatan, drainase, dan infrastruktur publik',
                'code' => 'PWD',
                'email' => 'pwd@government.gov',
                'phone' => '0274-510101',
                'address' => 'Kompleks Balai Kota, Blok A',
                'is_active' => true,
            ],
            [
                'name' => 'Dinas Kesehatan',
                'description' => 'Menangani layanan kesehatan masyarakat dan fasilitas kesehatan',
                'code' => 'HD',
                'email' => 'health@government.gov',
                'phone' => '0274-510102',
                'address' => 'Kompleks Balai Kota, Blok B',
                'is_active' => true,
            ],
            [
                'name' => 'Dinas Pendidikan',
                'description' => 'Menangani sekolah, program pendidikan, dan sarana belajar',
                'code' => 'ED',
                'email' => 'education@government.gov',
                'phone' => '0274-510103',
                'address' => 'Kompleks Balai Kota, Blok C',
                'is_active' => true,
            ],
            [
                'name' => 'Satuan Polisi Pamong Praja',
                'description' => 'Menangani ketertiban umum dan ketenteraman masyarakat',
                'code' => 'PSD',
                'email' => 'safety@government.gov',
                'phone' => '0274-510104',
                'address' => 'Kompleks Balai Kota, Blok D',
                'is_active' => true,
            ],
            [
                'name' => 'Dinas Lingkungan Hidup',
                'description' => 'Menangani persampahan, kebersihan, dan pelestarian lingkungan',
                'code' => 'ENV',
                'email' => 'environment@government.gov',
                'phone' => '0274-510105',
                'address' => 'Kompleks Balai Kota, Blok E',
                'is_active' => true,
            ],
        ];

        foreach ($departments as $department) {
            \App\Models\Department::create($department);
        }
    }
}
