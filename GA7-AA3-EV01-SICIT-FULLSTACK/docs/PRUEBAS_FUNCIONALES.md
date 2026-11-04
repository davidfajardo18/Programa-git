# Pruebas funcionales — SICIT

## Validaciones automáticas realizadas durante la preparación

- PHP: todos los archivos de `php/config` y `php/api` pasan `php -l` sin errores de sintaxis.
- JavaScript: `assets/script.js` pasa `node --check`.
- HTML: las siete páginas contienen estructura válida, y los seis módulos contienen formulario y tabla de resultados.
- Servidor PHP: `index.html` responde HTTP 200.
- API sin MySQL disponible: devuelve JSON controlado con HTTP 500, sin mostrar stack trace ni credenciales.

## Pruebas a ejecutar en XAMPP

### Roles
1. Crear rol válido.
2. Intentar crear un rol duplicado.
3. Editar un rol.
4. Eliminar un rol sin usuarios asociados.
5. Intentar eliminar un rol que tenga usuarios asociados.

### Usuarios
1. Crear usuario con contraseña de seis o más caracteres.
2. Intentar crear usuario sin contraseña.
3. Intentar crear nombre de usuario duplicado.
4. Editar usuario sin cambiar la contraseña.
5. Editar usuario asignando otro rol.
6. Eliminar usuario no relacionado.

### Pacientes
1. Crear paciente completo.
2. Crear paciente con identificación duplicada.
3. Asociar paciente a usuario.
4. Editar paciente.
5. Eliminar paciente sin citas.
6. Intentar eliminar paciente con citas relacionadas.

### Médicos
1. Crear médico con correo válido.
2. Intentar correo inválido.
3. Intentar identificación/correo duplicado.
4. Editar médico.
5. Eliminar médico sin citas.
6. Intentar eliminar médico con citas.

### Consultorios
1. Crear consultorio.
2. Intentar número duplicado.
3. Cambiar estado Disponible/Ocupado/Inactivo.
4. Eliminar consultorio sin citas.
5. Intentar eliminar consultorio con citas.

### Citas
1. Comprobar que los select de paciente, médico y consultorio se cargan desde MySQL.
2. Crear cita válida.
3. Editar fecha, hora, motivo o estado.
4. Eliminar cita.
5. Verificar estados Programada, Confirmada, Atendida y Cancelada.

### Conexión
1. Probar con Apache y MySQL encendidos.
2. Apagar MySQL y comprobar mensaje de conexión.
3. Restaurar MySQL y actualizar la lista.
