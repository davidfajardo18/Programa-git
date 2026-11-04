<?php
declare(strict_types=1);
require_once __DIR__ . '/common.php';

ejecutarSeguro(function (): void {
    $pdo = obtenerConexion();
    $metodo = $_SERVER['REQUEST_METHOD'];

    if ($metodo === 'GET') {
        $sql = 'SELECT u.id_usuario AS id, u.nombre_usuario, u.rol_id, r.nombre AS rol_nombre, u.estado
                FROM usuarios u
                INNER JOIN roles r ON r.id_rol = u.rol_id';
        if (isset($_GET['id'])) {
            $id = obtenerIdQuery();
            $stmt = $pdo->prepare($sql . ' WHERE u.id_usuario = :id');
            $stmt->execute([':id' => $id]);
            $registro = $stmt->fetch();
            if (!$registro) responder(false, 'Usuario no encontrado.', null, 404);
            responder(true, 'Usuario obtenido correctamente.', $registro);
        }
        $registros = $pdo->query($sql . ' ORDER BY u.nombre_usuario')->fetchAll();
        responder(true, 'Usuarios obtenidos correctamente.', $registros);
    }

    $datos = leerJson();

    if ($metodo === 'POST') {
        requerirCampos($datos, ['nombre_usuario', 'contrasena', 'rol_id', 'estado']);
        if (strlen((string)$datos['contrasena']) < 6) {
            responder(false, 'La contraseña debe tener al menos 6 caracteres.', null, 422);
        }
        $rolId = enteroPositivo($datos['rol_id'], 'rol_id');
        $estado = in_array((string)$datos['estado'], ['0', '1'], true) ? (int)$datos['estado'] : null;
        if ($estado === null) responder(false, 'El estado del usuario no es válido.', null, 422);

        $stmt = $pdo->prepare('INSERT INTO usuarios (nombre_usuario, contrasena_hash, rol_id, estado)
                              VALUES (:nombre, :hash, :rol_id, :estado)');
        $stmt->execute([
            ':nombre' => trim((string)$datos['nombre_usuario']),
            ':hash' => password_hash((string)$datos['contrasena'], PASSWORD_DEFAULT),
            ':rol_id' => $rolId,
            ':estado' => $estado,
        ]);
        responder(true, 'Usuario creado correctamente.', ['id' => (int)$pdo->lastInsertId()], 201);
    }

    if ($metodo === 'PUT') {
        requerirCampos($datos, ['id', 'nombre_usuario', 'rol_id', 'estado']);
        $id = enteroPositivo($datos['id'], 'id');
        $rolId = enteroPositivo($datos['rol_id'], 'rol_id');
        $estado = in_array((string)$datos['estado'], ['0', '1'], true) ? (int)$datos['estado'] : null;
        if ($estado === null) responder(false, 'El estado del usuario no es válido.', null, 422);

        if (!empty($datos['contrasena'])) {
            if (strlen((string)$datos['contrasena']) < 6) {
                responder(false, 'La contraseña debe tener al menos 6 caracteres.', null, 422);
            }
            $stmt = $pdo->prepare('UPDATE usuarios
                                  SET nombre_usuario = :nombre, contrasena_hash = :hash, rol_id = :rol_id, estado = :estado
                                  WHERE id_usuario = :id');
            $stmt->execute([
                ':nombre' => trim((string)$datos['nombre_usuario']),
                ':hash' => password_hash((string)$datos['contrasena'], PASSWORD_DEFAULT),
                ':rol_id' => $rolId,
                ':estado' => $estado,
                ':id' => $id,
            ]);
        } else {
            $stmt = $pdo->prepare('UPDATE usuarios
                                  SET nombre_usuario = :nombre, rol_id = :rol_id, estado = :estado
                                  WHERE id_usuario = :id');
            $stmt->execute([
                ':nombre' => trim((string)$datos['nombre_usuario']),
                ':rol_id' => $rolId,
                ':estado' => $estado,
                ':id' => $id,
            ]);
        }
        responder(true, 'Usuario actualizado correctamente.');
    }

    if ($metodo === 'DELETE') {
        $id = obtenerIdQuery();
        $stmt = $pdo->prepare('DELETE FROM usuarios WHERE id_usuario = :id');
        $stmt->execute([':id' => $id]);
        if ($stmt->rowCount() === 0) responder(false, 'Usuario no encontrado.', null, 404);
        responder(true, 'Usuario eliminado correctamente.');
    }

    responder(false, 'Método HTTP no permitido.', null, 405);
});
