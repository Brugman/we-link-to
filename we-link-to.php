<?php

/**
 * Plugin Name: We Link To
 * Description: Find out what we link to.
 * Version: 1.0.0
 * Plugin URI: https://github.com/Brugman/we-link-to
 * Author: Tim Brugman
 * Author URI: https://timbr.dev/
 * Text Domain: we-link-to
 */

if ( !defined( 'ABSPATH' ) )
    exit;

define( 'WLT_SITEURL', get_site_url() );

include 'functions.php';
include 'hooks.php';

