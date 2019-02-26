<?php

namespace App\Http\Controllers;

use App\Category;

class CategoryController extends Controller
{
    protected function getModelClass()
    {
        return Category::class;
    }

    protected function getValidatorRules()
    {
        return [
            'russian_title' => 'string|required',
        ];
    }

    public function index()
    {
        $route = app()->getCurrentRoute();
        $categories = Category::query()->paginate(50);

        return view('category/index', [
            'categories' => $categories,
        ]);
    }
}
