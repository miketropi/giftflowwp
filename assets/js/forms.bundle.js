/******/ (() => { // webpackBootstrap
/******/ 	"use strict";
/******/ 	var __webpack_modules__ = ({

/***/ "./node_modules/@babel/runtime/helpers/esm/asyncToGenerator.js":
/*!*********************************************************************!*\
  !*** ./node_modules/@babel/runtime/helpers/esm/asyncToGenerator.js ***!
  \*********************************************************************/
/***/ ((__unused_webpack___webpack_module__, __webpack_exports__, __webpack_require__) => {

__webpack_require__.r(__webpack_exports__);
/* harmony export */ __webpack_require__.d(__webpack_exports__, {
/* harmony export */   "default": () => (/* binding */ _asyncToGenerator)
/* harmony export */ });
function asyncGeneratorStep(n, t, e, r, o, a, c) {
  try {
    var i = n[a](c),
      u = i.value;
  } catch (n) {
    return void e(n);
  }
  i.done ? t(u) : Promise.resolve(u).then(r, o);
}
function _asyncToGenerator(n) {
  return function () {
    var t = this,
      e = arguments;
    return new Promise(function (r, o) {
      var a = n.apply(t, e);
      function _next(n) {
        asyncGeneratorStep(a, r, o, _next, _throw, "next", n);
      }
      function _throw(n) {
        asyncGeneratorStep(a, r, o, _next, _throw, "throw", n);
      }
      _next(void 0);
    });
  };
}


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
/************************************************************************/
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
/************************************************************************/
var __webpack_exports__ = {};
// This entry needs to be wrapped in an IIFE because it needs to be isolated against other modules in the chunk.
(() => {
/*!****************************!*\
  !*** ./assets/js/forms.js ***!
  \****************************/
__webpack_require__.r(__webpack_exports__);
/* harmony import */ var _babel_runtime_helpers_classCallCheck__WEBPACK_IMPORTED_MODULE_0__ = __webpack_require__(/*! @babel/runtime/helpers/classCallCheck */ "./node_modules/@babel/runtime/helpers/esm/classCallCheck.js");
/* harmony import */ var _babel_runtime_helpers_createClass__WEBPACK_IMPORTED_MODULE_1__ = __webpack_require__(/*! @babel/runtime/helpers/createClass */ "./node_modules/@babel/runtime/helpers/esm/createClass.js");
/* harmony import */ var _babel_runtime_helpers_asyncToGenerator__WEBPACK_IMPORTED_MODULE_2__ = __webpack_require__(/*! @babel/runtime/helpers/asyncToGenerator */ "./node_modules/@babel/runtime/helpers/esm/asyncToGenerator.js");



function _regenerator() {
  /*! regenerator-runtime -- Copyright (c) 2014-present, Facebook, Inc. -- license (MIT): https://github.com/babel/babel/blob/main/packages/babel-helpers/LICENSE */var e,
    t,
    r = "function" == typeof Symbol ? Symbol : {},
    n = r.iterator || "@@iterator",
    o = r.toStringTag || "@@toStringTag";
  function i(r, n, o, i) {
    var c = n && n.prototype instanceof Generator ? n : Generator,
      u = Object.create(c.prototype);
    return _regeneratorDefine2(u, "_invoke", function (r, n, o) {
      var i,
        c,
        u,
        f = 0,
        p = o || [],
        y = !1,
        G = {
          p: 0,
          n: 0,
          v: e,
          a: d,
          f: d.bind(e, 4),
          d: function d(t, r) {
            return i = t, c = 0, u = e, G.n = r, a;
          }
        };
      function d(r, n) {
        for (c = r, u = n, t = 0; !y && f && !o && t < p.length; t++) {
          var o,
            i = p[t],
            d = G.p,
            l = i[2];
          r > 3 ? (o = l === n) && (u = i[(c = i[4]) ? 5 : (c = 3, 3)], i[4] = i[5] = e) : i[0] <= d && ((o = r < 2 && d < i[1]) ? (c = 0, G.v = n, G.n = i[1]) : d < l && (o = r < 3 || i[0] > n || n > l) && (i[4] = r, i[5] = n, G.n = l, c = 0));
        }
        if (o || r > 1) return a;
        throw y = !0, n;
      }
      return function (o, p, l) {
        if (f > 1) throw TypeError("Generator is already running");
        for (y && 1 === p && d(p, l), c = p, u = l; (t = c < 2 ? e : u) || !y;) {
          i || (c ? c < 3 ? (c > 1 && (G.n = -1), d(c, u)) : G.n = u : G.v = u);
          try {
            if (f = 2, i) {
              if (c || (o = "next"), t = i[o]) {
                if (!(t = t.call(i, u))) throw TypeError("iterator result is not an object");
                if (!t.done) return t;
                u = t.value, c < 2 && (c = 0);
              } else 1 === c && (t = i["return"]) && t.call(i), c < 2 && (u = TypeError("The iterator does not provide a '" + o + "' method"), c = 1);
              i = e;
            } else if ((t = (y = G.n < 0) ? u : r.call(n, G)) !== a) break;
          } catch (t) {
            i = e, c = 1, u = t;
          } finally {
            f = 1;
          }
        }
        return {
          value: t,
          done: y
        };
      };
    }(r, o, i), !0), u;
  }
  var a = {};
  function Generator() {}
  function GeneratorFunction() {}
  function GeneratorFunctionPrototype() {}
  t = Object.getPrototypeOf;
  var c = [][n] ? t(t([][n]())) : (_regeneratorDefine2(t = {}, n, function () {
      return this;
    }), t),
    u = GeneratorFunctionPrototype.prototype = Generator.prototype = Object.create(c);
  function f(e) {
    return Object.setPrototypeOf ? Object.setPrototypeOf(e, GeneratorFunctionPrototype) : (e.__proto__ = GeneratorFunctionPrototype, _regeneratorDefine2(e, o, "GeneratorFunction")), e.prototype = Object.create(u), e;
  }
  return GeneratorFunction.prototype = GeneratorFunctionPrototype, _regeneratorDefine2(u, "constructor", GeneratorFunctionPrototype), _regeneratorDefine2(GeneratorFunctionPrototype, "constructor", GeneratorFunction), GeneratorFunction.displayName = "GeneratorFunction", _regeneratorDefine2(GeneratorFunctionPrototype, o, "GeneratorFunction"), _regeneratorDefine2(u), _regeneratorDefine2(u, o, "Generator"), _regeneratorDefine2(u, n, function () {
    return this;
  }), _regeneratorDefine2(u, "toString", function () {
    return "[object Generator]";
  }), (_regenerator = function _regenerator() {
    return {
      w: i,
      m: f
    };
  })();
}
function _regeneratorDefine2(e, r, n, t) {
  var i = Object.defineProperty;
  try {
    i({}, "", {});
  } catch (e) {
    i = 0;
  }
  _regeneratorDefine2 = function _regeneratorDefine(e, r, n, t) {
    function o(r, n) {
      _regeneratorDefine2(e, r, function (e) {
        return this._invoke(r, n, e);
      });
    }
    r ? i ? i(e, r, {
      value: n,
      enumerable: !t,
      configurable: !t,
      writable: !t
    }) : e[r] = n : (o("next", 0), o("throw", 1), o("return", 2));
  }, _regeneratorDefine2(e, r, n, t);
}
/**
 * Donation Form
 */
(function () {
  var _ref = (0,_babel_runtime_helpers_asyncToGenerator__WEBPACK_IMPORTED_MODULE_2__["default"])(/*#__PURE__*/_regenerator().m(function _callee4(w) {
    'use strict';

    var donationForm, initDonationForm;
    return _regenerator().w(function (_context4) {
      while (1) switch (_context4.n) {
        case 0:
          donationForm = /*#__PURE__*/function () {
            function donationForm(_donationForm, options) {
              (0,_babel_runtime_helpers_classCallCheck__WEBPACK_IMPORTED_MODULE_0__["default"])(this, donationForm);
              this.fields = {};
              this.form = _donationForm;
              this.options = options;
              this.totalSteps = this.form.querySelectorAll('.donation-form__step-panel').length;
              this.currentStep = 1;
              this.init(_donationForm, options);
            }
            return (0,_babel_runtime_helpers_createClass__WEBPACK_IMPORTED_MODULE_1__["default"])(donationForm, [{
              key: "init",
              value: function init(_donationForm2, options) {
                var _this = this;
                var self = this;

                // set default payment method selected
                this.form.querySelector("input[name=\"payment_method\"][value=\"".concat(options.paymentMethodSelected, "\"]")).checked = true;
                this.setInitFields(_donationForm2);
                this.onListenerFormFieldUpdate();

                // create event trigger on load form to document
                document.dispatchEvent(new CustomEvent('donationFormLoaded', {
                  detail: {
                    self: self,
                    form: self.form
                  }
                }));

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

                // on click next step
                this.form.addEventListener('click', function (event) {
                  // is contains class and is element had class donation-form__button--next
                  var isNextButton = event.target.classList.contains('donation-form__button--next') && event.target.tagName === 'BUTTON';
                  if (isNextButton) {
                    var stepPass = _this.onValidateFieldsCurrentStep();
                    // console.log('stepPass', stepPass);

                    if (stepPass) {
                      _this.onNextStep();
                    }
                  }
                });

                // on click previous step
                this.form.addEventListener('click', function (event) {
                  // is contains class and is element had class donation-form__button--back
                  var isBackButton = event.target.classList.contains('donation-form__button--back') && event.target.tagName === 'BUTTON';
                  if (isBackButton) {
                    _this.onPreviousStep();
                  }
                });

                // on submit form
                this.form.addEventListener('submit', function (event) {
                  event.preventDefault();
                  _this.onSubmitForm();
                });
              }
            }, {
              key: "onSetLoading",
              value: function onSetLoading(status) {
                var self = this;
                self.form.querySelector('.donation-form__button--submit').classList.toggle('loading', status);
                self.form.querySelector('.donation-form__button--submit').disabled = status;
              }
            }, {
              key: "onSubmitForm",
              value: function () {
                var _onSubmitForm = (0,_babel_runtime_helpers_asyncToGenerator__WEBPACK_IMPORTED_MODULE_2__["default"])(/*#__PURE__*/_regenerator().m(function _callee() {
                  var self, pass, response, errorMessage, _response$data, _t;
                  return _regenerator().w(function (_context) {
                    while (1) switch (_context.p = _context.n) {
                      case 0:
                        self = this; // self.form.querySelector('.donation-form__step-panel.is-active').classList.remove('is-active');
                        // self.form.querySelector('#donation-thank-you').classList.add('is-active');
                        // return;
                        self.onSetLoading(true);

                        // validate fields
                        pass = self.onValidateFieldsCurrentStep(); // console.log('pass', pass);
                        if (pass) {
                          _context.n = 1;
                          break;
                        }
                        return _context.a(2);
                      case 1:
                        _context.p = 1;
                        _context.n = 2;
                        return self.onDoHooks();
                      case 2:
                        _context.n = 4;
                        break;
                      case 3:
                        _context.p = 3;
                        _t = _context.v;
                        console.error('Error in onDoHooks:', _t);
                        self.onSetLoading(false);
                        return _context.a(2);
                      case 4:
                        _context.n = 5;
                        return self.onSendData(self.fields);
                      case 5:
                        response = _context.v;
                        console.log('onSubmitForm', response);
                        if (!(!response || !response.success)) {
                          _context.n = 6;
                          break;
                        }
                        // console.error('Error response:', response);
                        // show error section
                        self.form.querySelector('.donation-form__step-panel.is-active').classList.remove('is-active');
                        self.form.querySelector('#donation-error').classList.add('is-active');

                        // set error message
                        errorMessage = self.form.querySelector('#donation-error .donation-form__error-message');
                        if (errorMessage) {
                          errorMessage.innerHTML = "\n\t\t\t\t\t\t<h3 class=\"donation-form__error-title\">Error</h3>\n\t\t\t\t\t\t<p class=\"donation-form__error-text\">".concat((response === null || response === void 0 || (_response$data = response.data) === null || _response$data === void 0 ? void 0 : _response$data.message) || 'An error occurred. Please try again.', "</p>\n\t\t\t\t\t");
                        }
                        self.onSetLoading(false);
                        return _context.a(2);
                      case 6:
                        if (!(response && response.success)) {
                          _context.n = 7;
                          break;
                        }
                        // console.log('Success response:', response);
                        // show thank you section
                        self.form.querySelector('.donation-form__step-panel.is-active').classList.remove('is-active');
                        self.form.querySelector('#donation-thank-you').classList.add('is-active');
                        self.onSetLoading(false);
                        return _context.a(2);
                      case 7:
                        self.onSetLoading(false);
                      case 8:
                        return _context.a(2);
                    }
                  }, _callee, this, [[1, 3]]);
                }));
                function onSubmitForm() {
                  return _onSubmitForm.apply(this, arguments);
                }
                return onSubmitForm;
              }()
            }, {
              key: "onSendData",
              value: function () {
                var _onSendData = (0,_babel_runtime_helpers_asyncToGenerator__WEBPACK_IMPORTED_MODULE_2__["default"])(/*#__PURE__*/_regenerator().m(function _callee2(data) {
                  var ajaxurl, response;
                  return _regenerator().w(function (_context2) {
                    while (1) switch (_context2.n) {
                      case 0:
                        // const res = await jQuery.ajax({
                        // 	url: window.giftflowwpDonationForms.ajaxurl,
                        // 	type: 'POST',
                        // 	data: {
                        // 		action: 'giftflowwp_donation_form',
                        // 		wp_nonce: data.wp_nonce,
                        // 		data
                        // 	},
                        // 	error: function (xhr, status, error) {
                        // 		console.error('Error:', [error, status]);
                        // 	}
                        // })
                        // return res;
                        // return;
                        ajaxurl = "".concat(window.giftflowwpDonationForms.ajaxurl, "?action=giftflowwp_donation_form&wp_nonce=").concat(data.wp_nonce);
                        _context2.n = 1;
                        return fetch(ajaxurl, {
                          method: 'POST',
                          body: JSON.stringify(data),
                          headers: {
                            'Content-Type': 'application/json'
                          }
                        }).then(function (response) {
                          return response.json();
                        })["catch"](function (error) {
                          return error;
                        });
                      case 1:
                        response = _context2.v;
                        return _context2.a(2, response);
                    }
                  }, _callee2);
                }));
                function onSendData(_x2) {
                  return _onSendData.apply(this, arguments);
                }
                return onSendData;
              }()
            }, {
              key: "onDoHooks",
              value: function () {
                var _onDoHooks = (0,_babel_runtime_helpers_asyncToGenerator__WEBPACK_IMPORTED_MODULE_2__["default"])(/*#__PURE__*/_regenerator().m(function _callee3() {
                  var self;
                  return _regenerator().w(function (_context3) {
                    while (1) switch (_context3.n) {
                      case 0:
                        self = this; // allow developer add hooks from outside support async function and return promise
                        return _context3.a(2, new Promise(function (resolve, reject) {
                          self.form.dispatchEvent(new CustomEvent('donationFormBeforeSubmit', {
                            detail: {
                              self: self,
                              fields: self.fields,
                              resolve: resolve,
                              reject: reject
                            }
                          }));
                        }));
                    }
                  }, _callee3, this);
                }));
                function onDoHooks() {
                  return _onDoHooks.apply(this, arguments);
                }
                return onDoHooks;
              }()
            }, {
              key: "onSetField",
              value: function onSetField(name, value) {
                this.fields[name] = value;
              }
            }, {
              key: "onNextStep",
              value: function onNextStep() {
                var self = this;
                self.currentStep++;

                // nav
                self.form.querySelector('.donation-form__step-link.is-active').classList.remove('is-active');
                self.form.querySelector(".donation-form__step-item.nav-step-".concat(self.currentStep, " .donation-form__step-link")).classList.add('is-active');

                // panel
                self.form.querySelector('.donation-form__step-panel.is-active').classList.remove('is-active');
                self.form.querySelector('.donation-form__step-panel.step-' + self.currentStep).classList.add('is-active');
              }
            }, {
              key: "onPreviousStep",
              value: function onPreviousStep() {
                var self = this;
                self.currentStep--;

                // nav
                self.form.querySelector('.donation-form__step-link.is-active').classList.remove('is-active');
                self.form.querySelector(".donation-form__step-item.nav-step-".concat(self.currentStep, " .donation-form__step-link")).classList.add('is-active');

                // panel
                self.form.querySelector('.donation-form__step-panel.is-active').classList.remove('is-active');
                self.form.querySelector('.donation-form__step-panel.step-' + self.currentStep).classList.add('is-active');
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
                  console.log(event.target.name, value);

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
                  console.log('fields', self.fields);
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
                var wrapperField = inputField.closest('.donation-form__field');
                if (!wrapperField) {
                  if (!this.onValidateValue('required', value)) {
                    inputField.classList.add('error');
                    this.onUpdateOutputField(field, '');
                  } else {
                    inputField.classList.remove('error');
                    this.onUpdateOutputField(field, value);
                  }
                  return;
                }
                if (inputField.dataset.validate) {
                  var pass = this.onValidateValue(inputField.dataset.validate, value);
                  if (!pass) {
                    // inputField.classList.add('error');
                    wrapperField.classList.add('error');
                    this.onUpdateOutputField(field, '');
                  } else {
                    // inputField.classList.remove('error');
                    wrapperField.classList.remove('error');
                    this.onUpdateOutputField(field, value);
                  }
                }
              }
            }, {
              key: "onUpdateOutputField",
              value: function onUpdateOutputField(field, value) {
                var _this3 = this;
                var outputField = this.form.querySelectorAll("[data-output=\"".concat(field, "\"]"));
                if (!outputField || outputField.length === 0) {
                  return;
                }

                // if outputField is array, loop through it
                if (outputField.length > 1) {
                  outputField.forEach(function (output) {
                    var formatTemplate = output.dataset.formatTemplate;
                    var __v = value;
                    if (formatTemplate) {
                      __v = formatTemplate.replace('{{value}}', value);
                    }

                    // update output value
                    _this3.updateOutputValue(output, __v);
                  });
                  return;
                }

                // const formatTemplate = outputField?.dataset?.formatTemplate;

                // if (formatTemplate) {
                // 	value = formatTemplate.replace('{{value}}', value);
                // }

                // if (outputField) {
                // 	outputField.textContent = value;
                // }
              }
            }, {
              key: "updateOutputValue",
              value: function updateOutputValue(output, value) {
                if (output.tagName === 'INPUT' || output.tagName === 'TEXTAREA') {
                  // if output is input or textarea, set value
                  output.value = value;
                  output.setAttribute('value', value);
                } else {
                  // if output is not input or textarea, set text content
                  output.textContent = value;
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
                self.form.querySelector('input[name="donation_amount"]').setAttribute('value', amount);
                var changeEvent = new Event('change', {
                  bubbles: true
                });
                self.form.querySelector('input[name="donation_amount"]').dispatchEvent(changeEvent);

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
            }, {
              key: "onValidateFieldsCurrentStep",
              value: function onValidateFieldsCurrentStep() {
                var _this4 = this;
                var self = this;
                var currentStepWrapper = this.form.querySelector('.donation-form__step-panel.is-active');
                var pass = true;
                if (!currentStepWrapper) {
                  return;
                }
                var fields = currentStepWrapper.querySelectorAll('input[name][data-validate]');
                fields.forEach(function (field) {
                  var fieldName = field.name;
                  var fieldValue = field.value;
                  var fieldValidate = field.dataset.validate;
                  if (!_this4.onValidateValue(fieldValidate, fieldValue)) {
                    pass = false;
                  }
                  self.onUpdateUIByField(fieldName, fieldValue);
                });
                currentStepWrapper.querySelectorAll('[data-custom-validate="true"]').forEach(function (field) {
                  var status = field.dataset.customValidateStatus;
                  if (status === 'false') {
                    pass = false;

                    // add error class to field
                    field.classList.add('error', 'custom-error');
                  }
                });
                return pass;
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
          }(); // make custom event trigger donation form and how to use it	
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
            var _ref2 = event.detail || {},
              formSelector = _ref2.formSelector,
              options = _ref2.options;
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
          initDonationForm = function initDonationForm(formSelector, options) {
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
            initDonationForm('.donation-form', {
              paymentMethodSelected: 'stripe'
            });
          });
        case 1:
          return _context4.a(2);
      }
    }, _callee4);
  }));
  return function (_x) {
    return _ref.apply(this, arguments);
  };
})()(window);
})();

/******/ })()
;