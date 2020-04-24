var Encore = require('@symfony/webpack-encore');

Encore
  // directory where compiled assets will be stored
  .setOutputPath('web/build/')
  // public path used by the web server to access the output path
  .setPublicPath('/build')
  .addStyleEntry('layout', './assets/css/layout.scss')

  .addEntry('validar-info', './assets/js/institucion_educativa/ValidarInfo/index.js')
  .addEntry('solicitudes', './assets/js/IEDetalleSolicitud/solicitudes.js')
  .addEntry('inicio', './assets/js/IEInicio/index.js')
  .addEntry('multiple', './assets/js/IEDetalleSolicitudMultiple/index.js')
  .addEntry('montos', './assets/js/IERegistrarMontos/index.js')
  .enableSingleRuntimeChunk()
  .cleanupOutputBeforeBuild()
  .enableSourceMaps(!Encore.isProduction())
  .enableVersioning(Encore.isProduction())
  .enableReactPreset()
  .enableSassLoader()
;

module.exports = Encore.getWebpackConfig();
