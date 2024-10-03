<?php
namespace App\Http\Controllers\Books;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreCategoryRequest;
use App\Http\Requests\UpdateCategoryRequest;
use App\Http\Resources\CategoryResource;
use App\Models\Category;
use App\Traits\HasPermissions;

class CategoryController extends Controller
{
    use HasPermissions;

    /*
    |-------------------------------------------------------------------------- 
    | Constructor Method
    |-------------------------------------------------------------------------- 
    */
    public function __construct()
    {
        // $this->authorizePermissions('category');
    }

    /*
    |-------------------------------------------------------------------------- 
    | Display a listing of the categories.
    |-------------------------------------------------------------------------- 
    */
    public function index()
    {
        $categories = Category::all();
        return CategoryResource::collection($categories);
    }

    /*
    |-------------------------------------------------------------------------- 
    | Store a newly created category in storage.
    |-------------------------------------------------------------------------- 
    */
    public function store(StoreCategoryRequest $request)
    {
        $category = Category::create($request->validated());
        return new CategoryResource($category);
    }

    /*
    |-------------------------------------------------------------------------- 
    | Display the specified category.
    |-------------------------------------------------------------------------- 
    */
    public function show(Category $category)
    {
        return new CategoryResource($category);
    }

    /*
    |-------------------------------------------------------------------------- 
    | Update the specified category in storage.
    |-------------------------------------------------------------------------- 
    */
    public function update(UpdateCategoryRequest $request, Category $category)
    {
        $category->update($request->validated());
        return new CategoryResource($category);
    }

    /*
    |-------------------------------------------------------------------------- 
    | Remove the specified category from storage.
    |-------------------------------------------------------------------------- 
    */
    public function destroy(Category $category)
    {
        $category->delete();
        return response()->json(null, 204);
    }
}
