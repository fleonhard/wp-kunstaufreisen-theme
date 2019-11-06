<?php
/**
 * Copyright (c) 2019 Herborn Software
 *
 * @package hs
 */


function kar_add_frontend_scripts() {

    $theme = wp_get_theme();
    wp_enqueue_script('app', get_template_directory_uri() . '/public/js/app.js', array('jquery'), $theme->get('Version'), true);

    wp_enqueue_style('raleway','https://fonts.googleapis.com/css?family=Raleway:200,300,500');
    wp_enqueue_style('playfair_display','https://fonts.googleapis.com/css?family=Playfair+Display:400,700,900&display=swap');

    wp_enqueue_style('app', get_template_directory_uri() . '/public/css/app.css', array(), $theme->get('Version'), 'all');
}
add_action('wp_enqueue_scripts', 'kar_add_frontend_scripts');
