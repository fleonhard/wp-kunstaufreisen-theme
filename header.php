<?php
/**
 * Copyright (c) 2019 Herborn Software
 *
 * @package kar
 */
$sidebar_active = is_active_sidebar('main_sidebar');
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

<main class="site-content container py-4">
    <div class="row">
        <?php if ($sidebar_active): ?>
            <div class="d-none d-lg-block col-lg-4">
                <?php get_template_part( 'sidebar', 'main_sidebar' );  ?>
            </div>
        <?php endif; ?>
        <div class="col-12 <?php echo  $sidebar_active ? 'col-lg-8' : '' ?>">
