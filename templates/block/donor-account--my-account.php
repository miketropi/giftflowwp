<?php
/**
 * Template for my account
 *
 * @package GiftFlow
 * @since 1.0.0
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}

// =============================================================================
// INITIALIZATION & VALIDATION
// =============================================================================
// phpcs:ignore WordPress.NamingConventions.PrefixAllGlobals.NonPrefixedVariableFound, WordPress.WP.GlobalVariablesOverride.Prohibited
$current_user = $current_user ?? null; // wp user object.
// phpcs:ignore WordPress.NamingConventions.PrefixAllGlobals.NonPrefixedVariableFound
$donor = $donor ?? null; // donor information.

// Return if required data is missing.
if ( ! $current_user || ! $donor ) {
	return;
}


/**
 * Render page header
 *
 * @return void
 */
function giftflow_render_page_header() {
	?>
	<h2 class="gfw-donor-account__title"><?php esc_html_e( 'My Account', 'giftflow' ); ?></h2>
	<p>
	<?php esc_html_e( 'Keep your donor account information accurate and up to date using the form below. By maintaining your current contact details, you\'ll ensure you never miss important updates, donation receipts, or opportunities to support causes you care about.', 'giftflow' ); ?>
	</p>
	<p>
	<?php esc_html_e( '🔒 Your privacy is important to us. Your information is securely stored and will never be shared without your consent.', 'giftflow' ); ?>
	</p>
	<br/>
	<?php
}

/**
 * Render success/error messages
 *
 * @param array $account_result The account result.
 * @param array $password_result The password result.
 * @return void
 */
function giftflow_render_messages( $account_result, $password_result ) {
	// Account form messages.
	if ( $account_result ) {
		if ( $account_result['success'] ) {
			giftflow_render_message( $account_result['message'], 'success' );
		} else {
			giftflow_render_message( $account_result['errors'], 'error' );
		}
	}

	// Password form messages.
	if ( $password_result ) {
		if ( $password_result['success'] ) {
			giftflow_render_message( $password_result['message'], 'success' );
		} else {
			giftflow_render_message( $password_result['errors'], 'error' );
		}
	}
}

/**
 * Render a single message
 *
 * @param string $message The message.
 * @param string $type The type of message.
 * @return void
 */
function giftflow_render_message( $message, $type ) {
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
 *
 * @param array $field_config The field configuration.
 * @return void
 */
function giftflow_render_form_field( $field_config ) {
	$field_id         = $field_config['id'];
	$field_name       = $field_config['name'];
	$field_type       = $field_config['type'] ?? 'text';
	$field_value      = $field_config['value'] ?? '';
	$field_label      = $field_config['label'];
	$field_required   = $field_config['required'] ?? false;
	$field_readonly   = $field_config['readonly'] ?? false;
	$field_rows       = $field_config['rows'] ?? 3;
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
		<?php echo esc_html( $field_label ); ?><?php echo wp_kses_post( $required_span ); ?>
	</label>
	
	<?php if ( 'textarea' === $field_type ) : ?>
		<textarea 
		id="<?php echo esc_attr( $field_id ); ?>" 
		name="<?php echo esc_attr( $field_name ); ?>" 
		rows="<?php echo esc_attr( $field_config['rows'] ?? 3 ); ?>"
		<?php echo esc_attr( $readonly_attr . $required_attr . $attributes_string ); ?>
		><?php echo esc_textarea( $field_value ); ?></textarea>
	<?php else : ?>
		<input 
		type="<?php echo esc_attr( $field_type ); ?>" 
		id="<?php echo esc_attr( $field_id ); ?>" 
		name="<?php echo esc_attr( $field_name ); ?>" 
		value="<?php echo esc_attr( $field_value ); ?>"
		<?php echo esc_attr( $readonly_attr . $required_attr . $attributes_string ); ?>
		>
	<?php endif; ?>
	</div>
	<?php
}

/**
 * Render form section
 *
 * @param string $title The title of the section.
 * @param string $icon The icon of the section.
 * @param array $fields The fields of the section.
 * @param string $form_class The class of the form.
 * @return void
 */
function giftflow_render_form_section( $title, $icon, $fields, $form_class = '' ) {
	?>
	<div class="gfw-form-section <?php echo esc_attr( $form_class ); ?>">
		<h3 class="gfw-form-section-title">
			<?php echo wp_kses( giftflow_svg_icon( $icon ), giftflow_allowed_svg_tags() ); ?>
			<?php echo esc_html( $title ); ?>
		</h3>
		
		<div class="gfw-form-fields">
			<?php foreach ( $fields as $field ) : ?>
				<?php giftflow_render_form_field( $field ); ?>
			<?php endforeach; ?>
		</div>
	</div>
	<?php
}

// =============================================================================
// DATA VALIDATION
// =============================================================================

// Ensure required data is available.
// phpcs:ignore WordPress.NamingConventions.PrefixAllGlobals.NonPrefixedVariableFound
$account_result = $account_result ?? null;
// phpcs:ignore WordPress.NamingConventions.PrefixAllGlobals.NonPrefixedVariableFound
$password_result = $password_result ?? null;
// phpcs:ignore WordPress.NamingConventions.PrefixAllGlobals.NonPrefixedVariableFound
$form_data = $form_data ?? array();
?>

<div class="giftflow-donor-account-my-account">
	<?php giftflow_render_page_header(); ?>
	<?php giftflow_render_messages( $account_result, $password_result ); ?>
  
	<?php giftflow_render_account_form( $form_data ); ?>
	<?php giftflow_render_password_form(); ?>
</div>

<?php
// =============================================================================
// TEMPLATE RENDERING FUNCTIONS
// =============================================================================

/**
 * Render account information form
 *
 * @param array $form_data The form data.
 * @return void
 */
function giftflow_render_account_form( $form_data ) {
	// Personal information fields.
	$personal_fields = array(
		array(
			'id'       => 'first_name',
			'name'     => 'first_name',
			'type'     => 'text',
			'label'    => __( 'First Name', 'giftflow' ),
			'value'    => $form_data['first_name'],
			'required' => true,
		),
		array(
			'id'       => 'last_name',
			'name'     => 'last_name',
			'type'     => 'text',
			'label'    => __( 'Last Name', 'giftflow' ),
			'value'    => $form_data['last_name'],
			'required' => true,
		),
		array(
			'id'       => 'email',
			'name'     => 'email',
			'type'     => 'email',
			'label'    => __( 'Email Address', 'giftflow' ),
			'value'    => $form_data['email'],
			'required' => true,
			'readonly' => true,
		),
		array(
			'id'    => 'phone',
			'name'  => 'phone',
			'type'  => 'tel',
			'label' => __( 'Phone Number', 'giftflow' ),
			'value' => $form_data['phone'],
		),
	);

	// Address information fields.
	$address_fields = array(
		array(
			'id'         => 'address',
			'name'       => 'address',
			'type'       => 'textarea',
			'label'      => __( 'Street Address', 'giftflow' ),
			'value'      => $form_data['address'],
			'full_width' => true,
			'rows'       => 3,
		),
		array(
			'id'    => 'city',
			'name'  => 'city',
			'type'  => 'text',
			'label' => __( 'City', 'giftflow' ),
			'value' => $form_data['city'],
		),
		array(
			'id'    => 'state',
			'name'  => 'state',
			'type'  => 'text',
			'label' => __( 'State/Province', 'giftflow' ),
			'value' => $form_data['state'],
		),
		array(
			'id'    => 'postal_code',
			'name'  => 'postal_code',
			'type'  => 'text',
			'label' => __( 'ZIP/Postal Code', 'giftflow' ),
			'value' => $form_data['postal_code'],
		),
		array(
			'id'    => 'country',
			'name'  => 'country',
			'type'  => 'text',
			'label' => __( 'Country', 'giftflow' ),
			'value' => $form_data['country'],
		),
	);

	?>
	<form class="gfw-account-form" method="post" action="">
	<?php wp_nonce_field( 'giftflow_update_account', 'giftflow_account_nonce' ); ?>
	
	<?php giftflow_render_form_section( __( 'Personal Information', 'giftflow' ), 'info', $personal_fields ); ?>
	<?php giftflow_render_form_section( __( 'Address Information', 'giftflow' ), 'map-pin', $address_fields ); ?>
	
	<div class="gfw-form-actions">
		<button type="submit" name="giftflow_update_account" class="gfw-btn gfw-btn-primary">
		<?php esc_html_e( 'Update Account', 'giftflow' ); ?>
		</button>
	</div>
	</form>
	<?php
}

/**
 * Render password change form
 *
 * @return void
 */
function giftflow_render_password_form() {
	$password_fields = array(
		array(
			'id'         => 'giftflow_current_password',
			'name'       => 'giftflow_current_password',
			'type'       => 'password',
			'label'      => esc_html__( 'Current Password', 'giftflow' ),
			'required'   => true,
			'full_width' => true,
			'attributes' => array( 'autocomplete' => 'current-password' ),
		),
		array(
			'id'         => 'giftflow_new_password',
			'name'       => 'giftflow_new_password',
			'type'       => 'password',
			'label'      => esc_html__( 'New Password', 'giftflow' ),
			'required'   => true,
			'full_width' => true,
			'attributes' => array( 'autocomplete' => 'new-password' ),
		),
		array(
			'id'         => 'giftflow_confirm_password',
			'name'       => 'giftflow_confirm_password',
			'type'       => 'password',
			'label'      => esc_html__( 'Confirm New Password', 'giftflow' ),
			'required'   => true,
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
		<?php echo wp_kses( giftflow_svg_icon( 'lock-closed' ), giftflow_allowed_svg_tags() ); ?>
		<?php esc_html_e( 'Change Password', 'giftflow' ); ?>
	</h3>

	<p class="gfw-form-desc">
		<?php esc_html_e( 'For your security, you can change your password below. Please enter your current password and choose a new password that is at least 6 characters long.', 'giftflow' ); ?>
	</p>

	<br />

	<form class="gfw-account-form" method="post" action="">
		<div class="gfw-form-fields">
		<?php foreach ( $password_fields as $field ) : ?>
			<?php giftflow_render_form_field( $field ); ?>
		<?php endforeach; ?>
		</div>
	  
		<br />
	  
		<div class="gfw-form-actions">
		<button type="submit" name="giftflow_update_password" class="gfw-btn gfw-btn-primary">
			<?php esc_html_e( 'Update Password', 'giftflow' ); ?>
		</button>
		</div>
	  
		<?php wp_nonce_field( 'giftflow_update_password', 'giftflow_password_nonce' ); ?>
	</form>
	</div>
	<?php
}