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
/* harmony import */ var _babel_runtime_helpers_slicedToArray__WEBPACK_IMPORTED_MODULE_0__ = __webpack_require__(/*! @babel/runtime/helpers/slicedToArray */ "./node_modules/@babel/runtime/helpers/esm/slicedToArray.js");

document.addEventListener('DOMContentLoaded', function () {
  // Form steps handling
  var form = document.getElementById('giftflowwp-donation-form');
  var steps = form.querySelectorAll('.giftflowwp-donation-form-step');
  var stepNavItems = form.querySelectorAll('.giftflowwp-donation-form-step-nav-item');
  var currentStep = 1;

  // Next step button
  var nextStepButton = form.querySelector('.giftflowwp-donation-form-next-step');
  nextStepButton.addEventListener('click', function () {
    if (validateStep1()) {
      currentStep++;
      updateSteps();
      updateDonationSummary();
    }
  });

  // Previous step button
  var prevStepButton = form.querySelector('.giftflowwp-donation-form-prev-step');
  prevStepButton.addEventListener('click', function () {
    currentStep--;
    updateSteps();
  });

  // Update steps visibility and navigation
  function updateSteps() {
    steps.forEach(function (step) {
      step.classList.remove('active');
      step.style.display = 'none';
    });
    stepNavItems.forEach(function (item) {
      return item.classList.remove('active');
    });
    var currentStepElement = document.querySelector(".giftflowwp-donation-form-step-".concat(currentStep));
    currentStepElement.classList.add('active');
    currentStepElement.style.display = 'block';
    document.querySelector(".giftflowwp-donation-form-step-nav-item[data-step=\"".concat(currentStep, "\"]")).classList.add('active');
  }

  // Validate step 1 with improved error handling
  function validateStep1() {
    var amount = document.getElementById('giftflowwp-donation-form-input-amount').value;
    var firstName = document.getElementById('giftflowwp-donation-form-user-info-first-name').value;
    var lastName = document.getElementById('giftflowwp-donation-form-user-info-last-name').value;
    var email = document.getElementById('giftflowwp-donation-form-user-info-email').value;
    var isValid = true;
    var errorMessage = '';
    var errorField = null;
    if (!amount || parseFloat(amount) <= 0) {
      isValid = false;
      errorMessage = 'Please enter a valid donation amount';
      errorField = 'giftflowwp-donation-form-input-amount';
    } else if (!firstName) {
      isValid = false;
      errorMessage = 'Please enter your first name';
      errorField = 'giftflowwp-donation-form-user-info-first-name';
    } else if (!lastName) {
      isValid = false;
      errorMessage = 'Please enter your last name';
      errorField = 'giftflowwp-donation-form-user-info-last-name';
    } else if (!email || !isValidEmail(email)) {
      isValid = false;
      errorMessage = 'Please enter a valid email address';
      errorField = 'giftflowwp-donation-form-user-info-email';
    }
    if (!isValid) {
      showError(errorMessage, errorField);
    }
    return isValid;
  }

  // Show error message with animation
  function showError(message, fieldId) {
    var errorDiv = document.createElement('div');
    errorDiv.className = 'giftflowwp-form-error';
    errorDiv.textContent = message;
    errorDiv.style.opacity = '0';
    errorDiv.style.transform = 'translateY(-10px)';
    var field = document.getElementById(fieldId);
    field.parentNode.insertBefore(errorDiv, field.nextSibling);

    // Add error class to input
    field.classList.add('error');

    // Animate error message
    setTimeout(function () {
      errorDiv.style.opacity = '1';
      errorDiv.style.transform = 'translateY(0)';
    }, 10);

    // Remove error after 3 seconds
    setTimeout(function () {
      errorDiv.style.opacity = '0';
      errorDiv.style.transform = 'translateY(-10px)';
      setTimeout(function () {
        errorDiv.remove();
        field.classList.remove('error');
      }, 300);
    }, 3000);
  }

  // Email validation
  function isValidEmail(email) {
    var emailRegex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
    return emailRegex.test(email);
  }

  // Preset amount buttons with active state
  var presetAmountButtons = form.querySelectorAll('.giftflowwp-donation-form-preset-amount');
  presetAmountButtons.forEach(function (button) {
    button.addEventListener('click', function () {
      var amount = this.dataset.amount;
      var amountInput = document.getElementById('giftflowwp-donation-form-input-amount');
      amountInput.value = amount;

      // Update active state
      presetAmountButtons.forEach(function (btn) {
        return btn.classList.remove('active');
      });
      this.classList.add('active');
      updateDonationSummary();
    });
  });

  // Amount input change with validation
  var amountInput = document.getElementById('giftflowwp-donation-form-input-amount');
  amountInput.addEventListener('input', function () {
    // Remove active state from preset buttons
    presetAmountButtons.forEach(function (btn) {
      return btn.classList.remove('active');
    });

    // Validate amount
    var amount = parseFloat(this.value);
    if (amount > 0) {
      this.classList.remove('error');
    } else {
      this.classList.add('error');
    }
    updateDonationSummary();
  });

  // Donation type change with animation
  var donationTypeInputs = form.querySelectorAll('input[name="donation_type"]');
  donationTypeInputs.forEach(function (input) {
    input.addEventListener('change', function () {
      var labels = form.querySelectorAll('.giftflowwp-donation-form-recurring-option label');
      labels.forEach(function (label) {
        label.style.transform = 'scale(1)';
        setTimeout(function () {
          label.style.transform = 'scale(1.02)';
          setTimeout(function () {
            label.style.transform = 'scale(1)';
          }, 150);
        }, 10);
      });
      updateDonationSummary();
    });
  });

  // Update donation summary with animation
  function updateDonationSummary() {
    var amount = document.getElementById('giftflowwp-donation-form-input-amount').value;
    var donationType = form.querySelector('input[name="donation_type"]:checked').value;
    var firstName = document.getElementById('giftflowwp-donation-form-user-info-first-name').value;
    var lastName = document.getElementById('giftflowwp-donation-form-user-info-last-name').value;
    var anonymous = document.getElementById('giftflowwp-donation-form-user-info-anonymous').checked;

    // Animate summary updates
    var summaryAmount = document.getElementById('summary-amount');
    var summaryType = document.getElementById('summary-type');
    var summaryDonor = document.getElementById('summary-donor');

    // Fade out
    [summaryAmount, summaryType, summaryDonor].forEach(function (el) {
      el.style.opacity = '0';
      el.style.transform = 'translateY(-5px)';
    });

    // Update values
    var currencySymbol = window.giftflowwpForms && window.giftflowwpForms.currency_symbol || '$';
    summaryAmount.textContent = currencySymbol + amount;
    summaryType.textContent = donationType === 'monthly' ? 'Monthly' : 'One-time';
    summaryDonor.textContent = anonymous ? 'Anonymous' : "".concat(firstName, " ").concat(lastName);

    // Fade in
    setTimeout(function () {
      [summaryAmount, summaryType, summaryDonor].forEach(function (el) {
        el.style.opacity = '1';
        el.style.transform = 'translateY(0)';
      });
    }, 150);
  }

  // Payment method selection with animation
  var paymentMethodInputs = form.querySelectorAll('input[name="payment_method"]');
  paymentMethodInputs.forEach(function (input) {
    input.addEventListener('change', function () {
      var selectedMethod = this.value;
      var labels = form.querySelectorAll('.giftflowwp-donation-form-payment-method label');

      // Animate payment method selection
      labels.forEach(function (label) {
        label.style.transform = 'scale(1)';
        setTimeout(function () {
          label.style.transform = 'scale(1.02)';
          setTimeout(function () {
            label.style.transform = 'scale(1)';
          }, 150);
        }, 10);
      });

      // Show/hide payment forms with animation
      var stripePaymentForm = document.getElementById('stripe-payment-form');
      stripePaymentForm.style.opacity = '0';
      stripePaymentForm.style.transform = 'translateY(-10px)';
      setTimeout(function () {
        stripePaymentForm.style.display = selectedMethod === 'stripe' ? 'block' : 'none';
        if (selectedMethod === 'stripe') {
          setTimeout(function () {
            stripePaymentForm.style.opacity = '1';
            stripePaymentForm.style.transform = 'translateY(0)';
          }, 10);
        }
      }, 300);
    });
  });

  // Form submission with loading state
  form.addEventListener('submit', function (e) {
    e.preventDefault();
    if (!validateStep1()) {
      currentStep = 1;
      updateSteps();
      return;
    }
    var formData = {
      amount: document.getElementById('giftflowwp-donation-form-input-amount').value,
      first_name: document.getElementById('giftflowwp-donation-form-user-info-first-name').value,
      last_name: document.getElementById('giftflowwp-donation-form-user-info-last-name').value,
      email: document.getElementById('giftflowwp-donation-form-user-info-email').value,
      message: document.getElementById('giftflowwp-donation-form-user-info-message').value,
      anonymous: document.getElementById('giftflowwp-donation-form-user-info-anonymous').checked,
      payment_method: form.querySelector('input[name="payment_method"]:checked').value,
      donation_type: form.querySelector('input[name="donation_type"]:checked').value,
      campaign_id: form.dataset.campaignId,
      nonce: giftflowwpForms.nonce
    };

    // Show loading state
    var submitButton = form.querySelector('.giftflowwp-donation-form-submit');
    var originalText = submitButton.textContent;
    submitButton.disabled = true;
    submitButton.innerHTML = '<span class="giftflowwp-loading-spinner"></span> Processing...';

    // Process payment based on selected method
    if (formData.payment_method === 'stripe') {
      processStripePayment(formData);
    } else if (formData.payment_method === 'paypal') {
      processPayPalPayment(formData);
    }
  });

  // Process Stripe payment with improved error handling
  function processStripePayment(formData) {
    var stripe = Stripe(giftflowwpForms.stripe_public_key);
    var elements = stripe.elements();
    var card = elements.create('card', {
      style: {
        base: {
          fontSize: '16px',
          color: '#32325d',
          '::placeholder': {
            color: '#aab7c4'
          }
        },
        invalid: {
          color: '#dc2626',
          iconColor: '#dc2626'
        }
      }
    });
    card.mount('#card-element');
    card.addEventListener('change', function (event) {
      var displayError = document.getElementById('card-errors');
      if (event.error) {
        displayError.textContent = event.error.message;
        displayError.style.opacity = '1';
        displayError.style.transform = 'translateY(0)';
      } else {
        displayError.style.opacity = '0';
        displayError.style.transform = 'translateY(-10px)';
        setTimeout(function () {
          displayError.textContent = '';
        }, 300);
      }
    });
    stripe.createPaymentMethod({
      type: 'card',
      card: card
    }).then(function (result) {
      if (result.error) {
        var errorElement = document.getElementById('card-errors');
        errorElement.textContent = result.error.message;
        errorElement.style.opacity = '1';
        errorElement.style.transform = 'translateY(0)';
        var submitButton = form.querySelector('.giftflowwp-donation-form-submit');
        submitButton.disabled = false;
        submitButton.textContent = originalText;
      } else {
        formData.payment_method_id = result.paymentMethod.id;
        submitDonation(formData);
      }
    });
  }

  // Process PayPal payment
  function processPayPalPayment(formData) {
    var params = new URLSearchParams();
    for (var _i = 0, _Object$entries = Object.entries(formData); _i < _Object$entries.length; _i++) {
      var _Object$entries$_i = (0,_babel_runtime_helpers_slicedToArray__WEBPACK_IMPORTED_MODULE_0__["default"])(_Object$entries[_i], 2),
        key = _Object$entries$_i[0],
        value = _Object$entries$_i[1];
      params.append(key, value);
    }
    window.location.href = "".concat(giftflowwpForms.paypal_redirect_url, "?").concat(params.toString());
  }

  // Submit donation with improved error handling
  function submitDonation(formData) {
    var xhr = new XMLHttpRequest();
    xhr.open('POST', giftflowwpForms.ajaxurl, true);
    xhr.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded');
    var data = new URLSearchParams();
    data.append('action', 'process_donation');
    for (var _i2 = 0, _Object$entries2 = Object.entries(formData); _i2 < _Object$entries2.length; _i2++) {
      var _Object$entries2$_i = (0,_babel_runtime_helpers_slicedToArray__WEBPACK_IMPORTED_MODULE_0__["default"])(_Object$entries2[_i2], 2),
        key = _Object$entries2$_i[0],
        value = _Object$entries2$_i[1];
      data.append(key, value);
    }
    xhr.onload = function () {
      if (xhr.status === 200) {
        var response = JSON.parse(xhr.responseText);
        if (response.success) {
          // Show success message with animation
          var successMessage = document.createElement('div');
          successMessage.className = 'giftflowwp-success-message';
          successMessage.innerHTML = "\n                        <svg class=\"giftflowwp-success-icon\" viewBox=\"0 0 24 24\">\n                            <path d=\"M9 16.17L4.83 12l-1.42 1.41L9 19 21 7l-1.41-1.41z\"/>\n                        </svg>\n                        <p>".concat(response.data.message || 'Thank you for your donation!', "</p>\n                    ");
          form.parentNode.insertBefore(successMessage, form.nextSibling);

          // Animate success message
          setTimeout(function () {
            successMessage.style.opacity = '1';
            successMessage.style.transform = 'translateY(0)';
          }, 10);

          // Redirect after delay
          setTimeout(function () {
            window.location.href = response.data.redirect_url;
          }, 2000);
        } else {
          showError(response.data.message || 'An error occurred. Please try again.');
          var submitButton = form.querySelector('.giftflowwp-donation-form-submit');
          submitButton.disabled = false;
          submitButton.textContent = originalText;
        }
      } else {
        showError('An error occurred. Please try again.');
        var _submitButton = form.querySelector('.giftflowwp-donation-form-submit');
        _submitButton.disabled = false;
        _submitButton.textContent = originalText;
      }
    };
    xhr.onerror = function () {
      showError('An error occurred. Please try again.');
      var submitButton = form.querySelector('.giftflowwp-donation-form-submit');
      submitButton.disabled = false;
      submitButton.textContent = originalText;
    };
    xhr.send(data.toString());
  }
});

/***/ }),

/***/ "./node_modules/@babel/runtime/helpers/esm/arrayLikeToArray.js":
/*!*********************************************************************!*\
  !*** ./node_modules/@babel/runtime/helpers/esm/arrayLikeToArray.js ***!
  \*********************************************************************/
/***/ ((__unused_webpack___webpack_module__, __webpack_exports__, __webpack_require__) => {

__webpack_require__.r(__webpack_exports__);
/* harmony export */ __webpack_require__.d(__webpack_exports__, {
/* harmony export */   "default": () => (/* binding */ _arrayLikeToArray)
/* harmony export */ });
function _arrayLikeToArray(r, a) {
  (null == a || a > r.length) && (a = r.length);
  for (var e = 0, n = Array(a); e < a; e++) n[e] = r[e];
  return n;
}


/***/ }),

/***/ "./node_modules/@babel/runtime/helpers/esm/arrayWithHoles.js":
/*!*******************************************************************!*\
  !*** ./node_modules/@babel/runtime/helpers/esm/arrayWithHoles.js ***!
  \*******************************************************************/
/***/ ((__unused_webpack___webpack_module__, __webpack_exports__, __webpack_require__) => {

__webpack_require__.r(__webpack_exports__);
/* harmony export */ __webpack_require__.d(__webpack_exports__, {
/* harmony export */   "default": () => (/* binding */ _arrayWithHoles)
/* harmony export */ });
function _arrayWithHoles(r) {
  if (Array.isArray(r)) return r;
}


/***/ }),

/***/ "./node_modules/@babel/runtime/helpers/esm/iterableToArrayLimit.js":
/*!*************************************************************************!*\
  !*** ./node_modules/@babel/runtime/helpers/esm/iterableToArrayLimit.js ***!
  \*************************************************************************/
/***/ ((__unused_webpack___webpack_module__, __webpack_exports__, __webpack_require__) => {

__webpack_require__.r(__webpack_exports__);
/* harmony export */ __webpack_require__.d(__webpack_exports__, {
/* harmony export */   "default": () => (/* binding */ _iterableToArrayLimit)
/* harmony export */ });
function _iterableToArrayLimit(r, l) {
  var t = null == r ? null : "undefined" != typeof Symbol && r[Symbol.iterator] || r["@@iterator"];
  if (null != t) {
    var e,
      n,
      i,
      u,
      a = [],
      f = !0,
      o = !1;
    try {
      if (i = (t = t.call(r)).next, 0 === l) {
        if (Object(t) !== t) return;
        f = !1;
      } else for (; !(f = (e = i.call(t)).done) && (a.push(e.value), a.length !== l); f = !0);
    } catch (r) {
      o = !0, n = r;
    } finally {
      try {
        if (!f && null != t["return"] && (u = t["return"](), Object(u) !== u)) return;
      } finally {
        if (o) throw n;
      }
    }
    return a;
  }
}


/***/ }),

/***/ "./node_modules/@babel/runtime/helpers/esm/nonIterableRest.js":
/*!********************************************************************!*\
  !*** ./node_modules/@babel/runtime/helpers/esm/nonIterableRest.js ***!
  \********************************************************************/
/***/ ((__unused_webpack___webpack_module__, __webpack_exports__, __webpack_require__) => {

__webpack_require__.r(__webpack_exports__);
/* harmony export */ __webpack_require__.d(__webpack_exports__, {
/* harmony export */   "default": () => (/* binding */ _nonIterableRest)
/* harmony export */ });
function _nonIterableRest() {
  throw new TypeError("Invalid attempt to destructure non-iterable instance.\nIn order to be iterable, non-array objects must have a [Symbol.iterator]() method.");
}


/***/ }),

/***/ "./node_modules/@babel/runtime/helpers/esm/slicedToArray.js":
/*!******************************************************************!*\
  !*** ./node_modules/@babel/runtime/helpers/esm/slicedToArray.js ***!
  \******************************************************************/
/***/ ((__unused_webpack___webpack_module__, __webpack_exports__, __webpack_require__) => {

__webpack_require__.r(__webpack_exports__);
/* harmony export */ __webpack_require__.d(__webpack_exports__, {
/* harmony export */   "default": () => (/* binding */ _slicedToArray)
/* harmony export */ });
/* harmony import */ var _arrayWithHoles_js__WEBPACK_IMPORTED_MODULE_0__ = __webpack_require__(/*! ./arrayWithHoles.js */ "./node_modules/@babel/runtime/helpers/esm/arrayWithHoles.js");
/* harmony import */ var _iterableToArrayLimit_js__WEBPACK_IMPORTED_MODULE_1__ = __webpack_require__(/*! ./iterableToArrayLimit.js */ "./node_modules/@babel/runtime/helpers/esm/iterableToArrayLimit.js");
/* harmony import */ var _unsupportedIterableToArray_js__WEBPACK_IMPORTED_MODULE_2__ = __webpack_require__(/*! ./unsupportedIterableToArray.js */ "./node_modules/@babel/runtime/helpers/esm/unsupportedIterableToArray.js");
/* harmony import */ var _nonIterableRest_js__WEBPACK_IMPORTED_MODULE_3__ = __webpack_require__(/*! ./nonIterableRest.js */ "./node_modules/@babel/runtime/helpers/esm/nonIterableRest.js");




function _slicedToArray(r, e) {
  return (0,_arrayWithHoles_js__WEBPACK_IMPORTED_MODULE_0__["default"])(r) || (0,_iterableToArrayLimit_js__WEBPACK_IMPORTED_MODULE_1__["default"])(r, e) || (0,_unsupportedIterableToArray_js__WEBPACK_IMPORTED_MODULE_2__["default"])(r, e) || (0,_nonIterableRest_js__WEBPACK_IMPORTED_MODULE_3__["default"])();
}


/***/ }),

/***/ "./node_modules/@babel/runtime/helpers/esm/unsupportedIterableToArray.js":
/*!*******************************************************************************!*\
  !*** ./node_modules/@babel/runtime/helpers/esm/unsupportedIterableToArray.js ***!
  \*******************************************************************************/
/***/ ((__unused_webpack___webpack_module__, __webpack_exports__, __webpack_require__) => {

__webpack_require__.r(__webpack_exports__);
/* harmony export */ __webpack_require__.d(__webpack_exports__, {
/* harmony export */   "default": () => (/* binding */ _unsupportedIterableToArray)
/* harmony export */ });
/* harmony import */ var _arrayLikeToArray_js__WEBPACK_IMPORTED_MODULE_0__ = __webpack_require__(/*! ./arrayLikeToArray.js */ "./node_modules/@babel/runtime/helpers/esm/arrayLikeToArray.js");

function _unsupportedIterableToArray(r, a) {
  if (r) {
    if ("string" == typeof r) return (0,_arrayLikeToArray_js__WEBPACK_IMPORTED_MODULE_0__["default"])(r, a);
    var t = {}.toString.call(r).slice(8, -1);
    return "Object" === t && r.constructor && (t = r.constructor.name), "Map" === t || "Set" === t ? Array.from(r) : "Arguments" === t || /^(?:Ui|I)nt(?:8|16|32)(?:Clamped)?Array$/.test(t) ? (0,_arrayLikeToArray_js__WEBPACK_IMPORTED_MODULE_0__["default"])(r, a) : void 0;
  }
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