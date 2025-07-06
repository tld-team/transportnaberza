<?php
// cron-fetch-api.php

// Uključivanje WordPress okruženja ako je potrebno
if (!defined('ABSPATH')) {
    require_once(__DIR__ . '/wp-load.php');
}


function addUniqueIDIfNotExists($newID, $filename = 'unique_id.json'): bool
    {
        $data = [];

        $filename = get_template_directory() . '/' . $filename;
        // Ako fajl postoji, učitaćemo postojeće podatke
        if (file_exists($filename)) {
            $jsonContent = file_get_contents($filename);
            $data = json_decode($jsonContent, true);

            // Provera da li ID već postoji u nizu
            if (isset($data['uniqueID']) && in_array($newID, $data['uniqueID'])) {
                return false;
            }
        }

        // Ako fajl ne postoji ili ID nije pronađen, dodajemo ga
        if (!isset($data['uniqueID'])) {
            $data['uniqueID'] = [];
        }

        $data['uniqueID'][] = $newID; // Dodaj novi ID
        file_put_contents($filename, json_encode($data, JSON_PRETTY_PRINT));

        return true;
    }

// Vaša funkcija za dobavljanje podataka sa API-ja
function fetchAPI() {
    $curl = curl_init();

    curl_setopt_array($curl, array(
        CURLOPT_URL => 'https://v0-new-project-zotfivf5trm.vercel.app/offers',
        CURLOPT_RETURNTRANSFER => true,
        CURLOPT_ENCODING => '',
        CURLOPT_MAXREDIRS => 10,
        CURLOPT_TIMEOUT => 0,
        CURLOPT_FOLLOWLOCATION => true,
        CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
        CURLOPT_CUSTOMREQUEST => 'POST',
        CURLOPT_POSTFIELDS => json_encode([
            "username" => "767174",
            "password" => "486710"
        ]),
        CURLOPT_HTTPHEADER => array(
            'consumer_secret: cs_51d76db56c23a4aea8cb9a3be2afdcc743268449',
            'consumer_key: ck_fc589b296d70f87af0ed26658058b4e3003a8dba',
            'Content-Type: application/json'
        ),
    ));

    $response = curl_exec($curl);

    if (curl_errno($curl)) {
        error_log('Greška pri dobavljanju podataka sa API-ja: ' . curl_error($curl));
        return false;
    }

    curl_close($curl);

    return json_decode($response, true); // vraćamo kao asocijativni niz
}

// Glavna funkcija za procesiranje
function process_api_data() {
    // Proverite da li klasa postoji
    if (!class_exists('TLD_Cargo_Database_Handler')) {
        error_log('TLD Cargo plugin nije aktivan, ne mogu obraditi podatke.');
        return false;
    }

    // Dobavi podatke sa API-ja
    $api_data = fetchAPI();
    
    if (!$api_data || empty($api_data)) {
        error_log('Nema podataka sa API-ja ili je došlo do greške.');
        return false;
    }

    $db_handler = new TLD_Cargo_Database_Handler();
    $inserted_ids = [];

    // Procesiraj svaki oglas iz API odgovora
    foreach ($api_data as $offer) {
        if (!addUniqueIDIfNotExists($offer['uniqueID'])) continue;

	    $firma = $offer['firma'];

		$company = allow_clients($firma);
		if (!$company) continue;
        try {
            // Priprema order_data niza
            $order_data = [
                'user' => $company,
                'date_from' => date('Y-m-d', strtotime($offer['spreman_utovar'])),
                'date_to' => date('Y-m-d', strtotime($offer['rok_isporuke'])),
                'date_from_plus' => date('Y-m-d', strtotime($offer['spreman_utovar'])),
                'date_to_plus' => date('Y-m-d', strtotime($offer['rok_isporuke'])),
                'vehicle_type' => $offer['type'],
                'trailer' => '',
                'location_from' => $offer['from_grad'],
                'location_to' => $offer['to_grad'],
                'country_from' => $offer['from_drzava'],
                'country_to' => $offer['to_drzava'],
                'zip_from' => $offer['from_postanski_broj'],
                'zip_to' => $offer['to_postanski_broj'],
                'distance' => 0,
                'weight' => $offer['weight'],
                'length' => $offer['size'],
                'height' => 0,
                'price' => 0,
                'deactive' => 0,
                'description' => '',
            ];

            // Ubaci podatke u bazu
            $inserted_id = $db_handler->insert_data($order_data);

            if ($inserted_id) {
                $inserted_ids[] = $inserted_id;
                error_log('Podaci uspešno upisani u bazu podataka sa ID: ' . $inserted_id);
            } else {
                error_log('Greška pri upisu podataka u bazu podataka za oglas: ' . ($scraping_data['uniqueID'] ?? 'Nepoznat ID'));
            }
        } catch (Exception $e) {
            error_log('Greška pri obradi oglasa: ' . $e->getMessage());
            continue;
        }
    }

    return $inserted_ids;
}

// Registruj CRON akciju
add_action('init', function() {
    if (!wp_next_scheduled('tld_cargo_fetch_api')) {
        wp_schedule_event(time(), 'five_minutes', 'tld_cargo_fetch_api');
    }
});

// Definiši custom interval
add_filter('cron_schedules', function($schedules) {
    $schedules['five_minutes'] = array(
        'interval' => 300, // 300 sekundi = 5 minuta
        'display'  => __('Svakih 5 minuta')
    );
    return $schedules;
});

// Dodaj funkciju koja će se izvršavati
add_action('tld_cargo_fetch_api', 'process_api_data');

/** return user from our site */
function allow_clients($company) {

	$companys = get_field('company_info', 'option');

	foreach ($companys as $item) {
		if (isset($item['cargo_name']) && $item['cargo_name'] === $company) {
			return $item['our_name'];
		}
	}
	return false;
}