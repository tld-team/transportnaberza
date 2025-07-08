<?php

/**
 * Admin Class
 */
class TLD_Cargo_Database_Admin {

	public function __construct() {
		add_action( 'admin_menu', array( $this, 'add_admin_menu' ) );
		add_action( 'admin_init', array( $this, 'init_settings' ) );
		add_action( 'admin_enqueue_scripts', array( $this, 'enqueue_admin_scripts' ) );
	}

	/**
	 * Add admin menu
	 */
	public function add_admin_menu() {
		add_menu_page(
			'TLD Cargo Database Settings',
			'TLD Cargo DB',
			'manage_options',
			'tld-cargo-database',
			array( $this, 'admin_page' )
		);
	}

	/**
	 * Initialize settings
	 */
	public function init_settings() {
		register_setting( 'tld_cargo_db_settings', 'tld_cargo_db_options' );

		add_settings_section(
			'tld_cargo_db_main',
			'Osnovne postavke',
			array( $this, 'settings_section_callback' ),
			'tld-cargo-database'
		);

		add_settings_field(
			'form_id',
			'ID CF7 forme',
			array( $this, 'form_id_callback' ),
			'tld-cargo-database',
			'tld_cargo_db_main'
		);

		add_settings_field(
			'disable_email',
			'Onemogući slanje email-a',
			array( $this, 'disable_email_callback' ),
			'tld-cargo-database',
			'tld_cargo_db_main'
		);

		add_settings_field(
			'enable_logging',
			'Omogući logovanje',
			array( $this, 'enable_logging_callback' ),
			'tld-cargo-database',
			'tld_cargo_db_main'
		);
	}


	/**
	 * Settings section callback
	 */
    
	public function settings_section_callback() {
		echo '<p>Konfigurišite plugin za rad sa Contact Form 7.</p>';
	}

	/**
	 * Form ID callback
	 */
	public function form_id_callback() {
		$form_id = TLD_Cargo_Database::get_option( 'form_id', '' );
		echo '<input type="number" name="tld_cargo_db_options[form_id]" value="' . esc_attr( $form_id ) . '" min="1" />';
		echo '<p class="description">Unesite ID Contact Form 7 forme koju želite da povežete sa bazom podataka.</p>';

		// Show available forms
		$this->show_available_forms();
	}

	/**
	 * Disable email callback
	 */
	public function disable_email_callback() {
		$disable_email = TLD_Cargo_Database::get_option( 'disable_email', '1' );
		echo '<input type="checkbox" name="tld_cargo_db_options[disable_email]" value="1" ' . checked( 1, $disable_email, false ) . ' />';
		echo '<label>Onemogući slanje email-a za izabranu formu</label>';
	}

	/**
	 * Enable logging callback
	 */
	public function enable_logging_callback() {
		$enable_logging = TLD_Cargo_Database::get_option( 'enable_logging', '1' );
		echo '<input type="checkbox" name="tld_cargo_db_options[enable_logging]" value="1" ' . checked( 1, $enable_logging, false ) . ' />';
		echo '<label>Omogući logovanje grešaka i uspešnih upisa</label>';
	}

	/**
	 * Show available Contact Form 7 forms
	 */
	private function show_available_forms() {
		if ( ! class_exists( 'WPCF7_ContactForm' ) ) {
			echo '<p class="description" style="color: red;">Contact Form 7 plugin nije instaliran ili aktiviran.</p>';

			return;
		}

		$forms = get_posts( array(
			'post_type'   => 'wpcf7_contact_form',
			'numberposts' => - 1,
			'post_status' => 'publish'
		) );

		if ( empty( $forms ) ) {
			echo '<p class="description">Nema dostupnih CF7 formi.</p>';

			return;
		}

		echo '<p class="description"><strong>Dostupne forme:</strong></p>';
		echo '<ul style="margin-left: 20px;">';
		foreach ( $forms as $form ) {
			echo '<li>ID: <strong>' . $form->ID . '</strong> - ' . esc_html( $form->post_title ) . '</li>';
		}
		echo '</ul>';
	}

	/**
	 * Admin page
	 */
	public function admin_page() {
		?>
        <div class="wrap tld-cargo-db-admin">
            <h1><?php echo esc_html( get_admin_page_title() ); ?></h1>

            <form action="options.php" method="post">
				<?php
				settings_fields( 'tld_cargo_db_settings' );
				do_settings_sections( 'tld-cargo-database' );
				submit_button( 'Sačuvaj postavke' );
				?>
            </form>

            <hr>

            <h2>Informacije o tabeli</h2>
			<?php $this->show_table_info(); ?>

        </div>
		<?php
	}

	/**
	 * Show table information
	 */
	private function show_table_info() {
		$database   = new TLD_Cargo_Database_Handler();
		$table_name = $database->get_table_name();

		// Check if table exists
		$table_exists = $database->table_exists();

		if ( $table_exists ) {
			$count = $database->get_records_count();
			echo '<p><strong>Status tabele:</strong> <span class="status-active">Kreirana</span></p>';
			echo '<p><strong>Naziv tabele:</strong> ' . esc_html( $table_name ) . '</p>';
			echo '<p><strong>Broj zapisa:</strong> ' . intval( $count ) . '</p>';
		} else {
			echo '<p><strong>Status tabele:</strong> <span class="status-inactive">Nije kreirana</span></p>';
			echo '<p><button type="button" class="button" onclick="location.reload()">Osveži stranicu</button></p>';
		}
	}

	/**
	 * Enqueue admin scripts
	 */
	public function enqueue_admin_scripts( $hook ) {
		if ( 'settings_page_tld-cargo-database' !== $hook ) {
			return;
		}

		// Only enqueue if CSS file exists
		if ( file_exists( TLD_CARGO_DB_PLUGIN_DIR . 'assets/admin.css' ) ) {
			wp_enqueue_style( 'tld-cargo-db-admin', TLD_CARGO_DB_PLUGIN_URL . 'assets/admin.css', array(), TLD_CARGO_DB_VERSION );
		}
	}
}