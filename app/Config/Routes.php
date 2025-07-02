<?php

use CodeIgniter\Router\RouteCollection;

/**
 * @var RouteCollection $routes
 */
$routes->get('/', 'DashboardController::index');
$routes->get('accounts', 'AccountsController::index');
$routes->get('transactions', 'TransactionsController::index');
$routes->get('categories', 'CategoriesController::index');
$routes->get('categories/income', 'CategoriesController::income');
$routes->get('categories/expense', 'CategoriesController::expense');
$routes->get('reports', 'ReportsController::index');
$routes->get('users', 'UsersController::index');
$routes->get('settings', 'SettingsController::index');
$routes->match(['GET', 'POST'], 'login', 'AuthController::login');
$routes->match(['GET', 'POST'], 'register', 'AuthController::register');
$routes->get('logout', 'AuthController::logout');
$routes->post('accounts/add', 'AccountsController::add');
$routes->post('accounts/edit', 'AccountsController::edit');
$routes->match(['POST', 'DELETE'], 'accounts/delete/(:num)', 'AccountsController::delete/$1');
