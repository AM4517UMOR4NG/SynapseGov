<?php

namespace Database\Factories;

use App\Models\Department;
use Illuminate\Database\Eloquent\Factories\Factory;

class DepartmentFactory extends Factory
{
    protected $model = Department::class;

    public function definition(): array
    {
        $departments = [
            ['name' => 'Dinas Pekerjaan Umum dan Penataan Ruang', 'code' => 'PWD'],
            ['name' => 'Dinas Kesehatan', 'code' => 'HD'],
            ['name' => 'Dinas Pendidikan', 'code' => 'ED'],
            ['name' => 'Satuan Polisi Pamong Praja', 'code' => 'PSD'],
            ['name' => 'Dinas Lingkungan Hidup', 'code' => 'ENV'],
            ['name' => 'Dinas Perhubungan', 'code' => 'TD'],
            ['name' => 'Badan Keuangan Daerah', 'code' => 'FD'],
        ];

        $dept = $this->faker->randomElement($departments);

        return [
            'name' => $dept['name'],
            'code' => $dept['code'],
            'description' => $this->faker->sentence(),
        ];
    }
}
