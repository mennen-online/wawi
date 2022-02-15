<?php

return [
    'common' => [
        'actions' => 'Actions',
        'create' => 'Create',
        'edit' => 'Edit',
        'update' => 'Update',
        'new' => 'New',
        'cancel' => 'Cancel',
        'save' => 'Save',
        'delete' => 'Delete',
        'delete_selected' => 'Delete selected',
        'search' => 'Search...',
        'back' => 'Back to Index',
        'are_you_sure' => 'Are you sure?',
        'no_items_found' => 'No items found',
        'created' => 'Successfully created',
        'saved' => 'Saved successfully',
        'removed' => 'Successfully removed',
    ],

    'users' => [
        'name' => 'Users',
        'index_title' => 'Users List',
        'new_title' => 'New User',
        'create_title' => 'Create User',
        'edit_title' => 'Edit User',
        'show_title' => 'Show User',
        'inputs' => [
            'name' => 'Name',
            'email' => 'Email',
            'password' => 'Password',
        ],
    ],

    'vendors' => [
        'name' => 'Vendors',
        'index_title' => 'Vendors List',
        'new_title' => 'New Vendor',
        'create_title' => 'Create Vendor',
        'edit_title' => 'Edit Vendor',
        'show_title' => 'Show Vendor',
        'inputs' => [
            'company' => 'Company',
            'email' => 'Email',
            'phone' => 'Phone',
            'salutation' => 'Salutation',
            'first_name' => 'First Name',
            'last_name' => 'Last Name',
        ],
    ],

    'products' => [
        'name' => 'Products',
        'index_title' => 'Products List',
        'new_title' => 'New Product',
        'create_title' => 'Create Product',
        'edit_title' => 'Edit Product',
        'show_title' => 'Show Product',
        'inputs' => [
            'name' => 'Name',
            'description' => 'Description',
            'image_url' => 'Image Url',
        ],
    ],

    'vendor_products' => [
        'name' => 'Vendor Products',
        'index_title' => 'VendorProducts List',
        'new_title' => 'New Vendor product',
        'create_title' => 'Create VendorProduct',
        'edit_title' => 'Edit VendorProduct',
        'show_title' => 'Show VendorProduct',
        'inputs' => [
            'vendor_id' => 'Vendor',
            'product_id' => 'Product',
            'price' => 'Price',
            'available' => 'Available',
        ],
    ],
];
