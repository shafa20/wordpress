<?php
if (!defined('ABSPATH')) {
    exit; // Exit if accessed directly
}
?>

<!-- <div class="support-ticket-system">
    <button id="create-new-support" class="button">Create New Support</button>
    <div id="support-form-container" style="display:none;">
        <?php // include 'form-page.php'; 
        ?>
    </div>
</div> -->


<div class="support-ticket-container">

    <div class="can-not-create-ticket create-new-support">
        <div class="can-not-create-ticket-content support-form-container">

            <img src="<?php echo plugin_dir_url(__FILE__) . 'images/support-ticket.png'; ?>" alt="support-ticket">

            <div class="support-welcome-note">
                Welcome to virtuoso pixel support. We're here to answer your questions
                and resolve any issues you may have.
            </div>

            <button id="create-new-support" class="create-ticket-btn">
                <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 448 512">
                    <!--!Font Awesome Free 6.5.2 by @fontawesome - https://fontawesome.com License - https://fontawesome.com/license/free Copyright 2024 Fonticons, Inc.-->
                    <path d="M256 80c0-17.7-14.3-32-32-32s-32 14.3-32 32V224H48c-17.7 0-32 14.3-32 32s14.3 32 32 32H192V432c0 17.7 14.3 32 32 32s32-14.3 32-32V288H400c17.7 0 32-14.3 32-32s-14.3-32-32-32H256V80z" />
                </svg>
                Create new Support
            </button>
        </div>
    </div>

    <div id="support-form-container" style="display:none;">
        <?php include 'form-page.php'; ?>
    </div>

</div>
<?php
$order_id = isset($_GET['order_id']) ? absint($_GET['order_id']) : null;

?>
<!-- <script>
    document.getElementById('create-new-support').addEventListener('click', function() {
        document.querySelector('.can-not-create-ticket.create-new-support').style.display = 'none';
        document.getElementById('support-form-container').style.display = 'block';
    });
</script> -->


<script>
    document.addEventListener('DOMContentLoaded', function() {
        // Toggle support form visibility
        function toggleSupportForm() {
            // Hide the support list and show the support form container
            document.querySelector('.can-not-create-ticket.create-new-support').style.display = 'none';
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
    });
</script>