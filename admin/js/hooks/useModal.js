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

import { useState, useEffect, useCallback } from 'react';
export const useModal = (options = {}) => {
  const {
    initialOpen = false,
    closeOnEscape = true,
    closeOnOverlayClick = true,
    preventBodyScroll = true
  } = options;

  const [isOpen, setIsOpen] = useState(initialOpen);
  const [modalData, setModalData] = useState(null);

  // Open modal with optional data
  const openModal = useCallback((data = null) => {
    setModalData(data);
    setIsOpen(true);
  }, []);

  // Close modal and clear data
  const closeModal = useCallback(() => {
    setIsOpen(false);
    setModalData(null);
  }, []);

  // Toggle modal state
  const toggleModal = useCallback((data = null) => {
    if (isOpen) {
      closeModal();
    } else {
      openModal(data);
    }
  }, [isOpen, openModal, closeModal]);

  // Handle ESC key press
  useEffect(() => {
    if (!closeOnEscape || !isOpen) return;

    const handleEscape = (event) => {
      if (event.key === 'Escape') {
        closeModal();
      }
    };

    document.addEventListener('keydown', handleEscape);
    return () => document.removeEventListener('keydown', handleEscape);
  }, [closeOnEscape, isOpen, closeModal]);

  // Prevent body scroll when modal is open
  useEffect(() => {
    if (!preventBodyScroll) return;

    if (isOpen) {
      // Store original overflow value
      const originalOverflow = document.body.style.overflow;
      document.body.style.overflow = 'hidden';
      
      return () => {
        document.body.style.overflow = originalOverflow;
      };
    }
  }, [isOpen, preventBodyScroll]);

  // Handle overlay click
  const handleOverlayClick = useCallback((event) => {
    if (closeOnOverlayClick && event.target === event.currentTarget) {
      closeModal();
    }
  }, [closeOnOverlayClick, closeModal]);

  return {
    isOpen,
    modalData,
    openModal,
    closeModal,
    toggleModal,
    handleOverlayClick
  };
};

export default useModal;
