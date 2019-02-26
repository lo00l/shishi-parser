<?php

namespace App\Http\Controllers;

use App\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Input;

class ProductController extends Controller
{
    protected function getModelClass()
    {
        return Product::class;
    }

    protected function getValidatorRules()
    {
        return [
            'russian_title' => 'string|required',
            'russian_help_html' => 'string',
        ];
    }

    public function index(Request $request)
    {
        $pageId = $request->query('page_id');
        $query = Product::query();
        if (!is_null($pageId)) {
            $query->where('page_id', $pageId);
        }
        $products = $query->paginate(2);

        return view('product.index', [
            'products' => $products->appends(Input::except('page')),
        ]);
    }
}
