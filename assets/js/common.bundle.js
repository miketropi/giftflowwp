/******/ (() => { // webpackBootstrap
/******/ 	var __webpack_modules__ = ({

/***/ "./admin/css/admin.scss":
/*!******************************!*\
  !*** ./admin/css/admin.scss ***!
  \******************************/
/***/ ((__unused_webpack_module, __webpack_exports__, __webpack_require__) => {

"use strict";
__webpack_require__.r(__webpack_exports__);
// extracted by mini-css-extract-plugin


/***/ }),

/***/ "./assets/css/block-campaign-single-content.scss":
/*!*******************************************************!*\
  !*** ./assets/css/block-campaign-single-content.scss ***!
  \*******************************************************/
/***/ ((__unused_webpack_module, __webpack_exports__, __webpack_require__) => {

"use strict";
__webpack_require__.r(__webpack_exports__);
// extracted by mini-css-extract-plugin


/***/ }),

/***/ "./assets/css/block-campaign-status-bar.scss":
/*!***************************************************!*\
  !*** ./assets/css/block-campaign-status-bar.scss ***!
  \***************************************************/
/***/ ((__unused_webpack_module, __webpack_exports__, __webpack_require__) => {

"use strict";
__webpack_require__.r(__webpack_exports__);
// extracted by mini-css-extract-plugin


/***/ }),

/***/ "./assets/css/common.scss":
/*!********************************!*\
  !*** ./assets/css/common.scss ***!
  \********************************/
/***/ ((__unused_webpack_module, __webpack_exports__, __webpack_require__) => {

"use strict";
__webpack_require__.r(__webpack_exports__);
// extracted by mini-css-extract-plugin


/***/ }),

/***/ "./assets/css/donation-form.scss":
/*!***************************************!*\
  !*** ./assets/css/donation-form.scss ***!
  \***************************************/
/***/ ((__unused_webpack_module, __webpack_exports__, __webpack_require__) => {

"use strict";
__webpack_require__.r(__webpack_exports__);
// extracted by mini-css-extract-plugin


/***/ }),

/***/ "./assets/js/common.js":
/*!*****************************!*\
  !*** ./assets/js/common.js ***!
  \*****************************/
/***/ ((__unused_webpack_module, __webpack_exports__, __webpack_require__) => {

"use strict";
__webpack_require__.r(__webpack_exports__);
/* harmony import */ var _babel_runtime_helpers_asyncToGenerator__WEBPACK_IMPORTED_MODULE_0__ = __webpack_require__(/*! @babel/runtime/helpers/asyncToGenerator */ "./node_modules/@babel/runtime/helpers/esm/asyncToGenerator.js");
/* harmony import */ var _util_comment_form_js__WEBPACK_IMPORTED_MODULE_1__ = __webpack_require__(/*! ./util/comment-form.js */ "./assets/js/util/comment-form.js");
/* harmony import */ var _util_comment_form_js__WEBPACK_IMPORTED_MODULE_1___default = /*#__PURE__*/__webpack_require__.n(_util_comment_form_js__WEBPACK_IMPORTED_MODULE_1__);
/* harmony import */ var _util_modal_js__WEBPACK_IMPORTED_MODULE_2__ = __webpack_require__(/*! ./util/modal.js */ "./assets/js/util/modal.js");
/* harmony import */ var _util_helpers_js__WEBPACK_IMPORTED_MODULE_3__ = __webpack_require__(/*! ./util/helpers.js */ "./assets/js/util/helpers.js");
/* harmony import */ var _util_donation_button_js__WEBPACK_IMPORTED_MODULE_4__ = __webpack_require__(/*! ./util/donation-button.js */ "./assets/js/util/donation-button.js");

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
 * GiftFlow Common JS
 */




(function (w, $) {
  "use strict";

  var _giftflowwp_common = giftflowwp_common,
    ajax_url = _giftflowwp_common.ajax_url,
    nonce = _giftflowwp_common.nonce;
  w.giftflowwp = w.giftflowwp || {};
  var gfw = w.giftflowwp;

  // load donation list
  gfw.loadDonationListPaginationTemplate_Handle = /*#__PURE__*/function () {
    var _ref = (0,_babel_runtime_helpers_asyncToGenerator__WEBPACK_IMPORTED_MODULE_0__["default"])(/*#__PURE__*/_regenerator().m(function _callee(elem) {
      var _elem$dataset, campaign, page, container, res, _res$data, __html, __replace_content_selector;
      return _regenerator().w(function (_context) {
        while (1) switch (_context.n) {
          case 0:
            _elem$dataset = elem.dataset, campaign = _elem$dataset.campaign, page = _elem$dataset.page;
            if (!(!campaign || !page)) {
              _context.n = 1;
              break;
            }
            console.error('Missing campaign or page data attributes');
            return _context.a(2);
          case 1:
            container = elem.closest(".__donations-list-by-campaign-".concat(campaign));
            if (container) {
              _context.n = 2;
              break;
            }
            console.error('Container element not found');
            return _context.a(2);
          case 2:
            container.classList.add('gfw-loading-spinner');
            _context.n = 3;
            return $.ajax({
              url: ajax_url,
              type: 'POST',
              data: {
                action: 'giftflowwp_get_pagination_donation_list_html',
                campaign: campaign,
                page: page,
                nonce: nonce
              }
            });
          case 3:
            res = _context.v;
            container.classList.remove('gfw-loading-spinner');

            // res successful
            if (res.success) {
              _res$data = res.data, __html = _res$data.__html, __replace_content_selector = _res$data.__replace_content_selector;
              if (__replace_content_selector) {
                (0,_util_helpers_js__WEBPACK_IMPORTED_MODULE_3__.replaceContentBySelector)(__replace_content_selector, __html);
              }
            } else {
              console.error('Error loading donation list pagination template');
            }
          case 4:
            return _context.a(2);
        }
      }, _callee);
    }));
    return function (_x) {
      return _ref.apply(this, arguments);
    };
  }();
  gfw.donationButton_Handle = _util_donation_button_js__WEBPACK_IMPORTED_MODULE_4__["default"];
})(window, jQuery);

/***/ }),

/***/ "./assets/js/util/comment-form.js":
/*!****************************************!*\
  !*** ./assets/js/util/comment-form.js ***!
  \****************************************/
/***/ (() => {

/**
 * Comment Form JS
 */

(function (w, $) {
  'use strict';

  var replyCommentHandle = function replyCommentHandle() {
    var originalFormPosition = null;
    var originalFormTitle = null;
    var cancelReplyHtml = "<small><a rel=\"nofollow\" id=\"cancel-comment-reply-link\" href=\"#\">Cancel reply</a></small>";
    $(document).on('click', '.gfw-campaign-comments-list .comment-reply-link', function (e) {
      e.preventDefault();
      var commentId = $(this).data('commentid');
      var titleReply = $(this).data('replyto') || 'Leave a Reply';
      var form = $('#respond');
      var parentInput = form.find('input[name="comment_parent"]');
      if (!commentId || !form.length || !parentInput.length) {
        console.error('Missing comment ID or form elements');
        return;
      }

      // Store original position if not already stored
      if (!originalFormPosition) {
        originalFormPosition = form.parent();
      }
      if (!originalFormTitle) {
        originalFormTitle = form.find('#reply-title').html();
      }

      // set parent comment ID
      parentInput.val(commentId);

      // set form title titleReply
      form.find('#reply-title').html("".concat(titleReply, " ").concat(cancelReplyHtml));

      // move form
      var commentElement = $("#comment-".concat(commentId, " > .comment-body"));
      if (commentElement.length) {
        commentElement.after(form);
        form.find('textarea').focus();
      }
    });

    // Add cancel reply handler
    $(document).on('click', '#cancel-comment-reply-link', function (e) {
      e.preventDefault();
      var form = $('#respond');
      var parentInput = form.find('input[name="comment_parent"]');

      // Reset parent comment ID
      parentInput.val('0');

      // Return form to original position
      if (originalFormPosition) {
        originalFormPosition.append(form);
      }

      // Reset form title
      if (originalFormTitle) {
        form.find('#reply-title').html(originalFormTitle);
      }
    });
  };
  $(function () {
    replyCommentHandle();
  });
})(window, jQuery);

/***/ }),

/***/ "./assets/js/util/donation-button.js":
/*!*******************************************!*\
  !*** ./assets/js/util/donation-button.js ***!
  \*******************************************/
/***/ ((__unused_webpack_module, __webpack_exports__, __webpack_require__) => {

"use strict";
__webpack_require__.r(__webpack_exports__);
/* harmony export */ __webpack_require__.d(__webpack_exports__, {
/* harmony export */   "default": () => (/* binding */ donationButton_Handle)
/* harmony export */ });
/* harmony import */ var _babel_runtime_helpers_asyncToGenerator__WEBPACK_IMPORTED_MODULE_0__ = __webpack_require__(/*! @babel/runtime/helpers/asyncToGenerator */ "./node_modules/@babel/runtime/helpers/esm/asyncToGenerator.js");

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
var _giftflowwp_common = giftflowwp_common,
  ajax_url = _giftflowwp_common.ajax_url,
  nonce = _giftflowwp_common.nonce;
function donationButton_Handle(_x) {
  return _donationButton_Handle.apply(this, arguments);
}
function _donationButton_Handle() {
  _donationButton_Handle = (0,_babel_runtime_helpers_asyncToGenerator__WEBPACK_IMPORTED_MODULE_0__["default"])(/*#__PURE__*/_regenerator().m(function _callee(el) {
    var _el$dataset, campaignId, campaignTitle, ajaxModal;
    return _regenerator().w(function (_context) {
      while (1) switch (_context.n) {
        case 0:
          _el$dataset = el.dataset, campaignId = _el$dataset.campaignId, campaignTitle = _el$dataset.campaignTitle;
          ajaxModal = new GiftFlowWPModal({
            ajax: true,
            ajaxUrl: "".concat(ajax_url, "?action=giftflowwp_get_campaign_donation_form&campaign_id=").concat(campaignId, "&nonce=").concat(nonce),
            onLoad: function onLoad(content, modal) {
              // console.log('Content loaded:', modal);

              var donationForm = modal.contentElement.querySelector('form.donation-form');
              if (donationForm) {
                new window.donationForm_Class(donationForm, {
                  paymentMethodSelected: 'stripe'
                });
              }
            },
            className: 'modal-transparent-wrapper',
            width: '800px'
          });
          ajaxModal.open();
        case 1:
          return _context.a(2);
      }
    }, _callee);
  }));
  return _donationButton_Handle.apply(this, arguments);
}

/***/ }),

/***/ "./assets/js/util/helpers.js":
/*!***********************************!*\
  !*** ./assets/js/util/helpers.js ***!
  \***********************************/
/***/ ((__unused_webpack_module, __webpack_exports__, __webpack_require__) => {

"use strict";
__webpack_require__.r(__webpack_exports__);
/* harmony export */ __webpack_require__.d(__webpack_exports__, {
/* harmony export */   replaceContentBySelector: () => (/* binding */ replaceContentBySelector)
/* harmony export */ });
var replaceContentBySelector = function replaceContentBySelector(selector, content) {
  var elem = document.querySelector(selector);
  if (elem) {
    elem.innerHTML = content;
  } else {
    console.error("Element not found for selector: ".concat(selector));
  }
};

/***/ }),

/***/ "./assets/js/util/modal.js":
/*!*********************************!*\
  !*** ./assets/js/util/modal.js ***!
  \*********************************/
/***/ ((module, __webpack_exports__, __webpack_require__) => {

"use strict";
__webpack_require__.r(__webpack_exports__);
/* harmony import */ var _babel_runtime_helpers_asyncToGenerator__WEBPACK_IMPORTED_MODULE_0__ = __webpack_require__(/*! @babel/runtime/helpers/asyncToGenerator */ "./node_modules/@babel/runtime/helpers/esm/asyncToGenerator.js");
/* harmony import */ var _babel_runtime_helpers_defineProperty__WEBPACK_IMPORTED_MODULE_1__ = __webpack_require__(/*! @babel/runtime/helpers/defineProperty */ "./node_modules/@babel/runtime/helpers/esm/defineProperty.js");
/* harmony import */ var _babel_runtime_helpers_classCallCheck__WEBPACK_IMPORTED_MODULE_2__ = __webpack_require__(/*! @babel/runtime/helpers/classCallCheck */ "./node_modules/@babel/runtime/helpers/esm/classCallCheck.js");
/* harmony import */ var _babel_runtime_helpers_createClass__WEBPACK_IMPORTED_MODULE_3__ = __webpack_require__(/*! @babel/runtime/helpers/createClass */ "./node_modules/@babel/runtime/helpers/esm/createClass.js");
/* module decorator */ module = __webpack_require__.hmd(module);




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
function ownKeys(e, r) {
  var t = Object.keys(e);
  if (Object.getOwnPropertySymbols) {
    var o = Object.getOwnPropertySymbols(e);
    r && (o = o.filter(function (r) {
      return Object.getOwnPropertyDescriptor(e, r).enumerable;
    })), t.push.apply(t, o);
  }
  return t;
}
function _objectSpread(e) {
  for (var r = 1; r < arguments.length; r++) {
    var t = null != arguments[r] ? arguments[r] : {};
    r % 2 ? ownKeys(Object(t), !0).forEach(function (r) {
      (0,_babel_runtime_helpers_defineProperty__WEBPACK_IMPORTED_MODULE_1__["default"])(e, r, t[r]);
    }) : Object.getOwnPropertyDescriptors ? Object.defineProperties(e, Object.getOwnPropertyDescriptors(t)) : ownKeys(Object(t)).forEach(function (r) {
      Object.defineProperty(e, r, Object.getOwnPropertyDescriptor(t, r));
    });
  }
  return e;
}
/**
 * GiftFlowWP Modal Library
 * A clean, simple, and easy-to-use modal system with AJAX support
 * 
 * QUICK START EXAMPLES:
 * 
 * Basic Modal:
 * const modal = new GiftFlowWPModal({
 *     content: '<h2>Hello World!</h2><p>This is a simple modal.</p>',
 *     closeButton: true
 * });
 * modal.open();
 * 
 * AJAX Modal:
 * const ajaxModal = new GiftFlowWPModal({
 *     ajax: true,
 *     ajaxUrl: '/api/get-content',
 *     onLoad: (content, modal) => console.log('Content loaded:', content)
 * });
 * ajaxModal.open();
 * 
 * Quick Dialogs:
 * GiftFlowWPModal.alert('Operation completed!', 'Success');
 * const confirmed = await GiftFlowWPModal.confirm('Are you sure?');
 * const value = await GiftFlowWPModal.prompt('Enter your name:', 'John Doe');
 * 
 * Custom Modal:
 * const customModal = new GiftFlowWPModal({
 *     content: '<p>Custom sized modal</p>',
 *     width: '800px',
 *     animation: 'zoom',
 *     duration: 500
 * });
 * customModal.open();
 * 
 * For complete documentation and examples, see:
 * wp-content/plugins/giftflowwp/assets/js/util/README.md
 */
/**
 * GiftFlowWP Modal Library
 * A clean, simple, and easy-to-use modal system with AJAX support
 */
var GiftFlowWPModal = /*#__PURE__*/function () {
  function GiftFlowWPModal() {
    var options = arguments.length > 0 && arguments[0] !== undefined ? arguments[0] : {};
    (0,_babel_runtime_helpers_classCallCheck__WEBPACK_IMPORTED_MODULE_2__["default"])(this, GiftFlowWPModal);
    this.options = _objectSpread({
      // Modal options
      id: options.id || 'giftflowwp-modal',
      className: options.className || 'giftflowwp-modal',
      overlay: options.overlay !== false,
      closeOnOverlay: options.closeOnOverlay !== false,
      closeOnEscape: options.closeOnEscape !== false,
      closeButton: options.closeButton !== false,
      // Animation options
      animation: options.animation || 'fade',
      // 'fade', 'slide', 'zoom'
      duration: options.duration || 300,
      easing: options.easing || 'ease-in-out',
      // Content options
      content: options.content || '',
      ajax: options.ajax || false,
      ajaxUrl: options.ajaxUrl || '',
      ajaxData: options.ajaxData || {},
      ajaxMethod: options.ajaxMethod || 'GET',
      // Size options
      width: options.width || 'auto',
      maxWidth: options.maxWidth || '90vw',
      height: options.height || 'auto',
      maxHeight: options.maxHeight || '90vh',
      // Position options
      position: options.position || 'center',
      // 'center', 'top', 'bottom'

      // Callbacks
      onOpen: options.onOpen || null,
      onClose: options.onClose || null,
      onLoad: options.onLoad || null,
      onError: options.onError || null,
      // Auto-close options
      autoClose: options.autoClose || false,
      autoCloseDelay: options.autoCloseDelay || 5000,
      // Accessibility
      ariaLabel: options.ariaLabel || 'Modal dialog',
      focusTrap: options.focusTrap !== false
    }, options);
    this.isOpen = false;
    this.modalElement = null;
    this.overlayElement = null;
    this.contentElement = null;
    this.closeButtonElement = null;
    this.focusableElements = [];
    this.lastFocusedElement = null;
    this.autoCloseTimer = null;
    this.init();
  }

  /**
   * Initialize the modal
   */
  return (0,_babel_runtime_helpers_createClass__WEBPACK_IMPORTED_MODULE_3__["default"])(GiftFlowWPModal, [{
    key: "init",
    value: function init() {
      this.createModal();
      this.bindEvents();
      if (this.options.ajax && this.options.ajaxUrl) {
        this.loadAjaxContent();
      }
    }

    /**
     * Create modal HTML structure
     */
  }, {
    key: "createModal",
    value: function createModal() {
      // Create overlay
      if (this.options.overlay) {
        this.overlayElement = document.createElement('div');
        this.overlayElement.className = 'giftflowwp-modal__overlay';
        this.overlayElement.setAttribute('aria-hidden', 'true');
      }

      // Create modal container
      this.modalElement = document.createElement('div');
      this.modalElement.id = this.options.id;
      this.modalElement.className = "giftflowwp-modal ".concat(this.options.className);
      this.modalElement.setAttribute('role', 'dialog');
      this.modalElement.setAttribute('aria-modal', 'true');
      this.modalElement.setAttribute('aria-label', this.options.ariaLabel);
      this.modalElement.setAttribute('tabindex', '-1');

      // Set modal dimensions
      if (this.options.width !== 'auto') {
        this.modalElement.style.width = this.options.width;
      }
      if (this.options.maxWidth !== '90vw') {
        this.modalElement.style.maxWidth = this.options.maxWidth;
      }
      if (this.options.height !== 'auto') {
        this.modalElement.style.height = this.options.height;
      }
      if (this.options.maxHeight !== '90vh') {
        this.modalElement.style.maxHeight = this.options.maxHeight;
      }

      // Create modal content
      this.contentElement = document.createElement('div');
      this.contentElement.className = 'giftflowwp-modal__content';

      // Add close button if enabled
      if (this.options.closeButton) {
        this.closeButtonElement = document.createElement('button');
        this.closeButtonElement.className = 'giftflowwp-modal__close';
        this.closeButtonElement.setAttribute('type', 'button');
        this.closeButtonElement.setAttribute('aria-label', 'Close modal');
        this.closeButtonElement.innerHTML = "<svg xmlns=\"http://www.w3.org/2000/svg\" viewBox=\"0 0 24 24\" fill=\"none\" stroke=\"currentColor\" stroke-width=\"2\" stroke-linecap=\"round\" stroke-linejoin=\"round\" class=\"lucide lucide-x-icon lucide-x\"><path d=\"M18 6 6 18\"/><path d=\"m6 6 12 12\"/></svg>";
        this.modalElement.appendChild(this.closeButtonElement);
      }

      // Add content
      if (this.options.content && !this.options.ajax) {
        this.contentElement.innerHTML = this.options.content;
      }
      this.modalElement.appendChild(this.contentElement);

      // Append to DOM
      if (this.options.overlay) {
        this.overlayElement.appendChild(this.modalElement);
        document.body.appendChild(this.overlayElement);
      } else {
        document.body.appendChild(this.modalElement);
      }

      // Add animation classes
      this.modalElement.classList.add("giftflowwp-modal--".concat(this.options.animation));
    }

    /**
     * Bind event listeners
     */
  }, {
    key: "bindEvents",
    value: function bindEvents() {
      var _this = this;
      // Close button click
      if (this.closeButtonElement) {
        this.closeButtonElement.addEventListener('click', function () {
          return _this.close();
        });
      }

      // Overlay click
      if (this.options.overlay && this.options.closeOnOverlay) {
        this.overlayElement.addEventListener('click', function (e) {
          if (e.target === _this.overlayElement) {
            _this.close();
          }
        });
      }

      // Escape key
      if (this.options.closeOnEscape) {
        document.addEventListener('keydown', function (e) {
          if (e.key === 'Escape' && _this.isOpen) {
            _this.close();
          }
        });
      }

      // Focus trap
      if (this.options.focusTrap) {
        this.modalElement.addEventListener('keydown', function (e) {
          if (e.key === 'Tab') {
            _this.handleTabKey(e);
          }
        });
      }
    }

    /**
     * Handle tab key for focus trapping
     */
  }, {
    key: "handleTabKey",
    value: function handleTabKey(e) {
      this.focusableElements = this.modalElement.querySelectorAll('button, [href], input, select, textarea, [tabindex]:not([tabindex="-1"])');
      var firstElement = this.focusableElements[0];
      var lastElement = this.focusableElements[this.focusableElements.length - 1];
      if (e.shiftKey) {
        if (document.activeElement === firstElement) {
          e.preventDefault();
          lastElement.focus();
        }
      } else {
        if (document.activeElement === lastElement) {
          e.preventDefault();
          firstElement.focus();
        }
      }
    }

    /**
     * Load content via AJAX
     */
  }, {
    key: "loadAjaxContent",
    value: function () {
      var _loadAjaxContent = (0,_babel_runtime_helpers_asyncToGenerator__WEBPACK_IMPORTED_MODULE_0__["default"])(/*#__PURE__*/_regenerator().m(function _callee() {
        var response, content, _t;
        return _regenerator().w(function (_context) {
          while (1) switch (_context.p = _context.n) {
            case 0:
              _context.p = 0;
              this.showLoading();
              _context.n = 1;
              return fetch(this.options.ajaxUrl, {
                method: this.options.ajaxMethod,
                headers: {
                  'Content-Type': 'application/json'
                },
                body: this.options.ajaxMethod === 'POST' ? JSON.stringify(this.options.ajaxData) : undefined
              });
            case 1:
              response = _context.v;
              if (response.ok) {
                _context.n = 2;
                break;
              }
              throw new Error("HTTP error! status: ".concat(response.status));
            case 2:
              _context.n = 3;
              return response.text();
            case 3:
              content = _context.v;
              this.contentElement.innerHTML = content;
              if (this.options.onLoad) {
                this.options.onLoad(content, this);
              }
              _context.n = 5;
              break;
            case 4:
              _context.p = 4;
              _t = _context.v;
              console.error('Modal AJAX error:', _t);
              this.contentElement.innerHTML = '<div class="giftflowwp-modal__error">Failed to load content</div>';
              if (this.options.onError) {
                this.options.onError(_t, this);
              }
            case 5:
              return _context.a(2);
          }
        }, _callee, this, [[0, 4]]);
      }));
      function loadAjaxContent() {
        return _loadAjaxContent.apply(this, arguments);
      }
      return loadAjaxContent;
    }()
    /**
     * Show loading state
     */
  }, {
    key: "showLoading",
    value: function showLoading() {
      this.contentElement.innerHTML = '<div class="giftflowwp-modal__loading">Loading...</div>';
    }

    /**
     * Open the modal
     */
  }, {
    key: "open",
    value: function open() {
      var _this2 = this;
      if (this.isOpen) return;
      this.isOpen = true;
      this.lastFocusedElement = document.activeElement;

      // Show modal
      if (this.options.overlay) {
        this.overlayElement.style.display = 'flex';
        this.overlayElement.setAttribute('aria-hidden', 'false');
      }
      this.modalElement.style.display = 'block';

      // Trigger animation
      requestAnimationFrame(function () {
        _this2.modalElement.classList.add('giftflowwp-modal--open');
        if (_this2.options.overlay) {
          _this2.overlayElement.classList.add('giftflowwp-modal__overlay--open');
        }
      });

      // Focus modal
      this.modalElement.focus();

      // Set auto-close timer
      if (this.options.autoClose) {
        this.autoCloseTimer = setTimeout(function () {
          _this2.close();
        }, this.options.autoCloseDelay);
      }

      // Prevent body scroll
      document.body.style.overflow = 'hidden';

      // Call onOpen callback
      if (this.options.onOpen) {
        this.options.onOpen(this);
      }
    }

    /**
     * Close the modal
     */
  }, {
    key: "close",
    value: function close() {
      var _this3 = this;
      if (!this.isOpen) return;
      this.isOpen = false;

      // Clear auto-close timer
      if (this.autoCloseTimer) {
        clearTimeout(this.autoCloseTimer);
        this.autoCloseTimer = null;
      }

      // Trigger close animation
      this.modalElement.classList.remove('giftflowwp-modal--open');
      if (this.options.overlay) {
        this.overlayElement.classList.remove('giftflowwp-modal__overlay--open');
      }

      // Wait for animation to complete
      setTimeout(function () {
        _this3.modalElement.style.display = 'none';
        if (_this3.options.overlay) {
          _this3.overlayElement.style.display = 'none';
          _this3.overlayElement.setAttribute('aria-hidden', 'true');
        }

        // Restore body scroll
        document.body.style.overflow = '';

        // Restore focus
        if (_this3.lastFocusedElement) {
          _this3.lastFocusedElement.focus();
        }

        // Call onClose callback
        if (_this3.options.onClose) {
          _this3.options.onClose(_this3);
        }
      }, this.options.duration);
    }

    /**
     * Update modal content
     */
  }, {
    key: "setContent",
    value: function setContent(content) {
      this.contentElement.innerHTML = content;
    }

    /**
     * Update modal options
     */
  }, {
    key: "updateOptions",
    value: function updateOptions(newOptions) {
      this.options = _objectSpread(_objectSpread({}, this.options), newOptions);

      // Update dimensions if changed
      if (newOptions.width !== undefined) {
        this.modalElement.style.width = newOptions.width;
      }
      if (newOptions.maxWidth !== undefined) {
        this.modalElement.style.maxWidth = newOptions.maxWidth;
      }
      if (newOptions.height !== undefined) {
        this.modalElement.style.height = newOptions.height;
      }
      if (newOptions.maxHeight !== undefined) {
        this.modalElement.style.maxHeight = newOptions.maxHeight;
      }
    }

    /**
     * Destroy the modal
     */
  }, {
    key: "destroy",
    value: function destroy() {
      this.close();
      if (this.autoCloseTimer) {
        clearTimeout(this.autoCloseTimer);
      }
      if (this.modalElement && this.modalElement.parentNode) {
        this.modalElement.parentNode.removeChild(this.modalElement);
      }
      if (this.overlayElement && this.overlayElement.parentNode) {
        this.overlayElement.parentNode.removeChild(this.overlayElement);
      }
      this.modalElement = null;
      this.overlayElement = null;
      this.contentElement = null;
      this.closeButtonElement = null;
    }
  }]);
}();
/**
 * Static methods for easy modal creation
 */
GiftFlowWPModal.create = function (options) {
  return new GiftFlowWPModal(options);
};
GiftFlowWPModal.alert = function (message) {
  var title = arguments.length > 1 && arguments[1] !== undefined ? arguments[1] : 'Alert';
  var options = arguments.length > 2 && arguments[2] !== undefined ? arguments[2] : {};
  var modal = new GiftFlowWPModal(_objectSpread({
    content: "\n            <div class=\"giftflowwp-modal__header\">\n                <h3>".concat(title, "</h3>\n            </div>\n            <div class=\"giftflowwp-modal__body\">\n                <p>").concat(message, "</p>\n            </div>\n            <div class=\"giftflowwp-modal__footer\">\n                <button class=\"giftflowwp-modal__btn giftflowwp-modal__btn--primary\" onclick=\"this.closest('.giftflowwp-modal').giftflowwpModal.close()\">\n                    OK\n                </button>\n            </div>\n        "),
    closeButton: true,
    closeOnOverlay: false,
    closeOnEscape: true
  }, options));

  // Store modal reference for the close button
  modal.modalElement.giftflowwpModal = modal;
  modal.open();
  return modal;
};
GiftFlowWPModal.confirm = function (message) {
  var title = arguments.length > 1 && arguments[1] !== undefined ? arguments[1] : 'Confirm';
  var options = arguments.length > 2 && arguments[2] !== undefined ? arguments[2] : {};
  return new Promise(function (resolve) {
    var modal = new GiftFlowWPModal(_objectSpread({
      content: "\n                <div class=\"giftflowwp-modal__header\">\n                    <h3>".concat(title, "</h3>\n                </div>\n                <div class=\"giftflowwp-modal__body\">\n                    <p>").concat(message, "</p>\n                </div>\n                <div class=\"giftflowwp-modal__footer\">\n                    <button class=\"giftflowwp-modal__btn giftflowwp-modal__btn--secondary\" onclick=\"this.closest('.giftflowwp-modal').giftflowwpModal.confirmResult(false)\">\n                        Cancel\n                    </button>\n                    <button class=\"giftflowwp-modal__btn giftflowwp-modal__btn--primary\" onclick=\"this.closest('.giftflowwp-modal').giftflowwpModal.confirmResult(true)\">\n                        OK\n                    </button>\n                </div>\n            "),
      closeButton: true,
      closeOnOverlay: false,
      closeOnEscape: true,
      onClose: function onClose() {
        return resolve(false);
      }
    }, options));

    // Store modal reference and confirm method
    modal.modalElement.giftflowwpModal = modal;
    modal.confirmResult = function (result) {
      modal.close();
      resolve(result);
    };
    modal.open();
  });
};
GiftFlowWPModal.prompt = function (message) {
  var defaultValue = arguments.length > 1 && arguments[1] !== undefined ? arguments[1] : '';
  var title = arguments.length > 2 && arguments[2] !== undefined ? arguments[2] : 'Input';
  var options = arguments.length > 3 && arguments[3] !== undefined ? arguments[3] : {};
  return new Promise(function (resolve) {
    var modal = new GiftFlowWPModal(_objectSpread({
      content: "\n                <div class=\"giftflowwp-modal__header\">\n                    <h3>".concat(title, "</h3>\n                </div>\n                <div class=\"giftflowwp-modal__body\">\n                    <p>").concat(message, "</p>\n                    <input type=\"text\" class=\"giftflowwp-modal__input\" value=\"").concat(defaultValue, "\" placeholder=\"Enter value...\">\n                </div>\n                <div class=\"giftflowwp-modal__footer\">\n                    <button class=\"giftflowwp-modal__btn giftflowwp-modal__btn--secondary\" onclick=\"this.closest('.giftflowwp-modal').giftflowwpModal.promptResult(null)\">\n                        Cancel\n                    </button>\n                    <button class=\"giftflowwp-modal__btn giftflowwp-modal__btn--primary\" onclick=\"this.closest('.giftflowwp-modal').giftflowwpModal.promptResult(this.closest('.giftflowwp-modal').querySelector('.giftflowwp-modal__input').value)\">\n                        OK\n                    </button>\n                </div>\n            "),
      closeButton: true,
      closeOnOverlay: false,
      closeOnEscape: true,
      onClose: function onClose() {
        return resolve(null);
      }
    }, options));

    // Store modal reference and prompt method
    modal.modalElement.giftflowwpModal = modal;
    modal.promptResult = function (result) {
      modal.close();
      resolve(result);
    };
    modal.open();

    // Focus input
    setTimeout(function () {
      var input = modal.modalElement.querySelector('.giftflowwp-modal__input');
      if (input) {
        input.focus();
        input.select();
      }
    }, 100);
  });
};

// Export for different module systems
if ( true && module.exports) {
  module.exports = GiftFlowWPModal;
} else if (typeof define === 'function' && __webpack_require__.amdO) {
  define(function () {
    return GiftFlowWPModal;
  });
} else {
  window.GiftFlowWPModal = GiftFlowWPModal;
}

/***/ }),

/***/ "./node_modules/@babel/runtime/helpers/esm/asyncToGenerator.js":
/*!*********************************************************************!*\
  !*** ./node_modules/@babel/runtime/helpers/esm/asyncToGenerator.js ***!
  \*********************************************************************/
/***/ ((__unused_webpack___webpack_module__, __webpack_exports__, __webpack_require__) => {

"use strict";
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

"use strict";
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

"use strict";
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

/***/ "./node_modules/@babel/runtime/helpers/esm/defineProperty.js":
/*!*******************************************************************!*\
  !*** ./node_modules/@babel/runtime/helpers/esm/defineProperty.js ***!
  \*******************************************************************/
/***/ ((__unused_webpack___webpack_module__, __webpack_exports__, __webpack_require__) => {

"use strict";
__webpack_require__.r(__webpack_exports__);
/* harmony export */ __webpack_require__.d(__webpack_exports__, {
/* harmony export */   "default": () => (/* binding */ _defineProperty)
/* harmony export */ });
/* harmony import */ var _toPropertyKey_js__WEBPACK_IMPORTED_MODULE_0__ = __webpack_require__(/*! ./toPropertyKey.js */ "./node_modules/@babel/runtime/helpers/esm/toPropertyKey.js");

function _defineProperty(e, r, t) {
  return (r = (0,_toPropertyKey_js__WEBPACK_IMPORTED_MODULE_0__["default"])(r)) in e ? Object.defineProperty(e, r, {
    value: t,
    enumerable: !0,
    configurable: !0,
    writable: !0
  }) : e[r] = t, e;
}


/***/ }),

/***/ "./node_modules/@babel/runtime/helpers/esm/toPrimitive.js":
/*!****************************************************************!*\
  !*** ./node_modules/@babel/runtime/helpers/esm/toPrimitive.js ***!
  \****************************************************************/
/***/ ((__unused_webpack___webpack_module__, __webpack_exports__, __webpack_require__) => {

"use strict";
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

"use strict";
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

"use strict";
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
/******/ 			id: moduleId,
/******/ 			loaded: false,
/******/ 			exports: {}
/******/ 		};
/******/ 	
/******/ 		// Execute the module function
/******/ 		__webpack_modules__[moduleId](module, module.exports, __webpack_require__);
/******/ 	
/******/ 		// Flag the module as loaded
/******/ 		module.loaded = true;
/******/ 	
/******/ 		// Return the exports of the module
/******/ 		return module.exports;
/******/ 	}
/******/ 	
/******/ 	// expose the modules object (__webpack_modules__)
/******/ 	__webpack_require__.m = __webpack_modules__;
/******/ 	
/************************************************************************/
/******/ 	/* webpack/runtime/amd options */
/******/ 	(() => {
/******/ 		__webpack_require__.amdO = {};
/******/ 	})();
/******/ 	
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
/******/ 	/* webpack/runtime/harmony module decorator */
/******/ 	(() => {
/******/ 		__webpack_require__.hmd = (module) => {
/******/ 			module = Object.create(module);
/******/ 			if (!module.children) module.children = [];
/******/ 			Object.defineProperty(module, 'exports', {
/******/ 				enumerable: true,
/******/ 				set: () => {
/******/ 					throw new Error('ES Modules may not assign module.exports or exports.*, Use ESM export syntax, instead: ' + module.id);
/******/ 				}
/******/ 			});
/******/ 			return module;
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
/******/ 			"/assets/js/common.bundle": 0,
/******/ 			"assets/css/admin.bundle": 0,
/******/ 			"assets/css/donation-form.bundle": 0,
/******/ 			"assets/css/common.bundle": 0,
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
/******/ 	__webpack_require__.O(undefined, ["assets/css/admin.bundle","assets/css/donation-form.bundle","assets/css/common.bundle","assets/css/block-campaign-status-bar.bundle","assets/css/block-campaign-single-content.bundle"], () => (__webpack_require__("./assets/js/common.js")))
/******/ 	__webpack_require__.O(undefined, ["assets/css/admin.bundle","assets/css/donation-form.bundle","assets/css/common.bundle","assets/css/block-campaign-status-bar.bundle","assets/css/block-campaign-single-content.bundle"], () => (__webpack_require__("./assets/css/block-campaign-single-content.scss")))
/******/ 	__webpack_require__.O(undefined, ["assets/css/admin.bundle","assets/css/donation-form.bundle","assets/css/common.bundle","assets/css/block-campaign-status-bar.bundle","assets/css/block-campaign-single-content.bundle"], () => (__webpack_require__("./assets/css/block-campaign-status-bar.scss")))
/******/ 	__webpack_require__.O(undefined, ["assets/css/admin.bundle","assets/css/donation-form.bundle","assets/css/common.bundle","assets/css/block-campaign-status-bar.bundle","assets/css/block-campaign-single-content.bundle"], () => (__webpack_require__("./assets/css/common.scss")))
/******/ 	__webpack_require__.O(undefined, ["assets/css/admin.bundle","assets/css/donation-form.bundle","assets/css/common.bundle","assets/css/block-campaign-status-bar.bundle","assets/css/block-campaign-single-content.bundle"], () => (__webpack_require__("./assets/css/donation-form.scss")))
/******/ 	var __webpack_exports__ = __webpack_require__.O(undefined, ["assets/css/admin.bundle","assets/css/donation-form.bundle","assets/css/common.bundle","assets/css/block-campaign-status-bar.bundle","assets/css/block-campaign-single-content.bundle"], () => (__webpack_require__("./admin/css/admin.scss")))
/******/ 	__webpack_exports__ = __webpack_require__.O(__webpack_exports__);
/******/ 	
/******/ })()
;