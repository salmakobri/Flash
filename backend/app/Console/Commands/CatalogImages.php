<?php

namespace App\Console\Commands;

use App\Services\CatalogImage;
use Illuminate\Console\Command;

class CatalogImages extends Command
{
    protected $signature = 'catalog:images';

    protected $description = 'Liste les photos du catalogue (trouvées et manquantes)';

    public function handle(): int
    {
        $rows = [];
        $found = 0;
        $total = 0;

        foreach (config('catalog.categories') as $store) {
            foreach ($store['options'] as $option) {
                foreach ($option['items'] as $item) {
                    $total++;
                    if (CatalogImage::find($store['id'], $item)) {
                        $found++;
                        continue;
                    }
                    $rows[] = [$store['name'], $item['name'], CatalogImage::expected($store['id'], $item).'.jpg'];
                }
            }
        }

        $this->info("Photos trouvées : {$found} / {$total}");

        if ($rows) {
            $this->table(['Magasin', 'Article', 'Fichier à créer dans public/images/catalog/'], $rows);
        }

        return self::SUCCESS;
    }
}