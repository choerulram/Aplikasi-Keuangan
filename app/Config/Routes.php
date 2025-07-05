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
// Other Routes
// =======================
$routes->get('transactions', 'TransactionsController::index');
$routes->get('reports', 'ReportsController::index');
$routes->get('users', 'UsersController::index');
$routes->get('settings', 'SettingsController::index');
$routes->match(['GET', 'POST'], 'login', 'AuthController::login');
$routes->match(['GET', 'POST'], 'register', 'AuthController::register');
$routes->get('logout', 'AuthController::logout');
