// require jQuery normally
const $ = require('jquery');

// create global $ and jQuery variables
global.$ = global.jQuery = $;

$(document).ready(function() {
    $('.lang__selection select').on('change', changeLanguage);
    $('.mobile__lang__selection select').on('change', changeLanguage);
});

function changeLanguage(e) {
    window.location = $(this).val();
}
