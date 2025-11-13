<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\DB;

class GuestSeeder extends Seeder
{
    public function run(): void
    {
        $guests = [
            [
                'full_name' => 'Nguyễn Văn An',
                'phone' => '0905123456',
            ],
            [
                'full_name' => 'Trần Thị Bích',
                'phone' => '0912345678',
            ],
            [
                'full_name' => 'Lê Quốc Cường',
                'phone' => '0987654321',
            ],
            [
                'full_name' => 'Phạm Thu Hà',
                'phone' => '0977111222',
            ],
            [
                'full_name' => 'Đỗ Minh Khoa',
                'phone' => '0933666777',
            ],
        ];

        foreach ($guests as &$guest) {
            $guest['status'] = false;
            $guest['number'] = null;
            $guest['qr_token'] = Str::uuid()->toString();
            $guest['invited_at'] = now();
            $guest['arrived_at'] = null;
            $guest['created_at'] = now();
            $guest['updated_at'] = now();
        }

        DB::table('guests')->insert($guests);
    }
}
