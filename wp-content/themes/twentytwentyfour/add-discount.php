
<?php
function add_discount_page() {
    ?>
    <style>
/* Container for the product and category lists */
.product-list, .category-list {
    max-height: 200px; /* Adjust height as needed */
    overflow-y: auto;  /* Adds vertical scrollbar */
    border: 1px solid #ddd; /* Optional: Add border around list */
    padding: 5px; /* Optional: Add padding inside list */
}

/* Optional: Styling for scrollbars (for Webkit browsers) */
.product-list::-webkit-scrollbar, .category-list::-webkit-scrollbar {
    width: 8px;
}

.product-list::-webkit-scrollbar-thumb, .category-list::-webkit-scrollbar-thumb {
    background: #888; 
    border-radius: 4px;
}

.product-list::-webkit-scrollbar-thumb:hover, .category-list::-webkit-scrollbar-thumb:hover {
    background: #555; 
}

/* Ensure that the list items are displayed properly */
.product-list li, .category-list li {
    padding: 5px;
    border-bottom: 1px solid #ddd; /* Adds a border between items */
}

.product-list li:last-child, .category-list li:last-child {
    border-bottom: none; /* Remove border from last item */
}


    </style>
    <div class="wrap">
        <h1>Add New Discount</h1>
       <form id="discount-form">
		<table class="form-table">
			<tr>
				<th scope="row"><label for="discount_name">Discount name</label><span
						class="dashicon dashicons dashicons-editor-help barn2-help-tip"></span></th>
				<td><input type="text" id="discount_name" name="discount_name"
						class="regular-text components-text-control__input"></td>
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
						<div class="product-selector" id="product-selector" style="display: none;">
							<div class="search-container">
								<label for="product-search">Search for products</label>
								<input type="search" id="product-search" placeholder="Search for products">
							</div>
							<ul id="product-list" class="product-list">
								
								<!-- Add more products as needed -->
							</ul>
							<div class="selected-products">
								<div class="selected-header">
									<strong>Selected products</strong>
									<button type="button" id="clear-products" class="clear-all" style="display: none;">Clear
										all</button>
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
								
								<!-- Add more categories as needed -->
							</ul>
							<div class="selected-categories">
								<div class="selected-header">
									<strong>Selected categories</strong>
									<button type="button" id="clear-categories" class="clear-all"
										style="display: none;">Clear all</button>
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

						<div class="" id="discount-element">
							<div class="" id="percentage-element">
								<span class="">%</span>
								<input class="" type="text" value="0">
								<div class=""></div>
							</div>
							<div class="" id="fixed-discount-element" style="display: none;">
								<span class="">$</span>
								<input class="" type="text" value="0.00">
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
				<th scope="row"></th>
				<td>
					<input type="submit" class="button button-primary" value="Save">
				</td>
			</tr>

		</table>
	</form>
    </div>
    <?php
}
