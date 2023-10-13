<?php

namespace Config;

// Create a new instance of our RouteCollection class.
$routes = Services::routes();


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
// $routes->get('/hello', 'Hello::index');
$routes->get('/login', 'Login::index');
$routes->post('/login/check_login', 'Login::check_login');
$routes->get('/login/logout', 'Login::logout');
$routes->get('/login/forgot_page', 'User::forgot_page');
$routes->post('/login/forgot_password', 'User::forgot_password');
$routes->match(['get', 'post'], 'reset_password', 'User::reset_password', ['as' => 'reset_password']);
$routes->get('/signup', 'Signup::index');
$routes->post('/signup/check_signup', 'Signup::check_signup');
// $routes->post('/upload/upload_file', 'Upload::upload_file');
// $routes->get('/upload', 'Upload::index');
// $routes->get('movie', 'MovieController::index');
$routes->get('/course', 'Course::index');
$routes->get('/course/getAutoComplete', 'Course::getAutoComplete');
$routes->get('/course/searchKeyword', 'Course::searchKeyword');
$routes->get('/course/loadMoreCourses', 'Course::loadMoreCourses');
$routes->get('course/courseDetail/(:num)', 'Course::courseDetail/$1');
$routes->post('course/addComment', 'Course::addComment');
$routes->post('course/removeFromCart/(:num)', 'Course::removeFromCart/$1');
$routes->post('course/rmFromCartDetail/(:num)', 'Course::rmFromCartDetail/$1');
$routes->post('course/addToCart/', 'Course::addToCart');
$routes->get('/profile', 'User::index');
$routes->post('/profile/update_profile', 'User::update_profile');
$routes->get('/course/instructorAllCourse', 'Course::instructorAllCourse');
$routes->match(['get', 'post'],'/course/addCourse', 'Course::addCourse');
// $routes->get('/verify-email', 'Signup::verify_email_form');
// $routes->post('/verify_email', 'Signup::verify_email');
$routes->match(['get', 'post'], '/verify_email', 'Signup::verify_email');


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
