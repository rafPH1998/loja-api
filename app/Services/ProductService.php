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

    public function getProduct(int $id)
    {
        $product = Product::with(['images', 'category'])->findOrFail($id);

        if ($product) {
            $product->increment('views_count');
        }

        return $product;
    }

    public function relatedProducts(int $id)
    {
        $product = Product::select('id', 'category_id')->findOrFail($id);
    
        $relatedProducts = Product::with([
                'images' => fn($query) => $query->limit(1),
                'category'
            ])
            ->where('category_id', $product->category_id)
            ->where('id', '!=', $product->id)
            ->orderByDesc('views_count')
            ->get();
    
        return $relatedProducts;
    }
    
    
}