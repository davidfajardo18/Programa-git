<?php
declare(strict_types=1);
require_once __DIR__ . '/common.php';

ejecutarSeguro(function (): void {
    $pdo = obtenerConexion();
    $metodo = $_SERVER['REQUEST_METHOD'];

    if ($metodo === 'GET') {
        $sql = 'SELECT id_consultorio AS id, numero, ubicacion, estado FROM consultorios';
        if (isset($_GET['id'])) {
            $id = obtenerIdQuery();
            $stmt = $pdo->prepare($sql . ' WHERE id_consultorio = :id');
            $stmt->execute([':id' => $id]);
            $registro = $stmt->fetch();
            if (!$registro) responder(false, 'Consultorio no encontrado.', null, 404);
            responder(true, 'Consultorio obtenido correctamente.', $registro);
        }
        $registros = $pdo->query($sql . ' ORDER BY numero')->fetchAll();
        responder(true, 'Consultorios obtenidos correctamente.', $registros);
    }

    $datos = leerJson();

    if ($metodo === 'POST') {
        requerirCampos($datos, ['numero', 'ubicacion', 'estado']);
        if (!in_array($datos['estado'], ['Disponible', 'Ocupado', 'Inactivo'], true)) {
            responder(false, 'El estado del consultorio no es válido.', null, 422);
        }
        $stmt = $pdo->prepare('INSERT INTO consultorios (numero, ubicacion, estado)
                              VALUES (:numero, :ubicacion, :estado)');
        $stmt->execute([
            ':numero' => trim((string)$datos['numero']),
            ':ubicacion' => trim((string)$datos['ubicacion']),
            ':estado' => $datos['estado'],
        ]);
        responder(true, 'Consultorio creado correctamente.', ['id' => (int)$pdo->lastInsertId()], 201);
    }

    if ($metodo === 'PUT') {
        requerirCampos($datos, ['id', 'numero', 'ubicacion', 'estado']);
        $id = enteroPositivo($datos['id'], 'id');
        if (!in_array($datos['estado'], ['Disponible', 'Ocupado', 'Inactivo'], true)) {
            responder(false, 'El estado del consultorio no es válido.', null, 422);
        }
        $stmt = $pdo->prepare('UPDATE consultorios SET numero=:numero, ubicacion=:ubicacion, estado=:estado
                              WHERE id_consultorio=:id');
        $stmt->execute([
            ':numero' => trim((string)$datos['numero']),
            ':ubicacion' => trim((string)$datos['ubicacion']),
            ':estado' => $datos['estado'],
            ':id' => $id,
        ]);
        responder(true, 'Consultorio actualizado correctamente.');
    }

    if ($metodo === 'DELETE') {
        $id = obtenerIdQuery();
        $stmt = $pdo->prepare('DELETE FROM consultorios WHERE id_consultorio = :id');
        $stmt->execute([':id' => $id]);
        if ($stmt->rowCount() === 0) responder(false, 'Consultorio no encontrado.', null, 404);
        responder(true, 'Consultorio eliminado correctamente.');
    }

    responder(false, 'Método HTTP no permitido.', null, 405);
});
