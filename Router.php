<?php
require_once 'Request.php';
require_once 'Response.php';
require_once 'User.php';

class Router {
    private static $routes = [];

    public function get($uri, $callback) {
        self::$routes['GET'][$uri] = $callback;
    }
    
    public static function post($route, $callback) {
        self::$routes['POST'][$route] = $callback;
    }

    public static function handleRequest() {
        $requestMethod = $_SERVER['REQUEST_METHOD'];
        $requestUri = $_SERVER['REQUEST_URI'];

        // Debugging: Print registered routes and request URI
        error_log("Registered routes: " . print_r(self::$routes, true));
        error_log("Request URI: " . $requestUri);
    
        $request = Request::getInstance($requestUri, $requestMethod);
        $response = Response::getInstance();
    
        if (isset(self::$routes[$requestMethod][$requestUri])) {
            $callback = self::$routes[$requestMethod][$requestUri];
            return call_user_func($callback, $request, $response);
        } else {
            http_response_code(404);
            echo "404 Not Found";
            return null;
        }
    }
}
?>