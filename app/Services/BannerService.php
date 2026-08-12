<?php

namespace App\Services;

use App\Models\Banner;

class BannerService
{
    public function getAll()
    {
        return Banner::query()->latest()->get();
    }

    public function create(array $data): Banner
    {
        return Banner::create($data);
    }

    public function update(Banner $banner, array $data): Banner
    {
        $banner->update($data);

        return $banner->fresh();
    }

    public function delete(Banner $banner): void
    {
        $banner->delete();
    }
}
