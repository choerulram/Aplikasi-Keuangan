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
        $userId = session('user_id'); // Changed from session('id') to session('user_id')
        $isAdmin = session('role') === 'admin';

        // Debug information
        echo "<div style='background: #f5f5f5; padding: 10px; margin: 10px; border: 1px solid #ddd;'>";
        echo "<h3>Debug Information:</h3>";
        echo "<pre>";
        echo "User ID: " . $userId . "\n";
        echo "Is Admin: " . ($isAdmin ? 'true' : 'false') . "\n";
        echo "Session Data: \n";
        print_r(session()->get());
        echo "</pre>";
        echo "</div>";

        $budgets = $this->budgetModel->getBudgetsByUser($userId, $isAdmin);
        
        // Debug query
        echo "<div style='background: #f5f5f5; padding: 10px; margin: 10px; border: 1px solid #ddd;'>";
        echo "<h3>Query Debug:</h3>";
        echo "<pre>";
        echo "Generated SQL: " . $this->budgetModel->getLastQuery() . "\n\n";
        echo "Budget Results Count: " . count($budgets) . "\n";
        echo "First Budget Result (if any): \n";
        if (!empty($budgets)) {
            print_r($budgets[0]);
        } else {
            echo "No budgets found\n";
        }
        echo "</pre>";
        echo "</div>";

        $categories = $isAdmin 
            ? $this->categoryModel->findAll() 
            : $this->categoryModel->where('user_id', $userId)->findAll();

        $data = [
            'title' => 'Anggaran',
            'budgets' => $budgets,
            'categories' => $categories,
            'budgetModel' => $this->budgetModel,
            'isAdmin' => $isAdmin
        ];

        return view('Budgets/index', $data);
    }
}
