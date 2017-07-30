<?php
/**
 * Created by PhpStorm.
 * User: spider-ninja
 * Date: 6/4/16
 * Time: 1:12 PM
 */

\Slim\Slim::registerAutoloader();

global $app;

if(!isset($app))
    $app = new \Slim\Slim();


//$app->response->headers->set('Access-Control-Allow-Origin',  'http://localhost');
$app->response->headers->set('Access-Control-Allow-Credentials',  'true');
$app->response->headers->set('Access-Control-Allow-Methods', 'GET,PUT,POST,DELETE,OPTIONS');
/*$app->response->headers->set('Access-Control-Allow-Headers', 'X-CSRF-Token, X-Requested-With, Accept, Accept-Version, Content-Length, Content-MD5, Content-Type, Date, X-Api-Version');
*/
$app->response->headers->set('Content-Type', 'application/json');

/* Starting routes */

//auth
$app->post('/auth', 'userAuth');//user,org,temp_user{mobile,mac}
$app->post('/users', 'regUser');
$app->post('/ads', 'createAds');
$app->get('/ads','getAllAds');

$app->get('/users/:mobile/search','searchUser');

$app->get('/bill_board/:id','getBBAds');
$app->post('/bill_board/:id/order','postOrder');

$app->post('/bill_board/:id/waiter','storeWaiter');
$app->get('/bill_board/:id/waiter/:storeId','getWaiterCalls');

$app->get('/store/:storeId/waiter/:waiterId/table/:tableId','callAccepted');

$app->post('/bill_board/:id/feedback','storeFeedback');

$app->get('/user/:mobile/otp/:otp', 'checkOtp');

//$app->get('/service_provider','getServiceProviderByType');

/* Ending Routes */

$app->run();