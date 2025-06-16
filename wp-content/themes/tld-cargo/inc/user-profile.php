<?php

// Dodavanje i ređanje tabova
add_filter( 'uwp_account_available_tabs', 'tld_account_available_tabs_cb', 10, 1 );
function tld_account_available_tabs_cb( $tabs ) {
	// Tab "Company"
	$tabs['cargo-data'] = array(
		'title' => __( 'Moji utovari', 'userswp' ),
		'icon'  => 'fa-solid fa-truck-front',
		'order' => 12
	);

	// Tab "Users"
	$tabs['users'] = array(
		'title' => __( 'Users', 'userswp' ),
		'icon'  => 'fas fa-users',
		'order' => 11
	);

    unset($tabs[ 'privacy' ]);

	// Postavi podrazumevani redosled za postojeće tabove
    $count = 1;
	foreach ( $tabs as $key => $tab ) {
		if ( ! isset( $tab['order'] ) ) {
			$tabs[ $key ]['order'] = $count*10;
		}
        $count++;
	}
	// Sortiraj tabove po redosledu
	uasort( $tabs, function ( $a, $b ) {
		return $a['order'] - $b['order'];
	} );

	return $tabs;
}

// Naslov za Company tab
add_filter( 'uwp_account_page_title', 'tld_account_page_title_cb', 10, 2 );
function tld_account_page_title_cb( $title, $type ) {
	if ( $type == 'company' ) {
		$title = __( 'Company Information', 'userswp' );
	} elseif ( $type == 'users' ) {
		$title = __( 'User Management', 'userswp' );
	}

	return $title;
}

// Sadržaj za tabove
add_filter( 'uwp_account_form_display', 'tld_account_form_display_cb', 10, 1 );
function tld_account_form_display_cb( $type ): void {
	if ( $type == 'company' ) {
		?>
        <div class="uwp-account-company">
            <form id="userForm">
                <input type="hidden" id="userNumber" name="userNumber" value="<?php echo get_current_user_id() ?>">

                <div class="mb-3">
                    <label for="type" class="form-label">Type</label>
                    <select class="form-select" id="type" name="type" required>
                        <option value="" selected disabled>Select type</option>
                        <?php
                        $company_types = get_field( 'tld_company_type', 'option' );
                        foreach ( $company_types as $type ):
                        ?>
                        <option value="text"><?php echo $type['type'] ?></option>
                        <?php endforeach; ?>
                    </select>
                </div>

                <div class="mb-3">
                    <label for="company" class="form-label">Company</label>
                    <input type="text" class="form-control" id="company" name="company" required>
                </div>

                <div class="mb-3">
                    <label for="pib" class="form-label">PIB</label>
                    <input type="text" class="form-control" id="pib" name="pib" required>
                </div>

                <div class="mb-3">
                    <label for="email" class="form-label">Email</label>
                    <input type="email" class="form-control" id="email" name="email" required>
                </div>

                <div class="mb-3">
                    <label for="city" class="form-label">City</label>
                    <input type="text" class="form-control" id="city" name="city" required>
                </div>

                <div class="mb-3">
                    <label for="address" class="form-label">Address</label>
                    <input type="text" class="form-control" id="address" name="address" required>
                </div>

                <button type="submit" class="btn btn-primary">Submit</button>
            </form>
        </div>
		<?php
	} elseif ( $type == 'users' ) {
		?>
        <div class="uwp-account-users">
		    <?php echo do_shortcode( '[child_users_dashboard]' )?>
        </div>
        <?php
	}
}


function tld_get_acf_for_account_tab($type) {
	if ( isset( $_GET['type'] ) ) {
		$type = strip_tags( esc_sql( $_GET['type'] ) );
	} else {
		$type = 'account';
	}
	$data = get_field( 'ald_account_data_description', 'option' );

    $output = '';
    switch ( $type ) {
        case 'cargo-data':
            $output = $data['cargo-data'];
            break;
	    case 'notifications':
		    $output = $data['notifications'];
            break;
	    case 'users':
		    $output = $data['users'];
		    break;
	    case 'change-password':
		    $output = $data['change-password'];
            break;
        default:
    }

    return $output;
}
