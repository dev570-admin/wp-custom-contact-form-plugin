<?php
/*
Plugin Name: Custom Contact Form Plugin
Description: A custom contact form plugin for WordPress.
Version: 1.0
*/


function custom_form() {
    wp_enqueue_script('custom-form-validation', plugin_dir_url(__FILE__) . 'custom-form-validation.js', array('jquery'), '1.0', true);

    // Localize the AJAX URL for your script
    wp_localize_script('custom-form-validation', 'custom_contact_form_ajax', array(
        'ajax_url' => admin_url('admin-ajax.php')
    ));
}


 add_action('wp_enqueue_scripts', 'custom_form');
// AJAX function to handle form submission
function custom_contact_form_ajax() {
    global $wpdb;

    // Retrieve form data from the AJAX request
    $name = sanitize_text_field($_POST['name']);
    $email = sanitize_email($_POST['email']);
    $subject = sanitize_text_field($_POST['subject']);
    $message = sanitize_text_field($_POST['message']);

    // Define the table name
    $table_name = $wpdb->prefix . 'custom_form'; // Adjust the table name with the prefix

    // Insert data into the custom table
    $insert_result = $wpdb->insert(
        $table_name,
        array(
            'id'=>'',
            'name' => $name,
            'email' => $email,
            'sub' => $subject, // Make sure this matches your actual table column name
            'message' => $message,
        ),
        array(
            '%s',
            '%s',
            '%s',
            '%s'
        )
    );

    if ($insert_result === false) {
        // Error occurred during insertion
        echo 'error: ' . $wpdb->last_error;
    } else {
        // Form data inserted successfully
        echo 'success';
    }

    wp_die();
}
add_action('wp_ajax_custom_contact_form_submit', 'custom_contact_form_ajax'); // For logged-in users
add_action('wp_ajax_nopriv_custom_contact_form_submit', 'custom_contact_form_ajax'); // For non-logged-in users


// Create the form HTML
function custom_contact_form() {
    ob_start();
    ?>
   <form method="POST"  name="contactForm" class="contactForm" id="custom-contact-form">
        <div class="row">
            <div class="col-md-6">
                <div class="form-group">
                    <label class="label" for="name">Full Name</label>
                    <input type="text" class="form-control" name="name" id="name" placeholder="Name">
                </div>
            </div>
            <div class="col-md-6"> 
                <div class="form-group">
                    <label class="label" for="email">Email Address</label>
                    <input type="email" class="form-control" name="email" id="email" placeholder="Email">
                </div>
            </div>
            <div class="col-md-12">
                <div class="form-group">
                    <label class="label" for="subject">Subject</label>
                    <input type="text" class="form-control" name="subject" id="subject" placeholder="Subject">
                </div>
            </div>
            <div class="col-md-12">
                <div class="form-group">
                    <label class="label" for="#">Message</label>
                    <textarea name="message" class="form-control" id="message" cols="30" rows="4" placeholder="Message"></textarea>
                </div>
            </div>
            <div class="col-md-12">
                <div class="form-group">
                    <input type="submit" value="Send Message" class="btn btn-primary">
                    <div class="submitting"></div>
                </div>
            </div>
        </div>
    </form>

   
    <?php
    return ob_get_clean();
}
add_shortcode('custom_contact_form', 'custom_contact_form');


// retriving Data in Dash board 
function custom_form_admin_menu() {
    add_menu_page('Custom Form Data', 'Form Data', 'manage_options', 'custom-form-data', 'custom_form_data_page');
}
add_action('admin_menu', 'custom_form_admin_menu');


function custom_form_data_page() {
    global $wpdb;
    $table_name = $wpdb->prefix . 'custom_form'; // Adjust the table name with the prefix

    $data = $wpdb->get_results("SELECT * FROM $table_name");

    echo '<div class="wrap">';
    echo '<h2>Custom Form Data</h2>';
    echo '<table class="widefat">';
    echo '<thead><tr><th>Name</th><th>Email</th><th>Subject</th><th>Message</th></tr></thead>';
    echo '<tbody>';

    foreach ($data as $row) {
        echo '<tr>';
        echo '<td>' . esc_html($row->name) . '</td>';
        echo '<td>' . esc_html($row->email) . '</td>';
        echo '<td>' . esc_html($row->sub) . '</td>'; // Adjust for your table column name
        echo '<td>' . esc_html($row->message) . '</td>';
        echo '</tr>';
    }

    echo '</tbody>';
    echo '</table>';
    echo '</div>';
}

