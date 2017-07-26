// webpack.config.js
var Encore = require('@symfony/webpack-encore');

Encore
    .setOutputPath('web/build/')
    .setPublicPath('/build')

    .cleanupOutputBeforeBuild()

    .enableSassLoader()
    .enableVersioning()

    // allow legacy applications to use $/jQuery as a global variable
    .autoProvidejQuery()

    .autoProvideVariables({
        "window.jQuery": "jquery"
    })

    .createSharedEntry('js/vendor', ['jquery', 'bootstrap', './src/Intracto/SecretSantaBundle/Resources/public/js/modernizr.custom.min.js'])

    .addEntry('js/party.create', './src/Intracto/SecretSantaBundle/Resources/public/js/party.create.js')
    .addEntry('js/party.import', './src/Intracto/SecretSantaBundle/Resources/public/js/party.import.js')

    .addEntry('js/secretsanta', './src/Intracto/SecretSantaBundle/Resources/public/js/secretsanta.js')
    .addEntry('js/wishlist', './src/Intracto/SecretSantaBundle/Resources/public/js/wishlist.js')

    .addStyleEntry('css/main', [
        './src/Intracto/SecretSantaBundle/Resources/public/scss/main.scss',
        './src/Intracto/SecretSantaBundle/Resources/public/css/jquery-ui.min.css',
        './src/Intracto/SecretSantaBundle/Resources/public/css/jquery-ui.theme.min.css'
    ])

    .addStyleEntry('css/update', './src/Intracto/SecretSantaBundle/Resources/public/css/update.css')
    .addStyleEntry('css/report', './src/Intracto/SecretSantaBundle/Resources/public/css/report.css')
    .addStyleEntry('css/mediaqueries', './src/Intracto/SecretSantaBundle/Resources/public/css/mediaqueries.css')

    .enableSourceMaps(!Encore.isProduction())
;

// export the final configuration
module.exports = Encore.getWebpackConfig();
