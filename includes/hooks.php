<?php 
/**
 * Hooks file
 */

add_action( 'giftflowwp_donation_form_after_payment_method', 'giftflowwp_donation_form_thank_you_section_html', 20 );
add_action( 'giftflowwp_donation_form_after_payment_method', 'giftflowwp_donation_form_error_section_html', 22 );