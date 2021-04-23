var Encore = require('@symfony/webpack-encore');

Encore
    .setOutputPath('public/build/')
    .setPublicPath('/build')
    // https://symfony.com/doc/current/frontend/encore/vuejs.html#runtime-compiler-build
    .enableVueLoader(() => {}, {
        runtimeCompilerBuild: false
    })

    .addEntry('appAdminSaleCollection', './assets/js/section/admin/admin-sale-collection/app.js')
    .addEntry('appAdminOrder', './assets/js/section/admin/admin-order/app.js')
    .addEntry('appMainCategoryShow', './assets/js/section/main/main-category-show/app.js')
    .addEntry('appMenuCart', './assets/js/section/main/menu-cart/app.js')
    .addEntry('appMainCartShow', './assets/js/section/main/main-cart-show/app.js')

    .addEntry('section-main', './assets/section-main.js')
    .addEntry('section-admin', './assets/section-admin.js')
    .autoProvidejQuery()
    .splitEntryChunks()

    .copyFiles({
        from: './assets/images',
        to: 'images/[path][name].[hash:8].[ext]',
    })

    .enableSingleRuntimeChunk()
    .enableSassLoader()

    .cleanupOutputBeforeBuild()
    .enableBuildNotifications()
    .enableSourceMaps(!Encore.isProduction())
    .enableVersioning(Encore.isProduction())
    .configureBabel(() => {}, {
        useBuiltIns: 'usage',
        corejs: 3
    })
    .configureImageRule({
        // tell Webpack it should consider inlining
        type: 'asset',
        //maxSize: 4 * 1024, // 4 kb - the default is 8kb
    })

    .configureFontRule({
        type: 'asset',
        //maxSize: 4 * 1024
    })
;

module.exports = Encore.getWebpackConfig();
