<?php
require_once __DIR__ . '/../config/conexionBaseDatos.php';

class Medicamento
{
    private ?int $idMedicamento = null;
    private string $nombre = '';
    private string $descripcion = '';
    private string $presentacion = '';
    private int $stock = 0;

    public function crearMedicamento(string $nombre, string $descripcion, string $presentacion, int $stock): void
    {
        $this->nombre = $nombre;
        $this->descripcion = $descripcion;
        $this->presentacion = $presentacion;
        $this->stock = $stock;
    }

    public function setIdMedicamento(?int $idMedicamento): void { $this->idMedicamento = $idMedicamento; }
    public function getIdMedicamento(): ?int { return $this->idMedicamento; }
    public function setNombre(string $nombre): void { $this->nombre = $nombre; }
    public function getNombre(): string { return $this->nombre; }
    public function setDescripcion(string $descripcion): void { $this->descripcion = $descripcion; }
    public function getDescripcion(): string { return $this->descripcion; }
    public function setPresentacion(string $presentacion): void { $this->presentacion = $presentacion; }
    public function getPresentacion(): string { return $this->presentacion; }
    public function setStock(int $stock): void { $this->stock = $stock; }
    public function getStock(): int { return $this->stock; }

    public function agregarMedicamento(): bool
    {
        $conexion = Conectarse();
        $stmt = $conexion->prepare('INSERT INTO medicamento (nombre, descripcion, presentacion, stock) VALUES (?, ?, ?, ?)');
        $stmt->bind_param('sssi', $this->nombre, $this->descripcion, $this->presentacion, $this->stock);
        $resultado = $stmt->execute();
        $this->idMedicamento = $stmt->insert_id ?: null;
        $stmt->close();
        $conexion->close();
        return $resultado;
    }

    public function consultarMedicamento(int $idMedicamento): ?array
    {
        $conexion = Conectarse();
        $stmt = $conexion->prepare('SELECT * FROM medicamento WHERE id_medicamento = ?');
        $stmt->bind_param('i', $idMedicamento);
        $stmt->execute();
        $resultado = $stmt->get_result()->fetch_assoc();
        $stmt->close();
        $conexion->close();
        return $resultado ?: null;
    }

    public function actualizarMedicamento(): bool
    {
        if ($this->idMedicamento === null) {
            throw new InvalidArgumentException('El id del medicamento es obligatorio para actualizar.');
        }
        $conexion = Conectarse();
        $stmt = $conexion->prepare('UPDATE medicamento SET nombre=?, descripcion=?, presentacion=?, stock=? WHERE id_medicamento=?');
        $stmt->bind_param('sssii', $this->nombre, $this->descripcion, $this->presentacion, $this->stock, $this->idMedicamento);
        $resultado = $stmt->execute();
        $stmt->close();
        $conexion->close();
        return $resultado;
    }

    public function eliminarMedicamento(int $idMedicamento): bool
    {
        $conexion = Conectarse();
        $stmt = $conexion->prepare('DELETE FROM medicamento WHERE id_medicamento = ?');
        $stmt->bind_param('i', $idMedicamento);
        $resultado = $stmt->execute();
        $stmt->close();
        $conexion->close();
        return $resultado;
    }

    public function listarMedicamentos(): array
    {
        $conexion = Conectarse();
        $resultado = $conexion->query('SELECT * FROM medicamento ORDER BY nombre');
        $registros = $resultado ? $resultado->fetch_all(MYSQLI_ASSOC) : [];
        $conexion->close();
        return $registros;
    }
}
