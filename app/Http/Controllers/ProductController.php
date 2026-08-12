<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\OrderByEnum;
use App\Services\ProductService;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class ProductController extends Controller
{
    public function __construct(protected ProductService $productService)
    {
    }

    public function index(Request $request)
    {
        $validated = $request->validate([
            'orderBy' => ['nullable', Rule::in(OrderByEnum::values())],
            'limit' => ['nullable', 'integer', 'min:1', 'max:200'],
            'metadata' => ['nullable'],
            'category' => ['nullable', 'string'],
            'search' => ['nullable', 'string', 'max:255'],
        ]);

        $products = $this->productService->getAll($validated);

        return response()->json([
            'error' => null,
            'products' => $products,
        ]);
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'label' => ['required', 'string', 'max:255'],
            'price' => ['required', 'numeric', 'min:0'],
            'description' => ['nullable', 'string'],
            'category_id' => ['required', 'exists:categories,id'],
            'stock' => ['nullable', 'integer', 'min:0'],
            'liked' => ['sometimes', 'boolean'],
            'images' => ['nullable', 'array'],
            'images.*' => ['string', 'max:2048'],
        ]);

        $product = $this->productService->create($data);

        return response()->json([
            'error' => null,
            'product' => $product,
        ], 201);
    }

    public function show(int $id)
    {
        return response()->json([
            'error' => null,
            'product' => $this->productService->getProduct($id),
            'related' => $this->productService->relatedProducts($id),
        ]);
    }

    public function getRelatedProducts(int $id)
    {
        return response()->json([
            'error' => null,
            'related' => $this->productService->relatedProducts($id),
        ]);
    }

    public function update(Request $request, Product $product)
    {
        $data = $request->validate([
            'label' => ['sometimes', 'required', 'string', 'max:255'],
            'price' => ['sometimes', 'required', 'numeric', 'min:0'],
            'description' => ['nullable', 'string'],
            'category_id' => ['sometimes', 'required', 'exists:categories,id'],
            'stock' => ['nullable', 'integer', 'min:0'],
            'liked' => ['sometimes', 'boolean'],
            'images' => ['nullable', 'array'],
            'images.*' => ['string', 'max:2048'],
        ]);

        $product = $this->productService->update($product, $data);

        return response()->json([
            'error' => null,
            'product' => $product,
        ]);
    }

    public function destroy(Product $product)
    {
        $this->productService->delete($product);

        return response()->json([
            'error' => null,
            'message' => 'Produto removido com sucesso.',
        ]);
    }
}
