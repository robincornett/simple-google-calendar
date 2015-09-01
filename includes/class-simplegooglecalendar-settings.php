<?php
/**
 * Simple Google Calendar
 *
 * @package   SimpleGoogleCalendar
 * @author    Robin Cornett <hello@robincornett.com>
 * @link      https://github.com/robincornett/simple-google-calendar
 * @copyright 2015 Robin Cornett
 * @license   GPL-2.0+
 */

/**
 * Class for adding a new settings page to the WordPress admin, under Settings.
 *
 * @package SimpleGoogleCalendar
 */
class SimpleGoogleCalendar_Settings {

	/**
	 * Option registered by plugin.
	 * @var array
	 */
	protected $setting;

	/**
	 * Slug for settings page.
	 * @var string
	 */
	protected $page = 'simplegooglecalendar';

	/**
	 * Settings fields registered by plugin.
	 * @var array
	 */
	protected $fields;

	/**
	 * add a submenu page under Settings
	 * @return submenu Simple Google Calendar settings page
	 * @since  x.y.z
	 */
	public function do_submenu_page() {

		add_options_page(
			__( 'Simple Google Calendar Settings', 'simple-google-calendar' ),
			__( 'Simple Google Calendar', 'simple-google-calendar' ),
			'manage_options',
			$this->page,
			array( $this, 'do_settings_form' )
		);

		add_action( 'admin_init', array( $this, 'register_settings' ) );
		add_action( 'load-settings_page_simplegooglecalendar', array( $this, 'help' ) );

	}

	/**
	 * Output the plugin settings form.
	 *
	 * @since x.y.z
	 */
	public function do_settings_form() {

		$this->setting = $this->get_setting();

		echo '<div class="wrap">';
			echo '<h1>' . esc_attr( get_admin_page_title() ) . '</h1>';
			echo '<form action="options.php" method="post">';
				settings_fields( 'simplegooglecalendar' );
				do_settings_sections( 'simplegooglecalendar' );
				wp_nonce_field( 'simplegooglecalendar_save-settings', 'simplegooglecalendar_nonce', false );
				submit_button();
			echo '</form>';
		echo '</div>';

	}

	/**
	 * Add new fields to wp-admin/options-general.php?page=simplegooglecalendar
	 *
	 * @since x.y.z
	 */
	public function register_settings() {

		register_setting( 'simplegooglecalendar', 'simplegooglecalendar', array( $this, 'do_validation_things' ) );

		$this->register_sections();

	}

	protected function get_setting() {

		$defaults = array(
			'color'     => '#c00', // CHANGE THIS
			'time_zone' => 'America/New_York',
		);

		return get_option( 'simplegooglecalendar', $defaults );
	}

	/**
	 * Register sections for settings page.
	 *
	 * @since x.y.z
	 */
	protected function register_sections() {

		$sections = array(
			'general' => array(
				'id'    => 'general',
				'title' => __( 'General Plugin Settings', 'simple-google-calendar' ),
			),
		);

		foreach ( $sections as $section ) {
			add_settings_section(
				$section['id'],
				$section['title'],
				array( $this, $section['id'] . '_section_description' ),
				$this->page
			);
		}

		$this->register_fields( $sections );

	}

	/**
	 * Register settings fields
	 * @param  settings sections $sections
	 * @return fields           settings fields
	 *
	 * @since x.y.z
	 */
	protected function register_fields( $sections ) {

		$this->fields = array(
			array(
				'id'       => 'color',
				'title'    => __( 'Default Color', 'simple-google-calendar' ),
				'callback' => 'do_text_field',
				'section'  => 'general',
				'args'     => array( 'setting' => 'color', 'label' => __( 'Convert galleries only; do not fix feeds for email.', 'simple-google-calendar' ) ),
			),
			array(
				'id'       => 'time_zone',
				'title'    => __( 'Default Time Zone', 'simple-google-calendar' ),
				'callback' => 'do_text_field',
				'section'  => 'general',
				'args'     => array( 'setting' => 'time_zone', 'label' => __( 'This is the default time zone for your calendar.', 'simple-google-calendar' ) ),
			),
		);

		foreach ( $this->fields as $field ) {
			add_settings_field(
				'[' . $field['id'] . ']',
				sprintf( '<label for="%s">%s</label>', $field['id'], $field['title'] ),
				array( $this, $field['callback'] ),
				$this->page,
				$sections[ $field['section'] ]['id'],
				empty( $field['args'] ) ? array() : $field['args']
			);
		}
	}

	/**
	 * Callback for general plugin settings section.
	 *
	 * @since x.y.z
	 */
	public function general_section_description() {
		$description = __( 'The <em>Simple Google Calendar</em> plugin works out of the box without changing any settings. However, you may want to tweak some things.', 'simple-google-calendar' );
		printf( '<p>%s</p>', wp_kses_post( $description ) );
	}

	/**
	 * Generic callback to create a checkbox setting.
	 *
	 * @since x.y.z
	 */
	public function do_checkbox( $args ) {
		printf( '<input type="hidden" name="%s[%s]" value="0" />', esc_attr( $this->page ), esc_attr( $args['setting'] ) );
		printf( '<label for="%1$s[%2$s]"><input type="checkbox" name="%1$s[%2$s]" id="%1$s[%2$s]" value="1" %3$s class="code" />%4$s</label>',
			esc_attr( $this->page ),
			esc_attr( $args['setting'] ),
			checked( 1, esc_attr( $this->setting[ $args['setting'] ] ), false ),
			esc_attr( $args['label'] )
		);
		$this->do_description( $args['setting'] );
	}

	/**
	 * Generic callback to create a number field setting.
	 *
	 * @since x.y.z
	 */
	public function do_number( $args ) {

		printf( '<label for="%s[%s]">%s</label>', esc_attr( $this->page ),esc_attr( $args['setting'] ), esc_attr( $args['label'] ) );
		printf( '<input type="number" step="1" min="%1$s" max="%2$s" id="%5$s[%3$s]" name="%5$s[%3$s]" value="%4$s" class="small-text" />',
			(int) $args['min'],
			(int) $args['max'],
			esc_attr( $args['setting'] ),
			esc_attr( $this->setting[ $args['setting'] ] ),
			esc_attr( $this->page )
		);
		$this->do_description( $args['setting'] );

	}

	/**
	 * Generic callback to create a select/dropdown setting.
	 *
	 * @since x.y.z
	 */
	public function do_select( $args ) {
		$function = 'pick_' . $args['options'];
		$options  = $this->$function(); ?>
		<select id="simplegooglecalendar[<?php echo esc_attr( $args['setting'] ); ?>]" name="simplegooglecalendar[<?php echo esc_attr( $args['setting'] ); ?>]">
			<?php
			foreach ( (array) $options as $name => $key ) {
				printf( '<option value="%s" %s>%s</option>', esc_attr( $name ), selected( $name, $this->setting[ $args['setting'] ], false ), esc_attr( $key ) );
			} ?>
		</select> <?php
	}

	/**
	 * Generic callback to create a text field.
	 *
	 * @since x.y.z
	 */
	public function do_text_field( $args ) {
		printf( '<input type="text" id="%3$s[%1$s]" name="%3$s[%1$s]" value="%2$s" class="regular-text" />', esc_attr( $args['setting'] ), esc_attr( $this->setting[ $args['setting'] ] ), esc_attr( $this->page ) );
		$this->do_description( $args['setting'] );
	}

	/**
	 * Generic callback to display a field description.
	 * @param  string $args setting name used to identify description callback
	 * @return string       Description to explain a field.
	 */
	protected function do_description( $args ) {
		$function = $args . '_description';
		if ( ! method_exists( $this, $function ) ) {
			return;
		}
		$description = $this->$function();
		printf( '<p class="description">%s</p>', wp_kses_post( $description ) );
	}

	/**
	 * Validate all settings.
	 * @param  array $new_value new values from settings page
	 * @return array            validated values
	 *
	 * @since x.y.z
	 */
	public function do_validation_things( $new_value ) {

		if ( empty( $_POST['simplegooglecalendar_nonce'] ) ) {
			wp_die( esc_attr__( 'Something unexpected happened. Please try again.', 'simple-google-calendar' ) );
		}

		check_admin_referer( 'simplegooglecalendar_save-settings', 'simplegooglecalendar_nonce' );

		foreach ( $this->fields as $field ) {
			if ( 'do_checkbox' === $field['callback'] ) {
				$new_value[ $field['id'] ] = $this->one_zero( $new_value[ $field['id'] ] );
			} elseif ( 'do_select' === $field['callback'] ) {
				$new_value[ $field['id'] ] = esc_attr( $new_value[ $field['id'] ] );
			} elseif ( 'do_number' === $field['callback'] ) {
				$new_value[ $field['id'] ] = (int) $new_value[ $field['id'] ];
			} elseif ( 'do_text_field' === $field['callback'] ) {
				$new_value[ $field['id'] ] = sanitize_text_field( $field['id'] );
			}
		}

		return $new_value;

	}

	/**
	 * Returns a 1 or 0, for all truthy / falsy values.
	 *
	 * Uses double casting. First, we cast to bool, then to integer.
	 *
	 * @since x.y.z
	 *
	 * @param mixed $new_value Should ideally be a 1 or 0 integer passed in
	 * @return integer 1 or 0.
	 */
	protected function one_zero( $new_value ) {
		return (int) (bool) $new_value;
	}

	/**
	 * Help tab for settings screen
	 * @return help tab with verbose information for plugin
	 *
	 * @since x.y.z
	 */
	public function help() {
		$screen = get_current_screen();

		$general_help  = '<h3>' . __( 'RSS Image Size', 'simple-google-calendar' ) . '</h3>';
		$general_help .= '<p>' . __( 'If you have customized your emails to be a nonstandard width, or you are using a template with a sidebar, you will want to change your RSS Image size (width). The default is 560 pixels, which is the content width of a standard single column email (600 pixels wide with 20 pixels padding on the content). Mad Mimi users should set this to 530.', 'simple-google-calendar' ) . '</p>';
		$general_help .= '<p class="description">' . __( 'Note: Changing the width here will not affect previously uploaded images, but it will affect the max-width applied to images&rsquo; style.', 'simple-google-calendar' ) . '</p>';

		$help_tabs = array(
			array(
				'id'      => 'simplegooglecalendar_general-help',
				'title'   => __( 'General Settings', 'simple-google-calendar' ),
				'content' => $general_help,
			),
		);

		foreach ( $help_tabs as $tab ) {
			$screen->add_help_tab( $tab );
		}

	}
}
