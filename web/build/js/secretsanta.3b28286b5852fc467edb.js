webpackJsonp([4],{

/***/ "./node_modules/webpack/buildin/global.js":
/*!***********************************!*\
  !*** (webpack)/buildin/global.js ***!
  \***********************************/
/*! no static exports found */
/*! all exports used */
/***/ (function(module, exports) {

var g;

// This works in non-strict mode
g = (function() {
	return this;
})();

try {
	// This works if eval is allowed (see CSP)
	g = g || Function("return this")() || (1,eval)("this");
} catch(e) {
	// This works if the window reference is available
	if(typeof window === "object")
		g = window;
}

// g can still be undefined, but nothing to do about it...
// We return undefined, instead of nothing here, so it's
// easier to handle this case. if(!global) { ...}

module.exports = g;


/***/ }),

/***/ "./src/Intracto/SecretSantaBundle/Resources/public/js/secretsanta.js":
/*!***************************************************************************!*\
  !*** ./src/Intracto/SecretSantaBundle/Resources/public/js/secretsanta.js ***!
  \***************************************************************************/
/*! no static exports found */
/*! all exports used */
/***/ (function(module, exports, __webpack_require__) {

/* WEBPACK VAR INJECTION */(function(global) {// require jQuery normally
var $ = __webpack_require__(/*! jquery */ "./node_modules/jquery/dist/jquery.js");

// create global $ and jQuery variables
global.$ = global.jQuery = $;

$(document).ready(function () {
    $('.lang__selection select').on('change', changeLanguage);
    $('.mobile__lang__selection select').on('change', changeLanguage);
});

function changeLanguage(e) {
    window.location = $(this).val();
}
/* WEBPACK VAR INJECTION */}.call(exports, __webpack_require__(/*! ./../../../../../../node_modules/webpack/buildin/global.js */ "./node_modules/webpack/buildin/global.js")))

/***/ })

},["./src/Intracto/SecretSantaBundle/Resources/public/js/secretsanta.js"]);
//# sourceMappingURL=data:application/json;charset=utf-8;base64,eyJ2ZXJzaW9uIjozLCJzb3VyY2VzIjpbIndlYnBhY2s6Ly8vKHdlYnBhY2spL2J1aWxkaW4vZ2xvYmFsLmpzIiwid2VicGFjazovLy8uL3NyYy9JbnRyYWN0by9TZWNyZXRTYW50YUJ1bmRsZS9SZXNvdXJjZXMvcHVibGljL2pzL3NlY3JldHNhbnRhLmpzIl0sIm5hbWVzIjpbIiQiLCJyZXF1aXJlIiwiZ2xvYmFsIiwialF1ZXJ5IiwiZG9jdW1lbnQiLCJyZWFkeSIsIm9uIiwiY2hhbmdlTGFuZ3VhZ2UiLCJlIiwid2luZG93IiwibG9jYXRpb24iLCJ2YWwiXSwibWFwcGluZ3MiOiI7Ozs7Ozs7Ozs7QUFBQTs7QUFFQTtBQUNBO0FBQ0E7QUFDQSxDQUFDOztBQUVEO0FBQ0E7QUFDQTtBQUNBLENBQUM7QUFDRDtBQUNBO0FBQ0E7QUFDQTs7QUFFQTtBQUNBO0FBQ0EsNENBQTRDOztBQUU1Qzs7Ozs7Ozs7Ozs7OztBQ3BCQTtBQUNBLElBQU1BLElBQUksbUJBQUFDLENBQVEsb0RBQVIsQ0FBVjs7QUFFQTtBQUNBQyxPQUFPRixDQUFQLEdBQVdFLE9BQU9DLE1BQVAsR0FBZ0JILENBQTNCOztBQUVBQSxFQUFFSSxRQUFGLEVBQVlDLEtBQVosQ0FBa0IsWUFBVztBQUN6QkwsTUFBRSx5QkFBRixFQUE2Qk0sRUFBN0IsQ0FBZ0MsUUFBaEMsRUFBMENDLGNBQTFDO0FBQ0FQLE1BQUUsaUNBQUYsRUFBcUNNLEVBQXJDLENBQXdDLFFBQXhDLEVBQWtEQyxjQUFsRDtBQUNILENBSEQ7O0FBS0EsU0FBU0EsY0FBVCxDQUF3QkMsQ0FBeEIsRUFBMkI7QUFDdkJDLFdBQU9DLFFBQVAsR0FBa0JWLEVBQUUsSUFBRixFQUFRVyxHQUFSLEVBQWxCO0FBQ0gsQyIsImZpbGUiOiJqcy9zZWNyZXRzYW50YS4zYjI4Mjg2YjU4NTJmYzQ2N2VkYi5qcyIsInNvdXJjZXNDb250ZW50IjpbInZhciBnO1xyXG5cclxuLy8gVGhpcyB3b3JrcyBpbiBub24tc3RyaWN0IG1vZGVcclxuZyA9IChmdW5jdGlvbigpIHtcclxuXHRyZXR1cm4gdGhpcztcclxufSkoKTtcclxuXHJcbnRyeSB7XHJcblx0Ly8gVGhpcyB3b3JrcyBpZiBldmFsIGlzIGFsbG93ZWQgKHNlZSBDU1ApXHJcblx0ZyA9IGcgfHwgRnVuY3Rpb24oXCJyZXR1cm4gdGhpc1wiKSgpIHx8ICgxLGV2YWwpKFwidGhpc1wiKTtcclxufSBjYXRjaChlKSB7XHJcblx0Ly8gVGhpcyB3b3JrcyBpZiB0aGUgd2luZG93IHJlZmVyZW5jZSBpcyBhdmFpbGFibGVcclxuXHRpZih0eXBlb2Ygd2luZG93ID09PSBcIm9iamVjdFwiKVxyXG5cdFx0ZyA9IHdpbmRvdztcclxufVxyXG5cclxuLy8gZyBjYW4gc3RpbGwgYmUgdW5kZWZpbmVkLCBidXQgbm90aGluZyB0byBkbyBhYm91dCBpdC4uLlxyXG4vLyBXZSByZXR1cm4gdW5kZWZpbmVkLCBpbnN0ZWFkIG9mIG5vdGhpbmcgaGVyZSwgc28gaXQnc1xyXG4vLyBlYXNpZXIgdG8gaGFuZGxlIHRoaXMgY2FzZS4gaWYoIWdsb2JhbCkgeyAuLi59XHJcblxyXG5tb2R1bGUuZXhwb3J0cyA9IGc7XHJcblxuXG5cbi8vLy8vLy8vLy8vLy8vLy8vL1xuLy8gV0VCUEFDSyBGT09URVJcbi8vICh3ZWJwYWNrKS9idWlsZGluL2dsb2JhbC5qc1xuLy8gbW9kdWxlIGlkID0gLi9ub2RlX21vZHVsZXMvd2VicGFjay9idWlsZGluL2dsb2JhbC5qc1xuLy8gbW9kdWxlIGNodW5rcyA9IDQiLCIvLyByZXF1aXJlIGpRdWVyeSBub3JtYWxseVxuY29uc3QgJCA9IHJlcXVpcmUoJ2pxdWVyeScpO1xuXG4vLyBjcmVhdGUgZ2xvYmFsICQgYW5kIGpRdWVyeSB2YXJpYWJsZXNcbmdsb2JhbC4kID0gZ2xvYmFsLmpRdWVyeSA9ICQ7XG5cbiQoZG9jdW1lbnQpLnJlYWR5KGZ1bmN0aW9uKCkge1xuICAgICQoJy5sYW5nX19zZWxlY3Rpb24gc2VsZWN0Jykub24oJ2NoYW5nZScsIGNoYW5nZUxhbmd1YWdlKTtcbiAgICAkKCcubW9iaWxlX19sYW5nX19zZWxlY3Rpb24gc2VsZWN0Jykub24oJ2NoYW5nZScsIGNoYW5nZUxhbmd1YWdlKTtcbn0pO1xuXG5mdW5jdGlvbiBjaGFuZ2VMYW5ndWFnZShlKSB7XG4gICAgd2luZG93LmxvY2F0aW9uID0gJCh0aGlzKS52YWwoKTtcbn1cblxuXG5cbi8vIFdFQlBBQ0sgRk9PVEVSIC8vXG4vLyAuL3NyYy9JbnRyYWN0by9TZWNyZXRTYW50YUJ1bmRsZS9SZXNvdXJjZXMvcHVibGljL2pzL3NlY3JldHNhbnRhLmpzIl0sInNvdXJjZVJvb3QiOiIifQ==