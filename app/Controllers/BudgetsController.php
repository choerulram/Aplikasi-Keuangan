<?php

namespace App\Controllers;

class BudgetsController extends BaseController
{
    protected $budgetModel;
    protected $categoryModel;

    public function __construct()
    {
        $this->budgetModel = new \App\Models\BudgetModel();
        $this->categoryModel = new \App\Models\CategoryModel();
    }

    public function edit()
    {
        if (!$this->request->isAJAX()) {
            return redirect()->back();
        }

        // Validasi session
        if (!session('user_id')) {
            return $this->response->setJSON([
                'status' => false,
                'message' => 'Sesi Anda telah berakhir. Silakan login kembali.'
            ]);
        }

        $id = $this->request->getPost('id');
        $userId = session('user_id');
        $categoryId = $this->request->getPost('category_id');
        $periode = $this->request->getPost('periode');
        $jumlahAnggaran = $this->request->getPost('jumlah_anggaran');

        // Validasi input
        if (!$id || !$categoryId || !$periode || !$jumlahAnggaran) {
            return $this->response->setJSON([
                'status' => false,
                'message' => 'Semua field harus diisi!'
            ]);
        }

        // Cek apakah data yang akan diedit ada
        $existingBudget = $this->budgetModel->find($id);
        if (!$existingBudget) {
            // Dapatkan tipe kategori dari budget yang ada
            $category = $this->categoryModel->find($existingBudget['category_id']);
            $tipeBudget = $category['tipe'] === 'income' ? 'Target Pendapatan' : 'Batas Pengeluaran';
            return $this->response->setJSON([
                'status' => false,
                'message' => 'Data ' . strtolower($tipeBudget) . ' tidak ditemukan!'
            ]);
        }

        // Cek kepemilikan data kecuali untuk admin
        $isAdmin = session('role') === 'admin';
        if (!$isAdmin && $existingBudget['user_id'] != $userId) {
            return $this->response->setJSON([
                'status' => false,
                'message' => 'Anda tidak memiliki akses untuk mengubah data ini!'
            ]);
        }

        // Dapatkan tipe kategori (income/expense)
        $category = $this->categoryModel->find($categoryId);
        if (!$category) {
            return $this->response->setJSON([
                'status' => false,
                'message' => 'Kategori tidak ditemukan!'
            ]);
        }

        // Cek duplikasi kategori dan periode (kecuali untuk data yang sedang diedit)
        $duplicateBudget = $this->budgetModel->where([
            'user_id' => $userId,
            'category_id' => $categoryId,
            'periode' => $periode
        ])->where('id !=', $id)->first();

        if ($duplicateBudget) {
            $tipeBudget = $category['tipe'] === 'income' ? 'Target Pendapatan' : 'Batas Pengeluaran';
            return $this->response->setJSON([
                'status' => false,
                'message' => $tipeBudget . ' untuk kategori ini pada periode yang sama sudah ada!'
            ]);
        }

        // Update data anggaran
        $data = [
            'category_id' => $categoryId,
            'periode' => $periode,
            'jumlah_anggaran' => $jumlahAnggaran
        ];

        try {
            $result = $this->budgetModel->update($id, $data);
            if ($result) {
                $tipeBudget = $category['tipe'] === 'income' ? 'Target Pendapatan' : 'Batas Pengeluaran';
                return $this->response->setJSON([
                    'status' => true,
                    'message' => $tipeBudget . ' berhasil diperbarui!'
                ]);
            } else {
                $tipeBudget = $category['tipe'] === 'income' ? 'Target Pendapatan' : 'Batas Pengeluaran';
                return $this->response->setJSON([
                    'status' => false,
                    'message' => 'Gagal memperbarui ' . strtolower($tipeBudget) . '!'
                ]);
            }
        } catch (\Exception $e) {
            return $this->response->setJSON([
                'status' => false,
                'message' => 'Terjadi kesalahan: ' . $e->getMessage()
            ]);
        }
    }

    public function add()
    {
        if (!$this->request->isAJAX()) {
            return redirect()->back();
        }

        // Validasi session
        if (!session('user_id')) {
            return $this->response->setJSON([
                'status' => false,
                'message' => 'Sesi Anda telah berakhir. Silakan login kembali.'
            ]);
        }

        $userId = session('user_id');
        $categoryId = $this->request->getPost('category_id');
        $periode = $this->request->getPost('periode');
        $jumlahAnggaran = $this->request->getPost('jumlah_anggaran');

        // Validasi input
        if (!$categoryId || !$periode || !$jumlahAnggaran) {
            return $this->response->setJSON([
                'status' => false,
                'message' => 'Semua field harus diisi!'
            ]);
        }

        // Dapatkan tipe kategori (income/expense)
        $category = $this->categoryModel->find($categoryId);
        if (!$category) {
            return $this->response->setJSON([
                'status' => false,
                'message' => 'Kategori tidak ditemukan!'
            ]);
        }

        // Cek apakah sudah ada anggaran untuk kategori dan periode yang sama
        $existingBudget = $this->budgetModel->where([
            'user_id' => $userId,
            'category_id' => $categoryId,
            'periode' => $periode
        ])->first();

        if ($existingBudget) {
            $tipeBudget = $category['tipe'] === 'income' ? 'Target Pendapatan' : 'Batas Pengeluaran';
            return $this->response->setJSON([
                'status' => false,
                'message' => $tipeBudget . ' untuk kategori ini pada periode yang sama sudah ada!'
            ]);
        }

        // Simpan data anggaran
        $data = [
            'user_id' => $userId,
            'category_id' => $categoryId,
            'periode' => $periode,
            'jumlah_anggaran' => $jumlahAnggaran
        ];

        try {
            if ($this->budgetModel->insert($data)) {
                $tipeBudget = $category['tipe'] === 'income' ? 'Target Pendapatan' : 'Batas Pengeluaran';
                return $this->response->setJSON([
                    'status' => true,
                    'message' => $tipeBudget . ' berhasil ditambahkan!'
                ]);
            } else {
                $tipeBudget = $category['tipe'] === 'income' ? 'Target Pendapatan' : 'Batas Pengeluaran';
                return $this->response->setJSON([
                    'status' => false,
                    'message' => 'Gagal menambahkan ' . strtolower($tipeBudget) . '!'
                ]);
            }
        } catch (\Exception $e) {
            return $this->response->setJSON([
                'status' => false,
                'message' => 'Terjadi kesalahan: ' . $e->getMessage()
            ]);
        }
    }

    public function income()
    {
        $userId = session('user_id');
        $isAdmin = session('role') === 'admin';
        
        // Konfigurasi pagination
        $perPage = 10;
        $group = 'budgets_income';
        
        // Ambil data anggaran pendapatan
        $budgets = $this->budgetModel->getBudgetsByType($userId, 'income', $isAdmin, $perPage, $group);
        $total_budgets = $this->budgetModel->getTotalBudgetsByType($userId, 'income', $isAdmin);

        // Ambil kategori pendapatan saja
        $categories = $isAdmin 
            ? $this->categoryModel->where('tipe', 'income')->findAll()
            : $this->categoryModel->where(['user_id' => $userId, 'tipe' => 'income'])->findAll();

        $data = [
            'title' => 'Aplikasi Keuangan | Perencanaan Pendapatan',
            'budgets' => $budgets,
            'categories' => $categories,
            'budgetModel' => $this->budgetModel,
            'isAdmin' => $isAdmin,
            'pager' => $this->budgetModel->pager,
            'perPage' => $perPage,
            'total_budgets' => $total_budgets
        ];

        return view('Budgets/income', $data);
    }

    public function expense()
    {
        $userId = session('user_id');
        $isAdmin = session('role') === 'admin';
        
        // Konfigurasi pagination
        $perPage = 10;
        $group = 'budgets_expense';
        
        // Ambil data anggaran pengeluaran
        $budgets = $this->budgetModel->getBudgetsByType($userId, 'expense', $isAdmin, $perPage, $group);
        $total_budgets = $this->budgetModel->getTotalBudgetsByType($userId, 'expense', $isAdmin);

        // Ambil kategori pengeluaran saja
        $categories = $isAdmin 
            ? $this->categoryModel->where('tipe', 'expense')->findAll()
            : $this->categoryModel->where(['user_id' => $userId, 'tipe' => 'expense'])->findAll();

        $data = [
            'title' => 'Aplikasi Keuangan | Pelacakan Pengeluaran',
            'budgets' => $budgets,
            'categories' => $categories,
            'budgetModel' => $this->budgetModel,
            'isAdmin' => $isAdmin,
            'pager' => $this->budgetModel->pager,
            'perPage' => $perPage,
            'total_budgets' => $total_budgets
        ];

        return view('Budgets/expense', $data);
    }

    public function delete($id = null)
    {
        if (!$this->request->isAJAX()) {
            return redirect()->back();
        }

        // Validasi session
        if (!session('user_id')) {
            return $this->response->setJSON([
                'status' => false,
                'message' => 'Sesi Anda telah berakhir. Silakan login kembali.'
            ]);
        }

        $userId = session('user_id');
        $isAdmin = session('role') === 'admin';

        // Cek apakah data yang akan dihapus ada
        $budget = $this->budgetModel->find($id);
        if (!$budget) {
            return $this->response->setJSON([
                'status' => false,
                'message' => 'Data anggaran tidak ditemukan.'
            ]);
        }

        // Dapatkan tipe kategori untuk pesan
        $category = $this->categoryModel->find($budget['category_id']);
        $tipeBudget = $category['tipe'] === 'income' ? 'Target Pendapatan' : 'Batas Pengeluaran';

        // Cek kepemilikan data kecuali untuk admin
        if (!$isAdmin && $budget['user_id'] != $userId) {
            return $this->response->setJSON([
                'status' => false,
                'message' => 'Anda tidak memiliki akses untuk menghapus data ini.'
            ]);
        }

        try {
            // Hapus data anggaran
            if ($this->budgetModel->delete($id)) {
                session()->setFlashdata('success', $tipeBudget . ' berhasil dihapus.');
                return $this->response->setJSON([
                    'status' => true,
                    'message' => $tipeBudget . ' berhasil dihapus.',
                    'redirect' => $category['tipe'] === 'income' ? '/budgets/income' : '/budgets/expense'
                ]);
            } else {
                return $this->response->setJSON([
                    'status' => false,
                    'message' => 'Gagal menghapus ' . strtolower($tipeBudget) . '.'
                ]);
            }
        } catch (\Exception $e) {
            log_message('error', 'Error deleting budget: ' . $e->getMessage());
            return $this->response->setJSON([
                'status' => false,
                'message' => 'Terjadi kesalahan saat menghapus data anggaran.'
            ]);
        }
        if (!$isAdmin && $budget['user_id'] != $userId) {
            return $this->response->setJSON([
                'status' => false,
                'message' => 'Anda tidak memiliki akses untuk menghapus data ini.'
            ]);
        }

        try {
            // Hapus data anggaran
            if ($this->budgetModel->delete($id)) {
                return $this->response->setJSON([
                    'status' => true,
                    'message' => $tipeBudget . ' berhasil dihapus.'
                ]);
            } else {
                return $this->response->setJSON([
                    'status' => false,
                    'message' => 'Gagal menghapus ' . strtolower($tipeBudget) . '.'
                ]);
            }
        } catch (\Exception $e) {
            log_message('error', 'Error deleting budget: ' . $e->getMessage());
            return $this->response->setJSON([
                'status' => false,
                'message' => 'Terjadi kesalahan saat menghapus data anggaran.'
            ]);
        }
    }
}
