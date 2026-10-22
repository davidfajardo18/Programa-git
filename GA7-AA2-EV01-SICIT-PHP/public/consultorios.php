<?php
require_once __DIR__ . '/util.php';
require_once __DIR__ . '/../clases/Consultorio.php';

$consultorio = new Consultorio();
$mensaje = '';
$registro = null;
$lista = [];

try {
    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        $consultorio->crearConsultorio(
            trim($_POST['numeroConsultorio'] ?? ''),
            trim($_POST['ubicacion'] ?? ''),
            trim($_POST['estado'] ?? 'Disponible')
        );
        if (($_POST['accion'] ?? 'crear') === 'actualizar') {
            $consultorio->setIdConsultorio((int)($_POST['idConsultorio'] ?? 0));
            $consultorio->actualizarConsultorio();
            $mensaje = 'Consultorio actualizado correctamente.';
        } else {
            $consultorio->agregarConsultorio();
            $mensaje = 'Consultorio registrado correctamente.';
        }
    }
    if (isset($_GET['eliminar'])) {
        $consultorio->eliminarConsultorio((int)$_GET['eliminar']);
        $mensaje = 'Consultorio eliminado correctamente.';
    }
    if (isset($_GET['editar'])) {
        $registro = $consultorio->consultarConsultorio((int)$_GET['editar']);
    }
    $lista = $consultorio->listarConsultorios();
} catch (Throwable $error) {
    $mensaje = $error->getMessage();
}

encabezado('Consultorios', 'consultorios');
?>
<h1>Consultorios</h1>
<?php if ($mensaje): ?><div class="alert ok"><?= e($mensaje) ?></div><?php endif; ?>
<form class="form-card" method="post">
  <input type="hidden" name="accion" value="<?= $registro ? 'actualizar' : 'crear' ?>">
  <input type="hidden" name="idConsultorio" value="<?= e($registro['id_consultorio'] ?? '') ?>">
  <div class="grid">
    <label>Número *<input name="numeroConsultorio" required value="<?= e($registro['numero_consultorio'] ?? '') ?>"></label>
    <label>Ubicación<input name="ubicacion" value="<?= e($registro['ubicacion'] ?? '') ?>"></label>
    <label>Estado *<select name="estado"><option>Disponible</option><option>Ocupado</option><option>Inactivo</option></select></label>
  </div>
  <div class="actions"><button class="btn" type="submit"><?= $registro ? 'Actualizar' : 'Guardar' ?></button><a class="btn secondary" href="consultorios.php">Limpiar</a></div>
</form>
<table><thead><tr><th>ID</th><th>Número</th><th>Ubicación</th><th>Estado</th><th>Acciones</th></tr></thead><tbody>
<?php foreach ($lista as $fila): ?><tr><td><?= e($fila['id_consultorio']) ?></td><td><?= e($fila['numero_consultorio']) ?></td><td><?= e($fila['ubicacion']) ?></td><td><?= e($fila['estado']) ?></td><td><a href="?editar=<?= e($fila['id_consultorio']) ?>">Editar</a> <a class="delete" href="?eliminar=<?= e($fila['id_consultorio']) ?>">Eliminar</a></td></tr><?php endforeach; ?>
</tbody></table>
<?php pie(); ?>
