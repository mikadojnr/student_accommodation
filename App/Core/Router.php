<?php

namespace App\Core;

class Router
{
    private $routes = [];
    private $currentRoute = null;

    public function get($path, $handler)
    {
        $this->addRoute('GET', $path, $handler);
    }

    public function post($path, $handler)
    {
        $this->addRoute('POST', $path, $handler);
    }

    public function put($path, $handler)
    {
        $this->addRoute('PUT', $path, $handler);
    }

    public function delete($path, $handler)
    {
        $this->addRoute('DELETE', $path, $handler);
    }

    private function addRoute($method, $path, $handler)
    {
        $this->routes[] = [
            'method' => $method,
            'path' => $path,
            'handler' => $handler
        ];
    }

    public function dispatch()
    {
        $requestMethod = $_SERVER['REQUEST_METHOD'];
        $requestPath = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);

        // Handle PUT and DELETE methods via _method parameter
        if ($requestMethod === 'POST' && isset($_POST['_method'])) {
            $requestMethod = strtoupper($_POST['_method']);
        }

        foreach ($this->routes as $route) {
            if ($route['method'] === $requestMethod) {
                $pattern = $this->convertToRegex($route['path']);
                if (preg_match($pattern, $requestPath, $matches)) {
                    $this->currentRoute = $route;
                    array_shift($matches); // Remove full match
                    return $this->callHandler($route['handler'], $matches);
                }
            }
        }

        // No route found
        http_response_code(404);
        include BASE_PATH . '/resources/views/errors/404.php';
    }

    private function convertToRegex($path)
    {
        $pattern = preg_replace('/\{([^}]+)\}/', '([^/]+)', $path);
        return '#^' . $pattern . '$#';
    }

    private function callHandler($handler, $params = [])
    {
        if (is_string($handler)) {
            list($controller, $method) = explode('@', $handler);
            $controllerClass = "App\\Controllers\\$controller";
            
            if (class_exists($controllerClass)) {
                $controllerInstance = new $controllerClass();
                if (method_exists($controllerInstance, $method)) {
                    return call_user_func_array([$controllerInstance, $method], $params);
                }
            }
        }

        throw new \Exception("Handler not found: $handler");
    }

    public function getCurrentRoute()
    {
        return $this->currentRoute;
    }
}
