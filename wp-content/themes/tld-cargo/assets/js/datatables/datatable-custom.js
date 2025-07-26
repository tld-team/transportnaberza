// Funkcija koja formatira detalje za red (možeš je modifikovati po potrebi)
function formatRowDetails(d) {
    return `
        <dl>
            <dt>Full name:</dt>
            <dd>${d.location_to}</dd>
            <dt>Extension number:</dt>
            <dd>${d.vehicle_type}</dd>
            <dt>Extra info:</dt>
            <dd>And any further details here (images etc)...</dd>
        </dl>
    `;
}


new DataTable('#example', {
    processing: false,
    serverSide: true,
    ajax: {
        url: '../wp-content/themes/tld-cargo/inc/table-shortcode/cargo-shortcode-server_side.php',
        type: 'GET',
        dataFilter: function (rawResponse) {
            console.log("Raw server response:", rawResponse);

            try {
                const json = JSON.parse(rawResponse);
                console.log("Parsed JSON:", json);
                return JSON.stringify(json); // DataTable očekuje string
            } catch (e) {
                console.error("JSON parsing error:", e);
                return '{"data": []}'; // Vrati prazan podatak ako JSON nije validan
            }
        },
        error: function (xhr, error, thrown) {
            console.error('AJAX error:', xhr.responseText);
        }
    },
    columns: [
        {
            data: 'date_from',
            className: 'dt-control2',
            defaultContent: ''
        },
        {
            data: 'location_from',
            className: 'dt-control2',
            defaultContent: ''
        },
        {
            data: 'date_to',
            className: 'dt-control2',
            defaultContent: ''
        },
        {
            data: 'location_to',
            className: 'dt-control2',
            defaultContent: ''
        },
        {
            data: 'vehicle_type',
            className: 'dt-control2',
            defaultContent: ''
        },
        {
            data: 'trailer',
            className: 'dt-control2',
            defaultContent: ''
        },
        {
            data: 'country_from',
            className: 'dt-control2',
            defaultContent: ''
        },
        {
            data: 'country_to',
            className: 'dt-control2',
            defaultContent: ''
        }
    ],
    order: [[1, 'asc']]
});
