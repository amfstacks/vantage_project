<?php

use CodeIgniter\Router\RouteCollection;

/**
 * @var RouteCollection $routes
 */
// $routes->get('/', 'Home::index');

// Public Auth Routes
$routes->get('login', 'Auth::login');
$routes->post('login/attempt', 'Auth::attemptLogin');
$routes->get('logout', 'Auth::logout');
$routes->get('/', 'Frontend\Home::index');


// Protected Admin Routes (Grouped with the filter)
$routes->group('admin', ['filter' => 'adminAuth', 'namespace' => 'App\Controllers\Admin'], function($routes) {
    $routes->get('dashboard', 'Dashboard::index');
// Properties routes
$routes->get('properties', 'PropertyManager::index');
$routes->get('properties/create', 'PropertyManager::create');
$routes->post('properties/store', 'PropertyManager::store'); // <-- Make sure this line exists
   $routes->get('properties/edit/(:num)', 'PropertyManager::edit/$1');
$routes->post('properties/update/(:num)', 'PropertyManager::update/$1');
$routes->get('properties/delete/(:num)', 'PropertyManager::delete/$1');
$routes->get('properties/delete-image/(:num)', 'PropertyManager::deleteImage/$1');

    $routes->get('amenities', 'Settings::amenities');
$routes->post('amenities/save', 'Settings::saveAmenity'); // Handles both Create & Update
$routes->get('amenities/delete/(:num)', 'Settings::deleteAmenity/$1');
});
