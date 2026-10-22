<?php
function Conectarse(): mysqli
{
    $conexion = new mysqli('localhost', 'root', '', 'centromedico');
    $conexion->set_charset('utf8mb4');

    if ($conexion->connect_errno) {
        throw new RuntimeException(
            'Error de conexión a la base de datos: ' . $conexion->connect_error
        );
    }

    return $conexion;
}
