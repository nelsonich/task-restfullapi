<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Models\ProductCategory;
use Illuminate\Http\Request;

class ProductController extends Controller
{
    public function create(Request $request)
    {
        $request->validate([
            'name' => 'required',
        ]);

        $name = $request->post('name');
        $price = $request->post('price');
        $categories = $request->post('categories');

        $product = new Product();
        $product->name = $name;
        $product->price = $price;
        $product->save();

        if ($categories) {
            foreach (json_decode($categories) as $category) {
                ProductCategory::create([
                    'product_id' => $product->id,
                    'category_id' => $category,
                ]);
            }
        }

        return response()->json([
            'success' => 'ok',
        ], 201);
    }

    public function edit(Request $request, $id)
    {
        $request->validate([
            'name' => 'required',
        ]);

        $name = $request->post('name');
        $price = $request->post('price');
        $isPublished = $request->post('is_published');
        $categories = $request->post('categories');

        $product = Product::find($id);
        $product->name = $name;
        $product->price = $price;
        $product->is_published = filter_var($isPublished, FILTER_VALIDATE_BOOLEAN);
        $product->save();

        if ($categories) {
            ProductCategory::where('product_id', $id)->delete();

            foreach (json_decode($categories) as $category) {
                ProductCategory::create([
                    'product_id' => $product->id,
                    'category_id' => $category,
                ]);
            }
        }

        return response()->json([
            'success' => 'ok',
        ], 200);
    }

    public function filter(Request $request)
    {
        $q = $request->post('q');
        $minPrice = $request->post('min_price');
        $maxPrice = $request->post('max_price');
        $isPublished = $request->post('is_published');

        $products = Product::query()
            ->where('name', 'LIKE', "%{$q}%")
            ->where('is_published', filter_var($isPublished, FILTER_VALIDATE_BOOLEAN))
            ->whereBetween('price', [$minPrice, $maxPrice])
            ->with(['categories' => function ($query) use ($q) {
                $query->where('name', 'LIKE', "%{$q}%");
            }])
            ->get();

        return response()->json([
            'success' => 'ok',
            'data' => $products
        ]);
    }

    public function remove($id)
    {
        Product::destroy($id);

        return response()->json([
            'success' => 'ok',
        ]);
    }
}
