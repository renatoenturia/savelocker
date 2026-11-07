<?php

namespace Core;

class Route
{
    public array $routes = [];

    public function addRoute(string $httpMethod, string $uri, string $controller, string $method): void
    {
        $this->routes[$httpMethod][$uri] = [
            'class' => $controller,
            'method' => $method,
        ];
    }

    public function get(string $uri, string|array $controller): self
    {
        [$class, $method] = is_array($controller) ? $controller : [$controller, '__invoke'];

        $this->addRoute('GET', $uri, $class, $method);

        return $this;
    }

    public function post(string $uri, string|array $controller): self
    {
        [$class, $method] = is_array($controller) ? $controller : [$controller, '__invoke'];

        $this->addRoute('POST', $uri, $class, $method);

        return $this;
    }

    public function run(): void
    {
        $uri = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);
        $httpMethod = $_SERVER['REQUEST_METHOD'];

        $route = $this->routes[$httpMethod][$uri] ?? null;

        if ($route === null) {
            http_response_code(404);
            view('404');

            return;
        }

        $controller = new $route['class']();
        $method = $route['method'];
        $controller->$method();
    }
}
