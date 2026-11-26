function errorHandler(error, req, res, next) {
  console.error(error);

  // Error por dato duplicado en un campo marcado como unique.
  if (error && error.code === 11000) {
    return res.status(409).json({
      success: false,
      message: 'Ya existe un registro con uno de los datos únicos enviados.'
    });
  }

  // Error de validación generado por Mongoose.
  if (error && error.name === 'ValidationError') {
    const errors = Object.values(error.errors).map((item) => item.message);
    return res.status(422).json({
      success: false,
      message: 'Los datos enviados no son válidos.',
      errors
    });
  }

  return res.status(500).json({
    success: false,
    message: 'Ocurrió un error interno en el servidor.'
  });
}

module.exports = errorHandler;
