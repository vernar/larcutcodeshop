<?php

namespace Domain\Catalog\ViewModels;

use Domain\Catalog\Models\Category;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Support\Facades\Cache;
use Support\Traits\Makeable;

class CategoryViewModel
{
    use Makeable;

    const CACHE_KEY_CATEGORY_HOMEPAGE = 'cache-key-category-homepage';

    public function homepage(): array|Collection
    {
        return Cache::rememberForever(self::CACHE_KEY_CATEGORY_HOMEPAGE, function () {
            return Category::query()
                ->homepage()
                ->get();
        });
    }
}