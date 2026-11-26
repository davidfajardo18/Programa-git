# Registro de pruebas funcionales

| Prueba | Resultado esperado | Estado |
|---|---|---|
| Ruta raíz GET `/` | 200 y mensaje API funcionando | Pendiente de captura |
| POST paciente válido | 201 y documento con `_id` | Pendiente de captura |
| GET pacientes | 200 y arreglo JSON | Pendiente de captura |
| GET paciente por ID | 200 y paciente correcto | Pendiente de captura |
| PATCH paciente | 200 y datos actualizados | Pendiente de captura |
| DELETE paciente | 200 y mensaje de eliminación | Pendiente de captura |
| POST usuario | 201; contraseña no aparece en respuesta | Pendiente de captura |
| POST médico | 201 y documento con `_id` | Pendiente de captura |
| POST cita | 201 con referencias de paciente y médico | Pendiente de captura |
| GET citas | 200 con paciente y médico poblados | Pendiente de captura |
| ID inexistente | 404 | Pendiente de captura |
| Dato único duplicado | 409 | Pendiente de captura |
