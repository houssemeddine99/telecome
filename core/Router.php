<?php
class Router {
    private $routes = [];

    public function addRoute($method, $path, $controller, $action) {
        $this->routes[] = [
            'method' => $method,
            'path' => $path,
            'controller' => $controller,
            'action' => $action
        ];
    }

    public function route($db) {
        $path = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);
        $method = $_SERVER['REQUEST_METHOD'];

        foreach ($this->routes as $route) {
            if ($route['path'] === $path && $route['method'] === $method) {
                $controllerName = $route['controller'];
                $actionName = $route['action'];

                require_once __DIR__ . "/../controllers/{$controllerName}.php";
                $controller = new $controllerName($db);
                $controller->$actionName();
                return;
            }
        }

        // If no route matches, show 404 error
        http_response_code(404);
        echo "404 Not Found";
    }
}