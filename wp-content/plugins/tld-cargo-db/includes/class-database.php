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

		$sql = "CREATE TABLE IF NOT EXISTS {$this->table_name} (
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
	 * Get single record by ID
	 */
	public function get_record($id) {
		global $wpdb;
		$id = absint($id);
		
		$query = $wpdb->prepare("SELECT * FROM {$this->table_name} WHERE id = %d", $id);
		return $wpdb->get_row($query, ARRAY_A);
	}

	/**
	 * Get all records with optional pagination
	 */
	public function get_all_records($page = 1, $per_page = 10, $orderby = 'id', $order = 'DESC') {
		global $wpdb;
		
		$page = absint($page);
		$per_page = absint($per_page);
		$offset = ($page - 1) * $per_page;
		
		// Validate orderby to prevent SQL injection
		$valid_columns = $this->get_table_columns();
		$orderby = in_array($orderby, $valid_columns) ? $orderby : 'id';
		$order = strtoupper($order) === 'ASC' ? 'ASC' : 'DESC';
		
		$query = $wpdb->prepare(
			"SELECT * FROM {$this->table_name} ORDER BY {$orderby} {$order} LIMIT %d OFFSET %d",
			$per_page,
			$offset
		);
		
		return $wpdb->get_results($query, ARRAY_A);
	}

	/**
	 * Update record
	 */
	public function update_record($id, $data) {
		global $wpdb;
		$id = absint($id);
		
		// Sanitize data
		$sanitized_data = $this->sanitize_data($data);
		
		$result = $wpdb->update(
			$this->table_name,
			$sanitized_data,
			array('id' => $id),
			null,
			array('%d')
		);
		
		if ($result === false) {
			error_log('TLD Cargo DB Update Error: ' . $wpdb->last_error);
			return false;
		}
		
		return $result;
	}

	/**
	 * Delete record
	 */
	public function delete_record($id) {
		global $wpdb;
		$id = absint($id);
		
		$result = $wpdb->delete(
			$this->table_name,
			array('id' => $id),
			array('%d')
		);
		
		if ($result === false) {
			error_log('TLD Cargo DB Delete Error: ' . $wpdb->last_error);
			return false;
		}
		
		return $result;
	}

	/**
	 * Soft delete (deactivate) record
	 */
	public function deactivate_record($id) {
		return $this->update_record($id, array('deactive' => 1));
	}

	/**
	 * Reactivate record
	 */
	public function reactivate_record($id) {
		return $this->update_record($id, array('deactive' => 0));
	}

	/**
	 * Get table columns
	 */
	public function get_table_columns() {
		global $wpdb;
		
		$columns = $wpdb->get_col("DESCRIBE {$this->table_name}", 0);
		return $columns ?: array();
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
	 * Get active records count
	 */
	public function get_active_records_count() {
		global $wpdb;
		return $wpdb->get_var("SELECT COUNT(*) FROM {$this->table_name} WHERE deactive = 0");
	}

	/**
	 * Check if table exists
	 */
	public function table_exists() {
		global $wpdb;
		return $wpdb->get_var("SHOW TABLES LIKE '{$this->table_name}'") == $this->table_name;
	}
}