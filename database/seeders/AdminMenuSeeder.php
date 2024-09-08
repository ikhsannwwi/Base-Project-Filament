<?php

namespace Database\Seeders;

use App\Models\AdminMenu;
use Illuminate\Database\Seeder;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;

class AdminMenuSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $datas = [
            'Admin Menu',
            'Setting',
            'Log System',
            'User Group',
            'User'
        ];
        foreach ($datas as $value) {
            AdminMenu::create(['name' => $value]);
        }
    }
}
