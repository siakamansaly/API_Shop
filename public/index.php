<?php
use WshopApi\Renderer;

require_once "./../vendor/autoload.php";
define('ROOT_CONTROLLER', 'WshopApi\Controller\\');

// Start Router
$router = new AltoRouter();

// Add all routes
$router->addRoutes(array(
    array('GET', '/shop', ROOT_CONTROLLER.'Shop#ReadAllShop'),
    array('GET', '/shop/[i:id]', ROOT_CONTROLLER.'Shop#ReadOneShop'),
    array('POST', '/shop', ROOT_CONTROLLER.'Shop#CreateShop'),
    array('PUT', '/shop/[i:id]', ROOT_CONTROLLER.'Shop#UpdateShop'),
    array('DELETE', '/shop/[i:id]', ROOT_CONTROLLER.'Shop#DeleteShop'),
  ));

// Match url
$match = $router->match();

// call closure or throw 404 status
if (is_array($match)) {

    // Extract Class and method
    list($controller_class, $action) = explode('#', $match['target']);
    // Verify if method is callable, display page 
    if (is_callable(array($controller_class, $action))) {
        $controller = new $controller_class;
        
        call_user_func_array(array($controller, $action), $match['params']);
        
    } else {
        // Method not found
        $result['code'] = 405;
        $result['description'] = "Method not allowed";
        $response = new Renderer();
        echo $response->render($result['code'], $result);
    }
} else {
    // no route was matched
    $result['code'] = 404;
    $result['description'] = "Page not found";
    $response = new Renderer();
    echo $response->render($result['code'], $result);
}


