jQuery(document).ready(function($) {
    $('#userForm').on('submit', function(e) {
        e.preventDefault();
        alert("test2")
        // Show loading state
        $('button[type="submit"]').prop('disabled', true).html(
            '<span class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span> Processing...'
        );

        // Collect form data
        var formData = $(this).serialize();

        // AJAX request
        $.ajax({
            url: tld_ajax_company.ajax_url,
            type: 'POST',
            data: formData + '&action=tld_ajax_company&nonce=' + tld_ajax_company.nonce,
            dataType: 'json',
            success: function(response) {
                if(response.success) {
                    $('#responseMessage').html(
                        '<div class="alert alert-success">' + response.data.message + '</div>'
                    );

                    // Clear form if needed
                    if(response.data.clearForm) {
                        $('#userForm')[0].reset();
                    }

                    // Update hidden user number if returned
                    if(response.data.userNumber) {
                        $('#userNumber').val(response.data.userNumber);
                    }
                } else {
                    $('#responseMessage').html(
                        '<div class="alert alert-danger">' + response.data + '</div>'
                    );
                }
            },
            error: function(xhr, status, error) {
                $('#responseMessage').html(
                    '<div class="alert alert-danger">Error: ' + xhr.responseText + '</div>'
                );
            },
            complete: function() {
                $('button[type="submit"]').prop('disabled', false).html('Submit');
            }
        });
    });
});