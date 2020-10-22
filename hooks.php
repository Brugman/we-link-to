<?php

if ( !defined( 'ABSPATH' ) )
    exit;

/**
 * Create Tools menu item.
 */

add_action( 'admin_menu', function () {
    add_management_page(
        'We Link To', // page title
        'We Link To', // menu title
        'manage_options', // capability
        'we-link-to', // menu slug
        'wlt_controller', // function
        null // position
    );
});

