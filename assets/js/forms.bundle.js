/******/ (() => { // webpackBootstrap
/******/ 	"use strict";
/******/ 	var __webpack_modules__ = ({

/***/ "./admin/css/admin.scss":
/*!******************************!*\
  !*** ./admin/css/admin.scss ***!
  \******************************/
/***/ ((__unused_webpack_module, __webpack_exports__, __webpack_require__) => {

__webpack_require__.r(__webpack_exports__);
// extracted by mini-css-extract-plugin


/***/ }),

/***/ "./assets/css/block-campaign-single-content.scss":
/*!*******************************************************!*\
  !*** ./assets/css/block-campaign-single-content.scss ***!
  \*******************************************************/
/***/ ((__unused_webpack_module, __webpack_exports__, __webpack_require__) => {

__webpack_require__.r(__webpack_exports__);
// extracted by mini-css-extract-plugin


/***/ }),

/***/ "./assets/css/block-campaign-status-bar.scss":
/*!***************************************************!*\
  !*** ./assets/css/block-campaign-status-bar.scss ***!
  \***************************************************/
/***/ ((__unused_webpack_module, __webpack_exports__, __webpack_require__) => {

__webpack_require__.r(__webpack_exports__);
// extracted by mini-css-extract-plugin


/***/ }),

/***/ "./assets/css/donation-form.scss":
/*!***************************************!*\
  !*** ./assets/css/donation-form.scss ***!
  \***************************************/
/***/ ((__unused_webpack_module, __webpack_exports__, __webpack_require__) => {

__webpack_require__.r(__webpack_exports__);
// extracted by mini-css-extract-plugin


/***/ }),

/***/ "./assets/js/forms.js":
/*!****************************!*\
  !*** ./assets/js/forms.js ***!
  \****************************/
/***/ ((__unused_webpack_module, __webpack_exports__, __webpack_require__) => {

__webpack_require__.r(__webpack_exports__);
/* harmony import */ var _babel_runtime_helpers_classCallCheck__WEBPACK_IMPORTED_MODULE_0__ = __webpack_require__(/*! @babel/runtime/helpers/classCallCheck */ "./node_modules/@babel/runtime/helpers/esm/classCallCheck.js");
/* harmony import */ var _babel_runtime_helpers_createClass__WEBPACK_IMPORTED_MODULE_1__ = __webpack_require__(/*! @babel/runtime/helpers/createClass */ "./node_modules/@babel/runtime/helpers/esm/createClass.js");


/**
 * Donation Form
 */

(function (w) {
  'use strict';

  var donationForm = /*#__PURE__*/function () {
    function donationForm(_donationForm, options) {
      (0,_babel_runtime_helpers_classCallCheck__WEBPACK_IMPORTED_MODULE_0__["default"])(this, donationForm);
      this.fields = {};
      this.form = _donationForm;
      this.options = options;
      this.init(_donationForm, options);
    }
    return (0,_babel_runtime_helpers_createClass__WEBPACK_IMPORTED_MODULE_1__["default"])(donationForm, [{
      key: "init",
      value: function init(_donationForm2, options) {
        var _this = this;
        this.setInitFields(_donationForm2);
        this.onListenerFormFieldUpdate();

        // on change amount field
        this.form.addEventListener('input', function (event) {
          if (event.target.name === 'donation_amount') {
            _this.onUpdateAmountField(event.target.value);
          }
        });

        // on click Preset Amount
        this.form.addEventListener('click', function (event) {
          if (event.target.classList.contains('donation-form__preset-amount')) {
            _this.onClickPresetAmount(event);
          }
        });
      }
    }, {
      key: "setInitFields",
      value: function setInitFields(_donationForm3) {
        var _this2 = this;
        var self = this;
        var fields = _donationForm3.querySelectorAll('input[name]');
        fields.forEach(function (field) {
          var value = field.value;

          // validate event.target is checkbox field
          if (field.type === 'checkbox') {
            value = field.checked;
          }

          // validate event.target is radio field
          if (field.type === 'radio') {
            // get field name
            var fieldName = field.name;
            // const fieldValue = field.value;
            value = self.form.querySelector("input[name=\"".concat(fieldName, "\"]:checked")).value;
          }
          _this2.fields[field.name] = value;
        });

        // console.log('fields', this.fields);
      }
    }, {
      key: "onListenerFormFieldUpdate",
      value: function onListenerFormFieldUpdate() {
        var self = this;
        this.form.addEventListener('change', function (event) {
          self.fields[event.target.name] = event.target.value;
          var value = event.target.value;

          // validate event.target is checkbox field
          if (event.target.type === 'checkbox') {
            value = event.target.checked;
          }

          // validate event.target is radio field
          if (event.target.type === 'radio') {
            var fieldName = event.target.name;
            value = self.form.querySelector("input[name=\"".concat(fieldName, "\"]:checked")).value;
          }

          // update UI by field
          self.onUpdateUIByField(event.target.name, value);

          // console.log('fields', self.fields);
        });
      }
    }, {
      key: "onUpdateUIByField",
      value: function onUpdateUIByField(field, value) {
        // console.log('onUpdateUIByField', field, value);

        var inputField = this.form.querySelector("input[name=\"".concat(field, "\"]"));
        if (!inputField) {
          return;
        }
        if (field === 'donation_amount') {
          if (!this.onValidateValue('required', value)) {
            inputField.classList.add('error');
          } else {
            inputField.classList.remove('error');
          }
        }
        var wrapperField = inputField.closest('.donation-form__field');
        if (!wrapperField) {
          return;
        }
        if (inputField.dataset.validate) {
          var pass = this.onValidateValue(inputField.dataset.validate, value);
          if (!pass) {
            // inputField.classList.add('error');
            wrapperField.classList.add('error');
          } else {
            // inputField.classList.remove('error');
            wrapperField.classList.remove('error');
          }
        }
      }

      // on click Preset Amount
    }, {
      key: "onClickPresetAmount",
      value: function onClickPresetAmount(event) {
        event.preventDefault();
        event.stopPropagation();
        var self = this;
        var amount = event.target.dataset.amount;
        self.form.querySelector('input[name="donation_amount"]').value = amount;

        // Update UI by field
        this.onUpdateUIByField('donation_amount', amount);
        event.target.classList.add('active');
        self.form.querySelectorAll('.donation-form__preset-amount').forEach(function (presetAmount) {
          if (presetAmount !== event.target) {
            presetAmount.classList.remove('active');
          }
        });
      }

      // on update amout field
    }, {
      key: "onUpdateAmountField",
      value: function onUpdateAmountField(value) {
        // remove active
        this.form.querySelectorAll('.donation-form__preset-amount').forEach(function (presetAmount) {
          presetAmount.classList.remove('active');
        });
      }

      // validate field by type
    }, {
      key: "onValidateValue",
      value: function onValidateValue(type, value) {
        switch (type) {
          // email
          case 'email':
            return /^[^\s@]+@[^\s@]+\.[^\s@]+$/.test(value);

          // phone
          case 'phone':
            // number or string + on the first position
            return /^[0-9]+$/.test(value) || /^[a-zA-Z]+$/.test(value);

          // required
          case 'required':
            return value.trim() !== '';

          // default
          default:
            return true;
        }
      }
    }]);
  }();

  // make custom event trigger donation form and how to use it	
  /**
  * Custom event to trigger donation form initialization
  * 
  * Usage:
  * document.dispatchEvent(new CustomEvent('initDonationForm', {
  *   detail: {
  *     formSelector: '.my-custom-donation-form', // Optional: target specific forms
  *     options: {} // Optional: pass configuration options
  *   }
  * }));
  */
  document.addEventListener('initDonationForm', function (event) {
    var _ref = event.detail || {},
      formSelector = _ref.formSelector,
      options = _ref.options;
    if (formSelector) {
      // Initialize specific forms matching the selector
      document.querySelectorAll(formSelector).forEach(function (form) {
        new donationForm(form, options);
      });
    } else {
      // Initialize all donation forms if no selector provided
      document.querySelectorAll('.donation-form').forEach(function (form) {
        new donationForm(form, options);
      });
    }
    console.log('Donation forms initialized via custom event');
  });
  var initDonationForm = function initDonationForm(formSelector, options) {
    document.dispatchEvent(new CustomEvent('initDonationForm', {
      detail: {
        formSelector: formSelector,
        options: options
      }
    }));
  };
  w.initDonationForm = initDonationForm;

  // dom loaded
  document.addEventListener('DOMContentLoaded', function () {
    // initialize all donation forms
    initDonationForm('.donation-form', {});
  });
})(window);

/***/ }),

/***/ "./node_modules/@babel/runtime/helpers/esm/classCallCheck.js":
/*!*******************************************************************!*\
  !*** ./node_modules/@babel/runtime/helpers/esm/classCallCheck.js ***!
  \*******************************************************************/
/***/ ((__unused_webpack___webpack_module__, __webpack_exports__, __webpack_require__) => {

__webpack_require__.r(__webpack_exports__);
/* harmony export */ __webpack_require__.d(__webpack_exports__, {
/* harmony export */   "default": () => (/* binding */ _classCallCheck)
/* harmony export */ });
function _classCallCheck(a, n) {
  if (!(a instanceof n)) throw new TypeError("Cannot call a class as a function");
}


/***/ }),

/***/ "./node_modules/@babel/runtime/helpers/esm/createClass.js":
/*!****************************************************************!*\
  !*** ./node_modules/@babel/runtime/helpers/esm/createClass.js ***!
  \****************************************************************/
/***/ ((__unused_webpack___webpack_module__, __webpack_exports__, __webpack_require__) => {

__webpack_require__.r(__webpack_exports__);
/* harmony export */ __webpack_require__.d(__webpack_exports__, {
/* harmony export */   "default": () => (/* binding */ _createClass)
/* harmony export */ });
/* harmony import */ var _toPropertyKey_js__WEBPACK_IMPORTED_MODULE_0__ = __webpack_require__(/*! ./toPropertyKey.js */ "./node_modules/@babel/runtime/helpers/esm/toPropertyKey.js");

function _defineProperties(e, r) {
  for (var t = 0; t < r.length; t++) {
    var o = r[t];
    o.enumerable = o.enumerable || !1, o.configurable = !0, "value" in o && (o.writable = !0), Object.defineProperty(e, (0,_toPropertyKey_js__WEBPACK_IMPORTED_MODULE_0__["default"])(o.key), o);
  }
}
function _createClass(e, r, t) {
  return r && _defineProperties(e.prototype, r), t && _defineProperties(e, t), Object.defineProperty(e, "prototype", {
    writable: !1
  }), e;
}


/***/ }),

/***/ "./node_modules/@babel/runtime/helpers/esm/toPrimitive.js":
/*!****************************************************************!*\
  !*** ./node_modules/@babel/runtime/helpers/esm/toPrimitive.js ***!
  \****************************************************************/
/***/ ((__unused_webpack___webpack_module__, __webpack_exports__, __webpack_require__) => {

__webpack_require__.r(__webpack_exports__);
/* harmony export */ __webpack_require__.d(__webpack_exports__, {
/* harmony export */   "default": () => (/* binding */ toPrimitive)
/* harmony export */ });
/* harmony import */ var _typeof_js__WEBPACK_IMPORTED_MODULE_0__ = __webpack_require__(/*! ./typeof.js */ "./node_modules/@babel/runtime/helpers/esm/typeof.js");

function toPrimitive(t, r) {
  if ("object" != (0,_typeof_js__WEBPACK_IMPORTED_MODULE_0__["default"])(t) || !t) return t;
  var e = t[Symbol.toPrimitive];
  if (void 0 !== e) {
    var i = e.call(t, r || "default");
    if ("object" != (0,_typeof_js__WEBPACK_IMPORTED_MODULE_0__["default"])(i)) return i;
    throw new TypeError("@@toPrimitive must return a primitive value.");
  }
  return ("string" === r ? String : Number)(t);
}


/***/ }),

/***/ "./node_modules/@babel/runtime/helpers/esm/toPropertyKey.js":
/*!******************************************************************!*\
  !*** ./node_modules/@babel/runtime/helpers/esm/toPropertyKey.js ***!
  \******************************************************************/
/***/ ((__unused_webpack___webpack_module__, __webpack_exports__, __webpack_require__) => {

__webpack_require__.r(__webpack_exports__);
/* harmony export */ __webpack_require__.d(__webpack_exports__, {
/* harmony export */   "default": () => (/* binding */ toPropertyKey)
/* harmony export */ });
/* harmony import */ var _typeof_js__WEBPACK_IMPORTED_MODULE_0__ = __webpack_require__(/*! ./typeof.js */ "./node_modules/@babel/runtime/helpers/esm/typeof.js");
/* harmony import */ var _toPrimitive_js__WEBPACK_IMPORTED_MODULE_1__ = __webpack_require__(/*! ./toPrimitive.js */ "./node_modules/@babel/runtime/helpers/esm/toPrimitive.js");


function toPropertyKey(t) {
  var i = (0,_toPrimitive_js__WEBPACK_IMPORTED_MODULE_1__["default"])(t, "string");
  return "symbol" == (0,_typeof_js__WEBPACK_IMPORTED_MODULE_0__["default"])(i) ? i : i + "";
}


/***/ }),

/***/ "./node_modules/@babel/runtime/helpers/esm/typeof.js":
/*!***********************************************************!*\
  !*** ./node_modules/@babel/runtime/helpers/esm/typeof.js ***!
  \***********************************************************/
/***/ ((__unused_webpack___webpack_module__, __webpack_exports__, __webpack_require__) => {

__webpack_require__.r(__webpack_exports__);
/* harmony export */ __webpack_require__.d(__webpack_exports__, {
/* harmony export */   "default": () => (/* binding */ _typeof)
/* harmony export */ });
function _typeof(o) {
  "@babel/helpers - typeof";

  return _typeof = "function" == typeof Symbol && "symbol" == typeof Symbol.iterator ? function (o) {
    return typeof o;
  } : function (o) {
    return o && "function" == typeof Symbol && o.constructor === Symbol && o !== Symbol.prototype ? "symbol" : typeof o;
  }, _typeof(o);
}


/***/ })

/******/ 	});
/************************************************************************/
/******/ 	// The module cache
/******/ 	var __webpack_module_cache__ = {};
/******/ 	
/******/ 	// The require function
/******/ 	function __webpack_require__(moduleId) {
/******/ 		// Check if module is in cache
/******/ 		var cachedModule = __webpack_module_cache__[moduleId];
/******/ 		if (cachedModule !== undefined) {
/******/ 			return cachedModule.exports;
/******/ 		}
/******/ 		// Create a new module (and put it into the cache)
/******/ 		var module = __webpack_module_cache__[moduleId] = {
/******/ 			// no module.id needed
/******/ 			// no module.loaded needed
/******/ 			exports: {}
/******/ 		};
/******/ 	
/******/ 		// Execute the module function
/******/ 		__webpack_modules__[moduleId](module, module.exports, __webpack_require__);
/******/ 	
/******/ 		// Return the exports of the module
/******/ 		return module.exports;
/******/ 	}
/******/ 	
/******/ 	// expose the modules object (__webpack_modules__)
/******/ 	__webpack_require__.m = __webpack_modules__;
/******/ 	
/************************************************************************/
/******/ 	/* webpack/runtime/chunk loaded */
/******/ 	(() => {
/******/ 		var deferred = [];
/******/ 		__webpack_require__.O = (result, chunkIds, fn, priority) => {
/******/ 			if(chunkIds) {
/******/ 				priority = priority || 0;
/******/ 				for(var i = deferred.length; i > 0 && deferred[i - 1][2] > priority; i--) deferred[i] = deferred[i - 1];
/******/ 				deferred[i] = [chunkIds, fn, priority];
/******/ 				return;
/******/ 			}
/******/ 			var notFulfilled = Infinity;
/******/ 			for (var i = 0; i < deferred.length; i++) {
/******/ 				var [chunkIds, fn, priority] = deferred[i];
/******/ 				var fulfilled = true;
/******/ 				for (var j = 0; j < chunkIds.length; j++) {
/******/ 					if ((priority & 1 === 0 || notFulfilled >= priority) && Object.keys(__webpack_require__.O).every((key) => (__webpack_require__.O[key](chunkIds[j])))) {
/******/ 						chunkIds.splice(j--, 1);
/******/ 					} else {
/******/ 						fulfilled = false;
/******/ 						if(priority < notFulfilled) notFulfilled = priority;
/******/ 					}
/******/ 				}
/******/ 				if(fulfilled) {
/******/ 					deferred.splice(i--, 1)
/******/ 					var r = fn();
/******/ 					if (r !== undefined) result = r;
/******/ 				}
/******/ 			}
/******/ 			return result;
/******/ 		};
/******/ 	})();
/******/ 	
/******/ 	/* webpack/runtime/define property getters */
/******/ 	(() => {
/******/ 		// define getter functions for harmony exports
/******/ 		__webpack_require__.d = (exports, definition) => {
/******/ 			for(var key in definition) {
/******/ 				if(__webpack_require__.o(definition, key) && !__webpack_require__.o(exports, key)) {
/******/ 					Object.defineProperty(exports, key, { enumerable: true, get: definition[key] });
/******/ 				}
/******/ 			}
/******/ 		};
/******/ 	})();
/******/ 	
/******/ 	/* webpack/runtime/hasOwnProperty shorthand */
/******/ 	(() => {
/******/ 		__webpack_require__.o = (obj, prop) => (Object.prototype.hasOwnProperty.call(obj, prop))
/******/ 	})();
/******/ 	
/******/ 	/* webpack/runtime/make namespace object */
/******/ 	(() => {
/******/ 		// define __esModule on exports
/******/ 		__webpack_require__.r = (exports) => {
/******/ 			if(typeof Symbol !== 'undefined' && Symbol.toStringTag) {
/******/ 				Object.defineProperty(exports, Symbol.toStringTag, { value: 'Module' });
/******/ 			}
/******/ 			Object.defineProperty(exports, '__esModule', { value: true });
/******/ 		};
/******/ 	})();
/******/ 	
/******/ 	/* webpack/runtime/jsonp chunk loading */
/******/ 	(() => {
/******/ 		// no baseURI
/******/ 		
/******/ 		// object to store loaded and loading chunks
/******/ 		// undefined = chunk not loaded, null = chunk preloaded/prefetched
/******/ 		// [resolve, reject, Promise] = chunk loading, 0 = chunk loaded
/******/ 		var installedChunks = {
/******/ 			"/assets/js/forms.bundle": 0,
/******/ 			"assets/css/admin.bundle": 0,
/******/ 			"assets/css/donation-form.bundle": 0,
/******/ 			"assets/css/block-campaign-status-bar.bundle": 0,
/******/ 			"assets/css/block-campaign-single-content.bundle": 0
/******/ 		};
/******/ 		
/******/ 		// no chunk on demand loading
/******/ 		
/******/ 		// no prefetching
/******/ 		
/******/ 		// no preloaded
/******/ 		
/******/ 		// no HMR
/******/ 		
/******/ 		// no HMR manifest
/******/ 		
/******/ 		__webpack_require__.O.j = (chunkId) => (installedChunks[chunkId] === 0);
/******/ 		
/******/ 		// install a JSONP callback for chunk loading
/******/ 		var webpackJsonpCallback = (parentChunkLoadingFunction, data) => {
/******/ 			var [chunkIds, moreModules, runtime] = data;
/******/ 			// add "moreModules" to the modules object,
/******/ 			// then flag all "chunkIds" as loaded and fire callback
/******/ 			var moduleId, chunkId, i = 0;
/******/ 			if(chunkIds.some((id) => (installedChunks[id] !== 0))) {
/******/ 				for(moduleId in moreModules) {
/******/ 					if(__webpack_require__.o(moreModules, moduleId)) {
/******/ 						__webpack_require__.m[moduleId] = moreModules[moduleId];
/******/ 					}
/******/ 				}
/******/ 				if(runtime) var result = runtime(__webpack_require__);
/******/ 			}
/******/ 			if(parentChunkLoadingFunction) parentChunkLoadingFunction(data);
/******/ 			for(;i < chunkIds.length; i++) {
/******/ 				chunkId = chunkIds[i];
/******/ 				if(__webpack_require__.o(installedChunks, chunkId) && installedChunks[chunkId]) {
/******/ 					installedChunks[chunkId][0]();
/******/ 				}
/******/ 				installedChunks[chunkId] = 0;
/******/ 			}
/******/ 			return __webpack_require__.O(result);
/******/ 		}
/******/ 		
/******/ 		var chunkLoadingGlobal = self["webpackChunkgiftflowwp"] = self["webpackChunkgiftflowwp"] || [];
/******/ 		chunkLoadingGlobal.forEach(webpackJsonpCallback.bind(null, 0));
/******/ 		chunkLoadingGlobal.push = webpackJsonpCallback.bind(null, chunkLoadingGlobal.push.bind(chunkLoadingGlobal));
/******/ 	})();
/******/ 	
/************************************************************************/
/******/ 	
/******/ 	// startup
/******/ 	// Load entry module and return exports
/******/ 	// This entry module depends on other loaded chunks and execution need to be delayed
/******/ 	__webpack_require__.O(undefined, ["assets/css/admin.bundle","assets/css/donation-form.bundle","assets/css/block-campaign-status-bar.bundle","assets/css/block-campaign-single-content.bundle"], () => (__webpack_require__("./assets/js/forms.js")))
/******/ 	__webpack_require__.O(undefined, ["assets/css/admin.bundle","assets/css/donation-form.bundle","assets/css/block-campaign-status-bar.bundle","assets/css/block-campaign-single-content.bundle"], () => (__webpack_require__("./assets/css/block-campaign-single-content.scss")))
/******/ 	__webpack_require__.O(undefined, ["assets/css/admin.bundle","assets/css/donation-form.bundle","assets/css/block-campaign-status-bar.bundle","assets/css/block-campaign-single-content.bundle"], () => (__webpack_require__("./assets/css/block-campaign-status-bar.scss")))
/******/ 	__webpack_require__.O(undefined, ["assets/css/admin.bundle","assets/css/donation-form.bundle","assets/css/block-campaign-status-bar.bundle","assets/css/block-campaign-single-content.bundle"], () => (__webpack_require__("./assets/css/donation-form.scss")))
/******/ 	var __webpack_exports__ = __webpack_require__.O(undefined, ["assets/css/admin.bundle","assets/css/donation-form.bundle","assets/css/block-campaign-status-bar.bundle","assets/css/block-campaign-single-content.bundle"], () => (__webpack_require__("./admin/css/admin.scss")))
/******/ 	__webpack_exports__ = __webpack_require__.O(__webpack_exports__);
/******/ 	
/******/ })()
;