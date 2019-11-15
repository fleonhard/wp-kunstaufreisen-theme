<?php
/**
 * Copyright (c) 2019 Herborn Software
 *
 * @package kar
 */
?>

<!DOCTYPE html>
<html <?php language_attributes(); ?>>
<head>
    <meta charset="<?php bloginfo('charset'); ?>">
    <title><?php bloginfo('name');
        wp_title() ?></title>
    <meta name="description" content="<?php bloginfo('description'); ?>">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="profile" href="http://gmpg.org/xfn/11">
    <?php if (is_singular() && pings_open(get_queried_object())): ?>
        <link rel="pingback" href="<?php bloginfo('pingback_url'); ?>">
    <?php endif; ?>
    <?php wp_head(); ?>
</head>
<body <?php body_class(); ?> >

<?php get_template_part('templates/nav', apply_filters('current_header', '')); ?>
<?php get_template_part('templates/header', apply_filters('current_header', '')); ?>

<div class="container-fluid">
    <div class="row">
        <?php if (is_active_sidebar('desktop_left')): ?>
            <aside id="secondary" class="d-none d-xl-block py-4 col-3 kar-sidebar kar-sidebar-left" role="complementary">
                <div class="widget-area row px-4">
                    <?php dynamic_sidebar('desktop_left'); ?>
                </div>
            </aside>
        <?php endif; ?>

        <main class="col-12 py-4 site-content col-xl-6 <?php echo is_active_sidebar('desktop_left') ? '' : 'offset-xl-3' ?>">

        <?php if (is_active_sidebar('mobile_top')): ?>
                <aside class="d-xl-none widget-area row kar-sidebar pb-4" role="complementary">
                    <?php dynamic_sidebar('mobile_top'); ?>
                </aside>
        <?php endif; ?>

        <?php if (is_active_sidebar('desktop_top')): ?>
                <aside class="d-none d-xl-block widget-area row kar-sidebar pb-4" role="complementary">
                    <?php dynamic_sidebar('desktop_top'); ?>
                </aside>
        <?php endif; ?>