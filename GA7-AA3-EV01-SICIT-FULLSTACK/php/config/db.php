<?php
declare(strict_types=1);

/**
 * Conexión central a MySQL mediante PDO.
 * Credenciales predeterminadas de XAMPP: root sin contraseña.
 * Ajustar estos valores si el entorno local usa otra configuración.
 */
function obtenerConexion(): PDO
{
    $host = 'localhost';
    $baseDatos = 'sicit';
    $usuario = 'root';
    $contrasena = '';

    $dsn = "mysql:host={$host};dbname={$baseDatos};charset=utf8mb4";

    $opciones = [
        PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
        PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
        PDO::ATTR_EMULATE_PREPARES => false,
    ];

    return new PDO($dsn, $usuario, $contrasena, $opciones);
}
