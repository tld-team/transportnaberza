<?php
/**
 * Database Handler Class
 */
class TLD_Cargo_Database_Handler {

	private $table_name;

	public function __construct() {
		global $wpdb;
		$this->table_name = $wpdb->prefix . 'tld_cargo_orders';
	}

	/**
	 * Create custom table
	 */
	public function create_table() {
		global $wpdb;

		$sql = "CREATE TABLE {$this->table_name} (
            id mediumint(9) NOT NULL AUTO_INCREMENT,
            user mediumint(9) NOT NULL,
            created datetime DEFAULT CURRENT_TIMESTAMP,
            date_from date NOT NULL,
            date_to date NOT NULL,
            date_from_plus date,
            date_to_plus date,
            vehicle_type varchar(255) NOT NULL,
            trailer varchar(255),
            location_from varchar(255) NOT NULL,
            location_to varchar(255) NOT NULL,
            country_from varchar(100),
            country_to varchar(100),
            zip_from varchar(20),
            zip_to varchar(20),
            distance varchar(100),
            weight varchar(100),
            length varchar(100),
            height varchar(100),
            price varchar(100),
            deactive tinyint(1) DEFAULT 0,
            description text,
            PRIMARY KEY (id)
        ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;";

		require_once(ABSPATH . 'wp-admin/includes/upgrade.php');
		dbDelta($sql);
	}

	/**
	 * Insert data into table
	 */
	public function insert_data($data) {
		global $wpdb;

		// Sanitize data
		$sanitized_data = $this->sanitize_data($data);

		// Insert into database
		$result = $wpdb->insert($this->table_name, $sanitized_data);

		if ($result === false) {
			error_log('TLD Cargo DB Error: ' . $wpdb->last_error);
			return false;
		}

		return $wpdb->insert_id;
	}

	/**
	 * Sanitize data
	 */
	private function sanitize_data($data) {
		$sanitized = array();

		foreach ($data as $key => $value) {
			switch ($key) {
				case 'user':
					$sanitized[$key] = absint($value);
					break;
				case 'date_from':
				case 'date_to':
				case 'date_from_plus':
				case 'date_to_plus':
					$sanitized[$key] = sanitize_text_field($value);
					break;
				case 'deactive':
					$sanitized[$key] = (int) $value;
					break;
				case 'description':
					$sanitized[$key] = sanitize_textarea_field($value);
					break;
				default:
					$sanitized[$key] = sanitize_text_field($value);
					break;
			}
		}

		return $sanitized;
	}

	/**
	 * Get table name
	 */
	public function get_table_name() {
		return $this->table_name;
	}

	/**
	 * Get records count
	 */
	public function get_records_count() {
		global $wpdb;
		return $wpdb->get_var("SELECT COUNT(*) FROM {$this->table_name}");
	}

	/**
	 * Check if table exists
	 */
	public function table_exists() {
		global $wpdb;
		return $wpdb->get_var("SHOW TABLES LIKE '{$this->table_name}'") == $this->table_name;
	}
}