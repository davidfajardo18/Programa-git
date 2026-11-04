-- SICIT / Centro Médico
-- GA7-220501096-AA3-EV01
-- Base de datos derivada de los formularios del frontend entregado en evidencias anteriores.

CREATE DATABASE IF NOT EXISTS sicit
  CHARACTER SET utf8mb4
  COLLATE utf8mb4_unicode_ci;

USE sicit;

CREATE TABLE IF NOT EXISTS roles (
  id_rol INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
  nombre VARCHAR(50) NOT NULL,
  CONSTRAINT uq_roles_nombre UNIQUE (nombre)
) ENGINE=InnoDB;

CREATE TABLE IF NOT EXISTS usuarios (
  id_usuario INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
  nombre_usuario VARCHAR(50) NOT NULL,
  contrasena_hash VARCHAR(255) NOT NULL,
  rol_id INT UNSIGNED NOT NULL,
  estado TINYINT(1) NOT NULL DEFAULT 1,
  CONSTRAINT uq_usuarios_nombre UNIQUE (nombre_usuario),
  CONSTRAINT fk_usuarios_roles
    FOREIGN KEY (rol_id) REFERENCES roles(id_rol)
    ON UPDATE CASCADE ON DELETE RESTRICT,
  INDEX idx_usuarios_rol (rol_id)
) ENGINE=InnoDB;

CREATE TABLE IF NOT EXISTS pacientes (
  id_paciente INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
  identificacion VARCHAR(15) NOT NULL,
  nombres VARCHAR(50) NOT NULL,
  apellidos VARCHAR(50) NOT NULL,
  fecha_nacimiento DATE NOT NULL,
  sexo ENUM('Femenino','Masculino','Otro') NOT NULL,
  usuario_id INT UNSIGNED NULL,
  CONSTRAINT uq_pacientes_identificacion UNIQUE (identificacion),
  CONSTRAINT fk_pacientes_usuarios
    FOREIGN KEY (usuario_id) REFERENCES usuarios(id_usuario)
    ON UPDATE CASCADE ON DELETE SET NULL,
  INDEX idx_pacientes_usuario (usuario_id)
) ENGINE=InnoDB;

CREATE TABLE IF NOT EXISTS medicos (
  id_medico INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
  identificacion VARCHAR(15) NOT NULL,
  nombres VARCHAR(50) NOT NULL,
  apellidos VARCHAR(50) NOT NULL,
  especialidad VARCHAR(80) NOT NULL,
  telefono VARCHAR(20) NOT NULL,
  correo VARCHAR(100) NOT NULL,
  usuario_id INT UNSIGNED NULL,
  CONSTRAINT uq_medicos_identificacion UNIQUE (identificacion),
  CONSTRAINT uq_medicos_correo UNIQUE (correo),
  CONSTRAINT fk_medicos_usuarios
    FOREIGN KEY (usuario_id) REFERENCES usuarios(id_usuario)
    ON UPDATE CASCADE ON DELETE SET NULL,
  INDEX idx_medicos_usuario (usuario_id)
) ENGINE=InnoDB;

CREATE TABLE IF NOT EXISTS consultorios (
  id_consultorio INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
  numero VARCHAR(20) NOT NULL,
  ubicacion VARCHAR(100) NOT NULL,
  estado ENUM('Disponible','Ocupado','Inactivo') NOT NULL DEFAULT 'Disponible',
  CONSTRAINT uq_consultorios_numero UNIQUE (numero)
) ENGINE=InnoDB;

CREATE TABLE IF NOT EXISTS citas (
  id_cita INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
  paciente_id INT UNSIGNED NOT NULL,
  medico_id INT UNSIGNED NOT NULL,
  consultorio_id INT UNSIGNED NOT NULL,
  fecha DATE NOT NULL,
  hora TIME NOT NULL,
  motivo VARCHAR(250) NOT NULL,
  estado ENUM('Programada','Confirmada','Atendida','Cancelada') NOT NULL DEFAULT 'Programada',
  CONSTRAINT fk_citas_paciente
    FOREIGN KEY (paciente_id) REFERENCES pacientes(id_paciente)
    ON UPDATE CASCADE ON DELETE RESTRICT,
  CONSTRAINT fk_citas_medico
    FOREIGN KEY (medico_id) REFERENCES medicos(id_medico)
    ON UPDATE CASCADE ON DELETE RESTRICT,
  CONSTRAINT fk_citas_consultorio
    FOREIGN KEY (consultorio_id) REFERENCES consultorios(id_consultorio)
    ON UPDATE CASCADE ON DELETE RESTRICT,
  INDEX idx_citas_paciente (paciente_id),
  INDEX idx_citas_medico (medico_id),
  INDEX idx_citas_consultorio (consultorio_id),
  INDEX idx_citas_fecha (fecha)
) ENGINE=InnoDB;

-- Los roles ya aparecían como opciones en el frontend anterior, por eso se incluyen como datos iniciales.
INSERT IGNORE INTO roles (nombre) VALUES
  ('Administrador'),
  ('Recepcionista'),
  ('Médico'),
  ('Auxiliar de farmacia');
