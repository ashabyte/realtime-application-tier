<?php
// HAPUS SEMUA OUTPUT SEBELUMNYA
if (ob_get_level()) ob_end_clean();

error_reporting(0);
ini_set('display_errors', 0);

// HEADERS DULU
header("Content-Type: application/json; charset=UTF-8");
header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Methods: GET, POST, PUT, DELETE, OPTIONS");
header("Access-Control-Allow-Headers: Content-Type, Authorization");

// Handle preflight
if ($_SERVER['REQUEST_METHOD'] === 'OPTIONS') {
    http_response_code(200);
    exit();
}

try {
    // Define paths
    $basePath = dirname(__DIR__);
    
    // Load files
    require_once $basePath . '/app/config/Config.php';
    require_once $basePath . '/app/config/Database.php';
    require_once $basePath . '/app/core/Model.php';
    require_once $basePath . '/app/core/Controller.php';
    require_once $basePath . '/app/core/App.php';
    require_once $basePath . '/app/models/Idol.php';
    require_once $basePath . '/app/services/IdolService.php';
    
    // Run app
    new App();
    
} catch (Exception $e) {
    // JANGAN keluarin error detail
    echo json_encode([
        "success" => false,
        "message" => "Internal server error"
    ]);
}