jQuery(document).ready(function($) {
    $('#fetch_parseable_streams_button').on('click', function() {
        var url = $('input[name="wp_logs_to_parseable_options[wp_logs_to_parseable_url]"]').val();
        var username = $('input[name="wp_logs_to_parseable_options[wp_logs_to_parseable_username]"]').val();
        var password = $('input[name="wp_logs_to_parseable_options[wp_logs_to_parseable_password]"]').val();

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
                    alert(response.data.message);
                }
            },
            error: function() {
                alert('<?php _e('Error fetching log streams.', 'wp-logs-to-parseable'); ?>');
            }
        });
    });
});
