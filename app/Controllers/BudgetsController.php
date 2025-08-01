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

        // Cek apakah sudah ada anggaran untuk kategori dan periode yang sama
        $existingBudget = $this->budgetModel->where([
            'user_id' => $userId,
            'category_id' => $categoryId,
            'periode' => $periode
        ])->first();

        if ($existingBudget) {
            return $this->response->setJSON([
                'status' => false,
                'message' => 'Anggaran untuk kategori ini pada periode yang sama sudah ada!'
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
                return $this->response->setJSON([
                    'status' => true,
                    'message' => 'Anggaran berhasil ditambahkan!'
                ]);
            } else {
                return $this->response->setJSON([
                    'status' => false,
                    'message' => 'Gagal menambahkan anggaran!'
                ]);
            }
        } catch (\Exception $e) {
            return $this->response->setJSON([
                'status' => false,
                'message' => 'Terjadi kesalahan: ' . $e->getMessage()
            ]);
        }
    }

    public function index()
    {
        $userId = session('user_id');
        $isAdmin = session('role') === 'admin';
        $budgets = $this->budgetModel->getBudgetsByUser($userId, $isAdmin);

        $categories = $isAdmin 
            ? $this->categoryModel->findAll() 
            : $this->categoryModel->where('user_id', $userId)->findAll();

        $data = [
            'title' => 'Aplikasi Keuangan | Anggaran',
            'budgets' => $budgets,
            'categories' => $categories,
            'budgetModel' => $this->budgetModel,
            'isAdmin' => $isAdmin
        ];

        return view('Budgets/index', $data);
    }
}
