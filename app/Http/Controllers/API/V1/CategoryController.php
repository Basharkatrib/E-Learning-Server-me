<?php

namespace App\Http\Controllers\API\V1;

use App\Http\Controllers\Controller;
use App\Http\Resources\CategoryCollection;
use App\Models\Category;
use Illuminate\Http\Request;

class CategoryController extends Controller
{
    // Get all the category
    public function index()
    {
        return new CategoryCollection(Category::all());
    }

    //Post (create a new category by admin or teacher for future use)
    public function store(Request $request)
    {
        $validData = $request->validate([
            "name" => ["required", "string", "unique:categories,name"],
        ]);

        $newCategory = Category::create($validData);

        return response()->json($newCategory, 201);
    }
}
