jQuery(document).ready(function($) {
    $('#fetch_parseable_streams_button').on('click', function() {
        var url = $('input[name="wp_logs_to_parseable_options[wp_logs_to_parseable_url]"]').val();
        var username = $('input[name="wp_logs_to_parseable_options[wp_logs_to_parseable_username]"]').val();
        var password = $('input[name="wp_logs_to_parseable_options[wp_logs_to_parseable_password]"]').val();

        // Clear previous error messages
        $('#parseable_streams_error').remove();

        $.ajax({
            url: ajaxurl,
            method: 'POST',
            data: {
                action: 'fetch_parseable_streams',
                url: url,
                username: username,
                password: password,
            },
            success: function(response) {
                if (response.success) {
                    location.reload();
                } else {
                    displayError(response.data.message);
                }
            },
            error: function() {
                displayError('<?php _e('Error fetching log streams.', 'wp-logs-to-parseable'); ?>');
            }
        });
    });

    function displayError(message) {
        var errorHtml = '<div id="parseable_streams_error" class="notice notice-error"><p>' + message + '</p></div>';
        $('.wrap').prepend(errorHtml);
    }
});
