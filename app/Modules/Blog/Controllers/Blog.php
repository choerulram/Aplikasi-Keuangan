<?php
namespace App\Modules\Blog\Controllers;
use App\Controllers\BaseController;

class Blog extends BaseController
{
    public function index()
    {
        return view('App\Modules\Blog\Views\index');
    }
}
