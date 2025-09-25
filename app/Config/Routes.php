<?php

use CodeIgniter\Router\RouteCollection;

/**
 * @var RouteCollection $routes
 */
$routes->get('/', 'DashboardController::index');

// =======================
// Accounts Routes
// =======================
$routes->get('accounts', 'AccountsController::index');
$routes->post('accounts/add', 'AccountsController::add');
$routes->post('accounts/edit', 'AccountsController::edit');
$routes->match(['POST', 'DELETE'], 'accounts/delete/(:num)', 'AccountsController::delete/$1');

// =======================
// Categories Routes
// =======================
$routes->get('categories/income', 'CategoriesController::income');
$routes->post('categories/income/add', 'CategoriesController::addIncome');
$routes->post('categories/income/edit', 'CategoriesController::editIncome');
$routes->match(['POST', 'DELETE'], 'categories/income/delete/(:num)', 'CategoriesController::deleteIncome/$1');
$routes->get('categories/expense', 'CategoriesController::expense');
$routes->post('categories/expense/add', 'CategoriesController::addExpense');
$routes->post('categories/expense/edit', 'CategoriesController::editExpense');
$routes->match(['POST', 'DELETE'], 'categories/expense/delete/(:num)', 'CategoriesController::deleteExpense/$1');

// =======================
// Budgets Routes
// =======================
$routes->get('budgets/income', 'BudgetsController::income');
$routes->get('budgets/expense', 'BudgetsController::expense');
$routes->post('budgets/add', 'BudgetsController::add');
$routes->post('budgets/edit', 'BudgetsController::edit');
$routes->match(['POST', 'DELETE'], 'budgets/delete/(:num)', 'BudgetsController::delete/$1');

// =======================
// Transactions Routes
// =======================
$routes->get('transactions/income', 'TransactionsController::income');
$routes->post('transactions/income/add', 'TransactionsController::addIncome');
$routes->post('transactions/income/edit', 'TransactionsController::editIncome');
$routes->match(['POST', 'DELETE'], 'transactions/income/delete/(:num)', 'TransactionsController::deleteIncome/$1');
$routes->get('transactions/expense', 'TransactionsController::expense');
$routes->post('transactions/expense/add', 'TransactionsController::addExpense');
$routes->post('transactions/expense/edit', 'TransactionsController::editExpense');
$routes->match(['POST', 'DELETE'], 'transactions/expense/delete/(:num)', 'TransactionsController::deleteExpense/$1');

// =======================
// Reports Routes
// =======================
$routes->get('reports/cashflow', 'ReportsController::cashflow');
$routes->get('reports/budget', 'ReportsController::budget');
$routes->get('reports/category', 'ReportsController::category');
$routes->post('reports/exportPDF', 'ReportsController::exportPDF');
$routes->post('reports/exportExcel', 'ReportsController::exportExcel');
$routes->post('reports/exportBudgetPDF', 'ReportsController::exportBudgetPDF');
$routes->post('reports/exportBudgetExcel', 'ReportsController::exportBudgetExcel');
$routes->post('reports/exportCategoryPDF', 'ReportsController::exportCategoryPDF');
$routes->post('reports/exportCategoryExcel', 'ReportsController::exportCategoryExcel');
$routes->get('reports/account', 'ReportsController::account');
$routes->get('reports/trend', 'ReportsController::trend');
$routes->post('reports/exportAccountPDF', 'ReportsController::exportAccountPDF');
$routes->post('reports/exportAccountExcel', 'ReportsController::exportAccountExcel');
$routes->post('reports/exportTrendPDF', 'ReportsController::exportTrendPDF');
$routes->post('reports/exportTrendExcel', 'ReportsController::exportTrendExcel');

// =======================
// Users Routes
// =======================
$routes->get('users', 'UsersController::index');
$routes->post('users/add', 'UsersController::add');
$routes->post('users/edit', 'UsersController::edit');
$routes->post('users/delete', 'UsersController::delete');

// =======================
// Profile Routes
// =======================
$routes->get('profile', 'ProfileController::index');
$routes->post('profile/update', 'ProfileController::update');
$routes->post('profile/change-password', 'ProfileController::changePassword');

// =======================
// Settings Routes
// =======================
$routes->get('settings', 'SettingsController::index');
$routes->post('settings/update', 'SettingsController::update');

// =======================
// Auth Routes
// =======================
$routes->match(['GET', 'POST'], 'login', 'AuthController::login');
$routes->match(['GET', 'POST'], 'register', 'AuthController::register');
$routes->get('logout', 'AuthController::logout');
