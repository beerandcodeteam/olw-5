<?php

namespace App\Http\Controllers;

use App\Models\Brand;
use App\Models\Category;
use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;

class FrontController extends Controller
{
    public function buildMenu()
    {
        $brands = Brand::all();
        $categories = Category::with('products')->get();

        return response(['brands' => $brands, 'categories' => $categories]);
    }

    public function home() {
        [$brands, $categories, $products] = Cache::tags(['product_related'])->rememberForever('home-cache', function () {
            $brands = Brand::where('is_featured', 1)
                ->select('id', 'name')
                ->get();
            $categories = Category::where('is_featured', 1)
                ->select('id', 'name')
                ->get();
            $products = Product::with(['skus:id,name,price,product_id', 'skus.images:id,url,sku_id'])
                ->where('is_featured', 1)
                ->select('id', 'name', 'slug')
                ->get();

            return [$brands, $categories, $products];
        });


        return response([
            'brands' => $brands,
            'categories' => $categories,
            'products' => $products
        ]);
    }

    public function productAssessories() {
        $brands = Brand::all();
        $categories = Category::with('products')->get();

        return response(['brands' => $brands, 'categories' => $categories]);
    }

    public function products(Request $request) {
        $key = "product-request-{$request->page}-{$request->category_id}-{$request->brand_id}{$request->value_type}-{$request->price}";
        $products = Cache::tags(['product_related'])->rememberForever($key, function() use ($request) {
            $products = Product::with('skus.images');

            if ($request->filled('category_id')) {
                $products->where('category_id', $request->get('category_id'));
            }

            if ($request->filled('brand_id')) {
                $products->where('brand_id', $request->get('brand_id'));
            }

            if ($request->filled('value_type') && $request->filled('price')) {
                $products->whereHas('skus', function ($query) use ($request) {
                    $query->where('price', $request->value_type, $request->get('price'));
                });
            }

            $products = $products->paginate(12);

            return $products;
        });


        return response()->json($products);
    }

    public function product(Product $product) {
        $product = $product->load('skus.images');

        return response()->json($product);
    }

    public function static()
    {
        return "ok";
    }
}
