<?php

namespace App\Controllers;

use App\Controllers\BaseController;

class CategoriesController extends BaseController
{
    public function index()
    {
        return view('Categories/index', [
            'pageTitle' => 'Kategori',
            'title' => 'Kategori | Aplikasi Keuangan'
        ]);
    }
}
