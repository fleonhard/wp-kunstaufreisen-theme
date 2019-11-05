<?php
/**
 * Copyright (c) 2019 Herborn Software
 *
 * @package kar
 */

/**
 * The template for displaying 404 pages (not found).
 *
 * @package hs
 */

// Exit if accessed directly.
defined( 'ABSPATH' ) || exit;

get_header();

$container = get_theme_mod( 'hs_container_type' );
?>

<div class="container">
    <h1>404 Not found!</h1>
</div>

<?php get_footer();
