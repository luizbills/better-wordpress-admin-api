<?php

if ( ! defined( 'WPINC' ) ) die;

class _WP_Field_Renderer {

	protected static $field_defaults = [
		'id' => '',
		'label' => '',
		'desc' => '',
		'default' => '',
		'before' => '',
		'after'  => '',
	];

	private function __construct () {
		// don't instanciable
	}

	public static function render_invalid_field ( $settings, $echo = true ) {
		ob_start();
		?>

		<pre><code>Invalid field declaration. <?php echo esc_html( $settings['error_message'] ); ?></code></pre>

		<?php
		$html = apply_filters( 'better_wp_admin_api_field_invalid_output', ob_get_clean(), $settings );
		if ( ! $echo ) {
			return $html;
		}
		echo $html;
	}

	public static function render_text_field ( $settings, $echo = true ) {
		$defaults = [
			'props' => [],
		];
		$settings = array_merge( self::$field_defaults, $defaults, $settings );
		$settings = apply_filters( 'better_wp_admin_api_field_text_settings', $settings );

		$settings['props'] = array_merge(
			[
				'type' => 'text',
				'class' => ''
			],
			$settings['props']
		);

		if ( empty( $settings['props']['class'] ) ) {
			// by default,
			// input[type=number] has .small-text class
			// all others text-based input has .regular-text class
			$settings['props']['class'] = $settings['props']['type'] === 'number' ? 'small-text' : 'regular-text';
		}

		$settings['props'] = array_merge(
			$settings['props'],
			[
				'id'     => $settings['id'],
				'name'   => $settings['id'],
				'value'  => $settings['value'],
			]
		);

		$html = '';

		$html .= self::get_html_template( $settings['before'], false, [ $settings ] );

		$html .= '<input ';

		foreach ( $settings['props'] as $prop => $value ) {
			if ( is_bool( $value ) && $value ) {
				$html .= $prop . ' ';
			} else {
				$html .= $prop . '="' . esc_attr( $value ) . '" ';
			}
		}

		$html .= ">";

		$html .= self::get_html_template( $settings['after'], false, [ $settings ] ) . "\n";

		if ( isset( $settings['desc'] ) ) {
			$html .= '<p class="description">' . self::render_description( $settings['desc'], true, false ) . '</p>';
		}

		$html = apply_filters( 'better_wp_admin_api_field_text_output', $html, $settings );
		if ( ! $echo ) {
			return $html;
		}
		echo $html;
	}

	public static function render_select_field ( $settings, $echo = true ) {
		$settings = array_merge( self::$field_defaults, $settings );
		$settings = apply_filters( 'better_wp_admin_api_field_select_settings', $settings );

		extract( $settings );
		ob_start();
		echo self::get_html_template( $settings['before'], false, [ $settings ] );
		?>
		<select name="<?php echo esc_attr( $id ); ?>" id="<?php echo esc_attr( $id ); ?>">

			<?php
			foreach( $choices as $choice_value => $choice_label ) {

				if ( is_array( $choice_label ) ) {
					echo '<optgroup label="' . esc_attr( $choice_value ) . '">';

					foreach( $choice_label as $child_value => $child_label ) {
						self::render_option_select( $child_label, $child_value, $value == $child_value );
					}

					echo '</optgroup>';
				} else {
					self::render_option_select( $choice_label, $choice_value, $value == $choice_value );
				}
			}
			?>
		</select>

		<?php
		echo self::get_html_template( $settings['after'], false, [ $settings ] );

		if ( ! empty( $desc ) ) : ?>
		<p class="description"><?php self::render_description( $desc ); ?></p>
		<?php endif; ?>
		<?php

		$html = apply_filters( 'better_wp_admin_api_field_select_output', ob_get_clean(), $settings );
		if ( ! $echo ) {
			return $html;
		}
		echo $html;
	}

	protected static function render_option_select ( $label, $value, $selected = false, $echo = true ) {
		ob_start();
		?>

		<option
			value="<?php echo esc_attr( $value ); ?>"
			<?php echo ( $selected ? 'selected="selected"' : '' ); ?>
		>
			<?php echo esc_html( $label ) ?>
		</option>

		<?php
		$html = ob_get_clean();
		if ( ! $echo ) {
			return $html;
		}
		echo $html;
	}

	public static function render_checkbox_field ( $settings, $echo = true ) {
		$settings = array_merge( self::$field_defaults, $settings );
		$settings = apply_filters( 'better_wp_admin_api_field_checkbox_settings', $settings );

		extract( $settings );
		ob_start();
		?>
		<fieldset>
			<legend class="screen-reader-text">
				<span><?php echo esc_html( $label ); ?></span>
			</legend>
			<label for="<?php echo esc_attr( $id ); ?>">
				<?php echo self::get_html_template( $settings['before'], false, [ $settings ] ); ?>
				<input
					type="checkbox"
					name="<?php echo esc_attr( $id ); ?>"
					id="<?php echo esc_attr( $id ); ?>"
					value="on"
					<?php checked( 'on', $value ); ?>
				>
				<?php echo self::get_html_template( $settings['after'], false, [ $settings ] ); ?>
				<?php if ( ! empty( $desc ) ) : ?>
				<p class="description"><?php self::render_description( $desc ); ?></p>
				<?php endif; ?>
			</label>
		</fieldset>
		<?php

		$html = apply_filters( 'better_wp_admin_api_field_checkbox_output', ob_get_clean(), $settings );
		if ( ! $echo ) {
			return $html;
		}
		echo $html;
	}

	public static function render_checkbox_multi_field ( $settings, $echo = true ) {
		$settings = array_merge( self::$field_defaults, $settings );
		$settings = apply_filters( 'better_wp_admin_api_field_checkbox_multi_settings', $settings );

		extract( $settings );

		if ( is_string( $value ) && strlen( $value ) > 0 ) {
			// make an array of default values
			$default_array = explode( ',', $default );
			$default_array = array_filter( array_map( 'trim', $default_array ) );
			$value = $default_array;
		}

		ob_start();
		?>

		<fieldset>
			<legend class="screen-reader-text">
				<span><?php echo esc_html( $label ); ?></span>
			</legend>
			<label for="<?php echo esc_attr( $id ); ?>">
				<?php echo self::get_html_template( $settings['before'], false, [ $settings ] ); ?>

				<div class="checkboxes">

					<?php foreach ( $choices as $choice_value => $choice_label ) : ?>

					<label>
					<input
						type="checkbox"
						name="<?php echo esc_attr( $id ); ?>[]"
						id="<?php echo esc_attr( $id . '-' . $choice_value ); ?>"
						value="<?php echo esc_attr( $choice_value ); ?>"
						<?php empty( $value ) ? '' : checked( in_array( $choice_value, $value ), true ); ?>
					>
					<?php echo self::get_html_template( $choice_label, false, [ $settings ] ); ?>
					</label>
					<br>

					<?php endforeach; ?>

				</div><!-- /.checkboxes -->
				<?php echo self::get_html_template( $settings['after'], false, [ $settings ] ); ?>
				<?php if ( ! empty( $desc ) ) : ?>
				<p class="description"><?php self::render_description( $desc ); ?></p>
				<?php endif; ?>
			</label>
		</fieldset>
		<?php

		$html = apply_filters( 'better_wp_admin_api_field_checkbox_multi_output', ob_get_clean(), $settings );
		if ( ! $echo ) {
			return $html;
		}
		echo $html;
	}

	public static function render_radio_field ( $settings, $echo = true ) {
		$settings = array_merge( self::$field_defaults, $settings );
		$settings = apply_filters( 'better_wp_admin_api_field_radio_settings', $settings );

		extract( $settings );
		ob_start();
		?>
		<fieldset>
			<legend class="screen-reader-text">
				<span><?php echo esc_html( $label ); ?></span>
			</legend>
			<p>
				<?php echo self::get_html_template( $settings['before'], false, [ $settings ] ); ?>

				<?php foreach( $choices as $choice_key => $choice_label ) : ?>

				<label>
					<input
						type="radio"
						name="<?php echo esc_attr( $id ); ?>"
						value="<?php echo esc_attr( $choice_key ); ?>"
						<?php checked( $choice_key, $value ); ?>
					>

					<?php echo self::get_html_template( $choice_label, false, [ $settings, $choice_key ] ); ?>
				</label>
				<br>

				<?php endforeach; ?>

				<?php echo self::get_html_template( $settings['after'], false, [ $settings ] ); ?>
			</p>
		</fieldset>

		<?php if ( ! empty( $desc ) ) : ?>
		<p class="description"><?php self::render_description( $desc ); ?></p>
		<?php endif; ?>

		<?php
		$html = apply_filters( 'better_wp_admin_api_field_radio_output', ob_get_clean(), $settings );
		if ( ! $echo ) {
			return $html;
		}
		echo $html;
	}

	public static function render_hidden_field ( $settings, $echo = true ) {
		$settings = array_merge( self::$field_defaults, $settings );
		$settings = apply_filters( 'better_wp_admin_api_field_hidden_settings', $settings );

		extract( $settings );
		ob_start();

		echo self::get_html_template( $settings['before'], false, [ $settings ] );
		?>

		<input
			type="hidden"
			name="<?php echo esc_attr( $id ); ?>"
			id="<?php echo esc_attr( $id ); ?>"
			value="<?php echo esc_attr( $value );?>"
		>

		<?php
		echo self::get_html_template( $settings['after'], false, [ $settings ] );

		$html = apply_filters( 'better_wp_admin_api_field_hidden_output', ob_get_clean(), $settings );
		if ( ! $echo ) {
			return $html;
		}
		echo $html;
	}

	public static function render_code_field ( $settings, $echo = true ) {
		$code_defaults = [
			'lang'              => 'text',
			'theme'             => 'eclipse',
			'height'            => 200,
			'font-size'         => 12,
			'tab-size'          => 4,
			'soft-tab'          => false,
			'read-only'         => false,
			'show-print-margin' => true
		];
		$settings = array_merge( self::$field_defaults, $code_defaults, $settings );
		$settings = apply_filters( 'better_wp_admin_api_field_code_settings', $settings );

		$data_props = '';
		foreach ( $code_defaults as $name => $__ ) {
			$data_props .= 'data-code-editor-' . esc_attr( $name ) . '="' . esc_attr( $settings[ $name ] ) . '" ';
		}

		extract( $settings );
		ob_start();
		?>
		<noscript>
			<div class="notice notice-error">
				<p><strong>
					<?php echo esc_html( 'Code editor field needs javascript to work.', BETTER_WP_ADMIN_API_DOMAIN ); ?>
				</strong></p>
			</div>
		</noscript>

		<?php echo self::get_html_template( $settings['before'], false, [ $settings ] ); ?>

		<div class="code-field-container">
			<textarea
				id="<?php echo esc_attr( $id ); ?>"
				name="<?php echo esc_attr( $id ); ?>"
				class="large-text code"
				style="height: <?php echo esc_attr( $height ); ?>px;font-size: <?php echo esc_attr( $settings['font-size'] ); ?>px;"
			><?php echo $value; ?></textarea>
			<div
				data-code-editor-for="<?php echo esc_attr( $id ); ?>"
				<?php echo $data_props; ?>
				class="code-editor"
				style="height: <?php echo esc_attr( $height ); ?>px;font-size: <?php echo esc_attr( $settings['font-size'] ); ?>px;"
			>
		</div>

		<?php echo self::get_html_template( $settings['after'], false, [ $settings ] ); ?>

		<?php if ( ! empty( $desc ) ) : ?>
		<p class="description"><?php self::render_description( $desc ); ?></p>
		<?php endif; ?>

		<?php
		$html = apply_filters( 'better_wp_admin_api_field_code_output', ob_get_clean(), $settings );
		if ( ! $echo ) {
			return $html;
		}
		echo $html;
	}

	public static function render_color_field ( $settings, $echo = true ) {
		$settings = array_merge( self::$field_defaults, $settings );
		$settings = apply_filters( 'better_wp_admin_api_field_color_settings', $settings );

		extract( $settings );
		ob_start();
		?>
		<?php echo self::get_html_template( $settings['before'], false, [ $settings ] ); ?>

		<div class="color-picker-field-container">
			<input
				type="text"
				class="field-color"
				name="<?php echo esc_attr( $id ); ?>"
				id="<?php echo esc_attr( $id ); ?>"
				value="<?php echo esc_attr( $value ); ?>"
				data-default-color="<?php echo esc_attr( $default ); ?>"
			>
		</div>

		<?php echo self::get_html_template( $settings['after'], false, [ $settings ] ); ?>

		<?php if ( ! empty( $desc ) ) : ?>
		<p class="description"><?php self::render_description( $desc ); ?></p>
		<?php endif;

		$html = apply_filters( 'better_wp_admin_api_field_color_output', ob_get_clean(), $settings );
		if ( ! $echo ) {
			return $html;
		}
		echo $html;
	}

	public static function render_html_field ( $settings, $echo = true ) {
		$defaults = [
			'content' => '<pre><code>Missing "content" argument.</code></pre>',
		];
		$settings = array_merge( self::$field_defaults, $defaults, $settings );
		$settings = apply_filters( 'better_wp_admin_api_field_html_settings', $settings );
		ob_start();
		echo self::get_html_template( $settings['content'], false, [ $settings, $settings['__PAGE__'] ] );
		if ( ! empty( $settings['desc'] ) ) :
		?>
		<p class="description"><?php self::render_description( $settings['desc'] ); ?></p>
		<?php
		endif;
		$html = apply_filters( 'better_wp_admin_api_field_html_output', ob_get_clean(), $settings );
		if ( ! $echo ) {
			return $html;
		}
		echo $html;
	}

	public static function render_content_field ( $settings, $echo = true ) {
		$defauls = [
			'height'  => 200,
			'wpautop' => true,
		];
		$settings = array_merge( self::$field_defaults, $defauls, $settings );
		$settings = apply_filters( 'better_wp_admin_api_field_content_settings', $settings );

		extract( $settings );
		ob_start();

		echo self::get_html_template( $settings['before'], false, [ $settings ] );

		wp_editor(
			$value,
			esc_attr( $id ),
			[
				'textarea_name' => esc_attr( $id ),
				'editor_height' => intval( $height ),
				'wpautop'       => boolval( $wpautop ),
			]
		);

		echo self::get_html_template( $settings['after'], false, [ $settings ] );

		if ( ! empty( $desc ) ) :
		?>
		<p class="description"><?php self::render_description( $desc ); ?></p>
		<?php
		endif;

		$html = apply_filters( 'better_wp_admin_api_field_content_output', ob_get_clean(), $settings );
		if ( ! $echo ) {
			return $html;
		}
		echo $html;
	}

	public static function render_image_field ( $settings, $echo = true ) {
		$defauls = [
			'field_button_upload' => 'Upload new image',
			'field_button_remove' => 'Remove image',
			'uploader_title'      => 'Upload a image',
			'uploader_button'     => 'Use this image',
		];
		$settings = array_merge( self::$field_defaults, $defauls, $settings );
		$settings = apply_filters( 'better_wp_admin_api_field_color_settings', $settings );

		extract( $settings );

		$image_data = wp_get_attachment_image_src( $value );
		$image_url = false !== $image_data ? $image_data[0] : '';

		ob_start();

		echo self::get_html_template( $settings['before'], false, [ $settings ] );

		?>
		<div class="image-field-container">
			<img
				id="<?php echo esc_attr( $id ); ?>_preview"
				class="image_preview"
				src="<?php echo esc_url( $image_url ); ?>"
			>
			<button
				type="button"
				data-uploader_title="<?php echo esc_attr( $uploader_title ); ?>"
				data-uploader_button_text="<?php echo esc_attr( $uploader_button ); ?>"
				class="image_upload_button button"
			><?php echo esc_html( $field_button_upload ); ?></button>
			<button
				type="button"
				class="image_delete_button button"
			><?php echo esc_html( $field_button_remove ); ?></button>
			<input
				id="<?php echo esc_attr( $id ); ?>"
				class="image_data_field"
				type="hidden"
				name="<?php echo esc_attr( $id ); ?>"
				value="<?php echo esc_attr( $value ); ?>"
			>
		</div>

		<?php echo self::get_html_template( $settings['after'], false, [ $settings ] ); ?>

		<?php if ( ! empty( $settings['desc'] ) ) : ?>

		<p class="description"><?php self::render_description( $settings['desc'] ); ?></p>

		<?php endif;

		$html = apply_filters( 'better_wp_admin_api_field_image_output', ob_get_clean(), $settings );
		if ( ! $echo ) {
			return $html;
		}
		echo $html;
	}

	public static function render_description ( $text, $use_markdown = true, $echo = true ) {
		$use_markdown = apply_filters( 'better_wp_admin_api_render_description_use_markdown', $use_markdown, $text );
		$text = apply_filters( 'better_wp_admin_api_render_description_text', $text );
		if ( $use_markdown ) {
			$Parsedown = new Parsedown();
			$text = $Parsedown->line( $text );
		}
		$output = self::get_html_template( $text, true );
		$output = apply_filters( 'better_wp_admin_api_render_description_output', $output, $text, $use_markdown );
		if ( ! $echo ) {
			return $output;
		}
		echo $output;
	}

	public static function get_html_template ( $content, $safe = false, $args = [] ) {
		$html = $content;
		if ( is_callable( $content ) ) {
			ob_start();
			call_user_func_array( $content, $args );
			$html = ob_get_clean();
		}
		return ( ! $safe ? $html : wp_kses_post( $html ) );
	}
}
