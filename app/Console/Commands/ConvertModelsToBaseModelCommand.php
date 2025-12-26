<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\File;

class ConvertModelsToBaseModelCommand extends Command
{
    protected $signature = 'models:use-base-model';
    protected $description = 'Convert models extending Illuminate\Database\Eloquent\Model to BaseModel';

    public function handle(): int
    {
        $modelsPath = app_path('Models');

        foreach (File::allFiles($modelsPath) as $file) {

            $path    = $file->getRealPath();
            $content = File::get($path);

            // Skip if already extends BaseModel
            if (str_contains($content, 'extends BaseModel')) {
                continue;
            }

            // Match: extends Model (ONLY)
            if (! preg_match('/extends\s+Model\b/', $content)) {
                continue;
            }

            // Replace "extends Model" → "extends BaseModel"
            $content = preg_replace(
                '/extends\s+Model\b/',
                'extends BaseModel',
                $content,
                1
            );

            // Add BaseModel import if missing
            if (! str_contains($content, 'use App\Models\BaseModel;')) {
                $content = preg_replace(
                    '/(namespace\s+App\\\Models;\s*)/',
                    "$1\nuse App\Models\BaseModel;\n",
                    $content,
                    1
                );
            }

            // Remove unused Illuminate Model import (optional but clean)
            $content = preg_replace(
                '/use\s+Illuminate\\\Database\\\Eloquent\\\Model;\s*/',
                '',
                $content
            );

            File::put($path, $content);
            $this->info('Updated: ' . $file->getFilename());
        }

        $this->info('✔ All eligible models now extend BaseModel.');

        return self::SUCCESS;
    }
}
