CREATE DATABASE IF NOT EXISTS centromedico CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
USE centromedico;

CREATE TABLE IF NOT EXISTS medico (
    id_medico INT AUTO_INCREMENT PRIMARY KEY,
    tipo_documento VARCHAR(20) NOT NULL,
    numero_documento VARCHAR(20) NOT NULL UNIQUE,
    nombres VARCHAR(60) NOT NULL,
    apellidos VARCHAR(60) NOT NULL,
    especialidad VARCHAR(80) NOT NULL,
    telefono VARCHAR(20),
    correo VARCHAR(100) UNIQUE
);

CREATE TABLE IF NOT EXISTS consultorio (
    id_consultorio INT AUTO_INCREMENT PRIMARY KEY,
    numero_consultorio VARCHAR(20) NOT NULL UNIQUE,
    ubicacion VARCHAR(100),
    estado VARCHAR(20) NOT NULL DEFAULT 'Disponible'
);

CREATE TABLE IF NOT EXISTS medicamento (
    id_medicamento INT AUTO_INCREMENT PRIMARY KEY,
    nombre VARCHAR(100) NOT NULL,
    descripcion VARCHAR(250),
    presentacion VARCHAR(100),
    stock INT NOT NULL DEFAULT 0
);
