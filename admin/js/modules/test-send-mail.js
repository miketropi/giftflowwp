((w, $) => {
  'use strict';

  w.giftflowwp = {};
  w.giftflowwp.testSendMail_Handle = async (name) => {
    const res = await $.ajax({
      url: giftflowwp_admin.ajax_url,
      type: 'POST',
      data: {
        action: 'giftflowwp_test_send_mail',
        name,
        nonce: giftflowwp_admin.nonce,
      },
      error: (xhr, status, error) => {
        console.error(status, error);
      }
    })

    console.log(res);
  }

})(window, jQuery)