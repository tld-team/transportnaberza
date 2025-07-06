<?php
/**
 * Form Handler Class
 */
class TLD_Cargo_Form_Handler {

	private $database;

	public function __construct() {
		$this->database = new TLD_Cargo_Database_Handler();

		add_action('wpcf7_mail_sent', array($this, 'handle_form_submission'));
		add_filter('wpcf7_skip_mail', array($this, 'maybe_skip_mail'), 10, 2);
	}

	/**
	 * Handle form submission
	 */
	public function handle_form_submission($contact_form) {
		$form_id = TLD_Cargo_Database::get_option('form_id');

		// Check if this is the target form
		if (empty($form_id) || $contact_form->id() != $form_id) {
			return;
		}

		$submission = WPCF7_Submission::get_instance();
		if (!$submission) {
			$this->log_error('Submission instance not available');
			return;
		}

		// Get form data
		$data = $submission->get_posted_data();

		tld_log(sprintf( "Data %s", print_r($data, true) ));
		// Map form fields to database columns
		$db_data = $this->map_form_data($data);

		tld_log(sprintf( "DB Data %s", print_r($db_data, true) ));
		// Validate required fields
		if (!$this->validate_required_fields($db_data)) {
			$this->log_error('Required fields validation failed');
			return;
		}

		// Insert into database
		$insert_id = $this->database->insert_data($db_data);

		if ($insert_id) {
			$this->log_success("Data saved with ID: $insert_id for user: " . $db_data['user']);
		} else {
			$this->log_error('Failed to insert data into database');
		}
	}

	/**
	 * Map form data to database fields
	 */
	private function map_form_data($data) {
		return array(
			'user' => get_current_user_id(),
			'date_from' => isset($data['date_from']) ? $data['date_from'] : '',
			'date_to' => isset($data['date_to']) ? $data['date_to'] : '',
			'date_from_plus' => isset($data['date_from_plus']) ? $data['date_from_plus'] : '',
			'date_to_plus' => isset($data['date_to_plus']) ? $data['date_to_plus'] : '',
			'vehicle_type' => isset($data['vehicle_type'][0]) ? $data['vehicle_type'][0] : '',
			'trailer' => isset($data['trailer'][0]) ? $data['trailer'][0] : '',
			'location_from' => isset($data['location_from']) ? $data['location_from'] : '',
			'location_to' => isset($data['location_to']) ? $data['location_to'] : '',
			'country_from' => isset($data['country_from']) ? $data['country_from'] : '',
			'country_to' => isset($data['country_to']) ? $data['country_to'] : '',
			'zip_from' => isset($data['zip_from']) ? $data['zip_from'] : '',
			'zip_to' => isset($data['zip_to']) ? $data['zip_to'] : '',
			'distance' => isset($data['distance']) ? $data['distance'] : '',
			'weight' => isset($data['weight']) ? $data['weight'] : '',
			'length' => isset($data['length']) ? $data['length'] : '',
			'height' => isset($data['height']) ? $data['height'] : '',
			'price' => isset($data['price']) ? $data['price'] : '',
			'description' => isset($data['description']) ? $data['description'] : '',
			'deactive' => 0
		);
	}

	/**
	 * Validate required fields
	 */
	private function validate_required_fields($data) {
		$required_fields = array('date_from', 'date_to', 'vehicle_type', 'location_from', 'location_to');

		foreach ($required_fields as $field) {
			if (empty($data[$field])) {
				return false;
			}
		}

		return true;
	}

	/**
	 * Maybe skip email sending
	 */
	public function maybe_skip_mail($skip_mail, $contact_form) {
		$form_id = TLD_Cargo_Database::get_option('form_id');
		$disable_email = TLD_Cargo_Database::get_option('disable_email', '1');

		if (!empty($form_id) && $contact_form->id() == $form_id && $disable_email == '1') {
			return true;
		}

		return $skip_mail;
	}

	/**
	 * Log error
	 */
	private function log_error($message) {
		if (TLD_Cargo_Database::get_option('enable_logging', '1') == '1') {
			error_log('TLD Cargo DB Error: ' . $message);
		}
	}

	/**
	 * Log success
	 */
	private function log_success($message) {
		if (TLD_Cargo_Database::get_option('enable_logging', '1') == '1') {
			error_log('TLD Cargo DB Success: ' . $message);
		}
	}
}