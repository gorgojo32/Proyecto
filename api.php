<?php
require_once __DIR__ . '/controller/personaController.php';

$controlador = new PersonaController();
$accion = $_GET['accion'] ?? '';

try {
    switch ($accion) {
        case 'listar':     $controlador->listar(); break;
        case 'guardar':    $controlador->guardar(); break;
        case 'municipios': $controlador->municipios(); break;
        default:
            http_response_code(404);
            echo json_encode(['ok' => false, 'mensaje' => 'Acción no válida']);
    }
} catch (Exception $e) {
    http_response_code(500);
    echo json_encode(['ok' => false, 'mensaje' => 'Error del servidor']);
}