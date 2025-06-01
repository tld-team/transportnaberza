<?php

/**
 *
 * // Insert a new company
 * $company_id = tld_insert_company(array(
 * 'type' => 'supplier',
 * 'company' => 'Example Company',
 * 'pib' => '123456789',
 * 'email' => 'contact@example.com',
 * 'city' => 'Belgrade',
 * 'address' => '123 Main St',
 * 'licence_date' => '2025-12-31'
 * ));
 *
 * // Update company fields
 * tld_update_company(1, ['company', 'email'], ['New Company Name', 'new@email.com']);
 *
 * // Get a company
 * $company = tld_get_company(1);
 *
 * // Get all active companies
 * $active_companies = tld_get_active_companies();
 *
 *
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}
/**
 * Insert a new company
 *
 * @param array $data Array of company data
 *
 * @return int|false The inserted company ID or false on error
 */
function tld_insert_company( $data ) {
	global $wpdb;

	$defaults = array(
		'active'       => 1,
		'licence_date' => null
	);

	$data = wp_parse_args( $data, $defaults );

	$result = $wpdb->insert(
		$wpdb->prefix . 'tld_companies',
		$data,
		array(
			'%s', // type
			'%s', // company
			'%s', // pib
			'%s', // email
			'%s', // city
			'%s', // address
			'%d', // active
			'%s', // licence_date
		)
	);

	return $result ? $wpdb->insert_id : false;
}


function tld_update_company($user_id, $data) {
	global $wpdb;

	$table_name = $wpdb->prefix . 'tld_companies';

	// Provera da li kompanija postoji pre ažuriranja
	$exists = $wpdb->get_var($wpdb->prepare(
		"SELECT COUNT(*) FROM $table_name WHERE user = %d",
		$user_id
	));

	if (!$exists) {
		return false;
	}

	// Definišemo format za svako polje
	$formats = array(
		'user_id'      => '%d', // dodato user_id polje u formate
		'type'         => '%s',
		'company'     => '%s',
		'pib'         => '%s',
		'email'       => '%s',
		'city'       => '%s',
		'address'     => '%s',
	);

	// Pripremamo format array za polja koja se ažuriraju
	$update_formats = array();
	foreach ($data as $key => $value) {
		if (isset($formats[$key])) {
			$update_formats[] = $formats[$key];
		}
	}

	$where = array('user' => $user_id);
	$where_format = array('%d');

	$result = $wpdb->update(
		$table_name,
		$data,
		$where,
		$update_formats,
		$where_format
	);

	// $result će biti broj ažuriranih redova (može biti 0 ako su podaci isti)
	// ili false ako je došlo do greške
	return $result !== false;
}
/**
 * Get company by ID
 *
 * @param int $company_id Company ID
 *
 * @return object|null Company object or null if not found
 */
function tld_get_company( $current_user_id ) {
	global $wpdb;

	return $wpdb->get_row(
		$wpdb->prepare(
			"SELECT * FROM {$wpdb->prefix}tld_companies WHERE user = %d",
			$current_user_id
		)
	);
}

/**
 * Get company by PIB
 *
 * @param string $pib Company PIB
 *
 * @return object|null Company object or null if not found
 */
function tld_get_company_by_pib( $pib ) {
	global $wpdb;

	return $wpdb->get_row(
		$wpdb->prepare(
			"SELECT * FROM {$wpdb->prefix}tld_companies WHERE pib = %s",
			$pib
		)
	);
}

/**
 * Delete company (soft delete)
 *
 * @param int $company_id Company ID
 *
 * @return bool True on success, false on failure
 */
function tld_delete_company( $company_id ) {
	global $wpdb;

	return $wpdb->update(
		$wpdb->prefix . 'tld_companies',
		array( 'active' => 0 ),
		array( 'id' => $company_id ),
		array( '%d' ),
		array( '%d' )
	);
}

/**
 * Get all active companies
 *
 * @return array Array of company objects
 */
function tld_get_active_companies() {
	global $wpdb;

	return $wpdb->get_results(
		"SELECT * FROM {$wpdb->prefix}tld_companies WHERE active = 1 ORDER BY company ASC"
	);
}

/**
 * Get companies by type
 *
 * @param string $type Company type
 *
 * @return array Array of company objects
 */
function tld_get_companies_by_type( $type ) {
	global $wpdb;

	return $wpdb->get_results(
		$wpdb->prepare(
			"SELECT * FROM {$wpdb->prefix}tld_companies WHERE type = %s AND active = 1 ORDER BY company ASC",
			$type
		)
	);
}

/**
 * Check if company exists by PIB
 *
 * @param string $pib Company PIB
 *
 * @return bool True if exists, false otherwise
 */
function tld_company_exists( $user_id ) {
	global $wpdb;

	$count = $wpdb->get_var(
		$wpdb->prepare(
			"SELECT COUNT(*) FROM {$wpdb->prefix}tld_companies WHERE user = %s",
			$user_id
		)
	);

	return $count > 0;
}

