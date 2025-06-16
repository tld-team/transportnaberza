<?php
/*
Plugin Name: TLD Cargo Database
Description: Custom database tables for TLd Cargo system
Version: 1.0
Author: TLD team
*/

if (!defined('ABSPATH')) {
	exit; // Exit if accessed directly
}

global $wpdb;
$table_name = $wpdb->prefix . 'tld_transport_offers';

// Function to create the table
function create_transport_offers_table() {
	global $wpdb;
	$table_name = $wpdb->prefix . 'tld_transport_offers';

	$charset_collate = $wpdb->get_charset_collate();

	$sql = "CREATE TABLE $table_name (
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
        PRIMARY KEY  (id)
    ) $charset_collate;";

	require_once( ABSPATH . 'wp-admin/includes/upgrade.php' );
	dbDelta( $sql );
}

// Register activation hook
register_activation_hook( __FILE__, 'create_transport_offers_table' );

/**
 * CRUD FUNCTIONS
 */

/**
 * Create a new transport offer
 *
 * @param array $data Array of offer data
 * @return int|false The ID of the created offer or false on failure
 */
function tld_create_transport_offer($data) {
	global $wpdb;

	$defaults = array(
		'user' => get_current_user_id(),
		'created' => current_time('mysql'),
		'deactive' => 0
	);

	$data = wp_parse_args($data, $defaults);

	// Validate required fields
	$required = array('date_from', 'date_to', 'vehicle_type', 'location_from', 'location_to');
	foreach ($required as $field) {
		if (empty($data[$field])) {
			return false;
		}
	}

	$result = $wpdb->insert(
		$wpdb->prefix . 'tld_transport_offers',
		$data,
		array(
			'%d', // user
			'%s', // created
			'%s', // date_from
			'%s', // date_to
			'%s', // date_from_plus
			'%s', // date_to_plus
			'%s', // vehicle_type
			'%s', // trailer
			'%s', // location_from
			'%s', // location_to
			'%s', // country_from
			'%s', // country_to
			'%s', // zip_from
			'%s', // zip_to
			'%s', // distance
			'%s', // weight
			'%s', // length
			'%s', // height
			'%s', // price
			'%d', // deactive
			'%s'  // description
		)
	);

	return $result ? $wpdb->insert_id : false;
}

/**
 * Get transport offers
 *
 * @param array $args Query arguments
 * @return array Array of offers
 */
function tld_get_transport_offers($args = array()) {
	global $wpdb;

	$defaults = array(
		'number' => 20,
		'offset' => 0,
		'orderby' => 'created',
		'order' => 'DESC',
		'user' => '',
		'deactive' => 0
	);

	$args = wp_parse_args($args, $defaults);

	$where = "WHERE deactive = {$args['deactive']}";

	if (!empty($args['user'])) {
		$where .= $wpdb->prepare(" AND user = %d", $args['user']);
	}

	$query = "SELECT * FROM {$wpdb->prefix}tld_transport_offers $where 
              ORDER BY {$args['orderby']} {$args['order']} 
              LIMIT {$args['offset']}, {$args['number']}";

	$results = $wpdb->get_results($query);

	return $results;
}

/**
 * Get a single transport offer by ID
 *
 * @param int $id Offer ID
 * @return object|false The offer object or false if not found
 */
function tld_get_transport_offer($id) {
	global $wpdb;

	$query = $wpdb->prepare(
		"SELECT * FROM {$wpdb->prefix}tld_transport_offers WHERE id = %d",
		$id
	);

	return $wpdb->get_row($query);
}

/**
 * Update a transport offer
 *
 * @param int $id Offer ID
 * @param array $data Array of data to update
 * @return bool True on success, false on failure
 */
function tld_update_transport_offer($id, $data) {
	global $wpdb;

	// Remove any fields that shouldn't be updated
	unset($data['id'], $data['created']);

	$result = $wpdb->update(
		$wpdb->prefix . 'tld_transport_offers',
		$data,
		array('id' => $id),
		array(
			'%s', // date_from
			'%s', // date_to
			'%s', // date_from_plus
			'%s', // date_to_plus
			'%s', // vehicle_type
			'%s', // trailer
			'%s', // location_from
			'%s', // location_to
			'%s', // country_from
			'%s', // country_to
			'%s', // zip_from
			'%s', // zip_to
			'%s', // distance
			'%s', // weight
			'%s', // length
			'%s', // height
			'%s', // price
			'%d', // deactive
			'%s'  // description
		),
		array('%d')
	);

	return $result !== false;
}

/**
 * Delete a transport offer
 *
 * @param int $id Offer ID
 * @return bool True on success, false on failure
 */
function tld_delete_transport_offer($id) {
	global $wpdb;

	$result = $wpdb->delete(
		$wpdb->prefix . 'tld_transport_offers',
		array('id' => $id),
		array('%d')
	);

	return $result !== false;
}

/**
 * Count transport offers
 *
 * @param array $args Query arguments
 * @return int Number of offers matching criteria
 */
function tld_count_transport_offers($args = array()) {
	global $wpdb;

	$defaults = array(
		'user' => '',
		'deactive' => 0
	);

	$args = wp_parse_args($args, $defaults);

	$where = "WHERE deactive = {$args['deactive']}";

	if (!empty($args['user'])) {
		$where .= $wpdb->prepare(" AND user = %d", $args['user']);
	}

	$query = "SELECT COUNT(*) FROM {$wpdb->prefix}tld_transport_offers $where";

	return (int)$wpdb->get_var($query);
}