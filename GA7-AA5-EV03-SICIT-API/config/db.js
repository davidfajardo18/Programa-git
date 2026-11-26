const mongoose = require('mongoose');

async function conectarBaseDatos() {
  const uri = process.env.MONGODB_URI;

  if (!uri) {
    throw new Error('Falta configurar MONGODB_URI en el archivo .env');
  }

  await mongoose.connect(uri);
  console.log('MongoDB conectado correctamente');
}

module.exports = conectarBaseDatos;
