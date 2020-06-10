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
  .addEntry('mis-solicitudes', './assets/js/institucion_educativa/MisSolicitudes/index.js')
  .addEntry('came_solicitud_index', './assets/js/came/solicitud/index.js')
  .addEntry('pregrado_reporte', './assets/js/pregrado/reporte/index.js')
  .addEntry('fofoe_reporte-ingresos', './assets/js/fofoe/reporte_ingresos/index.js')
  .addEntry('fofoe_reporte-oportunidad', './assets/js/fofoe/reporte_oportunidad/index.js')
  .enableSingleRuntimeChunk()
  .cleanupOutputBeforeBuild()
  .enableSourceMaps(!Encore.isProduction())
  .enableVersioning(Encore.isProduction())
  .enableReactPreset()
  .enableSassLoader()
;

module.exports = Encore.getWebpackConfig();
