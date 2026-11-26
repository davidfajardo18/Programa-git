const express = require('express');
const router = express.Router();
const Cita = require('../models/Cita');

// GET /api/citas - Lista todos los registros.
router.get('/', async (req, res, next) => {
  try {
    const registros = await Cita.find().populate('paciente', 'tipoDocumento numeroDocumento nombres apellidos').populate('medico', 'nombres apellidos especialidad').sort({ createdAt: -1 });
    res.status(200).json({ success: true, data: registros });
  } catch (error) { next(error); }
});

// GET /api/citas/:id - Consulta un registro por su identificador.
router.get('/:id', async (req, res, next) => {
  try {
    const registro = await Cita.findById(req.params.id).populate('paciente', 'tipoDocumento numeroDocumento nombres apellidos').populate('medico', 'nombres apellidos especialidad');
    if (!registro) {
      return res.status(404).json({ success: false, message: 'Cita no encontrado.' });
    }
    res.status(200).json({ success: true, data: registro });
  } catch (error) { next(error); }
});

// POST /api/citas - Crea un nuevo registro.
router.post('/', async (req, res, next) => {
  try {
    const registro = new Cita(req.body);
    const guardado = await registro.save();
    res.status(201).json({
      success: true,
      message: 'Cita creado correctamente.',
      data: guardado
    });
  } catch (error) { next(error); }
});

// PATCH /api/citas/:id - Actualiza parcialmente un registro.
router.patch('/:id', async (req, res, next) => {
  try {
    const actualizado = await Cita.findByIdAndUpdate(
      req.params.id,
      req.body,
      { new: true, runValidators: true }
    ).populate('paciente', 'tipoDocumento numeroDocumento nombres apellidos').populate('medico', 'nombres apellidos especialidad');
    if (!actualizado) {
      return res.status(404).json({ success: false, message: 'Cita no encontrado.' });
    }
    res.status(200).json({
      success: true,
      message: 'Cita actualizado correctamente.',
      data: actualizado
    });
  } catch (error) { next(error); }
});

// DELETE /api/citas/:id - Elimina un registro.
router.delete('/:id', async (req, res, next) => {
  try {
    const eliminado = await Cita.findByIdAndDelete(req.params.id);
    if (!eliminado) {
      return res.status(404).json({ success: false, message: 'Cita no encontrado.' });
    }
    res.status(200).json({ success: true, message: 'Cita eliminado correctamente.' });
  } catch (error) { next(error); }
});

module.exports = router;
