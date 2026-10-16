<?php
require_once __DIR__ . '/../config/Databases.php';

class Persona
{
    private PDO $conn; // aquí guardamos la conexión a la base de datos

    public function __construct()
    {
        // Database->conectar() devuelve la conexión PDO ya lista
        $this->conn = (new Database())->conectar();
    }

    public function guardar(string $nombre, string $cedula, string $correo, string $telefono, string $municipio): bool
    {
        // Los ? se reemplazan por los valores del array en el mismo orden.
        // Así evitamos la inyección SQL (nunca pegamos texto del usuario directo en la consulta).
        $sql = "INSERT INTO personas (nombre, cedula, correo, telefono, municipio) VALUES (?, ?, ?, ?, ?)";
        return $this->conn->prepare($sql)->execute([$nombre, $cedula, $correo, $telefono, $municipio]);
    }

    public function listar(): array
    {
        return $this->conn->query("SELECT * FROM personas ORDER BY id DESC")->fetchAll();
    }
}