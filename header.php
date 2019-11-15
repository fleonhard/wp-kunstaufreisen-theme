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
        <?php if (is_active_sidebar('sidebar_left')): ?>
            <aside id="secondary" class="col-12 py-4 col-xl-3 kar-sidebar" role="complementary">
                <div class="widget-area row px-xl-4">
                    <?php dynamic_sidebar('sidebar_left'); ?>
                </div>
            </aside>
        <?php endif; ?>

        <main class="col-12 py-4 site-content col-xl-6 <?php echo is_active_sidebar('sidebar_right') ? '' : 'offset-xl-3' ?>">