<?php
require_once __DIR__ . '/../config/conexionBaseDatos.php';

class Medico
{
    private ?int $idMedico = null;
    private string $tipoDocumento = '';
    private string $numeroDocumento = '';
    private string $nombres = '';
    private string $apellidos = '';
    private string $especialidad = '';
    private string $telefono = '';
    private string $correo = '';

    public function crearMedico(string $tipoDocumento, string $numeroDocumento, string $nombres, string $apellidos, string $especialidad, string $telefono, string $correo): void
    {
        $this->tipoDocumento = $tipoDocumento;
        $this->numeroDocumento = $numeroDocumento;
        $this->nombres = $nombres;
        $this->apellidos = $apellidos;
        $this->especialidad = $especialidad;
        $this->telefono = $telefono;
        $this->correo = $correo;
    }

    public function setIdMedico(?int $idMedico): void { $this->idMedico = $idMedico; }
    public function getIdMedico(): ?int { return $this->idMedico; }
    public function setTipoDocumento(string $tipoDocumento): void { $this->tipoDocumento = $tipoDocumento; }
    public function getTipoDocumento(): string { return $this->tipoDocumento; }
    public function setNumeroDocumento(string $numeroDocumento): void { $this->numeroDocumento = $numeroDocumento; }
    public function getNumeroDocumento(): string { return $this->numeroDocumento; }
    public function setNombres(string $nombres): void { $this->nombres = $nombres; }
    public function getNombres(): string { return $this->nombres; }
    public function setApellidos(string $apellidos): void { $this->apellidos = $apellidos; }
    public function getApellidos(): string { return $this->apellidos; }
    public function setEspecialidad(string $especialidad): void { $this->especialidad = $especialidad; }
    public function getEspecialidad(): string { return $this->especialidad; }
    public function setTelefono(string $telefono): void { $this->telefono = $telefono; }
    public function getTelefono(): string { return $this->telefono; }
    public function setCorreo(string $correo): void { $this->correo = $correo; }
    public function getCorreo(): string { return $this->correo; }

    public function agregarMedico(): bool
    {
        $conexion = Conectarse();
        $stmt = $conexion->prepare('INSERT INTO medico (tipo_documento, numero_documento, nombres, apellidos, especialidad, telefono, correo) VALUES (?, ?, ?, ?, ?, ?, ?)');
        $stmt->bind_param('sssssss', $this->tipoDocumento, $this->numeroDocumento, $this->nombres, $this->apellidos, $this->especialidad, $this->telefono, $this->correo);
        $resultado = $stmt->execute();
        $this->idMedico = $stmt->insert_id ?: null;
        $stmt->close();
        $conexion->close();
        return $resultado;
    }

    public function consultarMedico(int $idMedico): ?array
    {
        $conexion = Conectarse();
        $stmt = $conexion->prepare('SELECT * FROM medico WHERE id_medico = ?');
        $stmt->bind_param('i', $idMedico);
        $stmt->execute();
        $resultado = $stmt->get_result()->fetch_assoc();
        $stmt->close();
        $conexion->close();
        return $resultado ?: null;
    }

    public function actualizarMedico(): bool
    {
        if ($this->idMedico === null) {
            throw new InvalidArgumentException('El id del médico es obligatorio para actualizar.');
        }
        $conexion = Conectarse();
        $stmt = $conexion->prepare('UPDATE medico SET tipo_documento=?, numero_documento=?, nombres=?, apellidos=?, especialidad=?, telefono=?, correo=? WHERE id_medico=?');
        $stmt->bind_param('sssssssi', $this->tipoDocumento, $this->numeroDocumento, $this->nombres, $this->apellidos, $this->especialidad, $this->telefono, $this->correo, $this->idMedico);
        $resultado = $stmt->execute();
        $stmt->close();
        $conexion->close();
        return $resultado;
    }

    public function eliminarMedico(int $idMedico): bool
    {
        $conexion = Conectarse();
        $stmt = $conexion->prepare('DELETE FROM medico WHERE id_medico = ?');
        $stmt->bind_param('i', $idMedico);
        $resultado = $stmt->execute();
        $stmt->close();
        $conexion->close();
        return $resultado;
    }

    public function listarMedicos(): array
    {
        $conexion = Conectarse();
        $resultado = $conexion->query('SELECT * FROM medico ORDER BY apellidos, nombres');
        $registros = $resultado ? $resultado->fetch_all(MYSQLI_ASSOC) : [];
        $conexion->close();
        return $registros;
    }
}
