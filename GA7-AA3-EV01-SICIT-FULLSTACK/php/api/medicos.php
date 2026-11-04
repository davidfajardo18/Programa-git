<?php
declare(strict_types=1);
require_once __DIR__ . '/common.php';

ejecutarSeguro(function (): void {
    $pdo = obtenerConexion();
    $metodo = $_SERVER['REQUEST_METHOD'];

    if ($metodo === 'GET') {
        $sql = 'SELECT m.id_medico AS id, m.identificacion, m.nombres, m.apellidos,
                       m.especialidad, m.telefono, m.correo, m.usuario_id,
                       COALESCE(u.nombre_usuario, "Sin asociación") AS usuario_nombre
                FROM medicos m
                LEFT JOIN usuarios u ON u.id_usuario = m.usuario_id';
        if (isset($_GET['id'])) {
            $id = obtenerIdQuery();
            $stmt = $pdo->prepare($sql . ' WHERE m.id_medico = :id');
            $stmt->execute([':id' => $id]);
            $registro = $stmt->fetch();
            if (!$registro) responder(false, 'Médico no encontrado.', null, 404);
            responder(true, 'Médico obtenido correctamente.', $registro);
        }
        $registros = $pdo->query($sql . ' ORDER BY m.apellidos, m.nombres')->fetchAll();
        responder(true, 'Médicos obtenidos correctamente.', $registros);
    }

    $datos = leerJson();

    if ($metodo === 'POST') {
        requerirCampos($datos, ['identificacion', 'nombres', 'apellidos', 'especialidad', 'telefono', 'correo']);
        if (!filter_var($datos['correo'], FILTER_VALIDATE_EMAIL)) {
            responder(false, 'El correo electrónico no tiene un formato válido.', null, 422);
        }
        $usuarioId = nuloOEnteroPositivo($datos['usuario_id'] ?? null, 'usuario_id');

        $stmt = $pdo->prepare('INSERT INTO medicos
            (identificacion, nombres, apellidos, especialidad, telefono, correo, usuario_id)
            VALUES (:identificacion, :nombres, :apellidos, :especialidad, :telefono, :correo, :usuario_id)');
        $stmt->execute([
            ':identificacion' => trim((string)$datos['identificacion']),
            ':nombres' => trim((string)$datos['nombres']),
            ':apellidos' => trim((string)$datos['apellidos']),
            ':especialidad' => trim((string)$datos['especialidad']),
            ':telefono' => trim((string)$datos['telefono']),
            ':correo' => trim((string)$datos['correo']),
            ':usuario_id' => $usuarioId,
        ]);
        responder(true, 'Médico creado correctamente.', ['id' => (int)$pdo->lastInsertId()], 201);
    }

    if ($metodo === 'PUT') {
        requerirCampos($datos, ['id', 'identificacion', 'nombres', 'apellidos', 'especialidad', 'telefono', 'correo']);
        $id = enteroPositivo($datos['id'], 'id');
        if (!filter_var($datos['correo'], FILTER_VALIDATE_EMAIL)) {
            responder(false, 'El correo electrónico no tiene un formato válido.', null, 422);
        }
        $usuarioId = nuloOEnteroPositivo($datos['usuario_id'] ?? null, 'usuario_id');

        $stmt = $pdo->prepare('UPDATE medicos SET
            identificacion=:identificacion, nombres=:nombres, apellidos=:apellidos,
            especialidad=:especialidad, telefono=:telefono, correo=:correo, usuario_id=:usuario_id
            WHERE id_medico=:id');
        $stmt->execute([
            ':identificacion' => trim((string)$datos['identificacion']),
            ':nombres' => trim((string)$datos['nombres']),
            ':apellidos' => trim((string)$datos['apellidos']),
            ':especialidad' => trim((string)$datos['especialidad']),
            ':telefono' => trim((string)$datos['telefono']),
            ':correo' => trim((string)$datos['correo']),
            ':usuario_id' => $usuarioId,
            ':id' => $id,
        ]);
        responder(true, 'Médico actualizado correctamente.');
    }

    if ($metodo === 'DELETE') {
        $id = obtenerIdQuery();
        $stmt = $pdo->prepare('DELETE FROM medicos WHERE id_medico = :id');
        $stmt->execute([':id' => $id]);
        if ($stmt->rowCount() === 0) responder(false, 'Médico no encontrado.', null, 404);
        responder(true, 'Médico eliminado correctamente.');
    }

    responder(false, 'Método HTTP no permitido.', null, 405);
});
