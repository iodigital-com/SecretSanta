grecaptcha.ready(function () {
    grecaptcha.execute('6LcCY38UAAAAAJi1PNBQMLiG5-jdyejrpaVhVzPe', {action: 'contact'}).then(function (token) {
        document.querySelector('.js-recaptchaToken').value = token;
    });
});