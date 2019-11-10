<?php
/**
 * Copyright (c) 2019 Herborn Software
 *
 * @package kar
 */


defined('ABSPATH') or die("Thanks for visting");

add_action('init', function () {
    $labels = array(
        'name' => _n('Art', 'Arts', 2, 'kar'),
        'singular_name' => _n('Art', 'Arts', 1, 'kar'),
        'menu_name' => __('Arts', 'kar'),
//        'parent_item_colon'   => __( 'Parent Art', 'kar' ),
//        'all_items' => __('All Arts', 'kar'),
//        'view_item' => __('View Art', 'kar'),
//        'add_new_item' => __('Add New Art', 'kar'),
//        'add_new' => __('Add New', 'kar'),
//        'edit_item' => __('Edit Art', 'kar'),
//        'update_item' => __('Update Art', 'kar'),
//        'search_items' => __('Search Art', 'kar'),
//        'not_found' => __('Art Not Found', 'kar'),
//        'not_found_in_trash' => __('Art Not found in Trash', 'kar'),
    );

    register_post_type('art', array(
        'label' => __('arts', 'kar'),
        'description' => '',
        'labels' => $labels,

        // Features this CPT supports in Post Editor
        'supports' => array('title', 'editor', 'excerpt', 'author', 'thumbnail', 'comments'),
        'show_in_rest' => true,
        // You can associate this CPT with a taxonomy or custom taxonomy.
        'taxonomies' => array('art_topic'),
        'rewrite' => array('slug' => 'arts'),

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
        'menu_icon' => 'dashicons-art',
        'can_export' => true,
        'has_archive' => true,
        'exclude_from_search' => false,
        'publicly_queryable' => true,
        'capability_type' => 'post',
    ));
});


add_action('init', function () {
    $labels = array(
        'name' => __('Art Topic', 'kar'),
        'singular_name' => _n('Topic', 'Topics', 1, 'kar'),
        'search_items' => __('Search Topics', 'kar'),
        'all_items' => __('All Topics', 'kar'),
        'parent_item' => __('Parent Topic', 'kar'),
        'edit_item' => __('Edit Topic', 'kar'),
        'update_item' => __('Update Topic', 'kar'),
        'add_new_item' => __('Add New Topic', 'kar'),
        'new_item_name' => __('New Topic', 'kar'),
        'menu_name' => _n('Topic', 'Topics', 2, 'kar'),
    );

    register_taxonomy('art_topic', 'art', array(
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
        'rewrite' => array('slug' => 'topics'),
    ));

    add_editor_for_taxonomy('art_topic');

});




