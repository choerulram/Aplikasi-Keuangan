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
$routes->get('reports', 'ReportsController::index');

// =======================
// Users Routes
// =======================
$routes->get('users', 'UsersController::index');
$routes->post('users/add', 'UsersController::add');
$routes->post('users/edit', 'UsersController::edit');

// =======================
// Settings Routes
// =======================
$routes->get('settings', 'SettingsController::index');

// =======================
// Auth Routes
// =======================
$routes->match(['GET', 'POST'], 'login', 'AuthController::login');
$routes->match(['GET', 'POST'], 'register', 'AuthController::register');
$routes->get('logout', 'AuthController::logout');
