<?php

namespace Database\Seeders;

use App\Models\Vendor;
use Illuminate\Database\Seeder;

class VendorSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $vendors = [
            [
                'name' => '総合卸業者',
                'address' => '福岡市中央区',
                'email' => 'vendor@example.com',
                'phone_number' => '09012345678',
                'contact_person' => '担当者名',
            ],
            [
                'name' => '八百屋',
                'address' => '福岡市南区',
                'email' => 'yaoya@example.com',
                'phone_number' => '09012345678',
                'contact_person' => '担当者名',
            ],
            [
                'name' => '精肉店',
                'address' => '福岡市南区',
                'email' => 'nikuya@example.com',
                'phone_number' => '09012345678',
                'contact_person' => '担当者名',
            ],
            [
                'name' => '精魚店',
                'address' => '福岡市南区',
                'email' => 'sakanaya@example.com',
                'phone_number' => '09012345678',
                'contact_person' => '担当者名',
            ],
        ];

        foreach ($vendors as $vendor) {
            Vendor::create($vendor);
        }
    }
}
