<?php

namespace Config;

// Create a new instance of our RouteCollection class.
$routes = Services::routes();

// Load the system's routing file first, so that the app and ENVIRONMENT
// can override as needed.
if (file_exists(SYSTEMPATH . 'Config/Routes.php')) {
    require SYSTEMPATH . 'Config/Routes.php';
}

/*
 * --------------------------------------------------------------------
 * Router Setup
 * --------------------------------------------------------------------
 */
$routes->setDefaultNamespace('App\Controllers');
$routes->setDefaultController('Home');
$routes->setDefaultMethod('index');
$routes->setTranslateURIDashes(false);
$routes->set404Override();
$routes->setAutoRoute(true);

/*
 * --------------------------------------------------------------------
 * Route Definitions
 * --------------------------------------------------------------------
 */

// We get a performance increase by specifying the default
// route since we don't have to scan directories.
$routes->get('/', 'Home::index');

// $routes->get('/auth/register', 'Auth::register');
//
// $routes->get('/dashboard', 'Dashboard::index');
// $routes->get('/dashboard/save_item', 'Dashboard::save_item');
// $routes->get('/dashboard/edit_item', 'Dashboard::edit_item');

$routes->group('', ['filter' => 'authCheck'], function($routes){
  $routes->get('/dashboard', 'Dashboard::index');
  $routes->get('/wecomepage', 'Dashboard::welcomepage');
  $routes->get('/salary', 'Salary::index');
  $routes->get('/salary/fzp', 'Salary::create_fzp');
  $routes->get('/salary/fzp/(:any)', 'Salary::update_fzp/$1');
  $routes->get('/salary/create_advance/(:any)', 'Salary::create_advance/$1');
  $routes->get('/salary/delete_advance/(:any)', 'Salary::delete_advance/$1');
  $routes->get('/salary/advance/(:any)', 'Salary::update_advance/$1');
  $routes->get('/report', 'Report::index');
  $routes->get('/analytics', 'Funds::index');
  $routes->get('/funds/save_new_contractors', 'Funds::save_new_contractors');
  $routes->get('/requests', 'PurchaseRequest::index');
  $routes->get('/request/create', 'PurchaseRequest::create');
  //$routes->get('/importCsvToDb', 'Funds::importCsvToDb');
  $routes->match(['get', 'post'], 'Funds/importCsvToDb', 'Funds::importCsvToDb');
  //$routes->match(['get', 'post'], 'Funds/save_new_contractors', 'Funds::save_new_contractors');
  $routes->get('/cars', 'Cars::index');
  $routes->get('/cars/save_car', 'Cars::save_car');
  $routes->get('/cars/indication', 'Cars::indication');
  $routes->post('/cars/save_indication', 'Cars::save_indication');
  $routes->post('/coupons', 'Coupons::index');
  $routes->match(['get', 'post'], 'Funds/importCsvToDb', 'Funds::importCsvToDb');
});

$routes->group('', ['filter' => 'alreadyLoggedIn'], function($routes){
  $routes->get('/auth', 'Auth::index');
  $routes->get('/auth/register', 'Auth::register');
});
/*
 * --------------------------------------------------------------------
 * Additional Routing
 * --------------------------------------------------------------------
 *
 * There will often be times that you need additional routing and you
 * need it to be able to override any defaults in this file. Environment
 * based routes is one such time. require() additional route files here
 * to make that happen.
 *
 * You will have access to the $routes object within that file without
 * needing to reload it.
 */
if (file_exists(APPPATH . 'Config/' . ENVIRONMENT . '/Routes.php')) {
    require APPPATH . 'Config/' . ENVIRONMENT . '/Routes.php';
}