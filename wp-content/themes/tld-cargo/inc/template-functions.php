<?php
/**
 * Functions which enhance the theme by hooking into WordPress
 *
 * @package tld-cargo
 */

/**
 * Adds custom classes to the array of body classes.
 *
 * @param array $classes Classes for the body element.
 * @return array
 */
function tld_cargo_body_classes( $classes ) {
	// Adds a class of hfeed to non-singular pages.
	if ( ! is_singular() ) {
		$classes[] = 'hfeed';
	}

	// Adds a class of no-sidebar when there is no sidebar present.
	if ( ! is_active_sidebar( 'sidebar-1' ) ) {
		$classes[] = 'no-sidebar';
	}

	return $classes;
}
add_filter( 'body_class', 'tld_cargo_body_classes' );

/**
 * Add a pingback url auto-discovery header for single posts, pages, or attachments.
 */
function tld_cargo_pingback_header() {
	if ( is_singular() && pings_open() ) {
		printf( '<link rel="pingback" href="%s">', esc_url( get_bloginfo( 'pingback_url' ) ) );
	}
}
add_action( 'wp_head', 'tld_cargo_pingback_header' );

// Funkcija za dinamičko popunjavanje select polja preko AJAX-a
function tld_populate_our_name_select_field_ajax($field) {
	error_log('ACF Field Loaded: ' . print_r($field['key'], true)); // Provera ključa

	global $wpdb;

	// Proveri da li je ovo pravo polje
	if ($field['key'] === 'field_686123a122d07') {
		$field['choices'] = array();
		$results = $wpdb->get_results(
			$wpdb->prepare("
		        SELECT user_id, kompanija, first_name, last_name 
		        FROM {$wpdb->prefix}uwp_usermeta
		        ORDER BY kompanija ASC
		    ")
		);

		// Popuni opcije
		foreach ($results as $row) {
			$display_text = sprintf('%s - %s %s', $row->kompanija, $row->first_name, $row->last_name);
			$field['choices'][$row->user_id] = $display_text;
		}

		// Ako nema rezultata, dodaj placeholder
		if (empty($field['choices'])) {
			$field['choices'][''] = 'Nema dostupnih kompanija';
		}
	}

	return $field;
}
add_filter('acf/load_field/key=field_686123a122d07', 'tld_populate_our_name_select_field_ajax', 10, 1);


// 1. Dodavanje 'ui' i 'ajax' opcija ACF polju
function customize_our_name_select_field_settings($field) {
	// Provera da li je pravo polje
	if ($field['key'] === 'field_686123a122d07') {
		$field['choices'] = []; // Prazne opcije, jer se pune preko AJAX-a
		$field['ui'] = 1;       // Omogući Select2
		$field['ajax'] = 1;     // Omogući AJAX učitavanje
		$field['placeholder'] = 'Izaberite korisnika...';
	}
	return $field;
}
//add_filter('acf/load_field/key=field_686123a122d07', 'customize_our_name_select_field_settings');


// 2. AJAX pretraga korisnika za to polje
/*
function ajax_query_our_name_select_field($args, $field, $post_id) {
	global $wpdb;
	// Dohvati pretragu iz korisničkog unosa
	$search = $args['s'] ?? '';

	$sql = "
        SELECT user_id, kompanija, first_name, last_name 
        FROM {$wpdb->prefix}uwp_usermeta
    ";

	// Dodaj WHERE ako postoji pretraga
	if (!empty($search)) {
		$search = '%' . $wpdb->esc_like($search) . '%';
		$sql .= $wpdb->prepare(" WHERE kompanija LIKE %s OR first_name LIKE %s OR last_name LIKE %s", $search, $search, $search);
	}

	$sql .= " ORDER BY kompanija ASC LIMIT 50";

	$results = $wpdb->get_results($sql);

	$choices = [];

	foreach ($results as $row) {
		$label = sprintf('%s - %s %s', $row->kompanija, $row->first_name, $row->last_name);
		$choices[] = [
			'id' => $row->user_id,
			'text' => $label
		];
	}

	$args['results'] = $choices;
	return $args;
}
add_filter('acf/fields/select/query/key=field_686123a122d07', 'ajax_query_our_name_select_field', 10, 3);
*/
