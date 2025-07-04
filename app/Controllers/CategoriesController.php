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

    public function editIncome()
    {
        if ($this->request->getMethod() !== 'POST') {
            return redirect()->to('/categories/income');
        }

        $id = $this->request->getPost('id');
        if (!$id) {
            return redirect()->to('/categories/income')->with('error', 'ID kategori tidak ditemukan.');
        }

        $categoryModel = new \App\Models\CategoryModel();
        $kategori = $categoryModel->find($id);
        if (!$kategori) {
            return redirect()->to('/categories/income')->with('error', 'Data kategori tidak ditemukan.');
        }

        // Hanya admin atau pemilik kategori yang boleh edit
        $role = session('role');
        $userId = session('user_id');
        if ($role !== 'admin' && $kategori['user_id'] != $userId) {
            return redirect()->to('/categories/income')->with('error', 'Anda tidak berhak mengubah kategori ini.');
        }

        $data = [
            'nama_kategori' => $this->request->getPost('nama_kategori'),
            'updated_at' => date('Y-m-d H:i:s'),
        ];

        $categoryModel->update($id, $data);
        return redirect()->to('/categories/income')->with('success', 'Kategori pemasukan berhasil diubah!');
    }

    public function deleteIncome($id)
    {
        $categoryModel = new \App\Models\CategoryModel();
        $kategori = $categoryModel->find($id);
        if (!$kategori) {
            return redirect()->to('/categories/income')->with('error', 'Data kategori tidak ditemukan.');
        }
        $role = session('role');
        $userId = session('user_id');
        // Hanya admin atau pemilik kategori yang boleh hapus
        if ($role !== 'admin' && $kategori['user_id'] != $userId) {
            return redirect()->to('/categories/income')->with('error', 'Anda tidak berhak menghapus kategori ini.');
        }
        if ($categoryModel->delete($id)) {
            return redirect()->to('/categories/income')->with('success', 'Kategori pemasukan berhasil dihapus!');
        } else {
            return redirect()->to('/categories/income')->with('error', 'Gagal menghapus kategori.');
        }
    }

    public function expense()
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
                ->where('categories.tipe', 'expense');
        } else {
            $builder = $categoryModel->where('user_id', $userId)->where('tipe', 'expense');
        }

        // Search by nama_kategori
        if (!empty($search)) {
            $builder = $builder->like('nama_kategori', $search);
        }

        $builder = $builder->orderBy('created_at', 'DESC');
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
            'role' => $role,
            'search' => $search
        ]);
    }
}
