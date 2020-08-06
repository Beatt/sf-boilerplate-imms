var Encore = require('@symfony/webpack-encore');
const SUBFOLDER_NAME = 'fofoe';

if(Encore.isProduction()) {
  Encore
    .setPublicPath(`/${SUBFOLDER_NAME}/build`)
    .setManifestKeyPrefix('build')
} else {
  Encore
    .setPublicPath('/build')
}

Encore
    // directory where compiled assets will be stored
    .setOutputPath('web/build/')
    .copyFiles({from: './assets/images', to: 'images/[path][name].[ext]'})
    .addStyleEntry('layout', './assets/css/layout.scss')
    // IE
    .addEntry('ie.perfil', './assets/js/ie/Perfil/index.js')
    .addEntry('ie.detalle.solicitud', './assets/js/ie/DetalleSolicitud/index.js')
    .addEntry('ie.registrar.montos', './assets/js/ie/RegistrarMontos/index.js')
    .addEntry('ie.inicio', './assets/js/ie/Inicio/index.js')
    .addEntry('ie.detalle.forma.pago', './assets/js/ie/DetalleFormaPago/index.js')
    .addEntry('ie.detalle.solicitud.multiple', './assets/js/ie/DetalleSolicitudMultiple/index.js')
    .addEntry('ie.cargar.comprobante', './assets/js/ie/RegistrarPago/index.js')
    .addEntry('ie.seleccionar.forma.pago', './assets/js/ie/SeleccionarFormaPago/index.js')
    .addEntry('ie.correccion.pago.fofoe', './assets/js/ie/EditarPagoFofoe/index.js')
    .addEntry('ie.detalle.ie', './assets/js/fofoe/DetalleIE/index.js')
    .addEntry('ie.carga.comprobante.pago', './assets/js/ie/CargaDeComprobanteDePago/index.js')

    // CAME
    .addEntry('came_solicitud_index', './assets/js/came/solicitud/index.js')
    .addEntry('pregrado_reporte', './assets/js/pregrado/reporte/index.js')
    .addEntry('enfermeria_reporte-ciclos', './assets/js/enfermeria/reporte_ciclos/index.js')

    // FOFOE
    .addEntry('fofoe_referencia', './assets/js/fofoe/referencia/index.js')
    .addEntry('fofoe.registrar.factura', './assets/js/fofoe/RegistrarFactura/index.js')
    .addEntry('fofoe_reportes-inicio', './assets/js/fofoe/reportes-inicio/index.js')
    .addEntry('fofoe_reporte-oportunidad', './assets/js/fofoe/reporte_oportunidad/index.js')
    .addEntry('fofoe_reporte-ingresos', './assets/js/fofoe/reporte_ingresos/index.js')
    .addEntry('validacion.de.pago', './assets/js/fofoe/ValidacionDePago/index.js')
    .addStyleEntry('layout-formato-fofoe', './assets/css/formatos/fofoe/layout.scss')
    .enableSingleRuntimeChunk()
    .cleanupOutputBeforeBuild()
    .enableSourceMaps(!Encore.isProduction())
    .enableVersioning(Encore.isProduction())
    .enableReactPreset()
    .enableSassLoader()
;

module.exports = Encore.getWebpackConfig();
