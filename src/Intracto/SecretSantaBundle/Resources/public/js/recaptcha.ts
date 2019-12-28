import RecaptchaSecrets from 'AppConfig/recaptcha_secrets.json'

window.grecaptcha.ready(() => {
    grecaptcha
        .execute(RecaptchaSecrets.key, {action: RecaptchaSecrets.action})
        .then(token => {
            document.querySelector<HTMLInputElement>('.js-recaptchaToken')!.value = token;
        });
});