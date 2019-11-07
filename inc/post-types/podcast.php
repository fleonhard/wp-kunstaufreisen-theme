<?php
/**
 * Copyright (c) 2019 Herborn Software
 *
 * @package kar
 */


defined('ABSPATH') or die("Thanks for visting");

add_action('init', function () {
    $labels = array(
        'name' => _n('Podcast', 'Podcasts', 2, 'kar'),
        'singular_name' => _n('Podcast', 'Podcasts', 1, 'kar'),
        'menu_name' => __('Podcasts', 'kar'),
//        'parent_item_colon'   => __( 'Parent Podcast', 'kar' ),
        'all_items' => __('All Podcasts', 'kar'),
        'view_item' => __('View Podcast', 'kar'),
        'add_new_item' => __('Add New Podcast', 'kar'),
        'add_new' => __('Add New', 'kar'),
        'edit_item' => __('Edit Podcast', 'kar'),
        'update_item' => __('Update Podcast', 'kar'),
        'search_items' => __('Search Podcast', 'kar'),
        'not_found' => __('Podcast Not Found', 'kar'),
        'not_found_in_trash' => __('Podcast Not found in Trash', 'kar'),
    );

    register_post_type('podcast', array(
        'label' => __('podcasts', 'kar'),
        'description' => __('Podcasts with Description and Audio', 'kar'),
        'labels' => $labels,

        // Features this CPT supports in Post Editor
        'supports' => array('title', 'editor', 'excerpt', 'author', 'thumbnail', 'comments'),
        'show_in_rest' => true,
        // You can associate this CPT with a taxonomy or custom taxonomy.
        'taxonomies' => array('series'),
        'rewrite' => array('slug' => 'podcasts'),

        /* A hierarchical CPT is like Pages and can have
        * Parent and child items. A non-hierarchical CPT
        * is like Posts.
        */
        'hierarchical' => false,
        'public' => true,
        'show_ui' => true,
        'show_in_menu' => true,
        'show_in_nav_menus' => true,
        'show_in_admin_bar' => true,
        'menu_position' => 5,
        'menu_icon' => 'dashicons-format-audio',
        'can_export' => true,
        'has_archive' => true,
        'exclude_from_search' => false,
        'publicly_queryable' => true,
        'capability_type' => 'post',
    ));
});


add_action('init', function () {
    $labels = array(
        'name' => __('Podcast Series', 'kar'),
        'singular_name' => _n('Series', 'Series', 1, 'kar'),
        'search_items' => __('Search Series', 'kar'),
        'all_items' => __('All Series', 'kar'),
        'parent_item' => __('Parent Series', 'kar'),
        'edit_item' => __('Edit Series', 'kar'),
        'update_item' => __('Update Series', 'kar'),
        'add_new_item' => __('Add New Series', 'kar'),
        'new_item_name' => __('New Series', 'kar'),
        'menu_name' => _n('Series', 'Series', 2, 'kar'),
    );

    register_taxonomy('podcast_series', 'podcast', array(
        'hierarchical' => true,
        'show_in_rest' => true,
        'public' => true,
        'show_ui' => true,
        'show_in_menu' => true,
        'show_in_nav_menus' => true,
        'show_in_admin_bar' => true,
        'labels' => $labels,
        'publicly_queryable' => true,
        'has_archive' => true,
        'show_admin_column' => true,
        'query_var' => true,
        'rewrite' => array('slug' => 'series'),
    ));

    add_editor_for_taxonomy('podcast_series');

});




