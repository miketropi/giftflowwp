<?php 
if ( ! defined( 'ABSPATH' ) ) {
  exit; // Exit if accessed directly.
}

// =============================================================================
// INITIALIZATION & VALIDATION
// =============================================================================

$current_user = $current_user ?? null; // wp user object
$donor = $donor ?? null; // donor information

// Return if required data is missing
if ( !$current_user || !$donor ) {
  return;
}

// =============================================================================
// FORM PROCESSING FUNCTIONS
// =============================================================================

/**
 * Process account information form submission
 */
function giftflowwp_process_account_form( $donor ) {
  $form_data = giftflowwp_sanitize_account_form_data();
  $errors = giftflowwp_validate_account_form_data( $form_data, $donor );
  
  if ( empty( $errors ) ) {
    $result = giftflowwp_update_donor_account( $donor['user_id'], $form_data, $donor['donor_id'] );
    if ( $result['success'] ) {
      return array( 'success' => true, 'message' => __( 'Your account information has been updated successfully.', 'giftflowwp' ) );
    } else {
      return array( 'success' => false, 'errors' => array( $result['message'] ) );
    }
  }
  
  return array( 'success' => false, 'errors' => $errors );
}

/**
 * Process password change form submission
 */
function giftflowwp_process_password_form( $current_user ) {
  $form_data = giftflowwp_sanitize_password_form_data();
  $errors = giftflowwp_validate_password_form_data( $form_data, $current_user );
  
  if ( empty( $errors ) ) {
    $result = giftflowwp_update_user_password( $current_user->ID, $form_data['new_password'] );
    if ( $result ) {
      
      // add action after update user password
      do_action( 'giftflowwp_after_update_user_password_success', $current_user->ID, $form_data['new_password'] );

      return array( 'success' => true, 'message' => __( 'Your password has been updated successfully.', 'giftflowwp' ) );
    } else {
      return array( 'success' => false, 'errors' => array( __( 'Failed to update password. Please try again.', 'giftflowwp' ) ) );
    }
  }
  
  return array( 'success' => false, 'errors' => $errors );
}

/**
 * Sanitize account form data
 */
function giftflowwp_sanitize_account_form_data() {
  return array(
    'first_name' => sanitize_text_field( $_POST['first_name'] ?? '' ),
    'last_name' => sanitize_text_field( $_POST['last_name'] ?? '' ),
    'email' => sanitize_email( $_POST['email'] ?? '' ),
    'phone' => sanitize_text_field( $_POST['phone'] ?? '' ),
    'address' => sanitize_textarea_field( $_POST['address'] ?? '' ),
    'city' => sanitize_text_field( $_POST['city'] ?? '' ),
    'state' => sanitize_text_field( $_POST['state'] ?? '' ),
    'postal_code' => sanitize_text_field( $_POST['postal_code'] ?? '' ),
    'country' => sanitize_text_field( $_POST['country'] ?? '' ),
  );
}

/**
 * Sanitize password form data
 */
function giftflowwp_sanitize_password_form_data() {
  return array(
    'current_password' => $_POST['giftflowwp_current_password'] ?? '',
    'new_password' => $_POST['giftflowwp_new_password'] ?? '',
    'confirm_password' => $_POST['giftflowwp_confirm_password'] ?? '',
  );
}

/**
 * Validate account form data
 */
function giftflowwp_validate_account_form_data( $data, $donor ) {
  $errors = array();
  
  // Required field validation
  if ( empty( $data['first_name'] ) ) {
    $errors[] = esc_html__( 'First name is required.', 'giftflowwp' );
  }
  
  if ( empty( $data['last_name'] ) ) {
    $errors[] = esc_html__( 'Last name is required.', 'giftflowwp' );
  }
  
  // if ( empty( $data['email'] ) || ! is_email( $data['email'] ) ) {
  //   $errors[] = esc_html__( 'Valid email address is required.', 'giftflowwp' );
  // }
  
  // // Email uniqueness validation
  // if ( $data['email'] !== $donor['email'] ) {
  //   $existing_user = get_user_by( 'email', $data['email'] );
  //   if ( $existing_user && $existing_user->ID !== $donor['user_id'] ) {
  //     $errors[] = esc_html__( 'This email address is already in use.', 'giftflowwp' );
  //   }
  // }
  
  return $errors;
}

/**
 * Validate password form data
 */
function giftflowwp_validate_password_form_data( $data, $current_user ) {
  $errors = array();
  
  // Current password validation
  if ( empty( $data['current_password'] ) || ! wp_check_password( $data['current_password'], $current_user->user_pass, $current_user->ID ) ) {
    $errors[] = esc_html__( 'Your current password is incorrect.', 'giftflowwp' );
  }
  
  // New password validation
  if ( empty( $data['new_password'] ) ) {
    $errors[] = esc_html__( 'Please enter a new password.', 'giftflowwp' );
  } elseif ( $data['new_password'] !== $data['confirm_password'] ) {
    $errors[] = esc_html__( 'New password and confirmation do not match.', 'giftflowwp' );
  } elseif ( strlen( $data['new_password'] ) < 6 ) {
    $errors[] = esc_html__( 'New password must be at least 6 characters.', 'giftflowwp' );
  }
  
  return $errors;
}

/**
 * Update donor account information
 */
function giftflowwp_update_donor_account( $user_id, $data, $donor_id ) {
  // validate donor id
  if ( ! $donor_id ) {
    return array( 'success' => false, 'message' => esc_html__( 'Donor ID is required.', 'giftflowwp' ) );
  }

  // Update WordPress user data
  $user_data = array(
    'ID' => $user_id,
    'first_name' => $data['first_name'],
    'last_name' => $data['last_name'],
    // 'user_email' => $data['email'], // do not update email
    'display_name' => $data['first_name'] . ' ' . $data['last_name'],
  );
  
  $result = wp_update_user( $user_data );
  
  if ( is_wp_error( $result ) ) {
    return array( 'success' => false, 'message' => esc_html__( 'There was an error updating your account. Please try again.', 'giftflowwp' ) );
  }
  
  // Update donor meta
  $meta_fields = array( 'first_name', 'last_name', 'phone', 'address', 'city', 'state', 'postal_code', 'country' );
  foreach ( $meta_fields as $field ) {
    update_post_meta( $donor_id, '_' . $field, $data[$field] );
  }
  
  return array( 'success' => true );
}

/**
 * Update user password
 */
function giftflowwp_update_user_password( $user_id, $new_password ) {
  $result = wp_update_user( array(
    'ID' => $user_id,
    'user_pass' => $new_password,
  ) );
  
  return ! is_wp_error( $result );
}

/**
 * Render page header
 */
function giftflowwp_render_page_header() {
  ?>
  <h2 class="gfw-donor-account__title"><?php esc_html_e('My Account', 'giftflowwp'); ?></h2>
  <p>
    <?php esc_html_e('Keep your donor account information accurate and up to date using the form below. By maintaining your current contact details, you\'ll ensure you never miss important updates, donation receipts, or opportunities to support causes you care about.', 'giftflowwp'); ?>
  </p>
  <p>
    <?php esc_html_e('🔒 Your privacy is important to us. Your information is securely stored and will never be shared without your consent.', 'giftflowwp'); ?>
  </p>
  <br/>
  <?php
}

/**
 * Render success/error messages
 */
function giftflowwp_render_messages( $account_result, $password_result ) {
  // Account form messages
  if ( $account_result ) {
    if ( $account_result['success'] ) {
      giftflowwp_render_message( $account_result['message'], 'success' );
    } else {
      giftflowwp_render_message( $account_result['errors'], 'error' );
    }
  }
  
  // Password form messages
  if ( $password_result ) {
    if ( $password_result['success'] ) {
      giftflowwp_render_message( $password_result['message'], 'success' );
    } else {
      giftflowwp_render_message( $password_result['errors'], 'error' );
    }
  }
}

/**
 * Render a single message
 */
function giftflowwp_render_message( $message, $type ) {
  $class = 'gfw-message gfw-message--' . $type;
  
  if ( is_array( $message ) ) {
    echo '<div class="' . esc_attr( $class ) . '"><ul>';
    foreach ( $message as $msg ) {
      echo '<li>' . esc_html( $msg ) . '</li>';
    }
    echo '</ul></div>';
  } else {
    echo '<div class="' . esc_attr( $class ) . '">' . esc_html( $message ) . '</div>';
  }
}

/**
 * Render form field
 */
function giftflowwp_render_form_field( $field_config ) {
  $field_id = $field_config['id'];
  $field_name = $field_config['name'];
  $field_type = $field_config['type'] ?? 'text';
  $field_value = $field_config['value'] ?? '';
  $field_label = $field_config['label'];
  $field_required = $field_config['required'] ?? false;
  $field_readonly = $field_config['readonly'] ?? false;
  $field_rows = $field_config['rows'] ?? 3;
  $field_attributes = $field_config['attributes'] ?? array();
  $field_full_width = $field_config['full_width'] ?? false;
  
  $attributes_string = '';
  foreach ( $field_attributes as $attr => $value ) {
    $attributes_string .= ' ' . esc_attr( $attr ) . '="' . esc_attr( $value ) . '"';
  }
  
  $required_span = $field_required ? ' <span class="required">*</span>' : '';
  $readonly_attr = $field_readonly ? ' readonly' : '';
  $required_attr = $field_required ? ' required' : '';
  
  ?>
  <div class="gfw-form-field<?php echo $field_full_width ? ' gfw-form-field--full' : ''; ?>">
    <label for="<?php echo esc_attr( $field_id ); ?>">
      <?php echo esc_html( $field_label ); ?><?php echo $required_span; ?>
    </label>
    
    <?php if ( $field_type === 'textarea' ): ?>
      <textarea 
        id="<?php echo esc_attr( $field_id ); ?>" 
        name="<?php echo esc_attr( $field_name ); ?>" 
        rows="<?php echo esc_attr( $field_config['rows'] ?? 3 ); ?>"
        <?php echo $readonly_attr . $required_attr . $attributes_string; ?>
      ><?php echo esc_textarea( $field_value ); ?></textarea>
    <?php else: ?>
      <input 
        type="<?php echo esc_attr( $field_type ); ?>" 
        id="<?php echo esc_attr( $field_id ); ?>" 
        name="<?php echo esc_attr( $field_name ); ?>" 
        value="<?php echo esc_attr( $field_value ); ?>"
        <?php echo $readonly_attr . $required_attr . $attributes_string; ?>
      >
    <?php endif; ?>
  </div>
  <?php
}

/**
 * Render form section
 */
function giftflowwp_render_form_section( $title, $icon, $fields, $form_class = 'gfw-account-form' ) {
  ?>
  <div class="gfw-form-section">
    <h3 class="gfw-form-section-title">
      <?php echo giftflowwp_svg_icon( $icon ); ?>
      <?php echo esc_html( $title ); ?>
    </h3>
    
    <div class="gfw-form-fields">
      <?php foreach ( $fields as $field ): ?>
        <?php giftflowwp_render_form_field( $field ); ?>
      <?php endforeach; ?>
    </div>
  </div>
  <?php
}

// =============================================================================
// FORM PROCESSING
// =============================================================================

$account_result = null;
$password_result = null;

// Process account form
if ( isset( $_POST['giftflowwp_update_account'] ) && wp_verify_nonce( $_POST['giftflowwp_account_nonce'], 'giftflowwp_update_account' ) ) {
  $account_result = giftflowwp_process_account_form( $donor );
}

// Process password form
if ( isset( $_POST['giftflowwp_update_password'] ) && wp_verify_nonce( $_POST['giftflowwp_password_nonce'], 'giftflowwp_update_password' ) ) {
  $password_result = giftflowwp_process_password_form( $current_user );
}

// =============================================================================
// DATA PREPARATION
// =============================================================================

// Get donor data for form population
$form_data = array(
  'first_name' => $donor['first_name'] ?? '',
  'last_name' => $donor['last_name'] ?? '',
  'email' => $donor['email'] ?? '',
  'phone' => $donor['phone'] ?? '',
  'address' => $donor['address'] ?? '',
  'city' => $donor['city'] ?? '',
  'state' => $donor['state'] ?? '',
  'postal_code' => $donor['postal_code'] ?? '',
  'country' => $donor['country'] ?? '',
);
?>

<div class="giftflowwp-donor-account-my-account">
  <?php giftflowwp_render_page_header(); ?>
  <?php giftflowwp_render_messages( $account_result, $password_result ); ?>
  
  <?php giftflowwp_render_account_form( $form_data ); ?>
  <?php giftflowwp_render_password_form(); ?>
</div>

<?php
// =============================================================================
// TEMPLATE RENDERING FUNCTIONS
// =============================================================================

/**
 * Render account information form
 */
function giftflowwp_render_account_form( $form_data ) {
  // Personal information fields
  $personal_fields = array(
    array(
      'id' => 'first_name',
      'name' => 'first_name',
      'type' => 'text',
      'label' => __( 'First Name', 'giftflowwp' ),
      'value' => $form_data['first_name'],
      'required' => true,
    ),
    array(
      'id' => 'last_name',
      'name' => 'last_name',
      'type' => 'text',
      'label' => __( 'Last Name', 'giftflowwp' ),
      'value' => $form_data['last_name'],
      'required' => true,
    ),
    array(
      'id' => 'email',
      'name' => 'email',
      'type' => 'email',
      'label' => __( 'Email Address', 'giftflowwp' ),
      'value' => $form_data['email'],
      'required' => true,
      'readonly' => true,
    ),
    array(
      'id' => 'phone',
      'name' => 'phone',
      'type' => 'tel',
      'label' => __( 'Phone Number', 'giftflowwp' ),
      'value' => $form_data['phone'],
    ),
  );

  // Address information fields
  $address_fields = array(
    array(
      'id' => 'address',
      'name' => 'address',
      'type' => 'textarea',
      'label' => __( 'Street Address', 'giftflowwp' ),
      'value' => $form_data['address'],
      'full_width' => true,
      'rows' => 3,
    ),
    array(
      'id' => 'city',
      'name' => 'city',
      'type' => 'text',
      'label' => __( 'City', 'giftflowwp' ),
      'value' => $form_data['city'],
    ),
    array(
      'id' => 'state',
      'name' => 'state',
      'type' => 'text',
      'label' => __( 'State/Province', 'giftflowwp' ),
      'value' => $form_data['state'],
    ),
    array(
      'id' => 'postal_code',
      'name' => 'postal_code',
      'type' => 'text',
      'label' => __( 'ZIP/Postal Code', 'giftflowwp' ),
      'value' => $form_data['postal_code'],
    ),
    array(
      'id' => 'country',
      'name' => 'country',
      'type' => 'text',
      'label' => __( 'Country', 'giftflowwp' ),
      'value' => $form_data['country'],
    ),
  );

  ?>
  <form class="gfw-account-form" method="post" action="">
    <?php wp_nonce_field( 'giftflowwp_update_account', 'giftflowwp_account_nonce' ); ?>
    
    <?php giftflowwp_render_form_section( __( 'Personal Information', 'giftflowwp' ), 'info', $personal_fields ); ?>
    <?php giftflowwp_render_form_section( __( 'Address Information', 'giftflowwp' ), 'map-pin', $address_fields ); ?>
    
    <div class="gfw-form-actions">
      <button type="submit" name="giftflowwp_update_account" class="gfw-btn gfw-btn-primary">
        <?php esc_html_e( 'Update Account', 'giftflowwp' ); ?>
      </button>
    </div>
  </form>
  <?php
}

/**
 * Render password change form
 */
function giftflowwp_render_password_form() {
  $password_fields = array(
    array(
      'id' => 'giftflowwp_current_password',
      'name' => 'giftflowwp_current_password',
      'type' => 'password',
      'label' => esc_html__( 'Current Password', 'giftflowwp' ),
      'required' => true,
      'full_width' => true,
      'attributes' => array( 'autocomplete' => 'current-password' ),
    ),
    array(
      'id' => 'giftflowwp_new_password',
      'name' => 'giftflowwp_new_password',
      'type' => 'password',
      'label' => esc_html__( 'New Password', 'giftflowwp' ),
      'required' => true,
      'full_width' => true,
      'attributes' => array( 'autocomplete' => 'new-password' ),
    ),
    array(
      'id' => 'giftflowwp_confirm_password',
      'name' => 'giftflowwp_confirm_password',
      'type' => 'password',
      'label' => esc_html__( 'Confirm New Password', 'giftflowwp' ),
      'required' => true,
      'full_width' => true,
      'attributes' => array( 'autocomplete' => 'new-password' ),
    ),
  );

  ?>
  <br/>
  <hr />
  <br />

  <div class="gfw-form-section">
    <h3 class="gfw-form-section-title gfw-donor-account__title">
      <?php echo giftflowwp_svg_icon( 'lock-closed' ); ?>
      <?php esc_html_e( 'Change Password', 'giftflowwp' ); ?>
    </h3>

    <p class="gfw-form-desc">
      <?php esc_html_e( 'For your security, you can change your password below. Please enter your current password and choose a new password that is at least 6 characters long.', 'giftflowwp' ); ?>
    </p>

    <br />

    <form class="gfw-account-form" method="post" action="">
      <div class="gfw-form-fields">
        <?php foreach ( $password_fields as $field ): ?>
          <?php giftflowwp_render_form_field( $field ); ?>
        <?php endforeach; ?>
      </div>
      
      <br />
      
      <div class="gfw-form-actions">
        <button type="submit" name="giftflowwp_update_password" class="gfw-btn gfw-btn-primary">
          <?php esc_html_e( 'Update Password', 'giftflowwp' ); ?>
        </button>
      </div>
      
      <?php wp_nonce_field( 'giftflowwp_update_password', 'giftflowwp_password_nonce' ); ?>
    </form>
  </div>
  <?php
}