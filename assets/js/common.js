/**
 * GiftFlow Common JS
 */
import './util/comment-form.js';
import GiftFlowModal from './util/modal.js';
import './util/campaign-single.js'; 
import './util/share-block.js';
import './util/campaign-images-gallery.js';

import { replaceContentBySelector, initClickToCopyByClass } from './util/helpers.js';
import donationButton_Handle from './util/donation-button.js';
import { createGiftflowLightbox } from './util/gfw-image-lightbox.js';

// Donation form — defines window.donationForm_Class
import './forms.js';

((w, $) => { 
  "use strict"
  const { ajax_url, nonce } = giftflow_common;

  w.GiftFlowModal = GiftFlowModal;

  w.giftflow = w.giftflow || {}
  const gfw = w.giftflow 

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
        action: 'giftflow_get_pagination_donation_list_html',
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

  gfw.copyShareUrl = function (btn) {
    const url = btn.dataset.url;
    if (!url) return;

    navigator.clipboard.writeText(url).then(() => {
      const copied = btn.parentElement.querySelector('.giftflow-share__copied');
      if (copied) {
        copied.hidden = false;
        setTimeout(() => { copied.hidden = true; }, 2000);
      }
    }).catch(() => {
      const input = document.createElement('input');
      input.value = url;
      document.body.appendChild(input);
      input.select();
      document.execCommand('copy');
      document.body.removeChild(input);
      const copied = btn.parentElement.querySelector('.giftflow-share__copied');
      if (copied) {
        copied.hidden = false;
        setTimeout(() => { copied.hidden = true; }, 2000);
      }
    });
  };

  // lightbox (vanilla overlay — avoids PhotoSwipe globals / `.pswp` clashes with other plugins)
  gfw.lightbox_initialize = function() {
    const galleryElements = document.querySelector('.giftflow-campaign-single-images:not(.giftflow-campaign-single-images--placeholder)');

    if (!galleryElements) {
      return;
    }

    const openBtn = galleryElements.querySelector('.giftflow-campaign-single-images-lightbox-open-btn');
    if (!openBtn) {
      return;
    }

    const sourceData = Array.from(galleryElements.querySelectorAll('.giftflow-campaign-single-images-image')).map((element) => {
      return {
        src: element.dataset.pswpSrc,
        width: element.dataset.pswpWidth,
        height: element.dataset.pswpHeight,
      };
    }).filter((item) => item.src);

    const lightbox = createGiftflowLightbox({ items: sourceData });

    openBtn.addEventListener('click', function () {
      lightbox.open(0);
    });
  }

  // dom loaded
  document.addEventListener('DOMContentLoaded', function() {
    gfw.lightbox_initialize();
    initClickToCopyByClass({ className: 'gfw-click-to-copy' });
  });

})(window, jQuery)