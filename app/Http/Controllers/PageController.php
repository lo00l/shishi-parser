<?php

namespace App\Http\Controllers;

use App\Page;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Input;
use Laravel\Lumen\Routing\Controller as LumenController;

class PageController extends LumenController
{
    public function index(Request $request)
    {
        $categoryId = $request->query('category_id');
        $query = Page::query();
        if (!is_null($categoryId)) {
            $query->where('category_id', $categoryId);
        }
        $pages = $query->paginate(50);

        return view('page.index', [
            'pages' => $pages->appends(Input::except('page')),
        ]);
    }
}
