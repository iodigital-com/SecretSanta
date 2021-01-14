// webpack.config.js
var Encore = require('@symfony/webpack-encore');
var path = require('path');

Encore
    .setOutputPath('public/build/')
    .setPublicPath('/build')

    .cleanupOutputBeforeBuild()

    .enableSassLoader()
    .enableVersioning()

    // allow legacy applications to use $/jQuery as a global variable
    .autoProvidejQuery()
    .enableSingleRuntimeChunk()

    .createSharedEntry('js/vendor', './assets/js/vendors.js')
    .addEntry('js/modernizr.custom', './assets/js/modernizr.custom.min.js')
    .addEntry('js/party.create', './assets/js/party.create.js')
    .addEntry('js/party.import', './assets/js/party.import.js')

    .addEntry('js/party.manage', './assets/js/party.manage.js')

    .addEntry('js/secretsanta', './assets/js/secretsanta.js')
    .addEntry('js/wishlist', './assets/js/wishlist.js')

    .addEntry('js/recaptcha', './assets/js/recaptcha.js')

    .addStyleEntry('css/main', [
        './assets/scss/main.scss',
        'jquery-ui/themes/base/core.css',
        'jquery-ui/themes/base/sortable.css',
    ])

    .addStyleEntry('css/update', './assets/css/update.css')
    .addStyleEntry('css/report', './assets/css/report.css')
    .addStyleEntry('css/mediaqueries', './assets/css/mediaqueries.css')

    .enableSourceMaps(!Encore.isProduction())

    .copyFiles({
        from: './assets/img',
        to: 'images/[path][name].[hash:8].[ext]',
    })
;

var config = Encore.getWebpackConfig();

config.resolve = {
    alias: {
        '@': path.resolve(__dirname, 'assets/'),
        AppConfig: path.resolve(__dirname, 'config/'),
    }
};

module.exports = config;
