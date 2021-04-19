<?php

namespace Config;

// Create a new instance of our RouteCollection class.
$routes = Services::routes();

// Load the system's routing file first, so that the app and ENVIRONMENT
// can override as needed.
if (file_exists(SYSTEMPATH . 'Config/Routes.php'))
{
	require SYSTEMPATH . 'Config/Routes.php';
}

/**
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
$routes->post('/exam/submit',"Home::submit_exam");
$routes->get('/guest/logout','Home::logout');
$routes->group('admin', function($routes)
{
    $routes->add('/', 'Admin::index');
    $routes->group('guests', function($routes){
		$routes->add("list", "Admin::guests/list");
		$routes->add("list/(:any)","Admin::guests/view/$1");
	});
	$routes->add("logout","Admin::logout");
});

/*
* @Api
*/
$routes->post("/api/isGuestLoggedIn","Api::isGuestLogged");
$routes->post("/api/guestLogin","Api::guestLogin");
$routes->post("/api/adminLogin","Api::adminLogin");
$routes->post("/api/getQuestions","Api::getQuestions");

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
if (file_exists(APPPATH . 'Config/' . ENVIRONMENT . '/Routes.php'))
{
	require APPPATH . 'Config/' . ENVIRONMENT . '/Routes.php';
}
