// js/discount.js
document.addEventListener('DOMContentLoaded', function () {
    document.addEventListener('click', function (event) {
        if (event.target && event.target.id === 'add-new-button') {
            event.preventDefault(); // Prevent the default link behavior
            var formContent = document.getElementById('form-placeholder-content').innerHTML;
            var placeholder = document.getElementById('form-placeholder');
            placeholder.innerHTML = formContent;
            placeholder.style.display = 'block';
        }

        if (event.target && event.target.id === 'close-form-button') {
            document.getElementById('form-placeholder').style.display = 'none';
        }
    });
});

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

document.addEventListener('change', function () {
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

    function updateSelectedList(list, selectedList) {
        selectedList.innerHTML = '';
        const checkboxes = list.querySelectorAll('input[type="checkbox"]');
        checkboxes.forEach(checkbox => {
            if (checkbox.checked) {
                const listItem = document.createElement('li');
                listItem.classList.add('selected-item');
                listItem.innerHTML = `
      <span>${checkbox.nextElementSibling.textContent}</span>
      <button type="button" class="remove-item" data-value="${checkbox.value}">×</button>
    `;
                selectedList.appendChild(listItem);
            }
        });
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
            exmpRow.style.display = ''; // Show the row
        } else if (selectedProductsRadio.checked) {
            productSelector.style.display = 'block';
            categorySelector.style.display = 'none';
            exmpRow.style.display = 'none'; // Hide the row
            updateSelectedList(productList, selectedProductsList);
        } else if (selectedCategoriesRadio.checked) {
            productSelector.style.display = 'none';
            categorySelector.style.display = 'block';
            exmpRow.style.display = 'none'; // Hide the row
            updateSelectedList(categoryList, selectedCategoriesList);
        }
    }

    productSearch.addEventListener('input', function () {
        filterItems(productSearch, productList);
    });

    categorySearch.addEventListener('input', function () {
        filterItems(categorySearch, categoryList);
    });

    productList.addEventListener('change', function (event) {
        if (event.target.type === 'checkbox') {
            updateSelectedList(productList, selectedProductsList);
        }
    });

    categoryList.addEventListener('change', function (event) {
        if (event.target.type === 'checkbox') {
            updateSelectedList(categoryList, selectedCategoriesList);
        }
    });

    selectedProductsList.addEventListener('click', function (event) {
        if (event.target.classList.contains('remove-item')) {
            const value = event.target.getAttribute('data-value');
            const checkbox = productList.querySelector(`input[type="checkbox"][value="${value}"]`);
            if (checkbox) {
                checkbox.checked = false;
                updateSelectedList(productList, selectedProductsList);
            }
        }
    });

    selectedCategoriesList.addEventListener('click', function (event) {
        if (event.target.classList.contains('remove-item')) {
            const value = event.target.getAttribute('data-value');
            const checkbox = categoryList.querySelector(`input[type="checkbox"][value="${value}"]`);
            if (checkbox) {
                checkbox.checked = false;
                updateSelectedList(categoryList, selectedCategoriesList);
            }
        }
    });

    clearProductsButton.addEventListener('click', function () {
        const checkboxes = productList.querySelectorAll('input[type="checkbox"]');
        checkboxes.forEach(checkbox => {
            checkbox.checked = false;
        });
        updateSelectedList(productList, selectedProductsList);
    });

    clearCategoriesButton.addEventListener('click', function () {
        const checkboxes = categoryList.querySelectorAll('input[type="checkbox"]');
        checkboxes.forEach(checkbox => {
            checkbox.checked = false;
        });
        updateSelectedList(categoryList, selectedCategoriesList);
    });

    document.querySelectorAll('input[name="which_products"]').forEach(radio => {
        radio.addEventListener('change', function () {
            if (this.value === 'selected_products') {
                productSelector.style.display = 'block';
                categorySelector.style.display = 'none';
                exmpRow.style.display = 'none'; // Hide the row
            } else if (this.value === 'selected_categories') {
                productSelector.style.display = 'none';
                categorySelector.style.display = 'block';
                exmpRow.style.display = 'none'; // Hide the row
            } else {
                productSelector.style.display = 'none';
                categorySelector.style.display = 'none';
                exmpRow.style.display = ''; // Show the row
            }
        });
    });

    // Initialize page state
    initializePage();
});
//exclusion
document.addEventListener('change', function () {
    // Function to filter list items based on search input
    function filterItems(searchInput, list) {
        const searchTerm = searchInput.value.toLowerCase();
        const items = list.querySelectorAll('li');
        items.forEach(item => {
            const label = item.textContent.toLowerCase();
            item.style.display = label.includes(searchTerm) ? '' : 'none';
        });
    }

    // Function to update the selected list and toggle the clear button
    function updateSelectedList(list, selectedList, clearButton) {
        selectedList.innerHTML = ''; // Clear the selected list
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
        clearButton.style.display = hasSelectedItems ? 'block' : 'none'; // Show or hide the button based on selection
    }

    // Function to handle item removal
    function handleItemRemoval(event, list, selectedList, clearButton) {
        if (event.target.classList.contains('remove-item')) {
            const value = event.target.getAttribute('data-value');
            const checkbox = list.querySelector(`input[type="checkbox"][value="${value}"]`);
            if (checkbox) {
                checkbox.checked = false;
                updateSelectedList(list, selectedList, clearButton);
            }
        }
    }

    // Initialize search and clear buttons for products
    const productSearch = document.getElementById('product-search');
    const productList = document.getElementById('product-list');
    const selectedProductsList = document.getElementById('selected-products-list');
    const clearProductsButton = document.getElementById('clear-products');

    productSearch.addEventListener('input', function () {
        filterItems(productSearch, productList);
    });

    productList.addEventListener('change', function () {
        updateSelectedList(productList, selectedProductsList, clearProductsButton);
    });

    selectedProductsList.addEventListener('click', function (event) {
        handleItemRemoval(event, productList, selectedProductsList, clearProductsButton);
    });

    clearProductsButton.addEventListener('click', function () {
        const checkboxes = productList.querySelectorAll('input[type="checkbox"]');
        checkboxes.forEach(checkbox => {
            checkbox.checked = false;
        });
        updateSelectedList(productList, selectedProductsList, clearProductsButton);
    });

    // Initialize search and clear buttons for categories
    const categorySearch = document.getElementById('category-search');
    const categoryList = document.getElementById('category-list');
    const selectedCategoriesList = document.getElementById('selected-categories-list');
    const clearCategoriesButton = document.getElementById('clear-categories');

    categorySearch.addEventListener('input', function () {
        filterItems(categorySearch, categoryList);
    });

    categoryList.addEventListener('change', function () {
        updateSelectedList(categoryList, selectedCategoriesList, clearCategoriesButton);
    });

    selectedCategoriesList.addEventListener('click', function (event) {
        handleItemRemoval(event, categoryList, selectedCategoriesList, clearCategoriesButton);
    });

    clearCategoriesButton.addEventListener('click', function () {
        const checkboxes = categoryList.querySelectorAll('input[type="checkbox"]');
        checkboxes.forEach(checkbox => {
            checkbox.checked = false;
        });
        updateSelectedList(categoryList, selectedCategoriesList, clearCategoriesButton);
    });
});