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
