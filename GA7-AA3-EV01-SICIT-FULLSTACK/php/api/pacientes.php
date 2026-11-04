<?php
declare(strict_types=1);
require_once __DIR__ . '/common.php';

ejecutarSeguro(function (): void {
    $pdo = obtenerConexion();
    $metodo = $_SERVER['REQUEST_METHOD'];

    if ($metodo === 'GET') {
        $sql = 'SELECT p.id_paciente AS id, p.identificacion, p.nombres, p.apellidos,
                       p.fecha_nacimiento, p.sexo, p.usuario_id,
                       COALESCE(u.nombre_usuario, "Sin asociación") AS usuario_nombre
                FROM pacientes p
                LEFT JOIN usuarios u ON u.id_usuario = p.usuario_id';
        if (isset($_GET['id'])) {
            $id = obtenerIdQuery();
            $stmt = $pdo->prepare($sql . ' WHERE p.id_paciente = :id');
            $stmt->execute([':id' => $id]);
            $registro = $stmt->fetch();
            if (!$registro) responder(false, 'Paciente no encontrado.', null, 404);
            responder(true, 'Paciente obtenido correctamente.', $registro);
        }
        $registros = $pdo->query($sql . ' ORDER BY p.apellidos, p.nombres')->fetchAll();
        responder(true, 'Pacientes obtenidos correctamente.', $registros);
    }

    $datos = leerJson();

    if ($metodo === 'POST') {
        requerirCampos($datos, ['identificacion', 'nombres', 'apellidos', 'fecha_nacimiento', 'sexo']);
        if (!in_array($datos['sexo'], ['Femenino', 'Masculino', 'Otro'], true)) {
            responder(false, 'El valor del sexo no es válido.', null, 422);
        }
        $usuarioId = nuloOEnteroPositivo($datos['usuario_id'] ?? null, 'usuario_id');

        $stmt = $pdo->prepare('INSERT INTO pacientes
            (identificacion, nombres, apellidos, fecha_nacimiento, sexo, usuario_id)
            VALUES (:identificacion, :nombres, :apellidos, :fecha, :sexo, :usuario_id)');
        $stmt->execute([
            ':identificacion' => trim((string)$datos['identificacion']),
            ':nombres' => trim((string)$datos['nombres']),
            ':apellidos' => trim((string)$datos['apellidos']),
            ':fecha' => $datos['fecha_nacimiento'],
            ':sexo' => $datos['sexo'],
            ':usuario_id' => $usuarioId,
        ]);
        responder(true, 'Paciente creado correctamente.', ['id' => (int)$pdo->lastInsertId()], 201);
    }

    if ($metodo === 'PUT') {
        requerirCampos($datos, ['id', 'identificacion', 'nombres', 'apellidos', 'fecha_nacimiento', 'sexo']);
        $id = enteroPositivo($datos['id'], 'id');
        if (!in_array($datos['sexo'], ['Femenino', 'Masculino', 'Otro'], true)) {
            responder(false, 'El valor del sexo no es válido.', null, 422);
        }
        $usuarioId = nuloOEnteroPositivo($datos['usuario_id'] ?? null, 'usuario_id');

        $stmt = $pdo->prepare('UPDATE pacientes
            SET identificacion=:identificacion, nombres=:nombres, apellidos=:apellidos,
                fecha_nacimiento=:fecha, sexo=:sexo, usuario_id=:usuario_id
            WHERE id_paciente=:id');
        $stmt->execute([
            ':identificacion' => trim((string)$datos['identificacion']),
            ':nombres' => trim((string)$datos['nombres']),
            ':apellidos' => trim((string)$datos['apellidos']),
            ':fecha' => $datos['fecha_nacimiento'],
            ':sexo' => $datos['sexo'],
            ':usuario_id' => $usuarioId,
            ':id' => $id,
        ]);
        responder(true, 'Paciente actualizado correctamente.');
    }

    if ($metodo === 'DELETE') {
        $id = obtenerIdQuery();
        $stmt = $pdo->prepare('DELETE FROM pacientes WHERE id_paciente = :id');
        $stmt->execute([':id' => $id]);
        if ($stmt->rowCount() === 0) responder(false, 'Paciente no encontrado.', null, 404);
        responder(true, 'Paciente eliminado correctamente.');
    }

    responder(false, 'Método HTTP no permitido.', null, 405);
});
