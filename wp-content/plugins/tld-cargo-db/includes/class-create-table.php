<?php
class TLD_Cargo_Create_Table {

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
}
	