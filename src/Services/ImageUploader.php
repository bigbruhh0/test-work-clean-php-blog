<?php

declare(strict_types=1);

namespace App\Services;

use RuntimeException;

class ImageUploader
{
    private const EXTENSIONS = [
        'image/jpeg' => 'jpg',
        'image/png' => 'png',
        'image/webp' => 'webp',
        'image/gif' => 'gif',
    ];

    public function upload(?array $file, string $name): ?string
    {
        if ($file === null || ($file['error'] ?? UPLOAD_ERR_NO_FILE) === UPLOAD_ERR_NO_FILE) {
            return null;
        }

        if (($file['error'] ?? UPLOAD_ERR_OK) !== UPLOAD_ERR_OK) {
            throw new RuntimeException('Image upload failed.');
        }

        $mime = mime_content_type((string) $file['tmp_name']);

        if (!isset(self::EXTENSIONS[$mime])) {
            throw new RuntimeException('Unsupported image type.');
        }

        $directory = storage_path('images' . DIRECTORY_SEPARATOR . 'posts');

        if (!is_dir($directory)) {
            mkdir($directory, 0775, true);
        }

        $filename = slugify($name) . '-' . date('YmdHis') . '.' . self::EXTENSIONS[$mime];
        $target = $directory . DIRECTORY_SEPARATOR . $filename;

        if (!move_uploaded_file((string) $file['tmp_name'], $target)) {
            throw new RuntimeException('Image upload failed.');
        }

        return 'posts/' . $filename;
    }
}
