var Encore = require('@symfony/webpack-encore');

Encore
    .setOutputPath('public/build/')
    .setPublicPath('/build')

    .enableVueLoader()

    .addEntry('appAdminSaleCollection', './assets/js/admin-sale-collection/app.js')
    .addEntry('appAdminOrder', './assets/js/admin-order/app.js')
    .addEntry('appMainCategoryShow', './assets/js/main-category-show/app.js')
//.addStyleEntry('css/app', './assets/scss/style.scss')
    .splitEntryChunks()

    .enableSingleRuntimeChunk()

    .cleanupOutputBeforeBuild()
    .enableBuildNotifications()
    .enableSourceMaps(!Encore.isProduction())
    .enableVersioning(Encore.isProduction())
    .configureBabel(() => {}, {
        useBuiltIns: 'usage',
        corejs: 3
    })
;

module.exports = Encore.getWebpackConfig();
