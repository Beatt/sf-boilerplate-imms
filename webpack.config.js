var Encore = require('@symfony/webpack-encore');

Encore
    // directory where compiled assets will be stored
    .setOutputPath('web/build/')
    // public path used by the web server to access the output path
    .setPublicPath('/build')
    .addEntry('app', './assets/js/app.js')
    .addEntry('came_solicitud_index', './assets/js/came/solicitud/index.js')
    .enableSingleRuntimeChunk()
    .cleanupOutputBeforeBuild()
    .enableSourceMaps(!Encore.isProduction())
    .enableVersioning(Encore.isProduction())
    .enableReactPreset()
    .enableSassLoader()
;

module.exports = Encore.getWebpackConfig();
