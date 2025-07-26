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
	 */public function get_record($id) {
		global $wpdb;
		$id = absint($id);
		
		$query = $wpdb->prepare("SELECT * FROM {$this->table_name} WHERE id = %d", $id);
		return $wpdb->get_row($query, ARRAY_A);
	}

	/**
	 * Get all records with optional pagination
	 */
	/**
	 * Get all records with optional pagination
	 * 
	 * @param int $page - page number (default: 1)
	 * @param int $per_page - number of records per page (default: 10)
	 * @param string $orderby - column to order by (default: 'created')
	 * @param string $order - ASC or DESC (default: DESC)
	 */
	public function get_all_records($page = 1, $per_page = 10, $orderby = 'created', $order = 'DESC') {
		global $wpdb;
		
		// Validate input parameters
		$page = absint($page);
		$per_page = absint($per_page);
		
		// Calculate offset
		$offset = ($page - 1) * $per_page;
		
		// Validate orderby to prevent SQL injection
		$valid_columns = $this->get_table_columns(); // Get all columns of the table
		$orderby = in_array($orderby, $valid_columns) ? $orderby : 'id'; // If orderby is not valid, use 'id'
		
		// Validate order
		$order = strtoupper($order) === 'ASC' ? 'ASC' : 'DESC';
		
		// Prepare SQL query
		$query = $wpdb->prepare(
			"SELECT * FROM {$this->table_name} ORDER BY {$orderby} {$order} LIMIT %d OFFSET %d",
			$per_page,
			$offset
		);
		
		// Execute query and return results
		return $wpdb->get_results($query, ARRAY_A);
	}

	public function get_extended_record_by_date ($days_to_extend = 0) {
		global $wpdb;
		
		$current_date = current_time('mysql', true);
			
		$extended_date = date('Y-m-d', strtotime($current_date . " - {$days_to_extend} days"));
		tld_log("Extended date: " . $extended_date);
		// Ako je prosleđen broj dana za produženje, koristimo date_from kao krajnji datum
		if ($days_to_extend > 0) {
			$query = $wpdb->prepare(
				"SELECT * FROM `" . $wpdb->prefix . "tld_cargo_orders` 
				WHERE deactive = 0 
				AND (
					date_from <= %s
					OR 
					(date_from IS NOT NULL AND date_from <= %s)
				)
				ORDER BY created DESC", // Dodato sortiranje
				$extended_date,
				$extended_date
			);
		} else {
			// Standardni upis bez produženja
			$query = $wpdb->prepare(
				"SELECT * FROM `" . $wpdb->prefix . "tld_cargo_orders` 
				WHERE deactive=0 
				AND date_from<=%s
				ORDER BY created DESC",  // Dodato sortiranje
				$current_date
			);
		}
		
		$results = $wpdb->get_results($query);
//		return json_encode($results);
		$error = $wpdb->last_error;
        if ($error) {
            tld_log("Error: {$error}");
        }
		
		return $results;
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