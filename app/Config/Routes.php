<?php

use CodeIgniter\Router\RouteCollection;

/**
 * @var RouteCollection $routes
 */
$routes->get('/', 'admin\LoginController::index');
$routes->post('/properties/sendMessage', 'PropertiesController::sendMessage');
$routes->get('properties/calculateTotalPrice/(:segment)/(:segment)/(:segment)', 'PropertiesController::calculateTotalPrice/$1/$2/$3');
$routes->get('/properties/getGuestLimit/(:num)', 'PropertiesController::getGuestLimit/$1');
$routes->get('/properties/icsData/(:num)', 'PropertiesController::icsData/$1');
$routes->get('/properties/datePrices/(:num)', 'PropertiesController::datePrices/$1');
$routes->post('/checkout/submitBooking', 'CheckoutController::submitBooking');
$routes->get('/checkout', 'CheckoutController::index');
$routes->get('/check-out', 'CheckoutController::index');
$routes->get('/terms-and-conditions', 'TermsandConditionsController::index');
require APPPATH . 'Config/Propertiesroutes.php';

//Admin Routes
$routes->get('/admin/login', 'admin\LoginController::index');
$routes->get('/admin/logout', 'admin\LogoutController::index');
$routes->post('/admin/loginfunc', 'admin\LoginController::loginfunc');
//Users
$routes->get('/admin/add-user', 'admin\AdduserController::index');
$routes->post('/admin/adduser/insert', 'admin\AdduserController::insert');
$routes->get('/admin/user-masterlist', 'admin\UsermasterlistController::index');
$routes->delete('/admin/usermasterlist/delete/(:num)', 'admin\UsermasterlistController::delete/$1');
$routes->get('/admin/edit-user/(:num)', 'admin\EdituserController::index/$1');
$routes->post('/admin/edituser/update', 'admin\EdituserController::update');
//Properties
$routes->get('/admin/add-property', 'admin\AddpropertyController::index');
$routes->post('/admin/addproperty/insert', 'admin\AddpropertyController::insert');
$routes->get('/admin/property-masterlist', 'admin\PropertymasterlistController::index');
$routes->delete('/admin/propertymasterlist/delete/(:num)', 'admin\propertymasterlistController::delete/$1');
$routes->get('/admin/edit-property/(:num)', 'admin\EditpropertyController::index/$1');
$routes->post('/admin/editproperty/update', 'admin\EditpropertyController::update');
$routes->post('/admin/propertymasterlist/uploadImages', 'admin\PropertymasterlistController::uploadImages');
$routes->post('/admin/propertymasterlist/uploadBanners', 'admin\PropertymasterlistController::uploadBanners');
$routes->post('/admin/propertymasterlist/uploadFA', 'admin\PropertymasterlistController::uploadFA');
$routes->get('/admin/pricing/(:num)', 'admin\PricingController::index/$1');
$routes->get('/admin/pricing/Lists', 'admin\PricingController::lists');
$routes->get('/admin/propertymasterlist/propertydetails', 'admin\PropertymasterlistController::propertyDetails');
$routes->post('/admin/propertymasterlist/getData', 'admin\PropertymasterlistController::getData');
$routes->post('/admin/pricing/insert', 'admin\PricingController::insert');
$routes->post('/admin/pricing/insert-multiple', 'admin\PricingController::insertMultiple');
$routes->delete('/admin/propertymasterlist/deleteBanner/(:num)', 'admin\PropertymasterlistController::deleteBanner/$1');
$routes->delete('/admin/propertymasterlist/deleteImage/(:num)', 'admin\PropertymasterlistController::deleteImage/$1');
$routes->delete('/admin/propertymasterlist/deleteFeature/(:num)', 'admin\PropertymasterlistController::deleteFeature/$1');
//Reviews
$routes->get('/admin/reviews', 'admin\ReviewsController::index');
$routes->delete('/admin/reviews/delete/(:num)', 'admin\ReviewsController::delete/$1');
$routes->get('/admin/reviews-for-approval', 'admin\ReviewsforapprovalController::index');
$routes->post('/admin/reviewsforapproval/approve/(:num)', 'admin\ReviewsforapprovalController::approve/$1');
//Messages
$routes->get('/admin/messages', 'admin\MessagesController::index');
$routes->delete('admin/messages/delete/(:num)', 'admin\MessagesController::delete/$1');
//Bookings
$routes->get('/admin', 'admin\BookingController::index');
$routes->get('/admin/booking', 'admin\BookingController::index');
$routes->delete('/admin/booking/delete/(:num)', 'admin\BookingController::delete/$1');
$routes->post('/admin/booking/approve/(:num)', 'admin\BookingController::approve/$1');
$routes->get('/admin/booking/exportToCsv', 'admin\BookingController::exportToCsv');
$routes->get('/admin/booking/generatePdf/(:num)', 'admin\BookingController::generatePdf/$1');