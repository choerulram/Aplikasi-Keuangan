<?php

namespace App\Controllers;

use App\Controllers\BaseController;

class CategoriesController extends BaseController
{
    public function income()
    {
        $categoryModel = new \App\Models\CategoryModel();
        $role = session('role');
        $userId = session('user_id');
        $perPage = 10;

        if ($role === 'admin') {
            $builder = $categoryModel
                ->select('categories.*, users.username')
                ->join('users', 'users.id = categories.user_id', 'left')
                ->where('categories.tipe', 'income');
        } else {
            $builder = $categoryModel->where('user_id', $userId)->where('tipe', 'income');
        }

        $categories = $builder->paginate($perPage, 'categories');
        $pager = $categoryModel->pager;
        $total = $builder->countAllResults(false);

        return view('Categories/income', [
            'pageTitle' => 'Kategori Pemasukan',
            'title' => 'Kategori Pemasukan | Aplikasi Keuangan',
            'categories' => $categories,
            'pager' => $pager,
            'total_categories' => $total,
            'perPage' => $perPage,
            'role' => $role
        ]);
    }

    public function expense()
    {
        $categoryModel = new \App\Models\CategoryModel();
        $role = session('role');
        $userId = session('user_id');
        $perPage = 10;

        if ($role === 'admin') {
            $builder = $categoryModel
                ->select('categories.*, users.username')
                ->join('users', 'users.id = categories.user_id', 'left')
                ->where('categories.tipe', 'expense');
        } else {
            $builder = $categoryModel->where('user_id', $userId)->where('tipe', 'expense');
        }

        $categories = $builder->paginate($perPage, 'categories');
        $pager = $categoryModel->pager;
        $total = $builder->countAllResults(false);

        return view('Categories/expense', [
            'pageTitle' => 'Kategori Pengeluaran',
            'title' => 'Kategori Pengeluaran | Aplikasi Keuangan',
            'categories' => $categories,
            'pager' => $pager,
            'total_categories' => $total,
            'perPage' => $perPage,
            'role' => $role
        ]);
    }
}
