import RecaptchaSecrets from 'AppConfig/recaptcha_secrets.json'

window.grecaptcha.ready(function () {
    grecaptcha.execute(RecaptchaSecrets.key, {action: RecaptchaSecrets.action}).then(function (token) {
        document.querySelector('.js-recaptchaToken').value = token;
    });
});