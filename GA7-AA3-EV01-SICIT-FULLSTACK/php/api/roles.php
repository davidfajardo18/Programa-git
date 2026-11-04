<?php
declare(strict_types=1);
require_once __DIR__ . '/common.php';

ejecutarSeguro(function (): void {
    $pdo = obtenerConexion();
    $metodo = $_SERVER['REQUEST_METHOD'];

    if ($metodo === 'GET') {
        if (isset($_GET['id'])) {
            $id = obtenerIdQuery();
            $stmt = $pdo->prepare('SELECT id_rol AS id, nombre FROM roles WHERE id_rol = :id');
            $stmt->execute([':id' => $id]);
            $registro = $stmt->fetch();
            if (!$registro) responder(false, 'Rol no encontrado.', null, 404);
            responder(true, 'Rol obtenido correctamente.', $registro);
        }

        $registros = $pdo->query('SELECT id_rol AS id, nombre FROM roles ORDER BY nombre')->fetchAll();
        responder(true, 'Roles obtenidos correctamente.', $registros);
    }

    $datos = leerJson();

    if ($metodo === 'POST') {
        requerirCampos($datos, ['nombre']);
        $stmt = $pdo->prepare('INSERT INTO roles (nombre) VALUES (:nombre)');
        $stmt->execute([':nombre' => trim((string)$datos['nombre'])]);
        responder(true, 'Rol creado correctamente.', ['id' => (int)$pdo->lastInsertId()], 201);
    }

    if ($metodo === 'PUT') {
        requerirCampos($datos, ['id', 'nombre']);
        $id = enteroPositivo($datos['id'], 'id');
        $stmt = $pdo->prepare('UPDATE roles SET nombre = :nombre WHERE id_rol = :id');
        $stmt->execute([':nombre' => trim((string)$datos['nombre']), ':id' => $id]);
        if ($stmt->rowCount() === 0) {
            $check = $pdo->prepare('SELECT id_rol FROM roles WHERE id_rol = :id');
            $check->execute([':id' => $id]);
            if (!$check->fetch()) responder(false, 'Rol no encontrado.', null, 404);
        }
        responder(true, 'Rol actualizado correctamente.');
    }

    if ($metodo === 'DELETE') {
        $id = obtenerIdQuery();
        $stmt = $pdo->prepare('DELETE FROM roles WHERE id_rol = :id');
        $stmt->execute([':id' => $id]);
        if ($stmt->rowCount() === 0) responder(false, 'Rol no encontrado.', null, 404);
        responder(true, 'Rol eliminado correctamente.');
    }

    responder(false, 'Método HTTP no permitido.', null, 405);
});
