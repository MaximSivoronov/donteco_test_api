<?php

namespace App\Http\Controllers\v1;

use App\Http\Controllers\Controller;
use App\Http\Requests\v1\category\storeRequest;
use App\Http\Requests\v1\category\updateRequest;
use App\Models\category;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class categoryController extends Controller
{
    public function getCategories()
    {
        $categories = Category::all();

        foreach ($categories as $category) {
            $category['products'] = $category->products;
        }

        return response()->json($categories, 200);
    }

    public function getCategory(category $category)
    {
        $category['products'] = $category->products;

        return response()->json($category, 200);
    }

    public function storeCategory(storeRequest $storeRequest)
    {
        $validatedData = $storeRequest->validated();

        $newCategory = category::create($validatedData);

        return response()->json($newCategory, 201);
    }

    public function updateCategory(category $category, updateRequest $updateRequest)
    {
        $validatedData = $updateRequest->validated();

        $category->update($validatedData);

        return response()->json($category, 200);
    }

    public function deleteCategory(category $category)
    {
        // Delete primary keys in category_product table.
        DB::table('category_product')->where('category_id', $category->id)->delete();

        $category->delete();

        return response()->json([], 204);
    }
}
