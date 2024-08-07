<?php
/*
Plugin Name: Support Ticket System
Description: Adds a support ticket system to WooCommerce's My Account page and WordPress admin panel.
Version: 1.1
Author: Your Name
*/

// Exit if accessed directly.
if (!defined('ABSPATH')) {
    exit;
}

// Define the plugin directory path.
define('SUPPORT_TICKET_PLUGIN_PATH', plugin_dir_path(__FILE__));

// Define the plugin URL path.
define('SUPPORT_TICKET_PLUGIN_URL', plugin_dir_url(__FILE__));

// Enqueue the plugin's custom CSS.
function support_ticket_enqueue_assets() {
    // Enqueue CSS files
    wp_enqueue_style(
        'support-ticket-style',
        SUPPORT_TICKET_PLUGIN_URL . 'css/style.css',
        array(),
        '1.0.0'
    );

    wp_enqueue_style(
        'support-lightbox',
        SUPPORT_TICKET_PLUGIN_URL . 'css/lightbox.min.css',
        array(),
        '1.0.1'
    );

    // Enqueue JS files
    wp_enqueue_script(
        'support-lightbox-js',
        SUPPORT_TICKET_PLUGIN_URL . 'js/lightbox.min.js',
        array('jquery'),
        '1.0.1',
        true
    );
    wp_enqueue_script(
        'support-lightbox-custom-js',
        SUPPORT_TICKET_PLUGIN_URL . 'js/lightbox-custom.js',
        array('jquery', 'support-lightbox-js'),
        '1.0.0',
        true
    );
}
add_action('wp_enqueue_scripts', 'support_ticket_enqueue_assets');


// Include necessary files.
include(SUPPORT_TICKET_PLUGIN_PATH . 'includes/admin_support_ticket.php');



// Add custom endpoint to My Account page
function sts_add_support_ticket_endpoint()
{
    add_rewrite_endpoint('support-ticket-system', EP_ROOT | EP_PAGES);
}
add_action('init', 'sts_add_support_ticket_endpoint');

// Flush rewrite rules on plugin activation
function sts_activate_plugin()
{
    sts_add_support_ticket_endpoint();
    flush_rewrite_rules();
}
register_activation_hook(__FILE__, 'sts_activate_plugin');

// Flush rewrite rules on plugin deactivation
function sts_deactivate_plugin()
{
    flush_rewrite_rules();
}
register_deactivation_hook(__FILE__, 'sts_deactivate_plugin');


// Add menu item to My Account navigation
function sts_add_support_ticket_link($items)
{
    // Remove and store the logout menu item
    $logout = $items['customer-logout'];
    unset($items['customer-logout']);

    // Add the new support ticket item before logout
    $new_items = [];
    foreach ($items as $key => $item) {
        $new_items[$key] = $item;
        if ($key === 'edit-account') { // You can change 'edit-account' to any existing menu key
            $new_items['support-ticket-system'] = __('Support', 'support-ticket-system');
        }
    }

    // Add the logout item back to the end
    $new_items['customer-logout'] = $logout;

    return $new_items;
}
add_filter('woocommerce_account_menu_items', 'sts_add_support_ticket_link');


// Display the content for the new menu item
function support_ticket_content()
{
    // Check if a specific ticket ID is provided in the URL
    $ticket_id = isset($_GET['ticket_id']) ? absint($_GET['ticket_id']) : null;

    if ($ticket_id) {
        // Redirect to user-reply.php with the ticket ID
        include SUPPORT_TICKET_PLUGIN_PATH . 'includes/user-reply.php';
    } else {
        // Check if the user has any messages
        $current_user_id = get_current_user_id();

        if ($current_user_id) {
            global $wpdb;
            $table_name_tickets = $wpdb->prefix . 'support_tickets';
            $user_has_messages = $wpdb->get_var($wpdb->prepare("SELECT COUNT(*) FROM $table_name_tickets WHERE user_id = %d", $current_user_id));

            if ($user_has_messages > 0) {
                include SUPPORT_TICKET_PLUGIN_PATH . 'includes/user-message-list.php';
            } else {
                include SUPPORT_TICKET_PLUGIN_PATH . 'includes/first-page.php';
            }
        } else {
            include SUPPORT_TICKET_PLUGIN_PATH . 'includes/first-page.php';
        }
    }
}
add_action('woocommerce_account_support-ticket-system_endpoint', 'support_ticket_content');

function enqueue_my_custom_scripts()
{
    wp_enqueue_script('my-ajax-script', plugins_url('/assets/js/ajax-handler.js', __FILE__), array('jquery'), null, true);

    // Localize script to pass AJAX URL and other variables to JavaScript
    wp_localize_script('my-ajax-script', 'ajax_object', array(
        'ajax_url' => admin_url('admin-ajax.php'),
        'ticket_id' => isset($_GET['ticket_id']) ? intval($_GET['ticket_id']) : 0
    ));
}
add_action('wp_enqueue_scripts', 'enqueue_my_custom_scripts');

// function sts_enqueue_scripts() {
//     wp_enqueue_script('sts-ajax-handler', plugin_dir_url(__FILE__) . 'assets/js/ajax-handler.js', array('jquery'), null, true);

//     // Localize script to pass ajax_url
//     wp_localize_script('sts-ajax-handler', 'ajax_object', array(
//         'ajax_url' => admin_url('admin-ajax.php')
//     ));
// }
// add_action('wp_enqueue_scripts', 'sts_enqueue_scripts');


// function fetch_replies() {
//     global $wpdb;

//     // Ensure the user is logged in
//     if (!is_user_logged_in()) {
//         wp_send_json_error('User not logged in');
//         wp_die();
//     }

//     // Get ticket ID from the AJAX request
//     $ticket_id = isset($_POST['ticket_id']) ? intval($_POST['ticket_id']) : 0;

//     if (!$ticket_id) {
//         wp_send_json_error('No ticket ID provided');
//         wp_die();
//     }

//     // Query replies from the database
//     $replies_table = $wpdb->prefix . 'support_ticket_replies';
//     $replies = $wpdb->get_results($wpdb->prepare("SELECT * FROM $replies_table WHERE ticket_id = %d ORDER BY replied_at ASC", $ticket_id));

//     if ($replies) {
//         $previous_date = null;

//         foreach ($replies as $reply) {
//             $user_info = get_userdata($reply->user_id);
//             $message_class = in_array('administrator', $user_info->roles) ? 'in-coming' : 'outgoing-message';
//             $message_date = date('j/n/Y', strtotime($reply->replied_at));

//             if ($previous_date !== $message_date) {
//                 echo '<div class="line-38"><div class="date">' . esc_html($message_date) . '</div></div>';
//                 $previous_date = $message_date;
//             }

//             echo '<div class="message-item ' . esc_attr($message_class) . '"><div class="message-content">' . esc_html($reply->message) . '</div></div>';
//         }
//     } else {
//         echo '<p>' . esc_html__('No replies found.', 'support-ticket-system') . '</p>';
//     }

//     wp_die(); // This is required to terminate immediately and return a proper response
// }
// add_action('wp_ajax_fetch_replies', 'fetch_replies');
// add_action('wp_ajax_nopriv_fetch_replies', 'fetch_replies'); // For non-logged-in users, if needed

function sts_create_tickets_table()
{
    global $wpdb;

    $tickets_table = $wpdb->prefix . 'support_tickets';
    $replies_table = $wpdb->prefix . 'support_ticket_replies';
    $charset_collate = $wpdb->get_charset_collate();

    $tickets_sql = "CREATE TABLE $tickets_table (
        id mediumint(9) NOT NULL AUTO_INCREMENT,
        user_id bigint(20) NOT NULL,
        name tinytext NOT NULL,
        phone_number varchar(20) NOT NULL,
        subject tinytext NOT NULL,
        order_id varchar(255) NOT NULL,
        message text NOT NULL,
        submitted_at datetime DEFAULT '0000-00-00 00:00:00' NOT NULL,
        image_url varchar(255) DEFAULT NULL,
        status tinyint(1) DEFAULT 1 NOT NULL,
        PRIMARY KEY (id)
    ) $charset_collate;";

    $replies_sql = "CREATE TABLE $replies_table (
        id mediumint(9) NOT NULL AUTO_INCREMENT,
        ticket_id mediumint(9) NOT NULL,
        user_id bigint(20) NOT NULL,
        message text NOT NULL,
        replied_at datetime DEFAULT '0000-00-00 00:00:00' NOT NULL,
        image_url varchar(255) DEFAULT NULL,
        PRIMARY KEY (id),
        FOREIGN KEY (ticket_id) REFERENCES $tickets_table(id) ON DELETE CASCADE
    ) $charset_collate;";

    require_once(ABSPATH . 'wp-admin/includes/upgrade.php');
    dbDelta($tickets_sql);
    dbDelta($replies_sql);
}
register_activation_hook(__FILE__, 'sts_create_tickets_table');
// Handle ticket submission
function sts_handle_ticket_submission()
{
    // echo "ok";
    // exit;
    if (isset($_POST['sts_submit_ticket'])) {

        global $wpdb;

        $name = sanitize_text_field($_POST['sts_name']);
        //$phone_number = esc_url_raw($_POST['sts_phone_number']);
        $phone_number = isset($_POST['sts_phone_number']) ? sanitize_text_field($_POST['sts_phone_number']) : '';
        $subject = sanitize_text_field($_POST['sts_subject']);
        $order_id = sanitize_text_field($_POST['sts_order_id']);  // Use sanitize_text_field for text input
        $submitted_at = current_time('mysql');
        // Create a DateTime object and add 6 hours
        $datetime = new DateTime($submitted_at);
        $datetime->modify('-6 hours');
        $submitted_at_adjusted = $datetime->format('Y-m-d H:i:s');
        $message = sanitize_textarea_field($_POST['sts_message']);
        $user_id = get_current_user_id();
        $user_info = get_userdata($user_id);
        $user_email = $user_info->user_email;
        // Handle file upload
        $image_url = null;
        if (!empty($_FILES['reply_image']['name'])) {
            require_once(ABSPATH . 'wp-admin/includes/file.php');
            $uploadedfile = $_FILES['reply_image'];

            // Allow only certain file types
            $allowed_file_types = array('jpg', 'jpeg', 'png');
            $file_extension = strtolower(pathinfo($uploadedfile['name'], PATHINFO_EXTENSION));
            // Check file size (5 MB limit)
            $max_file_size = 5 * 1024 * 1024; // 5 MB in bytes
            if ($uploadedfile['size'] > $max_file_size) {
                wp_send_json_error(array('message' => esc_html__('File size exceeds the 5 MB limit.', 'support-ticket-system')));
                return;
            }

            if (in_array($file_extension, $allowed_file_types)) {
                $upload_overrides = array('test_form' => false);
                $movefile = wp_handle_upload($uploadedfile, $upload_overrides);

                if ($movefile && !isset($movefile['error'])) {
                    $image_url = $movefile['url'];
                } else {
                    wp_send_json_error(array('message' => esc_html__('Error uploading file.', 'support-ticket-system')));
                    return;
                }
            } else {
                wp_send_json_error(array('message' => esc_html__('Invalid file type. Only jpg, jpeg, and png are allowed.', 'support-ticket-system')));
                return;
            }
        }
 

        $table_name = $wpdb->prefix . 'support_tickets';

        $wpdb->insert($table_name, [
            'user_id' => $user_id,
            'name' => $name,
            'phone_number' => $phone_number,
            'subject' => $subject,
            'order_id' => $order_id,
            'message' => $message,
            'image_url' => $image_url,  // Store image URL if uploaded
            'status' =>1,
            'submitted_at' => $submitted_at_adjusted,
        ]);

        // Email details
        // $from_email = 'prokash@technobd.com';
        $from_email = get_option('sts_from_email', 'prokash@technobd.com');

        $headers = array('Content-Type: text/plain; charset=UTF-8', 'From: ' . $from_email);

        // Send email to the user
        $user_subject = 'Support Ticket Submitted';
        $user_message = 'Hello ' . $name . ",\n\nYour support ticket has been submitted successfully. We will get back to you soon.\n\nTicket Details:\nSubject: " . $subject . "\nMessage: " . $message . "\n\nThank you!";

      // wp_mail($user_email, $user_subject, $user_message, $headers);

        // Send email to the admin
        $admin_email = get_option('admin_email');
        $admin_subject = 'New Support Ticket Submitted';
        $admin_message = 'A new support ticket has been submitted by ' . $name . ".\n\nTicket Details:\nName: " . $name . "\nDomain URL: " . $phone_number . "\nSubject: " . $subject . "\nOrder ID: " . $order_id . "\nMessage: " . $message . "\n\nPlease check the admin panel for more details.";
      // wp_mail($admin_email, $admin_subject, $admin_message, $headers);

        // Redirect to user message list page
        $redirect_url = wc_get_account_endpoint_url('support-ticket-system');
        wp_redirect(add_query_arg('ticket_submitted', 'true', $redirect_url));
        exit;
    }
}
add_action('init', 'sts_handle_ticket_submission');



// Handle user ticket reply
function sts_handle_ticket_reply()
{

    if (isset($_POST['sts_reply_message'])) {  // Changed from 'reply_message' to 'sts_reply_message'
        global $wpdb;

        $ticket_id = intval($_POST['ticket_id']);
        $reply_message = sanitize_textarea_field($_POST['sts_reply_message']);  // Changed from 'reply_message' to 'sts_reply_message'
        $user_id = get_current_user_id();

        $table_name = $wpdb->prefix . 'support_ticket_replies';
        $replied_at = current_time('mysql');
        // Create a DateTime object and add 6 hours
        $datetime = new DateTime($replied_at);
        $datetime->modify('+6 hours');
        $replied_at_adjusted = $datetime->format('Y-m-d H:i:s');
        // Handle file upload
        $image_url = null;
        if (!empty($_FILES['reply_image']['name'])) {
            require_once(ABSPATH . 'wp-admin/includes/file.php');
            $uploadedfile = $_FILES['reply_image'];

            // Allow only certain file types
            $allowed_file_types = array('jpg', 'jpeg', 'png');
            $file_extension = strtolower(pathinfo($uploadedfile['name'], PATHINFO_EXTENSION));
            // Check file size (5 MB limit)
            $max_file_size = 5 * 1024 * 1024; // 5 MB in bytes
            if ($uploadedfile['size'] > $max_file_size) {
                wp_send_json_error(array('message' => esc_html__('File size exceeds the 5 MB limit.', 'support-ticket-system')));
                return;
            }

            if (in_array($file_extension, $allowed_file_types)) {
                $upload_overrides = array('test_form' => false);
                $movefile = wp_handle_upload($uploadedfile, $upload_overrides);

                if ($movefile && !isset($movefile['error'])) {
                    $image_url = $movefile['url'];
                } else {
                    wp_send_json_error(array('message' => esc_html__('Error uploading file.', 'support-ticket-system')));
                    return;
                }
            } else {
                wp_send_json_error(array('message' => esc_html__('Invalid file type. Only jpg, jpeg, and png are allowed.', 'support-ticket-system')));
                return;
            }
        }
 
        $inserted = $wpdb->insert($table_name, [
            'ticket_id' => $ticket_id,
            'user_id' => $user_id,
            'message' => $reply_message,
            'replied_at' => $replied_at_adjusted,
            'image_url' => $image_url,  // Store image URL if uploaded
        ]);

        if ($inserted) {
            // Fetch ticket details
            $ticket_table = $wpdb->prefix . 'support_tickets';
            $ticket = $wpdb->get_row($wpdb->prepare("SELECT * FROM $ticket_table WHERE id = %d", $ticket_id));
            $user_info = get_userdata($ticket->user_id);
            $user_email = $user_info->user_email;

            // Email details
            // $from_email = 'prokash@technobd.com';
            $from_email = get_option('sts_from_email', 'prokash@technobd.com');

            $headers = array('Content-Type: text/plain; charset=UTF-8', 'From: ' . $from_email);

            // Send email to the user
            $user_subject = 'Support Ticket Reply for User';
            $user_message = 'Hello ' . $user_info->display_name . ",\n\nThere is a new reply to your support ticket.\n\nReply Message: " . $reply_message . "\n\nThank you!";
          // wp_mail($user_email, $user_subject, $user_message, $headers);

            // Send email to the admin
            $admin_email = get_option('admin_email');
            $admin_subject = 'New Reply to Support Ticket From User';
            $admin_message = 'A new reply has been submitted to a support ticket.\n\nTicket Details:\nName: ' . $ticket->name . "\nDomain URL: " . $ticket->domain_url . "\nSubject: " . $ticket->subject . "\nOrder ID: " . $ticket->order_id . "\nReply Message: " . $reply_message . "\n\nPlease check the admin panel for more details.";
          //  wp_mail($admin_email, $admin_subject, $admin_message, $headers);

            $replies_table = $wpdb->prefix . 'support_ticket_replies';
            $replies = $wpdb->get_results($wpdb->prepare("SELECT * FROM $replies_table WHERE ticket_id = %d ORDER BY replied_at ASC", $ticket_id));

            $formatted_replies = array();
            foreach ($replies as $reply) {


                $formatted_replies[] = array(
                    'message' => esc_html($reply->message),
                    'replied_at' => date('j/n/Y g:i A', strtotime($reply->replied_at)),
                    'image_url' => esc_url($reply->image_url),
                    'user_role' => get_userdata($reply->user_id)->roles,
                );
            }

            wp_send_json_success($formatted_replies);
            // Redirect to the same page to avoid form resubmission
            // wp_redirect(add_query_arg('ticket_id', $ticket_id, wp_get_referer()));
            //exit;
            // $html = sts_display_user_replies($ticket_id);

        } else {
            echo '<p>' . esc_html__('Failed to add reply.', 'support-ticket-system') . '</p>';
        }
    }
}
add_action('template_redirect', 'sts_handle_ticket_reply');
add_action('wp_ajax_sts_handle_ticket_reply', 'sts_handle_ticket_reply');
add_action('wp_ajax_nopriv_sts_handle_ticket_reply', 'sts_handle_ticket_reply');

function add_support_ticket_button_below_billing_address($order) {
    $order_id = $order->get_id();
    $order_number = $order->get_order_number();
    $home_url = home_url('/my-account/support-ticket-system/?order_id=' . $order_id);
    echo '<div class="support-btn"><a href="' . esc_url($home_url) . '" class="button support-ticket-button">Support Ticket System</a></div>';
}
add_action('woocommerce_order_details_after_customer_details', 'add_support_ticket_button_below_billing_address');

