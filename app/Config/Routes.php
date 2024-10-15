<?php

use CodeIgniter\Router\RouteCollection;

/**
 * @var RouteCollection $routes
 */
// $routes->get('/', 'Home::index');
$routes->get('book', 'Book::index'); // Aapko URL par book hit karna hoga
$routes->get('book/create', 'Book::create');
$routes->post('book/store', 'Book::store');
$routes->get('book/edit/(:num)', 'Book::edit/$1');
$routes->put('book/update/(:num)', 'Book::update/$1');
$routes->get('book/delete/(:num)', 'Book::delete/$1');
$routes->get('book/getCities/(:num)', 'Book::getCities/$1');
$routes->post('book/fetch', 'Book::fetch'); // AJAX route for fetching book data
$routes->get('/', 'Book::register'); 
$routes->post('user/store', 'Book::userstore'); // AJAX route for fetching book data
$routes->get('user/login', 'Book::userlogin'); // AJAX route for fetching book data
$routes->post('user/auth', 'Book::userauth');
$routes->post('user/logout', 'Book::logout'); 






$routes->group('api', function($routes) {
    $routes->get('books', 'ApiController::index');
    $routes->get('books/(:num)', 'ApiController::show/$1');
    $routes->post('books', 'ApiController::create');
    $routes->put('books/(:num)', 'ApiController::update/$1');
    $routes->delete('books/(:num)', 'ApiController::delete/$1');
});








 

