<?php
if (!defined('ABSPATH')) {
    exit; // Exit if accessed directly
}


?>


<div class="support-ticket-form">
    <div class="form-header">
        <h2>Create new support </h2>
        <p>Hey there! Thanks for reaching out. We're thrilled to assist you. What can we do for you today? </p>
    </div>
    <?php
    // Retrieve settings from options
    $name_label = get_option('sts_name_label', 'Your Full Name');
    $name_placeholder = get_option('sts_name_placeholder', 'Enter Your Full Name');
    $name_required = get_option('sts_name_required', 'no') === 'yes';

    $phone_number_label = get_option('sts_phone_number_label', 'Phone Number');
    $phone_number_placeholder = get_option('sts_phone_number_placeholder', 'Enter Your Phone Number');
    $phone_number_required = get_option('sts_phone_number_required', 'no') === 'yes';


    $subject_label = get_option('sts_subject_label', 'Subject');
    $subject_placeholder = get_option('sts_subject_placeholder', 'Enter Your Subject');
    $subject_required = get_option('sts_subject_required', 'no') === 'yes';

    $order_id_label = get_option('sts_order_id_label', 'Order ID');
    $order_id_placeholder = get_option('sts_order_id_placeholder', 'Enter Your Order ID');
    $order_id_required = get_option('sts_order_id_required', 'no') === 'yes';

    $message_label = get_option('sts_message_label', 'Message');
    $message_placeholder = get_option('sts_message_placeholder', 'Enter your message here');
    $message_required = get_option('sts_message_required', 'no') === 'yes';
    ?>
<?php
$order_id = isset($_GET['order_id']) ? esc_attr($_GET['order_id']) : '';
$order_name = '';
if ($order_id) {
    // Get the order object
    $order = wc_get_order($order_id);

   
        // Get the order name (e.g., billing first and last name, or a custom field)
        $billing_first_name = $order->get_billing_first_name();
        $billing_last_name = $order->get_billing_last_name();
        
        // Combine first and last name to form the full name
        $order_name = $billing_first_name . ' ' . $billing_last_name;

        // Print the order name
}
?>

    <form method="post" id="sts_support_ticket_form" enctype="multipart/form-data">
        <div class="form-content">
            <div class="form-w-50">
                <div class="ticket-form-row">
                    <label for="sts_name">
                        <?php echo esc_html($name_label); ?>
                        <?php if ($name_required): ?>
                            <span class="required"> * </span>
                        <?php endif; ?>
                    </label>
                    <input class="your-name" type="text" name="sts_name" id="sts_name" value="<?php echo esc_attr($order_name); ?>"
                        placeholder="<?php echo esc_attr($name_placeholder); ?>" <?php echo $name_required ? 'required' : ''; ?>>
                </div>
            </div>
            <div class="form-w-50">
                <div class="ticket-form-row">
                    <label for="sts_phone_number">
                        <?php echo esc_html($phone_number_label); ?>
                        <?php if ($phone_number_required): ?>
                            <span class="required"> * </span>
                        <?php endif; ?>
                    </label>
                    <input class="your-phone" type="text" name="sts_phone_number"
                        placeholder="<?php echo esc_attr($phone_number_placeholder); ?>" id="sts_phone_number" <?php echo $phone_number_required ? 'required' : ''; ?>>
                </div>
            </div>

            <div class="form-w-50">
                <div class="ticket-form-row">
                    <label for="sts_subject">
                        <?php echo esc_html($subject_label); ?>
                        <?php if ($subject_required): ?>
                            <span class="required"> * </span>
                        <?php endif; ?>
                    </label>
                    <input class="your-subject" type="text" name="sts_subject" id="sts_subject"
                        placeholder="<?php echo esc_attr($subject_placeholder); ?>" <?php echo $subject_required ? 'required' : ''; ?>>
                </div>
            </div>
            <div class="form-w-50">
                <div class="ticket-form-row">
                    <label for="sts_order_id"><?php echo esc_html($order_id_label); ?>
                        <?php if ($order_id_required): ?>
                            <span class="required"> * </span>
                        <?php endif; ?>
                    </label>
                    <input class="your-order_id" type="text" name="sts_order_id" id="sts_order_id" 
                  value="<?php echo esc_attr($order_id); ?>"
                        placeholder="<?php echo esc_attr($order_id_placeholder); ?>" <?php echo $order_id_required ? 'required' : ''; ?>>
                </div>
            </div>
            <div class="form-w-100">
                <div class="ticket-form-row">
                    <label for="sts_message">
                        <?php echo esc_html($message_label); ?>
                        <?php if ($message_required): ?>
                            <span class="required"> * </span>
                        <?php endif; ?>
                    </label>
                    <textarea class="your-message" placeholder="<?php echo esc_attr($message_placeholder); ?>"
                        name="sts_message" id="sts_message" <?php echo $message_required ? 'required' : ''; ?>></textarea>
                </div>

            </div>
            <style>
                #imagePreview {
                    display: none;
                    width: 100px;
                    height: 100px;
                    object-fit: cover;
                }
                .error-message {
        color: red;
        display: none;
    }
            </style>
            <img id="imagePreview" src="" alt="Image Preview" />
            <div class="error-message" id="fileError"></div>
 
            <a class="uplode-file" type="button" href="javascript:void(0);"
                onclick="document.getElementById('fileInput').click();">
                <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none">
                    <path
                        d="M21 15V19C21 19.5304 20.7893 20.0391 20.4142 20.4142C20.0391 20.7893 19.5304 21 19 21H5C4.46957 21 3.96086 20.7893 3.58579 20.4142C3.21071 20.0391 3 19.5304 3 19V15"
                        stroke="#000000" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" />
                    <path d="M17 8L12 3L7 8" stroke="#000000" stroke-width="2" stroke-linecap="round"
                        stroke-linejoin="round" />
                    <path d="M12 3V15" stroke="#000000" stroke-width="2" stroke-linecap="round"
                        stroke-linejoin="round" />
                </svg>
            </a>
                <input type="file" id="fileInput" style="display:none;" accept="image/*" name="reply_image" onchange="previewImage(event)" />

          
            <script>
    function previewImage(event) {
        var file = event.target.files[0];
        var fileError = document.getElementById('fileError');
        var imagePreview = document.getElementById('imagePreview');
        var reader = new FileReader();

        // Clear previous error messages and hide preview
        fileError.style.display = 'none';
        imagePreview.style.display = 'none';

        // Validate file size (5 MB limit)
        var maxFileSize = 5 * 1024 * 1024; // 5 MB in bytes
        if (file.size > maxFileSize) {
            fileError.textContent = 'File size exceeds the 5 MB limit.';
            fileError.style.display = 'block';
            return;
        }

        // Validate file type
        var allowedFileTypes = ['image/jpeg', 'image/png','image/jpg','image/PNG','image/JPG'];
        if (!allowedFileTypes.includes(file.type)) {
            fileError.textContent = 'Invalid file type. Only JPG and PNG are allowed.';
            fileError.style.display = 'block';
            return;
        }

        reader.onload = function () {
            imagePreview.src = reader.result;
            imagePreview.style.display = 'block';
        }

        if (file) {
            reader.readAsDataURL(file);
        }
    }
</script>

            <div class="form-w-100">
                <div class="submit-support-input">
                    <input class="submit-support-form" type="submit" name="sts_submit_ticket"
                        value="<?php esc_attr_e('Submit', 'support-ticket-system'); ?>">
                </div>
            </div>
        </div>
    </form>
</div>