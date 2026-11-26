# SICIT API REST - GA7-220501096-AA5-EV03

**Aprendiz:** David Fajardo Esteban  
**Proyecto:** SICIT - Sistema de Información para la Gestión Clínica  
**Tecnologías:** Node.js, Express, MongoDB, Mongoose y Postman.

## Objetivo
Implementar servicios web REST para los módulos Pacientes, Usuarios, Médicos y Citas del proyecto SICIT, aplicando operaciones CRUD y validándolas con Postman.

## Instalación rápida
1. Instalar Node.js LTS.
2. Abrir esta carpeta en Visual Studio Code.
3. Ejecutar `npm install`.
4. Copiar `.env.example` como `.env`.
5. Configurar `MONGODB_URI` con una conexión local o MongoDB Atlas.
6. Ejecutar `npm run dev`.
7. Verificar `http://localhost:10000/`.
8. Importar `postman/SICIT_API.postman_collection.json` en Postman.

## Endpoints principales
- `/api/pacientes`
- `/api/usuarios`
- `/api/medicos`
- `/api/citas`

Cada recurso implementa GET, GET por ID, POST, PATCH y DELETE.

Consulta `docs/ENDPOINTS.md` y `docs/GUIA_POSTMAN.md` para el detalle.
