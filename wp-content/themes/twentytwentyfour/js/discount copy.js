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
            exmpRow.style.display = ''; // Show the row
        } else if (selectedProductsRadio.checked) {
            productSelector.style.display = 'block';
            categorySelector.style.display = 'none';
            exmpRow.style.display = 'none'; // Hide the row
            updateSelectedList(productList, selectedProductsList, clearProductsButton);
        } else if (selectedCategoriesRadio.checked) {
            productSelector.style.display = 'none';
            categorySelector.style.display = 'block';
            exmpRow.style.display = 'none'; // Hide the row
            updateSelectedList(categoryList, selectedCategoriesList, clearCategoriesButton);
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
                exmpRow.style.display = 'none'; // Hide the row
                updateSelectedList(productList, selectedProductsList, clearProductsButton);
            } else if (this.value === 'selected_categories') {
                productSelector.style.display = 'none';
                categorySelector.style.display = 'block';
                exmpRow.style.display = 'none'; // Hide the row
                updateSelectedList(categoryList, selectedCategoriesList, clearCategoriesButton);
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

//user and role
document.addEventListener('change', function () {
    // Function to handle the visibility of selectors based on selected radio button
    function handleAppliesToChange() {
        const selectedAppliesTo = document.querySelector('input[name="applies_to"]:checked').value;
        document.getElementById('role-selector').style.display = selectedAppliesTo === 'selected_roles' ? 'block' : 'none';
        document.getElementById('user-selector').style.display = selectedAppliesTo === 'selected_users' ? 'block' : 'none';
        // Update the visibility of clear buttons based on current selections
        updateClearButtonVisibility();
    }

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
        // Show or hide the button based on selection
        clearButton.style.display = hasSelectedItems ? 'block' : 'none'; 
        updateClearButtonVisibility(); // Ensure clear button visibility is updated
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

    // Function to update the visibility of clear buttons
    function updateClearButtonVisibility() {
        const selectedRolesList = document.getElementById('selected-roles-list');
        const clearRolesButton = document.getElementById('clear-roles');
        const selectedUsersList = document.getElementById('selected-users-list');
        const clearUsersButton = document.getElementById('clear-users');

        const hasSelectedRoles = selectedRolesList.children.length > 0;
        const hasSelectedUsers = selectedUsersList.children.length > 0;

        clearRolesButton.style.display = hasSelectedRoles ? 'block' : 'none';
        clearUsersButton.style.display = hasSelectedUsers ? 'block' : 'none';
    }

    // Event listener for radio buttons
    document.querySelectorAll('input[name="applies_to"]').forEach(radio => {
        radio.addEventListener('change', handleAppliesToChange);
    });

    // Initialize search and clear buttons for roles
    const roleSearch = document.getElementById('role-search');
    const roleList = document.getElementById('role-list');
    const selectedRolesList = document.getElementById('selected-roles-list');
    const clearRolesButton = document.getElementById('clear-roles');

    roleSearch.addEventListener('input', function () {
        filterItems(roleSearch, roleList);
    });

    roleList.addEventListener('change', function () {
        updateSelectedList(roleList, selectedRolesList, clearRolesButton);
    });

    selectedRolesList.addEventListener('click', function (event) {
        handleItemRemoval(event, roleList, selectedRolesList, clearRolesButton);
    });

    clearRolesButton.addEventListener('click', function () {
        const checkboxes = roleList.querySelectorAll('input[type="checkbox"]');
        checkboxes.forEach(checkbox => {
            checkbox.checked = false;
        });
        updateSelectedList(roleList, selectedRolesList, clearRolesButton);
    });

    // Initialize search and clear buttons for users
    const userSearch = document.getElementById('user-search');
    const userList = document.getElementById('user-list');
    const selectedUsersList = document.getElementById('selected-users-list');
    const clearUsersButton = document.getElementById('clear-users');

    userSearch.addEventListener('input', function () {
        filterItems(userSearch, userList);
    });

    userList.addEventListener('change', function () {
        updateSelectedList(userList, selectedUsersList, clearUsersButton);
    });

    selectedUsersList.addEventListener('click', function (event) {
        handleItemRemoval(event, userList, selectedUsersList, clearUsersButton);
    });

    clearUsersButton.addEventListener('click', function () {
        const checkboxes = userList.querySelectorAll('input[type="checkbox"]');
        checkboxes.forEach(checkbox => {
            checkbox.checked = false;
        });
        updateSelectedList(userList, selectedUsersList, clearUsersButton);
    });
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
    function updateSelectedList(list, selectedList, clearButton, container) {
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
        // Show or hide the container and clear button based on selection
        container.style.display = hasSelectedItems ? 'block' : 'none';
        clearButton.style.display = hasSelectedItems ? 'block' : 'none';
    }

    // Function to handle item removal
    function handleItemRemoval(event, list, selectedList, clearButton, container) {
        if (event.target.classList.contains('remove-item')) {
            const value = event.target.getAttribute('data-value');
            const checkbox = list.querySelector(`input[type="checkbox"][value="${value}"]`);
            if (checkbox) {
                checkbox.checked = false;
                updateSelectedList(list, selectedList, clearButton, container);
            }
        }
    }

    // Product Search Section
    const productSearch = document.getElementById('exclusions-product-search');
    const productList = document.getElementById('exclusions-product-list');
    const selectedProductsList = document.getElementById('exclusions-selected-products-list');
    const clearProductsButton = document.getElementById('exclusions-clear-products');
    const selectedProductsContainer = document.querySelector('.exclusions-selected-products');

    productSearch.addEventListener('input', function () {
        filterItems(productSearch, productList);
    });

    productList.addEventListener('change', function () {
        updateSelectedList(productList, selectedProductsList, clearProductsButton, selectedProductsContainer);
    });

    selectedProductsList.addEventListener('click', function (event) {
        handleItemRemoval(event, productList, selectedProductsList, clearProductsButton, selectedProductsContainer);
    });

    clearProductsButton.addEventListener('click', function () {
        const checkboxes = productList.querySelectorAll('input[type="checkbox"]');
        checkboxes.forEach(checkbox => {
            checkbox.checked = false;
        });
        updateSelectedList(productList, selectedProductsList, clearProductsButton, selectedProductsContainer);
    });

    // Category Search Section
    const categorySearch = document.getElementById('exclusions-category-search');
    const categoryList = document.getElementById('exclusions-category-list');
    const selectedCategoriesList = document.getElementById('exclusions-selected-categories-list');
    const clearCategoriesButton = document.getElementById('exclusions-clear-categories');
    const selectedCategoriesContainer = document.querySelector('.exclusions-selected-categories');

    categorySearch.addEventListener('input', function () {
        filterItems(categorySearch, categoryList);
    });

    categoryList.addEventListener('change', function () {
        updateSelectedList(categoryList, selectedCategoriesList, clearCategoriesButton, selectedCategoriesContainer);
    });

    selectedCategoriesList.addEventListener('click', function (event) {
        handleItemRemoval(event, categoryList, selectedCategoriesList, clearCategoriesButton, selectedCategoriesContainer);
    });

    clearCategoriesButton.addEventListener('click', function () {
        const checkboxes = categoryList.querySelectorAll('input[type="checkbox"]');
        checkboxes.forEach(checkbox => {
            checkbox.checked = false;
        });
        updateSelectedList(categoryList, selectedCategoriesList, clearCategoriesButton, selectedCategoriesContainer);
    });
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
