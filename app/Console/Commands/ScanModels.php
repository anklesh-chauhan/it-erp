<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\File;

class ScanModels extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'app:scan-models';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command description';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $modelsPath = app_path('Models');
        $namespace = 'App\\Models\\';

        $modelFiles = File::files($modelsPath);

        $modelList = [];

        foreach ($modelFiles as $file) {
            $modelName = pathinfo($file->getFilename(), PATHINFO_FILENAME);
            $modelList[$namespace . $modelName] = $modelName;
        }

        $this->updateModelHelper($modelList);

        $this->info('ModelHelper updated successfully with detected models.');
    }

    protected function updateModelHelper(array $modelList)
    {
        $modelData = var_export($modelList, true);

        $content = <<<PHP
        <?php

        namespace App\Helpers;

        class ModelHelper
        {
            public static function getModelOptions(): array
            {
                return {$modelData};
            }
        }
        PHP;

        File::put(app_path('Helpers/ModelHelper.php'), $content);
    }
}
