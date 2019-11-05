<?php
/**
 * Copyright (c) 2019 Herborn Software
 *
 * @package hs
 */


function kar_add_overall_scripts() {
    wp_deregister_script('jquery');
    wp_enqueue_script('jquery', get_template_directory_uri() . '/js/libs/jquery.min.js', array(), '3.4.1', true);
    wp_enqueue_script('bootstrap', get_template_directory_uri() . '/js/libs/bootstrap.min.js', array('jquery'), '4.3.0', true);
}
add_action('wp_enqueue_scripts', 'kar_add_overall_scripts');
add_action('admin_enqueue_scripts', 'kar_add_overall_scripts');

function kar_add_frontend_scripts() {

    $theme = wp_get_theme();
    wp_enqueue_script('theme', get_template_directory_uri() . '/js/theme.js', array('jquery'), $theme->get('Version'), true);

    wp_enqueue_style('raleway','https://fonts.googleapis.com/css?family=Raleway:200,300,500');
    wp_enqueue_style('playfair_display','https://fonts.googleapis.com/css?family=Playfair+Display:400,700,900&display=swap');
    wp_enqueue_style('theme', get_template_directory_uri() . '/css/theme.css', array(), $theme->get('Version'), 'all');
}
add_action('wp_enqueue_scripts', 'kar_add_frontend_scripts');

function kar_add_backend_scripts() {
    wp_enqueue_script('admin', get_template_directory_uri() . '/js/admin.js', array('jquery'), '1.0.0', true);
}
add_action('admin_enqueue_scripts', 'kar_add_backend_scripts');
