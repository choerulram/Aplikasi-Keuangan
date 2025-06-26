<?php

use CodeIgniter\Router\RouteCollection;

/**
 * @var RouteCollection $routes
 */
$routes->get('/', 'Home::index');

// Autoload HMVC module routes
define('MODULES_PATH', APPPATH . 'Modules/');
$modules = scandir(MODULES_PATH);
foreach ($modules as $module) {
    if ($module === '.' || $module === '..') continue;
    $routePath = MODULES_PATH . $module . '/Config/Routes.php';
    if (file_exists($routePath)) {
        require $routePath;
    }
}
