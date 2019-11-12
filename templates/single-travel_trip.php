<?php
/**
 * @author Florian Herborn
 * @copyright 2019 Herborn Software
 * @license GPL-2.0-or-later
 *
 * @package kar
 */

?>
<div class="col-12 text-center mb-4">
    <h1 class="article-font"><?php the_title() ?></h1>
</div>
<?php if (have_posts()): ?>
    <?php while (have_posts()): the_post(); ?>
        <div <?php post_class('col-12 article-font'); ?>>
            <?php the_content(); ?>
        </div>
    <?php endwhile; ?>
<?php endif; ?>
<div class="col-12 mb-4 milestone-map-container">
    <div id="milestone_map">
        <button id="show_all_btn" class="btn btn-primary"><?php _e('Show All', 'kar') ?></button>
    </div>
</div>
<div class="col-lg-12" id="milestones">
    <div id="milestone_container" class="row">
        <?php
        $milestones = apply_filters('kar_get_trip_milestones', get_the_ID());
        if ($milestones->have_posts()) {
            while ($milestones->have_posts()) {
                $milestones->the_post();
                echo '<div class="col-12 mb-4">';
                do_action('kar_add_milestone_meta');
                do_action('kar_get_template', 'list');
                echo '</div>';
            }
        }
        wp_reset_postdata();
        ?>
    </div>
</div>

