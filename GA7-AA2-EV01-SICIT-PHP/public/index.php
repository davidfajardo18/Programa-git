<?php
require_once __DIR__ . '/util.php';
encabezado('Inicio', 'inicio');
?>
<section class="hero">
  <h1>SICIT · Gestión clínica</h1>
  <p class="muted">Módulo académico desarrollado en PHP orientado a objetos y MySQL.</p>
  <div class="cards">
    <article class="card"><h2>Médicos</h2><p>Inserción, consulta, actualización y eliminación de médicos.</p><a href="medicos.php">Abrir módulo →</a></article>
    <article class="card"><h2>Consultorios</h2><p>Administración de número, ubicación y estado.</p><a href="consultorios.php">Abrir módulo →</a></article>
    <article class="card"><h2>Medicamentos</h2><p>Gestión de nombre, descripción, presentación y stock.</p><a href="medicamentos.php">Abrir módulo →</a></article>
  </div>
</section>
<?php pie(); ?>
