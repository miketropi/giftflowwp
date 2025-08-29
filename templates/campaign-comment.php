<?php 
if ( ! defined( 'ABSPATH' ) ) {
  exit; // Exit if accessed directly.
}

$paged    = get_query_var('cpage') ? intval(get_query_var('cpage')) : 1;
$per_page = get_option('comments_per_page');

$args = [
  'post_id' => $post_id,
  'status'  => 'approve',
  'number'  => $per_page,
  'offset'  => ($paged - 1) * $per_page,
  'orderby' => 'comment_date_gmt',
  'order'   => 'DESC',
];

$comments = get_comments($args);

?>
<div class="gfw-campaign-comments">
  <?php 
  if ($comments) {
    $total_comments = get_comments([
      'post_id' => get_the_ID(),
      'status'  => 'approve',
      'count'   => true,
    ]);
    $total_pages = ceil($total_comments / $per_page);
    
    if ($total_pages > 1) {
      echo '<nav class="gfw-campaign-comments-pagination">';
      echo paginate_links([
          'base'      => add_query_arg('cpage', '%#%') . '#comment',
          'format'    => '',
          'current'   => $paged,
          'total'     => $total_pages,
          'prev_text' => __('Previous', 'giftflowwp'),
          'next_text' => __('Next', 'giftflowwp'),
      ]);
      echo '</nav> <!-- .gfw-campaign-comments-pagination -->';
    }

    echo '<ol class="gfw-campaign-comments-list">';
    wp_list_comments([
      'short_ping' => true,
    ], $comments);
    echo '</ol> <!-- .gfw-campaign-comments-list -->';

    if ($total_pages > 1) {
      echo '<nav class="gfw-campaign-comments-pagination">';
      echo paginate_links([
          'base'      => add_query_arg('cpage', '%#%') . '#comment',
          'format'    => '',
          'current'   => $paged,
          'total'     => $total_pages,
          'prev_text' => __('Previous', 'giftflowwp'),
          'next_text' => __('Next', 'giftflowwp'),
      ]);
      echo '</nav> <!-- .gfw-campaign-comments-pagination -->';
    }
  } else {

    // check post comment is closed
    $comment_status = comments_open(get_the_ID());
   
    if($comment_status !== true) {
      // return message that comment is closed
      ?>
      <div class="gfw-no-comments">
        <p>
          <?php esc_html_e('Comments are closed for this campaign.', 'giftflowwp'); ?>
        </p>
      </div>
      <?php
    } else {
    ?>
    <div class="gfw-no-comments">
      <p>
        <?php esc_html_e('No comments yet. Be the first to share your thoughts!, Your feedback and support mean a lot to this campaign.', 'giftflowwp'); ?>
      </p>
    </div>
    <?php
    }
  }
  ?>

  <?php comment_form([
    'class_container' => 'gfw-campaign-comments-form',
  ]); ?>
</div> <!-- .gfw-campaign-comments -->