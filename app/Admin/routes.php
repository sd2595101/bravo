<?php

use Illuminate\Routing\Router;

Admin::registerAuthRoutes();

Route::group([
    'prefix'        => config('admin.route.prefix'),
    'namespace'     => config('admin.route.namespace'),
    'middleware'    => config('admin.route.middleware'),
], function (Router $router) {

    $router->get('/', 'HomeController@index');
    // -- added
    $router->resource('crawler/sites', 'SiteController');
    
    $router->resource('issues', 'IssueController');
    
    $router->resource('book-task/roots', 'BookTaskRootController');
    $router->post('book-task/roots/{root}/regist', 'BookTaskRootController@regist')->name('roots.task');

});
