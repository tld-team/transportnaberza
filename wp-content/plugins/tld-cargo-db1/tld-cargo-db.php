<?php
/*
Plugin Name: TLD Cargo Database
Description: Snima podatke iz CF7 forme u custom tabelu bez slanja email-a.
Version: 2.0
Author: Your Name
Text Domain: tld-cargo-db
*/

// Prevent direct access
if (!defined('ABSPATH')) {
	exit;
}

// Define plugin constants
define('TLD_CARGO_DB_VERSION', '2.0');
define('TLD_CARGO_DB_PLUGIN_DIR', plugin_dir_path(__FILE__));
define('TLD_CARGO_DB_PLUGIN_URL', plugin_dir_url(__FILE__));

/**
 * Main Plugin Class
 */
/**
 * Main Plugin Class
 */
class TLD_Cargo_Database {

	private $admin;
	private $database;
	private $form_handler;

	/**
	 * Constructor
	 */
	public function __construct() {
		$this->load_includes();
		$this->init();
		$this->setup_hooks();
	}

	/**
	 * Load include files
	 */
	private function load_includes() {
		// Load classes from includes directory if they exist
		if (file_exists(TLD_CARGO_DB_PLUGIN_DIR . 'includes/class-database.php')) {
			require_once TLD_CARGO_DB_PLUGIN_DIR . 'includes/class-database.php';
		}
		if (file_exists(TLD_CARGO_DB_PLUGIN_DIR . 'includes/class-admin.php')) {
			require_once TLD_CARGO_DB_PLUGIN_DIR . 'includes/class-admin.php';
		}
		if (file_exists(TLD_CARGO_DB_PLUGIN_DIR . 'includes/class-form-handler.php')) {
			require_once TLD_CARGO_DB_PLUGIN_DIR . 'includes/class-form-handler.php';
		}
	}

	/**
	 * Initialize plugin components
	 */
	private function init() {
		$this->database = new TLD_Cargo_Database_Handler();
		$this->admin = new TLD_Cargo_Database_Admin();

		// Initialize form handler only if CF7 is active
		if (class_exists('WPCF7_ContactForm')) {
			$this->form_handler = new TLD_Cargo_Form_Handler();
		}
	}

	/**
	 * Setup WordPress hooks
	 */
	private function setup_hooks() {
		register_activation_hook(__FILE__, array($this, 'activate'));
		register_deactivation_hook(__FILE__, array($this, 'deactivate'));
	}

	/**
	 * Plugin activation
	 */
	public function activate() {
		$this->database->create_table();

		// Set default options
		$default_options = array(
			'form_id' => '',
			'disable_email' => '1',
			'enable_logging' => '1'
		);

		add_option('tld_cargo_db_options', $default_options);
	}

	/**
	 * Plugin deactivation
	 */
	public function deactivate() {
		// Clean up if needed
		// Note: We don't delete options or table on deactivation
		// Only on uninstall (which would be in uninstall.php)
	}

	/**
	 * Get plugin options
	 */
	public static function get_option($key, $default = '') {
		$options = get_option('tld_cargo_db_options', array());
		return isset($options[$key]) ? $options[$key] : $default;
	}

	/**
	 * Update plugin options
	 */
	public static function update_option($key, $value) {
		$options = get_option('tld_cargo_db_options', array());
		$options[$key] = $value;
		update_option('tld_cargo_db_options', $options);
	}
}
// Initialize the plugin
new TLD_Cargo_Database();