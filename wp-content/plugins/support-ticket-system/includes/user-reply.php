<?php
/*
Template Name: User Reply
*/

if (!defined('ABSPATH')) {
    exit; // Exit if accessed directly
}

get_header();

// Fetch ticket details and the last reply date
$ticket_id = isset($_GET['ticket_id']) ? intval($_GET['ticket_id']) : 0;
$ticket = null;
$last_reply_date = null;

if ($ticket_id) {
    global $wpdb;
    $ticket_table = $wpdb->prefix . 'support_tickets';
    $replies_table = $wpdb->prefix . 'support_ticket_replies';

    // Fetch ticket details
    $ticket = $wpdb->get_row($wpdb->prepare("SELECT * FROM $ticket_table WHERE id = %d", $ticket_id));

    // Fetch last reply date
    $last_reply = $wpdb->get_row($wpdb->prepare("SELECT replied_at FROM $replies_table WHERE ticket_id = %d ORDER BY replied_at DESC LIMIT 1", $ticket_id));
    if ($last_reply) {
        $last_reply_date = date('j F, Y, g:i A', strtotime($last_reply->replied_at));
    }
}
?>

<div class="support-ticket-container">
    <div class="support-message">
        <div class="message-header">
            <a href="<?php echo esc_url(home_url('/my-account/support-ticket-system/')); ?>">
                <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none">
                    <rect width="24" height="24" rx="5" fill="#000000" />
                    <path d="M19 12H5" stroke="white" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" />
                    <path d="M12 19L5 12L12 5" stroke="white" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" />
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
                        <span class="support-id"><?php echo esc_html__('Support ID:', 'your-text-domain'); ?> #<?php echo esc_html($ticket->id); ?></span>
                        <span class="support-id"><?php echo esc_html__('Order ID:', 'your-text-domain'); ?> <?php echo esc_html($ticket->order_id); ?></span>
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
                    function sts_display_user_replies($ticket_id)
                    {
                        global $wpdb;
                        $replies_table = $wpdb->prefix . 'support_ticket_replies';
                        $ticket = $wpdb->get_row($wpdb->prepare("SELECT * FROM wp_support_tickets WHERE id = %d", $ticket_id));
                        $replies = $wpdb->get_results($wpdb->prepare("SELECT * FROM $replies_table WHERE ticket_id = %d ORDER BY replied_at ASC", $ticket_id));
                        echo '<p class="initial-message">' . '</strong> ' . esc_html($ticket->message)  . '</p>';
                    
                        if ($replies) {
                            $previous_date = null;

                            foreach ($replies as $reply) {
                                $user_info = get_userdata($reply->user_id);
                                $message_class = in_array('administrator', $user_info->roles) ? 'in-coming' : 'outgoing-message';
                                $message_date = date('j/n/Y', strtotime($reply->replied_at));
                                $message_time = date_i18n('g:i A', strtotime($reply->replied_at));
                                $image_url = $reply->image_url;

                                if ($previous_date !== $message_date) {
                                    echo '<div class="line-38"><div class="date">' . esc_html($message_date) . '</div></div>';
                                    $previous_date = $message_date;
                                }

                                echo '<div class="message-item ' . esc_attr($message_class) . '">';
                                echo '<div class="message-content">';

                                // // Display image if available
                                // if (!empty($image_url)) {
                                //     echo '<div class="message-image">';
                                //     echo '<img src="' . esc_url($image_url) . '" alt="Image" />';
                                //     echo '</div>';
                                // }

                                // Display image name if availableif 
                                if (!empty($reply->image_url)) { 
                                    $image_name = basename($image_url); 
                                    echo '<div class="lightbox-trigger message-image"   data-image="' . esc_url($image_url) . '">' . esc_html($image_name) . '</div>'; 
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
                    }

                    if (isset($_GET['ticket_id']) && is_user_logged_in()) {
                        sts_display_user_replies($ticket_id);
                    } else {
                        echo '<p>' . esc_html__('No ticket ID provided or you are not logged in.', 'support-ticket-system') . '</p>';
                    }
                    ?>



                </div>
            </div>
        </div>


        <div class="chat-footer">
            <?php
            // Check if ticket status is not closed (status 2)
            if ($ticket && $ticket->status != 2 && $ticket->status != 3) :
            ?>
                <form id="reply-form" method="post" enctype="multipart/form-data">
                    <textarea id="reply" name="sts_reply_message" placeholder="<?php echo esc_attr__('Type your message here...', 'support-ticket-system'); ?>" required></textarea>
                    
                    <input type="hidden" name="ticket_id" value="<?php echo esc_attr($ticket_id); ?>">
                    <img id="imagePreview" src="" alt="Image Preview" style="display:none; width: 50px;" />

                    <a class="uplode-file" type="button" href="javascript:void(0);" onclick="document.getElementById('fileInput').click();">
                        <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none">
                            <path d="M21 15V19C21 19.5304 20.7893 20.0391 20.4142 20.4142C20.0391 20.7893 19.5304 21 19 21H5C4.46957 21 3.96086 20.7893 3.58579 20.4142C3.21071 20.0391 3 19.5304 3 19V15" stroke="#000000" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" />
                            <path d="M17 8L12 3L7 8" stroke="#000000" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" />
                            <path d="M12 3V15" stroke="#000000" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" />
                        </svg>
                    </a>
                    <input id="fileInput" class="upload-file d-none" type="file" name="reply_image" accept="image/*">
                    <button class="send_message" id="submit-button" class="send_message" type="submit">
                        <?php echo esc_html__('SEND', 'your-text-domain'); ?>
                    </button>
                    

                   
                    <script>
                        document.getElementById('fileInput').addEventListener('change', function(event) {
                            const file = event.target.files[0];
                            if (file) {
                                const reader = new FileReader();
                                reader.onload = function(e) {
                                    const imagePreview = document.getElementById('imagePreview');
                                    imagePreview.src = e.target.result;
                                    imagePreview.style.display = 'block';
                                };
                                reader.readAsDataURL(file);
                            }
                        });
                        
                    </script>
                </form>
            <?php else : ?>
                <form id="reply-form" method="post">
                    <input id="reply" name="sts_reply_message" placeholder="<?php echo esc_attr__('Type your message here...', 'support-ticket-system'); ?>" required disabled></input>
                    <input type="hidden" name="ticket_id" value="<?php echo esc_attr($ticket_id); ?>">
                    <button class="uplode-file" type="submit">
                        <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none">
                            <path d="M21 15V19C21 19.5304 20.7893 20.0391 20.4142 20.4142C20.0391 20.7893 19.5304 21 19 21H5C4.46957 21 3.96086 20.7893 3.58579 20.4142C3.21071 20.0391 3 19.5304 3 19V15" stroke="#0FAFE9" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" />
                            <path d="M17 8L12 3L7 8" stroke="#0FAFE9" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" />
                            <path d="M12 3V15" stroke="#0FAFE9" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" />
                        </svg>
                    </button>
                    <button class="send_message"  type="submit" disabled><?php echo esc_html__('SEND', 'your-text-domain'); ?></button>
                </form>
            <?php endif; ?>
        </div>

    </div>
</div>

<?php

?>
<script>
    document.addEventListener('DOMContentLoaded', function() {
        // Scroll to the bottom of the chat container
        var chatContainer = document.querySelector('.scrollbar-container');
        if (chatContainer) {
            chatContainer.scrollTop = chatContainer.scrollHeight;
        }
    });
</script>
