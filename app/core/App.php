<?php
class App {
    private $controller = 'IdolController';
    private $method = 'index';
    private $params = [];

    public function __construct() {
        // Set CORS headers
        header("Access-Control-Allow-Origin: *");
        header("Access-Control-Allow-Methods: GET, POST, PUT, DELETE, OPTIONS");
        header("Access-Control-Allow-Headers: Content-Type, Authorization");
        
        // Handle preflight
        if ($_SERVER['REQUEST_METHOD'] === 'OPTIONS') {
            http_response_code(200);
            exit();
        }
        
        // Get URL segments
        $url = $this->parseUrl();
        
        // Set controller
        if (isset($url[0]) && !empty($url[0])) {
            $controllerName = ucfirst(strtolower($url[0])) . 'Controller';
            if (file_exists("../app/controllers/{$controllerName}.php")) {
                $this->controller = $controllerName;
            }
            unset($url[0]);
        }
        
        // Load controller
        require_once "../app/controllers/{$this->controller}.php";
        $this->controller = new $this->controller();
        
        // Set method based on HTTP method
        $httpMethod = $_SERVER['REQUEST_METHOD'];
        $id = isset($url[1]) ? $url[1] : null;
        
        switch ($httpMethod) {
            case 'GET':
                $this->method = $id ? 'show' : 'index';
                $this->params = $id ? [$id] : [];
                break;
                
            case 'POST':
                $this->method = 'create';
                $this->params = [];
                break;
                
            case 'PUT':
            case 'PATCH':
                if ($id) {
                    $this->method = 'update';
                    $this->params = [$id];
                } else {
                    $this->error('ID required for update', 400);
                    return;
                }
                break;
                
            case 'DELETE':
                if ($id) {
                    $this->method = 'delete';
                    $this->params = [$id];
                } else {
                    $this->error('ID required for delete', 400);
                    return;
                }
                break;
                
            default:
                $this->error("Method {$httpMethod} not supported", 405);
                return;
        }
        
        // Call controller method
        if (method_exists($this->controller, $this->method)) {
            call_user_func_array([$this->controller, $this->method], $this->params);
        } else {
            $this->error("Method {$this->method} not found", 404);
        }
    }

    private function parseUrl() {
        if (isset($_GET['url'])) {
            return explode('/', filter_var(rtrim($_GET['url'], '/'), FILTER_SANITIZE_URL));
        }
        return [];
    }

    // Di constructor App, ganti bagian akhir:
    private function error($message, $code) {
    http_response_code($code);
    echo json_encode(["success" => false, "message" => $message], JSON_UNESCAPED_UNICODE);
    exit();
    }
}