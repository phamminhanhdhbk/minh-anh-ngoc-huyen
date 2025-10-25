<?php

namespace App\Services;

use Intervention\Image\Facades\Image;
use Illuminate\Support\Facades\Storage;

class ImageOptimizationService
{
    /**
     * Optimize and resize image
     *
     * @param string $imagePath Path to image in storage
     * @param int $maxWidth Maximum width
     * @param int $quality JPEG quality (0-100)
     * @return string Optimized image path
     */
    public function optimizeImage($imagePath, $maxWidth = 1200, $quality = 80)
    {
        $fullPath = storage_path('app/public/' . $imagePath);

        if (!file_exists($fullPath)) {
            return $imagePath;
        }

        try {
            $image = Image::make($fullPath);

            // Resize if image is larger than max width
            if ($image->width() > $maxWidth) {
                $image->resize($maxWidth, null, function ($constraint) {
                    $constraint->aspectRatio();
                    $constraint->upsize();
                });
            }

            // Save with compression
            $image->save($fullPath, $quality);

            return $imagePath;
        } catch (\Exception $e) {
            \Log::error('Image optimization failed: ' . $e->getMessage());
            return $imagePath;
        }
    }

    /**
     * Create multiple image sizes (thumbnail, medium, large)
     *
     * @param string $imagePath Original image path
     * @return array Paths to generated images
     */
    public function createResponsiveImages($imagePath)
    {
        $fullPath = storage_path('app/public/' . $imagePath);
        $pathInfo = pathinfo($imagePath);

        $sizes = [
            'thumbnail' => ['width' => 300, 'quality' => 80],
            'medium' => ['width' => 800, 'quality' => 85],
            'large' => ['width' => 1200, 'quality' => 90]
        ];

        $generatedImages = [];

        foreach ($sizes as $sizeName => $config) {
            try {
                $image = Image::make($fullPath);

                // Resize
                $image->resize($config['width'], null, function ($constraint) {
                    $constraint->aspectRatio();
                    $constraint->upsize();
                });

                // Generate new filename
                $newFilename = $pathInfo['filename'] . '_' . $sizeName . '.' . $pathInfo['extension'];
                $newPath = $pathInfo['dirname'] . '/' . $newFilename;
                $newFullPath = storage_path('app/public/' . $newPath);

                // Save
                $image->save($newFullPath, $config['quality']);

                $generatedImages[$sizeName] = $newPath;
            } catch (\Exception $e) {
                \Log::error("Failed to create $sizeName image: " . $e->getMessage());
            }
        }

        return $generatedImages;
    }

    /**
     * Optimize existing images in storage
     *
     * @param string $directory Directory to scan
     * @return int Number of optimized images
     */
    public function optimizeExistingImages($directory = 'products')
    {
        $files = Storage::disk('public')->files($directory);
        $count = 0;

        foreach ($files as $file) {
            if ($this->isImage($file)) {
                $this->optimizeImage($file);
                $count++;
            }
        }

        return $count;
    }

    /**
     * Check if file is an image
     *
     * @param string $path File path
     * @return bool
     */
    private function isImage($path)
    {
        $extension = strtolower(pathinfo($path, PATHINFO_EXTENSION));
        return in_array($extension, ['jpg', 'jpeg', 'png', 'gif', 'webp']);
    }

    /**
     * Convert image to WebP format
     *
     * @param string $imagePath Original image path
     * @return string|null WebP image path or null on failure
     */
    public function convertToWebP($imagePath)
    {
        $fullPath = storage_path('app/public/' . $imagePath);
        $pathInfo = pathinfo($imagePath);

        try {
            $image = Image::make($fullPath);

            // Generate WebP filename
            $webpFilename = $pathInfo['filename'] . '.webp';
            $webpPath = $pathInfo['dirname'] . '/' . $webpFilename;
            $webpFullPath = storage_path('app/public/' . $webpPath);

            // Save as WebP
            $image->encode('webp', 85)->save($webpFullPath);

            return $webpPath;
        } catch (\Exception $e) {
            \Log::error('WebP conversion failed: ' . $e->getMessage());
            return null;
        }
    }
}
