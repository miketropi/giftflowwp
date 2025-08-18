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
/* harmony import */ var _babel_runtime_helpers_asyncToGenerator__WEBPACK_IMPORTED_MODULE_2__ = __webpack_require__(/*! @babel/runtime/helpers/asyncToGenerator */ "./node_modules/@babel/runtime/helpers/esm/asyncToGenerator.js");
/* harmony import */ var _babel_runtime_regenerator__WEBPACK_IMPORTED_MODULE_3__ = __webpack_require__(/*! @babel/runtime/regenerator */ "@babel/runtime/regenerator");
/* harmony import */ var _babel_runtime_regenerator__WEBPACK_IMPORTED_MODULE_3___default = /*#__PURE__*/__webpack_require__.n(_babel_runtime_regenerator__WEBPACK_IMPORTED_MODULE_3__);




/**
 * Donation Form
 */
(function () {
  var _ref = (0,_babel_runtime_helpers_asyncToGenerator__WEBPACK_IMPORTED_MODULE_2__["default"])(/*#__PURE__*/_babel_runtime_regenerator__WEBPACK_IMPORTED_MODULE_3___default().mark(function _callee4(w) {
    'use strict';

    var donationForm, initDonationForm;
    return _babel_runtime_regenerator__WEBPACK_IMPORTED_MODULE_3___default().wrap(function _callee4$(_context4) {
      while (1) switch (_context4.prev = _context4.next) {
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
                var _onSubmitForm = (0,_babel_runtime_helpers_asyncToGenerator__WEBPACK_IMPORTED_MODULE_2__["default"])(/*#__PURE__*/_babel_runtime_regenerator__WEBPACK_IMPORTED_MODULE_3___default().mark(function _callee() {
                  var self, pass, response;
                  return _babel_runtime_regenerator__WEBPACK_IMPORTED_MODULE_3___default().wrap(function _callee$(_context) {
                    while (1) switch (_context.prev = _context.next) {
                      case 0:
                        self = this;
                        self.onSetLoading(true);

                        // validate fields
                        pass = self.onValidateFieldsCurrentStep(); // console.log('pass', pass);
                        if (pass) {
                          _context.next = 5;
                          break;
                        }
                        return _context.abrupt("return");
                      case 5:
                        _context.prev = 5;
                        _context.next = 8;
                        return self.onDoHooks();
                      case 8:
                        _context.next = 15;
                        break;
                      case 10:
                        _context.prev = 10;
                        _context.t0 = _context["catch"](5);
                        console.error('Error in onDoHooks:', _context.t0);
                        self.onSetLoading(false);
                        return _context.abrupt("return");
                      case 15:
                        _context.next = 17;
                        return self.onSendData(self.fields);
                      case 17:
                        response = _context.sent;
                        console.log('onSubmitForm', response);
                        self.onSetLoading(false);
                      case 20:
                      case "end":
                        return _context.stop();
                    }
                  }, _callee, this, [[5, 10]]);
                }));
                function onSubmitForm() {
                  return _onSubmitForm.apply(this, arguments);
                }
                return onSubmitForm;
              }()
            }, {
              key: "onSendData",
              value: function () {
                var _onSendData = (0,_babel_runtime_helpers_asyncToGenerator__WEBPACK_IMPORTED_MODULE_2__["default"])(/*#__PURE__*/_babel_runtime_regenerator__WEBPACK_IMPORTED_MODULE_3___default().mark(function _callee2(data) {
                  var ajaxurl, response;
                  return _babel_runtime_regenerator__WEBPACK_IMPORTED_MODULE_3___default().wrap(function _callee2$(_context2) {
                    while (1) switch (_context2.prev = _context2.next) {
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
                        _context2.next = 3;
                        return fetch(ajaxurl, {
                          method: 'POST',
                          body: JSON.stringify(data),
                          headers: {
                            'Content-Type': 'application/json'
                          }
                        }).then(function (response) {
                          return response.json();
                        }).then(function (data) {
                          return console.log(data);
                        })["catch"](function (error) {
                          return console.error('Error:', error);
                        });
                      case 3:
                        response = _context2.sent;
                        return _context2.abrupt("return", response);
                      case 5:
                      case "end":
                        return _context2.stop();
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
                var _onDoHooks = (0,_babel_runtime_helpers_asyncToGenerator__WEBPACK_IMPORTED_MODULE_2__["default"])(/*#__PURE__*/_babel_runtime_regenerator__WEBPACK_IMPORTED_MODULE_3___default().mark(function _callee3() {
                  var self;
                  return _babel_runtime_regenerator__WEBPACK_IMPORTED_MODULE_3___default().wrap(function _callee3$(_context3) {
                    while (1) switch (_context3.prev = _context3.next) {
                      case 0:
                        self = this; // allow developer add hooks from outside support async function and return promise
                        return _context3.abrupt("return", new Promise(function (resolve, reject) {
                          self.form.dispatchEvent(new CustomEvent('donationFormBeforeSubmit', {
                            detail: {
                              self: self,
                              fields: self.fields,
                              resolve: resolve,
                              reject: reject
                            }
                          }));
                        }));
                      case 2:
                      case "end":
                        return _context3.stop();
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
                var _outputField$dataset;
                var outputField = this.form.querySelector("[data-output=\"".concat(field, "\"]"));
                var formatTemplate = outputField === null || outputField === void 0 || (_outputField$dataset = outputField.dataset) === null || _outputField$dataset === void 0 ? void 0 : _outputField$dataset.formatTemplate;
                if (formatTemplate) {
                  value = formatTemplate.replace('{{value}}', value);
                }
                if (outputField) {
                  outputField.textContent = value;
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
                var _this3 = this;
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
                  if (!_this3.onValidateValue(fieldValidate, fieldValue)) {
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
        case 5:
        case "end":
          return _context4.stop();
      }
    }, _callee4);
  }));
  return function (_x) {
    return _ref.apply(this, arguments);
  };
})()(window);

/***/ }),

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


/***/ }),

/***/ "@babel/runtime/regenerator":
/*!*************************************!*\
  !*** external "regeneratorRuntime" ***!
  \*************************************/
/***/ ((module) => {

module.exports = window["regeneratorRuntime"];

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
/******/ 	/* webpack/runtime/compat get default export */
/******/ 	(() => {
/******/ 		// getDefaultExport function for compatibility with non-harmony modules
/******/ 		__webpack_require__.n = (module) => {
/******/ 			var getter = module && module.__esModule ?
/******/ 				() => (module['default']) :
/******/ 				() => (module);
/******/ 			__webpack_require__.d(getter, { a: getter });
/******/ 			return getter;
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