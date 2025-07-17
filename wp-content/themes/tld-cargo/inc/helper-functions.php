<?php

/**
 * Read JSON file and return data
 */
function tld_read_json($json_file_path) {
	if (!file_exists($json_file_path)) {
		error_log("JSON file not found at path: $json_file_path");
		return;
	}

	$json_content = file_get_contents($json_file_path);
	$data = json_decode($json_content, true);

	if (json_last_error() !== JSON_ERROR_NONE || !is_array($data)) {
		error_log("Invalid JSON format in file: $json_file_path");
		return;
	}

	return $data;
}


/**
 * Update user data based on email
 */
// $users_data = tld_read_json(get_template_directory() . '/users.json');

// function update_user_data_based_on_email($email, $data) {
//     // Pronađi korisnika po email adresi
//     $user = get_user_by('email', $email);
    
//     if (!$user) {
//         return false; // Korisnik nije pronađen
//     }
    
//     $user_id = $user->ID;
    
//     // Ažuriraj korisničke meta podatke
//     uwp_update_usermeta($user_id, 'mobile', $data['mobile']);
//     uwp_update_usermeta($user_id, 'company', $data['company']);
//     uwp_update_usermeta($user_id, 'fax', $data['fax']);
//     uwp_update_usermeta($user_id, 'tip_kompanije', $data['tip_kompanije']);
//     uwp_update_usermeta($user_id, 'kompanija', $data['kompanija']);
//     uwp_update_usermeta($user_id, 'pib', $data['pib']);
//     uwp_update_usermeta($user_id, 'country', 'gb');
//     uwp_update_usermeta($user_id, 'grad', $data['grad']);
//     uwp_update_usermeta($user_id, 'adresa', $data['adresa']);
//     uwp_update_usermeta($user_id, 'aktivan', $data['aktivan']);
//     uwp_update_usermeta($user_id, 'obrisati', $data['obrisati']);
//     uwp_update_usermeta($user_id, 'broj_korisnika', $data['broj_korisnika']);
//     uwp_update_usermeta($user_id, 'telefon_kompanije', $data['telefon_kompanije']);
//     echo "User updated: " . $email . "<br>";
//     return true;
// }

// foreach ($users_data['rows'] as $key => $user) {
//     echo $key . "<br>";
//     update_user_data_based_on_email($user['email'], $user);
// }
/** ============================================================================ */

