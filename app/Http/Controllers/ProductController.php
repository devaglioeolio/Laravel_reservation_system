<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Inertia\Inertia;

class ProductController extends Controller
{
     public function index()
    {
        // 일단은 더미 데이터 (나중에 DB 붙일거임)
        $products = [
            ['id' => 1, 'name' => '골프 18홀', 'price' => 150000],
            ['id' => 2, 'name' => '숙박 1박', 'price' => 90000],
            ['id' => 3, 'name' => '스키 리프트', 'price' => 60000],
        ];

        return Inertia::render('Products/Index', [
            'products' => $products
        ]);
    }
}
