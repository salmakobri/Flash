<?php

namespace App\Services;

use Illuminate\Support\Str;

class CatalogImage
{
    private const EXTENSIONS = ['jpg', 'jpeg', 'png', 'webp'];

    /** Nom attendu, sans extension : "kfc/zinger-box" */
    public static function expected(string $storeId, array $item): string
    {
        return $storeId.'/'.Str::slug($item['name']);
    }

    /** Chemin relatif de la photo si elle existe dans public/images/catalog, sinon null. */
    public static function find(string $storeId, array $item): ?string
    {
        if (!empty($item['image']) && file_exists(public_path('images/catalog/'.$item['image']))) {
            return $item['image'];
        }

        $base = self::expected($storeId, $item);
        foreach (self::EXTENSIONS as $ext) {
            if (file_exists(public_path("images/catalog/{$base}.{$ext}"))) {
                return "{$base}.{$ext}";
            }
        }

        return null;
    }
}