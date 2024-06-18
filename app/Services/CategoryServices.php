<?php

namespace App\Services;

use App\Http\Requests\CategoryStoreRequest;
use App\Http\Requests\CategoryUpdateRequest;
use App\Models\Category;

class CategoryServices
{

    public function list()
    {
        $categories = Category::paginate();

        return $categories;
    }

    public function store(CategoryStoreRequest $request) {
        $category = Category::create($request->validated());

        return $category;
    }

    public function update(CategoryUpdateRequest $request, Category $category) {
        $category->update($request->validated());

        return $category;
    }

    public function destroy(Category $category)
    {
        $category->delete();
    }

}
