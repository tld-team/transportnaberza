
jQuery(document).ready(function($) {
    alert('test');
    new DataTable('#example', {
        order: [[1, 'asc']]
    });
});
