const express = require('express');
const router = express.Router();
const Usuario = require('../models/Usuario');

router.get('/', async (req, res, next) => {
  try {
    const usuarios = await Usuario.find().sort({ createdAt: -1 });
    res.status(200).json({ success: true, data: usuarios });
  } catch (error) { next(error); }
});

router.get('/:id', async (req, res, next) => {
  try {
    const usuario = await Usuario.findById(req.params.id);
    if (!usuario) return res.status(404).json({ success: false, message: 'Usuario no encontrado.' });
    res.status(200).json({ success: true, data: usuario });
  } catch (error) { next(error); }
});

router.post('/', async (req, res, next) => {
  try {
    const usuario = new Usuario(req.body);
    const guardado = await usuario.save();
    const respuesta = await Usuario.findById(guardado._id);
    res.status(201).json({ success: true, message: 'Usuario creado correctamente.', data: respuesta });
  } catch (error) { next(error); }
});

router.patch('/:id', async (req, res, next) => {
  try {
    const usuario = await Usuario.findById(req.params.id).select('+contrasena');
    if (!usuario) return res.status(404).json({ success: false, message: 'Usuario no encontrado.' });

    const campos = ['nombreUsuario', 'correo', 'contrasena', 'rol', 'estado'];
    campos.forEach((campo) => {
      if (req.body[campo] !== undefined) usuario[campo] = req.body[campo];
    });

    await usuario.save();
    const actualizado = await Usuario.findById(usuario._id);
    res.status(200).json({ success: true, message: 'Usuario actualizado correctamente.', data: actualizado });
  } catch (error) { next(error); }
});

router.delete('/:id', async (req, res, next) => {
  try {
    const eliminado = await Usuario.findByIdAndDelete(req.params.id);
    if (!eliminado) return res.status(404).json({ success: false, message: 'Usuario no encontrado.' });
    res.status(200).json({ success: true, message: 'Usuario eliminado correctamente.' });
  } catch (error) { next(error); }
});

module.exports = router;
