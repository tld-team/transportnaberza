<?php
// Handle form submission
function tld_ajax_company(): void {
	// Verify nonce
	check_ajax_referer('custom-ajax-company', 'nonce');

	global $wpdb;

	// Get POST data
	$current_user = isset($_POST['userNumber']) ? intval($_POST['userNumber']) : 0;
	$type = sanitize_text_field($_POST['type']);
	$company = sanitize_text_field($_POST['company']);
	$pib = sanitize_text_field($_POST['pib']);
	$email = sanitize_email($_POST['email']);
	$city = sanitize_text_field($_POST['city']);
	$address = sanitize_text_field($_POST['address']);

	// Validate data
	if (empty($type) || empty($company) || empty($pib) || empty($email) || empty($city) || empty($address)) {
		wp_send_json_error('All fields are required');
	}

	if (!is_email($email)) {
		wp_send_json_error('Invalid email format');
	}

	$exist_user = tld_company_exists($current_user);

	tld_log(sprintf('update: %s', print_r($exist_user, true)));
	try {
		if ($exist_user) {
			tld_log(sprintf('update: %s', print_r(get_current_user_id(), true)));
			tld_update_company( $current_user, array(
				'user'         => $current_user,
				'type'         => $type,
				'company'      => $company,
				'pib'          => $pib,
				'email'        => $email,
				'city'         => $city,
				'address'      => $address
			) );
		} else {
			tld_log(sprintf('insert user id: %s', print_r(get_current_user_id(), true)));
			tld_insert_company( array(
				'user'         => $current_user,
				'type'         => $type,
				'company'      => $company,
				'pib'          => $pib,
				'email'        => $email,
				'city'         => $city,
				'address'      => $address,
				'licence_date' => '2025-12-31'
			) );
		}

		if (true) {
			wp_send_json_success(array(
				'message' => 'User data saved successfully!',
				'userNumber' => $user_number,
				'clearForm' => ($user_number <= 0)
			));
		} else {
			wp_send_json_error('Error saving data: ' . $wpdb->last_error);
		}
	} catch (Exception $e) {
		wp_send_json_error('Error: ' . $e->getMessage());
	}
}
add_action('wp_ajax_tld_ajax_company', 'tld_ajax_company');
add_action('wp_ajax_nopriv_tld_ajax_company', 'tld_ajax_company');