<?php
declare(strict_types=1);
require_once __DIR__ . '/common.php';

ejecutarSeguro(function (): void {
    $pdo = obtenerConexion();
    $metodo = $_SERVER['REQUEST_METHOD'];

    $selectBase = 'SELECT c.id_cita AS id, c.paciente_id, c.medico_id, c.consultorio_id,
                          c.fecha, c.hora, c.motivo, c.estado,
                          CONCAT(p.nombres, " ", p.apellidos) AS paciente_nombre,
                          CONCAT(m.nombres, " ", m.apellidos) AS medico_nombre,
                          co.numero AS consultorio_numero
                   FROM citas c
                   INNER JOIN pacientes p ON p.id_paciente = c.paciente_id
                   INNER JOIN medicos m ON m.id_medico = c.medico_id
                   INNER JOIN consultorios co ON co.id_consultorio = c.consultorio_id';

    if ($metodo === 'GET') {
        if (isset($_GET['id'])) {
            $id = obtenerIdQuery();
            $stmt = $pdo->prepare($selectBase . ' WHERE c.id_cita = :id');
            $stmt->execute([':id' => $id]);
            $registro = $stmt->fetch();
            if (!$registro) responder(false, 'Cita no encontrada.', null, 404);
            responder(true, 'Cita obtenida correctamente.', $registro);
        }
        $registros = $pdo->query($selectBase . ' ORDER BY c.fecha DESC, c.hora DESC')->fetchAll();
        responder(true, 'Citas obtenidas correctamente.', $registros);
    }

    $datos = leerJson();

    if (in_array($metodo, ['POST', 'PUT'], true)) {
        $requeridos = ['paciente_id', 'medico_id', 'consultorio_id', 'fecha', 'hora', 'motivo', 'estado'];
        if ($metodo === 'PUT') array_unshift($requeridos, 'id');
        requerirCampos($datos, $requeridos);

        $pacienteId = enteroPositivo($datos['paciente_id'], 'paciente_id');
        $medicoId = enteroPositivo($datos['medico_id'], 'medico_id');
        $consultorioId = enteroPositivo($datos['consultorio_id'], 'consultorio_id');
        if (!in_array($datos['estado'], ['Programada', 'Confirmada', 'Atendida', 'Cancelada'], true)) {
            responder(false, 'El estado de la cita no es válido.', null, 422);
        }

        if ($metodo === 'POST') {
            $stmt = $pdo->prepare('INSERT INTO citas
                (paciente_id, medico_id, consultorio_id, fecha, hora, motivo, estado)
                VALUES (:paciente, :medico, :consultorio, :fecha, :hora, :motivo, :estado)');
            $stmt->execute([
                ':paciente' => $pacienteId,
                ':medico' => $medicoId,
                ':consultorio' => $consultorioId,
                ':fecha' => $datos['fecha'],
                ':hora' => $datos['hora'],
                ':motivo' => trim((string)$datos['motivo']),
                ':estado' => $datos['estado'],
            ]);
            responder(true, 'Cita creada correctamente.', ['id' => (int)$pdo->lastInsertId()], 201);
        }

        $id = enteroPositivo($datos['id'], 'id');
        $stmt = $pdo->prepare('UPDATE citas SET
            paciente_id=:paciente, medico_id=:medico, consultorio_id=:consultorio,
            fecha=:fecha, hora=:hora, motivo=:motivo, estado=:estado
            WHERE id_cita=:id');
        $stmt->execute([
            ':paciente' => $pacienteId,
            ':medico' => $medicoId,
            ':consultorio' => $consultorioId,
            ':fecha' => $datos['fecha'],
            ':hora' => $datos['hora'],
            ':motivo' => trim((string)$datos['motivo']),
            ':estado' => $datos['estado'],
            ':id' => $id,
        ]);
        responder(true, 'Cita actualizada correctamente.');
    }

    if ($metodo === 'DELETE') {
        $id = obtenerIdQuery();
        $stmt = $pdo->prepare('DELETE FROM citas WHERE id_cita = :id');
        $stmt->execute([':id' => $id]);
        if ($stmt->rowCount() === 0) responder(false, 'Cita no encontrada.', null, 404);
        responder(true, 'Cita eliminada correctamente.');
    }

    responder(false, 'Método HTTP no permitido.', null, 405);
});
