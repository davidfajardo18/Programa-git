const express = require('express');
const router = express.Router();
const Paciente = require('../models/Paciente');

// GET /api/pacientes - Lista todos los registros.
router.get('/', async (req, res, next) => {
  try {
    const registros = await Paciente.find().sort({ createdAt: -1 });
    res.status(200).json({ success: true, data: registros });
  } catch (error) { next(error); }
});

// GET /api/pacientes/:id - Consulta un registro por su identificador.
router.get('/:id', async (req, res, next) => {
  try {
    const registro = await Paciente.findById(req.params.id);
    if (!registro) {
      return res.status(404).json({ success: false, message: 'Paciente no encontrado.' });
    }
    res.status(200).json({ success: true, data: registro });
  } catch (error) { next(error); }
});

// POST /api/pacientes - Crea un nuevo registro.
router.post('/', async (req, res, next) => {
  try {
    const registro = new Paciente(req.body);
    const guardado = await registro.save();
    res.status(201).json({
      success: true,
      message: 'Paciente creado correctamente.',
      data: guardado
    });
  } catch (error) { next(error); }
});

// PATCH /api/pacientes/:id - Actualiza parcialmente un registro.
router.patch('/:id', async (req, res, next) => {
  try {
    const actualizado = await Paciente.findByIdAndUpdate(
      req.params.id,
      req.body,
      { new: true, runValidators: true }
    );
    if (!actualizado) {
      return res.status(404).json({ success: false, message: 'Paciente no encontrado.' });
    }
    res.status(200).json({
      success: true,
      message: 'Paciente actualizado correctamente.',
      data: actualizado
    });
  } catch (error) { next(error); }
});

// DELETE /api/pacientes/:id - Elimina un registro.
router.delete('/:id', async (req, res, next) => {
  try {
    const eliminado = await Paciente.findByIdAndDelete(req.params.id);
    if (!eliminado) {
      return res.status(404).json({ success: false, message: 'Paciente no encontrado.' });
    }
    res.status(200).json({ success: true, message: 'Paciente eliminado correctamente.' });
  } catch (error) { next(error); }
});

module.exports = router;
