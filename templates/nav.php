<?php
/**
 * Copyright (c) 2019 Herborn Software
 *
 * @package kar
 */
?>

<div id="wrapper-navbar" class="container" itemscope itemtype="http://schema.org/WebSite">

    <a class="skip-link sr-only sr-only-focusable" href="#content"><?php esc_html_e('Skip to content', 'hs'); ?></a>

    <nav class="navbar navbar-expand-md navbar-light bg-transparent">

        <div class="container">

            <!-- Your site title as branding in the menu -->
            <?php if (!has_custom_logo()) : ?>
            <a  class="navbar-brand"
                rel="home" href="<?php echo esc_url(home_url('/')); ?>"
                title="<?php echo esc_attr(get_bloginfo('name', 'display')); ?>"
                itemprop="url">
                <?php bloginfo('name'); ?>
            </a>


            <?php else:
                the_custom_logo();
            endif; ?><!-- end custom logo -->

            <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarNavDropdown"
                    aria-controls="navbarNavDropdown" aria-expanded="false"
                    aria-label="<?php esc_attr_e('Toggle navigation', 'hs'); ?>">
                <span class="navbar-toggler-icon"></span>
            </button>

            <!-- The WordPress Menu goes here -->
            <?php wp_nav_menu(
                array(
                    'theme_location' => 'primary',
                    'container_class' => 'collapse navbar-collapse',
                    'container_id' => 'navbarNavDropdown',
                    'menu_class' => 'navbar-nav ml-auto',
                    'fallback_cb' => '',
                    'menu_id' => 'main-menu',
                    'depth' => 2,
                    'walker' => new Bootstrap_Navwalker(),
                )
            ); ?>
        </div>

    </nav><!-- .site-navigation -->
</div>
