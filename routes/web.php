<?php

/*
|--------------------------------------------------------------------------
| Application Routes
|--------------------------------------------------------------------------
|
| Here is where you can register all of the routes for an application.
| It is a breeze. Simply tell Lumen the URIs it should respond to
| and give it the Closure to call when that URI is requested.
|
*/

$app->get('/', [
    'as' => 'main',
    'uses' => 'ExecutionController@index',
]);
$app->post('/run', 'ExecutionController@run');

$app->get('/categories', [
    'as' => 'categories',
    'uses' => 'CategoryController@index',
]);
$app->post('/categories/{id}/update', [
    'as' => 'save_category',
    'uses' => 'CategoryController@update'
]);
$app->post('/categories/update-all', [
    'as' => 'save_all_categories',
    'uses' => 'CategoryController@updateAll',
]);

$app->get('/pages', [
    'as' => 'pages',
    'uses' => 'PageController@index',
]);
$app->post('/pages/{id}/update', [
    'as' => 'save_page',
    'uses' => 'PageController@update'
]);
$app->post('/pages/update-all', [
    'as' => 'save_all_pages',
    'uses' => 'PageController@updateAll',
]);

$app->get('/products', [
    'as' => 'products',
    'uses' => 'ProductController@index',
]);
$app->post('/products/{id}/update', [
    'as' => 'save_product',
    'uses' => 'ProductController@update'
]);
$app->post('/products/update-all', [
    'as' => 'save_all_products',
    'uses' => 'ProductController@updateAll',
]);
