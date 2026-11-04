# Guion de video — GA7-220501096-AA3-EV01

Duración sugerida: 4 a 6 minutos.

## 1. Presentación

**Decir:**

> Hola, mi nombre es David Fajardo Esteban. En esta evidencia presento la evolución del
> proyecto SICIT o Centro Médico. Partí del frontend realizado en evidencias anteriores y lo
> convertí en una aplicación web funcional usando Bootstrap, JavaScript, PHP y MySQL.

**Mostrar:** página `index.html` ejecutándose desde `localhost`.

## 2. Explicar la arquitectura

**Decir:**

> La interfaz se mantiene separada del backend. HTML y Bootstrap manejan la presentación,
> JavaScript utiliza fetch para comunicarse con una API PHP, PHP valida los datos y usa PDO
> para conectarse a MySQL.

**Mostrar:** brevemente las carpetas `assets`, `php/api`, `php/config` y `database.sql`.

## 3. Mostrar Bootstrap y continuidad visual

**Decir:**

> Se conservó la identidad del Centro Médico de la evidencia frontend anterior y se integró
> Bootstrap para los formularios, botones, tablas y comportamiento responsive.

**Mostrar:** formulario de Pacientes o Médicos.

## 4. Demostrar CREATE y READ

**Decir:**

> Voy a registrar un ejemplo. Al guardar, JavaScript envía los datos a PHP y PHP los persiste
> en MySQL. Después la tabla se actualiza automáticamente con los datos de la base de datos.

**Hacer:** crear un consultorio o paciente y mostrar que aparece en la tabla.

## 5. Demostrar UPDATE

**Decir:**

> El botón Editar carga el registro en el formulario. Al guardar nuevamente se ejecuta la
> actualización en la base de datos.

**Hacer:** editar el registro recién creado.

## 6. Demostrar DELETE

**Decir:**

> Antes de eliminar existe una confirmación para evitar borrados accidentales. La API también
> respeta las relaciones de la base de datos.

**Hacer:** eliminar un registro que no tenga relaciones.

## 7. Mostrar relaciones

**Decir:**

> Los campos relacionados ya no usan opciones escritas manualmente. Por ejemplo, en Citas los
> pacientes, médicos y consultorios se cargan desde la base de datos y el usuario ve nombres
> comprensibles mientras el sistema envía los identificadores reales.

**Mostrar:** módulo Citas y abrir sus select.

## 8. Mostrar versionamiento

**Decir:**

> El proyecto se encuentra versionado en GitHub, cumpliendo el requisito de utilizar una
> herramienta de control de versiones.

**Mostrar:** repositorio y carpeta de la evidencia.

## 9. Cierre

**Decir:**

> Con esta evidencia el frontend dejó de usar datos simulados y quedó conectado a MySQL con
> operaciones CRUD reales, validación en frontend y backend, consultas preparadas y diseño
> responsive con Bootstrap. Muchas gracias.
