<?php

class SimpleGoogleCalendar_Widget extends WP_Widget {

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
			'title'    => '',
			'id'       => 'en.usa#holiday@group.v.calendar.google.com^#333333',
			'timezone' => 'America%2FNew_York',
			'mode'     => 'agenda',
			'height'   => 300,
		);

		$widget_ops = array(
			'classname'   => 'simplegooglecalendar',
			'description' => __( 'Display a Google Calendar feed.', 'simple-google-calendar' ),
		);

		$control_ops = array(
			'id_base' => 'simplegooglecalendar',
			'width'   => 200,
			'height'  => 250,
		);

		parent::__construct( 'simplegooglecalendar', __( 'Simple Google Calendar', 'simple-google-calendar' ), $widget_ops, $control_ops );

	}

	/**
	 * Echo the widget content.
	 *
	 * @param array $args Display arguments including before_title, after_title, before_widget, and after_widget.
	 * @param array $instance The settings for the particular instance of the widget
	 */
	function widget( $args, $instance ) {

		// Merge with defaults
		$instance = wp_parse_args( (array) $instance, $this->defaults );

		if ( ! $instance['id'] ) {
			return;
		}

		echo wp_kses_post( $args['before_widget'] );

		if ( ! empty( $instance['title'] ) ) {
			echo wp_kses_post( $args['before_title'] . apply_filters( 'widget_title', $instance['title'], $instance, $this->id_base ) . $args['after_title'] );
		}

		$simplegooglecalendar = new SimpleGoogleCalendar();
		$source = 'src=' . $simplegooglecalendar->calendar_replace( $instance['id'] ) . '&amp;';
		$timezone = $simplegooglecalendar->calendar_replace( $instance['timezone'] );
		$class  = 'embed-calendar';
		$mode   = 'mode=AGENDA&amp;';
		if ( 'month' === $instance['mode'] ) {
			$mode = '';
		} elseif ( 'week' === $instance['mode'] ) {
			$mode = 'mode=WEEK&amp;';
		}
		echo '<div class="' . esc_attr( $class ) . '"><iframe src="https://www.google.com/calendar/embed?showTitle=0&amp;showNav=0&amp;showPrint=0&amp;showTabs=0&amp;showCalendars=0&amp;showTz=0&amp;' . esc_attr( $mode ) . 'height=' . esc_attr( $instance['height'] ) . '&amp;wkst=1&amp;bgcolor=%23FFFFFF&amp;' . esc_attr( $source ) . 'ctz=' . esc_attr( $timezone ) . '" style=" border-width:0 " width="100%" height="' . esc_attr( $instance['height'] ) . '" frameborder="0" scrolling="no"></iframe></div>';

		echo wp_kses_post( $args['after_widget'] );

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

		$new_instance['title']  = strip_tags( $new_instance['title'] );
		$new_instance['id']     = sanitize_text_field( $new_instance['id'] );
		$new_instance['mode']   = sanitize_text_field( $new_instance['mode'] );
		$new_instance['height'] = (int) $new_instance['height'];

		return $new_instance;

	}

	/**
	 * Echo the settings update form.
	 *
	 * @param array $instance Current settings
	 */
	function form( $instance ) {

		// Merge with defaults
		$instance = wp_parse_args( (array) $instance, $this->defaults );

		?>
		<p>
			<label for="<?php echo esc_attr( $this->get_field_id( 'title' ) ); ?>"><?php esc_attr_e( 'Title', 'simple-google-calendar' ); ?>:</label>
			<input type="text" id="<?php echo esc_attr( $this->get_field_id( 'title' ) ); ?>" name="<?php echo esc_attr( $this->get_field_name( 'title' ) ); ?>" value="<?php echo esc_attr( $instance['title'] ); ?>" class="widefat" />
		</p>

		<p>
			<label for="<?php echo esc_attr( $this->get_field_id( 'id' ) ); ?>"><?php esc_attr_e( 'Calendar ID', 'simple-google-calendar' ); ?>:</label>
			<textarea id="<?php echo esc_attr( $this->get_field_id( 'id' ) ); ?>" name="<?php echo esc_attr( $this->get_field_name( 'id' ) ); ?>" value="<?php echo esc_attr( $instance['id'] ); ?>" class="widefat" rows="3"><?php echo esc_attr( $instance['id'] ); ?></textarea>
		</p>

		<p>
			<label for="<?php echo esc_attr( $this->get_field_id( 'timezone' ) ); ?>"><?php esc_attr_e( 'Time Zone', 'simple-google-calendar' ); ?>:</label>
			<input type="text" id="<?php echo esc_attr( $this->get_field_id( 'timezone' ) ); ?>" name="<?php echo esc_attr( $this->get_field_name( 'timezone' ) ); ?>" value="<?php echo esc_attr( $instance['timezone'] ); ?>" class="widefat" />
		</p>

		<p>
			<label for="<?php echo esc_attr( $this->get_field_id( 'mode' ) ); ?>"><?php esc_attr_e( 'Calendar View', 'simple-google-calendar' ); ?>:</label>
			<select id="<?php echo esc_attr( $this->get_field_name( 'mode' ) ); ?>" name="<?php echo esc_attr( $this->get_field_name( 'mode' ) ); ?>" class="widefat">
				<option value="agenda" <?php selected( 'agenda', $instance['mode'] ); ?>><?php esc_attr_e( 'List View', 'simple-google-calendar' ); ?></option>
				<option value="month" <?php selected( 'month', $instance['mode'] ); ?>><?php esc_attr_e( 'Month View', 'simple-google-calendar' ); ?></option>
				<option value="week" <?php selected( 'week', $instance['mode'] ); ?>><?php esc_attr_e( 'Week View', 'simple-google-calendar' ); ?></option>
			</select>
		</p>
		<p>
			<label for="<?php echo esc_attr( $this->get_field_id( 'height' ) ); ?>"><?php esc_attr_e( 'Height', 'simple-google-calendar' ); ?>:</label>
			<input type="number" id="<?php echo esc_attr( $this->get_field_id( 'height' ) ); ?>" name="<?php echo esc_attr( $this->get_field_name( 'height' ) ); ?>" value="<?php echo esc_attr( $instance['height'] ); ?>" class="widefat" />
		</p>
		<?php
	}

}
