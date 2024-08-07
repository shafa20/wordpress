<?php
/*
Plugin Name: Support Ticket System
Description: Plugin for handling support tickets in WooCommerce My Account.
Version: 1.0
Author: Your Name
*/

// Check if the constant is already defined
if (!defined('SUPPORT_TICKET_PLUGIN_URL')) {
    define('SUPPORT_TICKET_PLUGIN_URL', plugin_dir_url(__FILE__));
}

// Enqueue the plugin's custom CSS and JS for admin pages.
function support_ticket_enqueue_admin_assets($hook_suffix)
{
    if (strpos($hook_suffix, 'support-ticket-system') !== false) {
        wp_enqueue_style(
            'support-ticket-admin-style',
            SUPPORT_TICKET_PLUGIN_URL . 'css/admin.css',
            array(),
            '1.0.0'
        );
        wp_enqueue_style(
            'lightbox-style',
            SUPPORT_TICKET_PLUGIN_URL . 'css/lightbox.min.css',
            array(),
            '1.0.0'
        );
        wp_enqueue_script(
            'lightbox-script',
            SUPPORT_TICKET_PLUGIN_URL . 'js/lightbox.min.js',
            array('jquery'),
            '1.0.0',
            true
        );
        wp_enqueue_script(
            'lightbox-custom-script',
            SUPPORT_TICKET_PLUGIN_URL . 'js/lightbox-custom.js',
            array('jquery', 'lightbox-script'),
            '1.0.0',
            true
        );
    }
}
add_action('admin_enqueue_scripts', 'support_ticket_enqueue_admin_assets');

// Add custom CSS to hide the sub-menu
function support_ticket_hide_submenu_items()
{
    ?>
    <style>
        /* Hide the first and third submenu items, keeping the second item visible */
        .toplevel_page_support-ticket-system .wp-submenu-wrap li:not(:nth-child(3)) {
            display: none !important;
        }
    </style>
    <?php
}
add_action('admin_head', 'support_ticket_hide_submenu_items');

// Add admin menu
function sts_add_admin_menu()
{
    add_menu_page(
        __('Support Ticket System', 'support-ticket-system'),
        __('Support Ticket System', 'support-ticket-system'),
        'manage_options',
        'support-ticket-system',
        'sts_display_admin_tickets_page',
        'dashicons-tickets',
        6
    );

    add_submenu_page(
        'support-ticket-system',
        __('Settings', 'support-ticket-system'),
        __('Settings', 'support-ticket-system'),
        'manage_options',
        'support-ticket-system-settings',
        'sts_display_settings_page'
    );
}
add_action('admin_menu', 'sts_add_admin_menu');

// Display the settings page
function sts_display_settings_page()
{
    // Handle form submission
    if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['sts_settings_nonce']) && wp_verify_nonce($_POST['sts_settings_nonce'], 'sts_save_settings')) {
        update_option('sts_from_email', sanitize_email($_POST['sts_from_email']));

        // Save field labels, placeholders, and required status
        update_option('sts_name_label', sanitize_text_field($_POST['sts_name_label']));
        update_option('sts_name_placeholder', sanitize_text_field($_POST['sts_name_placeholder']));
        update_option('sts_name_required', isset($_POST['sts_name_required']) ? 'yes' : 'no');

        update_option('sts_phone_number_label', sanitize_text_field($_POST['sts_phone_number_label']));
        update_option('sts_phone_number_placeholder', sanitize_text_field($_POST['sts_phone_number_placeholder']));
        update_option('sts_phone_number_required', isset($_POST['sts_phone_number_required']) ? 'yes' : 'no');

        update_option('sts_subject_label', sanitize_text_field($_POST['sts_subject_label']));
        update_option('sts_subject_placeholder', sanitize_text_field($_POST['sts_subject_placeholder']));
        update_option('sts_subject_required', isset($_POST['sts_subject_required']) ? 'yes' : 'no');

        update_option('sts_order_id_label', sanitize_text_field($_POST['sts_order_id_label']));
        update_option('sts_order_id_placeholder', sanitize_text_field($_POST['sts_order_id_placeholder']));
        update_option('sts_order_id_required', isset($_POST['sts_order_id_required']) ? 'yes' : 'no');

        update_option('sts_message_label', sanitize_text_field($_POST['sts_message_label']));
        update_option('sts_message_placeholder', sanitize_text_field($_POST['sts_message_placeholder']));
        update_option('sts_message_required', isset($_POST['sts_message_required']) ? 'yes' : 'no');

        echo '<div class="notice notice-success is-dismissible"><p>Settings saved.</p></div>';
    }

    // Get the current settings
    $from_email = get_option('sts_from_email', '');

    // Get labels, placeholders, and required status
    $name_label = get_option('sts_name_label', 'Your Full Name');
    $name_placeholder = get_option('sts_name_placeholder', 'Enter Your Full Name');
    $name_required = get_option('sts_name_required', 'no');

    $phone_number_label = get_option('sts_phone_number_label', 'Phone Number');
    $phone_number_placeholder = get_option('sts_phone_number_placeholder', 'Enter Your Phone Number');
    $phone_number_required = get_option('sts_phone_number_required', 'no');


    $subject_label = get_option('sts_subject_label', 'Subject');
    $subject_placeholder = get_option('sts_subject_placeholder', 'Enter Your Subject');
    $subject_required = get_option('sts_subject_required', 'no');

    $order_id_label = get_option('sts_order_id_label', 'Order ID');
    $order_id_placeholder = get_option('sts_order_id_placeholder', 'Enter Order Id Please');
    $order_id_required = get_option('sts_order_id_required', 'no');

    $message_label = get_option('sts_message_label', 'Message');
    $message_placeholder = get_option('sts_message_placeholder', 'Enter your message here');
    $message_required = get_option('sts_message_required', 'no');
    ?>
    <div class="wrap">
        <h1><?php esc_html_e('Support Ticket System Settings', 'support-ticket-system'); ?></h1>
        <form method="post" action="">
            <?php wp_nonce_field('sts_save_settings', 'sts_settings_nonce'); ?>
            <table class="form-table">
                <tr>
                    <th scope="row"><label
                            for="sts_from_email"><?php esc_html_e('From Email', 'support-ticket-system'); ?></label></th>
                    <td><input type="email" id="sts_from_email" name="sts_from_email"
                            value="<?php echo esc_attr($from_email); ?>" class="regular-text" /></td>
                </tr>
                <tr>
                    <th scope="row"><label
                            for="sts_name_label"><?php esc_html_e('Name Label', 'support-ticket-system'); ?></label></th>
                    <td>
                        <input type="text" id="sts_name_label" name="sts_name_label"
                            value="<?php echo esc_attr($name_label); ?>" class="regular-text" />
                    </td>
                </tr>
                <tr>
                    <th scope="row"><label
                            for="sts_name_placeholder"><?php esc_html_e('Name Placeholder', 'support-ticket-system'); ?></label>
                    </th>
                    <td>
                        <input type="text" id="sts_name_placeholder" name="sts_name_placeholder"
                            value="<?php echo esc_attr($name_placeholder); ?>" class="regular-text" />
                        <label for="sts_name_required">
                            <input type="checkbox" id="sts_name_required" name="sts_name_required" <?php checked($name_required, 'yes'); ?> />
                            <?php esc_html_e('Required', 'support-ticket-system'); ?>
                        </label>
                    </td>
                </tr>
                <tr>
                    <th scope="row"><label
                            for="sts_phone_number_placeholder"><?php esc_html_e('Phone Number Placeholder', 'support-ticket-system'); ?></label>
                    </th>
                    <td>
                        <input type="text" id="sts_phone_number_placeholder" name="sts_phone_number_placeholder"
                            value="<?php echo esc_attr($phone_number_placeholder); ?>" class="regular-text" />
                        <label for="sts_phone_number_required">
                            <input type="checkbox" id="sts_phone_number_required" name="sts_phone_number_required" <?php checked($phone_number_required, 'yes'); ?> />
                            <?php esc_html_e('Required', 'support-ticket-system'); ?>
                        </label>
                    </td>
                </tr>
                <tr>
                    <th scope="row"><label
                            for="sts_phone_number_placeholder"><?php esc_html_e('Phone Number Placeholder', 'support-ticket-system'); ?></label>
                    </th>
                    <td>
                        <input type="text" id="sts_phone_number_placeholder" name="sts_phone_number_placeholder"
                            value="<?php echo esc_attr($phone_number_placeholder); ?>" class="regular-text" />
                        <label for="sts_phone_number_required">
                            <input type="checkbox" id="sts_phone_number_required" name="sts_phone_number_required" <?php checked($phone_number_required, 'yes'); ?> />
                            <?php esc_html_e('Required', 'support-ticket-system'); ?>
                        </label>
                    </td>
                </tr>
                <tr>
                    <th scope="row"><label
                            for="sts_subject_label"><?php esc_html_e('Subject Label', 'support-ticket-system'); ?></label>
                    </th>
                    <td>
                        <input type="text" id="sts_subject_label" name="sts_subject_label"
                            value="<?php echo esc_attr($subject_label); ?>" class="regular-text" />
                    </td>
                </tr>
                <tr>
                    <th scope="row"><label
                            for="sts_subject_placeholder"><?php esc_html_e('Subject Placeholder', 'support-ticket-system'); ?></label>
                    </th>
                    <td>
                        <input type="text" id="sts_subject_placeholder" name="sts_subject_placeholder"
                            value="<?php echo esc_attr($subject_placeholder); ?>" class="regular-text" />
                        <label for="sts_subject_required">
                            <input type="checkbox" id="sts_subject_required" name="sts_subject_required" <?php checked($subject_required, 'yes'); ?> />
                            <?php esc_html_e('Required', 'support-ticket-system'); ?>
                        </label>
                    </td>
                </tr>
                <tr>
                    <th scope="row"><label
                            for="sts_order_id_label"><?php esc_html_e('Order ID Label', 'support-ticket-system'); ?></label>
                    </th>
                    <td>
                        <input type="text" id="sts_order_id_label" name="sts_order_id_label"
                            value="<?php echo esc_attr($order_id_label); ?>" class="regular-text" />
                    </td>
                </tr>
                <tr>
                    <th scope="row"><label
                            for="sts_order_id_placeholder"><?php esc_html_e('Order ID Placeholder', 'support-ticket-system'); ?></label>
                    </th>
                    <td>
                        <input type="text" id="sts_order_id_placeholder" name="sts_order_id_placeholder"
                            value="<?php echo esc_attr($order_id_placeholder); ?>" class="regular-text" />
                        <label for="sts_order_id_required">
                            <input type="checkbox" id="sts_order_id_required" name="sts_order_id_required" <?php checked($order_id_required, 'yes'); ?> />
                            <?php esc_html_e('Required', 'support-ticket-system'); ?>
                        </label>
                    </td>
                </tr>
                <tr>
                    <th scope="row"><label
                            for="sts_message_label"><?php esc_html_e('Message Label', 'support-ticket-system'); ?></label>
                    </th>
                    <td>
                        <input type="text" id="sts_message_label" name="sts_message_label"
                            value="<?php echo esc_attr($message_label); ?>" class="regular-text" />
                    </td>
                </tr>
                <tr>
                    <th scope="row"><label
                            for="sts_message_placeholder"><?php esc_html_e('Message Placeholder', 'support-ticket-system'); ?></label>
                    </th>
                    <td>
                        <input type="text" id="sts_message_placeholder" name="sts_message_placeholder"
                            value="<?php echo esc_attr($message_placeholder); ?>" class="regular-text" />
                        <label for="sts_message_required">
                            <input type="checkbox" id="sts_message_required" name="sts_message_required" <?php checked($message_required, 'yes'); ?> />
                            <?php esc_html_e('Required', 'support-ticket-system'); ?>
                        </label>
                    </td>
                </tr>
            </table>
            <p><input type="submit" class="button-primary"
                    value="<?php esc_attr_e('Save Settings', 'support-ticket-system'); ?>" /></p>
        </form>
    </div>
    <?php
}



// display admin ticket replay list
function sts_display_admin_tickets_page()
{
    global $wpdb;
    $table_name = $wpdb->prefix . 'support_tickets';
    $message_table = $wpdb->prefix . 'support_ticket_replies';

    // Handle pagination
    $items_per_page = 10; // Set the number of items per page
    $current_page = isset($_GET['paged']) ? max(1, intval($_GET['paged'])) : 1;
    $offset = ($current_page - 1) * $items_per_page;

    // Handle search
    $search = isset($_GET['s']) ? sanitize_text_field($_GET['s']) : '';

    // Query for tickets with search and pagination
    $search_query = $search ? $wpdb->prepare("AND (name LIKE %s OR name LIKE %s OR order_id LIKE %s)", '%' . $wpdb->esc_like($search) . '%', '%' . $wpdb->esc_like($search) . '%', '%' . $wpdb->esc_like($search) . '%') : '';

    $tickets = $wpdb->get_results($wpdb->prepare("SELECT * FROM $table_name WHERE 1=1 $search_query ORDER BY submitted_at DESC LIMIT %d OFFSET %d", $items_per_page, $offset));

    $total_tickets = $wpdb->get_var($wpdb->prepare("SELECT COUNT(*) FROM $table_name WHERE 1=1 $search_query"));

    echo '<div class="wrap">';
    echo '<h1>' . esc_html__('Support Ticket System', 'support-ticket-system') . '</h1>';

    // Search form
    echo '<form method="get" action="">';
    echo '<input type="hidden" name="page" value="support-ticket-system" />';
    echo '<p class="search-box">';
    echo '<label class="screen-reader-text" for="ticket-search-input">' . esc_html__('Search Tickets', 'support-ticket-system') . '</label>';
    echo '<input type="search" id="ticket-search-input" name="s" value="' . esc_attr($search) . '" />';
    echo '<input type="submit" id="search-submit" class="button" value="' . esc_attr__('Search Tickets', 'support-ticket-system') . '" />';
    echo '</p>';
    echo '</form>';

    // Table
    echo '<table id="tickets-table" class="widefat fixed" cellspacing="0">';
    echo '<colgroup>
         <col width="10%"/>
         <col width="15%" />
         <col width="15%" />
         <col width="10%" />
         <col width="25%" />
         <col width="10%" />
         <col width="5%" />
         <col width="10%" />
       </colgroup>';
    echo '<thead><tr><th>' . esc_html__('Name', 'support-ticket-system') . '</th><th>' . esc_html__('Phone No', 'support-ticket-system') . '</th><th>' . esc_html__('Subject', 'support-ticket-system') . '</th><th style="text-align: center;">' . esc_html__('Order ID', 'support-ticket-system') . '</th><th>' . esc_html__('Message', 'support-ticket-system') . '</th><th>' . esc_html__('View Reply', 'support-ticket-system') . '</th><th style="text-align: center;">' . esc_html__('Unseen', 'support-ticket-system') . '</th><th style="text-align: right;">' . esc_html__('Action', 'support-ticket-system') . '</th></tr></thead>';
    echo '<tbody>';

    if ($tickets) {
        foreach ($tickets as $ticket) {
            $unseen_count = $wpdb->get_var($wpdb->prepare("
                 SELECT COUNT(*)
                 FROM $message_table
                 WHERE ticket_id = %d
                 AND user_id != 1
                 AND replied_at > IFNULL((
                     SELECT MAX(replied_at)
                     FROM $message_table
                     WHERE ticket_id = %d
                     AND user_id = 1
                 ), '0000-00-00 00:00:00')
             ", $ticket->id, $ticket->id));
            echo '<tr>';
            echo '<td>' . esc_html($ticket->name) . '</td>';
            echo '<td>' . esc_html($ticket->phone_number) . '</td>';
            echo '<td>' . esc_html($ticket->subject) . '</td>';
            echo '<td style="text-align: center;">' . esc_html($ticket->order_id) . '</td>';
            echo '<td>' . esc_html($ticket->message) . '</td>';
            echo '<td><a href="' . esc_url(admin_url('admin.php?page=support-ticket-replies&ticket_id=' . $ticket->id)) . '">' . esc_html__('View Replies', 'support-ticket-system') . '</a></td>';
            echo '<td style="text-align: center;"><span class="unseen-message">' . esc_html($unseen_count) . '</span></td>';
            echo '<td style="text-align: right;">';
            echo '<select class="ticket-status" data-ticket-id="' . esc_attr($ticket->id) . '">';
            // echo '<option value="0"' . selected($ticket->status, 0, false) . '>' . esc_html__('Pending', 'support-ticket-system') . '</option>';
            echo '<option value="1"' . selected($ticket->status, 1, false) . '>' . esc_html__('Open', 'support-ticket-system') . '</option>';
            echo '<option value="2"' . selected($ticket->status, 2, false) . '>' . esc_html__('Close', 'support-ticket-system') . '</option>';
            // echo '<option value="3"' . selected($ticket->status, 3, false) . '>' . esc_html__('Solved', 'support-ticket-system') . '</option>';
            echo '</select>';
            echo '</td>';
            echo '</tr>';
        }
    } else {
        echo '<tr><td colspan="8">' . esc_html__('No tickets found.', 'support-ticket-system') . '</td></tr>';
    }

    echo '</tbody>';
    echo '</table>';

    // Pagination
    $total_pages = ceil($total_tickets / $items_per_page);
    if ($total_pages > 1) {
        $pagination = paginate_links(
            array(
                'base' => add_query_arg('paged', '%#%'),
                'format' => '',
                'prev_text' => $current_page > 1 ? __('&laquo;', 'support-ticket-system') : '<span class="tablenav-pages-navspan button disabled" aria-hidden="true">«</span>',
                'next_text' => $current_page < $total_pages ? __('&raquo;', 'support-ticket-system') : '<span class="tablenav-pages-navspan button disabled" aria-hidden="true">»</span>',
                'total' => $total_pages,
                'current' => $current_page,
                'type' => 'list',
                'before_page_number' => '<span class="tablenav-pages-navspan button">',
                'after_page_number' => '</span>',
            )
        );

        // Adjust pagination HTML to match WordPress admin style
        $pagination = str_replace(
            array('<a ', '</a>', '<span class="page-num">', '</span>'),
            array('<a class="button" ', '</a>', '<span class="tablenav-paging-text">', '</span>'),
            $pagination
        );

        echo '<div class="tablenav bottom">';
        echo '<div class="alignleft actions bulkactions">';
        echo '<label for="bulk-action-selector-bottom" class="screen-reader-text">' . esc_html__('Select bulk action', 'support-ticket-system') . '</label>';

        echo '</div>';
        echo '<div class="alignleft actions"></div>';

        echo '<div class="tablenav-pages">';
        echo '<span class="displaying-num">' . sprintf(esc_html__('Total %s items', 'support-ticket-system'), number_format_i18n($total_tickets)) . '</span>';

        echo '<span class="pagination-links">';

        // Previous page link
        if ($current_page > 1) {
            echo '<a class="first-page button" href="' . esc_url(add_query_arg('paged', 1)) . '"><span class="screen-reader-text">First page</span><span aria-hidden="true">«</span></a>';
            echo '<a class="prev-page button" href="' . esc_url(add_query_arg('paged', $current_page - 1)) . '"><span class="screen-reader-text">Previous page</span><span aria-hidden="true">‹</span></a>';
        } else {
            echo '<span class="tablenav-pages-navspan button disabled" aria-hidden="true">«</span>';
            echo '<span class="tablenav-pages-navspan button disabled" aria-hidden="true">‹</span>';
        }

        // Current page indicator
        echo '<span class="screen-reader-text">Current Page</span>';
        echo '<span id="table-paging" class="paging-input"><span class="tablenav-paging-text">' . sprintf(esc_html__('%s of %s', 'support-ticket-system'), number_format_i18n($current_page), number_format_i18n($total_pages)) . '</span></span>';

        // Next page link
        if ($current_page < $total_pages) {
            echo '<a class="next-page button" href="' . esc_url(add_query_arg('paged', $current_page + 1)) . '"><span class="screen-reader-text">Next page</span><span aria-hidden="true">›</span></a>';
            echo '<a class="last-page button" href="' . esc_url(add_query_arg('paged', $total_pages)) . '"><span class="screen-reader-text">Last page</span><span aria-hidden="true">»</span></a>';
        } else {
            echo '<span class="tablenav-pages-navspan button disabled" aria-hidden="true">›</span>';
            echo '<span class="tablenav-pages-navspan button disabled" aria-hidden="true">»</span>';
        }

        echo '</span>'; // .pagination-links
        echo '</div>'; // .tablenav-pages

        echo '<br class="clear">';
        echo '</div>';
    }

    echo '</div>';

    // Include the JavaScript for handling status change
    ?>
    <script type="text/javascript">
        jQuery(document).ready(function ($) {
            $('.ticket-status').change(function () {
                var ticketId = $(this).data('ticket-id');
                var status = $(this).val();

                $.ajax({
                    url: ajaxurl,
                    type: 'POST',
                    data: {
                        action: 'update_ticket_status',
                        ticket_id: ticketId,
                        status: status,
                        _ajax_nonce: '<?php echo wp_create_nonce('update_ticket_status_nonce'); ?>'
                    },
                    success: function (response) {
                        if (response.success) {
                            alert('Ticket status updated successfully.');
                        } else {
                            alert('Failed to update ticket status.');
                        }
                    }
                });
            });
        });
    </script>
    <?php
}


// Add the AJAX handler for updating ticket status
function sts_update_ticket_status()
{
    check_ajax_referer('update_ticket_status_nonce', '_ajax_nonce');

    if (isset($_POST['ticket_id']) && isset($_POST['status'])) {
        global $wpdb;
        $ticket_id = intval($_POST['ticket_id']);
        $status = intval($_POST['status']);

        $table_name = $wpdb->prefix . 'support_tickets';
        $updated = $wpdb->update(
            $table_name,
            array('status' => $status),
            array('id' => $ticket_id),
            array('%d'),
            array('%d')
        );

        if ($updated !== false) {
            wp_send_json_success();
        } else {
            wp_send_json_error();
        }
    } else {
        wp_send_json_error();
    }
}
add_action('wp_ajax_update_ticket_status', 'sts_update_ticket_status');


// Display replies for a ticket in the admin panel
function sts_display_ticket_replies($initial_ticket_id = null)
{
    //before sorted by latest message
    // global $wpdb;
    // $ticket_table = $wpdb->prefix . 'support_tickets';
    // $tickets = $wpdb->get_results("SELECT * FROM $ticket_table");

    global $wpdb;
    $table_name_tickets = $wpdb->prefix . 'support_tickets';
    $table_name_replies = $wpdb->prefix . 'support_ticket_replies';

    // Update the query to fetch tickets and order by the latest reply date or creation date
    $query = "
    SELECT t.*, 
           u.display_name, 
           IFNULL(MAX(r.replied_at), t.submitted_at) AS latest_activity
    FROM $table_name_tickets AS t
    LEFT JOIN $wpdb->users AS u ON t.user_id = u.ID
    LEFT JOIN $table_name_replies AS r ON t.id = r.ticket_id
    GROUP BY t.id
    ORDER BY latest_activity DESC
";

    $tickets = $wpdb->get_results($query);
    // print_r($tickets);
    // die;
    ?>
    <div class="wrap support-ticket-wrap">
        <h1><?php esc_html_e('Support Tickets', 'support-ticket-system'); ?></h1>
        <div class="ticket-list-container">
            <div class="ticket-list">
                <ul>
                    <?php foreach ($tickets as $ticket): ?>
                        <?php
                        $message_table = $wpdb->prefix . 'support_ticket_replies';
                        $unseen_count = $wpdb->get_var($wpdb->prepare("
                         SELECT COUNT(*)
                         FROM $message_table
                         WHERE ticket_id = %d
                         AND user_id != 1
                         AND replied_at > IFNULL((
                             SELECT MAX(replied_at)
                             FROM $message_table
                             WHERE ticket_id = %d
                             AND user_id = 1
                         ), '0000-00-00 00:00:00')
                     ", $ticket->id, $ticket->id));

                        ?>
                        <li data-ticket-id="<?php echo esc_attr($ticket->id); ?>" <?php echo ($ticket->id == $initial_ticket_id) ? 'class="active"' : ''; ?>>
                            <div class="support-content">
                                <?php $user = get_user_by('ID', $ticket->user_id);
                                $display_name = $user->display_name;
                                ?>
                                <span class="user-name"><?php echo esc_html($display_name); ?>
                                    <span class="total-message"><?php echo esc_html($unseen_count); ?></span>
                                </span>

                                <span class="subject"><?php echo esc_html($ticket->subject); ?></span>
                            </div>
                            <div class="support-footer">
                                <div class="support-footer-left">
                                    <?php
                                    // Display the correct status based on the ticket's status value
                                    switch ($ticket->status) {
                                        case 1:
                                            echo '<span class="open">Open</span>';
                                            break;
                                        case 2:
                                            echo '<span class="closed">Close</span>';
                                            break;
                                        case 3:
                                            echo '<span class="solved">Solved</span>';
                                            break;
                                        default:
                                            echo '<span class="pending">Pending</span>';
                                            break;
                                    }
                                    ?>
                                </div>
                                <div class="support-footer-right">
                                    <span class="support-id">Order ID:<?php echo esc_html($ticket->order_id); ?></span>
                                </div>
                            </div>

                        </li>
                    <?php endforeach; ?>
                </ul>
            </div>
            <div class="ticket-details-container">
                <div class="chat-open">
                    <div id="ticket-details">
                        <!-- Ticket details and replies will be loaded here via AJAX -->
                        <?php if ($initial_ticket_id): ?>
                            <script>
                                jQuery(document).ready(function ($) {
                                    // Load the initial ticket details via AJAX
                                    $.ajax({
                                        url: ajaxurl,
                                        type: 'POST',
                                        data: {
                                            action: 'load_ticket_details',
                                            ticket_id: <?php echo intval($initial_ticket_id); ?>
                                        },
                                        success: function (response) {
                                            if (response.success) {
                                                $('#ticket-details').html(response.data);
                                            } else {
                                                console.error('Failed to load ticket details:', response.data);
                                            }
                                        },
                                        error: function (error) {
                                            console.error('Error loading ticket details:', error);
                                        }
                                    });
                                });
                            </script>
                        <?php endif; ?>

                    </div>

                </div>
            </div>
        </div>
    </div>
    <script>
        jQuery(document).ready(function ($) {

            $('.ticket-list li').on('click', function () {
                var ticketId = $(this).data('ticket-id');

                // Remove 'active' class from all list items
                $('.ticket-list li').removeClass('active');

                // Add 'active' class to the clicked item
                $(this).addClass('active');

                // Load ticket details and replies via AJAX
                $.ajax({
                    url: ajaxurl,
                    type: 'POST',
                    data: {
                        action: 'load_ticket_details',
                        ticket_id: ticketId
                    },
                    success: function (response) {
                        if (response.success) {
                            $('#ticket-details').html(response.data);
                        } else {
                            console.error('Failed to load ticket details:', response.data);
                        }
                    },
                    error: function (error) {
                        console.error('Error loading ticket details:', error);
                    }
                });
            });
        });
    </script>

    <?php
}

//admin chat page load
function sts_load_ticket_details($ticket_id)
{
    global $wpdb;
    $ticket_id = intval($_POST['ticket_id']);
    $ticket_table = $wpdb->prefix . 'support_tickets';
    $replies_table = $wpdb->prefix . 'support_ticket_replies';
    $ticket = $wpdb->get_row($wpdb->prepare("SELECT * FROM $ticket_table WHERE id = %d", $ticket_id));
    $replies = $wpdb->get_results($wpdb->prepare("SELECT * FROM $replies_table WHERE ticket_id = %d", $ticket_id));
    // Fetch last reply date
    $last_reply = $wpdb->get_row($wpdb->prepare("SELECT replied_at FROM $replies_table WHERE ticket_id = %d ORDER BY replied_at DESC LIMIT 1", $ticket_id));
    if ($last_reply) {
        $last_reply_date = date('j F, Y, g:i A', strtotime($last_reply->replied_at));
    }
    // Fetch ticket details

    ob_start();
    ?>
    <div class="chat">
        <div class="wrap support-ticket-wrap d-none">
            <h1><?php esc_html_e('Ticket Replies', 'support-ticket-system'); ?></h1>
            <div class="ticket-details">
                <h2><?php esc_html_e('Ticket Details', 'support-ticket-system'); ?></h2>
                <p><strong><?php esc_html_e('Name:', 'support-ticket-system'); ?></strong>
                    <?php echo esc_html($ticket->name); ?></p>
                <p><strong><?php esc_html_e('Domain URL:', 'support-ticket-system'); ?></strong>
                    <?php echo esc_html($ticket->domain_url); ?></p>
                <p><strong><?php esc_html_e('Subject:', 'support-ticket-system'); ?></strong>
                    <?php echo esc_html($ticket->subject); ?></p>
                <p><strong><?php esc_html_e('Order ID:', 'support-ticket-system'); ?></strong>
                    <?php echo esc_html($ticket->order_id); ?></p>
                <p><strong><?php esc_html_e('Message:', 'support-ticket-system'); ?></strong>
                    <?php echo esc_html($ticket->message); ?></p>
            </div>
        </div>
        <div class="support-ticket-container">
            <div class="support-message">
                <div class="message-header">
                    <a href="<?php echo esc_url(home_url('/wp-admin/admin.php?page=support-ticket-system/')); ?>">
                        <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none">
                            <rect width="24" height="24" rx="5" fill="#0FAFE9" />
                            <path d="M19 12H5" stroke="white" stroke-width="2" stroke-linecap="round"
                                stroke-linejoin="round" />
                            <path d="M12 19L5 12L12 5" stroke="white" stroke-width="2" stroke-linecap="round"
                                stroke-linejoin="round" />
                        </svg>
                    </a>
                    <div class="message-header-content">
                        <h3><?php echo esc_html($ticket->subject); ?></h3>
                        <div class="smoll-content">
                        <div class="content-left">
                            <?php if (!empty($last_reply_date)) : ?>
                                <span><?php echo esc_html__('Last message', 'your-text-domain'); ?> <?php echo esc_html($last_reply_date); ?></span>
                            <?php else: ?>
                                <?php $last_reply_date = date('j F, Y, g:i A', strtotime($ticket->submitted_at)); ?>
                                <span><?php echo esc_html__('Last message', 'your-text-domain'); ?> <?php echo esc_html($last_reply_date); ?></span>
                            <?php endif; ?>
                        </div>
                            <div class="content-right">

                                <span class="support-id"><?php echo esc_html__('Support ID:', 'your-text-domain'); ?>
                                    #<?php echo esc_html($ticket->id); ?></span>
                                <select class="ticket-status" data-ticket-id="<?php echo esc_attr($ticket->id); ?>">
                                    <option value="0" <?php selected($ticket->status, 0); ?>>
                                        <?php echo esc_html__('Pending', 'support-ticket-system'); ?></option>
                                    <option value="1" <?php selected($ticket->status, 1); ?>>
                                        <?php echo esc_html__('Open', 'support-ticket-system'); ?></option>
                                    <option value="2" <?php selected($ticket->status, 2); ?>>
                                        <?php echo esc_html__('Close', 'support-ticket-system'); ?></option>
                                    <option value="3" <?php selected($ticket->status, 3); ?>>
                                        <?php echo esc_html__('Solved', 'support-ticket-system'); ?></option>
                                </select>
                                <script>
                                    jQuery(document).ready(function ($) {
                                        $('.ticket-status').on('change', function () {
                                            var ticketId = $(this).data('ticket-id');
                                            var newStatus = $(this).val();

                                            // AJAX request to update ticket status
                                            $.ajax({
                                                url: ajaxurl,
                                                type: 'POST',
                                                data: {
                                                    action: 'update_ticket_status',
                                                    _ajax_nonce: '<?php echo wp_create_nonce('update_ticket_status_nonce'); ?>',
                                                    ticket_id: ticketId,
                                                    status: newStatus
                                                },
                                                success: function (response) {
                                                    // Handle success (if needed)
                                                    alert('Ticket status updated successfully.');
                                                },
                                                error: function (error) {
                                                    // Handle errors (if needed)
                                                    alert('Ticket status updated fail.');
                                                }
                                            });
                                        });
                                    });
                                </script>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="scrollbar-container ps ps--active-y">
                    <div class="chat-body">
                        <div class="messages">
                            <!-- Messages will be appended here -->
                            <?php
                            // Display ticket replies
                            global $wpdb;
                            $replies_table = $wpdb->prefix . 'support_ticket_replies';
                            $replies = $wpdb->get_results($wpdb->prepare("SELECT * FROM $replies_table WHERE ticket_id = %d ORDER BY replied_at ASC", $ticket_id));
                            $ticket = $wpdb->get_row($wpdb->prepare("SELECT * FROM wp_support_tickets WHERE id = %d", $ticket_id));
                            echo '<p>' . '</strong> ' . esc_html($ticket->message) . '</p>';
                            if ($replies) {
                                $previous_date = null;

                                foreach ($replies as $reply) {
                                    $user_info = get_userdata($reply->user_id);
                                    $message_class = in_array('administrator', $user_info->roles) ? 'outgoing-message' : 'in-coming';
                                    $message_date = date('j/n/Y', strtotime($reply->replied_at));
                                    $message_time = date_i18n('g:i A', strtotime($reply->replied_at));
                                    $image_url = $reply->image_url;

                                    if ($previous_date !== $message_date) {
                                        echo '<div class="line-38"><div class="date">' . esc_html($message_date) . '</div></div>';
                                        $previous_date = $message_date;
                                    }


                                    echo '<div class="message-item ' . esc_attr($message_class) . '">';
                                    echo '<div class="message-content">';

                                    // Display image if available
                                    if (!empty($image_url)) {
                                        // echo '<div class="lightbox-trigger" data-image="' . esc_url($image_url) . '">';
                                        // echo '<img src="' . esc_url($image_url) . '" alt="Image" />';
                                        // echo '</div>';
                                        echo '<a href="' . esc_url($image_url) . '" data-lightbox="mygallery" class="message-image"><img src="' . esc_url($image_url) . '" alt="Image 1"></a>';
                                    }

                                    echo '<p>' . esc_html($reply->message) . '</p>';
                                    echo '<div class="message-item-footer">';
                                    echo '<span class="time"><svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 512 512"><!--!Font Awesome Free 6.6.0 by @fontawesome - https://fontawesome.com License - https://fontawesome.com/license/free Copyright 2024 Fonticons, Inc.-->
                                    <path d="M464 256A208 208 0 1 1 48 256a208 208 0 1 1 416 0zM0 256a256 256 0 1 0 512 0A256 256 0 1 0 0 256zM232 120l0 136c0 8 4 15.5 10.7 20l96 64c11 7.4 25.9 4.4 33.3-6.7s4.4-25.9-6.7-33.3L280 243.2 280 120c0-13.3-10.7-24-24-24s-24 10.7-24 24z"></path>
                                     </svg> ' . esc_html($message_time) . '</span>';
                                    echo '</div>';
                                    echo '</div>';
                                    echo '</div>';
                                }
                            } else {
                            }
                            ?>
                        </div>
                    </div>
                </div>

                <div class="chat-footer">
                    <form id="reply-form" method="post" enctype="multipart/form-data">
                        <textarea id="reply" name="reply_message"
                            placeholder="<?php echo esc_attr__('Type your message here...', 'support-ticket-system'); ?>"
                            required></textarea>
                        <input type="hidden" name="ticket_id" value="<?php echo esc_attr($ticket_id); ?>">
                        <img id="imagePreview" src="" alt="Image Preview" style="display:none; width: 50px;" />
                        <a class="uplode-file" type="button" href="javascript:void(0);"
                            onclick="document.getElementById('fileInput').click();">
                            <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none">
                                <path
                                    d="M21 15V19C21 19.5304 20.7893 20.0391 20.4142 20.4142C20.0391 20.7893 19.5304 21 19 21H5C4.46957 21 3.96086 20.7893 3.58579 20.4142C3.21071 20.0391 3 19.5304 3 19V15"
                                    stroke="#0FAFE9" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" />
                                <path d="M17 8L12 3L7 8" stroke="#0FAFE9" stroke-width="2" stroke-linecap="round"
                                    stroke-linejoin="round" />
                                <path d="M12 3V15" stroke="#0FAFE9" stroke-width="2" stroke-linecap="round"
                                    stroke-linejoin="round" />
                            </svg>
                        </a>
                        <input id="fileInput" class="upload-file d-none" type="file" name="reply_image" accept="image/*">
                        <button class="send_message" id="submit-button" name="sts_reply_message"
                            type="submit"><?php echo esc_html__('SEND', 'your-text-domain'); ?></button>
                        <script>
                            document.getElementById('fileInput').addEventListener('change', function (event) {
                                const file = event.target.files[0];
                                if (file) {
                                    const reader = new FileReader();
                                    reader.onload = function (e) {
                                        const imagePreview = document.getElementById('imagePreview');
                                        imagePreview.src = e.target.result;
                                        imagePreview.style.display = 'block';
                                    };
                                    reader.readAsDataURL(file);
                                }
                            });
                        </script>
                    </form>
                </div>
                <script>
                    // jQuery(document).ready(function($) {
                    //     $('#reply-form').on('submit', function(event) {
                    //         event.preventDefault();
                    //         var form = $(this);
                    //         var formData = form.serialize();

                    //         $.ajax({
                    //             type: 'POST',
                    //             url: ajaxurl,
                    //             data: formData + '&action=sts_handle_reply_submission&_ajax_nonce=<?php echo wp_create_nonce('sts_reply_nonce'); ?>',
                    //             dataType: 'json',
                    //             success: function(response) {
                    //                 if (response.success) {
                    //                     $('.chat').html(response.data); // Update the chat content
                    //                     $('#reply').val(''); // Clear the input field
                    //                 } else {
                    //                     alert('Error sending reply. Please try again.');
                    //                 }
                    //             },
                    //             error: function(error) {
                    //                 console.error('Error sending reply:', error);
                    //                 alert('Error sending reply. Please try again.');
                    //             }
                    //         });
                    //     });
                    // });

                    jQuery(document).ready(function ($) {
                        function scrollToBottom() {
                            var container = $('.scrollbar-container');
                            container.scrollTop(container[0].scrollHeight);
                        }

                        // Call scrollToBottom on page load
                        scrollToBottom();

                        // $('#reply').on('keydown', function (e) {
                        //     if (e.key === 'Enter' && !e.shiftKey) {
                        //         e.preventDefault(); // Prevent default form submission on Enter key press
                        //     }
                        // });
                        $('#reply').on('keydown', function (e) {
                            if (e.key === 'Enter' && !e.shiftKey) {
                                e.preventDefault(); // Prevent default form submission on Enter key press
                                const textarea = $(this);
                                const value = textarea.val();
                                textarea.val(value + '\n'); // Append newline character
                            }
                        });

                        $('#reply-form').on('submit', function (event) {
                            event.preventDefault();
                            var form = $(this)[0]; // Get the raw form element
                            var formData = new FormData(form);

                            var $submitButton = $('#submit-button');
                            $submitButton.prop('disabled', true); // Disable the submit button

                            // Add action and nonce for security
                            formData.append('action', 'sts_handle_reply_submission');
                            formData.append('_ajax_nonce', '<?php echo wp_create_nonce('sts_reply_nonce'); ?>');

                            $.ajax({
                                type: 'POST',
                                url: ajaxurl,
                                data: formData,
                                processData: false,
                                contentType: false,
                                dataType: 'json',
                                success: function (response) {
                                    if (response.success) {
                                        $('.chat').html(response.data); // Update the chat content
                                        $('#reply').val(''); // Clear the input field
                                        $('#fileInput').val(''); // Clear the file input field

                                        // Ensure all images are loaded before scrolling
                                        var images = $('.chat img');
                                        var imagesLoaded = 0;

                                        if (images.length > 0) {
                                            images.each(function () {
                                                if (this.complete) {
                                                    imagesLoaded++;
                                                    if (imagesLoaded === images.length) {
                                                        scrollToBottom();
                                                    }
                                                } else {
                                                    $(this).on('load', function () {
                                                        imagesLoaded++;
                                                        if (imagesLoaded === images.length) {
                                                            scrollToBottom();
                                                        }
                                                    });
                                                }
                                            });
                                        } else {
                                            scrollToBottom();
                                        }

                                    } else {
                                        alert('Error: ' + response.data.message);
                                    }
                                },
                                error: function (error) {
                                    alert('Error: ' + response.data.message);
                                },
                                complete: function () {
                                    $submitButton.prop('disabled', false); // Re-enable the submit button after the request completes
                                }
                            });
                        });
                    });
                </script>

            </div>
        </div>
    </div>

    <?php
    wp_send_json_success(ob_get_clean());
}
add_action('wp_ajax_load_ticket_details', 'sts_load_ticket_details');



// Handle displaying replies when admin clicks "View Replies"
function sts_admin_display_replies_page()
{
    if (isset($_GET['ticket_id'])) {
        sts_display_ticket_replies(intval($_GET['ticket_id']));
    } else {
        sts_display_admin_tickets_page();
    }
}
add_action('admin_menu', function () {
    add_submenu_page(
        'support-ticket-system',
        __('Ticket Replies', 'support-ticket-system'),
        __('Ticket Replies', 'support-ticket-system'),
        'manage_options',
        'support-ticket-replies',
        'sts_admin_display_replies_page'
    );
});


// Handle reply submission from admin
function sts_handle_reply_submission()
{
    check_ajax_referer('sts_reply_nonce', '_ajax_nonce');

    if (isset($_POST['ticket_id']) && isset($_POST['reply_message'])) {
        global $wpdb;
        $table_name = $wpdb->prefix . 'support_ticket_replies';
        $ticket_id = intval($_POST['ticket_id']);
        $message = sanitize_text_field($_POST['reply_message']);
        $user_id = get_current_user_id();

        $replied_at = current_time('mysql');

        // Create a DateTime object and add 6 hours
        $datetime = new DateTime($replied_at);
        $datetime->modify('+6 hours');
        $replied_at_adjusted = $datetime->format('Y-m-d H:i:s');
        $image_url = '';

        if (!empty($_FILES['reply_image']['name'])) {
            $uploaded_file = $_FILES['reply_image'];

            // Allow only certain file types
            $allowed_file_types = array('jpg', 'jpeg', 'png');
            $file_extension = strtolower(pathinfo($uploaded_file['name'], PATHINFO_EXTENSION));

            // Check file size (5 MB limit)
            $max_file_size = 5 * 1024 * 1024; // 5 MB in bytes
            if ($uploaded_file['size'] > $max_file_size) {
                wp_send_json_error(array('message' => esc_html__('File size exceeds the 5 MB limit.', 'support-ticket-system')));
                return;
            }

            if (in_array($file_extension, $allowed_file_types)) {
                $upload_overrides = array('test_form' => false);
                $movefile = wp_handle_upload($uploaded_file, $upload_overrides);

                if ($movefile && !isset($movefile['error'])) {
                    $image_url = $movefile['url'];
                    // Proceed with additional logic if needed
                } else {
                    wp_send_json_error(array('message' => esc_html__('Error uploading file.', 'support-ticket-system')));
                    return;
                }
            } else {
                wp_send_json_error(array('message' => esc_html__('Invalid file type. Only jpg, jpeg, and png are allowed.', 'support-ticket-system')));
                return;
            }
        }

        $wpdb->insert(
            $table_name,
            [
                'ticket_id' => $ticket_id,
                'message' => $message,
                'user_id' => $user_id,
                'replied_at' => $replied_at_adjusted,
                'image_url' => $image_url,
            ]
        );

        // Fetch ticket details
        $ticket_table = $wpdb->prefix . 'support_tickets';
        $ticket = $wpdb->get_row($wpdb->prepare("SELECT * FROM $ticket_table WHERE id = %d", $ticket_id));
        $user_info = get_userdata($ticket->user_id);
        $user_email = $user_info->user_email;

        // Email details
        //$from_email = 'prokash@technobd.com';
        $from_email = get_option('sts_from_email', 'prokash1@technobd.com');

        $headers = array('Content-Type: text/plain; charset=UTF-8', 'From: ' . $from_email);

        // Send email to the user
        $user_subject = 'Support Ticket Reply From Admin';
        $user_message = 'Hello ' . $user_info->display_name . ",\n\nThere is a new reply to your support ticket.\n\nReply Message: " . $message . "\n\nThank you!";
        // wp_mail($user_email, $user_subject, $user_message, $headers);

        // Send email to the admin
        $admin_email = get_option('admin_email');
        $admin_subject = 'New Reply to Support Ticket For Admin';
        $admin_message = 'A new reply has been submitted to a support ticket.\n\nTicket Details:\nName: ' . $ticket->name . "\nDomain URL: " . $ticket->phone_number . "\nSubject: " . $ticket->subject . "\nOrder ID: " . $ticket->order_id . "\nReply Message: " . $message . "\n\nPlease check the admin panel for more details.";
        // wp_mail($admin_email, $admin_subject, $admin_message, $headers);

        // Prepare the response data
        $html = sts_load_ticket_details($ticket_id);

        wp_send_json_success($html);
    } else {
        wp_send_json_error('Invalid request');
    }
}
add_action('wp_ajax_sts_handle_reply_submission', 'sts_handle_reply_submission');

?>