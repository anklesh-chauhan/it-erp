<?php

namespace App\Services;

use Illuminate\Support\Facades\Log;
use Intervention\Image\ImageManager;
use Intervention\Image\Drivers\Gd\Driver;

class ImageWatermarkService
{
    public static function apply(string $path, array $meta): void
    {
        // Ensure file exists before reading
        if (!file_exists($path)) {
            return;
        }

        $manager = new ImageManager(new Driver());

        try {
            $image = $manager->read($path);

            $text = sprintf(
                "%s\n",
                now()->format('d-m-Y H:i'),
            );

            $fontPath = public_path('fonts/Roboto/static/Roboto-Regular.ttf');

            $image->text($text, 20, 20, function($font) use ($fontPath) {
                $font->filename($fontPath); // Point to the physical file
                $font->size(10);
                $font->color('#ffffff');
                $font->stroke('#000000', 1);
            });

            $image->save($path);

        } catch (\Exception $e) {
            // Log error if decoding fails
            Log::error("Watermark failed: " . $e->getMessage());
        }
    }
}
