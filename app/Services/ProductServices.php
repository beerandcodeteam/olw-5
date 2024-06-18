<?php

namespace App\Services;

use App\Http\Requests\CategoryStoreRequest;
use App\Http\Requests\CategoryUpdateRequest;
use App\Http\Requests\ProductStoreRequest;
use App\Http\Requests\ProductUpdateRequest;
use App\Models\Category;
use App\Models\Product;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class ProductServices
{

    public function list()
    {
        $products = Product::paginate();

        return $products;
    }

    public function store(ProductStoreRequest $request) {
        $product = DB::transaction(function () use ($request) {

            $product_data = $request->except("sku");
            $product_data['slug'] = Str::slug($product_data['name']);

            $product = Product::create($product_data);

            $skus = $product->skus()->createMany($request->get('sku'));

            foreach ($skus as $key => $sku) {
                foreach ($request->sku[$key]['images'] as $index => $image) {
                    $path = $image['url']->store('products');

                    $sku->images()->create([
                        'url' => $path,
                        'cover' => $index == 0
                    ]);
                }
            }

            return $product->load('skus.images');
        });

        return $product;
    }

    public function update(ProductUpdateRequest $request, Product $product) {
        $product = DB::transaction(function () use ($request, $product) {

            $product_data = $request->except("sku");

            $product_data['slug'] = Str::slug($product_data['name']);

            $product->update($product_data);

            foreach ($request->sku as $key => $sku) {

                $sku = $product->skus()->updateOrCreate(['id' => $sku['id'] ?? 0], $sku);

                if (isset($request->sku[$key]['images'])) {
                    foreach ($request->sku[$key]['images'] as $index => $image) {

                        $path = $image["url"]->store('products', 's3');

                        $sku->images()->create([
                            'url' => $path,
                            'featured' => $index == 0
                        ]);
                    }
                }
            }

            return $product->load('skus.images');
        });

        return $product;
    }

    public function destroy(Product $product)
    {
        DB::transaction(function () use ($product) {
            $product = $product->load('skus.images');

            foreach ($product->skus as $sku) {
                foreach($sku->images as $image){
                    $image->delete();
                }

                $sku->delete();
            }

            $product->delete();

        });
    }

}
