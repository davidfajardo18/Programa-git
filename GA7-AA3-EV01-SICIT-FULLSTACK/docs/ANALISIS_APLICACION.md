# Análisis de la aplicación web SICIT / Centro Médico

## 1. Resumen funcional

El frontend existente corresponde a una aplicación de Centro Médico que permite gestionar
pacientes, médicos, citas, consultorios, usuarios y roles. En la evidencia anterior los
formularios tenían comportamiento demostrativo; esta versión incorpora persistencia real.

## 2. Inventario de pantallas

| Pantalla | Módulo | Operaciones |
|---|---|---|
| index.html | Navegación | Acceso a módulos |
| pacientes.html | Pacientes | Crear, listar, editar, eliminar |
| medicos.html | Médicos | Crear, listar, editar, eliminar |
| citas.html | Citas | Crear, listar, editar, eliminar |
| consultorios.html | Consultorios | Crear, listar, editar, eliminar |
| usuarios.html | Usuarios | Crear, listar, editar, eliminar |
| roles.html | Roles | Crear, listar, editar, eliminar |

## 3. Entidades y campos derivados del frontend

### Roles
- id_rol
- nombre

### Usuarios
- id_usuario
- nombre_usuario
- contrasena_hash
- rol_id
- estado

### Pacientes
- id_paciente
- identificacion
- nombres
- apellidos
- fecha_nacimiento
- sexo
- usuario_id

### Médicos
- id_medico
- identificacion
- nombres
- apellidos
- especialidad
- telefono
- correo
- usuario_id

### Consultorios
- id_consultorio
- numero
- ubicacion
- estado

### Citas
- id_cita
- paciente_id
- medico_id
- consultorio_id
- fecha
- hora
- motivo
- estado

## 4. Relaciones

- Un rol puede estar asociado con muchos usuarios.
- Un usuario puede estar asociado de forma opcional con un paciente o médico.
- Un paciente puede tener muchas citas.
- Un médico puede atender muchas citas.
- Un consultorio puede utilizarse en muchas citas.
- Cada cita referencia exactamente un paciente, un médico y un consultorio.

## 5. Cambios mínimos realizados sobre el frontend

1. Se conserva la navegación, nombres de módulos y estilo turquesa del Centro Médico.
2. Se integra Bootstrap para formularios, tablas, botones y responsividad.
3. Los ID dejan de ser campos escritos manualmente: MySQL los genera con `AUTO_INCREMENT`.
4. Se agregan tablas de registros debajo de cada formulario para hacer visible el CRUD.
5. Los `select` que representan relaciones se cargan dinámicamente desde MySQL.
6. El antiguo mensaje JavaScript simulado se reemplaza por respuestas reales de la API.
7. Se agregan estados de carga, éxito, error y lista vacía.

## 6. Separación de responsabilidades

- **HTML:** estructura de las pantallas.
- **CSS / Bootstrap:** presentación y diseño responsive.
- **JavaScript:** interacción con el DOM y peticiones `fetch()`.
- **PHP:** validación, lógica CRUD y respuestas JSON.
- **db.php:** conexión PDO.
- **MySQL:** persistencia y relaciones.

## 7. Seguridad mínima

- Consultas preparadas con PDO.
- Validación en backend.
- Contraseñas almacenadas con `password_hash()`.
- No se exponen credenciales en JavaScript.
- No se concatena entrada del usuario en SQL.
- Restricciones de integridad referencial mediante claves foráneas.
