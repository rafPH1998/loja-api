<?php

namespace App\Http\Controllers;

use App\Models\Banner;
use App\Services\BannerService;
use Illuminate\Http\Request;

class BannerController extends Controller
{
    public function __construct(protected BannerService $bannerService)
    {
    }

    public function index()
    {
        return response()->json([
            'error' => null,
            'banners' => $this->bannerService->getAll(),
        ]);
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'img' => ['required', 'string', 'max:2048'],
            'link' => ['nullable', 'string', 'max:2048'],
        ]);

        $banner = $this->bannerService->create([
            'img' => $data['img'],
            'link' => $data['link'] ?? '/',
        ]);

        return response()->json([
            'error' => null,
            'banner' => $banner,
        ], 201);
    }

    public function show(Banner $banner)
    {
        return response()->json([
            'error' => null,
            'banner' => $banner,
        ]);
    }

    public function update(Request $request, Banner $banner)
    {
        $data = $request->validate([
            'img' => ['sometimes', 'required', 'string', 'max:2048'],
            'link' => ['nullable', 'string', 'max:2048'],
        ]);

        $banner = $this->bannerService->update($banner, $data);

        return response()->json([
            'error' => null,
            'banner' => $banner,
        ]);
    }

    public function destroy(Banner $banner)
    {
        $this->bannerService->delete($banner);

        return response()->json([
            'error' => null,
            'message' => 'Banner removido com sucesso.',
        ]);
    }
}
