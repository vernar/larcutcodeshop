<?php

namespace App\Http\Controllers;

use App\Models\Product;
use Domain\Catalog\Models\Brand;
use Domain\Catalog\Models\Category;
use Illuminate\Contracts\Foundation\Application;
use Illuminate\Contracts\View\Factory;
use Illuminate\Contracts\View\View;

class HomeController extends Controller
{
    public function index()
    {
        return view('index');
    }

    public function __invoke(): Factory|View|Application
    {
        $categories = Category::query()
            ->homepage()
            ->get();
        $products   = Product::query()
            ->homepage()
            ->get();
        $brands     = Brand::query()
            ->homepage()
            ->get();

        return view('index', compact(
            'categories',
            'products',
            'brands',
        ));
    }
}
