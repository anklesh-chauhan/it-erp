<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\File;

class ReplaceFilamentResourceBase extends Command
{
    protected $signature = 'filament:use-base-resource
        {path=app/Filament/Resources : Path to Filament resources}';

    protected $description = 'Replace "extends Resource" with "extends BaseResource" in Filament Resources';

    public function handle(): int
    {
        $basePath = base_path($this->argument('path'));

        if (! File::exists($basePath)) {
            $this->error("Path not found: {$basePath}");
            return self::FAILURE;
        }

        $files = File::allFiles($basePath);
        $updated = 0;

        foreach ($files as $file) {
            if ($file->getExtension() !== 'php') {
                continue;
            }

            $content = File::get($file->getRealPath());

            // Skip if already using BaseResource
            if (str_contains($content, 'extends BaseResource')) {
                continue;
            }

            if (! str_contains($content, 'extends Resource')) {
                continue;
            }

            // Replace class inheritance
            $content = str_replace(
                'extends Resource',
                'extends BaseResource',
                $content
            );

            // Add use statement if missing
            if (! str_contains($content, 'use App\\Filament\\Resources\\BaseResource;')) {
                $content = preg_replace(
                    '/use Filament\\\\Resources\\\\Resource;\\R/',
                    "use Filament\\\\Resources\\\\Resource;\nuse App\\\\Filament\\\\Resources\\\\BaseResource;\n",
                    $content,
                    1
                );
            }

            File::put($file->getRealPath(), $content);
            $updated++;

            $this->info("✔ Updated: {$file->getRelativePathname()}");
        }

        $this->info("\n✅ Done. {$updated} resource(s) updated.");

        return self::SUCCESS;
    }
}
