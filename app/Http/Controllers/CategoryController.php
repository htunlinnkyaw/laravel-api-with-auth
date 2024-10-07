<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Http\Requests\StoreCategoryRequest;
use App\Http\Requests\UpdateCategoryRequest;
use Illuminate\Http\Request;

class CategoryController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function search(Request $request)
    {
        if ($request->name) {
            $categories = Category::orWhere('name', 'LIKE', "%{$request->name}%")->get();

            return response()->json([
                'message' => 'success',
                'data' => $categories
            ], 200);
        }

        return response()->json(['message' => 'Not Found Category'], 404);
    }

    public function index()
    {
        // first method to hide unnecessary columns
        // $categories = Category::all()->makeHidden(['created_at', 'updated_at']);

        // second method to hide unnecessary columns
        // $categories = Category::select('id', 'name', 'description')->get();

        // third method to hide unnecessary columns (to write in model)
        $categories = Category::all();

        if ($categories->isEmpty()) {
            return response()->json([
                'message' => 'Not Found Category',
            ], 404);
        }

        return response()->json([
            'message' => 'success',
            'data' => $categories,
        ], 200);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreCategoryRequest $request)
    {
        $category = new Category();
        $category->name = $request->name;
        $category->description = $request->description;
        $category->save();

        return response(['message' => 'Category created successfully'], 200);
        // return $request;
    }

    /**
     * Display the specified resource.
     */
    public function show(Category $category)
    {
        $category = Category::find($category->id)->makeHidden(['created_at', 'updated_at']);
        return response()->json(['message' => 'detail category fetching success', 'data' => $category], 200);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Category $category)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateCategoryRequest $request, Category $category)
    {
        $category->name = $request->name;
        $category->description = $request->description;
        $category->update();

        return response()->json(['message' => 'Category updated successfully'], 200);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Category $category)
    {
        if ($category) {

            $category->delete();
            return response()->json(['message' => 'Category deleted successfully'], 200);
        }
    }
}
