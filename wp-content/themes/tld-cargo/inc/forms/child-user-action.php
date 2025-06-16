<?php
/*
 * File: custom-child-users.php
 * Description: Logika za kreiranje i upravljanje child userima pod glavnim userom
 */

// 1. Registracija shortcode-a za prikaz forme i liste usera
add_shortcode('child_users_dashboard', 'custom_child_users_dashboard');

function custom_child_users_dashboard() {
	// Provera da li je user ulogovan
	if (!is_user_logged_in()) {
		return '<div class="alert alert-warning">Morate biti prijavljeni da biste pristupili ovom sadržaju.</div>';
	}

	// Provera korisničke uloge - zabrana pristupa za contributor role
	$current_user = wp_get_current_user();
	if (in_array('contributor', $current_user->roles)) {
		return '<div class="alert alert-danger">Nemate dozvolu za pristup ovom sadržaju.</div>';
	}
	$current_user = wp_get_current_user();
	$output = '';

	// 2. Obrada forme za kreiranje novog usera
	if (isset($_POST['create_child_user'])) {
		$output .= custom_handle_child_user_creation($current_user->ID);
	}

	// 3. Obrada brisanja usera
	if (isset($_GET['delete_child_user'])) {
		$output .= custom_handle_child_user_deletion($current_user->ID, $_GET['delete_child_user']);
	}

	// 4. Obrada editovanja usera
	if (isset($_POST['edit_child_user'])) {
		$output .= custom_handle_child_user_edit($current_user->ID, $_POST['user_id']);
	}

	// 5. Obrada deaktivacije usera
	if (isset($_GET['toggle_user_status'])) {
		$output .= custom_handle_user_status_toggle($current_user->ID, $_GET['toggle_user_status']);
	}

	$allowed_users = uwp_get_usermeta($current_user->ID, 'broj_korisnika', true);

	$users = get_users(array(
		'meta_key' => 'parent_user_id',
		'meta_value' => $current_user->ID,
		'count_total' => true
	));
;

	// 6. Prikaz HTML strukture sa vašim dizajnom
	if (count($users) < $allowed_users) {
		$output .= '
    <div class="container">
        <!-- Add User Button -->
        <button class="btn btn-primary mb-3" data-bs-toggle="modal" data-bs-target="#userFormModal">
            <i class="bi bi-plus-circle"></i> Add User
        </button>';
	} else {
		$user_disable_notification = get_field( 'tld_account_user_disable_notification', 'options' );
		$output .= '
		<div class="container">
    <!-- Info Message with similar styling to the original button -->
    <div class="p-3 mb-3 bg-light text-dark rounded d-flex align-items-center">
        <i class="bi bi-info-circle-fill me-2"></i>
        <div>'. $user_disable_notification.'
        </div>
    </div>
</div>
		';
	}

	// 7. Prikaz liste postojećih child usera
	$output .= custom_get_child_users_list($current_user->ID);

	// 8. Prikaz modala za kreiranje usera
	$output .= '
        <!-- User Form Modal -->
        <div class="modal fade" id="userFormModal" tabindex="-1" aria-hidden="true">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title">Add User</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        ' . custom_get_child_user_form() . '
                    </div>
                </div>
            </div>
        </div>';

	// 9. Prikaz modala za editovanje usera
	$output .= '
        <!-- Edit User Form Modal -->
        <div class="modal fade" id="editUserFormModal" tabindex="-1" aria-hidden="true">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title">Edit User</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body" id="editUserFormContainer">
                        <!-- Edit forma će biti ubačena ovde preko AJAX-a -->
                    </div>
                </div>
            </div>
        </div>
    </div>';

	// 10. Dodajemo JavaScript za editovanje
	$output .= custom_get_child_users_js();

	return $output;
}

// Funkcija za obradu kreiranja novog usera
function custom_handle_child_user_creation($parent_id) {
	// Provera nonce-a
	if (!isset($_POST['child_user_nonce']) || !wp_verify_nonce($_POST['child_user_nonce'], 'create_child_user')) {
		return '<div class="alert alert-danger">Greška u zahtevu.</div>';
	}

	// Osnovna validacija
	$first_name = sanitize_text_field($_POST['first_name']);
	$last_name = sanitize_text_field($_POST['last_name']);
	$username = sanitize_user($_POST['username']);
	$email = sanitize_email($_POST['email']);
	$phone = sanitize_text_field($_POST['phone']);
	$password = $_POST['password'];
	$role = 'contributor'; // Fiksna role kao subscriber

	if (empty($username) || empty($email) || empty($password) || empty($first_name) || empty($last_name)) {
		return '<div class="alert alert-danger">Sva obavezna polja moraju biti popunjena.</div>';
	}

	if (!is_email($email)) {
		return '<div class="alert alert-danger">Unesite validnu email adresu.</div>';
	}

	// Provera da li username ili email već postoje
	if (username_exists($username)) {
		return '<div class="alert alert-danger">Korisničko ime već postoji.</div>';
	}

	if (email_exists($email)) {
		return '<div class="alert alert-danger">Email već postoji.</div>';
	}

	// Kreiranje novog usera
	$user_id = wp_create_user($username, $password, $email);

	if (is_wp_error($user_id)) {
		return '<div class="alert alert-danger">Greška pri kreiranju korisnika: ' . $user_id->get_error_message() . '</div>';
	}

	// Dodela role i dodatnih podataka
	$user = new WP_User($user_id);
	$user->set_role($role);

	// Čuvanje dodatnih podataka
	update_user_meta($user_id, 'parent_user_id', $parent_id);
	update_user_meta($user_id, 'first_name', $first_name);
	update_user_meta($user_id, 'last_name', $last_name);
	update_user_meta($user_id, 'phone', $phone);
	update_user_meta($user_id, 'account_status', 'active'); // Podrazumevano aktivni nalog

	// Slanje obaveštenja
	wp_new_user_notification($user_id, null, 'both');

	return '<div class="alert alert-success">Uspešno kreiran novi korisnik.</div>';
}

// Funkcija za obradu brisanja usera
function custom_handle_child_user_deletion($parent_id, $child_user_id) {
	// Provera nonce-a
	if (!isset($_GET['_wpnonce']) || !wp_verify_nonce($_GET['_wpnonce'], 'delete_child_user_' . $child_user_id)) {
		return '<div class="alert alert-danger">Greška u zahtevu.</div>';
	}

	// Provera da li je user zaista child od parenta
	$actual_parent_id = get_user_meta($child_user_id, 'parent_user_id', true);
	if ($actual_parent_id != $parent_id) {
		return '<div class="alert alert-danger">Nemate dozvolu za brisanje ovog korisnika.</div>';
	}

	// Brisanje usera
	require_once(ABSPATH.'wp-admin/includes/user.php');
	if (wp_delete_user($child_user_id)) {
		return '<div class="alert alert-success">Korisnik uspešno obrisan.</div>';
	} else {
		return '<div class="alert alert-danger">Greška pri brisanju korisnika.</div>';
	}
}

// Funkcija za obradu editovanja usera
function custom_handle_child_user_edit($parent_id, $user_id) {
	// Provera nonce-a
	if (!isset($_POST['edit_user_nonce']) || !wp_verify_nonce($_POST['edit_user_nonce'], 'edit_child_user_' . $user_id)) {
		return '<div class="alert alert-danger">Greška u zahtevu.</div>';
	}

	// Provera da li je user zaista child od parenta
	$actual_parent_id = get_user_meta($user_id, 'parent_user_id', true);
	if ($actual_parent_id != $parent_id) {
		return '<div class="alert alert-danger">Nemate dozvolu za izmenu ovog korisnika.</div>';
	}

	// Osnovna validacija
	$first_name = sanitize_text_field($_POST['first_name']);
	$last_name = sanitize_text_field($_POST['last_name']);
	$email = sanitize_email($_POST['email']);
	$phone = sanitize_text_field($_POST['phone']);

	if (empty($first_name) || empty($last_name) || empty($email)) {
		return '<div class="alert alert-danger">Sva obavezna polja moraju biti popunjena.</div>';
	}

	if (!is_email($email)) {
		return '<div class="alert alert-danger">Unesite validnu email adresu.</div>';
	}

	// Provera da li email pripada drugom korisniku
	$existing_user = get_user_by('email', $email);
	if ($existing_user && $existing_user->ID != $user_id) {
		return '<div class="alert alert-danger">Email već postoji.</div>';
	}

	// Ažuriranje osnovnih podataka
	wp_update_user(array(
		'ID' => $user_id,
		'user_email' => $email
	));

	// Ažuriranje dodatnih podataka
	update_user_meta($user_id, 'first_name', $first_name);
	update_user_meta($user_id, 'last_name', $last_name);
	update_user_meta($user_id, 'phone', $phone);

	// Ažuriranje passworda ako je promenjen
	if (!empty($_POST['password'])) {
		wp_set_password($_POST['password'], $user_id);
	}

	return '<div class="alert alert-success">Korisnički podaci uspešno ažurirani.</div>';
}

// Funkcija za obradu deaktivacije usera
function custom_handle_user_status_toggle($parent_id, $user_id) {
	// Provera nonce-a
	if (!isset($_GET['_wpnonce']) || !wp_verify_nonce($_GET['_wpnonce'], 'toggle_user_status_' . $user_id)) {
		return '<div class="alert alert-danger">Greška u zahtevu.</div>';
	}

	// Provera da li je user zaista child od parenta
	$actual_parent_id = get_user_meta($user_id, 'parent_user_id', true);
	if ($actual_parent_id != $parent_id) {
		return '<div class="alert alert-danger">Nemate dozvolu za izmenu statusa ovog korisnika.</div>';
	}

	// Provera trenutnog statusa i promena
	$current_status = get_user_meta($user_id, 'account_status', true);
	$new_status = ($current_status == 'active') ? 'disabled' : 'active';
	update_user_meta($user_id, 'account_status', $new_status);

	return '<div class="alert alert-success">Status korisnika uspešno promenjen.</div>';
}

// Funkcija za prikaz forme za kreiranje novog usera
function custom_get_child_user_form() {
	$form = '
    <form method="post" action="">
        ' . wp_nonce_field('create_child_user', 'child_user_nonce', true, false) . '
        <div class="mb-3">
            <label for="firstName" class="form-label">Ime</label>
            <input type="text" class="form-control" id="firstName" name="first_name" required>
        </div>
        <div class="mb-3">
            <label for="lastName" class="form-label">Prezime</label>
            <input type="text" class="form-control" id="lastName" name="last_name" required>
        </div>
        <div class="mb-3">
            <label for="username" class="form-label">Korisničko ime</label>
            <input type="text" class="form-control" id="username" name="username" required>
        </div>
        <div class="mb-3">
            <label for="email" class="form-label">Email</label>
            <input type="email" class="form-control" id="email" name="email" required>
        </div>
        <div class="mb-3">
            <label for="phone" class="form-label">Telefon</label>
            <input type="text" class="form-control" id="phone" name="phone">
        </div>
        <div class="mb-3">
            <label for="password" class="form-label">Password</label>
            <input type="password" class="form-control" id="password" name="password" required>
        </div>
        <div class="modal-footer">
            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
            <button type="submit" name="create_child_user" class="btn btn-primary">Save</button>
        </div>
    </form>';

	return $form;
}

// Funkcija za prikaz forme za editovanje usera
function custom_get_child_user_edit_form($user_id) {
	$user = get_userdata($user_id);
	$first_name = get_user_meta($user_id, 'first_name', true);
	$last_name = get_user_meta($user_id, 'last_name', true);
	$phone = get_user_meta($user_id, 'phone', true);

	$form = '
    <form method="post" action="">
        ' . wp_nonce_field('edit_child_user_' . $user_id, 'edit_user_nonce', true, false) . '
        <input type="hidden" name="user_id" value="' . esc_attr($user_id) . '">
        <div class="mb-3">
            <label for="editFirstName" class="form-label">Ime</label>
            <input type="text" class="form-control" id="editFirstName" name="first_name" value="' . esc_attr($first_name) . '" required>
        </div>
        <div class="mb-3">
            <label for="editLastName" class="form-label">Prezime</label>
            <input type="text" class="form-control" id="editLastName" name="last_name" value="' . esc_attr($last_name) . '" required>
        </div>
        <div class="mb-3">
            <label for="editEmail" class="form-label">Email</label>
            <input type="email" class="form-control" id="editEmail" name="email" value="' . esc_attr($user->user_email) . '" required>
        </div>
        <div class="mb-3">
            <label for="editPhone" class="form-label">Telefon</label>
            <input type="text" class="form-control" id="editPhone" name="phone" value="' . esc_attr($phone) . '">
        </div>
        <div class="mb-3">
            <label for="editPassword" class="form-label">Novi password (ostavite prazno ako ne želite da promenite)</label>
            <input type="password" class="form-control" id="editPassword" name="password">
        </div>
        <div class="modal-footer">
            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
            <button type="submit" name="edit_child_user" class="btn btn-primary">Save Changes</button>
        </div>
    </form>';

	return $form;
}

// Funkcija za prikaz liste child usera
function custom_get_child_users_list($parent_id) {
	$args = array(
		'meta_key' => 'parent_user_id',
		'meta_value' => $parent_id,
		'meta_compare' => '='
	);

	$users = get_users($args);

	if (empty($users)) {
		return '<div class="alert alert-info">Nema kreiranih korisnika.</div>';
	}

	$list = '
    <div class="table-responsive">
        <table class="table table-striped table-hover">
            <thead class="table-dark">
                <tr>
                    <th>Ime i prezime</th>
                    <th>Korisničko ime</th>
                    <th>Telefon</th>
                    <th>Status</th>
                    <th>Akcije</th>
                </tr>
            </thead>
            <tbody>';

	foreach ($users as $user) {
		$first_name = get_user_meta($user->ID, 'first_name', true);
		$last_name = get_user_meta($user->ID, 'last_name', true);
		$phone = get_user_meta($user->ID, 'phone', true);
		$status = get_user_meta($user->ID, 'account_status', true);
		$status_text = ($status == 'active') ? 'Aktivan' : 'Deaktiviran';
		$status_class = ($status == 'active') ? 'success' : 'danger';

		$delete_url = wp_nonce_url(
			add_query_arg('delete_child_user', $user->ID),
			'delete_child_user_' . $user->ID
		);

		$toggle_status_url = wp_nonce_url(
			add_query_arg('toggle_user_status', $user->ID),
			'toggle_user_status_' . $user->ID
		);

		$list .= '
            <tr>
                <td>' . esc_html($first_name . ' ' . $last_name) . '</td>
                <td>' . esc_html($user->user_login) . '</td>
                <td>' . esc_html($phone) . '</td>
                <td><span class="badge bg-' . $status_class . '">' . $status_text . '</span></td>
                <td>
                    <button class="btn btn-sm btn-primary edit-user-btn" data-user-id="' . $user->ID . '"><i class="bi bi-pencil-square"></i></button>
                    <a href="' . $toggle_status_url . '" class="btn btn-sm btn-' . (($status == 'active') ? 'warning' : 'success') . '">' . (($status == 'active') ? '<i class="bi bi-x-circle"></i>' : '<i class="bi bi-check-circle"></i>') . '</a>
                    <a href="' . $delete_url . '" class="btn btn-sm btn-danger" onclick="return confirm(\'Da li ste sigurni da želite da obrišete ovog korisnika?\')"><i class="bi bi-trash"></i></a>
                </td>
            </tr>';
	}

	$list .= '
            </tbody>
        </table>
    </div>';

	return $list;
}

// Funkcija za JavaScript
function custom_get_child_users_js() {
	return '
    <script>
    jQuery(document).ready(function($) {
        // Otvaranje edit forme preko AJAX-a
        $(".edit-user-btn").click(function() {
            var userId = $(this).data("user-id");
            
            $.ajax({
                url: "' . admin_url('admin-ajax.php') . '",
                type: "POST",
                data: {
                    action: "get_child_user_edit_form",
                    user_id: userId,
                    nonce: "' . wp_create_nonce('get_edit_form_nonce') . '"
                },
                beforeSend: function() {
                    $("#editUserFormContainer").html("<p>Loading...</p>");
                },
                success: function(response) {
                    $("#editUserFormContainer").html(response);
                    $("#editUserFormModal").modal("show");
                },
                error: function() {
                    $("#editUserFormContainer").html("<p>Error loading form.</p>");
                }
            });
        });
    });
    </script>';
}

// AJAX handler za dobavljanje edit forme
add_action('wp_ajax_get_child_user_edit_form', 'ajax_get_child_user_edit_form');
function ajax_get_child_user_edit_form() {
	// Provera nonce-a
	if (!isset($_POST['nonce']) || !wp_verify_nonce($_POST['nonce'], 'get_edit_form_nonce')) {
		wp_die('Greška u zahtevu.');
	}

	// Provera da li je user_id prosleđen
	if (!isset($_POST['user_id'])) {
		wp_die('Korisnik nije pronađen.');
	}

	$user_id = intval($_POST['user_id']);
	$current_user = wp_get_current_user();

	// Provera da li je user zaista child od parenta
	$actual_parent_id = get_user_meta($user_id, 'parent_user_id', true);
	if ($actual_parent_id != $current_user->ID) {
		wp_die('Nemate dozvolu za izmenu ovog korisnika.');
	}

	echo custom_get_child_user_edit_form($user_id);
	wp_die();
}