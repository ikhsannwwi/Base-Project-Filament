<?php

namespace App\Console\Commands;

use App\Models\AdminMenu;
use Illuminate\Support\Str;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Artisan;

class MakeFilamentResourceWithMenu extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'make:filament-resource-with-menu {name}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Create a Filament resource and a corresponding menu entry';

    /**
     * Execute the console command.
     */
    public function __construct()
    {
        parent::__construct();
    }

    public function handle()
    {
        $name = $this->argument('name');
        
        Artisan::call('make:filament-resource', ['name' => $name]);

        $this->createMenuEntry($name);

        $this->copyAndModifyPolicyFile($name);

        $this->info('Filament resource created, policy file copied, and menu entry added successfully.');
    }

    protected function createMenuEntry($name)
    {
        if (AdminMenu::where('name', $name)->exists()) {
            $this->info('Menu entry already exists.');
            return;
        }

        AdminMenu::create([
            'identifier' => Str::of($name)->lower()->snake(),
            'name' => $name,
        ]);
    }

    protected function copyAndModifyPolicyFile($name)
    {
        $name = str_replace(' ', '', $name);
        $templatePath = app_path('Policies/TemplatePolicy.php');
        $newPolicyPath = app_path("Policies/{$name}Policy.php");

        if (!file_exists($templatePath)) {
            $this->error('TemplatePolicy.php does not exist.');
            return;
        }

        if (file_exists($newPolicyPath)) {
            $this->info("{$name}Policy.php already exists.");
            return;
        }

        $content = file_get_contents($templatePath);

        $replacements = [
            'TemplatePolicy' => "{$name}Policy",
            'Template' => $name,
            "AdminMenu::where('name', 'Template')" => "AdminMenu::where('name', '{$name}')",
        ];

        $newContent = str_replace(array_keys($replacements), array_values($replacements), $content);

        if (file_put_contents($newPolicyPath, $newContent) !== false) {
            $this->info("Policy file {$name}Policy.php created successfully.");
        } else {
            $this->error('Failed to create the policy file.');
        }
    }
}
