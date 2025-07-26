<?php

// Apsolutna putanja do wp-load.php (prilagodite prema vašoj instalaciji)

require_once($_SERVER['DOCUMENT_ROOT'] . '/wp-load.php');
//require_once 'wp-load.php';
add_action('wp_loaded', 'cargo_shortcode_server_side');
function cargo_shortcode_server_side() {


// Inicijalizuj klasu
$db_handler = new TLD_Cargo_Database_Handler();

// Dohvati DataTables parametre
$draw = isset($_GET['draw']) ? intval($_GET['draw']) : 1;
$start = isset($_GET['start']) ? intval($_GET['start']) : 0;
$length = isset($_GET['length']) ? intval($_GET['length']) : 10;
$search = isset($_GET['search']['value']) ? sanitize_text_field($_GET['search']['value']) : '';
$order = isset($_GET['order'][0]) ? $_GET['order'][0] : [];
$order_column_idx = isset($order['column']) ? intval($order['column']) : 0;
$order_dir = isset($order['dir']) ? sanitize_text_field($order['dir']) : 'asc';

// Mapiraj indeks kolone na naziv kolone
$columns = [
    'date_from',
    'location_from',
    'date_to',
    'location_to',
    'vehicle_type',
    'trailer',
    'country_from',
    'country_to'
];
$order_column = $columns[$order_column_idx] ?? 'date_from';

// Dohvati podatke iz tvoje metode
// Pretpostavka: get_extended_record_by_date može prihvatiti dodatne parametre ili se prilagođava
$records = $db_handler->get_extended_record_by_date(12); // Prilagodi parametar '12' ako je potrebno

// Ako metoda ne podržava paginaciju/pretragu, primeni ih ručno
$data = $records;
if (!empty($search)) {
    $data = array_filter($records, function($row) use ($search) {
        return stripos($row['location_from'], $search) !== false ||
               stripos($row['location_to'], $search) !== false ||
               stripos($row['vehicle_type'], $search) !== false;
    });
    $data = array_values($data); // Reindeksiraj niz
}

// Primenjuj sortiranje
// if (!empty($order_column)) {
//     usort($data, function($a, $b) use ($order_column, $order_dir) {
//         $value_a = $a[$order_column] ?? '';
//         $value_b = $b[$order_column] ?? '';
//         return ($order_dir === 'asc') ? strcmp($value_a, $value_b) : -strcmp($value_a, $value_b);
//     });
// }

// Primenjuj paginaciju
$data = array_slice($data, $start, $length);

// Ukupan i filtriran broj redova
$recordsTotal = count($records);
$recordsFiltered = empty($search) ? $recordsTotal : count($data);

// Pripremi odgovor za DataTables
$response = [
    'draw' => $draw,
    'recordsTotal' => $recordsTotal,
    'recordsFiltered' => $recordsFiltered,
    'data' => $data
];

// Postavi JSON header i vrati odgovor
header('Content-Type: application/json');
echo json_encode($response);
exit;



}





