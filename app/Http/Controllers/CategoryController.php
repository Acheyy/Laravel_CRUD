<?php

namespace App\Http\Controllers;

// use Illuminate\Support\Facades\Category;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\Category;
use DB;


class CategoryController extends Controller
{
    public static $IS_MAIN_CATEGORY = 0;

    public function index()
    {
        $categories = DB::table('categories')->paginate(10);
        $operationState = 'add';
        return view('home', compact('categories', 'operationState'));
    }

    public function store(Request $request, $id = null)
    {

        $category = Category::updateOrCreate(
            ['id' => $id],
            [
                'name' => $request->name,
                'parent_id' => $request->parent_id ? $request->parent_id : self::$IS_MAIN_CATEGORY
            ]

        );

        return response()->json([
            'success' => true,
            'category' => $category
        ]);
    }

    public function edit($id)
    {


        $category = Category::find($id);
        $operationState = 'update';

        return response()->json([
            'success' => true,
            'operationState' => $operationState,
            'category' => $category
        ]);
    }

    public function destroyAll()
    {
        Category::truncate();

        return response()->json([
            'success' => true
        ]);
    }

    public function destroy($id)
    {
        $category = Category::findOrFail($id);
        $category->delete();

        Category::where('parent_id', $id)->delete();

        return response()->json([
            'success' => true
        ]);
    }
}
