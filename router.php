<?php
require_once 'config.php';

class Router {
    public function run() {
        $url = isset($_GET['url']) ? trim($_GET['url'], "/") : 'login/home';
        $url = filter_var($url, FILTER_SANITIZE_URL);
        $urlSegments = explode('/', $url);

        $controllerName = ucfirst($urlSegments[0]) . 'Controller';
        $methodName = isset($urlSegments[1]) ? $urlSegments[1] : 'home';
        $controllerFile = __DIR__ . '/controllers/' . $controllerName . '.php';

        if (file_exists($controllerFile)) {
            require_once $controllerFile;
            $controller = new $controllerName();

            if (method_exists($controller, $methodName)) {
                $params = array_slice($urlSegments, 2);
                call_user_func_array([$controller, $methodName], $params);
            } else {
                http_response_code(404);
                echo "Método '$methodName' no encontrado en '$controllerName'.";
            }
        } else {
            http_response_code(404);
            echo "Controlador '$controllerName' no encontrado.";
        }
    }
}

$router = new Router();
$router->run();
?>
