const mongoose = require('mongoose');

const pacienteSchema = new mongoose.Schema(
  {
    tipoDocumento: {
      type: String,
      required: [true, 'El tipo de documento es obligatorio.'],
      trim: true
    },
    numeroDocumento: {
      type: String,
      required: [true, 'El número de documento es obligatorio.'],
      unique: true,
      trim: true
    },
    nombres: {
      type: String,
      required: [true, 'Los nombres son obligatorios.'],
      trim: true
    },
    apellidos: {
      type: String,
      required: [true, 'Los apellidos son obligatorios.'],
      trim: true
    },
    fechaNacimiento: {
      type: Date,
      required: [true, 'La fecha de nacimiento es obligatoria.']
    },
    telefono: {
      type: String,
      trim: true
    },
    correo: {
      type: String,
      trim: true,
      lowercase: true
    }
  },
  { timestamps: true }
);

module.exports = mongoose.model('Paciente', pacienteSchema);
