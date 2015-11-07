//$("#header").click(function() {
//    window.location = $(this).find("a#homelink").attr("href");
//    return false;
//});

$(document).ready(function() {
    $('.lang__selection select').on('change', changeLanguage);
});

function changeLanguage(e) {
    window.location = $(this).val();
}
