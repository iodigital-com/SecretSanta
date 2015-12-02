$(".header__left").click(function() {
    window.location = $('#header').find("a#homelink").attr("href");
    return false;
});

$(document).ready(function() {
    $('.lang__selection select').on('change', changeLanguage);
});

function changeLanguage(e) {
    window.location = $(this).val();
}
