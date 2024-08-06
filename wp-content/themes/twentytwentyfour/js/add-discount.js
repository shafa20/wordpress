

document.addEventListener('change', function (event) {
    if (event.target.name === 'availability') {
        var dateFields = document.getElementById('date-fields');
        if (document.getElementById('specific_dates').checked) {
            dateFields.style.display = 'block';
        } else {
            dateFields.style.display = 'none';
        }
    }
});





//which products
document.addEventListener('DOMContentLoaded', function () {
    const productSearch = document.getElementById('product-search');
    const productList = document.getElementById('product-list');
    const selectedProductsList = document.getElementById('selected-products-list');
    const clearProductsButton = document.getElementById('clear-products');

    const categorySearch = document.getElementById('category-search');
    const categoryList = document.getElementById('category-list');
    const selectedCategoriesList = document.getElementById('selected-categories-list');
    const clearCategoriesButton = document.getElementById('clear-categories');

    const productSelector = document.getElementById('product-selector');
    const categorySelector = document.getElementById('category-selector');
	const exmpRow = document.getElementById('exmp'); // Ensure the correct ID

    function updateSelectedList(list, selectedList, clearButton) {
        selectedList.innerHTML = '';
        let hasSelectedItems = false;
        const checkboxes = list.querySelectorAll('input[type="checkbox"]');
        checkboxes.forEach(checkbox => {
            if (checkbox.checked) {
                hasSelectedItems = true;
                const listItem = document.createElement('li');
                listItem.classList.add('selected-item');
                listItem.innerHTML = `
                    <span>${checkbox.nextElementSibling.textContent}</span>
                    <button type="button" class="remove-item" data-value="${checkbox.value}">×</button>
                `;
                selectedList.appendChild(listItem);
            }
        });
        clearButton.style.display = hasSelectedItems ? 'inline-block' : 'none';
    }

    function filterItems(searchInput, list) {
        const searchTerm = searchInput.value.toLowerCase();
        const items = list.querySelectorAll('li');
        items.forEach(item => {
            const label = item.textContent.toLowerCase();
            item.style.display = label.includes(searchTerm) ? '' : 'none';
        });
    }

    function initializePage() {
        const allProductsRadio = document.querySelector('input[name="which_products"][value="all_products"]');
        const selectedProductsRadio = document.querySelector('input[name="which_products"][value="selected_products"]');
        const selectedCategoriesRadio = document.querySelector('input[name="which_products"][value="selected_categories"]');

        if (allProductsRadio.checked) {
            productSelector.style.display = 'none';
            categorySelector.style.display = 'none';
        } else if (selectedProductsRadio.checked) {
            productSelector.style.display = 'block';
            categorySelector.style.display = 'none';
			exmpRow.style.display = 'none'; // Hide the row
            fetchProducts();
        } else if (selectedCategoriesRadio.checked) {
            productSelector.style.display = 'none';
            categorySelector.style.display = 'block';
			exmpRow.style.display = 'none'; // Hide the row
            fetchCategories();
        }
    }

    function fetchProducts() {
        fetch(discounts_params.ajax_url + '?action=fetch_products')
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    const products = data.data;
                    productList.innerHTML = products.map(product => `
                        <li>
                            <input type="checkbox" id="product-${product.id}" value="${product.name}">
                            <label for="product-${product.id}">${product.name}</label>
                        </li>
                    `).join('');
                    updateSelectedList(productList, selectedProductsList, clearProductsButton);
                }
            });
    }

    function fetchCategories() {
        fetch(discounts_params.ajax_url + '?action=fetch_categories')
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    const categories = data.data;
                    categoryList.innerHTML = categories.map(category => `
                        <li>
                            <input type="checkbox" id="category-${category.id}" value="${category.name}">
                            <label for="category-${category.id}">${category.name}</label>
                        </li>
                    `).join('');
                    updateSelectedList(categoryList, selectedCategoriesList, clearCategoriesButton);
                }
            });
    }

    productSearch.addEventListener('input', function () {
        filterItems(productSearch, productList);
    });

    categorySearch.addEventListener('input', function () {
        filterItems(categorySearch, categoryList);
    });

    productList.addEventListener('change', function (event) {
        if (event.target.type === 'checkbox') {
            updateSelectedList(productList, selectedProductsList, clearProductsButton);
        }
    });

    categoryList.addEventListener('change', function (event) {
        if (event.target.type === 'checkbox') {
            updateSelectedList(categoryList, selectedCategoriesList, clearCategoriesButton);
        }
    });

    selectedProductsList.addEventListener('click', function (event) {
        if (event.target.classList.contains('remove-item')) {
            const value = event.target.getAttribute('data-value');
            const checkbox = productList.querySelector(`input[type="checkbox"][value="${value}"]`);
            if (checkbox) {
                checkbox.checked = false;
                updateSelectedList(productList, selectedProductsList, clearProductsButton);
            }
        }
    });

    selectedCategoriesList.addEventListener('click', function (event) {
        if (event.target.classList.contains('remove-item')) {
            const value = event.target.getAttribute('data-value');
            const checkbox = categoryList.querySelector(`input[type="checkbox"][value="${value}"]`);
            if (checkbox) {
                checkbox.checked = false;
                updateSelectedList(categoryList, selectedCategoriesList, clearCategoriesButton);
            }
        }
    });

    clearProductsButton.addEventListener('click', function () {
        const checkboxes = productList.querySelectorAll('input[type="checkbox"]');
        checkboxes.forEach(checkbox => {
            checkbox.checked = false;
        });
        updateSelectedList(productList, selectedProductsList, clearProductsButton);
    });

    clearCategoriesButton.addEventListener('click', function () {
        const checkboxes = categoryList.querySelectorAll('input[type="checkbox"]');
        checkboxes.forEach(checkbox => {
            checkbox.checked = false;
        });
        updateSelectedList(categoryList, selectedCategoriesList, clearCategoriesButton);
    });

    document.querySelectorAll('input[name="which_products"]').forEach(radio => {
        radio.addEventListener('change', function () {
            if (this.value === 'selected_products') {
                productSelector.style.display = 'block';
                categorySelector.style.display = 'none';
                fetchProducts();
            } else if (this.value === 'selected_categories') {
                productSelector.style.display = 'none';
                categorySelector.style.display = 'block';
                fetchCategories();
            } else {
                productSelector.style.display = 'none';
                categorySelector.style.display = 'none';
            }
        });
    });

    // Initialize page state
    initializePage();
});


//discount
document.addEventListener('change', function () {
    // Function to toggle the visibility of the discount elements
    function toggleDiscountElements() {
        const percentageElement = document.getElementById('percentage-element');
        const fixedDiscountElement = document.getElementById('fixed-discount-element');
        const percentageRadio = document.querySelector('input[name="discount_type"][value="percentage_discount"]');
        const fixedRadio = document.querySelector('input[name="discount_type"][value="fixed_discount"]');

        if (percentageRadio.checked) {
            percentageElement.style.display = 'block';
            fixedDiscountElement.style.display = 'none';
        } else if (fixedRadio.checked) {
            percentageElement.style.display = 'none';
            fixedDiscountElement.style.display = 'block';
        }
    }

    // Initialize the visibility on page load
    toggleDiscountElements();

    // Add event listeners to radio buttons
    const discountRadios = document.querySelectorAll('input[name="discount_type"]');
    discountRadios.forEach(radio => {
        radio.addEventListener('change', toggleDiscountElements);
    });
});
