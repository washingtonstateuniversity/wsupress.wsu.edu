<?php

class WSU_Press_Header_Link_Widget extends WP_Widget {

	/**
	 * Registers the widget officially through the parent class.
	 */
	public function __construct() {
		parent::__construct(
			'wsu_press_header_link',
			'Header Link',
			array(
				'description' => 'A link to display in the header.',
			)
		);
	}

	/**
	 * Displays the widget.
	 *
	 * @param array $args
	 * @param array $instance
	 */
	public function widget( $args, $instance ) {
		$default_instance = array(
			'url' => '',
			'label' => '',
		);

		$instance = shortcode_atts( $default_instance, $instance );

		?>
		<li>
			<a href="<?php echo esc_url( $instance['url'] ); ?>"><?php echo esc_html( $instance['label'] ); ?></a>
		</li>
		<?php
	}

	/**
	 * Displays the form used to update the widget.
	 *
	 * @param array $instance
	 *
	 * @return void
	 */
	public function form( $instance ) {
		$url = ! empty( $instance['url'] ) ? $instance['url'] : '';
		$label = ! empty( $instance['label'] ) ? $instance['label'] : '';

		?>
		<p>
			<label for="<?php echo esc_attr( $this->get_field_id( 'url' ) ); ?>">Link URL</label>
			<input id="<?php echo esc_attr( $this->get_field_id( 'url' ) ); ?>"
				   name="<?php echo esc_attr( $this->get_field_name( 'url' ) ); ?>"
				   type="text"
				   class="widefat"
				   value="<?php echo esc_attr( $url ); ?>" />
		</p>
		<p>
			<label for="<?php echo esc_attr( $this->get_field_id( 'label' ) ); ?>">Link Label</label>
			<input id="<?php echo esc_attr( $this->get_field_id( 'label' ) ); ?>"
				   name="<?php echo esc_attr( $this->get_field_name( 'label' ) ); ?>"
				   type="text"
				   class="widefat"
				   value="<?php echo esc_attr( $label ); ?>" />
		</p>
		<?php
	}

	/**
	 * Processes widget options on save.
	 *
	 * @param array $new_instance
	 * @param array $old_instance
	 *
	 * @return array
	 */
	public function update( $new_instance, $old_instance ) {
		$instance = array();
		$instance['url'] = ( ! empty( $new_instance['url'] ) ) ? esc_url_raw( $new_instance['url'] ) : '';
		$instance['label'] = ( ! empty( $new_instance['label'] ) ) ? sanitize_text_field( $new_instance['label'] ) : '';

		return $instance;
	}
}
