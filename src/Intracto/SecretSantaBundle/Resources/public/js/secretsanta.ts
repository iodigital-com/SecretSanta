// require jQuery normally
const jquery = require('jquery');

// create global $ and jQuery variables
(global as any).$ = (global as any).jQuery = jquery;

$(document).ready(function() {
    $('.lang__selection select').on('change', changeLanguage);
    $('.mobile__lang__selection select').on('change', changeLanguage);
});

function changeLanguage(this: HTMLSelectElement) {
    window.location.href = $(this).val() as string;
}
