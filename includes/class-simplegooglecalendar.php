<?php

class SimpleGoogleCalendar {

	protected $settings;

	public function __construct( $settings ) {
		$this->settings = $settings;
	}

	public function run() {
		add_action( 'admin_menu', array( $this->settings, 'do_submenu_page' ) );
		add_shortcode( 'simplegooglecalendar', array( $this, 'calendar_embed' ) );
		add_action( 'wp_head', array( $this, 'css' ) );
		add_action( 'widgets_init', array( $this, 'register_widget' ) );
	}

	function calendar_embed( $atts ) {

		if ( is_feed() ) {
			return wpautop( apply_filters( 'simplegooglecalendar_feed_notice', __( 'A Google calendar has been added to this post. Please visit the site directly to see it.', 'simple-google-calendar' ) ) );
		}

		$attributes = shortcode_atts( array(
			'id'       => 'en.usa#holiday@group.v.calendar.google.com^#333333',
			'timezone' => 'America/New_York',
			'mode'     => '',
			'height'   => 600,
			'tabs'     => 0,
		), $atts );
		foreach ( $attributes as $attribute => $id ) {
			if ( 'id' === $attribute ) {
				$attributes['id'] = 'src=' . $this->calendar_replace( $id ) . '&amp;';
			}
			if ( 'timezone' === $attribute ) {
				$attributes['timezone'] = 'src=' . $this->calendar_replace( $id ) . '&amp;';
			}
		}
		$class = 'embed-calendar';
		$mode  = '';
		if ( 'agenda' === $attributes['mode'] ) {
			$mode = 'mode=AGENDA&amp;';
		}
		if ( 'week' === $attributes['mode'] ) {
			$mode = 'mode=WEEK&amp;';
		}
		if ( 'both' === $attributes['mode'] ) {
			$class  = 'desktop-calendar';
			$mobile = 'mobile-calendar';
		}
		$tabs = 'true' === $attributes['tabs'] ? 1 : 0;
		$calendar = '<div class="' . $class . '"><iframe src="https://www.google.com/calendar/embed?showTitle=0&amp;showTabs=' . $tabs . '&amp;showTz=0&amp;' . $mode . 'height=' . $attributes['height'] . '&amp;wkst=1&amp;bgcolor=%23FFFFFF&amp;' . $attributes['id'] . 'ctz=' . $attributes['timezone'] . '" style=" border-width:0 " width="100%" height="' . $attributes['height'] . '" frameborder="0" scrolling="no"></iframe></div>';
		if ( 'both' === $attributes['mode'] ) {
			$calendar .= '<div class="' . $mobile . '"><iframe src="https://www.google.com/calendar/embed?showTitle=0&amp;showTabs=0&amp;showTz=0&amp;mode=AGENDA&amp;height=' . $attributes['height'] . '&amp;wkst=1&amp;bgcolor=%23FFFFFF&amp;' . $attributes['id'] . 'ctz=' . $attributes['timezone'] . '" style=" border-width:0 " width="100%" height="' . $attributes['height'] . '" frameborder="0" scrolling="no"></iframe></div>';
		}
		return $calendar;
	}

	public function calendar_replace( $string ) {
		return str_replace(
		 	array( '&', '@', '#', ',', '^', '/' ),
		 	array( '&amp;', '%40', '%23', '&amp;src=', '&amp;color=', '%2F' ),
		 	$string
		);
	}

	public function css() {
		$css = '
			.desktop-calendar { display: block; }
			.mobile-calendar { display: none; }
			@media only screen and (max-width: 960px) {
				.desktop-calendar { display: none; }
				.mobile-calendar { display: block; }
			}';
		$css = apply_filters( 'simplegooglecalendar_inline_css', $css );
		$css = str_replace( "\t", '', $css );
		$css = str_replace( array( "\n", "\r" ), ' ', $css );

		// Echo the CSS
		printf( '<style type="text/css" media="screen">%s</style>', esc_attr( $css ) );

	}

	public function register_widget() {
		register_widget( 'SimpleGoogleCalendar_Widget' );
	}
}
