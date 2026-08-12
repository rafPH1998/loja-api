<?php

namespace Database\Seeders;

use App\Models\Banner;
use Illuminate\Database\Seeder;

class BannerSeeder extends Seeder
{
    public function run(): void
    {
        $banners = [
            ['img' => '/assets/banners/banner-1.png', 'link' => '/categorias/camisas'],
            ['img' => '/assets/banners/banner-2.png', 'link' => '/categorias/kits'],
            ['img' => '/assets/banners/banner-3.png', 'link' => '/categorias/camisas'],
            ['img' => '/assets/banners/banner-4.png', 'link' => '/categorias/kits'],
        ];

        foreach ($banners as $banner) {
            Banner::query()->updateOrCreate(
                ['img' => $banner['img']],
                ['link' => $banner['link']]
            );
        }
    }
}
