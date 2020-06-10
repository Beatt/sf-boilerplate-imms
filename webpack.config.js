var Encore = require('@symfony/webpack-encore');

Encore
  // directory where compiled assets will be stored
  .setOutputPath('web/build/')
  // public path used by the web server to access the output path
  .setPublicPath('/build')
  .copyFiles( {from: './assets/images', to: 'images/[path][name].[ext]'})
  .addStyleEntry('layout', './assets/css/layout.scss')
  .addEntry('ie.perfil', './assets/js/institucion_educativa/Perfil/index.js')
  .addEntry('ie.detalle.solicitud', './assets/js/institucion_educativa/DetalleSolicitud/index.js')
  .addEntry('detalle_solicitud_multiple', './assets/js/institucion_educativa/DetalleSolicitudMultiple/index.js')
  .addEntry('ie.registrar.montos', './assets/js/institucion_educativa/RegistrarMontos/index.js')
  .addEntry('registrar-pagos', './assets/js/institucion_educativa/RegistrarPago/index.js')
  .addEntry('ie.inicio', './assets/js/institucion_educativa/Inicio/index.js')
  .addEntry('came_solicitud_index', './assets/js/came/solicitud/index.js')
  .addEntry('pregrado_reporte', './assets/js/pregrado/reporte/index.js')
  .addEntry('seleccionar_forma_pago', './assets/js/institucion_educativa/SeleccionarFormaPago/index.js')
  .addEntry('ie.detalle.forma.pago', './assets/js/institucion_educativa/DetalleFormaPago/index.js')
  .addStyleEntry('layout-formato-fofoe', './assets/css/formatos/fofoe/layout.scss')
  .enableSingleRuntimeChunk()
  .cleanupOutputBeforeBuild()
  .enableSourceMaps(!Encore.isProduction())
  .enableVersioning(Encore.isProduction())
  .enableReactPreset()
  .enableSassLoader()
;

module.exports = Encore.getWebpackConfig();
