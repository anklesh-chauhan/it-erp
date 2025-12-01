<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use RecursiveDirectoryIterator;
use RecursiveIteratorIterator;

class AddBulkApprovalActionToResources extends Command
{
    protected $signature = 'resources:add-bulk-approval-action';
    protected $description = 'Inject BulkApprovalAction::make() inside BulkActionGroup::make([ in Filament Resources (excluding RelationManagers)';

    public function handle()
    {
        $resourcePath = app_path('Filament/Resources');

        if (!is_dir($resourcePath)) {
            $this->error("Resources folder not found: $resourcePath");
            return Command::FAILURE;
        }

        $iterator = new RecursiveIteratorIterator(
            new RecursiveDirectoryIterator($resourcePath)
        );

        foreach ($iterator as $file) {
            if ($file->isFile() && str_ends_with($file->getFilename(), 'Resource.php')) {
                $this->processFile($file->getPathname());
            }
        }

        $this->info("✔ BulkApprovalAction injected successfully into all eligible resources.");
        return Command::SUCCESS;
    }

    private function processFile(string $path)
    {
        // Skip RelationManager directories
        if (str_contains($path, DIRECTORY_SEPARATOR . 'RelationManagers' . DIRECTORY_SEPARATOR)) {
            return;
        }

        $content = file_get_contents($path);

        // Skip if file contains class ...RelationManager
        if (preg_match('/class\s+\w+RelationManager\b/', $content)) {
            return;
        }

        // Skip if already injected
        if (strpos($content, 'BulkApprovalAction::make') !== false) {
            $this->line("⏭ Skipped (already added): " . basename($path));
            return;
        }

        // Add import if missing
        if (!preg_match('/use\s+App\\\\Filament\\\\Actions\\\\BulkApprovalAction;/', $content)) {
            $content = preg_replace(
                '/namespace\s+[^;]+;/',
                "$0\n\nuse App\\Filament\\Actions\\BulkApprovalAction;",
                $content,
                1
            );
        }

        // Inject after BulkActionGroup::make([
        $pattern = '/(BulkActionGroup::make\s*\(\s*\[\s*)/';

        if (preg_match($pattern, $content)) {
            $replacement = "$1\n                        BulkApprovalAction::make(),\n\n";

            $content = preg_replace($pattern, $replacement, $content, 1);

            file_put_contents($path, $content);
            $this->info("✔ Updated: " . basename($path));
        } else {
            $this->warn("⚠ BulkActionGroup::make([ not found in: " . basename($path));
        }
    }
}
