<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use RecursiveDirectoryIterator;
use RecursiveIteratorIterator;

class AddApprovalActionToResources extends Command
{
    protected $signature = 'resources:add-approval-action';
    protected $description = 'Inject ApprovalAction::make() after EditAction::make() in Filament Resources (excluding RelationManagers)';

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
            if ($file->isFile() && $file->getExtension() === 'php') {
                $this->processFile($file->getPathname());
            }
        }

        $this->info("✔ ApprovalAction injected successfully into all eligible resources.");
        return Command::SUCCESS;
    }

    private function processFile(string $path)
    {
        // Skip if file belongs to RelationManagers directory
        if (str_contains($path, DIRECTORY_SEPARATOR . 'RelationManagers' . DIRECTORY_SEPARATOR)) {
            return;
        }

        $content = file_get_contents($path);

        // Skip if file class ends with "RelationManager"
        if (preg_match('/class\s+\w*RelationManager\b/', $content)) {
            return;
        }

        // Already added?
        if (strpos($content, 'ApprovalAction::make') !== false) {
            $this->line("⏭ Skipped (already added): " . basename($path));
            return;
        }

        // Add import if missing
        if (!preg_match('/use\s+App\\\\Filament\\\\Actions\\\\ApprovalAction;/', $content)) {
            $content = preg_replace(
                '/namespace\s+[^;]+;/',
                "$0\n\nuse App\\Filament\\Actions\\ApprovalAction;",
                $content,
                1
            );
        }

        // Insert after EditAction::make()
        $pattern = '/(EditAction::make\s*\(\s*\)\s*,?)/';

        if (preg_match($pattern, $content)) {
            $replacement = "$1\n                ApprovalAction::make(),";

            $content = preg_replace($pattern, $replacement, $content, 1);

            file_put_contents($path, $content);
            $this->info("✔ Updated: " . basename($path));
        } else {
            $this->warn("⚠ EditAction::make() not found: " . basename($path));
        }
    }
}
