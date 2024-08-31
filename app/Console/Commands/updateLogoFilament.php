<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use File;

class updateLogoFilament extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'app:update-logo-filament';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Update code logo filament';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        // Path ke file logo yang akan menggantikan
        $sourcePath = resource_path('views/template/logo.blade.php');

        // Path ke file logo di vendor yang akan diganti
        $destinationPath = base_path('vendor/filament/filament/resources/views/components/logo.blade.php');

        // Mengecek apakah file source ada
        if (!File::exists($sourcePath)) {
            $this->error("Source file tidak ditemukan: {$sourcePath}");
            return;
        }

        // Mengecek apakah file tujuan ada
        if (!File::exists($destinationPath)) {
            $this->error("File di vendor tidak ditemukan: {$destinationPath}");
            return;
        }

        // Mengganti file di vendor dengan file dari template
        File::copy($sourcePath, $destinationPath);

        // Pesan sukses
        $this->info('Logo Filament berhasil diperbarui.');
    }
}
