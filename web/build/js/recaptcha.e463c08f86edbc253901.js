webpackJsonp([6],{

/***/ "./app/config/recaptcha_secrets.json":
/*!*******************************************!*\
  !*** ./app/config/recaptcha_secrets.json ***!
  \*******************************************/
/*! no static exports found */
/*! exports used: default */
/***/ (function(module, exports) {

module.exports = {"key":"6LcCY38UAAAAAJi1PNBQMLiG5-jdyejrpaVhVzPe","secret_key":"6LcCY38UAAAAAMXtsofuXSnt2PBQLeegZCWDrRCo","action":"contact","threshold":0.9}

/***/ }),

/***/ "./src/Intracto/SecretSantaBundle/Resources/public/js/recaptcha.js":
/*!*************************************************************************!*\
  !*** ./src/Intracto/SecretSantaBundle/Resources/public/js/recaptcha.js ***!
  \*************************************************************************/
/*! exports provided:  */
/*! all exports used */
/***/ (function(module, __webpack_exports__, __webpack_require__) {

"use strict";
Object.defineProperty(__webpack_exports__, "__esModule", { value: true });
/* harmony import */ var __WEBPACK_IMPORTED_MODULE_0_AppConfig_recaptcha_secrets_json__ = __webpack_require__(/*! AppConfig/recaptcha_secrets.json */ "./app/config/recaptcha_secrets.json");
/* harmony import */ var __WEBPACK_IMPORTED_MODULE_0_AppConfig_recaptcha_secrets_json___default = __webpack_require__.n(__WEBPACK_IMPORTED_MODULE_0_AppConfig_recaptcha_secrets_json__);


window.grecaptcha.ready(function () {
    grecaptcha.execute(__WEBPACK_IMPORTED_MODULE_0_AppConfig_recaptcha_secrets_json___default.a.key, { action: __WEBPACK_IMPORTED_MODULE_0_AppConfig_recaptcha_secrets_json___default.a.action }).then(function (token) {
        document.querySelector('.js-recaptchaToken').value = token;
    });
});

/***/ })

},["./src/Intracto/SecretSantaBundle/Resources/public/js/recaptcha.js"]);
//# sourceMappingURL=data:application/json;charset=utf-8;base64,eyJ2ZXJzaW9uIjozLCJzb3VyY2VzIjpbIndlYnBhY2s6Ly8vLi9hcHAvY29uZmlnL3JlY2FwdGNoYV9zZWNyZXRzLmpzb24iLCJ3ZWJwYWNrOi8vLy4vc3JjL0ludHJhY3RvL1NlY3JldFNhbnRhQnVuZGxlL1Jlc291cmNlcy9wdWJsaWMvanMvcmVjYXB0Y2hhLmpzIl0sIm5hbWVzIjpbIndpbmRvdyIsImdyZWNhcHRjaGEiLCJyZWFkeSIsImV4ZWN1dGUiLCJSZWNhcHRjaGFTZWNyZXRzIiwia2V5IiwiYWN0aW9uIiwidGhlbiIsInRva2VuIiwiZG9jdW1lbnQiLCJxdWVyeVNlbGVjdG9yIiwidmFsdWUiXSwibWFwcGluZ3MiOiI7Ozs7Ozs7Ozs7QUFBQSxrQkFBa0IsNEk7Ozs7Ozs7Ozs7Ozs7Ozs7QUNBbEI7O0FBRUFBLE9BQU9DLFVBQVAsQ0FBa0JDLEtBQWxCLENBQXdCLFlBQVk7QUFDaENELGVBQVdFLE9BQVgsQ0FBbUIsd0VBQUFDLENBQWlCQyxHQUFwQyxFQUF5QyxFQUFDQyxRQUFRLHdFQUFBRixDQUFpQkUsTUFBMUIsRUFBekMsRUFBNEVDLElBQTVFLENBQWlGLFVBQVVDLEtBQVYsRUFBaUI7QUFDOUZDLGlCQUFTQyxhQUFULENBQXVCLG9CQUF2QixFQUE2Q0MsS0FBN0MsR0FBcURILEtBQXJEO0FBQ0gsS0FGRDtBQUdILENBSkQsRSIsImZpbGUiOiJqcy9yZWNhcHRjaGEuZTQ2M2MwOGY4NmVkYmMyNTM5MDEuanMiLCJzb3VyY2VzQ29udGVudCI6WyJtb2R1bGUuZXhwb3J0cyA9IHtcImtleVwiOlwiNkxjQ1kzOFVBQUFBQUppMVBOQlFNTGlHNS1qZHllanJwYVZoVnpQZVwiLFwic2VjcmV0X2tleVwiOlwiNkxjQ1kzOFVBQUFBQU1YdHNvZnVYU250MlBCUUxlZWdaQ1dEclJDb1wiLFwiYWN0aW9uXCI6XCJjb250YWN0XCIsXCJ0aHJlc2hvbGRcIjowLjl9XG5cblxuLy8vLy8vLy8vLy8vLy8vLy8vXG4vLyBXRUJQQUNLIEZPT1RFUlxuLy8gLi9hcHAvY29uZmlnL3JlY2FwdGNoYV9zZWNyZXRzLmpzb25cbi8vIG1vZHVsZSBpZCA9IC4vYXBwL2NvbmZpZy9yZWNhcHRjaGFfc2VjcmV0cy5qc29uXG4vLyBtb2R1bGUgY2h1bmtzID0gNiIsImltcG9ydCBSZWNhcHRjaGFTZWNyZXRzIGZyb20gJ0FwcENvbmZpZy9yZWNhcHRjaGFfc2VjcmV0cy5qc29uJ1xuXG53aW5kb3cuZ3JlY2FwdGNoYS5yZWFkeShmdW5jdGlvbiAoKSB7XG4gICAgZ3JlY2FwdGNoYS5leGVjdXRlKFJlY2FwdGNoYVNlY3JldHMua2V5LCB7YWN0aW9uOiBSZWNhcHRjaGFTZWNyZXRzLmFjdGlvbn0pLnRoZW4oZnVuY3Rpb24gKHRva2VuKSB7XG4gICAgICAgIGRvY3VtZW50LnF1ZXJ5U2VsZWN0b3IoJy5qcy1yZWNhcHRjaGFUb2tlbicpLnZhbHVlID0gdG9rZW47XG4gICAgfSk7XG59KTtcblxuXG4vLyBXRUJQQUNLIEZPT1RFUiAvL1xuLy8gLi9zcmMvSW50cmFjdG8vU2VjcmV0U2FudGFCdW5kbGUvUmVzb3VyY2VzL3B1YmxpYy9qcy9yZWNhcHRjaGEuanMiXSwic291cmNlUm9vdCI6IiJ9