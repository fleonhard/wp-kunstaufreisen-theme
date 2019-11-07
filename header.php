<?php
/**
 * Copyright (c) 2019 Herborn Software
 *
 * @package kar
 */
$sidebar_active = is_active_sidebar('main_sidebar');
?>

<!DOCTYPE html>
<html <? language_attributes(); ?>>
<head>
    <meta charset="<? bloginfo('charset'); ?>">
    <title><? bloginfo('name');wp_title() ?></title>
    <meta name="description" content="<? bloginfo('description'); ?>">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="profile" href="http://gmpg.org/xfn/11">
    <? if (is_singular() && pings_open(get_queried_object())): ?>
        <link rel="pingback" href="<? bloginfo('pingback_url'); ?>">
    <? endif; ?>
    <? wp_head(); ?>
</head>
<body <? body_class(); ?>>

<? get_template_part('templates/nav', apply_filters('current_header', '')); ?>
<? get_template_part('templates/header', apply_filters('current_header', '')); ?>

<main class="site-content container">
    <div class="row my-4">
        <? if ($sidebar_active): ?>
            <div class="d-none d-lg-block col-lg-4">
                <? get_template_part( 'sidebar', 'main_sidebar' );  ?>
            </div>
        <? endif; ?>
        <div class="col-12 <?= $sidebar_active ? 'col-lg-8' : '' ?>">
