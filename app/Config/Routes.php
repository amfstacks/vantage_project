<?php

use CodeIgniter\Router\RouteCollection;

/** @var RouteCollection $routes */

$routes->get('/', 'Frontend\Home::index');
$routes->get('home', 'Frontend\Home::index');

// Public authentication
$routes->get('login', 'Auth::login');
$routes->post('login/attempt', 'Auth::attemptLogin');
$routes->get('logout', 'Auth::logout');

// Public pages
$routes->get('properties', 'Frontend\Properties::index');
$routes->get('property/(:segment)', 'Frontend\Properties::show/$1');
$routes->get('about', 'Frontend\About::index');
$routes->get('contact', 'Frontend\Contact::index');
$routes->get('sitemap.xml', 'Frontend\Seo::sitemap');
$routes->get('robots.txt', 'Frontend\Seo::robots');

// AJAX endpoints
$routes->get('ajax/properties', 'Frontend\Properties::ajax');
$routes->get('ajax/load-featured-tabs', 'Frontend\PropertyAjax::loadAllFeaturedTabs');
$routes->get('ajax/get-amenities/(:num)', 'Frontend\PropertyAjax::getAmenities/$1');
$routes->get('ajax/load-recent-properties', 'Frontend\PropertyAjax::loadRecentProperties');
$routes->get('ajax/load-locations', 'Frontend\PropertyAjax::loadLocations');
$routes->post('ajax/property-request', 'Frontend\PropertyRequest::store');

// Protected admin routes
$routes->group('admin', ['filter' => 'adminAuth', 'namespace' => 'App\Controllers\Admin'], static function (RouteCollection $routes): void {
    $routes->get('dashboard', 'Dashboard::index');

    $routes->get('properties', 'PropertyManager::index');
    $routes->get('properties/create', 'PropertyManager::create');
    $routes->post('properties/store', 'PropertyManager::store');
    $routes->get('properties/edit/(:num)', 'PropertyManager::edit/$1');
    $routes->post('properties/update/(:num)', 'PropertyManager::update/$1');
    $routes->post('properties/delete/(:num)', 'PropertyManager::delete/$1');
    $routes->post('properties/delete-image/(:num)', 'PropertyManager::deleteImage/$1');
    $routes->post('properties/set-primary-image/(:num)', 'PropertyManager::setPrimaryImage/$1');

    $routes->get('requests', 'Requests::index');
    $routes->post('requests/status/(:num)', 'Requests::updateStatus/$1');

    $routes->get('amenities', 'Settings::amenities');
    $routes->post('amenities/save', 'Settings::saveAmenity');
    $routes->post('amenities/delete/(:num)', 'Settings::deleteAmenity/$1');

    $routes->get('property-types', 'Settings::propertyTypes');
    $routes->post('property-types/save', 'Settings::savePropertyType');
    $routes->post('property-types/delete/(:num)', 'Settings::deletePropertyType/$1');

    $routes->get('purposes', 'Settings::purposes');
    $routes->post('purposes/save', 'Settings::savePurpose');
    $routes->post('purposes/delete/(:num)', 'Settings::deletePurpose/$1');
});
