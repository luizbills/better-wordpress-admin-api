<?php

if ( ! defined( 'WPINC' ) ) die;

class _WP_Admin_Metabox {

	public $settings = null;

	protected $fields = [];

	protected static $metaboxes_created = [];

	public function __construct ( $settings = [] ) {
		$this->settings = $this->validate_settings( $settings );
		$this->init();
		$this->hooks();
	}

	protected function validate_settings ( $settings ) {
		$defaults = [
			// required
			// Unique ID of the menu item
			'id' => null,
			// Title of the metabox
			'title' => null,

			// optionals
			// description
			'desc' => '',
			// prefix to all fields of this page
			'prefix' => '',
			// User role
			'capability' => 'manage_options',
			// allowed post types
			'post_type' => [ 'post' ],
			// other arguments
			'context' => 'advanced',
			'position' => 'default', // priority
		];
		$result = array_merge( $defaults, $settings );

		if ( empty( $result['id'] ) || empty( $result['title'] ) ) {
			throw new Exception( 'A metabox requires an id and menu_name' );
		}

		return $result;
	}

	protected function init () {
		self::$metaboxes_created[ $this->settings['id'] ] = $this;
	}

	protected function hooks () {
		add_action( 'add_meta_boxes' , [ $this, 'add_meta_box' ] );
		add_action( 'save_post', [ $this, 'save_meta_box' ] );

		add_action( 'admin_enqueue_scripts', [ $this, 'enqueue_assets' ] );
	}

	public function add_meta_box () {
		$post_types = is_array( $this->settings['post_type'] ) ? $this->settings['post_type'] : [ $this->settings['post_type'] ];

		foreach ( $post_types as $post_type ) {
			add_meta_box(
				$this->settings['id'],
				$this->settings['title'],
				[ $this, 'render_meta_box'],
				$post_type,
				$this->settings['context'],
				$this->settings['position']
			);
		}
	}

	public function render_meta_box ( $post ) {
		foreach( $this->fields as $id => $field ) {
			$wrapper_class = $field['wrapper_class'] . ' metabox-field';
			?>
			<div id="<?php echo esc_attr( $id ); ?>" class="<?php echo esc_attr( $wrapper_class ); ?>">
				<label for="<?php echo esc_attr ($id ); ?>">
					<strong><?php echo esc_html( $field['label'] ); ?></strong>
				</label>
				<?php $this->render_field( $field ); ?>
			</div>
			<?php
		}
	}

	protected function render_field ( $field_settings ) {
		$field_type = $field_settings['type'];

		$field_settings['value'] = $this->get_field_value( $field_settings['id'] );

		$renderer = "_WP_Field_Renderer::render_${field_type}_field";

		if ( is_callable( $renderer ) ) {
			call_user_func( $renderer, $field_settings );
		} else {
			// used to made custom fields
			$field_settings = apply_filters( "better_wp_admin_api_field_${field_type}_settings", $field_settings );
			$field_output = apply_filters( "better_wp_admin_api_field_${field_type}_output", false, $field_settings );

			if ( $field_output !== false ) {
				// print a custom field
				echo $field_output;
			} else {
				// print an undefined field notice
				echo "<pre><code>Undefined \"${field_type}\" field type.</code></pre>";
			}
		}
	}

	public function save_meta_box ( $post_id ) {
		if ( defined( 'DOING_AUTOSAVE' ) && DOING_AUTOSAVE ) return;

		foreach( $this->fields as $id => $field ) {
			if ( isset( $_POST[ $id ] ) ) {
				$value = $_POST[ $id ];
				$sanitize_callback = isset( $field['sanitize_callback'] ) ? $field['sanitize_callback'] : false;
				if ( is_callable( $sanitize_callback ) ) {
					$value = call_user_func( $sanitize_callback, $value, $id, this );
				}
				update_post_meta( $post_id, $id, $_POST[ $id ] );
			}
		}
	}

	public function enqueue_assets () {
		$script_suffix = defined( 'WP_DEBUG_SCRIPT' ) && WP_DEBUG_SCRIPT ? '' : '.min';
		$has_code_field = defined( 'WP_ADMIN_PAGE_HAS_CODE_FIELD' );
		$has_color_field = defined( 'WP_ADMIN_PAGE_HAS_COLOR_FIELD' );
		$has_image_field = defined( 'WP_ADMIN_PAGE_HAS_IMAGE_FIELD' );

		if ( $has_color_field ) {
			// required for 'color' field type
			wp_enqueue_style( 'wp-color-picker' );
		}

		if ( $has_image_field ) {
			// required for 'image' field type
			wp_enqueue_media();
		}

		if ( $has_code_field ) {
			// required for 'code' field type
			wp_enqueue_script(
				'ace-editor',
				_WP_Admin_API::get_asset_file_url( 'vendor/ace/src-min-noconflict/ace.js' ),
				[],
				'1.3.1',
				true
			);
		}

		// required for 'code' field type
		$deps = [ 'jquery' ];
		if ( $has_code_field ) $deps[] = 'ace-editor';
		if ( $has_color_field ) $deps[] = 'wp-color-picker';

		wp_enqueue_script(
			'better-wp-admin-api-fields',
			_WP_Admin_API::get_asset_file_url( 'js/admin/fields' . $script_suffix . '.js' ),
			$deps,
			BETTER_WP_ADMIN_API_VERSION,
			true
		);

		// fields css
		wp_enqueue_style(
			'better-wp-admin-api-fields',
			_WP_Admin_API::get_asset_file_url( 'css/admin/fields' . $script_suffix . '.css' )
		);
	}

	public function add_field ( $data ) {
		$defaults = [
			'id'                => '',
			'type'              => 'text',
			'sanitize_callback' => '',
			'default'           => false,
			'wrapper_class'     => '',
		];
		$data = array_merge( $defaults, $data );

		if ( empty ( $data['id'] ) ) {
			$data['id'] = '__invalid__' . rand( 0, 2147483647 );
			$data['type'] = '__invalid__';
			$data['error_message'] = 'All fields requires a "id" parameter.';
		} else {
			$data['id'] = $this->prefix_field_name( $data['id'] );

			if ( ! defined( 'WP_ADMIN_PAGE_HAS_' . strtoupper( $data['type'] ) . '_FIELD' ) ) {
				define( 'WP_ADMIN_PAGE_HAS_' . strtoupper( $data['type'] ) . '_FIELD', true );
			}
		}

		if ( empty( $data['label'] ) ) {
			$data['label'] = $data['id'];
		}

		// add 'hidden_class' to fields of type hidden
		$data['wrapper_class'] .= $data['type'] === 'hidden' ? ' hidden_field' : '';

		// special keys
		$data['__META_BOX__'] = $this;
		$data['unprefixed_id'] = $data['id'];

		// store the field
		$this->fields[ $data['id'] ] = $data;

		// return the prefixed input name
		return $data['id'];
	}

	public function get_field_value ( $field_id, $post_id = null ) {
		$value = '';
		$field = null;

		if ( empty( $post_id ) ) {
			$post_id = get_the_ID();
		}

		if ( ! empty( $post_id ) ) {
			$possible_ids = [ $field_id, $this->prefix_field_name( $field_id ) ];

			foreach ( $possible_ids as $_id ) {
				if ( isset( $this->fields[ $_id ] ) ) {
					$field = $this->fields[ $_id ];
					$field_id = $_id;
					break;
				}
			}
		}

		if ( ! empty( $field ) ) {
			$value = get_post_meta( $post_id, $field_id, true );
			if ( false === $value ) {
				$value = apply_filters( 'better_wp_admin_api_metabox_field_default_value', $field['default'], $field, $this );
			} elseif ( empty( $value ) && ! is_numeric( $value ) ) {
				$value = '';
			}
		}

		return $value;
	}

	public function prefix_field_name ( $field_id ) {
		return ( ! empty( $this->settings['prefix'] ) )
			? $this->settings['prefix'] . $field_id
			: $field_id;
	}

	public function unprefix_field_name ( $field_id ) {
		if ( ! empty( $this->settings['prefix'] ) ) {
			$unprefixed = str_replace( $this->settings['prefix'], '', $field_id );

			if ( $unprefixed === '' ) {
				$unprefixed = $this->settings['prefix'] . $this->settings['prefix'];
			}

			return $unprefixed;
		}
		return $field_id;
	}

	protected function fix_field_name ( $field_id ) {
		return $this->prefix_field_name( $this->unprefix_field_name( $field_id ) );
	}

	public static function get_instance_by_id ( $page_id ) {
		if ( ! empty( self::$pages_created[ $page_id ] ) ) {
			return self::$pages_created[ $page_id ];
		}
		return false;
	}
}
