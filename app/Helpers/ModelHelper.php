<?php

namespace App\Helpers;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\File;
use ReflectionClass;

class ModelHelper
{
    public static function getModelOptions(): array
    {
        $modelPath = app_path('Models');
        $models = [];

        if (! File::exists($modelPath)) {
            return [];
        }

        foreach (File::allFiles($modelPath) as $file) {
            $namespace = "App\\Models\\";
            $class = $namespace . $file->getFilenameWithoutExtension();

            if (! class_exists($class)) {
                continue;
            }

            // Make sure it's a valid Eloquent Model
            if (is_subclass_of($class, Model::class)) {
                $models[$class] = self::labelFromClass($class);
            }
        }

        return $models;
    }

    /**
     * Convert model class into label — e.g. "App\Models\SalesOrder" → "Sales Order"
     */
    private static function labelFromClass(string $class): string
    {
        $short = (new ReflectionClass($class))->getShortName();

        return trim(preg_replace('/(?<!^)[A-Z]/', ' $0', $short));
    }
}
