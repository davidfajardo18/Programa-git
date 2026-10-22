<?php
require_once __DIR__ . '/../config/conexionBaseDatos.php';

class Consultorio
{
    private ?int $idConsultorio = null;
    private string $numeroConsultorio = '';
    private string $ubicacion = '';
    private string $estado = 'Disponible';

    public function crearConsultorio(string $numeroConsultorio, string $ubicacion, string $estado): void
    {
        $this->numeroConsultorio = $numeroConsultorio;
        $this->ubicacion = $ubicacion;
        $this->estado = $estado;
    }

    public function setIdConsultorio(?int $idConsultorio): void { $this->idConsultorio = $idConsultorio; }
    public function getIdConsultorio(): ?int { return $this->idConsultorio; }
    public function setNumeroConsultorio(string $numeroConsultorio): void { $this->numeroConsultorio = $numeroConsultorio; }
    public function getNumeroConsultorio(): string { return $this->numeroConsultorio; }
    public function setUbicacion(string $ubicacion): void { $this->ubicacion = $ubicacion; }
    public function getUbicacion(): string { return $this->ubicacion; }
    public function setEstado(string $estado): void { $this->estado = $estado; }
    public function getEstado(): string { return $this->estado; }

    public function agregarConsultorio(): bool
    {
        $conexion = Conectarse();
        $stmt = $conexion->prepare('INSERT INTO consultorio (numero_consultorio, ubicacion, estado) VALUES (?, ?, ?)');
        $stmt->bind_param('sss', $this->numeroConsultorio, $this->ubicacion, $this->estado);
        $resultado = $stmt->execute();
        $this->idConsultorio = $stmt->insert_id ?: null;
        $stmt->close();
        $conexion->close();
        return $resultado;
    }

    public function consultarConsultorio(int $idConsultorio): ?array
    {
        $conexion = Conectarse();
        $stmt = $conexion->prepare('SELECT * FROM consultorio WHERE id_consultorio = ?');
        $stmt->bind_param('i', $idConsultorio);
        $stmt->execute();
        $resultado = $stmt->get_result()->fetch_assoc();
        $stmt->close();
        $conexion->close();
        return $resultado ?: null;
    }

    public function actualizarConsultorio(): bool
    {
        if ($this->idConsultorio === null) {
            throw new InvalidArgumentException('El id del consultorio es obligatorio para actualizar.');
        }
        $conexion = Conectarse();
        $stmt = $conexion->prepare('UPDATE consultorio SET numero_consultorio=?, ubicacion=?, estado=? WHERE id_consultorio=?');
        $stmt->bind_param('sssi', $this->numeroConsultorio, $this->ubicacion, $this->estado, $this->idConsultorio);
        $resultado = $stmt->execute();
        $stmt->close();
        $conexion->close();
        return $resultado;
    }

    public function eliminarConsultorio(int $idConsultorio): bool
    {
        $conexion = Conectarse();
        $stmt = $conexion->prepare('DELETE FROM consultorio WHERE id_consultorio = ?');
        $stmt->bind_param('i', $idConsultorio);
        $resultado = $stmt->execute();
        $stmt->close();
        $conexion->close();
        return $resultado;
    }

    public function listarConsultorios(): array
    {
        $conexion = Conectarse();
        $resultado = $conexion->query('SELECT * FROM consultorio ORDER BY numero_consultorio');
        $registros = $resultado ? $resultado->fetch_all(MYSQLI_ASSOC) : [];
        $conexion->close();
        return $registros;
    }
}
