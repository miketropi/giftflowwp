/**
 * GiftFlow Common JS
 */
import { replaceContentBySelector } from './util/helpers.js';

((w, $) => {
  "use strict"
  const { ajax_url, nonce } = giftflowwp_common;

  w.giftflowwp = w.giftflowwp || {}
  const gfw = w.giftflowwp

  // load donation list
  gfw.loadDonationListPaginationTemplate_Handle = async function (elem) {
    const { campaign, page } = elem.dataset;
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

})(window, jQuery)