jQuery(document).ready(function($) {
    $('#custom-contact-form').submit(function(event) {
        event.preventDefault();

        // Validate form fields (you can add more validation as needed)
        var name = $('#name').val();
        var email = $('#email').val();
        var subject = $('#subject').val();
        var message = $('#message').val();

        // Validate email format
        var emailPattern = /^[a-zA-Z0-9._-]+@[a-zA-Z0-9.-]+\.[a-zA-Z]{2,4}$/;
       
        if (!emailPattern.test(email)) {
            alert(' cheack valid email address.');
            return;
        }

        if (!name || !email || !subject || !message) {
            alert('Please fill in all fields.');
            return;
        }
         
        // Send data via AJAX
        $.ajax({
            type: 'POST',
            url: custom_contact_form_ajax.ajax_url,
            data: {
                action: 'custom_contact_form_submit', // Action to trigger in WordPress
                name: $('#name').val(),
                email: $('#email').val(),
                subject: $('#subject').val(),
                message: $('#message').val()
            },
            success: function(response) {
                // Handle success or error responses
                if (response === 'success') {
                    alert('Form submitted successfully!');
                } else {
                    alert('An error occurred: ' + response);
                }
            },
            error: function(xhr, textStatus, errorThrown) {
                // Handle AJAX error
                console.error('AJAX Error: ' + errorThrown);
            }
        });
    });
});
