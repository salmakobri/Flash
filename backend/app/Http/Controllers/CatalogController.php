<?php

namespace App\Http\Controllers;

use App\Services\CatalogImage;
use Illuminate\Http\JsonResponse;

class CatalogController extends Controller
{
    public function index(): JsonResponse
    {
        $img = fn ($f) => $f ? asset('images/catalog/'.$f) : null;

        $categories = collect(config('catalog.categories'))->map(function ($c) use ($img) {
            $storeId = $c['id'];
            $c['logo'] = $img($c['logo'] ?? null);
            $c['type'] = $c['type'] ?? 'menu';
            $c['options'] = collect($c['options'])->values()->map(function ($o, $oi) use ($img, $storeId) {
                $o['items'] = collect($o['items'])->values()->map(function ($i, $ii) use ($img, $oi, $storeId) {
                    $i += ['meal' => false, 'desc' => null];
                    $i['id'] = $oi.'-'.$ii;
                    $i['image'] = $img(CatalogImage::find($storeId, $i));
                    return $i;
                })->all();
                return $o;
            })->all();
            return $c;
        })->all();

        return response()->json($categories);
    }
}