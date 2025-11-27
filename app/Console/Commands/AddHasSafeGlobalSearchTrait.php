<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use RecursiveDirectoryIterator;
use RecursiveIteratorIterator;

class AddHasSafeGlobalSearchTrait extends Command
{
    protected $signature = 'resources:add-has-safe-global-search';
    protected $description = 'Add HasSafeGlobalSearch trait only to Resource.php files and skip RelationManagers';

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
            if (! $file->isFile()) continue;

            $path = $file->getPathname();

            // Only apply to xxxResource.php
            if (!str_ends_with($path, 'Resource.php')) continue;

            // Skip RelationManagers folder
            if (str_contains($path, DIRECTORY_SEPARATOR . 'RelationManagers' . DIRECTORY_SEPARATOR)) continue;

            $this->processFile($path);
        }

        $this->info("✔ Finished adding HasSafeGlobalSearch to Resource.php files.");
        return Command::SUCCESS;
    }

    private function processFile(string $path)
    {
        $content = file_get_contents($path);
        $updated = false;

        //
        // 1️⃣ Add import at top
        //
        if (!preg_match('/use\s+App\\\\Traits\\\\HasSafeGlobalSearch;/', $content)) {
            $content = preg_replace(
                '/(namespace\s+[^\;]+;)/',
                "$1\n\nuse App\\Traits\\HasSafeGlobalSearch;",
                $content,
                1
            );
            $updated = true;
        }

        //
        // 2️⃣ Inject inside class { ... }
        //

        // Find class opening
        if (preg_match('/class\s+\w+\s+extends\s+\w+[^{]*\{/', $content, $match)) {

            $classOpen = $match[0];

            // Check IF trait already used
            if (!preg_match('/use\s+HasSafeGlobalSearch\s*;/', $content)) {

                // Inject AFTER the class opening bracket
                $newClassOpen = $classOpen . "\n    use HasSafeGlobalSearch;";

                $content = str_replace($classOpen, $newClassOpen, $content);
                $updated = true;
            }
        }

        if ($updated) {
            file_put_contents($path, $content);
            $this->info("✔ Updated → " . basename($path));
        } else {
            $this->line("⏭ Skipped (Already OK) → " . basename($path));
        }
    }
}
