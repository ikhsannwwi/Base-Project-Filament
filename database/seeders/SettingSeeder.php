<?php

namespace Database\Seeders;

use App\Models\Setting;
use Illuminate\Database\Seeder;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;

class SettingSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Setting::updateOrCreate(
            ['name' => 'favicon'],
            ['value' => '']
        );
        
        Setting::updateOrCreate(
            ['name' => 'logo_image'],
            ['value' => '']
        );
        
        Setting::updateOrCreate(
            ['name' => 'project_name'],
            ['value' => '']
        );
    }
}
