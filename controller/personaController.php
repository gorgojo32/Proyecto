<?php
require_once __DIR__ . '/../models/persona.php';

class PersonaController
{
    private Persona $modelo;

    public function __construct()
    {
        $this->modelo = new Persona();
    }

    public function listar(): void
    {
        $this->responder(['ok' => true, 'datos' => $this->modelo->listar()]);
    }

    // Devuelve la lista de municipios leyendo el archivo data/municipios.php
    public function municipios(): void
    {
        // "require" devuelve lo que el archivo tenga en su "return"
       require __DIR__ . '/../data/municipios.php';
       $this->responder(['ok' => true, 'datos' => array_values($citiesOrder)]);
    }

    public function guardar(): void
    {
        // trim quita espacios sobrantes; ?? '' evita error si el campo no llegó
        $nombre    = trim($_POST['nombre'] ?? '');
        $cedula    = trim($_POST['cedula'] ?? '');
        $correo    = trim($_POST['correo'] ?? '');
        $telefono  = trim($_POST['telefono'] ?? '');
        $municipio = trim($_POST['municipio'] ?? '');

        if ($nombre === '' || $cedula === '' || $correo === '' || $telefono === '' || $municipio === '') {
            $this->responder(['ok' => false, 'mensaje' => 'Todos los campos son obligatorios'], 422);
        }
        // ctype_digit = solo números
        if (!ctype_digit($cedula)) {
            $this->responder(['ok' => false, 'mensaje' => 'La cédula solo debe tener números'], 422);
        }
        if (!filter_var($correo, FILTER_VALIDATE_EMAIL)) {
            $this->responder(['ok' => false, 'mensaje' => 'Correo inválido'], 422);
        }
        // Verificamos que el municipio enviado exista de verdad en la lista
        require __DIR__ . '/../data/municipios.php'; // crea $citiesOrder
        if (!in_array($municipio, $citiesOrder, true)) {
            $this->responder(['ok' => false, 'mensaje' => 'Municipio inválido'], 422);
        }

        $this->modelo->guardar($nombre, $cedula, $correo, $telefono, $municipio);
        $this->responder(['ok' => true, 'mensaje' => 'Guardado correctamente']);
    }

    // Manda la respuesta en JSON y corta la ejecución (exit)
    private function responder(array $data, int $codigo = 200): void
    {
        http_response_code($codigo);
        header('Content-Type: application/json; charset=utf-8');
        echo json_encode($data);
        exit;
    }
}