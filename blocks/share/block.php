<?php

function giftflowwp_share_block() {
    register_block_type(
        'giftflowwp/share',
        array(
            'api_version' => 3,
            'render_callback' => 'giftflowwp_share_block_render',
            'attributes' => array(
                'title' => array(
                    'type' => 'string',
                    'default' => 'Share this',
                ),
                'customUrl' => array(
                    'type' => 'string',
                    'default' => '',
                ),
                'showSocials' => array(
                    'type' => 'boolean',
                    'default' => true,
                ),
                'showEmail' => array(
                    'type' => 'boolean',
                    'default' => true,
                ),
                'showCopyUrl' => array(
                    'type' => 'boolean',
                    'default' => true,
                ),
            ),
        )
    );
}

add_action('init', 'giftflowwp_share_block');

function giftflowwp_share_block_render($attributes, $content, $block) {
    $title = $attributes['title'] ?? esc_html__('Share this', 'giftflowwp');
    $show_socials = $attributes['showSocials'] ?? true;
    $show_email = $attributes['showEmail'] ?? true;
    $show_copy_url = $attributes['showCopyUrl'] ?? true;
    $custom_url = $attributes['customUrl'] ?? '';

    // Get the URL to share - if customUrl is empty, get current post/page URL
    if (!empty($custom_url)) {
        $share_url = $custom_url;
    } else {
        // Get current post/page URL
        if (is_singular()) {
            $share_url = get_permalink();
        } elseif (is_home() || is_front_page()) {
            $share_url = home_url('/');
        } elseif (is_category()) {
            $share_url = get_category_link(get_queried_object_id());
        } elseif (is_tag()) {
            $share_url = get_tag_link(get_queried_object_id());
        } elseif (is_author()) {
            $share_url = get_author_posts_url(get_queried_object_id());
        } elseif (is_date()) {
            $share_url = get_pagenum_link();
        } elseif (is_search()) {
            $share_url = home_url('/?s=' . urlencode(get_search_query()));
        } else {
            $share_url = home_url(add_query_arg(array()));
        }
    }

    // Get title and description for sharing
    if (is_singular()) {
        $share_title = get_the_title();
        $share_description = get_the_excerpt() ?: get_bloginfo('description');
    } elseif (is_home() || is_front_page()) {
        $share_title = get_bloginfo('name');
        $share_description = get_bloginfo('description');
    } elseif (is_category()) {
        $category = get_queried_object();
        $share_title = single_cat_title('', false);
        $share_description = category_description() ?: get_bloginfo('description');
    } elseif (is_tag()) {
        $share_title = single_tag_title('', false);
        $share_description = tag_description() ?: get_bloginfo('description');
    } elseif (is_author()) {
        $author = get_queried_object();
        $share_title = get_the_author_meta('display_name', $author->ID);
        $share_description = get_the_author_meta('description', $author->ID) ?: get_bloginfo('description');
    } elseif (is_date()) {
        $share_title = get_the_date();
        $share_description = get_bloginfo('description');
    } elseif (is_search()) {
        $share_title = sprintf(__('Search results for: %s', 'giftflowwp'), get_search_query());
        $share_description = get_bloginfo('description');
    } else {
        $share_title = get_bloginfo('name');
        $share_description = get_bloginfo('description');
    }

    // Default social platforms (Facebook, X/Twitter, LinkedIn)
    $social_platforms = array('facebook', 'twitter', 'linkedin');

    ob_start();
    ?>
    <div class="giftflowwp-share">
        <?php if (!empty($title)): ?>
            <strong class="giftflowwp-share__title"><?php echo esc_html($title); ?></strong>
        <?php endif; ?>

        <div class="giftflowwp-share__buttons">
            <?php if ($show_socials && !empty($social_platforms)): ?>
                <?php foreach ($social_platforms as $platform): ?>
                    <?php echo giftflowwp_render_social_share_button($platform, $share_url, $share_title, $share_description); ?>
                <?php endforeach; ?>
            <?php endif; ?>

            <?php if ($show_email): ?>
                <a href="<?php echo esc_url(giftflowwp_get_email_share_url($share_url, $share_title, $share_description)); ?>" 
                   class="giftflowwp-share__button giftflowwp-share__button--email"
                   title="<?php esc_attr_e('Share via Email', 'giftflowwp'); ?>"
                   target="_blank"
                   rel="noopener noreferrer">
                    <!-- <span class="giftflowwp-share__icon">
                        <?php // echo giftflowwp_svg_icon('mail'); ?>
                    </span> -->
                    <span class="giftflowwp-share__text"><?php esc_html_e('Email', 'giftflowwp'); ?></span>
                </a>
            <?php endif; ?>

            <?php if ($show_copy_url): ?>
                <a href="#"
                    class="giftflowwp-share__button giftflowwp-share__button--copy-url"
                    data-url="<?php echo esc_attr($share_url); ?>"
                    title="<?php esc_attr_e('Copy URL to clipboard', 'giftflowwp'); ?>"
                    onclick="giftflowwpCopyUrlToClipboard('<?php echo esc_js($share_url); ?>', this)">
                    <!-- <span class="giftflowwp-share__icon">
                        <?php // echo giftflowwp_svg_icon('link'); ?>
                    </span> -->
                    <span class="giftflowwp-share__text"><?php esc_html_e('Copy Link', 'giftflowwp'); ?></span>
                </a>
            <?php endif; ?>
        </div>

        <?php if ($show_copy_url): ?>
            <div class="giftflowwp-share__copy-feedback" style="display: none;">
                <span class="giftflowwp-share__copy-message">
                    <?php echo giftflowwp_svg_icon('checkmark-circle'); ?>
                    <?php esc_html_e('URL copied to clipboard!', 'giftflowwp'); ?>
                </span>
            </div>
        <?php endif; ?>
    </div>

    <script>
    function giftflowwpCopyUrlToClipboard(url, button) {
        if (navigator.clipboard && window.isSecureContext) {
            // Use modern clipboard API
            navigator.clipboard.writeText(url).then(function() {
                giftflowwpShowCopyFeedback(button);
            }).catch(function(err) {
                console.error('Failed to copy: ', err);
                giftflowwpFallbackCopy(url, button);
            });
        } else {
            // Fallback for older browsers
            giftflowwpFallbackCopy(url, button);
        }
    }

    function giftflowwpFallbackCopy(url, button) {
        const textArea = document.createElement('textarea');
        textArea.value = url;
        textArea.style.position = 'fixed';
        textArea.style.left = '-999999px';
        textArea.style.top = '-999999px';
        document.body.appendChild(textArea);
        textArea.focus();
        textArea.select();
        
        try {
            document.execCommand('copy');
            giftflowwpShowCopyFeedback(button);
        } catch (err) {
            console.error('Fallback copy failed: ', err);
        }
        
        document.body.removeChild(textArea);
    }

    function giftflowwpShowCopyFeedback(button) {
        const container = button.closest('.giftflowwp-share');
        const feedback = container.querySelector('.giftflowwp-share__copy-feedback');
        
        if (feedback) {
            feedback.style.display = 'block';
            setTimeout(function() {
                feedback.style.display = 'none';
            }, 2000);
        }
    }
    </script>
    <?php
    return ob_get_clean();
}

function giftflowwp_render_social_share_button($platform, $url, $title, $description) {
    $share_urls = array(
        'facebook' => 'https://www.facebook.com/sharer/sharer.php?u=' . urlencode($url),
        'twitter' => 'https://twitter.com/intent/tweet?url=' . urlencode($url) . '&text=' . urlencode($title),
        'linkedin' => 'https://www.linkedin.com/sharing/share-offsite/?url=' . urlencode($url),
    );

    $platform_names = array(
        'facebook' => __('Facebook', 'giftflowwp'),
        'twitter' => __('X', 'giftflowwp'),
        'linkedin' => __('LinkedIn', 'giftflowwp'),
    );

    $platform_icons = array(
        'facebook' => 'facebook',
        'twitter' => 'twitter',
        'linkedin' => 'linkedin',
    );

    if (!isset($share_urls[$platform])) {
        return '';
    }

    $share_url = $share_urls[$platform];
    $platform_name = $platform_names[$platform];
    $icon_name = $platform_icons[$platform];

    ob_start();
    ?>
    <a href="<?php echo esc_url($share_url); ?>" 
       class="giftflowwp-share__button giftflowwp-share__button--<?php echo esc_attr($platform); ?>"
       title="<?php printf(esc_attr__('Share on %s', 'giftflowwp'), esc_attr($platform_name)); ?>"
       target="_blank"
       rel="noopener noreferrer">
        <!-- <span class="giftflowwp-share__icon">
            <?php // echo giftflowwp_svg_icon($icon_name); ?>
        </span> -->
        <span class="giftflowwp-share__text"><?php echo esc_html($platform_name); ?></span>
    </a>
    <?php
    return ob_get_clean();
}

function giftflowwp_get_email_share_url($url, $title, $description) {
    $subject = sprintf(__('Check out: %s', 'giftflowwp'), $title);
    $body = sprintf(__("I thought you might be interested in this:\n\n%s\n\n%s\n\n%s", 'giftflowwp'), $title, $description, $url);
    
    return 'mailto:?subject=' . urlencode($subject) . '&body=' . urlencode($body);
}
