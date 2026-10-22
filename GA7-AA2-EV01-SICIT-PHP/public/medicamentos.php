<?php
require_once __DIR__ . '/util.php';
require_once __DIR__ . '/../clases/Medicamento.php';

$medicamento = new Medicamento();
$mensaje = '';
$registro = null;
$lista = [];

try {
    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        $medicamento->crearMedicamento(
            trim($_POST['nombre'] ?? ''),
            trim($_POST['descripcion'] ?? ''),
            trim($_POST['presentacion'] ?? ''),
            max(0, (int)($_POST['stock'] ?? 0))
        );
        if (($_POST['accion'] ?? 'crear') === 'actualizar') {
            $medicamento->setIdMedicamento((int)($_POST['idMedicamento'] ?? 0));
            $medicamento->actualizarMedicamento();
            $mensaje = 'Medicamento actualizado correctamente.';
        } else {
            $medicamento->agregarMedicamento();
            $mensaje = 'Medicamento registrado correctamente.';
        }
    }
    if (isset($_GET['eliminar'])) {
        $medicamento->eliminarMedicamento((int)$_GET['eliminar']);
        $mensaje = 'Medicamento eliminado correctamente.';
    }
    if (isset($_GET['editar'])) {
        $registro = $medicamento->consultarMedicamento((int)$_GET['editar']);
    }
    $lista = $medicamento->listarMedicamentos();
} catch (Throwable $error) {
    $mensaje = $error->getMessage();
}

encabezado('Medicamentos', 'medicamentos');
?>
<h1>Medicamentos</h1>
<?php if ($mensaje): ?><div class="alert ok"><?= e($mensaje) ?></div><?php endif; ?>
<form class="form-card" method="post">
  <input type="hidden" name="accion" value="<?= $registro ? 'actualizar' : 'crear' ?>">
  <input type="hidden" name="idMedicamento" value="<?= e($registro['id_medicamento'] ?? '') ?>">
  <div class="grid">
    <label>Nombre *<input name="nombre" required value="<?= e($registro['nombre'] ?? '') ?>"></label>
    <label>Presentación<input name="presentacion" value="<?= e($registro['presentacion'] ?? '') ?>"></label>
    <label>Stock *<input type="number" min="0" name="stock" required value="<?= e($registro['stock'] ?? '0') ?>"></label>
    <label class="wide">Descripción<textarea name="descripcion"><?= e($registro['descripcion'] ?? '') ?></textarea></label>
  </div>
  <div class="actions"><button class="btn" type="submit"><?= $registro ? 'Actualizar' : 'Guardar' ?></button><a class="btn secondary" href="medicamentos.php">Limpiar</a></div>
</form>
<table><thead><tr><th>ID</th><th>Nombre</th><th>Presentación</th><th>Stock</th><th>Acciones</th></tr></thead><tbody>
<?php foreach ($lista as $fila): ?><tr><td><?= e($fila['id_medicamento']) ?></td><td><?= e($fila['nombre']) ?></td><td><?= e($fila['presentacion']) ?></td><td><?= e($fila['stock']) ?></td><td><a href="?editar=<?= e($fila['id_medicamento']) ?>">Editar</a> <a class="delete" href="?eliminar=<?= e($fila['id_medicamento']) ?>">Eliminar</a></td></tr><?php endforeach; ?>
</tbody></table>
<?php pie(); ?>
