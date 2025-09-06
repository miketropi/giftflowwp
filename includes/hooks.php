<?php 
/**
 * Hooks file
 */

add_action( 'giftflowwp_donation_form_after_payment_method', 'giftflowwp_donation_form_thank_you_section_html', 20 );
add_action( 'giftflowwp_donation_form_after_payment_method', 'giftflowwp_donation_form_error_section_html', 22 );

// new user 
add_action('giftflowwp_donation_after_payment_processed', 'giftflowwp_auto_create_user_on_donation', 10, 2);