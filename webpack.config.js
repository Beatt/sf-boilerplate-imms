var Encore = require('@symfony/webpack-encore');

Encore
  // directory where compiled assets will be stored
  .setOutputPath('web/build/')
  // public path used by the web server to access the output path
  .setPublicPath('/build')
  .addStyleEntry('layout', './assets/css/layout.scss')
  .addEntry('validar-info', './assets/js/institucion_educativa/ValidarInfo/index.js')
  .addEntry('detalle-campos', './assets/js/institucion_educativa/DetalleSolicitud/index.js')
  .addEntry('registrar-montos', './assets/js/institucion_educativa/RegistrarMontos/index.js')
  .addEntry('solicitudes', './assets/js/IEDetalleSolicitud/solicitudes.js')
  .addEntry('inicio', './assets/js/IEInicio/index.js')
  .addEntry('multiple', './assets/js/IEDetalleSolicitudMultiple/index.js')
  .addEntry('montos', './assets/js/IERegistrarMontos/index.js')
  .addEntry('solicitud-index', './assets/js/institucion_educativa/Solicitud/Index/index.js')
  .addEntry('came_solicitud_index', './assets/js/came/solicitud/index.js')
  .addEntry('detalle_solicitud_multiple', './assets/js/institucion_educativa/DetalleSolicitudMultiple/index.js')
  .enableSingleRuntimeChunk()
  .cleanupOutputBeforeBuild()
  .enableSourceMaps(!Encore.isProduction())
  .enableVersioning(Encore.isProduction())
  .enableReactPreset()
  .enableSassLoader()
;

module.exports = Encore.getWebpackConfig();
