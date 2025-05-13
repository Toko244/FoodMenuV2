<?php

namespace App\Services;

use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Intervention\Image\Laravel\Facades\Image;

class FileUploadService
{
    public function fileUpload($file, $storagePath = 'misc', $dimensionsArray = null, $filesystem = 'public'): array
    {
        $image = Image::read($file);
        $convert = $image->toWebp(60);
        $extension = explode('/', $convert->mediaType())[1];
        $originalTitle = $file->getClientOriginalName();

        $name = Str::random(10).str_replace([' ', '_'], '-', strtotime(now()));

        $path = "{$storagePath}/{$name}.{$extension}";

        Storage::disk($filesystem)->put($path, $convert);

        if ($dimensionsArray) {
            $this->resizeImage($image, $dimensionsArray, $storagePath, $filesystem, $name, $extension);
        }

        return [
            'title' => $originalTitle,
            'path' => $path,
        ];
    }

    protected function resizeImage($image, $dimensionsArray, $storagePath, $filesystem, $name, $extension): void
    {
        foreach ($dimensionsArray as $dimensions) {
            $width = $dimensions['x'];
            $height = $dimensions['y'];

            $resizedImage = $image->resize($width, $height, function ($constraint) {
                $constraint->aspectRatio();
            });

            $resizedName = "{$name}_{$width}X{$height}.{$extension}";
            $resizedPath = "{$storagePath}/{$resizedName}";

            Storage::disk($filesystem)->put($resizedPath, (string) $resizedImage->toWebp(60));
        }
    }

    public function deleteFile($path, $dimensionsArray = null, $filesystem = 'public'): void
    {
        $fullPath = "{$filesystem}/{$path}";

        if ($dimensionsArray) {
            foreach ($dimensionsArray as $dimensions) {
                $width = $dimensions['x'];
                $height = $dimensions['y'];
                $resizedPath = str_replace(
                    '.'.pathinfo($path, PATHINFO_EXTENSION),
                    "_{$width}X{$height}.".pathinfo($path, PATHINFO_EXTENSION),
                    $fullPath
                );

                if (Storage::exists($resizedPath)) {
                    Storage::delete($resizedPath);
                }
            }
        }

        if (Storage::exists($fullPath)) {
            Storage::delete($fullPath);
        }
    }
}
