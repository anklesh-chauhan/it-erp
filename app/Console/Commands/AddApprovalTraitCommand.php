<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\File;

class AddApprovalTraitCommand extends Command
{
    protected $signature = 'models:add-approval-trait';
    protected $description = 'Add HasApprovalWorkflow trait to all models';

    public function handle()
    {
        $modelsPath = app_path('Models');

        foreach (File::allFiles($modelsPath) as $file) {
            $path = $file->getRealPath();
            $content = File::get($path);

            // Insert import if missing
            if (!str_contains($content, 'use App\Traits\HasApprovalWorkflow;')) {
                $content = preg_replace(
                    '/(namespace\s+App\\\Models;\s*)([\s\S]*?)(class\s)/',
                    "$1$2use App\Traits\HasApprovalWorkflow;\n\n$3",
                    $content,
                    1
                );
            }

            // Add trait inside class
            if (preg_match('/class\s+[^\{]+\{/', $content)) {

                // If class has use traits already
                if (preg_match('/class\s+[^\{]+\{\s*use\s+([^;]+);/', $content)) {

                    $content = preg_replace_callback(
                        '/(class\s+[^\{]+\{\s*use\s+)([^;]+)(;)/',
                        function ($m) {
                            $traits = array_map('trim', explode(',', $m[2]));

                            if (!in_array('HasApprovalWorkflow', $traits)) {
                                $traits[] = 'HasApprovalWorkflow';
                            }

                            return $m[1] . implode(', ', $traits) . $m[3];
                        },
                        $content,
                        1
                    );

                } else {
                    // No "use" inside class → add new
                    $content = preg_replace(
                        '/(class\s+[^\{]+\{)/',
                        "$1\n    use HasApprovalWorkflow;\n",
                        $content,
                        1
                    );
                }
            }

            File::put($path, $content);
            $this->info("Updated: " . $file->getFilename());
        }

        $this->info('✔ All models updated successfully!');
    }
}
