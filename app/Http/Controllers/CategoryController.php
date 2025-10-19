<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\CategoryMetaData;
use Illuminate\Http\Request;

class CategoryController extends Controller
{
    /**
     * Handle the incoming request.
     */
    public function __invoke(string $slug)
    {
        return response()->json([
            'error' => null,
            'category' => Category::with('metadata')->where('slug', $slug)->firstOrFail()
        ]);
    }
}
