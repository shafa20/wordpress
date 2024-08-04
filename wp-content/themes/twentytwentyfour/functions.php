<?php
/**
 * Twenty Twenty-Four functions and definitions
 *
 * @link https://developer.wordpress.org/themes/basics/theme-functions/
 *
 * @package Twenty Twenty-Four
 * @since Twenty Twenty-Four 1.0
 */

/**
 * Register block styles.
 */

if (!function_exists('twentytwentyfour_block_styles')):
	/**
	 * Register custom block styles
	 *
	 * @since Twenty Twenty-Four 1.0
	 * @return void
	 */
	function twentytwentyfour_block_styles()
	{

		register_block_style(
			'core/details',
			array(
				'name' => 'arrow-icon-details',
				'label' => __('Arrow icon', 'twentytwentyfour'),
				/*
				 * Styles for the custom Arrow icon style of the Details block
				 */
				'inline_style' => '
				.is-style-arrow-icon-details {
					padding-top: var(--wp--preset--spacing--10);
					padding-bottom: var(--wp--preset--spacing--10);
				}

				.is-style-arrow-icon-details summary {
					list-style-type: "\2193\00a0\00a0\00a0";
				}

				.is-style-arrow-icon-details[open]>summary {
					list-style-type: "\2192\00a0\00a0\00a0";
				}',
			)
		);
		register_block_style(
			'core/post-terms',
			array(
				'name' => 'pill',
				'label' => __('Pill', 'twentytwentyfour'),
				/*
				 * Styles variation for post terms
				 * https://github.com/WordPress/gutenberg/issues/24956
				 */
				'inline_style' => '
				.is-style-pill a,
				.is-style-pill span:not([class], [data-rich-text-placeholder]) {
					display: inline-block;
					background-color: var(--wp--preset--color--base-2);
					padding: 0.375rem 0.875rem;
					border-radius: var(--wp--preset--spacing--20);
				}

				.is-style-pill a:hover {
					background-color: var(--wp--preset--color--contrast-3);
				}',
			)
		);
		register_block_style(
			'core/list',
			array(
				'name' => 'checkmark-list',
				'label' => __('Checkmark', 'twentytwentyfour'),
				/*
				 * Styles for the custom checkmark list block style
				 * https://github.com/WordPress/gutenberg/issues/51480
				 */
				'inline_style' => '
				ul.is-style-checkmark-list {
					list-style-type: "\2713";
				}

				ul.is-style-checkmark-list li {
					padding-inline-start: 1ch;
				}',
			)
		);
		register_block_style(
			'core/navigation-link',
			array(
				'name' => 'arrow-link',
				'label' => __('With arrow', 'twentytwentyfour'),
				/*
				 * Styles for the custom arrow nav link block style
				 */
				'inline_style' => '
				.is-style-arrow-link .wp-block-navigation-item__label:after {
					content: "\2197";
					padding-inline-start: 0.25rem;
					vertical-align: middle;
					text-decoration: none;
					display: inline-block;
				}',
			)
		);
		register_block_style(
			'core/heading',
			array(
				'name' => 'asterisk',
				'label' => __('With asterisk', 'twentytwentyfour'),
				'inline_style' => "
				.is-style-asterisk:before {
					content: '';
					width: 1.5rem;
					height: 3rem;
					background: var(--wp--preset--color--contrast-2, currentColor);
					clip-path: path('M11.93.684v8.039l5.633-5.633 1.216 1.23-5.66 5.66h8.04v1.737H13.2l5.701 5.701-1.23 1.23-5.742-5.742V21h-1.737v-8.094l-5.77 5.77-1.23-1.217 5.743-5.742H.842V9.98h8.162l-5.701-5.7 1.23-1.231 5.66 5.66V.684h1.737Z');
					display: block;
				}

				/* Hide the asterisk if the heading has no content, to avoid using empty headings to display the asterisk only, which is an A11Y issue */
				.is-style-asterisk:empty:before {
					content: none;
				}

				.is-style-asterisk:-moz-only-whitespace:before {
					content: none;
				}

				.is-style-asterisk.has-text-align-center:before {
					margin: 0 auto;
				}

				.is-style-asterisk.has-text-align-right:before {
					margin-left: auto;
				}

				.rtl .is-style-asterisk.has-text-align-left:before {
					margin-right: auto;
				}",
			)
		);
	}
endif;

add_action('init', 'twentytwentyfour_block_styles');

/**
 * Enqueue block stylesheets.
 */

if (!function_exists('twentytwentyfour_block_stylesheets')):
	/**
	 * Enqueue custom block stylesheets
	 *
	 * @since Twenty Twenty-Four 1.0
	 * @return void
	 */
	function twentytwentyfour_block_stylesheets()
	{
		/**
		 * The wp_enqueue_block_style() function allows us to enqueue a stylesheet
		 * for a specific block. These will only get loaded when the block is rendered
		 * (both in the editor and on the front end), improving performance
		 * and reducing the amount of data requested by visitors.
		 *
		 * See https://make.wordpress.org/core/2021/12/15/using-multiple-stylesheets-per-block/ for more info.
		 */
		wp_enqueue_block_style(
			'core/button',
			array(
				'handle' => 'twentytwentyfour-button-style-outline',
				'src' => get_parent_theme_file_uri('assets/css/button-outline.css'),
				'ver' => wp_get_theme(get_template())->get('Version'),
				'path' => get_parent_theme_file_path('assets/css/button-outline.css'),
			)
		);
	}
endif;

add_action('init', 'twentytwentyfour_block_stylesheets');
// custom colur code start from here
function theme_custom_product_taxonomy()
{
	$labels = array(
		'name' => _x('Color', 'taxonomy general name', 'textdomain'),
		'singular_name' => _x('Color', 'taxonomy singular name', 'textdomain'),
		'search_items' => __('Search Colors', 'textdomain'),
		'popular_items' => __('Popular Colors', 'textdomain'),
		'all_items' => __('All Colors', 'textdomain'),
		'edit_item' => __('Edit Color', 'textdomain'),
		'update_item' => __('Update Color', 'textdomain'),
		'add_new_item' => __('Add New Color', 'textdomain'),
		'new_item_name' => __('New Color Name', 'textdomain'),
		'separate_items_with_commas' => __('Separate colors with commas', 'textdomain'),
		'add_or_remove_items' => __('Add or remove colors', 'textdomain'),
		'choose_from_most_used' => __('Choose from the most used colors', 'textdomain'),
		'not_found' => __('No colors found', 'textdomain'),
		'menu_name' => __('Color', 'textdomain'),
	);
	$args = array(
		'labels' => $labels,
		'public' => true,
		'hierarchical' => true,
		'show_ui' => true,
		'show_admin_column' => true,
		'show_in_nav_menus' => false,
		'show_tagcloud' => false,
	);
	register_taxonomy('product_color', 'product', $args);
}
add_action('init', 'theme_custom_product_taxonomy', 0);

// custom colur code end

// product colors start from here

// Include the custom WP_List_Table class
require_once get_template_directory() . '/class-product-color-list-table.php';

// Register the custom admin menu page
add_action('admin_menu', 'register_product_color_admin_page');

function register_product_color_admin_page()
{
	add_menu_page(
		'Product Colors',     // Page title
		'Product Colors',     // Menu title
		'manage_options',     // Capability
		'product-color-page', // Menu slug
		'product_color_admin_page_content', // Callback function
		'dashicons-admin-appearance', // Icon
		6                     // Position
	);
}

//color style
function add_custom_styles()
{
	?>
	<style>
		.wp-list-table .column-colors {
			width: 40%;
		}

		.gradient-box {
			width: 35px;
			height: 20px;
			display: inline-block;

		}

		.gradient-box.black {
			background: linear-gradient(90deg, #000 0%, #737373 100%);
		}

		.gradient-box.brown {
			background: linear-gradient(90deg, #CD853F 0%, #4A2C2A 100%);
		}

		.gradient-box.red {
			background: linear-gradient(90deg, #E00 0%, #730013 100%);
		}

		.gradient-box.green {
			background: linear-gradient(90deg, #228B22 0%, #355E3B 100%);
		}

		.gradient-box.yellow {
			background: linear-gradient(90deg, #FFD700 0%, #D4AF37 100%);
		}

		.gradient-box.silver {
			background: linear-gradient(90deg, #E5E4E2 0%, #808080 100%);
		}

		.gradient-box.ivory {
			background: linear-gradient(90deg, #FFFFF0 0%, #F0EAD6 100%);
		}

		.gradient-box.blue {
			background: linear-gradient(90deg, #4169E1 0%, #191970 100%);
		}

		.gradient-box.sky-blue {
			background: linear-gradient(90deg, #73C2FA 0%, #007FFF 100%);
		}

		.gradient-box.taupe {
			background: linear-gradient(90deg, #C3B091 0%, #483C32 100%);
		}

		.gradient-box.teal {
			background: linear-gradient(90deg, #0FF 0%, #008080 100%);
		}

		.gradient-box.white {
			background: linear-gradient(90deg, #F8F8FF 0%, #F5F5F5 100%);
		}

		.gradient-box.pink {
			background: linear-gradient(90deg, #E681FF 0%, #CE00D1 100%);
		}
	</style>
	<?php
}
add_action('admin_head', 'add_custom_styles');

// show productt colors page content
function product_color_admin_page_content()
{
	?>
	<div class="wrap">
		<h1><?php _e('Product Colors', 'textdomain'); ?></h1>

		<?php
		// Start the session
		session_start();
		?>
		<!-- Check if the session variable is set -->
		<?php if (isset($_SESSION['product_sku_list'])): ?>
			<style>
				.hide-button {
					display: none;
				}
			</style>
			<form method="post" enctype="multipart/form-data">
				<input type="file" name="product_color_csv" accept=".csv" style="display: none;">
				<input type="submit" name="import_csv" class="button button-primary"
					value="<?php _e('Import CSV', 'textdomain'); ?>" style="display: none;">
				<input type="submit" name="reset" class="button button-secondary" value="<?php _e('Reset', 'textdomain'); ?>">
			</form>
		<?php else: ?>
			<form method="post" enctype="multipart/form-data">
				<input type="file" name="product_color_csv" accept=".csv">
				<input type="submit" name="import_csv" class="button button-primary"
					value="<?php _e('Import CSV', 'textdomain'); ?>">
				<input type="submit" name="reset" class="button button-secondary" value="<?php _e('Reset', 'textdomain'); ?>">
			</form>
		<?php endif; ?>

		<?php
		$example_lt = new Example_List_Table();
		$example_lt->prepare_items();
		?>
		<form method="post" id="product-color-form">
			<?php
			$example_lt->display();
			?>
		</form>
		<div id="update-success" style="display:none; color: green; font-weight: bold;">
			<?php _e('Product color updated successfully!', 'textdomain'); ?>
		</div>
	</div>
	<script type="text/javascript">
		document.addEventListener('DOMContentLoaded', function () {
			var checkboxes = document.querySelectorAll('input[type="checkbox"][name^="product_color"]');
			checkboxes.forEach(function (checkbox) {
				checkbox.addEventListener('change', function () {
					var productId = this.getAttribute('data-product-id');
					var colorId = this.getAttribute('data-color-id');
					var checked = this.checked ? 'yes' : 'no';

					var data = {
						action: 'update_product_color',
						product_id: productId,
						color_id: colorId,
						checked: checked,
						_ajax_nonce: '<?php echo wp_create_nonce('update_product_color_nonce'); ?>'
					};

					jQuery.post(ajaxurl, data, function (response) {
						if (response.success) {
							document.getElementById('update-success').style.display = 'block';
							setTimeout(function () {
								document.getElementById('update-success').style.display = 'none';
							}, 2000);
						}
					});
				});
			});
		});
	</script>
	<?php
}

// Handle form submissions for import CSV and reset
add_action('admin_init', 'handle_product_color_form');

function handle_product_color_form()
{
	if (isset($_POST['import_csv'])) {
		// Check if file is uploaded
		if (isset($_FILES['product_color_csv']) && $_FILES['product_color_csv']['error'] == 0) {
			$csv_file = $_FILES['product_color_csv']['tmp_name'];
			$sku_list = read_csv($csv_file);

			if ($sku_list) {
				// Start session and save SKU list
				session_start();
				$_SESSION['product_sku_list'] = $sku_list;
				wp_redirect(admin_url('admin.php?page=product-color-page'));
				exit;
			}
		}
	}

	if (isset($_POST['reset'])) {
		// Destroy session
		session_start();
		session_destroy();
		wp_redirect(admin_url('admin.php?page=product-color-page'));
		exit;
	}
}
// read inside csv file value
function read_csv($file)
{
	$sku_list = array();
	if (($handle = fopen($file, 'r')) !== false) {
		while (($data = fgetcsv($handle)) !== false) {
			if (!empty($data[0])) {
				$sku_list[] = trim($data[0]);
			}
		}
		fclose($handle);
	}
	return $sku_list;
}



// update product color by AJAX
add_action('wp_ajax_update_product_color', 'update_product_color');

function update_product_color()
{
	check_ajax_referer('update_product_color_nonce');

	$product_id = intval($_POST['product_id']);
	$color_id = intval($_POST['color_id']);
	$checked = sanitize_text_field($_POST['checked']);

	if ($checked === 'yes') {
		wp_set_object_terms($product_id, array($color_id), 'product_color', true);
	} else {
		wp_remove_object_terms($product_id, $color_id, 'product_color');
	}

	wp_send_json_success();
}
// product colors end here

// price update start from here
add_action('admin_menu', 'price_update_menu');

function price_update_menu()
{
	add_menu_page(
		'Price Update', // Page title
		'Price Update', // Menu title
		'manage_options', // Capability
		'price-update', // Menu slug
		'price_update_page', // Function to display page content
		'dashicons-edit', // Icon URL
		6 // Position
	);
}
// show content of price update
function price_update_page()
{
	global $wpdb;

	// Handle form submission
	if (isset($_POST['update_prices'])) {
		foreach ($_POST['price'] as $id => $price) {
			// Ensure the price is a float and properly formatted
			$price = floatval($price);

			// Update the database
			$wpdb->update(
				'wp_printful_global_price',
				array('price' => $price),
				array('id' => intval($id)),
				array('%f'),
				array('%d')
			);
		}
		echo '<div class="notice notice-success is-dismissible" style="color:green;"><p>Prices updated successfully.</p></div>';
	}

	// Fetch data from the custom table
	$results = $wpdb->get_results("SELECT * FROM `wp_printful_global_price`");

	echo '<div class="wrap">';
	echo '<h1>Price Update</h1>';
	echo '<form method="post">';
	echo '<table class="wp-list-table widefat fixed striped">';
	echo '<thead><tr><th>Item</th><th>Size</th><th>Retail Price</th></tr></thead>';
	echo '<tbody>';

	if ($results) {
		foreach ($results as $row) {
			echo '<tr>';
			echo '<td>' . esc_html($row->item_name) . '</td>';
			echo '<td>' . esc_html($row->size) . '</td>';
			echo '<td><input style="text-align: right;" type="number" step="0.01" min="0" name="price[' . intval($row->id) . ']" value="' . esc_attr($row->price) . '"></td>';
			echo '</tr>';
		}
	} else {
		echo '<tr><td colspan="3">No items found.</td></tr>';
	}

	echo '</tbody>';
	echo '</table>';
	echo '<p><input type="submit" name="update_prices" class="button-primary" value="Update"></p>';
	echo '</form>';
	echo '</div>';
}
// price update end here
//WooCommerce Discount Manager start here
// Hook to add custom menu
add_action('admin_menu', 'add_discounts_menu');

function add_discounts_menu()
{
	add_menu_page(
		'Discounts',            // Page title
		'Discounts',            // Menu title
		'manage_options',       // Capability required
		'discounts',            // Menu slug
		'discounts_page_content', // Callback function
		'dashicons-tag',        // Icon URL (optional)
		6                       // Position (optional)
	);
}

// Hook to add custom menu
add_action('admin_menu', 'add_discounts_menu');
// Include the custom WP_List_Table class
require_once get_template_directory() . '/discount-list-table.php';
// Enqueue the custom JS file for the admin page
function enqueue_discount_js()
{
	// Ensure the script is only loaded on the Discounts page
	$screen = get_current_screen();
	if ($screen->id === 'toplevel_page_discounts') {
		wp_enqueue_script('discount-js', get_template_directory_uri() . '/js/discount.js', array(), '1.0.0', true);
	}
}
add_action('admin_enqueue_scripts', 'enqueue_discount_js');




function discounts_page_content()
{
	?>
	<div class="wrap">
		<h1>Discounts</h1>
		<div class="wdm-admin-page-title">
			<h2>Discounts
				<a class="page-title-action" href="#" id="add-new-button">Add New</a>
			</h2>
		</div>

		<!-- The table displaying the discounts -->
		<?php
		$exampleListTable = new Example_List_Table2();
		$exampleListTable->prepare_items();
		$exampleListTable->display();
		?>

		<!-- Hidden form content -->
		<div id="discount-form-content" style="display:none;">
			<?php
			ob_start();
			discount_details_meta_box(); // Call to render the form content
			$form_content = ob_get_clean();
			?>
			<div id="form-placeholder-content">
				<?php echo $form_content; ?>
			</div>
		</div>
	</div>

	<style>
		/* Style the switch container */
		.switch {
			position: relative;
			display: inline-block;
			width: 34px;
			height: 20px;
		}

		/* Hide default HTML checkbox */
		.switch input {
			opacity: 0;
			width: 0;
			height: 0;
		}

		/* Style the slider */
		.slider {
			position: absolute;
			cursor: pointer;
			top: 0;
			left: 0;
			right: 0;
			bottom: 0;
			background-color: #ccc;
			transition: .4s;
			border-radius: 20px;
		}

		/* Slider before */
		.slider:before {
			position: absolute;
			content: "";
			height: 12px;
			width: 12px;
			border-radius: 50%;
			left: 4px;
			bottom: 4px;
			background-color: white;
			transition: .4s;
		}

		/* Checked slider */
		input:checked+.slider {
			background-color: #2196F3;
		}

		/* Move slider when checked */
		input:checked+.slider:before {
			transform: translateX(14px);
		}
	</style>


	<?php
}
function my_enqueue_admin_scripts()
{
	wp_enqueue_script('jquery');
	wp_enqueue_script('thickbox');
	wp_enqueue_style('thickbox');
	wp_enqueue_script('media-upload');
	wp_enqueue_script('wp-editor');
	wp_enqueue_style('wp-edit-blocks');
	wp_enqueue_style('editor-buttons');
	wp_enqueue_script('quicktags');
	wp_enqueue_script('editor');
	wp_enqueue_script('wplink');
}
add_action('admin_enqueue_scripts', 'my_enqueue_admin_scripts');


// Function to render the discount details meta box content
function discount_details_meta_box()
{
	?>
	<form id="discount-form">
		<table class="form-table">
			<tr>
				<th scope="row"><label for="discount_name">Discount name</label><span
						class="dashicon dashicons dashicons-editor-help barn2-help-tip"></span></th>
				<td><input type="text" id="discount_name" name="discount_name"
						class="regular-text components-text-control__input"></td>
			</tr>

			<tr>
				<th scope="row"><label for="discount_type">Discount type</label></th>
				<td>
					<select id="discount_type" name="discount_type" class="components-base-control__field">
						<option value="simple">Simple</option>
						<option value="total_spend">Based on total spend</option>
					</select>
				</td>
			</tr>

			<tr>
				<th scope="row">
					<label for="which_products">Which products?
						<span class="dashicon dashicons dashicons-editor-help barn2-help-tip"></span>
					</label>
				</th>
				<td>
					<fieldset>
						<label><input type="radio" name="which_products" value="all_products" checked> All
							products</label><br>
						<label><input type="radio" name="which_products" value="selected_products"> Selected
							products</label><br>
						<label><input type="radio" name="which_products" value="selected_categories"> Selected
							categories</label>

						<!-- Product Selector -->
						<div class="product-selector" id="product-selector">
							<div class="search-container">
								<label for="product-search">Search for products</label>
								<input type="search" id="product-search" placeholder="Search for products">
							</div>
							<ul id="product-list" class="product-list">
								<li><input type="checkbox" id="product-1" value="Beanie"><label
										for="product-1">Beanie</label></li>
								<li><input type="checkbox" id="product-2" value="Beanie with Logo"><label
										for="product-2">Beanie with Logo</label></li>
								<!-- Add more products as needed -->
							</ul>
							<div class="selected-products">
								<div class="selected-header">
									<strong>Selected products</strong>
									<button type="button" id="clear-products" class="clear-all">Clear all</button>
								</div>
								<ul id="selected-products-list"></ul>
							</div>
						</div>

						<!-- Category Selector -->
						<div class="category-selector" id="category-selector" style="display: none;">
							<div class="search-container">
								<label for="category-search">Search for categories</label>
								<input type="search" id="category-search" placeholder="Search for categories">
							</div>
							<ul id="category-list" class="category-list">
								<li><input type="checkbox" id="category-1" value="Category 1"><label
										for="category-1">Category 1</label></li>
								<li><input type="checkbox" id="category-2" value="Category 2"><label
										for="category-2">Category 2</label></li>
								<!-- Add more categories as needed -->
							</ul>
							<div class="selected-categories">
								<div class="selected-header">
									<strong>Selected categories</strong>
									<button type="button" id="clear-categories" class="clear-all">Clear all</button>
								</div>
								<ul id="selected-categories-list"></ul>
							</div>
						</div>
					</fieldset>
				</td>

			</tr>
			<tr>
				<th scope="row"><label for="discount">Discount</label></th>
				<td>
					<fieldset>
						<label><input type="radio" name="discount_type" value="percentage_discount" checked> Percentage
							discount</label>
						<br>
						<label><input type="radio" name="discount_type" value="fixed_discount"> Fixed discount</label>

						<div class="">
							<div class=""><span class="">%</span><input class="" type="text" value="0">
								<div class=""></div>
							</div>
						</div>
					</fieldset>
				</td>

			</tr>

			<tr>

				<th scope="row"><label for="sale_badge">Sale badge</label></th>
				<td>
					<input type="checkbox" id="sale_badge" name="sale_badge" checked>
					<label for="sale_badge">Display a sale badge on products eligible for this discount</label>
				</td>


			</tr>


			<tr>
    <th scope="row"><label for="applies_to">Applies to</label></th>
    <td>
        <fieldset>
            <label><input type="radio" name="applies_to" value="everyone" checked> Everyone</label><br>
            <label><input type="radio" name="applies_to" value="selected_roles"> Selected roles</label><br>
            <label><input type="radio" name="applies_to" value="selected_users"> Selected users</label>
        </fieldset>

        <!-- Role Selector -->
        <div class="role-selector" id="role-selector" style="display: none;">
            <div class="search-container">
                <label for="role-search">Search for roles</label>
                <input type="search" id="role-search" placeholder="Search for roles">
            </div>
            <ul id="role-list" class="role-list">
                <li><input type="checkbox" id="role-1" value="Admin"><label for="role-1">Admin</label></li>
                <li><input type="checkbox" id="role-2" value="Editor"><label for="role-2">Editor</label></li>
                <!-- Add more roles as needed -->
            </ul>
            <div class="selected-roles">
                <div class="selected-header">
                    <strong>Selected roles</strong>
                    <button type="button" id="clear-roles" class="clear-all" style="display: none;">Clear all</button>
                </div>
                <ul id="selected-roles-list"></ul>
            </div>
        </div>

        <!-- User Selector -->
        <div class="user-selector" id="user-selector" style="display: none;">
            <div class="search-container">
                <label for="user-search">Search for users</label>
                <input type="search" id="user-search" placeholder="Search for users">
            </div>
            <ul id="user-list" class="user-list">
                <li><input type="checkbox" id="user-1" value="John Doe"><label for="user-1">John Doe</label></li>
                <li><input type="checkbox" id="user-2" value="Jane Smith"><label for="user-2">Jane Smith</label></li>
                <!-- Add more users as needed -->
            </ul>
            <div class="selected-users">
                <div class="selected-header">
                    <strong>Selected users</strong>
                    <button type="button" id="clear-users" class="clear-all" style="display: none;">Clear all</button>
                </div>
                <ul id="selected-users-list"></ul>
            </div>
        </div>
    </td>
</tr>




			<tr class="exmp" id="exmp">
				<td class="">
					<label for="exclusions"><span>Exclusions</span></label>
				</td>
				<td class="">
					<div class="product-category-wrapper">
						<div class="product-search">
							<label for="product-search">Search for products</label>
							<input class="components-text-control__input" type="search" id="product-search"
								placeholder="Search for products">
							<ul id="product-list" class="search-list">
								<!-- Product items will be listed here -->
								<li>
									<label>
										<input type="checkbox" name="products" value="product1">
										<span>Product 1</span>
									</label>
								</li>
								<li>
									<label>
										<input type="checkbox" name="products" value="product2">
										<span>Product 2</span>
									</label>
								</li>
							</ul>
							<button id="clear-products" type="button">Clear All Products</button>
							<ul id="selected-products-list" class="selected-list">
								<!-- Selected products will be listed here -->
							</ul>
						</div>
						<div class="category-search">
							<label for="category-search">Search for categories</label>
							<input class="components-text-control__input" type="search" id="category-search"
								placeholder="Search for categories">
							<ul id="category-list" class="search-list">
								<!-- Category items will be listed here -->
								<li>
									<label>
										<input type="checkbox" name="categories" value="category1">
										<span>Category 1</span>
									</label>
								</li>
								<li>
									<label>
										<input type="checkbox" name="categories" value="category2">
										<span>Category 2</span>
									</label>
								</li>
							</ul>
							<button id="clear-categories" type="button">Clear All Categories</button>
							<ul id="selected-categories-list" class="selected-list">
								<!-- Selected categories will be listed here -->
							</ul>
						</div>
					</div>
				</td>
			</tr>
			<tr>
				<th scope="row"><label for="availability">Availability <span
							class="dashicon dashicons dashicons-editor-help barn2-help-tip"></span></label></th>
				<td>
					<fieldset>
						<label>
							<input type="radio" name="availability" value="always_available" id="always_available" checked>
							Always available
						</label><br>
						<label>
							<input type="radio" name="availability" value="specific_dates" id="specific_dates">
							Specific dates
						</label>
					</fieldset>
					<div id="date-fields" style="display:none;">
						<label for="start-date">Start Date:</label>
						<input type="date" id="start-date" name="start-date">
						<label for="end-date">End Date:</label>
						<input type="date" id="end-date" name="end-date">
					</div>
				</td>
			</tr>


			<tr>
				<th scope="row"><label for="product_page_content">Product page content <span
							class="dashicon dashicons dashicons-editor-help barn2-help-tip"></span></label></th>
				<td>
					<?php
					$content = ''; // You can pre-fill this with any content you need
					$editor_id = 'product_page_content';
					$settings = array(
						'textarea_name' => 'product_page_content',
						'media_buttons' => true,
						'textarea_rows' => 10,
						'teeny' => false,
						'quicktags' => true
					);
					wp_editor($content, $editor_id, $settings);
					?>
				</td>

			</tr>
			<tr style="display:none">
				<th scope="row"><label for="product_page_content">Product page content</label></th>
				<td>
					<?php
					$content = ''; // You can pre-fill this with any content you need
					$editor_id = 'product_page_content';
					$settings = array(
						'textarea_name' => 'product_page_content',
						'media_buttons' => true,
						'textarea_rows' => 10,
						'teeny' => false,
						'quicktags' => true
					);
					wp_editor($content, $editor_id, $settings);
					?>
				</td>
			</tr>
			<tr>
				<th scope="row"><label for="content_location">Content location</label></th>
				<td>
					<select id="content_location" name="content_location">
						<option value="before_add_to_cart">Before add to cart button</option>
						<!-- Add other options as needed -->
					</select>
				</td>
			</tr>
			<tr>
				<th scope="row"><label for="cart_notice">Cart notice</label></th>
				<td><input type="text" id="cart_notice" name="cart_notice"
						class="regular-text components-text-control__input"></td>
			</tr>
			<tr>
				<th scope="row"></th>
				<td>
					<input type="submit" class="button button-primary" value="Save">
				</td>
			</tr>

		</table>
	</form>

	<?php
}

/**
 * Register pattern categories.
 */

if (!function_exists('twentytwentyfour_pattern_categories')):
	/**
	 * Register pattern categories
	 *
	 * @since Twenty Twenty-Four 1.0
	 * @return void
	 */
	function twentytwentyfour_pattern_categories()
	{

		register_block_pattern_category(
			'twentytwentyfour_page',
			array(
				'label' => _x('Pages', 'Block pattern category', 'twentytwentyfour'),
				'description' => __('A collection of full page layouts.', 'twentytwentyfour'),
			)
		);
	}
endif;

add_action('init', 'twentytwentyfour_pattern_categories');
