<?php

/** update promo period after registration, na osnovu setovanja iz ACF polje*/
function tld_set_licence_expiry_based_on_tld_promo_period($user_id) {
//	if (!$user_id || is_wp_error($result)) {
//		return; // Ako nema korisnika ili je greška, prekini izvršavanje
//	}

	// 1. Dohvati ACF polje 'tld_promo_period' za korisnika
	$promo_period = (int) get_field('tld_promo_period', 'option');
	// 2. Ako polje ne postoji ili je 0, postavi defaultnu vrijednost (npr. 30 dana)
	if (empty($promo_period) || $promo_period == 0) {
		$promo_period = 30; // Defaultni period ako nije postavljeno
	}
	tld_log( sprintf( 'promo period : %s', print_r($promo_period, true) ) );

	// 3. Izračunaj datum isteka (trenutni datum + $promo_period dana)
	$expiry_date = date('Y-m-d', strtotime("+{$promo_period} days"));
	tld_log( sprintf( 'expiry_date : %s', print_r($expiry_date, true) ) );

	// 4. Spremi datum u UsersWP polje 'licenca'
	update_user_meta($user_id, 'licenca', $expiry_date);

	// Alternativno, ako UsersWP koristi svoju metodu:
	 uwp_update_usermeta($user_id, 'licenca', $expiry_date);
}
add_action('user_register', 'tld_set_licence_expiry_based_on_tld_promo_period', 10, 2);
