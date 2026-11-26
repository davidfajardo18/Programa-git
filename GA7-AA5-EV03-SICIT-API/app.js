const express = require('express');
const cors = require('cors');
const dotenv = require('dotenv');
const conectarBaseDatos = require('./config/db');

const pacientesRoutes = require('./routes/pacientes');
const usuariosRoutes = require('./routes/usuarios');
const medicosRoutes = require('./routes/medicos');
const citasRoutes = require('./routes/citas');
const errorHandler = require('./middleware/errorHandler');

dotenv.config();
const app = express();
const PORT = process.env.PORT || 10000;

// Middlewares globales: permiten JSON y peticiones desde herramientas como Postman.
app.use(cors());
app.use(express.json());

// Ruta de prueba para verificar que el servidor está funcionando.
app.get('/', (req, res) => {
  res.json({
    success: true,
    message: 'API SICIT funcionando correctamente',
    version: '1.0.0'
  });
});

// Rutas principales de la API.
app.use('/api/pacientes', pacientesRoutes);
app.use('/api/usuarios', usuariosRoutes);
app.use('/api/medicos', medicosRoutes);
app.use('/api/citas', citasRoutes);

// Manejador central de errores.
app.use(errorHandler);

async function iniciarServidor() {
  try {
    await conectarBaseDatos();
    app.listen(PORT, () => {
      console.log(`Servidor SICIT disponible en http://localhost:${PORT}`);
    });
  } catch (error) {
    console.error('No fue posible iniciar el servidor:', error.message);
    process.exit(1);
  }
}

iniciarServidor();
