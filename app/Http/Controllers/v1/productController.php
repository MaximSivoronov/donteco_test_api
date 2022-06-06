<?php

namespace App\Http\Controllers\v1;

use App\Http\Controllers\Controller;
use App\Http\Requests\v1\product\storeRequest;
use App\Http\Requests\v1\product\updateRequest;
use App\Models\product;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class productController extends Controller
{
    public function getProducts()
    {
        $products = product::all();

        foreach ($products as $product) {
            $product['categories'] = $product->categories;
        }

        return response()->json($products, 200);
    }

    public function getProduct(product $product)
    {
        $product['categories'] = $product->categories;

        return response()->json($product, 200);
    }

    public function storeProduct(storeRequest $storeRequest)
    {
        $validatedData = $storeRequest->validated();
        $categoryId = $validatedData['category_id'];

        $category = json_decode(DB::table('categories')->where('id', $categoryId)->get(), true);

        // Check if we have such category.
        if ($category == null) {
            return response()->json(['message' => "Categories table haven't such category"], 422);
        }

        unset($validatedData['category_id']);

        $newProduct = product::create($validatedData);

        // Creating entry to category_product table.
        DB::table('category_product')->insert([
            'product_id' => $newProduct->id,
            'category_id' => $categoryId,
        ]);

        // Getting count of products in this category.
        $countOfProducts =
            json_decode(
                DB::table('categories')
                    ->where('id', '=', $categoryId)
                    ->get(),
                true)[0]['count_of_products'];

        // Updating count of products in this category.
        $countOfProducts++;
        DB::table('categories')->where('id', $categoryId)->update(['count_of_products' => $countOfProducts]);

        // Get all categories for this product.
        $newProduct['categories'] = $newProduct->categories;

        return response()->json($category, 201);
    }

    public function updateProduct(product $product, updateRequest $updateRequest)
    {
        $validatedData = $updateRequest->validated();

        $product->update($validatedData);

        return response()->json($product, 200);
    }

    public function deleteProduct(product $product)
    {
        // Inner join category_product with categories table.
        $result = DB::select(DB::raw(
            'select distinct cp.category_id, c.count_of_products from category_product as cp
        inner join categories as c
        on cp.category_id = c.id'));

        // Parse to json.
        $result = json_decode(json_encode($result), true);

        // Update count_of_products count in each category that our product has.
        foreach ($result as $category) {
            DB::table('categories')
                ->where('id', $category['category_id'])
                ->update(['count_of_products' => $category['count_of_products'] - 1]);
        }

        // Delete primary keys in category_product table.
        DB::table('category_product')->where('product_id', $product->id)->delete();

        $product->delete();

        return response()->json([], 204);
    }
}
