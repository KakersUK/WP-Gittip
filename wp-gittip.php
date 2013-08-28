<?php
/*
	Plugin Name: WP Gittip
	Plugin URI: http://daan.kortenba.ch/wp-gittip

	Description: WP Gittip allows you to display a Gittip widget on your site.

	Author: Daan Kortenbach
	Author URI: http://daan.kortenba.ch/

	Version: 1.0

	License: GNU General Public License v2.0 (or later)
	License URI: http://www.opensource.org/licenses/gpl-license.php
*/

add_action( 'widgets_init', 'wp_gittip_load_widget' );
/**
 * Register widget
 */
function wp_gittip_load_widget() {

	register_widget( 'WP_Gittip_Widget' );

}

/**
 * WP Gittip widget class.
 */
class WP_Gittip_Widget extends WP_Widget {

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
			'gittip_username' => '',
		);

		$widget_ops = array(
			'classname'   => 'wp-gittip',
			'description' => __( 'Displays a Gittip widget', 'wpgittip' ),
		);

		$control_ops = array(
			'id_base' => 'wp-gittip',
			'width'   => 200,
			'height'  => 250,
		);

		parent::__construct( 'wp-gittip', __( 'WP Gittip', 'wpgittip' ), $widget_ops, $control_ops );

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

			printf( '<script data-gittip-username="%s" src="https://www.gittip.com/assets/widgets/0002.js"></script>', $instance['gittip_username'] );

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
		$new_instance['gittip_username'] = strip_tags( $new_instance['gittip_username'] );

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
			<label for="<?php echo $this->get_field_id( 'title' ); ?>"><?php _e( 'Title', 'wpgittip' ); ?>:</label>
			<input type="text" id="<?php echo $this->get_field_id( 'title' ); ?>" name="<?php echo $this->get_field_name( 'title' ); ?>" value="<?php echo esc_attr( $instance['title'] ); ?>" class="widefat" />
		</p>

		<p>
			<label for="<?php echo $this->get_field_id( 'gittip_username' ); ?>"><?php _e( 'Gittip username', 'wpgittip' ); ?>:</label>
			<input type="text" id="<?php echo $this->get_field_id( 'gittip_username' ); ?>" name="<?php echo $this->get_field_name( 'gittip_username' ); ?>" value="<?php echo esc_attr( $instance['gittip_username'] ); ?>" class="widefat" />
		</p>

		<?php

	}

}
