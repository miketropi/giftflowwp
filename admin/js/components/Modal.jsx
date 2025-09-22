import React from 'react';
import useModal from '../hooks/useModal';

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
const Modal = ({
  isOpen = false,
  onClose,
  title,
  children,
  size = 'medium',
  variant = 'default',
  overlay = 'default',
  position = 'center',
  footer,
  actions = [],
  showCloseButton = true,
  closeOnOverlayClick = true,
  closeOnEscape = true,
  preventBodyScroll = true,
  className = '',
  modalData,
  imageSrc,
  imageAlt = 'Modal image'
}) => {
  const modal = useModal({
    initialOpen: isOpen,
    closeOnEscape,
    closeOnOverlayClick,
    preventBodyScroll
  });

  // Sync external isOpen state with internal hook state
  React.useEffect(() => {
    if (isOpen) {
      modal.openModal(modalData);
    } else {
      modal.closeModal();
    }
  }, [isOpen, modalData]);

  // Handle close
  const handleClose = () => {
    modal.closeModal();
    if (onClose) {
      onClose();
    }
  };

  // Handle overlay click
  const handleOverlayClick = (event) => {
    if (closeOnOverlayClick) {
      modal.handleOverlayClick(event);
      if (onClose) {
        onClose();
      }
    }
  };

  // Build CSS classes
  const modalClasses = [
    'giftflowwp-modal',
    isOpen ? 'giftflowwp-modal--open' : '',
    size !== 'medium' ? `giftflowwp-modal--${size}` : '',
    variant !== 'default' ? `giftflowwp-modal--${variant}` : '',
    overlay !== 'default' ? `giftflowwp-modal-overlay--${overlay}` : '',
    position !== 'center' ? `giftflowwp-modal--${position}` : '',
    className
  ].filter(Boolean).join(' ');

  // Render close button
  const renderCloseButton = () => {
    if (!showCloseButton) return null;

    return (
      <button 
        className="giftflowwp-modal__close" 
        onClick={handleClose}
        aria-label="Close modal"
      >
        <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" strokeWidth="2">
          <line x1="18" y1="6" x2="6" y2="18"></line>
          <line x1="6" y1="6" x2="18" y2="18"></line>
        </svg>
      </button>
    );
  };

  // Render header
  const renderHeader = () => {
    if (!title && !showCloseButton && variant !== 'with-image') return null;

    return (
      <div className="giftflowwp-modal__header">
        {variant === 'with-image' && imageSrc && (
          <img 
            src={imageSrc} 
            alt={imageAlt} 
            className="giftflowwp-modal__image"
          />
        )}
        {title && (
          <h3 className="giftflowwp-modal__title">
            {title}
          </h3>
        )}
        {renderCloseButton()}
      </div>
    );
  };

  // Render footer
  const renderFooter = () => {
    if (!footer && actions.length === 0) return null;

    return (
      <div className="giftflowwp-modal__footer">
        {footer || (
          <div className="giftflowwp-modal__actions">
            {actions.map((action, index) => (
              <button
                key={index}
                className={`giftflowwp-modal__button giftflowwp-modal__button--${action.variant || 'secondary'}`}
                onClick={action.onClick}
                disabled={action.disabled}
                type={action.type || 'button'}
              >
                {action.icon && <span className="giftflowwp-modal__button-icon">{action.icon}</span>}
                {action.text}
              </button>
            ))}
          </div>
        )}
      </div>
    );
  };

  // Don't render if not open
  if (!isOpen) return null;

  return (
    <div 
      className={modalClasses} 
      // onClick={handleOverlayClick} 
    >
      <div className="giftflowwp-modal__content" onClick={(e) => e.stopPropagation()}>
        {renderHeader()}
        
        <div className="giftflowwp-modal__body">
          {variant === 'loading' && (
            <div className="giftflowwp-modal__spinner"></div>
          )}
          {children}
        </div>
        
        {renderFooter()}
      </div>
    </div>
  );
};

export default Modal;
