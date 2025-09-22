/******/ (() => { // webpackBootstrap
/******/ 	"use strict";
/******/ 	var __webpack_modules__ = ({

/***/ "./admin/js/components/CampaignTracking.jsx":
/*!**************************************************!*\
  !*** ./admin/js/components/CampaignTracking.jsx ***!
  \**************************************************/
/***/ ((__unused_webpack_module, __webpack_exports__, __webpack_require__) => {

__webpack_require__.r(__webpack_exports__);
/* harmony export */ __webpack_require__.d(__webpack_exports__, {
/* harmony export */   "default": () => (__WEBPACK_DEFAULT_EXPORT__)
/* harmony export */ });
/* harmony import */ var _babel_runtime_helpers_slicedToArray__WEBPACK_IMPORTED_MODULE_0__ = __webpack_require__(/*! @babel/runtime/helpers/slicedToArray */ "./node_modules/@babel/runtime/helpers/esm/slicedToArray.js");
/* harmony import */ var react__WEBPACK_IMPORTED_MODULE_1__ = __webpack_require__(/*! react */ "react");
/* harmony import */ var react__WEBPACK_IMPORTED_MODULE_1___default = /*#__PURE__*/__webpack_require__.n(react__WEBPACK_IMPORTED_MODULE_1__);
/* harmony import */ var _hooks_useCampaign__WEBPACK_IMPORTED_MODULE_2__ = __webpack_require__(/*! ../hooks/useCampaign */ "./admin/js/hooks/useCampaign.js");
/* harmony import */ var lucide_react__WEBPACK_IMPORTED_MODULE_3__ = __webpack_require__(/*! lucide-react */ "./node_modules/lucide-react/dist/esm/icons/wrench.js");
/* harmony import */ var _CampaignTrackingModalSettings__WEBPACK_IMPORTED_MODULE_4__ = __webpack_require__(/*! ./CampaignTrackingModalSettings */ "./admin/js/components/CampaignTrackingModalSettings.jsx");
/* harmony import */ var react_jsx_runtime__WEBPACK_IMPORTED_MODULE_5__ = __webpack_require__(/*! react/jsx-runtime */ "react/jsx-runtime");
/* harmony import */ var react_jsx_runtime__WEBPACK_IMPORTED_MODULE_5___default = /*#__PURE__*/__webpack_require__.n(react_jsx_runtime__WEBPACK_IMPORTED_MODULE_5__);






var CampaignTracking = function CampaignTracking() {
  var _useCampaign = (0,_hooks_useCampaign__WEBPACK_IMPORTED_MODULE_2__["default"])({
      per_page: 3
    }),
    campaigns = _useCampaign.campaigns,
    loading = _useCampaign.loading,
    error = _useCampaign.error;
  var _useState = (0,react__WEBPACK_IMPORTED_MODULE_1__.useState)(false),
    _useState2 = (0,_babel_runtime_helpers_slicedToArray__WEBPACK_IMPORTED_MODULE_0__["default"])(_useState, 2),
    isModalOpen = _useState2[0],
    setIsModalOpen = _useState2[1];
  if (loading) return /*#__PURE__*/(0,react_jsx_runtime__WEBPACK_IMPORTED_MODULE_5__.jsxs)("div", {
    className: "giftflowwp-overview__widget",
    children: [/*#__PURE__*/(0,react_jsx_runtime__WEBPACK_IMPORTED_MODULE_5__.jsx)("h4", {
      className: "giftflowwp-overview__widget-title",
      children: "Campaigns Tracking"
    }), /*#__PURE__*/(0,react_jsx_runtime__WEBPACK_IMPORTED_MODULE_5__.jsx)("div", {
      className: "giftflowwp-campaigns-list__loading",
      children: "Loading..."
    })]
  });
  if (error) return /*#__PURE__*/(0,react_jsx_runtime__WEBPACK_IMPORTED_MODULE_5__.jsxs)("div", {
    className: "giftflowwp-overview__widget",
    children: [/*#__PURE__*/(0,react_jsx_runtime__WEBPACK_IMPORTED_MODULE_5__.jsx)("h4", {
      className: "giftflowwp-overview__widget-title",
      children: "Campaigns Tracking"
    }), /*#__PURE__*/(0,react_jsx_runtime__WEBPACK_IMPORTED_MODULE_5__.jsxs)("div", {
      className: "giftflowwp-campaigns-list__error",
      children: ["Error: ", error]
    })]
  });
  var onSave = function onSave(campaigns) {
    console.log("Save", campaigns);
  };
  return /*#__PURE__*/(0,react_jsx_runtime__WEBPACK_IMPORTED_MODULE_5__.jsxs)(react_jsx_runtime__WEBPACK_IMPORTED_MODULE_5__.Fragment, {
    children: [/*#__PURE__*/(0,react_jsx_runtime__WEBPACK_IMPORTED_MODULE_5__.jsxs)("div", {
      className: "giftflowwp-overview__widget",
      children: [/*#__PURE__*/(0,react_jsx_runtime__WEBPACK_IMPORTED_MODULE_5__.jsxs)("div", {
        className: "giftflowwp-overview__widget-header",
        children: [/*#__PURE__*/(0,react_jsx_runtime__WEBPACK_IMPORTED_MODULE_5__.jsx)("h4", {
          className: "giftflowwp-overview__widget-title",
          children: "Campaigns Tracking"
        }), /*#__PURE__*/(0,react_jsx_runtime__WEBPACK_IMPORTED_MODULE_5__.jsx)("button", {
          className: "giftflowwp-overview__widget-header-btn",
          onClick: function onClick() {
            return setIsModalOpen(true);
          },
          children: /*#__PURE__*/(0,react_jsx_runtime__WEBPACK_IMPORTED_MODULE_5__.jsx)(lucide_react__WEBPACK_IMPORTED_MODULE_3__["default"], {
            size: 16
          })
        })]
      }), /*#__PURE__*/(0,react_jsx_runtime__WEBPACK_IMPORTED_MODULE_5__.jsx)("div", {
        className: "giftflowwp-campaigns-list",
        children: /*#__PURE__*/(0,react_jsx_runtime__WEBPACK_IMPORTED_MODULE_5__.jsx)("ul", {
          className: "giftflowwp-campaigns-list__ul",
          children: campaigns.map(function (campaign) {
            return /*#__PURE__*/(0,react_jsx_runtime__WEBPACK_IMPORTED_MODULE_5__.jsx)("li", {
              className: "giftflowwp-campaigns-list__item",
              children: /*#__PURE__*/(0,react_jsx_runtime__WEBPACK_IMPORTED_MODULE_5__.jsxs)("div", {
                className: "giftflowwp-campaigns-list__content",
                children: [/*#__PURE__*/(0,react_jsx_runtime__WEBPACK_IMPORTED_MODULE_5__.jsx)("a", {
                  href: campaign.link,
                  target: "_blank",
                  rel: "noopener noreferrer",
                  className: "giftflowwp-campaigns-list__title-link",
                  children: /*#__PURE__*/(0,react_jsx_runtime__WEBPACK_IMPORTED_MODULE_5__.jsx)("h5", {
                    className: "giftflowwp-campaigns-list__title",
                    dangerouslySetInnerHTML: {
                      __html: campaign.title
                    }
                  })
                }), /*#__PURE__*/(0,react_jsx_runtime__WEBPACK_IMPORTED_MODULE_5__.jsxs)("div", {
                  className: "giftflowwp-campaigns-list__stats",
                  children: [/*#__PURE__*/(0,react_jsx_runtime__WEBPACK_IMPORTED_MODULE_5__.jsxs)("span", {
                    className: "giftflowwp-campaigns-list__stat",
                    children: [/*#__PURE__*/(0,react_jsx_runtime__WEBPACK_IMPORTED_MODULE_5__.jsx)("strong", {
                      children: "Goal:"
                    }), " ", /*#__PURE__*/(0,react_jsx_runtime__WEBPACK_IMPORTED_MODULE_5__.jsx)("span", {
                      className: "__monospace",
                      dangerouslySetInnerHTML: {
                        __html: campaign.__goal_amount
                      }
                    })]
                  }), /*#__PURE__*/(0,react_jsx_runtime__WEBPACK_IMPORTED_MODULE_5__.jsxs)("span", {
                    className: "giftflowwp-campaigns-list__stat",
                    children: [/*#__PURE__*/(0,react_jsx_runtime__WEBPACK_IMPORTED_MODULE_5__.jsx)("strong", {
                      children: "Raised:"
                    }), " ", /*#__PURE__*/(0,react_jsx_runtime__WEBPACK_IMPORTED_MODULE_5__.jsx)("span", {
                      className: "__monospace",
                      dangerouslySetInnerHTML: {
                        __html: campaign.__raised_amount
                      }
                    })]
                  }), /*#__PURE__*/(0,react_jsx_runtime__WEBPACK_IMPORTED_MODULE_5__.jsxs)("span", {
                    className: "giftflowwp-campaigns-list__stat",
                    children: [/*#__PURE__*/(0,react_jsx_runtime__WEBPACK_IMPORTED_MODULE_5__.jsx)("strong", {
                      children: "Status:"
                    }), " ", /*#__PURE__*/(0,react_jsx_runtime__WEBPACK_IMPORTED_MODULE_5__.jsx)("span", {
                      className: "giftflowwp-campaigns-list__status giftflowwp-campaigns-list__status--".concat(campaign.status),
                      children: campaign.status.charAt(0).toUpperCase() + campaign.status.slice(1)
                    })]
                  })]
                }), /*#__PURE__*/(0,react_jsx_runtime__WEBPACK_IMPORTED_MODULE_5__.jsx)("div", {
                  className: "giftflowwp-campaigns-list__progress-bar",
                  children: /*#__PURE__*/(0,react_jsx_runtime__WEBPACK_IMPORTED_MODULE_5__.jsx)("div", {
                    className: "giftflowwp-campaigns-list__progress-fill",
                    style: {
                      width: "".concat(campaign.percentage, "%")
                    }
                  })
                }), /*#__PURE__*/(0,react_jsx_runtime__WEBPACK_IMPORTED_MODULE_5__.jsxs)("div", {
                  className: "giftflowwp-campaigns-list__meta",
                  children: [/*#__PURE__*/(0,react_jsx_runtime__WEBPACK_IMPORTED_MODULE_5__.jsxs)("span", {
                    className: "giftflowwp-campaigns-list__percentage",
                    children: [campaign.percentage, "% funded"]
                  }), campaign.start_date && /*#__PURE__*/(0,react_jsx_runtime__WEBPACK_IMPORTED_MODULE_5__.jsxs)("span", {
                    className: "giftflowwp-campaigns-list__date",
                    children: [/*#__PURE__*/(0,react_jsx_runtime__WEBPACK_IMPORTED_MODULE_5__.jsx)("strong", {
                      children: "Start:"
                    }), " ", /*#__PURE__*/(0,react_jsx_runtime__WEBPACK_IMPORTED_MODULE_5__.jsx)("span", {
                      className: "__monospace",
                      children: campaign.start_date.split("T")[0]
                    })]
                  }), campaign.end_date && /*#__PURE__*/(0,react_jsx_runtime__WEBPACK_IMPORTED_MODULE_5__.jsxs)("span", {
                    className: "giftflowwp-campaigns-list__date",
                    children: [/*#__PURE__*/(0,react_jsx_runtime__WEBPACK_IMPORTED_MODULE_5__.jsx)("strong", {
                      children: "End:"
                    }), " ", /*#__PURE__*/(0,react_jsx_runtime__WEBPACK_IMPORTED_MODULE_5__.jsx)("span", {
                      className: "__monospace",
                      children: campaign.end_date.split("T")[0]
                    })]
                  })]
                })]
              })
            }, campaign.id);
          })
        })
      })]
    }), /*#__PURE__*/(0,react_jsx_runtime__WEBPACK_IMPORTED_MODULE_5__.jsx)(_CampaignTrackingModalSettings__WEBPACK_IMPORTED_MODULE_4__["default"], {
      isOpen: isModalOpen,
      onClose: function onClose() {
        return setIsModalOpen(false);
      },
      onSave: onSave
    })]
  });
};
/* harmony default export */ const __WEBPACK_DEFAULT_EXPORT__ = (CampaignTracking);

/***/ }),

/***/ "./admin/js/components/CampaignTrackingModalSettings.jsx":
/*!***************************************************************!*\
  !*** ./admin/js/components/CampaignTrackingModalSettings.jsx ***!
  \***************************************************************/
/***/ ((__unused_webpack_module, __webpack_exports__, __webpack_require__) => {

__webpack_require__.r(__webpack_exports__);
/* harmony export */ __webpack_require__.d(__webpack_exports__, {
/* harmony export */   "default": () => (/* binding */ CampaignTrackingModalSettings)
/* harmony export */ });
/* harmony import */ var _babel_runtime_helpers_slicedToArray__WEBPACK_IMPORTED_MODULE_0__ = __webpack_require__(/*! @babel/runtime/helpers/slicedToArray */ "./node_modules/@babel/runtime/helpers/esm/slicedToArray.js");
/* harmony import */ var react__WEBPACK_IMPORTED_MODULE_1__ = __webpack_require__(/*! react */ "react");
/* harmony import */ var react__WEBPACK_IMPORTED_MODULE_1___default = /*#__PURE__*/__webpack_require__.n(react__WEBPACK_IMPORTED_MODULE_1__);
/* harmony import */ var _Modal__WEBPACK_IMPORTED_MODULE_2__ = __webpack_require__(/*! ./Modal */ "./admin/js/components/Modal.jsx");
/* harmony import */ var react_jsx_runtime__WEBPACK_IMPORTED_MODULE_3__ = __webpack_require__(/*! react/jsx-runtime */ "react/jsx-runtime");
/* harmony import */ var react_jsx_runtime__WEBPACK_IMPORTED_MODULE_3___default = /*#__PURE__*/__webpack_require__.n(react_jsx_runtime__WEBPACK_IMPORTED_MODULE_3__);




function CampaignTrackingModalSettings(_ref) {
  var isOpen = _ref.isOpen,
    onClose = _ref.onClose,
    onSave = _ref.onSave;
  var _useState = (0,react__WEBPACK_IMPORTED_MODULE_1__.useState)([]),
    _useState2 = (0,_babel_runtime_helpers_slicedToArray__WEBPACK_IMPORTED_MODULE_0__["default"])(_useState, 2),
    campaigns = _useState2[0],
    setCampaigns = _useState2[1];
  var _useState3 = (0,react__WEBPACK_IMPORTED_MODULE_1__.useState)(true),
    _useState4 = (0,_babel_runtime_helpers_slicedToArray__WEBPACK_IMPORTED_MODULE_0__["default"])(_useState3, 2),
    loading = _useState4[0],
    setLoading = _useState4[1];
  var _useState5 = (0,react__WEBPACK_IMPORTED_MODULE_1__.useState)(null),
    _useState6 = (0,_babel_runtime_helpers_slicedToArray__WEBPACK_IMPORTED_MODULE_0__["default"])(_useState5, 2),
    error = _useState6[0],
    setError = _useState6[1];
  var _useState7 = (0,react__WEBPACK_IMPORTED_MODULE_1__.useState)([]),
    _useState8 = (0,_babel_runtime_helpers_slicedToArray__WEBPACK_IMPORTED_MODULE_0__["default"])(_useState7, 2),
    selectedCampaigns = _useState8[0],
    setSelectedCampaigns = _useState8[1];
  (0,react__WEBPACK_IMPORTED_MODULE_1__.useEffect)(function () {
    if (isOpen) {
      setLoading(true);
      setError(null);
      fetch('/wp-json/giftflowwp/v1/campaigns?per_page=-1').then(function (res) {
        return res.json();
      }).then(function (data) {
        return setCampaigns(data);
      }).then(function () {
        return setLoading(false);
      })["catch"](function (err) {
        return setError(err);
      });
    }
  }, [isOpen]);
  return /*#__PURE__*/(0,react_jsx_runtime__WEBPACK_IMPORTED_MODULE_3__.jsx)(_Modal__WEBPACK_IMPORTED_MODULE_2__["default"], {
    isOpen: isOpen,
    onClose: onClose,
    title: "Select Campaigns to track",
    actions: [{
      text: "Save",
      variant: "primary",
      onClick: function onClick() {
        onSave(selectedCampaigns);
      }
    }, {
      text: "Close",
      variant: "secondary",
      onClick: function onClick() {
        onClose();
      }
    }],
    children: /*#__PURE__*/(0,react_jsx_runtime__WEBPACK_IMPORTED_MODULE_3__.jsxs)(react_jsx_runtime__WEBPACK_IMPORTED_MODULE_3__.Fragment, {
      children: [loading && /*#__PURE__*/(0,react_jsx_runtime__WEBPACK_IMPORTED_MODULE_3__.jsx)("div", {
        children: "Loading..."
      }), error && /*#__PURE__*/(0,react_jsx_runtime__WEBPACK_IMPORTED_MODULE_3__.jsxs)("div", {
        children: ["Error: ", error]
      }), loading === false && campaigns.length > 0 && /*#__PURE__*/(0,react_jsx_runtime__WEBPACK_IMPORTED_MODULE_3__.jsxs)("div", {
        className: "giftflowwp-campaigns-list__settings-container",
        children: [/*#__PURE__*/(0,react_jsx_runtime__WEBPACK_IMPORTED_MODULE_3__.jsx)("div", {
          className: "giftflowwp-campaigns-list__select-wrapper",
          children: /*#__PURE__*/(0,react_jsx_runtime__WEBPACK_IMPORTED_MODULE_3__.jsx)("select", {
            id: "campaign-multiselect",
            multiple: true,
            className: "giftflowwp-campaigns-list__multiselect",
            onChange: function onChange(e) {
              setSelectedCampaigns(Array.from(e.target.selectedOptions).map(function (opt) {
                return opt.value;
              }));
            },
            children: campaigns.map(function (campaign) {
              return /*#__PURE__*/(0,react_jsx_runtime__WEBPACK_IMPORTED_MODULE_3__.jsx)("option", {
                value: campaign.id,
                selected: selectedCampaigns.includes(campaign.id),
                className: "giftflowwp-campaigns-list__option",
                dangerouslySetInnerHTML: {
                  __html: "#ID ".concat(campaign.id, " \u2014 ").concat(campaign.title, " (").concat(campaign.status, ") (").concat(campaign.percentage, "%)")
                }
              }, campaign.id);
            })
          })
        }), /*#__PURE__*/(0,react_jsx_runtime__WEBPACK_IMPORTED_MODULE_3__.jsxs)("div", {
          className: "giftflowwp-campaigns-list__help-text __monospace",
          children: ["Hold ", /*#__PURE__*/(0,react_jsx_runtime__WEBPACK_IMPORTED_MODULE_3__.jsx)("span", {
            className: "giftflowwp-campaigns-list__key",
            children: "Ctrl"
          }), " (or ", /*#__PURE__*/(0,react_jsx_runtime__WEBPACK_IMPORTED_MODULE_3__.jsx)("span", {
            className: "giftflowwp-campaigns-list__key",
            children: "Cmd"
          }), " on Mac) and click to select multiple campaigns."]
        })]
      })]
    })
  });
}

/***/ }),

/***/ "./admin/js/components/DashboardView.jsx":
/*!***********************************************!*\
  !*** ./admin/js/components/DashboardView.jsx ***!
  \***********************************************/
/***/ ((__unused_webpack_module, __webpack_exports__, __webpack_require__) => {

__webpack_require__.r(__webpack_exports__);
/* harmony export */ __webpack_require__.d(__webpack_exports__, {
/* harmony export */   "default": () => (__WEBPACK_DEFAULT_EXPORT__)
/* harmony export */ });
/* harmony import */ var _Welcome__WEBPACK_IMPORTED_MODULE_0__ = __webpack_require__(/*! ./Welcome */ "./admin/js/components/Welcome.jsx");
/* harmony import */ var _OverView__WEBPACK_IMPORTED_MODULE_1__ = __webpack_require__(/*! ./OverView */ "./admin/js/components/OverView.jsx");
/* harmony import */ var react_jsx_runtime__WEBPACK_IMPORTED_MODULE_2__ = __webpack_require__(/*! react/jsx-runtime */ "react/jsx-runtime");
/* harmony import */ var react_jsx_runtime__WEBPACK_IMPORTED_MODULE_2___default = /*#__PURE__*/__webpack_require__.n(react_jsx_runtime__WEBPACK_IMPORTED_MODULE_2__);



var DashboardView = function DashboardView() {
  return /*#__PURE__*/(0,react_jsx_runtime__WEBPACK_IMPORTED_MODULE_2__.jsxs)("div", {
    className: "giftflowwp-dashboard-view",
    children: [/*#__PURE__*/(0,react_jsx_runtime__WEBPACK_IMPORTED_MODULE_2__.jsx)(_Welcome__WEBPACK_IMPORTED_MODULE_0__["default"], {}), /*#__PURE__*/(0,react_jsx_runtime__WEBPACK_IMPORTED_MODULE_2__.jsx)(_OverView__WEBPACK_IMPORTED_MODULE_1__["default"], {})]
  });
};
/* harmony default export */ const __WEBPACK_DEFAULT_EXPORT__ = (DashboardView);

/***/ }),

/***/ "./admin/js/components/Modal.jsx":
/*!***************************************!*\
  !*** ./admin/js/components/Modal.jsx ***!
  \***************************************/
/***/ ((__unused_webpack_module, __webpack_exports__, __webpack_require__) => {

__webpack_require__.r(__webpack_exports__);
/* harmony export */ __webpack_require__.d(__webpack_exports__, {
/* harmony export */   "default": () => (__WEBPACK_DEFAULT_EXPORT__)
/* harmony export */ });
/* harmony import */ var react__WEBPACK_IMPORTED_MODULE_0__ = __webpack_require__(/*! react */ "react");
/* harmony import */ var react__WEBPACK_IMPORTED_MODULE_0___default = /*#__PURE__*/__webpack_require__.n(react__WEBPACK_IMPORTED_MODULE_0__);
/* harmony import */ var _hooks_useModal__WEBPACK_IMPORTED_MODULE_1__ = __webpack_require__(/*! ../hooks/useModal */ "./admin/js/hooks/useModal.js");
/* harmony import */ var react_jsx_runtime__WEBPACK_IMPORTED_MODULE_2__ = __webpack_require__(/*! react/jsx-runtime */ "react/jsx-runtime");
/* harmony import */ var react_jsx_runtime__WEBPACK_IMPORTED_MODULE_2___default = /*#__PURE__*/__webpack_require__.n(react_jsx_runtime__WEBPACK_IMPORTED_MODULE_2__);



/**
 * Modal Component - A flexible and reusable modal component
 * 
 * @param {Object} props - Component props
 * @param {boolean} props.isOpen - Whether the modal is open
 * @param {Function} props.onClose - Function to call when modal closes
 * @param {string} props.title - Modal title
 * @param {React.ReactNode} props.children - Modal content
 * @param {string} props.size - Modal size ('small', 'medium', 'large', 'fullscreen')
 * @param {string} props.variant - Modal variant ('default', 'loading', 'with-image', 'no-padding')
 * @param {string} props.overlay - Overlay variant ('default', 'blur', 'dark', 'light')
 * @param {string} props.position - Modal position ('center', 'top', 'bottom', 'left', 'right')
 * @param {React.ReactNode} props.footer - Custom footer content
 * @param {Array} props.actions - Array of action buttons
 * @param {boolean} props.showCloseButton - Whether to show close button
 * @param {boolean} props.closeOnOverlayClick - Whether to close on overlay click
 * @param {boolean} props.closeOnEscape - Whether to close on ESC key
 * @param {boolean} props.preventBodyScroll - Whether to prevent body scroll
 * @param {string} props.className - Additional CSS classes
 * @param {Object} props.modalData - Data passed to the modal
 * @param {string} props.imageSrc - Image source for image variant
 * @param {string} props.imageAlt - Image alt text for image variant
 * 
 * @example
 * // Basic modal
 * <Modal 
 *   isOpen={isOpen} 
 *   onClose={() => setIsOpen(false)}
 *   title="Confirm Action"
 * >
 *   <p>Are you sure you want to proceed?</p>
 * </Modal>
 * 
 * @example
 * // Modal with actions
 * <Modal 
 *   isOpen={isOpen} 
 *   onClose={() => setIsOpen(false)}
 *   title="Delete Campaign"
 *   size="small"
 *   actions={[
 *     { text: 'Cancel', variant: 'secondary', onClick: () => setIsOpen(false) },
 *     { text: 'Delete', variant: 'danger', onClick: handleDelete }
 *   ]}
 * >
 *   <p>This action cannot be undone.</p>
 * </Modal>
 * 
 * @example
 * // Modal with image
 * <Modal 
 *   isOpen={isOpen} 
 *   onClose={() => setIsOpen(false)}
 *   title="Success!"
 *   variant="with-image"
 *   imageSrc="/success-icon.png"
 *   imageAlt="Success"
 *   size="small"
 * >
 *   <p>Your campaign has been created successfully.</p>
 * </Modal>
 */

var Modal = function Modal(_ref) {
  var _ref$isOpen = _ref.isOpen,
    isOpen = _ref$isOpen === void 0 ? false : _ref$isOpen,
    onClose = _ref.onClose,
    title = _ref.title,
    children = _ref.children,
    _ref$size = _ref.size,
    size = _ref$size === void 0 ? 'medium' : _ref$size,
    _ref$variant = _ref.variant,
    variant = _ref$variant === void 0 ? 'default' : _ref$variant,
    _ref$overlay = _ref.overlay,
    overlay = _ref$overlay === void 0 ? 'default' : _ref$overlay,
    _ref$position = _ref.position,
    position = _ref$position === void 0 ? 'center' : _ref$position,
    footer = _ref.footer,
    _ref$actions = _ref.actions,
    actions = _ref$actions === void 0 ? [] : _ref$actions,
    _ref$showCloseButton = _ref.showCloseButton,
    showCloseButton = _ref$showCloseButton === void 0 ? true : _ref$showCloseButton,
    _ref$closeOnOverlayCl = _ref.closeOnOverlayClick,
    closeOnOverlayClick = _ref$closeOnOverlayCl === void 0 ? true : _ref$closeOnOverlayCl,
    _ref$closeOnEscape = _ref.closeOnEscape,
    closeOnEscape = _ref$closeOnEscape === void 0 ? true : _ref$closeOnEscape,
    _ref$preventBodyScrol = _ref.preventBodyScroll,
    preventBodyScroll = _ref$preventBodyScrol === void 0 ? true : _ref$preventBodyScrol,
    _ref$className = _ref.className,
    className = _ref$className === void 0 ? '' : _ref$className,
    modalData = _ref.modalData,
    imageSrc = _ref.imageSrc,
    _ref$imageAlt = _ref.imageAlt,
    imageAlt = _ref$imageAlt === void 0 ? 'Modal image' : _ref$imageAlt;
  var modal = (0,_hooks_useModal__WEBPACK_IMPORTED_MODULE_1__["default"])({
    initialOpen: isOpen,
    closeOnEscape: closeOnEscape,
    closeOnOverlayClick: closeOnOverlayClick,
    preventBodyScroll: preventBodyScroll
  });

  // Sync external isOpen state with internal hook state
  react__WEBPACK_IMPORTED_MODULE_0___default().useEffect(function () {
    if (isOpen) {
      modal.openModal(modalData);
    } else {
      modal.closeModal();
    }
  }, [isOpen, modalData]);

  // Handle close
  var handleClose = function handleClose() {
    modal.closeModal();
    if (onClose) {
      onClose();
    }
  };

  // Handle overlay click
  var handleOverlayClick = function handleOverlayClick(event) {
    if (closeOnOverlayClick) {
      modal.handleOverlayClick(event);
      if (onClose) {
        onClose();
      }
    }
  };

  // Build CSS classes
  var modalClasses = ['giftflowwp-modal', isOpen ? 'giftflowwp-modal--open' : '', size !== 'medium' ? "giftflowwp-modal--".concat(size) : '', variant !== 'default' ? "giftflowwp-modal--".concat(variant) : '', overlay !== 'default' ? "giftflowwp-modal-overlay--".concat(overlay) : '', position !== 'center' ? "giftflowwp-modal--".concat(position) : '', className].filter(Boolean).join(' ');

  // Render close button
  var renderCloseButton = function renderCloseButton() {
    if (!showCloseButton) return null;
    return /*#__PURE__*/(0,react_jsx_runtime__WEBPACK_IMPORTED_MODULE_2__.jsx)("button", {
      className: "giftflowwp-modal__close",
      onClick: handleClose,
      "aria-label": "Close modal",
      children: /*#__PURE__*/(0,react_jsx_runtime__WEBPACK_IMPORTED_MODULE_2__.jsxs)("svg", {
        width: "20",
        height: "20",
        viewBox: "0 0 24 24",
        fill: "none",
        stroke: "currentColor",
        strokeWidth: "2",
        children: [/*#__PURE__*/(0,react_jsx_runtime__WEBPACK_IMPORTED_MODULE_2__.jsx)("line", {
          x1: "18",
          y1: "6",
          x2: "6",
          y2: "18"
        }), /*#__PURE__*/(0,react_jsx_runtime__WEBPACK_IMPORTED_MODULE_2__.jsx)("line", {
          x1: "6",
          y1: "6",
          x2: "18",
          y2: "18"
        })]
      })
    });
  };

  // Render header
  var renderHeader = function renderHeader() {
    if (!title && !showCloseButton && variant !== 'with-image') return null;
    return /*#__PURE__*/(0,react_jsx_runtime__WEBPACK_IMPORTED_MODULE_2__.jsxs)("div", {
      className: "giftflowwp-modal__header",
      children: [variant === 'with-image' && imageSrc && /*#__PURE__*/(0,react_jsx_runtime__WEBPACK_IMPORTED_MODULE_2__.jsx)("img", {
        src: imageSrc,
        alt: imageAlt,
        className: "giftflowwp-modal__image"
      }), title && /*#__PURE__*/(0,react_jsx_runtime__WEBPACK_IMPORTED_MODULE_2__.jsx)("h3", {
        className: "giftflowwp-modal__title",
        children: title
      }), renderCloseButton()]
    });
  };

  // Render footer
  var renderFooter = function renderFooter() {
    if (!footer && actions.length === 0) return null;
    return /*#__PURE__*/(0,react_jsx_runtime__WEBPACK_IMPORTED_MODULE_2__.jsx)("div", {
      className: "giftflowwp-modal__footer",
      children: footer || /*#__PURE__*/(0,react_jsx_runtime__WEBPACK_IMPORTED_MODULE_2__.jsx)("div", {
        className: "giftflowwp-modal__actions",
        children: actions.map(function (action, index) {
          return /*#__PURE__*/(0,react_jsx_runtime__WEBPACK_IMPORTED_MODULE_2__.jsxs)("button", {
            className: "giftflowwp-modal__button giftflowwp-modal__button--".concat(action.variant || 'secondary'),
            onClick: action.onClick,
            disabled: action.disabled,
            type: action.type || 'button',
            children: [action.icon && /*#__PURE__*/(0,react_jsx_runtime__WEBPACK_IMPORTED_MODULE_2__.jsx)("span", {
              className: "giftflowwp-modal__button-icon",
              children: action.icon
            }), action.text]
          }, index);
        })
      })
    });
  };

  // Don't render if not open
  if (!isOpen) return null;
  return /*#__PURE__*/(0,react_jsx_runtime__WEBPACK_IMPORTED_MODULE_2__.jsx)("div", {
    className: modalClasses
    // onClick={handleOverlayClick} 
    ,

    children: /*#__PURE__*/(0,react_jsx_runtime__WEBPACK_IMPORTED_MODULE_2__.jsxs)("div", {
      className: "giftflowwp-modal__content",
      onClick: function onClick(e) {
        return e.stopPropagation();
      },
      children: [renderHeader(), /*#__PURE__*/(0,react_jsx_runtime__WEBPACK_IMPORTED_MODULE_2__.jsxs)("div", {
        className: "giftflowwp-modal__body",
        children: [variant === 'loading' && /*#__PURE__*/(0,react_jsx_runtime__WEBPACK_IMPORTED_MODULE_2__.jsx)("div", {
          className: "giftflowwp-modal__spinner"
        }), children]
      }), renderFooter()]
    })
  });
};
/* harmony default export */ const __WEBPACK_DEFAULT_EXPORT__ = (Modal);

/***/ }),

/***/ "./admin/js/components/OverView.jsx":
/*!******************************************!*\
  !*** ./admin/js/components/OverView.jsx ***!
  \******************************************/
/***/ ((__unused_webpack_module, __webpack_exports__, __webpack_require__) => {

__webpack_require__.r(__webpack_exports__);
/* harmony export */ __webpack_require__.d(__webpack_exports__, {
/* harmony export */   "default": () => (__WEBPACK_DEFAULT_EXPORT__)
/* harmony export */ });
/* harmony import */ var react__WEBPACK_IMPORTED_MODULE_0__ = __webpack_require__(/*! react */ "react");
/* harmony import */ var react__WEBPACK_IMPORTED_MODULE_0___default = /*#__PURE__*/__webpack_require__.n(react__WEBPACK_IMPORTED_MODULE_0__);
/* harmony import */ var _QuickActions__WEBPACK_IMPORTED_MODULE_1__ = __webpack_require__(/*! ./QuickActions */ "./admin/js/components/QuickActions.jsx");
/* harmony import */ var _CampaignTracking__WEBPACK_IMPORTED_MODULE_2__ = __webpack_require__(/*! ./CampaignTracking */ "./admin/js/components/CampaignTracking.jsx");
/* harmony import */ var react_jsx_runtime__WEBPACK_IMPORTED_MODULE_3__ = __webpack_require__(/*! react/jsx-runtime */ "react/jsx-runtime");
/* harmony import */ var react_jsx_runtime__WEBPACK_IMPORTED_MODULE_3___default = /*#__PURE__*/__webpack_require__.n(react_jsx_runtime__WEBPACK_IMPORTED_MODULE_3__);




var OverView = function OverView() {
  return /*#__PURE__*/(0,react_jsx_runtime__WEBPACK_IMPORTED_MODULE_3__.jsxs)("div", {
    className: "giftflowwp-overview",
    children: [/*#__PURE__*/(0,react_jsx_runtime__WEBPACK_IMPORTED_MODULE_3__.jsxs)("div", {
      className: "giftflowwp-overview__main",
      children: [/*#__PURE__*/(0,react_jsx_runtime__WEBPACK_IMPORTED_MODULE_3__.jsx)("h3", {
        className: "giftflowwp-overview__title",
        children: "Dashboard Overview"
      }), /*#__PURE__*/(0,react_jsx_runtime__WEBPACK_IMPORTED_MODULE_3__.jsxs)("div", {
        className: "giftflowwp-overview__stats",
        children: [/*#__PURE__*/(0,react_jsx_runtime__WEBPACK_IMPORTED_MODULE_3__.jsxs)("div", {
          className: "giftflowwp-overview__stat-card",
          children: [/*#__PURE__*/(0,react_jsx_runtime__WEBPACK_IMPORTED_MODULE_3__.jsx)("div", {
            className: "giftflowwp-overview__stat-icon",
            children: "\uD83D\uDCB0"
          }), /*#__PURE__*/(0,react_jsx_runtime__WEBPACK_IMPORTED_MODULE_3__.jsxs)("div", {
            className: "giftflowwp-overview__stat-content",
            children: [/*#__PURE__*/(0,react_jsx_runtime__WEBPACK_IMPORTED_MODULE_3__.jsx)("span", {
              className: "giftflowwp-overview__stat-value",
              children: "$12,450"
            }), /*#__PURE__*/(0,react_jsx_runtime__WEBPACK_IMPORTED_MODULE_3__.jsx)("span", {
              className: "giftflowwp-overview__stat-label",
              children: "Total Raised"
            })]
          })]
        }), /*#__PURE__*/(0,react_jsx_runtime__WEBPACK_IMPORTED_MODULE_3__.jsxs)("div", {
          className: "giftflowwp-overview__stat-card",
          children: [/*#__PURE__*/(0,react_jsx_runtime__WEBPACK_IMPORTED_MODULE_3__.jsx)("div", {
            className: "giftflowwp-overview__stat-icon",
            children: "\uD83C\uDFAF"
          }), /*#__PURE__*/(0,react_jsx_runtime__WEBPACK_IMPORTED_MODULE_3__.jsxs)("div", {
            className: "giftflowwp-overview__stat-content",
            children: [/*#__PURE__*/(0,react_jsx_runtime__WEBPACK_IMPORTED_MODULE_3__.jsx)("span", {
              className: "giftflowwp-overview__stat-value",
              children: "8"
            }), /*#__PURE__*/(0,react_jsx_runtime__WEBPACK_IMPORTED_MODULE_3__.jsx)("span", {
              className: "giftflowwp-overview__stat-label",
              children: "Active Campaigns"
            })]
          })]
        }), /*#__PURE__*/(0,react_jsx_runtime__WEBPACK_IMPORTED_MODULE_3__.jsxs)("div", {
          className: "giftflowwp-overview__stat-card",
          children: [/*#__PURE__*/(0,react_jsx_runtime__WEBPACK_IMPORTED_MODULE_3__.jsx)("div", {
            className: "giftflowwp-overview__stat-icon",
            children: "\uD83D\uDC65"
          }), /*#__PURE__*/(0,react_jsx_runtime__WEBPACK_IMPORTED_MODULE_3__.jsxs)("div", {
            className: "giftflowwp-overview__stat-content",
            children: [/*#__PURE__*/(0,react_jsx_runtime__WEBPACK_IMPORTED_MODULE_3__.jsx)("span", {
              className: "giftflowwp-overview__stat-value",
              children: "156"
            }), /*#__PURE__*/(0,react_jsx_runtime__WEBPACK_IMPORTED_MODULE_3__.jsx)("span", {
              className: "giftflowwp-overview__stat-label",
              children: "Total Donors"
            })]
          })]
        })]
      }), /*#__PURE__*/(0,react_jsx_runtime__WEBPACK_IMPORTED_MODULE_3__.jsxs)("div", {
        className: "giftflowwp-overview__recent-activity",
        children: [/*#__PURE__*/(0,react_jsx_runtime__WEBPACK_IMPORTED_MODULE_3__.jsx)("h4", {
          children: "Recent Activity"
        }), /*#__PURE__*/(0,react_jsx_runtime__WEBPACK_IMPORTED_MODULE_3__.jsxs)("div", {
          className: "giftflowwp-overview__activity-list",
          children: [/*#__PURE__*/(0,react_jsx_runtime__WEBPACK_IMPORTED_MODULE_3__.jsxs)("div", {
            className: "giftflowwp-overview__activity-item",
            children: [/*#__PURE__*/(0,react_jsx_runtime__WEBPACK_IMPORTED_MODULE_3__.jsx)("div", {
              className: "giftflowwp-overview__activity-icon",
              children: "\uD83D\uDC9D"
            }), /*#__PURE__*/(0,react_jsx_runtime__WEBPACK_IMPORTED_MODULE_3__.jsxs)("div", {
              className: "giftflowwp-overview__activity-content",
              children: [/*#__PURE__*/(0,react_jsx_runtime__WEBPACK_IMPORTED_MODULE_3__.jsx)("span", {
                className: "giftflowwp-overview__activity-text",
                children: "New donation of $250 received"
              }), /*#__PURE__*/(0,react_jsx_runtime__WEBPACK_IMPORTED_MODULE_3__.jsx)("span", {
                className: "giftflowwp-overview__activity-time",
                children: "2 hours ago"
              })]
            })]
          }), /*#__PURE__*/(0,react_jsx_runtime__WEBPACK_IMPORTED_MODULE_3__.jsxs)("div", {
            className: "giftflowwp-overview__activity-item",
            children: [/*#__PURE__*/(0,react_jsx_runtime__WEBPACK_IMPORTED_MODULE_3__.jsx)("div", {
              className: "giftflowwp-overview__activity-icon",
              children: "\uD83C\uDF89"
            }), /*#__PURE__*/(0,react_jsx_runtime__WEBPACK_IMPORTED_MODULE_3__.jsxs)("div", {
              className: "giftflowwp-overview__activity-content",
              children: [/*#__PURE__*/(0,react_jsx_runtime__WEBPACK_IMPORTED_MODULE_3__.jsx)("span", {
                className: "giftflowwp-overview__activity-text",
                children: "\"Emergency Fund\" reached 75% of goal"
              }), /*#__PURE__*/(0,react_jsx_runtime__WEBPACK_IMPORTED_MODULE_3__.jsx)("span", {
                className: "giftflowwp-overview__activity-time",
                children: "5 hours ago"
              })]
            })]
          }), /*#__PURE__*/(0,react_jsx_runtime__WEBPACK_IMPORTED_MODULE_3__.jsxs)("div", {
            className: "giftflowwp-overview__activity-item",
            children: [/*#__PURE__*/(0,react_jsx_runtime__WEBPACK_IMPORTED_MODULE_3__.jsx)("div", {
              className: "giftflowwp-overview__activity-icon",
              children: "\uD83D\uDC64"
            }), /*#__PURE__*/(0,react_jsx_runtime__WEBPACK_IMPORTED_MODULE_3__.jsxs)("div", {
              className: "giftflowwp-overview__activity-content",
              children: [/*#__PURE__*/(0,react_jsx_runtime__WEBPACK_IMPORTED_MODULE_3__.jsx)("span", {
                className: "giftflowwp-overview__activity-text",
                children: "New donor registered"
              }), /*#__PURE__*/(0,react_jsx_runtime__WEBPACK_IMPORTED_MODULE_3__.jsx)("span", {
                className: "giftflowwp-overview__activity-time",
                children: "1 day ago"
              })]
            })]
          })]
        })]
      })]
    }), /*#__PURE__*/(0,react_jsx_runtime__WEBPACK_IMPORTED_MODULE_3__.jsxs)("div", {
      className: "giftflowwp-overview__sidebar",
      children: [/*#__PURE__*/(0,react_jsx_runtime__WEBPACK_IMPORTED_MODULE_3__.jsx)(_QuickActions__WEBPACK_IMPORTED_MODULE_1__["default"], {}), /*#__PURE__*/(0,react_jsx_runtime__WEBPACK_IMPORTED_MODULE_3__.jsx)(_CampaignTracking__WEBPACK_IMPORTED_MODULE_2__["default"], {})]
    })]
  });
};
/* harmony default export */ const __WEBPACK_DEFAULT_EXPORT__ = (OverView);

/***/ }),

/***/ "./admin/js/components/QuickActions.jsx":
/*!**********************************************!*\
  !*** ./admin/js/components/QuickActions.jsx ***!
  \**********************************************/
/***/ ((__unused_webpack_module, __webpack_exports__, __webpack_require__) => {

__webpack_require__.r(__webpack_exports__);
/* harmony export */ __webpack_require__.d(__webpack_exports__, {
/* harmony export */   "default": () => (/* binding */ QuickActions)
/* harmony export */ });
/* harmony import */ var lucide_react__WEBPACK_IMPORTED_MODULE_0__ = __webpack_require__(/*! lucide-react */ "./node_modules/lucide-react/dist/esm/icons/file-down.js");
/* harmony import */ var react_jsx_runtime__WEBPACK_IMPORTED_MODULE_1__ = __webpack_require__(/*! react/jsx-runtime */ "react/jsx-runtime");
/* harmony import */ var react_jsx_runtime__WEBPACK_IMPORTED_MODULE_1___default = /*#__PURE__*/__webpack_require__.n(react_jsx_runtime__WEBPACK_IMPORTED_MODULE_1__);


function QuickActions() {
  return /*#__PURE__*/(0,react_jsx_runtime__WEBPACK_IMPORTED_MODULE_1__.jsxs)("div", {
    className: "giftflowwp-overview__widget",
    children: [/*#__PURE__*/(0,react_jsx_runtime__WEBPACK_IMPORTED_MODULE_1__.jsx)("h4", {
      className: "giftflowwp-overview__widget-title",
      children: "Quick Actions"
    }), /*#__PURE__*/(0,react_jsx_runtime__WEBPACK_IMPORTED_MODULE_1__.jsxs)("div", {
      className: "giftflowwp-overview__action-list",
      children: [/*#__PURE__*/(0,react_jsx_runtime__WEBPACK_IMPORTED_MODULE_1__.jsxs)("button", {
        className: "giftflowwp-overview__action-btn __monospace",
        type: "button",
        children: [/*#__PURE__*/(0,react_jsx_runtime__WEBPACK_IMPORTED_MODULE_1__.jsx)("span", {
          className: "giftflowwp-overview__action-icon",
          children: /*#__PURE__*/(0,react_jsx_runtime__WEBPACK_IMPORTED_MODULE_1__.jsx)(lucide_react__WEBPACK_IMPORTED_MODULE_0__["default"], {
            color: "#FFF",
            size: 20
          })
        }), "Export Campaign"]
      }), /*#__PURE__*/(0,react_jsx_runtime__WEBPACK_IMPORTED_MODULE_1__.jsxs)("button", {
        className: "giftflowwp-overview__action-btn __monospace",
        type: "button",
        children: [/*#__PURE__*/(0,react_jsx_runtime__WEBPACK_IMPORTED_MODULE_1__.jsx)("span", {
          className: "giftflowwp-overview__action-icon",
          children: /*#__PURE__*/(0,react_jsx_runtime__WEBPACK_IMPORTED_MODULE_1__.jsx)(lucide_react__WEBPACK_IMPORTED_MODULE_0__["default"], {
            color: "#FFF",
            size: 20
          })
        }), "Export Donor"]
      })]
    })]
  });
}

/***/ }),

/***/ "./admin/js/components/Welcome.jsx":
/*!*****************************************!*\
  !*** ./admin/js/components/Welcome.jsx ***!
  \*****************************************/
/***/ ((__unused_webpack_module, __webpack_exports__, __webpack_require__) => {

__webpack_require__.r(__webpack_exports__);
/* harmony export */ __webpack_require__.d(__webpack_exports__, {
/* harmony export */   "default": () => (__WEBPACK_DEFAULT_EXPORT__)
/* harmony export */ });
/* harmony import */ var react__WEBPACK_IMPORTED_MODULE_0__ = __webpack_require__(/*! react */ "react");
/* harmony import */ var react__WEBPACK_IMPORTED_MODULE_0___default = /*#__PURE__*/__webpack_require__.n(react__WEBPACK_IMPORTED_MODULE_0__);
/* harmony import */ var lucide_react__WEBPACK_IMPORTED_MODULE_1__ = __webpack_require__(/*! lucide-react */ "./node_modules/lucide-react/dist/esm/icons/sparkles.js");
/* harmony import */ var react_jsx_runtime__WEBPACK_IMPORTED_MODULE_2__ = __webpack_require__(/*! react/jsx-runtime */ "react/jsx-runtime");
/* harmony import */ var react_jsx_runtime__WEBPACK_IMPORTED_MODULE_2___default = /*#__PURE__*/__webpack_require__.n(react_jsx_runtime__WEBPACK_IMPORTED_MODULE_2__);



var Welcome = function Welcome() {
  return /*#__PURE__*/(0,react_jsx_runtime__WEBPACK_IMPORTED_MODULE_2__.jsxs)("div", {
    className: "giftflowwp-welcome",
    children: [/*#__PURE__*/(0,react_jsx_runtime__WEBPACK_IMPORTED_MODULE_2__.jsx)("div", {
      className: "giftflowwp-welcome__header",
      children: /*#__PURE__*/(0,react_jsx_runtime__WEBPACK_IMPORTED_MODULE_2__.jsxs)("div", {
        className: "giftflowwp-welcome__logo-section",
        children: [/*#__PURE__*/(0,react_jsx_runtime__WEBPACK_IMPORTED_MODULE_2__.jsx)(lucide_react__WEBPACK_IMPORTED_MODULE_1__["default"], {
          width: 48,
          height: 48
        }), /*#__PURE__*/(0,react_jsx_runtime__WEBPACK_IMPORTED_MODULE_2__.jsxs)("div", {
          className: "giftflowwp-welcome__title-section",
          children: [/*#__PURE__*/(0,react_jsx_runtime__WEBPACK_IMPORTED_MODULE_2__.jsx)("h2", {
            className: "giftflowwp-welcome__title",
            children: "GiftFlowWP Dashboard"
          }), /*#__PURE__*/(0,react_jsx_runtime__WEBPACK_IMPORTED_MODULE_2__.jsx)("p", {
            className: "giftflowwp-welcome__subtitle __monospace",
            children: "Your hub for managing fundraising campaigns and settings."
          })]
        })]
      })
    }), /*#__PURE__*/(0,react_jsx_runtime__WEBPACK_IMPORTED_MODULE_2__.jsxs)("div", {
      className: "giftflowwp-welcome__content",
      children: [/*#__PURE__*/(0,react_jsx_runtime__WEBPACK_IMPORTED_MODULE_2__.jsx)("div", {
        className: "giftflowwp-welcome__left-column",
        children: /*#__PURE__*/(0,react_jsx_runtime__WEBPACK_IMPORTED_MODULE_2__.jsxs)("div", {
          className: "giftflowwp-welcome__features",
          children: [/*#__PURE__*/(0,react_jsx_runtime__WEBPACK_IMPORTED_MODULE_2__.jsx)("h3", {
            children: "Key Features"
          }), /*#__PURE__*/(0,react_jsx_runtime__WEBPACK_IMPORTED_MODULE_2__.jsxs)("ul", {
            className: "giftflowwp-welcome__features-list __monospace",
            children: [/*#__PURE__*/(0,react_jsx_runtime__WEBPACK_IMPORTED_MODULE_2__.jsxs)("li", {
              children: ["\uD83D\uDE80 ", /*#__PURE__*/(0,react_jsx_runtime__WEBPACK_IMPORTED_MODULE_2__.jsx)("strong", {
                children: "Create and launch new fundraising campaigns"
              }), " in just a few clicks."]
            }), /*#__PURE__*/(0,react_jsx_runtime__WEBPACK_IMPORTED_MODULE_2__.jsxs)("li", {
              children: ["\u2699\uFE0F ", /*#__PURE__*/(0,react_jsx_runtime__WEBPACK_IMPORTED_MODULE_2__.jsx)("strong", {
                children: "Customize plugin settings"
              }), " to match your organization's needs."]
            }), /*#__PURE__*/(0,react_jsx_runtime__WEBPACK_IMPORTED_MODULE_2__.jsxs)("li", {
              children: ["\uD83D\uDCCA ", /*#__PURE__*/(0,react_jsx_runtime__WEBPACK_IMPORTED_MODULE_2__.jsx)("strong", {
                children: "Track campaign progress"
              }), " and donor engagement from your dashboard."]
            })]
          })]
        })
      }), /*#__PURE__*/(0,react_jsx_runtime__WEBPACK_IMPORTED_MODULE_2__.jsxs)("div", {
        className: "giftflowwp-welcome__right-column",
        children: [/*#__PURE__*/(0,react_jsx_runtime__WEBPACK_IMPORTED_MODULE_2__.jsxs)("div", {
          className: "giftflowwp-welcome__actions",
          children: [/*#__PURE__*/(0,react_jsx_runtime__WEBPACK_IMPORTED_MODULE_2__.jsxs)("a", {
            href: "admin.php?page=giftflowwp_create_campaign",
            className: "giftflowwp-welcome__action-btn",
            children: [/*#__PURE__*/(0,react_jsx_runtime__WEBPACK_IMPORTED_MODULE_2__.jsx)("span", {
              role: "img",
              "aria-label": "Create",
              children: "\u2795"
            }), " Create Campaign"]
          }), /*#__PURE__*/(0,react_jsx_runtime__WEBPACK_IMPORTED_MODULE_2__.jsxs)("a", {
            href: "admin.php?page=giftflowwp_settings",
            className: "giftflowwp-welcome__action-btn",
            children: [/*#__PURE__*/(0,react_jsx_runtime__WEBPACK_IMPORTED_MODULE_2__.jsx)("span", {
              role: "img",
              "aria-label": "Settings",
              children: "\u2699\uFE0F"
            }), " Go to Settings"]
          })]
        }), /*#__PURE__*/(0,react_jsx_runtime__WEBPACK_IMPORTED_MODULE_2__.jsxs)("div", {
          className: "giftflowwp-welcome__help",
          children: [/*#__PURE__*/(0,react_jsx_runtime__WEBPACK_IMPORTED_MODULE_2__.jsx)("h4", {
            children: "Need Help?"
          }), /*#__PURE__*/(0,react_jsx_runtime__WEBPACK_IMPORTED_MODULE_2__.jsxs)("p", {
            children: ["Visit our ", /*#__PURE__*/(0,react_jsx_runtime__WEBPACK_IMPORTED_MODULE_2__.jsx)("a", {
              href: "https://giftflowwp.com/docs",
              target: "_blank",
              rel: "noopener noreferrer",
              children: "documentation"
            }), " or ", /*#__PURE__*/(0,react_jsx_runtime__WEBPACK_IMPORTED_MODULE_2__.jsx)("a", {
              href: "https://giftflowwp.com/support",
              target: "_blank",
              rel: "noopener noreferrer",
              children: "contact support"
            }), "."]
          })]
        })]
      })]
    })]
  });
};
/* harmony default export */ const __WEBPACK_DEFAULT_EXPORT__ = (Welcome);

/***/ }),

/***/ "./admin/js/hooks/useCampaign.js":
/*!***************************************!*\
  !*** ./admin/js/hooks/useCampaign.js ***!
  \***************************************/
/***/ ((__unused_webpack_module, __webpack_exports__, __webpack_require__) => {

__webpack_require__.r(__webpack_exports__);
/* harmony export */ __webpack_require__.d(__webpack_exports__, {
/* harmony export */   "default": () => (/* binding */ useCampaign)
/* harmony export */ });
/* harmony import */ var _babel_runtime_helpers_defineProperty__WEBPACK_IMPORTED_MODULE_0__ = __webpack_require__(/*! @babel/runtime/helpers/defineProperty */ "./node_modules/@babel/runtime/helpers/esm/defineProperty.js");
/* harmony import */ var _babel_runtime_helpers_slicedToArray__WEBPACK_IMPORTED_MODULE_1__ = __webpack_require__(/*! @babel/runtime/helpers/slicedToArray */ "./node_modules/@babel/runtime/helpers/esm/slicedToArray.js");
/* harmony import */ var react__WEBPACK_IMPORTED_MODULE_2__ = __webpack_require__(/*! react */ "react");
/* harmony import */ var react__WEBPACK_IMPORTED_MODULE_2___default = /*#__PURE__*/__webpack_require__.n(react__WEBPACK_IMPORTED_MODULE_2__);


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
      (0,_babel_runtime_helpers_defineProperty__WEBPACK_IMPORTED_MODULE_0__["default"])(e, r, t[r]);
    }) : Object.getOwnPropertyDescriptors ? Object.defineProperties(e, Object.getOwnPropertyDescriptors(t)) : ownKeys(Object(t)).forEach(function (r) {
      Object.defineProperty(e, r, Object.getOwnPropertyDescriptor(t, r));
    });
  }
  return e;
}

function useCampaign() {
  var args = arguments.length > 0 && arguments[0] !== undefined ? arguments[0] : {};
  // Set default values for params using destructuring
  var _ref = args || {},
    _ref$includeIds = _ref.includeIds,
    includeIds = _ref$includeIds === void 0 ? [] : _ref$includeIds,
    _ref$excludeIds = _ref.excludeIds,
    excludeIds = _ref$excludeIds === void 0 ? [] : _ref$excludeIds,
    _ref$search = _ref.search,
    search = _ref$search === void 0 ? '' : _ref$search,
    _ref$order = _ref.order,
    order = _ref$order === void 0 ? 'desc' : _ref$order,
    _ref$orderby = _ref.orderby,
    orderby = _ref$orderby === void 0 ? 'date' : _ref$orderby,
    _ref$page = _ref.page,
    page = _ref$page === void 0 ? 1 : _ref$page,
    _ref$per_page = _ref.per_page,
    per_page = _ref$per_page === void 0 ? 10 : _ref$per_page;
  var _useState = (0,react__WEBPACK_IMPORTED_MODULE_2__.useState)([]),
    _useState2 = (0,_babel_runtime_helpers_slicedToArray__WEBPACK_IMPORTED_MODULE_1__["default"])(_useState, 2),
    campaigns = _useState2[0],
    setCampaigns = _useState2[1];
  var _useState3 = (0,react__WEBPACK_IMPORTED_MODULE_2__.useState)(true),
    _useState4 = (0,_babel_runtime_helpers_slicedToArray__WEBPACK_IMPORTED_MODULE_1__["default"])(_useState3, 2),
    loading = _useState4[0],
    setLoading = _useState4[1];
  var _useState5 = (0,react__WEBPACK_IMPORTED_MODULE_2__.useState)(null),
    _useState6 = (0,_babel_runtime_helpers_slicedToArray__WEBPACK_IMPORTED_MODULE_1__["default"])(_useState5, 2),
    error = _useState6[0],
    setError = _useState6[1];
  var _useState7 = (0,react__WEBPACK_IMPORTED_MODULE_2__.useState)({
      include: includeIds || [],
      exclude: excludeIds || [],
      search: search || '',
      order: order || 'desc',
      orderby: orderby || 'date',
      page: page || 1,
      per_page: per_page || 10
    }),
    _useState8 = (0,_babel_runtime_helpers_slicedToArray__WEBPACK_IMPORTED_MODULE_1__["default"])(_useState7, 2),
    params = _useState8[0],
    setParams = _useState8[1];

  // Function to update params state
  var updateParams = function updateParams(newParams) {
    setParams(function (prev) {
      return _objectSpread(_objectSpread({}, prev), newParams);
    });
  };
  (0,react__WEBPACK_IMPORTED_MODULE_2__.useEffect)(function () {
    var isMounted = true;
    setLoading(true);
    setError(null);

    // Build the API URL with query parameters
    var queryString = Object.entries(params).filter(function (_ref2) {
      var _ref3 = (0,_babel_runtime_helpers_slicedToArray__WEBPACK_IMPORTED_MODULE_1__["default"])(_ref2, 2),
        _ = _ref3[0],
        value = _ref3[1];
      return value !== undefined && value !== null && value !== '';
    }).map(function (_ref4) {
      var _ref5 = (0,_babel_runtime_helpers_slicedToArray__WEBPACK_IMPORTED_MODULE_1__["default"])(_ref4, 2),
        key = _ref5[0],
        value = _ref5[1];
      if (Array.isArray(value)) {
        return value.map(function (v) {
          return "".concat(encodeURIComponent(key), "[]=").concat(encodeURIComponent(v));
        }).join('&');
      }
      return "".concat(encodeURIComponent(key), "=").concat(encodeURIComponent(value));
    }).join('&');
    var urlWithParams = "/wp-json/giftflowwp/v1/campaigns".concat(queryString ? "?".concat(queryString) : '');

    // Replace this URL with your actual API endpoint
    fetch(urlWithParams).then(function (res) {
      if (!res.ok) throw new Error('Failed to fetch campaigns');
      return res.json();
    }).then(function (data) {
      if (isMounted) {
        setCampaigns(data);
        setLoading(false);
      }
    })["catch"](function (err) {
      if (isMounted) {
        setError(err.message || 'Unknown error');
        setLoading(false);
      }
    });
    return function () {
      isMounted = false;
    };
  }, [params]);
  return {
    campaigns: campaigns,
    loading: loading,
    error: error,
    updateParams: updateParams
  };
}

/***/ }),

/***/ "./admin/js/hooks/useModal.js":
/*!************************************!*\
  !*** ./admin/js/hooks/useModal.js ***!
  \************************************/
/***/ ((__unused_webpack_module, __webpack_exports__, __webpack_require__) => {

__webpack_require__.r(__webpack_exports__);
/* harmony export */ __webpack_require__.d(__webpack_exports__, {
/* harmony export */   "default": () => (__WEBPACK_DEFAULT_EXPORT__),
/* harmony export */   useModal: () => (/* binding */ useModal)
/* harmony export */ });
/* harmony import */ var _babel_runtime_helpers_slicedToArray__WEBPACK_IMPORTED_MODULE_0__ = __webpack_require__(/*! @babel/runtime/helpers/slicedToArray */ "./node_modules/@babel/runtime/helpers/esm/slicedToArray.js");
/* harmony import */ var react__WEBPACK_IMPORTED_MODULE_1__ = __webpack_require__(/*! react */ "react");
/* harmony import */ var react__WEBPACK_IMPORTED_MODULE_1___default = /*#__PURE__*/__webpack_require__.n(react__WEBPACK_IMPORTED_MODULE_1__);

/**
 * useModal Hook - A comprehensive modal management hook for React components
 * 
 * This hook provides a complete solution for managing modal state, including
 * keyboard navigation, body scroll prevention, and overlay interactions.
 * 
 * @author GiftFlowWP Team
 * @version 1.0.0
 * 
 * @example
 * // Basic usage
 * const modal = useModal();
 * 
 * // With custom options
 * const modal = useModal({
 *   initialOpen: false,
 *   closeOnEscape: true,
 *   closeOnOverlayClick: true,
 *   preventBodyScroll: true
 * });
 * 
 * // Using the modal
 * return (
 *   <>
 *     <button onClick={() => modal.openModal({ title: 'Hello' })}>
 *       Open Modal
 *     </button>
 * 
 *     {modal.isOpen && (
 *       <div className={`giftflowwp-modal ${modal.isOpen ? 'giftflowwp-modal--open' : ''} giftflowwp-modal--medium`}>
 *         <div className="giftflowwp-modal__content" onClick={modal.handleOverlayClick}>
 *           <div className="giftflowwp-modal__header">
 *             <h3 className="giftflowwp-modal__title">Modal Title</h3>
 *             <button className="giftflowwp-modal__close" onClick={modal.closeModal}>
 *               <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" strokeWidth="2">
 *                 <line x1="18" y1="6" x2="6" y2="18"></line>
 *                 <line x1="6" y1="6" x2="18" y2="18"></line>
 *               </svg>
 *             </button>
 *           </div>
 *           
 *           <div className="giftflowwp-modal__body">
 *             Modal content here
 *           </div>
 *           
 *           <div className="giftflowwp-modal__footer">
 *             <div className="giftflowwp-modal__actions">
 *               <button className="giftflowwp-modal__button giftflowwp-modal__button--secondary" onClick={modal.closeModal}>
 *                 Cancel
 *               </button>
 *               <button className="giftflowwp-modal__button giftflowwp-modal__button--primary">
 *                 Confirm
 *               </button>
 *             </div>
 *           </div>
 *         </div>
 *       </div>
 *     )}
 *   </>
 * );
 * 
 * @example
 * // Modal with data
 * const modal = useModal();
 * 
 * const handleEditCampaign = (campaign) => {
 *   modal.openModal({ 
 *     type: 'edit',
 *     campaign: campaign,
 *     title: 'Edit Campaign'
 *   });
 * };
 * 
 * // Access modal data
 * if (modal.isOpen && modal.modalData) {
 *   console.log(modal.modalData.type); // 'edit'
 *   console.log(modal.modalData.campaign); // campaign object
 * }
 * 
 * @example
 * // Custom modal sizes
 * // Small modal (400px)
 * <div className="giftflowwp-modal giftflowwp-modal--small">
 * 
 * // Medium modal (600px) - default
 * <div className="giftflowwp-modal giftflowwp-modal--medium">
 * 
 * // Large modal (800px)
 * <div className="giftflowwp-modal giftflowwp-modal--large">
 * 
 * // Fullscreen modal
 * <div className="giftflowwp-modal giftflowwp-modal--fullscreen">
 * 
 * @example
 * // Modal variants
 * // Modal with loading state
 * <div className="giftflowwp-modal giftflowwp-modal--loading">
 *   <div className="giftflowwp-modal__spinner"></div>
 * </div>
 * 
 * // Modal with image header
 * <div className="giftflowwp-modal giftflowwp-modal--with-image">
 *   <div className="giftflowwp-modal__header">
 *     <img src="image.jpg" alt="Header" className="giftflowwp-modal__image" />
 *     <h3 className="giftflowwp-modal__title">Title</h3>
 *   </div>
 * </div>
 * 
 * // Modal with no padding
 * <div className="giftflowwp-modal giftflowwp-modal--no-padding">
 * 
 * @example
 * // Button variants in modal footer
 * <div className="giftflowwp-modal__actions">
 *   <button className="giftflowwp-modal__button giftflowwp-modal__button--primary">Primary</button>
 *   <button className="giftflowwp-modal__button giftflowwp-modal__button--secondary">Secondary</button>
 *   <button className="giftflowwp-modal__button giftflowwp-modal__button--danger">Danger</button>
 *   <button className="giftflowwp-modal__button giftflowwp-modal__button--ghost">Ghost</button>
 * </div>
 * 
 * @example
 * // Footer alignment options
 * // Default (right aligned)
 * <div className="giftflowwp-modal__footer">
 * 
 * // Center aligned
 * <div className="giftflowwp-modal__footer giftflowwp-modal__footer--center">
 * 
 * // Space between
 * <div className="giftflowwp-modal__footer giftflowwp-modal__footer--space-between">
 * 
 * @example
 * // Overlay variants
 * // Default overlay
 * <div className="giftflowwp-modal">
 * 
 * // Blur overlay
 * <div className="giftflowwp-modal giftflowwp-modal-overlay--blur">
 * 
 * // Dark overlay
 * <div className="giftflowwp-modal giftflowwp-modal-overlay--dark">
 * 
 * // Light overlay
 * <div className="giftflowwp-modal giftflowwp-modal-overlay--light">
 * 
 * @example
 * // Modal positioning
 * // Top positioned
 * <div className="giftflowwp-modal giftflowwp-modal--top">
 * 
 * // Bottom positioned
 * <div className="giftflowwp-modal giftflowwp-modal--bottom">
 * 
 * // Left positioned
 * <div className="giftflowwp-modal giftflowwp-modal--left">
 * 
 * // Right positioned
 * <div className="giftflowwp-modal giftflowwp-modal--right">
 * 
 * @param {Object} options - Configuration options for the modal
 * @param {boolean} [options.initialOpen=false] - Initial open state
 * @param {boolean} [options.closeOnEscape=true] - Close modal on ESC key press
 * @param {boolean} [options.closeOnOverlayClick=true] - Close modal when clicking overlay
 * @param {boolean} [options.preventBodyScroll=true] - Prevent body scroll when modal is open
 * 
 * @returns {Object} Modal state and control functions
 * @returns {boolean} returns.isOpen - Current modal state (open/closed)
 * @returns {*} returns.modalData - Data passed to the modal
 * @returns {Function} returns.openModal - Function to open modal with optional data
 * @returns {Function} returns.closeModal - Function to close modal and clear data
 * @returns {Function} returns.toggleModal - Function to toggle modal state
 * @returns {Function} returns.handleOverlayClick - Click handler for overlay interactions
 * 
 * @since 1.0.0
 */


var useModal = function useModal() {
  var options = arguments.length > 0 && arguments[0] !== undefined ? arguments[0] : {};
  var _options$initialOpen = options.initialOpen,
    initialOpen = _options$initialOpen === void 0 ? false : _options$initialOpen,
    _options$closeOnEscap = options.closeOnEscape,
    closeOnEscape = _options$closeOnEscap === void 0 ? true : _options$closeOnEscap,
    _options$closeOnOverl = options.closeOnOverlayClick,
    closeOnOverlayClick = _options$closeOnOverl === void 0 ? true : _options$closeOnOverl,
    _options$preventBodyS = options.preventBodyScroll,
    preventBodyScroll = _options$preventBodyS === void 0 ? true : _options$preventBodyS;
  var _useState = (0,react__WEBPACK_IMPORTED_MODULE_1__.useState)(initialOpen),
    _useState2 = (0,_babel_runtime_helpers_slicedToArray__WEBPACK_IMPORTED_MODULE_0__["default"])(_useState, 2),
    isOpen = _useState2[0],
    setIsOpen = _useState2[1];
  var _useState3 = (0,react__WEBPACK_IMPORTED_MODULE_1__.useState)(null),
    _useState4 = (0,_babel_runtime_helpers_slicedToArray__WEBPACK_IMPORTED_MODULE_0__["default"])(_useState3, 2),
    modalData = _useState4[0],
    setModalData = _useState4[1];

  // Open modal with optional data
  var openModal = (0,react__WEBPACK_IMPORTED_MODULE_1__.useCallback)(function () {
    var data = arguments.length > 0 && arguments[0] !== undefined ? arguments[0] : null;
    setModalData(data);
    setIsOpen(true);
  }, []);

  // Close modal and clear data
  var closeModal = (0,react__WEBPACK_IMPORTED_MODULE_1__.useCallback)(function () {
    setIsOpen(false);
    setModalData(null);
  }, []);

  // Toggle modal state
  var toggleModal = (0,react__WEBPACK_IMPORTED_MODULE_1__.useCallback)(function () {
    var data = arguments.length > 0 && arguments[0] !== undefined ? arguments[0] : null;
    if (isOpen) {
      closeModal();
    } else {
      openModal(data);
    }
  }, [isOpen, openModal, closeModal]);

  // Handle ESC key press
  (0,react__WEBPACK_IMPORTED_MODULE_1__.useEffect)(function () {
    if (!closeOnEscape || !isOpen) return;
    var handleEscape = function handleEscape(event) {
      if (event.key === 'Escape') {
        closeModal();
      }
    };
    document.addEventListener('keydown', handleEscape);
    return function () {
      return document.removeEventListener('keydown', handleEscape);
    };
  }, [closeOnEscape, isOpen, closeModal]);

  // Prevent body scroll when modal is open
  (0,react__WEBPACK_IMPORTED_MODULE_1__.useEffect)(function () {
    if (!preventBodyScroll) return;
    if (isOpen) {
      // Store original overflow value
      var originalOverflow = document.body.style.overflow;
      document.body.style.overflow = 'hidden';
      return function () {
        document.body.style.overflow = originalOverflow;
      };
    }
  }, [isOpen, preventBodyScroll]);

  // Handle overlay click
  var handleOverlayClick = (0,react__WEBPACK_IMPORTED_MODULE_1__.useCallback)(function (event) {
    if (closeOnOverlayClick && event.target === event.currentTarget) {
      closeModal();
    }
  }, [closeOnOverlayClick, closeModal]);
  return {
    isOpen: isOpen,
    modalData: modalData,
    openModal: openModal,
    closeModal: closeModal,
    toggleModal: toggleModal,
    handleOverlayClick: handleOverlayClick
  };
};
/* harmony default export */ const __WEBPACK_DEFAULT_EXPORT__ = (useModal);

/***/ }),

/***/ "./admin/js/modules/dashboard-view.js":
/*!********************************************!*\
  !*** ./admin/js/modules/dashboard-view.js ***!
  \********************************************/
/***/ ((__unused_webpack_module, __webpack_exports__, __webpack_require__) => {

__webpack_require__.r(__webpack_exports__);
/* harmony import */ var _wordpress_element__WEBPACK_IMPORTED_MODULE_0__ = __webpack_require__(/*! @wordpress/element */ "@wordpress/element");
/* harmony import */ var _wordpress_element__WEBPACK_IMPORTED_MODULE_0___default = /*#__PURE__*/__webpack_require__.n(_wordpress_element__WEBPACK_IMPORTED_MODULE_0__);
/* harmony import */ var _components_DashboardView__WEBPACK_IMPORTED_MODULE_1__ = __webpack_require__(/*! ../components/DashboardView */ "./admin/js/components/DashboardView.jsx");
/* harmony import */ var react_jsx_runtime__WEBPACK_IMPORTED_MODULE_2__ = __webpack_require__(/*! react/jsx-runtime */ "react/jsx-runtime");
/* harmony import */ var react_jsx_runtime__WEBPACK_IMPORTED_MODULE_2___default = /*#__PURE__*/__webpack_require__.n(react_jsx_runtime__WEBPACK_IMPORTED_MODULE_2__);




(function (w) {
  'use strict';

  var DashboardView_Init = function DashboardView_Init() {
    var elem = document.querySelector('#GFWP_DASHBOARD_VIEW_ROOT');
    if (elem) {
      var root = (0,_wordpress_element__WEBPACK_IMPORTED_MODULE_0__.createRoot)(elem);
      root.render(/*#__PURE__*/(0,react_jsx_runtime__WEBPACK_IMPORTED_MODULE_2__.jsx)(_components_DashboardView__WEBPACK_IMPORTED_MODULE_1__["default"], {}));
    }
  };

  // dom ready 
  document.addEventListener('DOMContentLoaded', function () {
    DashboardView_Init();
  });
})(window);

/***/ }),

/***/ "./admin/js/modules/test-send-mail.js":
/*!********************************************!*\
  !*** ./admin/js/modules/test-send-mail.js ***!
  \********************************************/
/***/ ((__unused_webpack_module, __webpack_exports__, __webpack_require__) => {

__webpack_require__.r(__webpack_exports__);
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
(function (w, $) {
  'use strict';

  w.giftflowwp = {};
  w.giftflowwp.testSendMail_Handle = /*#__PURE__*/function () {
    var _ref = (0,_babel_runtime_helpers_asyncToGenerator__WEBPACK_IMPORTED_MODULE_0__["default"])(/*#__PURE__*/_regenerator().m(function _callee(name) {
      var r, res;
      return _regenerator().w(function (_context) {
        while (1) switch (_context.n) {
          case 0:
            r = confirm('Are you sure you want to send the test email?');
            if (r) {
              _context.n = 1;
              break;
            }
            return _context.a(2);
          case 1:
            _context.n = 2;
            return $.ajax({
              url: giftflowwp_admin.ajax_url,
              type: 'POST',
              data: {
                action: 'giftflowwp_test_send_mail',
                name: name,
                nonce: giftflowwp_admin.nonce
              },
              error: function error(xhr, status, _error) {
                console.error(status, _error);
              }
            });
          case 2:
            res = _context.v;
          case 3:
            return _context.a(2);
        }
      }, _callee);
    }));
    return function (_x) {
      return _ref.apply(this, arguments);
    };
  }();
})(window, jQuery);

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

/***/ "./node_modules/@babel/runtime/helpers/esm/defineProperty.js":
/*!*******************************************************************!*\
  !*** ./node_modules/@babel/runtime/helpers/esm/defineProperty.js ***!
  \*******************************************************************/
/***/ ((__unused_webpack___webpack_module__, __webpack_exports__, __webpack_require__) => {

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


/***/ }),

/***/ "./node_modules/lucide-react/dist/esm/Icon.js":
/*!****************************************************!*\
  !*** ./node_modules/lucide-react/dist/esm/Icon.js ***!
  \****************************************************/
/***/ ((__unused_webpack_module, __webpack_exports__, __webpack_require__) => {

__webpack_require__.r(__webpack_exports__);
/* harmony export */ __webpack_require__.d(__webpack_exports__, {
/* harmony export */   "default": () => (/* binding */ Icon)
/* harmony export */ });
/* harmony import */ var react__WEBPACK_IMPORTED_MODULE_0__ = __webpack_require__(/*! react */ "react");
/* harmony import */ var react__WEBPACK_IMPORTED_MODULE_0___default = /*#__PURE__*/__webpack_require__.n(react__WEBPACK_IMPORTED_MODULE_0__);
/* harmony import */ var _defaultAttributes_js__WEBPACK_IMPORTED_MODULE_1__ = __webpack_require__(/*! ./defaultAttributes.js */ "./node_modules/lucide-react/dist/esm/defaultAttributes.js");
/* harmony import */ var _shared_src_utils_js__WEBPACK_IMPORTED_MODULE_2__ = __webpack_require__(/*! ./shared/src/utils.js */ "./node_modules/lucide-react/dist/esm/shared/src/utils.js");
/**
 * @license lucide-react v0.544.0 - ISC
 *
 * This source code is licensed under the ISC license.
 * See the LICENSE file in the root directory of this source tree.
 */





const Icon = (0,react__WEBPACK_IMPORTED_MODULE_0__.forwardRef)(
  ({
    color = "currentColor",
    size = 24,
    strokeWidth = 2,
    absoluteStrokeWidth,
    className = "",
    children,
    iconNode,
    ...rest
  }, ref) => (0,react__WEBPACK_IMPORTED_MODULE_0__.createElement)(
    "svg",
    {
      ref,
      ..._defaultAttributes_js__WEBPACK_IMPORTED_MODULE_1__["default"],
      width: size,
      height: size,
      stroke: color,
      strokeWidth: absoluteStrokeWidth ? Number(strokeWidth) * 24 / Number(size) : strokeWidth,
      className: (0,_shared_src_utils_js__WEBPACK_IMPORTED_MODULE_2__.mergeClasses)("lucide", className),
      ...!children && !(0,_shared_src_utils_js__WEBPACK_IMPORTED_MODULE_2__.hasA11yProp)(rest) && { "aria-hidden": "true" },
      ...rest
    },
    [
      ...iconNode.map(([tag, attrs]) => (0,react__WEBPACK_IMPORTED_MODULE_0__.createElement)(tag, attrs)),
      ...Array.isArray(children) ? children : [children]
    ]
  )
);


//# sourceMappingURL=Icon.js.map


/***/ }),

/***/ "./node_modules/lucide-react/dist/esm/createLucideIcon.js":
/*!****************************************************************!*\
  !*** ./node_modules/lucide-react/dist/esm/createLucideIcon.js ***!
  \****************************************************************/
/***/ ((__unused_webpack_module, __webpack_exports__, __webpack_require__) => {

__webpack_require__.r(__webpack_exports__);
/* harmony export */ __webpack_require__.d(__webpack_exports__, {
/* harmony export */   "default": () => (/* binding */ createLucideIcon)
/* harmony export */ });
/* harmony import */ var react__WEBPACK_IMPORTED_MODULE_0__ = __webpack_require__(/*! react */ "react");
/* harmony import */ var react__WEBPACK_IMPORTED_MODULE_0___default = /*#__PURE__*/__webpack_require__.n(react__WEBPACK_IMPORTED_MODULE_0__);
/* harmony import */ var _shared_src_utils_js__WEBPACK_IMPORTED_MODULE_1__ = __webpack_require__(/*! ./shared/src/utils.js */ "./node_modules/lucide-react/dist/esm/shared/src/utils.js");
/* harmony import */ var _Icon_js__WEBPACK_IMPORTED_MODULE_2__ = __webpack_require__(/*! ./Icon.js */ "./node_modules/lucide-react/dist/esm/Icon.js");
/**
 * @license lucide-react v0.544.0 - ISC
 *
 * This source code is licensed under the ISC license.
 * See the LICENSE file in the root directory of this source tree.
 */





const createLucideIcon = (iconName, iconNode) => {
  const Component = (0,react__WEBPACK_IMPORTED_MODULE_0__.forwardRef)(
    ({ className, ...props }, ref) => (0,react__WEBPACK_IMPORTED_MODULE_0__.createElement)(_Icon_js__WEBPACK_IMPORTED_MODULE_2__["default"], {
      ref,
      iconNode,
      className: (0,_shared_src_utils_js__WEBPACK_IMPORTED_MODULE_1__.mergeClasses)(
        `lucide-${(0,_shared_src_utils_js__WEBPACK_IMPORTED_MODULE_1__.toKebabCase)((0,_shared_src_utils_js__WEBPACK_IMPORTED_MODULE_1__.toPascalCase)(iconName))}`,
        `lucide-${iconName}`,
        className
      ),
      ...props
    })
  );
  Component.displayName = (0,_shared_src_utils_js__WEBPACK_IMPORTED_MODULE_1__.toPascalCase)(iconName);
  return Component;
};


//# sourceMappingURL=createLucideIcon.js.map


/***/ }),

/***/ "./node_modules/lucide-react/dist/esm/defaultAttributes.js":
/*!*****************************************************************!*\
  !*** ./node_modules/lucide-react/dist/esm/defaultAttributes.js ***!
  \*****************************************************************/
/***/ ((__unused_webpack_module, __webpack_exports__, __webpack_require__) => {

__webpack_require__.r(__webpack_exports__);
/* harmony export */ __webpack_require__.d(__webpack_exports__, {
/* harmony export */   "default": () => (/* binding */ defaultAttributes)
/* harmony export */ });
/**
 * @license lucide-react v0.544.0 - ISC
 *
 * This source code is licensed under the ISC license.
 * See the LICENSE file in the root directory of this source tree.
 */

var defaultAttributes = {
  xmlns: "http://www.w3.org/2000/svg",
  width: 24,
  height: 24,
  viewBox: "0 0 24 24",
  fill: "none",
  stroke: "currentColor",
  strokeWidth: 2,
  strokeLinecap: "round",
  strokeLinejoin: "round"
};


//# sourceMappingURL=defaultAttributes.js.map


/***/ }),

/***/ "./node_modules/lucide-react/dist/esm/icons/file-down.js":
/*!***************************************************************!*\
  !*** ./node_modules/lucide-react/dist/esm/icons/file-down.js ***!
  \***************************************************************/
/***/ ((__unused_webpack_module, __webpack_exports__, __webpack_require__) => {

__webpack_require__.r(__webpack_exports__);
/* harmony export */ __webpack_require__.d(__webpack_exports__, {
/* harmony export */   __iconNode: () => (/* binding */ __iconNode),
/* harmony export */   "default": () => (/* binding */ FileDown)
/* harmony export */ });
/* harmony import */ var _createLucideIcon_js__WEBPACK_IMPORTED_MODULE_0__ = __webpack_require__(/*! ../createLucideIcon.js */ "./node_modules/lucide-react/dist/esm/createLucideIcon.js");
/**
 * @license lucide-react v0.544.0 - ISC
 *
 * This source code is licensed under the ISC license.
 * See the LICENSE file in the root directory of this source tree.
 */



const __iconNode = [
  ["path", { d: "M15 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V7Z", key: "1rqfz7" }],
  ["path", { d: "M14 2v4a2 2 0 0 0 2 2h4", key: "tnqrlb" }],
  ["path", { d: "M12 18v-6", key: "17g6i2" }],
  ["path", { d: "m9 15 3 3 3-3", key: "1npd3o" }]
];
const FileDown = (0,_createLucideIcon_js__WEBPACK_IMPORTED_MODULE_0__["default"])("file-down", __iconNode);


//# sourceMappingURL=file-down.js.map


/***/ }),

/***/ "./node_modules/lucide-react/dist/esm/icons/sparkles.js":
/*!**************************************************************!*\
  !*** ./node_modules/lucide-react/dist/esm/icons/sparkles.js ***!
  \**************************************************************/
/***/ ((__unused_webpack_module, __webpack_exports__, __webpack_require__) => {

__webpack_require__.r(__webpack_exports__);
/* harmony export */ __webpack_require__.d(__webpack_exports__, {
/* harmony export */   __iconNode: () => (/* binding */ __iconNode),
/* harmony export */   "default": () => (/* binding */ Sparkles)
/* harmony export */ });
/* harmony import */ var _createLucideIcon_js__WEBPACK_IMPORTED_MODULE_0__ = __webpack_require__(/*! ../createLucideIcon.js */ "./node_modules/lucide-react/dist/esm/createLucideIcon.js");
/**
 * @license lucide-react v0.544.0 - ISC
 *
 * This source code is licensed under the ISC license.
 * See the LICENSE file in the root directory of this source tree.
 */



const __iconNode = [
  [
    "path",
    {
      d: "M11.017 2.814a1 1 0 0 1 1.966 0l1.051 5.558a2 2 0 0 0 1.594 1.594l5.558 1.051a1 1 0 0 1 0 1.966l-5.558 1.051a2 2 0 0 0-1.594 1.594l-1.051 5.558a1 1 0 0 1-1.966 0l-1.051-5.558a2 2 0 0 0-1.594-1.594l-5.558-1.051a1 1 0 0 1 0-1.966l5.558-1.051a2 2 0 0 0 1.594-1.594z",
      key: "1s2grr"
    }
  ],
  ["path", { d: "M20 2v4", key: "1rf3ol" }],
  ["path", { d: "M22 4h-4", key: "gwowj6" }],
  ["circle", { cx: "4", cy: "20", r: "2", key: "6kqj1y" }]
];
const Sparkles = (0,_createLucideIcon_js__WEBPACK_IMPORTED_MODULE_0__["default"])("sparkles", __iconNode);


//# sourceMappingURL=sparkles.js.map


/***/ }),

/***/ "./node_modules/lucide-react/dist/esm/icons/wrench.js":
/*!************************************************************!*\
  !*** ./node_modules/lucide-react/dist/esm/icons/wrench.js ***!
  \************************************************************/
/***/ ((__unused_webpack_module, __webpack_exports__, __webpack_require__) => {

__webpack_require__.r(__webpack_exports__);
/* harmony export */ __webpack_require__.d(__webpack_exports__, {
/* harmony export */   __iconNode: () => (/* binding */ __iconNode),
/* harmony export */   "default": () => (/* binding */ Wrench)
/* harmony export */ });
/* harmony import */ var _createLucideIcon_js__WEBPACK_IMPORTED_MODULE_0__ = __webpack_require__(/*! ../createLucideIcon.js */ "./node_modules/lucide-react/dist/esm/createLucideIcon.js");
/**
 * @license lucide-react v0.544.0 - ISC
 *
 * This source code is licensed under the ISC license.
 * See the LICENSE file in the root directory of this source tree.
 */



const __iconNode = [
  [
    "path",
    {
      d: "M14.7 6.3a1 1 0 0 0 0 1.4l1.6 1.6a1 1 0 0 0 1.4 0l3.106-3.105c.32-.322.863-.22.983.218a6 6 0 0 1-8.259 7.057l-7.91 7.91a1 1 0 0 1-2.999-3l7.91-7.91a6 6 0 0 1 7.057-8.259c.438.12.54.662.219.984z",
      key: "1ngwbx"
    }
  ]
];
const Wrench = (0,_createLucideIcon_js__WEBPACK_IMPORTED_MODULE_0__["default"])("wrench", __iconNode);


//# sourceMappingURL=wrench.js.map


/***/ }),

/***/ "./node_modules/lucide-react/dist/esm/shared/src/utils.js":
/*!****************************************************************!*\
  !*** ./node_modules/lucide-react/dist/esm/shared/src/utils.js ***!
  \****************************************************************/
/***/ ((__unused_webpack_module, __webpack_exports__, __webpack_require__) => {

__webpack_require__.r(__webpack_exports__);
/* harmony export */ __webpack_require__.d(__webpack_exports__, {
/* harmony export */   hasA11yProp: () => (/* binding */ hasA11yProp),
/* harmony export */   mergeClasses: () => (/* binding */ mergeClasses),
/* harmony export */   toCamelCase: () => (/* binding */ toCamelCase),
/* harmony export */   toKebabCase: () => (/* binding */ toKebabCase),
/* harmony export */   toPascalCase: () => (/* binding */ toPascalCase)
/* harmony export */ });
/**
 * @license lucide-react v0.544.0 - ISC
 *
 * This source code is licensed under the ISC license.
 * See the LICENSE file in the root directory of this source tree.
 */

const toKebabCase = (string) => string.replace(/([a-z0-9])([A-Z])/g, "$1-$2").toLowerCase();
const toCamelCase = (string) => string.replace(
  /^([A-Z])|[\s-_]+(\w)/g,
  (match, p1, p2) => p2 ? p2.toUpperCase() : p1.toLowerCase()
);
const toPascalCase = (string) => {
  const camelCase = toCamelCase(string);
  return camelCase.charAt(0).toUpperCase() + camelCase.slice(1);
};
const mergeClasses = (...classes) => classes.filter((className, index, array) => {
  return Boolean(className) && className.trim() !== "" && array.indexOf(className) === index;
}).join(" ").trim();
const hasA11yProp = (props) => {
  for (const prop in props) {
    if (prop.startsWith("aria-") || prop === "role" || prop === "title") {
      return true;
    }
  }
};


//# sourceMappingURL=utils.js.map


/***/ }),

/***/ "@wordpress/element":
/*!*********************************!*\
  !*** external ["wp","element"] ***!
  \*********************************/
/***/ ((module) => {

module.exports = window["wp"]["element"];

/***/ }),

/***/ "react":
/*!************************!*\
  !*** external "React" ***!
  \************************/
/***/ ((module) => {

module.exports = window["React"];

/***/ }),

/***/ "react/jsx-runtime":
/*!**********************************!*\
  !*** external "ReactJSXRuntime" ***!
  \**********************************/
/***/ ((module) => {

module.exports = window["ReactJSXRuntime"];

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
/************************************************************************/
var __webpack_exports__ = {};
// This entry needs to be wrapped in an IIFE because it needs to be isolated against other modules in the chunk.
(() => {
/*!***************************!*\
  !*** ./admin/js/admin.js ***!
  \***************************/
__webpack_require__.r(__webpack_exports__);
/* harmony import */ var _modules_test_send_mail__WEBPACK_IMPORTED_MODULE_0__ = __webpack_require__(/*! ./modules/test-send-mail */ "./admin/js/modules/test-send-mail.js");
/* harmony import */ var _modules_dashboard_view__WEBPACK_IMPORTED_MODULE_1__ = __webpack_require__(/*! ./modules/dashboard-view */ "./admin/js/modules/dashboard-view.js");


})();

/******/ })()
;