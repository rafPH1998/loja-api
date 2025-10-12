<?php

namespace App\Http\Controllers;

use App\OrderByEnum;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use App\Services\ProductService;

class ProductController extends Controller
{
    public function __construct(protected ProductService $productService)
    { }

    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $validated = $request->validate([
            'orderBy' => ['nullable', Rule::in(OrderByEnum::values())],
            'limit' => ['nullable', 'integer'],
            'metadata' => ['nullable', 'string'],
        ]);

        $filters = [
            'metadata' => $validated['metadata'] ?? null,
            'orderBy' => $validated['orderBy'] ?? null,
            'limit' => $validated['limit'] ?? null,
        ];

        $products = $this->productService->getAll($filters);

        return response()->json([
            'error' => null,
            'products' => $products
        ]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }
}
