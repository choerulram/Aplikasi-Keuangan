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
        $search = $this->request->getGet('search');

        if ($role === 'admin') {
            $builder = $categoryModel
                ->select('categories.*, users.username')
                ->join('users', 'users.id = categories.user_id', 'left')
                ->where('categories.tipe', 'income');
        } else {
            $builder = $categoryModel->where('user_id', $userId)->where('tipe', 'income');
        }

        // Search by nama_kategori
        if (!empty($search)) {
            $builder = $builder->like('nama_kategori', $search);
        }

        $builder = $builder->orderBy('created_at', 'DESC');
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
            'role' => $role,
            'search' => $search
        ]);
    }

    public function addIncome()
    {
        if ($this->request->getMethod() !== 'POST') {
            return redirect()->to('/categories/income');
        }

        $categoryModel = new \App\Models\CategoryModel();

        $data = [
            'nama_kategori' => $this->request->getPost('nama_kategori'),
            'tipe' => 'income',
            'user_id' => session('user_id'),
            'created_at' => date('Y-m-d H:i:s')
        ];

        // Validasi sederhana
        if (empty($data['nama_kategori'])) {
            return redirect()->to('/categories/income')->with('error', 'Nama kategori wajib diisi.');
        }

        $categoryModel->insert($data);

        return redirect()->to('/categories/income')->with('success', 'Kategori pemasukan berhasil ditambahkan!');
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
