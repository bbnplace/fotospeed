<?php

namespace Database\Seeders;

use App\Models\Setting;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class SettingsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Setting::create([
            'max_file_size' => 10240,
            'thumbnail_size' => 150,
            'file_mime_types' => 'jpeg,png,jpg,gif',
            'email_port' => 465,
            'min_order_processing_days' => 0,
            'max_order_processing_days' => 0,
        ]);
    }
}
