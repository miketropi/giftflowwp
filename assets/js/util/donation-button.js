const { ajax_url, nonce } = giftflowwp_common;

export default async function donationButton_Handle(el) {
  const { campaignId, campaignTitle } = el.dataset;
  
  const ajaxModal = new GiftFlowWPModal({
    ajax: true,
    ajaxUrl: `${ajax_url}?action=giftflowwp_get_campaign_donation_form&campaign_id=${campaignId}&nonce=${nonce}`,
    onLoad: (content, modal) => {
      // console.log('Content loaded:', modal);

      const donationForm = modal.contentElement.querySelector('form.donation-form');
      if(donationForm){
        new window.donationForm_Class(donationForm, {
          paymentMethodSelected: 'stripe',
        });
      } 
    },
    className: 'modal-transparent-wrapper',
    width: '800px',
  });

  ajaxModal.open();
}