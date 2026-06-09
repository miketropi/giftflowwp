const { ajax_url, nonce } = giftflow_common;

export default async function donationButton_Handle(el) {
  const { campaignId, campaignTitle, presetAmount } = el.dataset;
  const modalWidth = window?._giftflow_common?.modalWidth || '720px';

  let url = `${ajax_url}?action=giftflow_get_campaign_donation_form&campaign_id=${campaignId}&nonce=${nonce}`;
  if (presetAmount && parseFloat(presetAmount) > 0) {
    url += `&amount=${presetAmount}`;
  }

  try {
    const response = await fetch(url);
    
    if (!response.ok) {
      alert(giftflow_common?.ajax_error || 'Failed to load donation form. Please refresh the page and try again.');
      return;
    }
    
    const content = await response.text();
    
    // Check for JSON error response from wp_send_json_error
    if (content.startsWith('{"success":false')) {
      try {
        const err = JSON.parse(content);
        alert(giftflow_common?.ajax_error || (err.data || 'An error occurred. Please refresh the page and try again.'));
      } catch(e) {
        alert('An error occurred. Please refresh the page and try again.');
      }
      return;
    }

    const ajaxModal = new GiftFlowModal({
      content: content,
      onLoad: (_, modal) => {
        const donationForm = modal.contentElement.querySelector('form.donation-form');
        if(donationForm){
          new window.donationForm_Class(donationForm, {
            paymentMethodSelected: 'stripe',
          });
        } 
      },
      className: 'modal-transparent-wrapper',
      width: modalWidth,
      onClose: (_) => {
        _.destroy();
      }
    });

    ajaxModal.open();
  } catch (error) {
    console.error('Donation form error:', error);
    alert(giftflow_common?.ajax_error || 'Failed to load donation form. Please try again.');
  }
}