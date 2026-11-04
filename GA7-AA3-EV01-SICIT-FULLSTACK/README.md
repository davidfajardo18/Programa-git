# SICIT / Centro Médico — GA7-220501096-AA3-EV01

**Aprendiz:** David Fajardo Esteban  
**Programa:** Tecnólogo en Análisis y Desarrollo de Software — SENA  
**Evidencia:** Codificación y pruebas de módulos del software Stand-alone, web y/o móvil.

## Objetivo

Transformar el frontend construido en evidencias anteriores en una aplicación web funcional,
conservando su identidad visual y conectándolo a una base de datos MySQL real.

## Tecnologías

- HTML5
- Bootstrap 5.3.3 como framework de diseño
- CSS3
- JavaScript con `fetch()` y `async/await`
- PHP 8+ para la API
- PDO y consultas preparadas
- MySQL / MariaDB
- Git y GitHub para versionamiento

## Módulos implementados

- Roles
- Usuarios
- Pacientes
- Médicos
- Consultorios
- Citas

Cada módulo permite **crear, listar, editar y eliminar** registros. Los campos relacionados
(`select`) se cargan desde la base de datos.

## Arquitectura

```text
HTML + Bootstrap + CSS
          |
     JavaScript
        fetch()
          |
       API PHP
          |
        PDO
          |
        MySQL
```

## Instalación rápida en XAMPP

1. Copie la carpeta `DAVID_FAJARDO_AA3_EV01` dentro de:
   `C:\xampp\htdocs\`
2. Inicie **Apache** y **MySQL** desde XAMPP.
3. Abra `http://localhost/phpmyadmin`.
4. Importe el archivo `database.sql`.
5. Verifique `php/config/db.php`. La configuración incluida corresponde a XAMPP:
   - host: `localhost`
   - base de datos: `sicit`
   - usuario: `root`
   - contraseña: vacía
6. Abra en el navegador:
   `http://localhost/DAVID_FAJARDO_AA3_EV01/`
7. Registre primero roles/usuarios si desea asociarlos a pacientes o médicos.
8. Para crear citas, registre antes pacientes, médicos y consultorios.

> No abra los HTML mediante `file://`. La aplicación debe ejecutarse desde Apache porque usa
> endpoints PHP y MySQL.

## Versionamiento

Repositorio del proyecto:

https://github.com/davidfajardo18/Programa-git/tree/main/GA7-AA3-EV01-SICIT-FULLSTACK

## Archivos de apoyo

- `docs/ANALISIS_APLICACION.md`: análisis previo al desarrollo.
- `docs/PRUEBAS_FUNCIONALES.md`: casos de prueba.
- `docs/GUION_VIDEO.md`: guion sugerido para el video expositivo.
- `ENLACE_REPOSITORIO.txt`: enlace del repositorio solicitado por la evidencia.
