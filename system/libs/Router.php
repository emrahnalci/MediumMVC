<?php
class Router {
    protected $routes = [];

    public function get($uri, $action) {
        $this->add('GET', $uri, $action);
    }

    public function post($uri, $action) {
        $this->add('POST', $uri, $action);
    }

    protected function add($method, $uri, $action) {
        $uri = '/' . trim($uri, '/');
        $this->routes[$method][$uri] = $action;
    }

    public function dispatch() {
        $method = $_SERVER['REQUEST_METHOD'];
        $uri = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);
        $base = str_replace('index.php', '', $_SERVER['SCRIPT_NAME']);
        if (strpos($uri, $base) === 0) {
            $uri = substr($uri, strlen($base));
        }
        $uri = '/' . trim($uri, '/');
        $params = [];
        $action = null;
        if ($this->match($method, $uri, $params, $action)) {
            $this->callAction($action, $params);
            return;
        }
        // fallback to legacy parser
        new URLParser();
    }

    protected function match($method, $uri, &$params, &$action) {
        if (!isset($this->routes[$method])) {
            return false;
        }
        foreach ($this->routes[$method] as $route => $act) {
            $pattern = preg_replace('#\{[^/]+\}#', '([^/]+)', $route);
            $pattern = '#^' . $pattern . '$#';
            if (preg_match($pattern, $uri, $matches)) {
                array_shift($matches);
                $params = $matches;
                $action = $act;
                return true;
            }
        }
        return false;
    }

    protected function callAction($action, $params) {
        if (is_callable($action)) {
            call_user_func_array($action, $params);
            return;
        }
        if (is_string($action)) {
            $parts = explode('@', $action);
            $controller = ucwords($parts[0]);
            $method = $parts[1] ?? 'index';
            $file = 'app/controllers/' . $controller . '.php';
            if (file_exists($file)) {
                include_once $file;
                if (class_exists($controller)) {
                    $obj = new $controller();
                    if (method_exists($obj, $method)) {
                        call_user_func_array([$obj, $method], $params);
                        return;
                    }
                }
            }
        }
        header('HTTP/1.1 404 Not Found');
        echo 'Route not found';
    }
}
