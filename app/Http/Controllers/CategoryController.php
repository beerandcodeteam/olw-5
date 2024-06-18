<?php

namespace App\Http\Controllers;

use App\Http\Requests\CategoryStoreRequest;
use App\Http\Requests\CategoryUpdateRequest;
use App\Models\Category;
use App\Services\CategoryServices;
use Illuminate\Support\Facades\Gate;

class CategoryController extends Controller
{
    public function __construct(protected CategoryServices $categoryServices)
    {}

    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        Gate::authorize('viewAny', Category::class);
        $categories = $this->categoryServices->list();

        return response()->json($categories);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(CategoryStoreRequest $request)
    {
        Gate::authorize('create', Category::class);
        $category = $this->categoryServices->store($request);

        return response()->json($category);
    }

    /**
     * Display the specified resource.
     */
    public function show(Category $category)
    {
        Gate::authorize('view', $category);
        return response()->json($category);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(CategoryUpdateRequest $request, Category $category)
    {
        Gate::authorize('update', $category);
        $category = $this->categoryServices->update($request, $category);

        return response()->json($category);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Category $category)
    {
        Gate::authorize('delete', $category);
        $this->categoryServices->destroy($category);

        return response()->json(["message" => "Category deleted."]);
    }
}
