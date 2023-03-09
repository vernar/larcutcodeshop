<?php

namespace App\Http\Controllers;

use App\Models\Product;
use Domain\Catalog\Models\Brand;
use Domain\Catalog\Models\Category;
use Illuminate\Contracts\Foundation\Application;
use Illuminate\Contracts\View\Factory;
use Illuminate\Contracts\View\View;
use Illuminate\Database\Eloquent\Builder;

class CatalogController extends Controller
{
    public function __invoke(?Category $category): Factory|View|Application
    {
        $brands = Brand::query()
            ->select(['id', 'title'])
            ->has('products')
            ->get();

        $categories = Category::query()
            ->select(['id', 'title', 'slug'])
            ->has('products')
            ->limit(10)
            ->get();

        /* scaute search
                $products = Product::search(request('s'))->query(function (Builder $query) use ($category) {
                    $query->select(['id', 'title', 'slug', 'price', 'thumbnail'])
                        ->when($category->exists, function (Builder $q) use ($category) {
                            $q->whereRelation(
                                'categories',
                                'categories.id',
                                '=',
                                $category->id
                            );
                        })
                        ->filtered()
                        ->sorted();
                })->paginate(6);
        */
        $products = Product::query()
            ->select(['id', 'title', 'slug', 'price', 'thumbnail'])
            ->when(request('s'), function (Builder $query) {
                $query->whereFullText(['title', 'text'], request('s'));
            })
            ->when($category->exists, function (Builder $q) use ($category) {
                $q->whereRelation(
                    'categories',
                    'categories.id',
                    '=',
                    $category->id
                );
            })
            ->filtered()
            ->sorted()
            ->paginate(6);

        return view('catalog.index', [
            'products'   => $products,
            'categories' => $categories,
            'brands'     => $brands,
            'category'   => $category,
        ]);
    }
}