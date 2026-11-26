const mongoose = require('mongoose');

const citaSchema = new mongoose.Schema(
  {
    paciente: {
      type: mongoose.Schema.Types.ObjectId,
      ref: 'Paciente',
      required: [true, 'El paciente es obligatorio.']
    },
    medico: {
      type: mongoose.Schema.Types.ObjectId,
      ref: 'Medico',
      required: [true, 'El médico es obligatorio.']
    },
    consultorio: {
      type: String,
      required: [true, 'El consultorio es obligatorio.'],
      trim: true
    },
    fecha: {
      type: Date,
      required: [true, 'La fecha es obligatoria.']
    },
    hora: {
      type: String,
      required: [true, 'La hora es obligatoria.'],
      trim: true
    },
    motivo: {
      type: String,
      required: [true, 'El motivo es obligatorio.'],
      trim: true
    },
    estado: {
      type: String,
      enum: ['Programada', 'Confirmada', 'Atendida', 'Cancelada'],
      default: 'Programada'
    }
  },
  { timestamps: true }
);

module.exports = mongoose.model('Cita', citaSchema);
