<?php
require_once __DIR__ . '/util.php';
require_once __DIR__ . '/../clases/Medico.php';

$medico = new Medico();
$mensaje = '';
$tipoMensaje = 'ok';
$registro = null;
$lista = [];

try {
    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        $accion = $_POST['accion'] ?? 'crear';
        $medico->crearMedico(
            trim($_POST['tipoDocumento'] ?? ''),
            trim($_POST['numeroDocumento'] ?? ''),
            trim($_POST['nombres'] ?? ''),
            trim($_POST['apellidos'] ?? ''),
            trim($_POST['especialidad'] ?? ''),
            trim($_POST['telefono'] ?? ''),
            trim($_POST['correo'] ?? '')
        );

        if ($accion === 'actualizar') {
            $medico->setIdMedico((int)($_POST['idMedico'] ?? 0));
            $medico->actualizarMedico();
            $mensaje = 'Médico actualizado correctamente.';
        } else {
            $medico->agregarMedico();
            $mensaje = 'Médico registrado correctamente.';
        }
    }

    if (isset($_GET['eliminar'])) {
        $medico->eliminarMedico((int)$_GET['eliminar']);
        $mensaje = 'Médico eliminado correctamente.';
    }

    if (isset($_GET['editar'])) {
        $registro = $medico->consultarMedico((int)$_GET['editar']);
    }

    $lista = $medico->listarMedicos();
} catch (Throwable $error) {
    $mensaje = $error->getMessage();
    $tipoMensaje = 'error';
}

encabezado('Médicos', 'medicos');
?>
<h1>Médicos</h1>
<?php if ($mensaje): ?><div class="alert <?= e($tipoMensaje) ?>"><?= e($mensaje) ?></div><?php endif; ?>
<form class="form-card" method="post">
  <input type="hidden" name="accion" value="<?= $registro ? 'actualizar' : 'crear' ?>">
  <input type="hidden" name="idMedico" value="<?= e($registro['id_medico'] ?? '') ?>">
  <div class="grid">
    <label>Tipo de documento *<input name="tipoDocumento" required value="<?= e($registro['tipo_documento'] ?? '') ?>"></label>
    <label>Número de documento *<input name="numeroDocumento" required value="<?= e($registro['numero_documento'] ?? '') ?>"></label>
    <label>Nombres *<input name="nombres" required value="<?= e($registro['nombres'] ?? '') ?>"></label>
    <label>Apellidos *<input name="apellidos" required value="<?= e($registro['apellidos'] ?? '') ?>"></label>
    <label>Especialidad *<input name="especialidad" required value="<?= e($registro['especialidad'] ?? '') ?>"></label>
    <label>Teléfono<input name="telefono" value="<?= e($registro['telefono'] ?? '') ?>"></label>
    <label class="wide">Correo electrónico<input type="email" name="correo" value="<?= e($registro['correo'] ?? '') ?>"></label>
  </div>
  <div class="actions"><button class="btn" type="submit"><?= $registro ? 'Actualizar' : 'Guardar' ?></button><a class="btn secondary" href="medicos.php">Limpiar</a></div>
</form>
<table><thead><tr><th>ID</th><th>Documento</th><th>Nombre</th><th>Especialidad</th><th>Acciones</th></tr></thead><tbody>
<?php foreach ($lista as $fila): ?><tr><td><?= e($fila['id_medico']) ?></td><td><?= e($fila['numero_documento']) ?></td><td><?= e($fila['nombres'].' '.$fila['apellidos']) ?></td><td><?= e($fila['especialidad']) ?></td><td><a href="?editar=<?= e($fila['id_medico']) ?>">Editar</a> <a class="delete" href="?eliminar=<?= e($fila['id_medico']) ?>">Eliminar</a></td></tr><?php endforeach; ?>
</tbody></table>
<?php pie(); ?>
