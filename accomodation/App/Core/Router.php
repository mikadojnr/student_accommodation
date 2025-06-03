<?php

namespace App\Core;

/**
 * Router class
 * Handles routing for the application
 */
class Router
{
    /**
     * @var array
     */
    protected $routes = [
        'GET' => [],
        'POST' => []
    ];

    /**
     * Register a GET route
     *
     * @param string $uri
     * @param string $controller
     * @return void
     */
    public function get($uri, $controller)
    {
        $this->routes['GET'][$uri] = $controller;
    }

    /**
     * Register a POST route
     *
     * @param string $uri
     * @param string $controller
     * @return void
     */
    public function post($uri, $controller)
    {
        $this->routes['POST'][$uri] = $controller;
    }

    /**
     * Dispatch the request to the appropriate route
     *
     * @return void
     */
    public function dispatch()
    {
        $uri = $this->parseUri();
        $method = $_SERVER['REQUEST_METHOD'];

        // Check for direct match
        if (array_key_exists($uri, $this->routes[$method])) {
            return $this->callAction(
                ...explode('@', $this->routes[$method][$uri])
            );
        }

        // Check for dynamic routes with parameters
        foreach ($this->routes[$method] as $route => $controller) {
            if (strpos($route, '{') !== false) {
                $pattern = $this->convertRouteToRegex($route);
                if (preg_match($pattern, $uri, $matches)) {
                    array_shift($matches); // Remove the full match
                    list($controllerName, $actionName) = explode('@', $controller);
                    return $this->callAction($controllerName, $actionName, $matches);
                }
            }
        }

        // No route found
        $this->notFound();
    }

    /**
     * Parse the URI from the request
     *
     * @return string
     */
    protected function parseUri()
    {
        $uri = $_SERVER['REQUEST_URI'];
        
        // Remove query string
        if (strpos($uri, '?') !== false) {
            $uri = substr($uri, 0, strpos($uri, '?'));
        }
        
        // Remove trailing slash
        $uri = rtrim($uri, '/');
        
        // If URI is empty, set it to '/'
        if (empty($uri)) {
            $uri = '/';
        }
        
        return $uri;
    }

    /**
     * Convert route with parameters to regex pattern
     *
     * @param string $route
     * @return string
     */
    protected function convertRouteToRegex($route)
    {
        $pattern = preg_replace('/\{([a-zA-Z0-9_]+)\}/', '([^/]+)', $route);
        return '#^' . $pattern . '$#';
    }

    /**
     * Call the controller action
     *
     * @param string $controller
     * @param string $action
     * @param array $params
     * @return mixed
     */
    protected function callAction($controller, $action, $params = [])
    {
        $controller = "App\\Controllers\\{$controller}";
        
        if (!class_exists($controller)) {
            throw new \Exception("Controller {$controller} does not exist");
        }
        
        $controllerInstance = new $controller();
        
        if (!method_exists($controllerInstance, $action)) {
            throw new \Exception("Action {$action} does not exist on {$controller}");
        }
        
        return $controllerInstance->$action(...$params);
    }

    /**
     * Handle 404 Not Found
     *
     * @return void
     */
    protected function notFound()
    {
        header('HTTP/1.0 404 Not Found');
        echo View::render('errors/404');
        exit;
    }
}
