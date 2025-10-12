<?php

namespace App\Services;

use App\Models\Product;

class ProductService 
{
    public function getAll(array $filters)
    {
        $data = Product::with('images', 'metaData.metaValue')
            ->when($filters['metadata'] ?? null, function ($query, $metadata) {
                foreach ($metadata as $metaName => $metaValue) {
                    $query->whereHas('metaData.metaValue', function ($q) use ($metaName, $metaValue) {
                        $q->where('label', $metaValue)
                        ->whereHas('metaData', function ($q2) use ($metaName) {
                            $q2->where('name', $metaName);
                        });
                    });
                }
            })
            ->when($filters['orderBy'] ?? null, function ($query, $orderBy) {
                [$column, $direction] = match ($orderBy) {
                    'views'   => ['views_count', 'desc'],
                    'selling' => ['sales_count', 'desc'],
                    'price'   => ['price', 'asc'],
                    default   => [null, null],
                };
                if ($column) $query->orderBy($column, $direction);
            })
            ->limit($filters['limit'] ?? 10)
            ->get();

        return $data;
    }
    
}