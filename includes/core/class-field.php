<?php
/**
 * Field Class
 *
 * A flexible field builder for WordPress custom meta fields.
 *
 * @package GiftFlowWP
 * @since 1.0.0
 */

// Exit if accessed directly.
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Field Class
 *
 * @since 1.0.0
 */
class GiftFlowWP_Field {

	/**
	 * Field ID
	 *
	 * @var string
	 */
	private $id;

	/**
	 * Field name
	 *
	 * @var string
	 */
	private $name;

	/**
	 * Field label
	 *
	 * @var string
	 */
	private $label;

	/**
	 * Field description
	 *
	 * @var string
	 */
	private $description;

	/**
	 * Field type
	 *
	 * @var string
	 */
	private $type;

	/**
	 * Field value
	 *
	 * @var mixed
	 */
	private $value;

	/**
	 * Field options (for select, multiple select, etc.)
	 *
	 * @var array
	 */
	private $options = array();

	/**
	 * Field attributes
	 *
	 * @var array
	 */
	private $attributes = array();

	/**
	 * Field classes
	 *
	 * @var array
	 */
	private $classes = array();

	/**
	 * Field wrapper classes
	 *
	 * @var array
	 */
	private $wrapper_classes = array();

	/**
	 * Field default value
	 *
	 * @var mixed
	 */
	private $default;

	/**
	 * Field required
	 *
	 * @var bool
	 */
	private $required = false;

	/**
	 * Field disabled
	 *
	 * @var bool
	 */
	private $disabled = false;

	/**
	 * Field placeholder
	 *
	 * @var string
	 */
	private $placeholder = '';

	/**
	 * Field min value (for number, currency)
	 *
	 * @var int|float
	 */
	private $min;

	/**
	 * Field max value (for number, currency)
	 *
	 * @var int|float
	 */
	private $max;

	/**
	 * Field step value (for number, currency)
	 *
	 * @var int|float
	 */
	private $step;

	/**
	 * Field currency symbol (for currency)
	 *
	 * @var string
	 */
	private $currency_symbol = '$';

	/**
	 * Field currency position (for currency)
	 *
	 * @var string
	 */
	private $currency_position = 'before';

	/**
	 * Field rows (for textarea)
	 *
	 * @var int
	 */
	private $rows = 5;

	/**
	 * Field columns (for textarea)
	 *
	 * @var int
	 */
	private $cols = 50;

	/**
	 * Field date format (for datetime)
	 *
	 * @var string
	 */
	private $date_format = 'Y-m-d H:i:s';

	/**
	 * Field time format (for datetime)
	 *
	 * @var string
	 */
	private $time_format = 'H:i:s';

	/**
	 * Field color format (for color)
	 *
	 * @var string
	 */
	private $color_format = 'hex';

	/**
	 * Field gallery settings (for gallery)
	 *
	 * @var array
	 */
	private $gallery_settings = array(
		'max_images' => 0,
		'image_size' => 'thumbnail',
		'button_text' => 'Select Images',
		'remove_text' => 'Remove All',
	);

	/**
	 * Field repeater settings (for repeater)
	 *
	 * @var array
	 */
	private $repeater_settings = array(
		'fields' => array(),
		'button_text' => 'Add Row',
		'remove_text' => 'Remove Row',
		'min_rows' => 0,
		'max_rows' => 0,
		'row_label' => 'Row',
	);

	/**
	 * Constructor
	 *
	 * @param string $id Field ID.
	 * @param string $name Field name.
	 * @param string $type Field type.
	 * @param array  $args Field arguments.
	 */
	public function __construct( $id, $name, $type, $args = array() ) {
		$this->id = $id;
		$this->name = $name;
		$this->type = $type;

		// Set default value.
		$this->default = isset( $args['default'] ) ? $args['default'] : '';

		// Set field value.
		$this->value = isset( $args['value'] ) ? $args['value'] : $this->default;

		// Set field label.
		$this->label = isset( $args['label'] ) ? $args['label'] : '';

		// Set field description.
		$this->description = isset( $args['description'] ) ? $args['description'] : '';

		// Set field options.
		$this->options = isset( $args['options'] ) ? $args['options'] : array();

		// Set field attributes.
		$this->attributes = isset( $args['attributes'] ) ? $args['attributes'] : array();

		// Set field classes.
		$this->classes = isset( $args['classes'] ) ? $args['classes'] : array();

		// Set field wrapper classes.
		$this->wrapper_classes = isset( $args['wrapper_classes'] ) ? $args['wrapper_classes'] : array();

		// Set field required.
		$this->required = isset( $args['required'] ) ? $args['required'] : false;

		// Set field disabled.
		$this->disabled = isset( $args['disabled'] ) ? $args['disabled'] : false;

		// Set field placeholder.
		$this->placeholder = isset( $args['placeholder'] ) ? $args['placeholder'] : '';

		// Set field min value.
		$this->min = isset( $args['min'] ) ? $args['min'] : null;

		// Set field max value.
		$this->max = isset( $args['max'] ) ? $args['max'] : null;

		// Set field step value.
		$this->step = isset( $args['step'] ) ? $args['step'] : null;


    // default currency symbol
    $default_currency_symbol = giftflowwp_get_currency_symbol(giftflowwp_get_current_currency());

		// Set field currency symbol.
		$this->currency_symbol = isset( $args['currency_symbol'] ) ? $args['currency_symbol'] : $default_currency_symbol;

		// Set field currency position.
		$this->currency_position = isset( $args['currency_position'] ) ? $args['currency_position'] : 'before';

		// Set field rows.
		$this->rows = isset( $args['rows'] ) ? $args['rows'] : 5;

		// Set field columns.
		$this->cols = isset( $args['cols'] ) ? $args['cols'] : 50;

		// Set field date format.
		$this->date_format = isset( $args['date_format'] ) ? $args['date_format'] : 'Y-m-d H:i:s';

		// Set field time format.
		$this->time_format = isset( $args['time_format'] ) ? $args['time_format'] : 'H:i:s';

		// Set field color format.
		$this->color_format = isset( $args['color_format'] ) ? $args['color_format'] : 'hex';

		// Set gallery settings.
		if ( 'gallery' === $type && isset( $args['gallery_settings'] ) ) {
			$this->gallery_settings = wp_parse_args( $args['gallery_settings'], $this->gallery_settings );
		}

		// Set repeater settings.
		if ( 'repeater' === $type && isset( $args['repeater_settings'] ) ) {
			$this->repeater_settings = wp_parse_args( $args['repeater_settings'], $this->repeater_settings );
		}
	}

	/**
	 * Render field
	 *
	 * @return string
	 */
	public function render() {
		$output = '';

		// Start field wrapper.
		$output .= $this->get_field_wrapper_start();

		// Render field label.
		$output .= $this->get_field_label();

		$output .= '<div class="giftflowwp-field-wrapper">';
    

		// Render field based on type.
		switch ( $this->type ) {
			case 'textfield':
				$output .= $this->render_textfield();
				break;
			case 'number':
				$output .= $this->render_number();
				break;
			case 'currency':
				$output .= $this->render_currency();
				break;
			case 'select':
				$output .= $this->render_select();
				break;
			case 'multiple_select':
				$output .= $this->render_multiple_select();
				break;
			case 'textarea':
				$output .= $this->render_textarea();
				break;
			case 'checkbox':
				$output .= $this->render_checkbox();
				break;
			case 'switch':
				$output .= $this->render_switch();
				break;
			case 'datetime':
				$output .= $this->render_datetime();
				break;
			case 'color':
				$output .= $this->render_color();
				break;
			case 'gallery':
				$output .= $this->render_gallery();
				break;
			case 'googlemap':
				$output .= $this->render_googlemap();
				break;
			case 'repeater':
				$output .= $this->render_repeater();
				break;
			default:
				$output .= $this->render_textfield();
				break;
		}

    // Render field description.
		$output .= $this->get_field_description();

    $output .= '</div>';

		// End field wrapper.
		$output .= $this->get_field_wrapper_end();

		return $output;
	}

	/**
	 * Get field wrapper start
	 *
	 * @return string
	 */
	private function get_field_wrapper_start() {
		$wrapper_classes = array_merge( array( 'giftflowwp-field', 'giftflowwp-field-' . $this->type ), $this->wrapper_classes );
		$wrapper_classes = array_filter( $wrapper_classes );
		$wrapper_class = implode( ' ', $wrapper_classes );

		return '<div class="' . esc_attr( $wrapper_class ) . '">';
	}

	/**
	 * Get field wrapper end
	 *
	 * @return string
	 */
	private function get_field_wrapper_end() {
		return '</div>';
	}

	/**
	 * Get field label
	 *
	 * @return string
	 */
	private function get_field_label() {
		if ( empty( $this->label ) ) {
			return '';
		}

		$required = $this->required ? ' <span class="required">*</span>' : '';
		return '<label for="' . esc_attr( $this->id ) . '">' . esc_html( $this->label ) . $required . '</label>';
	}

	/**
	 * Get field description
	 *
	 * @return string
	 */
	private function get_field_description() {
		if ( empty( $this->description ) ) {
			return '';
		}

		return '<p class="description">' . esc_html( $this->description ) . '</p>';
	}

	/**
	 * Get field attributes
	 *
	 * @return string
	 */
	private function get_field_attributes() {
		$attributes = array();

		// Add ID.
		$attributes[] = 'id="' . esc_attr( $this->id ) . '"';

		// Add name.
		$attributes[] = 'name="' . esc_attr( $this->name ) . '"';

		// Add placeholder.
		if ( ! empty( $this->placeholder ) ) {
			$attributes[] = 'placeholder="' . esc_attr( $this->placeholder ) . '"';
		}

		// Add required.
		if ( $this->required ) {
			$attributes[] = 'required';
		}

		// Add disabled.
		if ( $this->disabled ) {
			$attributes[] = 'disabled';
		}

		// Add classes.
		$classes = array_merge( array( 'giftflowwp-field-input' ), $this->classes );
		$classes = array_filter( $classes );
		$class = implode( ' ', $classes );
		$attributes[] = 'class="' . esc_attr( $class ) . '"';

		// Add custom attributes.
		foreach ( $this->attributes as $key => $value ) {
			$attributes[] = esc_attr( $key ) . '="' . esc_attr( $value ) . '"';
		}

		return implode( ' ', $attributes );
	}

	/**
	 * Render textfield
	 *
	 * @return string
	 */
	private function render_textfield() {
		$attributes = $this->get_field_attributes();
		return '<input type="text" ' . $attributes . ' value="' . esc_attr( $this->value ) . '" />';
	}

	/**
	 * Render number
	 *
	 * @return string
	 */
	private function render_number() {
		$attributes = $this->get_field_attributes();

		// Add min.
		if ( null !== $this->min ) {
			$attributes .= ' min="' . esc_attr( $this->min ) . '"';
		}

		// Add max.
		if ( null !== $this->max ) {
			$attributes .= ' max="' . esc_attr( $this->max ) . '"';
		}

		// Add step.
		if ( null !== $this->step ) {
			$attributes .= ' step="' . esc_attr( $this->step ) . '"';
		}

		return '<input type="number" ' . $attributes . ' value="' . esc_attr( $this->value ) . '" />';
	}

	/**
	 * Render currency
	 *
	 * @return string
	 */
	private function render_currency() {
		$attributes = $this->get_field_attributes();

		// Add min.
		if ( null !== $this->min ) {
			$attributes .= ' min="' . esc_attr( $this->min ) . '"';
		}

		// Add max.
		if ( null !== $this->max ) {
			$attributes .= ' max="' . esc_attr( $this->max ) . '"';
		}

		// Add step.
		if ( null !== $this->step ) {
			$attributes .= ' step="' . esc_attr( $this->step ) . '"';
		}

		$output = '<div class="giftflowwp-currency-field">';

		if ( 'before' === $this->currency_position ) {
			$output .= '<span class="giftflowwp-currency-symbol">' . esc_html( $this->currency_symbol ) . '</span>';
		}

		$output .= '<input type="number" ' . $attributes . ' value="' . esc_attr( $this->value ) . '" />';

		if ( 'after' === $this->currency_position ) {
			$output .= '<span class="giftflowwp-currency-symbol">' . esc_html( $this->currency_symbol ) . '</span>';
		}

		$output .= '</div>';

		return $output;
	}

	/**
	 * Render select
	 *
	 * @return string
	 */
	private function render_select() {
		$attributes = $this->get_field_attributes();
		$output = '<select ' . $attributes . '>';

		// Add empty option if no value is selected.
		if ( empty( $this->value ) ) {
			$output .= '<option value="">' . esc_html__( 'Select an option', 'giftflowwp' ) . '</option>';
		}

		// Add options.
		foreach ( $this->options as $option_value => $option_label ) {
			$selected = selected( $this->value, $option_value, false );
			$output .= '<option value="' . esc_attr( $option_value ) . '" ' . $selected . '>' . esc_html( $option_label ) . '</option>';
		}

		$output .= '</select>';

		return $output;
	}

	/**
	 * Render multiple select
	 *
	 * @return string
	 */
	private function render_multiple_select() {
		$attributes = $this->get_field_attributes();
		$attributes .= ' multiple';

		// Convert value to array if it's not already.
		$values = is_array( $this->value ) ? $this->value : array( $this->value );

		$output = '<select ' . $attributes . '>';

		// Add options.
		foreach ( $this->options as $option_value => $option_label ) {
			$selected = in_array( $option_value, $values, true ) ? ' selected' : '';
			$output .= '<option value="' . esc_attr( $option_value ) . '"' . $selected . '>' . esc_html( $option_label ) . '</option>';
		}

		$output .= '</select>';

		return $output;
	}

	/**
	 * Render textarea
	 *
	 * @return string
	 */
	private function render_textarea() {
		$attributes = $this->get_field_attributes();
		$attributes .= ' rows="' . esc_attr( $this->rows ) . '"';
		$attributes .= ' cols="' . esc_attr( $this->cols ) . '"';

		return '<textarea ' . $attributes . '>' . esc_textarea( $this->value ) . '</textarea>';
	}

	/**
	 * Render checkbox
	 *
	 * @return string
	 */
	private function render_checkbox() {
		$attributes = $this->get_field_attributes();
		$checked = checked( $this->value, true, false );

		return '<input type="checkbox" ' . $attributes . ' ' . $checked . ' />';
	}

	/**
	 * Render switch
	 *
	 * @return string
	 */
	private function render_switch() {
		$attributes = $this->get_field_attributes();
		$checked = checked( $this->value, true, false );

		$output = '<div class="giftflowwp-switch">';
		$output .= '<input type="checkbox" ' . $attributes . ' ' . $checked . ' value="1" />';
		$output .= '<span class="giftflowwp-switch-slider"></span>';
		$output .= '</div>';

		return $output;
	}

	/**
	 * Render datetime
	 *
	 * @return string
	 */
	private function render_datetime() {
		$attributes = $this->get_field_attributes();
		$value = ! empty( $this->value ) ? date( $this->date_format, strtotime( $this->value ) ) : '';

		return '<input type="datetime-local" ' . $attributes . ' value="' . esc_attr( $value ) . '" />';
	}

	/**
	 * Render color
	 *
	 * @return string
	 */
	private function render_color() {
		$attributes = $this->get_field_attributes();

		$output = '<div class="giftflowwp-color-field">';
		$output .= '<input type="color" ' . $attributes . ' value="' . esc_attr( $this->value ) . '" />';
		$output .= '<input type="text" class="giftflowwp-color-text" value="' . esc_attr( $this->value ) . '" />';
		$output .= '</div>';

		return $output;
	}

	/**
	 * Render gallery
	 *
	 * @return string
	 */
	private function render_gallery() {
		// Start output buffering
		ob_start();
		
		// Ensure value is an array
		$image_ids = $this->value ? explode(',', $this->value) : array();
		
		// Generate a unique ID for the gallery
		$gallery_id = 'giftflowwp-gallery-' . $this->id;
		?>
		<div class="giftflowwp-gallery-field" id="<?php echo esc_attr( $gallery_id ); ?>">
			<!-- Hidden input to store image IDs -->
			<input type="hidden" name="<?php echo esc_attr( $this->name ); ?>" id="<?php echo esc_attr( $this->id ); ?>" value="<?php echo esc_attr( implode( ',', $image_ids ) ); ?>" />
			
			<!-- Gallery preview container -->
			<div class="giftflowwp-gallery-preview">
				<?php
				// Display selected images
				if ( ! empty( $image_ids ) ) {
					foreach ( $image_ids as $image_id ) {
						$image_url = wp_get_attachment_image_url( $image_id, $this->gallery_settings['image_size'] );
						if ( $image_url ) {
							?>
							<div class="giftflowwp-gallery-image" data-id="<?php echo esc_attr( $image_id ); ?>">
								<img src="<?php echo esc_url( $image_url ); ?>" alt="<?php echo esc_attr( get_post_meta( $image_id, '_wp_attachment_image_alt', true ) ); ?>" />
								<span class="giftflowwp-gallery-remove" title="<?php esc_attr_e( 'Remove Image', 'giftflowwp' ); ?>">&times;</span>
							</div>
							<?php
						}
					}
				}
				?>
			</div><!-- End gallery preview -->
			
			<!-- Gallery controls -->
			<div class="giftflowwp-gallery-controls">
				<button type="button" class="button giftflowwp-gallery-add"><?php echo esc_html( $this->gallery_settings['button_text'] ); ?></button>
				
				<?php if ( ! empty( $image_ids ) ) : ?>
					<button type="button" class="button giftflowwp-gallery-remove-all"><?php echo esc_html( $this->gallery_settings['remove_text'] ); ?></button>
				<?php endif; ?>
			</div><!-- End gallery controls -->
			
			<!-- JavaScript for gallery functionality -->
			<script type="text/javascript">
				jQuery(document).ready(function($) {
					var galleryFrame;
					var $gallery = $("#<?php echo esc_js( $gallery_id ); ?>");
					var $input = $gallery.find("input[type=hidden]");
					var $preview = $gallery.find(".giftflowwp-gallery-preview");
					var $addButton = $gallery.find(".giftflowwp-gallery-add");
					var $removeAllButton = $gallery.find(".giftflowwp-gallery-remove-all");
					var maxImages = <?php echo intval( $this->gallery_settings['max_images'] ); ?>;
					
					// Open media frame
					$addButton.on("click", function(e) {
						e.preventDefault();
						
						// If the media frame already exists, reopen it
						if (galleryFrame) {
							galleryFrame.open();
							return;
						}
						
						// Create the media frame
						galleryFrame = wp.media({
							title: "<?php esc_attr_e( 'Select Images', 'giftflowwp' ); ?>",
							button: {
								text: "<?php esc_attr_e( 'Add to Gallery', 'giftflowwp' ); ?>"
							},
							multiple: true
						});
						
						// When an image is selected in the media frame
						galleryFrame.on("select", function() {
							var selection = galleryFrame.state().get("selection");
							var currentIds = $input.val() ? $input.val().split(",") : [];
							
							selection.map(function(attachment) {
								attachment = attachment.toJSON();
								
								// Check if max images limit is reached
								if (maxImages > 0 && currentIds.length >= maxImages) {
									return;
								}
								
								// Add image ID to the array if not already present
								if (currentIds.indexOf(attachment.id.toString()) === -1) {
									currentIds.push(attachment.id);
								}
							});
							
							// Update hidden input
							$input.val(currentIds.join(","));
							
							// Update preview
							updateGalleryPreview();
						});
						
						// Open the media frame
						galleryFrame.open();
					});
					
					// Remove single image
					$gallery.on("click", ".giftflowwp-gallery-remove", function(e) {
						e.preventDefault();
						
						var $image = $(this).parent();
						var imageId = $image.data("id");
						var currentIds = $input.val() ? $input.val().split(",") : [];
						
						// Remove image ID from array
						currentIds = currentIds.filter(function(id) {
							return id != imageId;
						});
						
						// Update hidden input
						$input.val(currentIds.join(","));
						
						// Remove image from preview
						$image.remove();
						
						// Show/hide remove all button
						if (currentIds.length === 0) {
							$removeAllButton.hide();
						}
					});
					
					// Remove all images
					$removeAllButton.on("click", function(e) {
						e.preventDefault();
						
						// Clear hidden input
						$input.val("");
						
						// Clear preview
						$preview.empty();
						
						// Hide remove all button
						$(this).hide();
					});
					
					// Update gallery preview
					function updateGalleryPreview() {
						var currentIds = $input.val() ? $input.val().split(",") : [];
						
						// Clear preview
						$preview.empty();
						
						// Add images to preview
						if (currentIds.length > 0) {
							$.ajax({
								url: ajaxurl,
								type: "POST",
								data: {
									action: "giftflowwp_get_gallery_images",
									ids: currentIds,
									size: "<?php echo esc_js( $this->gallery_settings['image_size'] ); ?>",
									nonce: "<?php echo esc_js( wp_create_nonce( 'giftflowwp_gallery_nonce' ) ); ?>"
								},
								success: function(response) {
									if (response.success && response.data) {
										$.each(response.data, function(id, image) {
											var $image = $("<div class='giftflowwp-gallery-image' data-id='" + id + "'>");
											$image.append("<img src='" + image.url + "' alt='" + image.alt + "' />");
											$image.append("<span class='giftflowwp-gallery-remove' title='<?php esc_attr_e( 'Remove Image', 'giftflowwp' ); ?>'>&times;</span>");
											$preview.append($image);
										});
										
										// Show remove all button
										$removeAllButton.show();
									}
								}
							});
						} else {
							// Hide remove all button
							$removeAllButton.hide();
						}
					}
				});
			</script>
		</div><!-- End gallery field -->
		<?php
		
		// Return the buffered content
		return ob_get_clean();
	}

	/**
	 * Render Google Maps field
	 *
	 * @return string
	 */
	private function render_googlemap() {
		// Start output buffering
		ob_start();
		
		// Generate a unique ID for the map
		$map_id = 'giftflowwp-map-' . $this->id;
		
		// Get the current value
		$value = $this->value;
		$address = '';
		$lat = '';
		$lng = '';
		
		// Parse the value if it exists
		if (!empty($value)) {
			$value_data = json_decode($value, true);
			if (is_array($value_data)) {
				$address = isset($value_data['address']) ? $value_data['address'] : '';
				$lat = isset($value_data['lat']) ? $value_data['lat'] : '';
				$lng = isset($value_data['lng']) ? $value_data['lng'] : '';
			}
		}
		
		// Get Google Maps API key from settings or use a default
		$api_key = defined('GIFTFLOWWP_GOOGLE_MAPS_API_KEY') ? GIFTFLOWWP_GOOGLE_MAPS_API_KEY : '';
		?>
		<div class="giftflowwp-googlemap-field" id="<?php echo esc_attr( $map_id ); ?>">
			<!-- Hidden input to store location data -->
			<input type="hidden" name="<?php echo esc_attr( $this->name ); ?>" id="<?php echo esc_attr( $this->id ); ?>" value="<?php echo esc_attr( $value ); ?>" />
			
			<!-- Address input field -->
			<div class="giftflowwp-googlemap-address">
				<input type="text" class="giftflowwp-googlemap-address-input" placeholder="<?php esc_attr_e( 'Enter an address', 'giftflowwp' ); ?>" value="<?php echo esc_attr( $address ); ?>" />
				<button type="button" class="button giftflowwp-googlemap-search"><?php esc_html_e( 'Search', 'giftflowwp' ); ?></button>
			</div>
			
			<!-- Map container -->
			<div class="giftflowwp-googlemap-container" style="height: 300px; margin-top: 10px;"></div>
			
			<!-- Coordinates display -->
			<div class="giftflowwp-googlemap-coordinates">
				<p>
					<strong><?php esc_html_e( 'Latitude:', 'giftflowwp' ); ?></strong> <span class="giftflowwp-googlemap-lat"><?php echo esc_html( $lat ); ?></span>
					<strong><?php esc_html_e( 'Longitude:', 'giftflowwp' ); ?></strong> <span class="giftflowwp-googlemap-lng"><?php echo esc_html( $lng ); ?></span>
				</p>
			</div>
			
			<!-- JavaScript for Google Maps functionality -->
			<script type="text/javascript">
				jQuery(document).ready(function($) {
					var $mapField = $("#<?php echo esc_js( $map_id ); ?>");
					var $input = $mapField.find("input[type=hidden]");
					var $addressInput = $mapField.find(".giftflowwp-googlemap-address-input");
					var $searchButton = $mapField.find(".giftflowwp-googlemap-search");
					var $mapContainer = $mapField.find(".giftflowwp-googlemap-container");
					var $latDisplay = $mapField.find(".giftflowwp-googlemap-lat");
					var $lngDisplay = $mapField.find(".giftflowwp-googlemap-lng");
					
					var map, marker, geocoder;
					var defaultLat = <?php echo !empty($lat) ? $lat : '40.7128'; ?>;
					var defaultLng = <?php echo !empty($lng) ? $lng : '-74.0060'; ?>;
					var defaultZoom = <?php echo !empty($lat) && !empty($lng) ? '15' : '2'; ?>;
					
					// Initialize the map
					function initMap() {
						// Create map
						map = new google.maps.Map($mapContainer[0], {
							center: { lat: parseFloat(defaultLat), lng: parseFloat(defaultLng) },
							zoom: defaultZoom,
							mapTypeControl: true,
							streetViewControl: true,
							fullscreenControl: true
						});
						
						// Create geocoder
						geocoder = new google.maps.Geocoder();
						
						// Create marker
						marker = new google.maps.Marker({
							map: map,
							draggable: true,
							position: { lat: parseFloat(defaultLat), lng: parseFloat(defaultLng) }
						});
						
						// Add marker drag event
						marker.addListener('dragend', function() {
							updateLocationFromMarker();
						});
						
						// Add map click event
						map.addListener('click', function(event) {
							marker.setPosition(event.latLng);
							updateLocationFromMarker();
						});
						
						// If we have coordinates, reverse geocode to get the address
						if (defaultLat && defaultLng) {
							reverseGeocode({ lat: parseFloat(defaultLat), lng: parseFloat(defaultLng) });
						}
					}
					
					// Update location from marker position
					function updateLocationFromMarker() {
						var position = marker.getPosition();
						reverseGeocode(position);
					}
					
					// Reverse geocode to get address from coordinates
					function reverseGeocode(position) {
						geocoder.geocode({ location: position }, function(results, status) {
							if (status === 'OK') {
								if (results[0]) {
									$addressInput.val(results[0].formatted_address);
									updateLocationData(position.lat(), position.lng(), results[0].formatted_address);
								}
							}
						});
					}
					
					// Update location data
					function updateLocationData(lat, lng, address) {
						$latDisplay.text(lat);
						$lngDisplay.text(lng);
						
						var locationData = {
							lat: lat,
							lng: lng,
							address: address
						};
						
						$input.val(JSON.stringify(locationData));
					}
					
					// Search for address
					$searchButton.on('click', function() {
						var address = $addressInput.val();
						if (address) {
							geocoder.geocode({ address: address }, function(results, status) {
								if (status === 'OK') {
									var position = results[0].geometry.location;
									map.setCenter(position);
									map.setZoom(15);
									marker.setPosition(position);
									updateLocationData(position.lat(), position.lng(), results[0].formatted_address);
								} else {
									alert('<?php esc_html_e( 'Geocode was not successful for the following reason: ', 'giftflowwp' ); ?>' + status);
								}
							});
						}
					});
					
					// Allow pressing Enter in the address field to search
					$addressInput.on('keypress', function(e) {
						if (e.which === 13) {
							e.preventDefault();
							$searchButton.click();
						}
					});
					
					// Load Google Maps API and initialize the map
					if (typeof google === 'undefined') {
						var script = document.createElement('script');
						script.src = 'https://maps.googleapis.com/maps/api/js?key=<?php echo esc_js( $api_key ); ?>&callback=initMap';
						script.async = true;
						script.defer = true;
						document.head.appendChild(script);
						
						// Define the callback function
						window.initMap = initMap;
					} else {
						initMap();
					}
				});
			</script>
		</div><!-- End Google Maps field -->
		<?php
		
		// Return the buffered content
		return ob_get_clean();
	}

	/**
	 * Render repeater field
	 *
	 * @return string
	 */
	private function render_repeater() {
		// Start output buffering
		ob_start();

		// Get the repeater settings
		$repeater_settings = $this->repeater_settings;
		$fields = $repeater_settings['fields'];
		$button_text = $repeater_settings['button_text'];
		$remove_text = $repeater_settings['remove_text'];
		$min_rows = $repeater_settings['min_rows'];
		$max_rows = $repeater_settings['max_rows'];
		$row_label = $repeater_settings['row_label'];

		// Ensure value is an array
		$values = is_array($this->value) ? $this->value : array();
		if (empty($values) && $min_rows > 0) {
			// Initialize with empty rows if minimum rows required
			$values = array_fill(0, $min_rows, array());
		}

		// Generate a unique ID for the repeater
		$repeater_id = 'giftflowwp-repeater-' . $this->id;
		?>
		<div class="giftflowwp-repeater-field" id="<?php echo esc_attr($repeater_id); ?>">
			<!-- Hidden input to store all values -->
			<input type="hidden" name="<?php echo esc_attr($this->name); ?>" id="<?php echo esc_attr($this->id); ?>" value="<?php echo esc_attr(json_encode($values)); ?>" />
			
			<!-- Repeater rows container -->
			<div class="giftflowwp-repeater-rows">
				<?php foreach ($values as $row_index => $row_values): ?>
					<div class="giftflowwp-repeater-row" data-index="<?php echo esc_attr($row_index); ?>">
						<div class="giftflowwp-repeater-row-header">
							<span class="giftflowwp-repeater-row-title"><?php echo esc_html($row_label . ' ' . ($row_index + 1)); ?></span>
							<button type="button" class="button giftflowwp-repeater-remove-row"><?php echo esc_html($remove_text); ?></button>
						</div>
						<div class="giftflowwp-repeater-row-content">
							<?php foreach ($fields as $field_id => $field_args): 
								$field_value = isset($row_values[$field_id]) ? $row_values[$field_id] : '';
								$field_name = $this->name . '[' . $row_index . '][' . $field_id . ']';
								$field_id = $this->id . '_' . $row_index . '_' . $field_id;
								
								// Create field instance
								$field = new GiftFlowWP_Field(
									$field_id,
									$field_name,
									$field_args['type'],
									array_merge(
										$field_args,
										array(
											'value' => $field_value,
											'wrapper_classes' => array('giftflowwp-repeater-field'),
										)
									)
								);
								
								// Render the field
								echo $field->render();
							endforeach; ?>
						</div>
					</div>
				<?php endforeach; ?>
			</div>
			
			<!-- Add row button -->
			<button type="button" class="button giftflowwp-repeater-add-row" <?php echo ($max_rows > 0 && count($values) >= $max_rows) ? 'disabled' : ''; ?>>
				<?php echo esc_html($button_text); ?>
			</button>
			
			<!-- JavaScript for repeater functionality -->
			<script type="text/javascript">
				jQuery(document).ready(function($) {
					var $repeater = $("#<?php echo esc_js($repeater_id); ?>");
					var $rows = $repeater.find(".giftflowwp-repeater-rows");
					var $addButton = $repeater.find(".giftflowwp-repeater-add-row");
					var $hiddenInput = $repeater.find("input[type=hidden]");
					var maxRows = <?php echo intval($max_rows); ?>;
					var rowTemplate = <?php echo json_encode($this->get_repeater_row_template($fields)); ?>;
					
					// Add new row
					$addButton.on("click", function() {
						if (maxRows > 0 && $rows.children().length >= maxRows) {
							return;
						}
						
						var newIndex = $rows.children().length;
						var newRow = $(rowTemplate.replace(/__INDEX__/g, newIndex));
						$rows.append(newRow);
						
						// Update hidden input
						updateHiddenInput();
						
						// Disable add button if max rows reached
						if (maxRows > 0 && $rows.children().length >= maxRows) {
							$addButton.prop("disabled", true);
						}
					});
					
					// Remove row
					$repeater.on("click", ".giftflowwp-repeater-remove-row", function(e) {
						e.preventDefault();
						var $row = $(this).closest(".giftflowwp-repeater-row");
						$row.remove();
						
						// Update row indices
						$rows.find(".giftflowwp-repeater-row").each(function(index) {
							$(this).attr("data-index", index);
							$(this).find(".giftflowwp-repeater-row-title").text("<?php echo esc_js($row_label); ?> " + (index + 1));
							$(this).find("input, select, textarea").each(function() {
								var name = $(this).attr("name");
								name = name.replace(/\[\d+\]/, "[" + index + "]");
								$(this).attr("name", name);
							});
						});
						
						// Update hidden input
						updateHiddenInput();
						
						// Enable add button
						$addButton.prop("disabled", false);
					});
					
					// Update values when fields change
					$repeater.on("change", "input, select, textarea", function() {
						updateHiddenInput();
					});
					
					// Function to update hidden input with all values
					function updateHiddenInput() {
						var values = [];
						$rows.find(".giftflowwp-repeater-row").each(function() {
							var rowValues = {};
							$(this).find("input, select, textarea").each(function() {
								var name = $(this).attr("name");
								var matches = name.match(/\[(\d+)\]\[([^\]]+)\]/);
								if (matches) {
									var fieldId = matches[2];
									if ($(this).attr("type") === "checkbox" || $(this).attr("type") === "switch") {
										rowValues[fieldId] = $(this).is(":checked");
									} else {
										rowValues[fieldId] = $(this).val();
									}
								}
							});
							values.push(rowValues);
						});
						$hiddenInput.val(JSON.stringify(values));
					}
				});
			</script>
		</div>
		<?php
		
		// Return the buffered content
		return ob_get_clean();
	}

	/**
	 * Get repeater row template
	 *
	 * @param array $fields Fields configuration.
	 * @return string
	 */
	private function get_repeater_row_template($fields) {
		ob_start();
		?>
		<div class="giftflowwp-repeater-row" data-index="__INDEX__">
			<div class="giftflowwp-repeater-row-header">
				<span class="giftflowwp-repeater-row-title">
					<?php echo esc_html($this->repeater_settings['row_label']); ?> 
					<!-- __INDEX_PLUS_1__ -->
				</span>
				<button type="button" class="button giftflowwp-repeater-remove-row"><?php echo esc_html($this->repeater_settings['remove_text']); ?></button>
			</div>
			<div class="giftflowwp-repeater-row-content">
				<?php foreach ($fields as $field_id => $field_args): 
					$field_name = $this->name . '[__INDEX__][' . $field_id . ']';
					$field_id = $this->id . '___INDEX__' . $field_id;
					
					// Create field instance
					$field = new GiftFlowWP_Field(
						$field_id,
						$field_name,
						$field_args['type'],
						array_merge(
							$field_args,
							array(
								'value' => '',
								'wrapper_classes' => array('giftflowwp-repeater-field'),
							)
						)
					);
					
					// Render the field
					echo $field->render();
				endforeach; ?>
			</div>
		</div>
		<?php
		return ob_get_clean();
	}

	/**
	 * Get field value
	 *
	 * @return mixed
	 */
	public function get_value() {
		return $this->value;
	}

	/**
	 * Set field value
	 *
	 * @param mixed $value Field value.
	 */
	public function set_value( $value ) {
		$this->value = $value;
	}

	/**
	 * Get field ID
	 *
	 * @return string
	 */
	public function get_id() {
		return $this->id;
	}

	/**
	 * Get field name
	 *
	 * @return string
	 */
	public function get_name() {
		return $this->name;
	}

	/**
	 * Get field type
	 *
	 * @return string
	 */
	public function get_type() {
		return $this->type;
	}
}
