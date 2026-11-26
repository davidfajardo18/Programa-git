# Guía de testing con Postman

## 1. Iniciar servidor
En terminal, dentro de la carpeta del proyecto:
```bash
npm install
npm run dev
```
Debe aparecer `MongoDB conectado correctamente` y `Servidor SICIT disponible en http://localhost:10000`.

## 2. Importar colección
En Postman selecciona **Import** y carga `postman/SICIT_API.postman_collection.json`.
La variable `baseUrl` ya está configurada como `http://localhost:10000`.

## 3. Orden recomendado de pruebas
1. `Health - API funcionando`.
2. Pacientes: POST, GET todos, GET por ID, PATCH y DELETE.
3. Usuarios: POST, GET todos, GET por ID, PATCH y DELETE.
4. Médicos: POST, GET todos, GET por ID, PATCH y DELETE.
5. Citas: primero crea un paciente y un médico, copia sus `_id`, después prueba POST, GET, PATCH y DELETE de citas.

## 4. Capturas que deben incluirse en la evidencia
Toma pantallazos donde se vea claramente:
- Método HTTP seleccionado.
- URL completa.
- Body JSON cuando corresponda.
- Código de respuesta (`200`, `201`, etc.).
- Respuesta JSON.

Capturas mínimas recomendadas:
1. POST Paciente - 201.
2. GET Pacientes - 200.
3. PATCH Paciente - 200.
4. DELETE Paciente - 200.
5. POST Médico - 201.
6. POST Usuario - 201.
7. POST Cita - 201.
8. GET Citas - 200.

Guárdalas dentro de la carpeta `capturas/`.
