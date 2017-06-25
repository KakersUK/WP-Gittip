<?php
/*
 *	Plugin Name: WP-Gratipay
 *	Plugin URI: https://github.com/KakersUK/WP-Gratipay
 *	Description: WP-Gratipay allows you to display a Gratipay widget on your site.
 *	Version: 1.2
 *	Author: Jamie Scott
 *	Author URI: http://www.kakersuk.com/
 *	License: GPL2
 */

add_action( 'widgets_init', 'wp_gratipay_load_widget' );
add_shortcode('gratipay', 'wp_gratipay_shortcode');

/**
 * Register widget
 */
function wp_gratipay_load_widget() {

	register_widget( 'WP_Gratipay_Widget' );

}

function wp_gratipay_shortcode($atts) {

	$atts = shortcode_atts(
		array(
			'username' => '',
		), $atts);

	return '<script data-gratipay-username="' . esc_attr( $atts['username'] ) .'" src="//grtp.co/v1.js"></script>';

}

/**
 * WP-Gratipay widget class.
 */
class WP_Gratipay_Widget extends WP_Widget {

	/**
	 * Holds widget settings defaults, populated in constructor.
	 *
	 * @var array
	 */
	protected $defaults;

	/**
	 * Constructor. Set the default widget options and create widget.
	 */
	function __construct() {

		$this->defaults = array(
			'title'           => '',
			'gratipay_username' => '',
		);

		$widget_ops = array(
			'classname'   => 'wp-gratipay',
			'description' => __( 'Displays a Gratipay widget', 'wpgratipay' ),
		);

		$control_ops = array(
			'id_base' => 'wp-gratipay',
			'width'   => 200,
			'height'  => 250,
		);

		parent::__construct( 'wp-gratipay', __( 'WP Gratipay', 'wpgratipay' ), $widget_ops, $control_ops );

	}

	/**
	 * Echo the widget content.
	 *
	 * @param array $args Display arguments including before_title, after_title, before_widget, and after_widget.
	 * @param array $instance The settings for the particular instance of the widget
	 */
	function widget( $args, $instance ) {

		extract( $args );

		//* Merge with defaults
		$instance = wp_parse_args( (array) $instance, $this->defaults );

		echo $before_widget;

			if ( ! empty( $instance['title'] ) )
				echo $before_title . apply_filters( 'widget_title', $instance['title'], $instance, $this->id_base ) . $after_title;

			printf( '<script data-gratipay-username="%s" src="//grtp.co/v1.js"></script>', esc_attr( $instance['gratipay_username'] ) );

		echo $after_widget;

	}

	/**
	 * Update a particular instance.
	 *
	 * This function should check that $new_instance is set correctly.
	 * The newly calculated value of $instance should be returned.
	 * If "false" is returned, the instance won't be saved/updated.
	 *
	 * @param array $new_instance New settings for this instance as input by the user via form()
	 * @param array $old_instance Old settings for this instance
	 * @return array Settings to save or bool false to cancel saving
	 */
	function update( $new_instance, $old_instance ) {

		$new_instance['title']           = strip_tags( $new_instance['title'] );
		$new_instance['gratipay_username'] = strip_tags( $new_instance['gratipay_username'] );

		return $new_instance;

	}

	/**
	 * Echo the settings update form.
	 *
	 * @param array $instance Current settings
	 */
	function form( $instance ) {

		//* Merge with defaults
		$instance = wp_parse_args( (array) $instance, $this->defaults );

		?>
		<p>
			<label for="<?php echo $this->get_field_id( 'title' ); ?>"><?php _e( 'Title', 'wpgratipay' ); ?>:</label>
			<input type="text" id="<?php echo $this->get_field_id( 'title' ); ?>" name="<?php echo $this->get_field_name( 'title' ); ?>" value="<?php echo esc_attr( $instance['title'] ); ?>" class="widefat" />
		</p>

		<p>
			<label for="<?php echo $this->get_field_id( 'gratipay_username' ); ?>"><?php _e( 'Gratipay username', 'wpgratipay' ); ?>:</label>
			<input type="text" id="<?php echo $this->get_field_id( 'gratipay_username' ); ?>" name="<?php echo $this->get_field_name( 'gratipay_username' ); ?>" value="<?php echo esc_attr( $instance['gratipay_username'] ); ?>" class="widefat" />
		</p>

		<?php

	}

}
