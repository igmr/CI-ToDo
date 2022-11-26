<?php

namespace Config;

// Create a new instance of our RouteCollection class.
$routes = Services::routes();

// Load the system's routing file first, so that the app and ENVIRONMENT
// can override as needed.
if (is_file(SYSTEMPATH . 'Config/Routes.php')) {
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
// The Auto Routing (Legacy) is very dangerous. It is easy to create vulnerable apps
// where controller filters or CSRF protection are bypassed.
// If you don't want to define all routes, please use the Auto Routing (Improved).
// Set `$autoRoutesImproved` to true in `app/Config/Feature.php` and set the following to true.
// $routes->setAutoRoute(false);

/*
* --------------------------------------------------------------------
* Route Definitions
* --------------------------------------------------------------------
*/

// We get a performance increase by specifying the default
// route since we don't have to scan directories.
$routes->get('/', 'Home::index');
//*	****************************************************************************
//*	API REST
//*	****************************************************************************
//*	AUTH
//*	****************************************************************************
$routes->post('api/v1/auth/register'
	,[\App\Controllers\API\V1\AuthController::class		,	'register']);
$routes->post('api/v1/auth'
	,[\App\Controllers\API\V1\AuthController::class		,	'index']);
//*	****************************************************************************
//*	PROFILE
//*	****************************************************************************
$routes->get('api/v1/profile'
	,[\App\Controllers\API\V1\ProfileController::class	,	'index']
	,['filter'	=>	'auth']);
$routes->put('api/v1/profile'
	,[\App\Controllers\API\V1\ProfileController::class	,	'edit']
	,['filter'	=>	'auth']);
//*	****************************************************************************
//*	LIST
//*	****************************************************************************
$routes->get('api/v1/list'
	,[\App\Controllers\API\V1\ListController::class		,	'index']
	,['filter'	=>	'auth']);
$routes->get('api/v1/list/(:num)'
	,[\App\Controllers\API\V1\ListController::class		,	'show/$1']
	,['filter'	=>	'auth']);
$routes->post('api/v1/list'
	,[\App\Controllers\API\V1\ListController::class		,	'store']
	,['filter'	=>	'auth']);
$routes->put('api/v1/list/(:num)'
	,[\App\Controllers\API\V1\ListController::class		,	'edit/$1']
	,['filter'	=>	'auth']);
$routes->delete('api/v1/list/(:num)'
	,[\App\Controllers\API\V1\ListController::class		,	'remove/$1']
	,['filter'	=>	'auth']);
//*	****************************************************************************
//*	TASK
//*	****************************************************************************
$routes->get('api/v1/task'
	,[\App\Controllers\API\V1\TaskController::class		,	'index/all']
	,['filter'	=>	'auth']);
$routes->get('api/v1/task/today'
	,[\App\Controllers\API\V1\TaskController::class		,	'index/today']
	,['filter'	=>	'auth']);
$routes->get('api/v1/task/complete'
	,[\App\Controllers\API\V1\TaskController::class		,	'index/complete']
	,['filter'	=>	'auth']);
$routes->get('api/v1/task/important'
	,[\App\Controllers\API\V1\TaskController::class		,	'index/important']
	,['filter'	=>	'auth']);
$routes->get('api/v1/task/(:num)'
	,[\App\Controllers\API\V1\TaskController::class		,	'show/$1']
	,['filter'	=>	'auth']);
$routes->post('api/v1/task'
	,[\App\Controllers\API\V1\TaskController::class		,	'store']
	,['filter'	=>	'auth']);
$routes->put('api/v1/task/(:num)'
	,[\App\Controllers\API\V1\TaskController::class		,	'edit/all/$1']
	,['filter'	=>	'auth']);
$routes->put('api/v1/task/today/(:num)'
	,[\App\Controllers\API\V1\TaskController::class		,	'edit/today/$1']
	,['filter'	=>	'auth']);
$routes->put('api/v1/task/complete/(:num)'
	,[\App\Controllers\API\V1\TaskController::class		,	'edit/complete/$1']
	,['filter'	=>	'auth']);
$routes->put('api/v1/task/important/(:num)'
	,[\App\Controllers\API\V1\TaskController::class		,	'edit/important/$1']
	,['filter'	=>	'auth']);
//*	****************************************************************************
//*	TASK ITEM
//*	****************************************************************************
$routes->get('api/v1/task/(:num)/task_item'
	,[\App\Controllers\API\V1\TaskItemController::class	,	'index/$1']
	,['filter' => 'auth']);
$routes->get('api/v1/task_item/(:num)'
	,[\App\Controllers\API\V1\TaskItemController::class	,	'show/$1']
	,['filter' => 'auth']);
$routes->post('api/v1/task_item'
	,[\App\Controllers\API\V1\TaskItemController::class	,	'store']
	,['filter' => 'auth']);
$routes->put('api/v1/task_item/(:num)'
	,[\App\Controllers\API\V1\TaskItemController::class	,	'edit/all/$1']
	,['filter' => 'auth']);
$routes->put('api/v1/task_item/complete/(:num)'
	,[\App\Controllers\API\V1\TaskItemController::class	,	'edit/complete/$1']
	,['filter' => 'auth']);
//*	****************************************************************************

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
if (is_file(APPPATH . 'Config/' . ENVIRONMENT . '/Routes.php')) {
	require APPPATH . 'Config/' . ENVIRONMENT . '/Routes.php';
}
