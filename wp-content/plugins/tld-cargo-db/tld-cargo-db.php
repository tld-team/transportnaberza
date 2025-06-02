<?php
/*
Plugin Name: TLd Cargo Database
Description: Custom database tables for TLd Cargo system
Version: 1.0
Author: Your Name
*/

if (!defined('ABSPATH')) {
	exit; // Exit if accessed directly
}

global $tld_cargo_db_version;
$tld_cargo_db_version = '1.0';

/**
 * Create custom tables on plugin activation
 */
function tld_cargo_install() {
	global $wpdb;
	global $tld_cargo_db_version;

	$companies_table = $wpdb->prefix . 'tld_companies';

	$charset_collate = $wpdb->get_charset_collate();

	$sql = "CREATE TABLE $companies_table (
        id mediumint(9) NOT NULL AUTO_INCREMENT,
        user mediumint(9) NOT NULL,
        type varchar(100) NOT NULL,
        company varchar(255) NOT NULL,
        pib varchar(50) NOT NULL,
        email varchar(100) NOT NULL,
        city varchar(100) NOT NULL,
        address text NOT NULL,
        active tinyint(1) DEFAULT 1 NOT NULL,
        licence_date date DEFAULT NULL,
        created_at datetime DEFAULT CURRENT_TIMESTAMP NOT NULL,
        updated_at datetime DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP NOT NULL,
        PRIMARY KEY  (id),
        UNIQUE KEY pib (pib),
        KEY email (email),
        KEY active (active)
    ) $charset_collate;";

	require_once(ABSPATH . 'wp-admin/includes/upgrade.php');
	dbDelta($sql);

	add_option('tld_cargo_db_version', $tld_cargo_db_version);
}
register_activation_hook(__FILE__, 'tld_cargo_install');
