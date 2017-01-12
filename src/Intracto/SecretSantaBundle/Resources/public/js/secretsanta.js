$(document).ready(function() {
    $('.lang__selection select').on('change', changeLanguage);
    $('.mobile__lang__selection select').on('change', changeLanguage);
});

function changeLanguage(e) {
    window.location = $(this).val();
}
