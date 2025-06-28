<?php

use CodeIgniter\Router\RouteCollection;

/**
 * @var RouteCollection $routes
 */
$routes->get('/', 'DashboardController::index');
$routes->get('accounts', 'AccountsController::index');
$routes->get('transactions', 'TransactionsController::index');
$routes->get('categories', 'CategoriesController::index');
$routes->get('reports', 'ReportsController::index');
$routes->get('users', 'UsersController::index');
$routes->get('settings', 'SettingsController::index');
$routes->get('dbtest', 'Dbtest::index');
$routes->get('dbdebug', 'Dbdebug::index');
