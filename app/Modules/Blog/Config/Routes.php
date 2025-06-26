<?php
$routes->group('blog', ['namespace' => 'App\Modules\Blog\Controllers'], function($routes){
    $routes->get('/', 'Blog::index');
});
