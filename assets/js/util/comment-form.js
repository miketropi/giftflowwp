/**
 * Comment Form JS
 */

((w, $) => {
  'use strict';

  const replyCommentHandle = () => {
    let originalFormPosition = null;
    let originalFormTitle = null;
    let cancelReplyHtml = `<small><a rel="nofollow" id="cancel-comment-reply-link" href="#">Cancel reply</a></small>`

    $(document).on('click', '.gfw-campaign-comments-list .comment-reply-link', function (e) {
      e.preventDefault();
      const commentId = $(this).data('commentid');
      const titleReply = $(this).data('replyto') || 'Leave a Reply';
      const form = $('#respond');
      const parentInput = form.find('input[name="comment_parent"]');

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
      form.find('#reply-title').html(`${titleReply} ${cancelReplyHtml}`);


      // move form
      const commentElement = $(`#comment-${commentId} > .comment-body`);
      if (commentElement.length) {
        commentElement.after(form);
        form.find('textarea').focus();
      }
    });

    // Add cancel reply handler
    $(document).on('click', '#cancel-comment-reply-link', function (e) {
      e.preventDefault();
      const form = $('#respond');
      const parentInput = form.find('input[name="comment_parent"]');

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
  }

  $(() => {
    replyCommentHandle();
  })
})(window, jQuery)