/**
 * GiftFlow Common JS
 */
import './util/comment-form.js';
import './util/modal.js';

import { replaceContentBySelector } from './util/helpers.js';
import donationButton_Handle from './util/donation-button.js';

((w, $) => {
  "use strict"
  const { ajax_url, nonce } = giftflowwp_common;

  w.giftflowwp = w.giftflowwp || {}
  const gfw = w.giftflowwp

  // load donation list
  gfw.loadDonationListPaginationTemplate_Handle = async function (elem) {
    const { campaign, page } = elem.dataset;

    if (!campaign || !page) {
      console.error('Missing campaign or page data attributes');
      return;
    }

    const container = elem.closest(`.__donations-list-by-campaign-${campaign}`);

    if(!container) {
      console.error('Container element not found');
      return;
    }

    container.classList.add('gfw-loading-spinner');

    const res = await $.ajax({
      url: ajax_url,
      type: 'POST',
      data: {
        action: 'giftflowwp_get_pagination_donation_list_html',
        campaign,
        page,
        nonce,
      },
    })

    container.classList.remove('gfw-loading-spinner');

    // res successful
    if (res.success) {
      const { __html, __replace_content_selector } = res.data;
      if(__replace_content_selector) {
        replaceContentBySelector(__replace_content_selector, __html);
      }
    } else {
      console.error('Error loading donation list pagination template');
    }
  }

  gfw.donationButton_Handle = donationButton_Handle;

})(window, jQuery)