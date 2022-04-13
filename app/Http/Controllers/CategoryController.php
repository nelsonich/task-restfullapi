<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\ProductCategory;
use Illuminate\Http\Request;

class CategoryController extends Controller
{
    public function create(Request $request)
    {
        $request->validate([
            'name' => 'required',
        ]);

        $name = $request->post('name');

        $category = new Category();
        $category->name = $name;
        $category->save();

        return response()->json([
            'success' => 'ok',
        ], 201);
    }

    public function destroy($id)
    {
        $isExists = ProductCategory::where('category_id', $id)->exists();

        if ($isExists) {
            return response()->json([
                'message' => 'категория прикреплена к товару',
            ]);
        }

        Category::destroy($id);

        return response()->json([
            'success' => 'ok',
        ]);
    }
}
