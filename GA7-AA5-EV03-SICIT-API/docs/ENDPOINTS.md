# Documentación de endpoints - SICIT API

Base URL local: `http://localhost:10000`

## Pacientes
| Método | Endpoint | Función |
|---|---|---|
| GET | `/api/pacientes` | Listar pacientes |
| GET | `/api/pacientes/:id` | Consultar paciente por ID |
| POST | `/api/pacientes` | Registrar paciente |
| PATCH | `/api/pacientes/:id` | Actualizar paciente |
| DELETE | `/api/pacientes/:id` | Eliminar paciente |

### Body POST ejemplo
```json
{
  "tipoDocumento": "CC",
  "numeroDocumento": "1020304050",
  "nombres": "Laura",
  "apellidos": "Ramírez",
  "fechaNacimiento": "1995-06-15",
  "telefono": "3105551234",
  "correo": "laura@test.com"
}
```

## Usuarios
| Método | Endpoint | Función |
|---|---|---|
| GET | `/api/usuarios` | Listar usuarios |
| GET | `/api/usuarios/:id` | Consultar usuario por ID |
| POST | `/api/usuarios` | Registrar usuario |
| PATCH | `/api/usuarios/:id` | Actualizar usuario |
| DELETE | `/api/usuarios/:id` | Eliminar usuario |

### Body POST ejemplo
```json
{
  "nombreUsuario": "laura.ramirez",
  "correo": "laura.usuario@test.com",
  "contrasena": "Clave123",
  "rol": "Recepcionista",
  "estado": "Activo"
}
```

## Médicos
| Método | Endpoint | Función |
|---|---|---|
| GET | `/api/medicos` | Listar médicos |
| GET | `/api/medicos/:id` | Consultar médico por ID |
| POST | `/api/medicos` | Registrar médico |
| PATCH | `/api/medicos/:id` | Actualizar médico |
| DELETE | `/api/medicos/:id` | Eliminar médico |

### Body POST ejemplo
```json
{
  "tipoDocumento": "CC",
  "numeroDocumento": "987654321",
  "nombres": "Andrea",
  "apellidos": "Gómez",
  "especialidad": "Medicina General",
  "telefono": "3101234567",
  "correo": "andrea@clinica.com"
}
```

## Citas
| Método | Endpoint | Función |
|---|---|---|
| GET | `/api/citas` | Listar citas |
| GET | `/api/citas/:id` | Consultar cita por ID |
| POST | `/api/citas` | Registrar cita |
| PATCH | `/api/citas/:id` | Actualizar cita |
| DELETE | `/api/citas/:id` | Eliminar cita |

### Body POST ejemplo
Reemplaza los valores de `paciente` y `medico` por los `_id` obtenidos al crear esos registros.
```json
{
  "paciente": "ID_PACIENTE",
  "medico": "ID_MEDICO",
  "consultorio": "101",
  "fecha": "2026-12-01",
  "hora": "10:30",
  "motivo": "Consulta general",
  "estado": "Programada"
}
```

## Códigos HTTP usados
- `200`: operación correcta.
- `201`: registro creado.
- `404`: registro no encontrado.
- `409`: dato duplicado.
- `422`: error de validación.
- `500`: error interno del servidor.
