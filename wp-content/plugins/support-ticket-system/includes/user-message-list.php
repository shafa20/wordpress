<?php
/*
Template Name: User Message List
*/

if (!defined('ABSPATH')) {
    exit; // Exit if accessed directly
}

get_header();

?>



<div class="support-ticket-container">
    <div class="support-list">
        <div class="support-list-header">
            <h2>Support</h2>
            <button id="create-new-support" class="create-ticket-btn"><svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 448 512"><!--!Font Awesome Free 6.5.2 by @fontawesome - https://fontawesome.com License - https://fontawesome.com/license/free Copyright 2024 Fonticons, Inc.-->
                    <path d="M256 80c0-17.7-14.3-32-32-32s-32 14.3-32 32V224H48c-17.7 0-32 14.3-32 32s14.3 32 32 32H192V432c0 17.7 14.3 32 32 32s32-14.3 32-32V288H400c17.7 0 32-14.3 32-32s-14.3-32-32-32H256V80z" />
                </svg> Create new Support
            </button>
        </div>
        <div class="support-list-status">
            <a href="?status=open" class="open <?php echo (isset($_GET['status']) && $_GET['status'] == 'open') ? 'active' : ''; ?>">Open</a>
            <a href="?status=closed" class="closed <?php echo (isset($_GET['status']) && $_GET['status'] == 'closed') ? 'active' : ''; ?>">Close</a>
        </div>


        <ul class="list">
            <?php
            // Display user messages with pagination
            function sts_display_user_messages()
            {
                global $wpdb;
                $user_id = get_current_user_id();
                $table_name_tickets = $wpdb->prefix . 'support_tickets';
                $table_name_replies = $wpdb->prefix . 'support_ticket_replies';

                // Pagination setup
                $items_per_page = 3;
                $current_page = 1; // Default to page 1 if not set

                // Check if the URL structure matches for pagination
                if (preg_match('/\/page\/(\d+)/', $_SERVER['REQUEST_URI'], $matches)) {
                    // Extract the page number from the URL
                    $current_page = intval($matches[1]);
                } elseif (isset($_GET['paged'])) {
                    // Fall back to the paged parameter in GET request
                    $current_page = max(1, intval($_GET['paged']));
                }

                $offset = ($current_page - 1) * $items_per_page;

                // Base query before latest sort by reply
                // $query = "SELECT t.*, u.display_name 
                //           FROM $table_name_tickets AS t 
                //           LEFT JOIN $wpdb->users AS u ON t.user_id = u.ID 
                //           WHERE t.user_id = %d";

                // Base query
                $query = "SELECT t.*, u.display_name, 
                IFNULL(MAX(r.replied_at), t.submitted_at) AS last_reply_date
                FROM $table_name_tickets AS t
                LEFT JOIN $wpdb->users AS u ON t.user_id = u.ID
                LEFT JOIN $table_name_replies AS r ON t.id = r.ticket_id
                WHERE t.user_id = %d";


             // Filter by status if provided
                if (isset($_GET['status'])) {
                    switch ($_GET['status']) {
                        case 'open':
                            $query .= " AND t.status = 1";
                            break;
                        case 'closed':
                            $query .= " AND t.status = 2";
                            break;
                        default:
                            // Handle default case if needed
                            break;
                    }
                }

                // Add pagination to the query before sort by reply
              //  $query .= " ORDER BY t.submitted_at DESC LIMIT %d OFFSET %d";
              $query .= " GROUP BY t.id ORDER BY last_reply_date DESC LIMIT %d OFFSET %d";
                // Prepare variables for the query
                $args = array($user_id, $items_per_page, $offset);

                // Fetch tickets
                $tickets = $wpdb->get_results($wpdb->prepare($query, $args));
                // echo '<pre>';
                // print_r ($tickets);
                // die;
                // Total tickets for pagination
                $count_query = "SELECT COUNT(*) FROM $table_name_tickets WHERE user_id = %d";
                if (isset($_GET['status'])) {
                    switch ($_GET['status']) {
                        case 'open':
                            $count_query .= " AND status = 1";
                            break;
                        case 'closed':
                            $count_query .= " AND status = 2";
                            break;
                       
                        default:
                            // Handle default case if needed
                            break;
                    }
                }
                $total_tickets = $wpdb->get_var($wpdb->prepare($count_query, $user_id));

                if ($tickets) {
                    foreach ($tickets as $ticket) {
                        // Check if there are replies for this ticket
                        $replies = $wpdb->get_results($wpdb->prepare("SELECT * 
                                                                      FROM $table_name_replies 
                                                                      WHERE ticket_id = %d 
                                                                      ORDER BY replied_at DESC 
                                                                      LIMIT 1", $ticket->id));

                        if ($replies) {
                            // Display the last reply message instead of the original ticket message
                            $last_reply = $replies[0];
                            $last_message = esc_html($last_reply->message);
                        } else {
                            // Display the original ticket message if no replies
                            $last_message = esc_html($ticket->message);
                        }
            ?>
                        <li>
                            <a href="<?php echo esc_url(add_query_arg('ticket_id', $ticket->id, wc_get_account_endpoint_url('support-ticket-system'))); ?>">
                                <div class="support-content">
                                    <?php 
                                    $message_table = $wpdb->prefix . 'support_ticket_replies';
                                    $unseen_count = $wpdb->get_var($wpdb->prepare("
                                    SELECT COUNT(*)
                                    FROM $message_table
                                    WHERE ticket_id = %d
                                    AND user_id != %d
                                    AND replied_at > IFNULL((
                                        SELECT MAX(replied_at)
                                        FROM $message_table
                                        WHERE ticket_id = %d
                                        AND user_id = %d
                                    ), '0000-00-00 00:00:00')
                                ", $ticket->id, $user_id, $ticket->id, $user_id));
                                    ?>
                                    <span class="user-name"><span><strong>User:</strong> <?php echo esc_html($ticket->display_name); ?></span> <span class="total-message"><?php echo esc_html($unseen_count); ?></span></span>
                                    <!-- <span class="domin-url">technobd.com</span> -->
                                    <span class="subject"><strong>Subject:</strong> <?php echo esc_html($ticket->subject); ?></span>
                                    <!-- <p><?php // echo $last_message; ?></p> -->
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
                                        }
                                        ?>
                                        <span class="support-id">Support ID: #<?php echo esc_html($ticket->id); ?></span>
                                        <span class="support-id">Order ID:<?php echo esc_html($ticket->order_id); ?></span>
                                    </div>
                                    <div class="support-footer-right">
                                        <span class="time"><svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 512 512"><!--!Font Awesome Free 6.6.0 by @fontawesome - https://fontawesome.com License - https://fontawesome.com/license/free Copyright 2024 Fonticons, Inc.-->
                                                <path d="M464 256A208 208 0 1 1 48 256a208 208 0 1 1 416 0zM0 256a256 256 0 1 0 512 0A256 256 0 1 0 0 256zM232 120l0 136c0 8 4 15.5 10.7 20l96 64c11 7.4 25.9 4.4 33.3-6.7s4.4-25.9-6.7-33.3L280 243.2 280 120c0-13.3-10.7-24-24-24s-24 10.7-24 24z" />
                                            </svg> <?php echo date('h:i A', strtotime($ticket->submitted_at)); ?></span>
                                        <span class="date"> <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 448 512"><!--!Font Awesome Free 6.6.0 by @fontawesome - https://fontawesome.com License - https://fontawesome.com/license/free Copyright 2024 Fonticons, Inc.-->
                                                <path d="M128 0c17.7 0 32 14.3 32 32l0 32 128 0 0-32c0-17.7 14.3-32 32-32s32 14.3 32 32l0 32 48 0c26.5 0 48 21.5 48 48l0 48L0 160l0-48C0 85.5 21.5 64 48 64l48 0 0-32c0-17.7 14.3-32 32-32zM0 192l448 0 0 272c0 26.5-21.5 48-48 48L48 512c-26.5 0-48-21.5-48-48L0 192zm64 80l0 32c0 8.8 7.2 16 16 16l32 0c8.8 0 16-7.2 16-16l0-32c0-8.8-7.2-16-16-16l-32 0c-8.8 0-16 7.2-16 16zm128 0l0 32c0 8.8 7.2 16 16 16l32 0c8.8 0 16-7.2 16-16l0-32c0-8.8-7.2-16-16-16l-32 0c-8.8 0-16 7.2-16 16zm144-16c-8.8 0-16 7.2-16 16l0 32c0 8.8 7.2 16 16 16l32 0c8.8 0 16-7.2 16-16l0-32c0-8.8-7.2-16-16-16l-32 0zM64 400l0 32c0 8.8 7.2 16 16 16l32 0c8.8 0 16-7.2 16-16l0-32c0-8.8-7.2-16-16-16l-32 0c-8.8 0-16 7.2-16 16zm144-16c-8.8 0-16 7.2-16 16l0 32c0 8.8 7.2 16 16 16l32 0c8.8 0 16-7.2 16-16l0-32c0-8.8-7.2-16-16-16l-32 0zm112 16l0 32c0 8.8 7.2 16 16 16l32 0c8.8 0 16-7.2 16-16l0-32c0-8.8-7.2-16-16-16l-32 0c-8.8 0-16 7.2-16 16z" />
                                            </svg> <?php echo date('j F, Y', strtotime($ticket->submitted_at)); ?></span>

                                    </div>
                                </div>
                            </a>
                        </li>

            <?php
                    }
                } else {
                    echo '<li>No support tickets found.</li>';
                }

                // Display pagination
                $total_pages = ceil($total_tickets / $items_per_page);
                if ($total_pages > 1) {
                    echo '<div class="pagination">';

                    // Previous button
                    if ($current_page > 1) {
                        $prev_page_url = (isset($_GET['status'])) ? esc_url(add_query_arg(array('status' => $_GET['status'], 'paged' => ($current_page - 1)))) : esc_url(add_query_arg('paged', ($current_page - 1)));
                        echo '<a href="' . $prev_page_url . '" class="prev">Previous</a>';
                    } else {
                        echo '<span class="prev disabled">Previous</span>';
                    }

                    // Page numbers
                    for ($i = 1; $i <= $total_pages; $i++) {
                        $class = ($i == $current_page) ? ' class="active"' : '';
                        $page_url = (isset($_GET['status'])) ? esc_url(add_query_arg(array('status' => $_GET['status'], 'paged' => $i))) : esc_url(add_query_arg('paged', $i));
                        echo '<a href="' . $page_url . '"' . $class . '>' . $i . '</a>';
                    }

                    // Next button
                    if ($current_page < $total_pages) {
                        $next_page_url = (isset($_GET['status'])) ? esc_url(add_query_arg(array('status' => $_GET['status'], 'paged' => ($current_page + 1)))) : esc_url(add_query_arg('paged', ($current_page + 1)));
                        echo '<a href="' . $next_page_url . '" class="next">Next</a>';
                    } else {
                        echo '<span class="next disabled">Next</span>';
                    }

                    echo '</div>';
                }
            }
            sts_display_user_messages();
            ?>
        </ul>
    </div>
    <div id="support-form-container" style="display:none;">
        <?php include SUPPORT_TICKET_PLUGIN_PATH . 'includes/form-page.php'; ?>
    </div>
</div>


<?php
$order_id = isset($_GET['order_id']) ? absint($_GET['order_id']) : null;

?>


<script>
    document.addEventListener('DOMContentLoaded', function() {
        // Toggle support form visibility
        function toggleSupportForm() {
            // Hide the support list and show the support form container
            document.querySelector('.support-list').style.display = 'none';
            document.getElementById('support-form-container').style.display = 'block';
        }

        // Handle 'click' event
        document.getElementById('create-new-support').addEventListener('click', toggleSupportForm);

        // Handle 'order_id' parameter
        const orderId = "<?php echo $order_id; ?>";
        if (orderId) {
            // Perform actions based on the presence of order_id
            toggleSupportForm();  // Example action: Toggle form visibility if order_id is present
        }

        // Highlight active status link
        const currentStatus = "<?php echo isset($_GET['status']) ? $_GET['status'] : ''; ?>";
        if (currentStatus) {
            const statusLinks = document.querySelectorAll('.support-list-status a');
            statusLinks.forEach(link => {
                if (link.classList.contains(currentStatus)) {
                    link.classList.add('active');
                }
            });
        }
    });
</script>
