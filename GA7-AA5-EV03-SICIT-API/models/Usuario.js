const mongoose = require('mongoose');
const bcrypt = require('bcryptjs');

const usuarioSchema = new mongoose.Schema(
  {
    nombreUsuario: {
      type: String,
      required: [true, 'El nombre de usuario es obligatorio.'],
      unique: true,
      trim: true
    },
    correo: {
      type: String,
      required: [true, 'El correo es obligatorio.'],
      unique: true,
      trim: true,
      lowercase: true
    },
    contrasena: {
      type: String,
      required: [true, 'La contraseña es obligatoria.'],
      minlength: [6, 'La contraseña debe tener al menos 6 caracteres.'],
      select: false
    },
    rol: {
      type: String,
      required: true,
      enum: ['Administrador', 'Recepcionista', 'Médico', 'Auxiliar de farmacia']
    },
    estado: {
      type: String,
      enum: ['Activo', 'Inactivo'],
      default: 'Activo'
    }
  },
  { timestamps: true }
);

usuarioSchema.pre('save', async function cifrarContrasena(next) {
  if (!this.isModified('contrasena')) return next();
  this.contrasena = await bcrypt.hash(this.contrasena, 10);
  next();
});

module.exports = mongoose.model('Usuario', usuarioSchema);
