<?php
/**
 * Simple Google Calendar
 *
 * @package           SimpleGoogleCalendar
 * @author            Robin Cornett
 * @link              https://github.com/robincornett/simple-google-calendar
 * @copyright         2015 Robin Cornett
 * @license           GPL-2.0+
 *
 * @wordpress-plugin
 * Plugin Name:       Simple Google Calendar
 * Plugin URI:        https://github.com/robincornett/simple-google-calendar
 * Description:       A simple plugin to add Google calendar feed(s) to a website. Uses the Google embed code.
 * Version:           0.1.0
 * Author:            Robin Cornett
 * Author URI:        http://robincornett.com
 * Text Domain:       simple-google-calendar
 * License:           GPL-2.0+
 * License URI:       http://www.gnu.org/licenses/gpl-2.0.txt
 * Domain Path:       /languages
 * GitHub Plugin URI: https://github.com/robincornett/simple-google-calendar
 * GitHub Branch:     master
 */

require plugin_dir_path( __FILE__ ) . 'includes/class-simplegooglecalendar.php';
require_once plugin_dir_path( __FILE__ ) . 'includes/class-simplegooglecalendar-widget.php';

$simplegooglecalendar = new SimpleGoogleCalendar();
$simplegooglecalendar->run();
