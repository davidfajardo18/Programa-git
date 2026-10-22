<?php
function e(?string $valor): string
{
    return htmlspecialchars((string)$valor, ENT_QUOTES, 'UTF-8');
}

function encabezado(string $titulo, string $activo = ''): void
{
    $items = [
        'index.php' => ['Inicio', 'inicio'],
        'medicos.php' => ['Médicos', 'medicos'],
        'consultorios.php' => ['Consultorios', 'consultorios'],
        'medicamentos.php' => ['Medicamentos', 'medicamentos'],
    ];

    echo '<!doctype html><html lang="es"><head><meta charset="UTF-8"><meta name="viewport" content="width=device-width,initial-scale=1">';
    echo '<title>' . e($titulo) . ' | SICIT</title><link rel="stylesheet" href="assets/styles.css"></head><body>';
    echo '<header class="topbar"><div class="brand">✚ SICIT · Centro Médico</div><nav class="nav">';

    foreach ($items as $href => [$texto, $clave]) {
        $clase = $activo === $clave ? ' class="active"' : '';
        echo '<a href="' . $href . '"' . $clase . '>' . e($texto) . '</a>';
    }

    echo '</nav></header><main class="container">';
}

function pie(): void
{
    echo '</main><footer>Proyecto académico SICIT · David Fajardo Esteban · SENA 2026</footer></body></html>';
}
