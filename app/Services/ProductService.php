<?php

namespace App\Services;

use App\Models\Product;

class ProductService
{
    public function getAll(array $filters)
    {
        return Product::with(['images', 'category', 'metaData.metaValue'])
            ->when($filters['category'] ?? null, function ($query, $slug) {
                $query->whereHas('category', fn ($q) => $q->where('slug', $slug));
            })
            ->when($filters['search'] ?? null, function ($query, $search) {
                $query->where(function ($inner) use ($search) {
                    $inner->where('label', 'like', "%{$search}%")
                        ->orWhere('description', 'like', "%{$search}%");
                });
            })
            ->when($filters['metadata'] ?? null, function ($query, $metadata) {
                if (is_string($metadata)) {
                    $decoded = json_decode($metadata, true);
                    $metadata = is_array($decoded) ? $decoded : [];
                }

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
                    'views' => ['views_count', 'desc'],
                    'selling' => ['sales_count', 'desc'],
                    'price' => ['price', 'asc'],
                    'price_desc' => ['price', 'desc'],
                    'newest' => ['created_at', 'desc'],
                    default => [null, null],
                };

                if ($column) {
                    $query->orderBy($column, $direction);
                }
            })
            ->limit($filters['limit'] ?? 50)
            ->get();
    }

    public function getProduct(int $id)
    {
        $product = Product::with(['images', 'category'])->findOrFail($id);
        $product->increment('views_count');

        return $product->fresh(['images', 'category']);
    }

    public function relatedProducts(int $id)
    {
        $product = Product::select('id', 'category_id')->findOrFail($id);

        return Product::with([
            'images' => fn ($query) => $query->limit(1),
            'category',
        ])
            ->where('category_id', $product->category_id)
            ->where('id', '!=', $product->id)
            ->orderByDesc('views_count')
            ->limit(8)
            ->get();
    }

    public function create(array $data): Product
    {
        $images = $data['images'] ?? [];
        unset($data['images']);

        $product = Product::create($data);
        $this->syncImages($product, $images);

        return $product->load(['images', 'category']);
    }

    public function update(Product $product, array $data): Product
    {
        $images = $data['images'] ?? null;
        unset($data['images']);

        $product->update($data);

        if (is_array($images)) {
            $this->syncImages($product, $images);
        }

        return $product->fresh(['images', 'category']);
    }

    public function delete(Product $product): void
    {
        $product->delete();
    }

    private function syncImages(Product $product, array $images): void
    {
        $product->images()->delete();

        foreach (array_filter($images) as $url) {
            $product->images()->create(['url' => $url]);
        }
    }
}
