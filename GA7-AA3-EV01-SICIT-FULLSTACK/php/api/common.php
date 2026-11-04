<?php
declare(strict_types=1);

require_once __DIR__ . '/../config/db.php';

header('Content-Type: application/json; charset=utf-8');

function responder(bool $success, string $message, mixed $data = null, int $status = 200): never
{
    http_response_code($status);
    echo json_encode([
        'success' => $success,
        'message' => $message,
        'data' => $data,
    ], JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES);
    exit;
}

function leerJson(): array
{
    $contenido = file_get_contents('php://input');
    if ($contenido === false || trim($contenido) === '') {
        return [];
    }

    $datos = json_decode($contenido, true);
    if (!is_array($datos)) {
        responder(false, 'El cuerpo de la solicitud no contiene JSON válido.', null, 400);
    }
    return $datos;
}

function requerirCampos(array $datos, array $campos): void
{
    $faltantes = [];
    foreach ($campos as $campo) {
        if (!array_key_exists($campo, $datos) || trim((string)($datos[$campo] ?? '')) === '') {
            $faltantes[] = $campo;
        }
    }

    if ($faltantes) {
        responder(false, 'Complete los campos obligatorios: ' . implode(', ', $faltantes) . '.', null, 422);
    }
}

function enteroPositivo(mixed $valor, string $nombre): int
{
    $entero = filter_var($valor, FILTER_VALIDATE_INT, ['options' => ['min_range' => 1]]);
    if ($entero === false) {
        responder(false, "El campo {$nombre} debe contener un identificador válido.", null, 422);
    }
    return $entero;
}

function nuloOEnteroPositivo(mixed $valor, string $nombre): ?int
{
    if ($valor === null || $valor === '') return null;
    return enteroPositivo($valor, $nombre);
}

function obtenerIdQuery(): int
{
    return enteroPositivo($_GET['id'] ?? null, 'id');
}

function ejecutarSeguro(callable $operacion): never
{
    try {
        $operacion();
    } catch (PDOException $error) {
        $codigo = (int)($error->errorInfo[1] ?? 0);

        if ($codigo === 1062) {
            responder(false, 'Ya existe un registro con uno de los valores que deben ser únicos.', null, 409);
        }

        if (in_array($codigo, [1451, 1452], true)) {
            responder(false, 'La operación no puede completarse porque existen datos relacionados.', null, 409);
        }

        responder(false, 'No fue posible completar la operación en la base de datos.', null, 500);
    } catch (Throwable $error) {
        responder(false, 'Ocurrió un error inesperado al procesar la solicitud.', null, 500);
    }
}
