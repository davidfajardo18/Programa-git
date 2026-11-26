const express = require('express');
const router = express.Router();
const Medico = require('../models/Medico');

// GET /api/medicos - Lista todos los registros.
router.get('/', async (req, res, next) => {
  try {
    const registros = await Medico.find().sort({ createdAt: -1 });
    res.status(200).json({ success: true, data: registros });
  } catch (error) { next(error); }
});

// GET /api/medicos/:id - Consulta un registro por su identificador.
router.get('/:id', async (req, res, next) => {
  try {
    const registro = await Medico.findById(req.params.id);
    if (!registro) {
      return res.status(404).json({ success: false, message: 'Médico no encontrado.' });
    }
    res.status(200).json({ success: true, data: registro });
  } catch (error) { next(error); }
});

// POST /api/medicos - Crea un nuevo registro.
router.post('/', async (req, res, next) => {
  try {
    const registro = new Medico(req.body);
    const guardado = await registro.save();
    res.status(201).json({
      success: true,
      message: 'Médico creado correctamente.',
      data: guardado
    });
  } catch (error) { next(error); }
});

// PATCH /api/medicos/:id - Actualiza parcialmente un registro.
router.patch('/:id', async (req, res, next) => {
  try {
    const actualizado = await Medico.findByIdAndUpdate(
      req.params.id,
      req.body,
      { new: true, runValidators: true }
    );
    if (!actualizado) {
      return res.status(404).json({ success: false, message: 'Médico no encontrado.' });
    }
    res.status(200).json({
      success: true,
      message: 'Médico actualizado correctamente.',
      data: actualizado
    });
  } catch (error) { next(error); }
});

// DELETE /api/medicos/:id - Elimina un registro.
router.delete('/:id', async (req, res, next) => {
  try {
    const eliminado = await Medico.findByIdAndDelete(req.params.id);
    if (!eliminado) {
      return res.status(404).json({ success: false, message: 'Médico no encontrado.' });
    }
    res.status(200).json({ success: true, message: 'Médico eliminado correctamente.' });
  } catch (error) { next(error); }
});

module.exports = router;
