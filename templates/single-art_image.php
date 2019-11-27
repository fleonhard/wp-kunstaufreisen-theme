<?php
/**
 * @author Florian Herborn
 * @copyright 2019 Herborn Software
 * @license GPL-2.0-or-later
 *
 * @package kar
 */
?>
<?php if (has_post_thumbnail()): ?>
    <div class="col-12 col-lg-6 mb-4">
        <a href="<?php echo get_the_post_thumbnail_url() ?>">
            <img src="<?php echo get_the_post_thumbnail_url() ?>"
                 alt="<?php echo get_the_post_thumbnail_caption() ?>" class="w-100"
            <!--data-img="#image-preview"-->>
        </a>
    </div>
<?php endif; ?>

<div class="mb-4 <?php echo has_post_thumbnail() ? 'col-lg-6' : 'col-lg-12' ?>">
    <small><?php echo apply_filters('kar_get_image_gallery_link', get_the_ID()); ?></small>
    <h1 class="article-font"><?php the_title() ?></h1>
    <div>
        <table>
            <tr>
                <th><?php _e('Size', 'kar') ?>:</th>
                <td><?php echo apply_filters('kar_get_image_size', get_the_ID()); ?></td>
            </tr>
            <tr>
                <th><?php _e('Style', 'kar') ?>:</th>
                <td><?php echo apply_filters('kar_get_image_materials', get_the_ID()); ?></td>
            </tr>
            <tr>
                <th><?php _e('Support Material', 'kar') ?>:</th>
                <td><?php echo apply_filters('kar_get_image_support_medium', get_the_ID()); ?></td>
            </tr>
        </table>
    </div>
</div>
<?php if (have_posts()): ?>
    <?php while (have_posts()): the_post(); ?>
        <div <?php post_class('col-12 article-font'); ?>>
            <?php the_content(); ?>
        </div>
    <?php endwhile; ?>
<?php endif; ?>
<div class="col-lg-12">
    <div class="list-group">
        <?php

        $the_query = apply_filters('kar_get_podcast_episodes', get_the_ID());
        if ($the_query->have_posts()) {
            while ($the_query->have_posts()) {
                $the_query->the_post();
                do_action('kar_get_template', 'list');
            }
        }
        wp_reset_postdata();
        ?>
    </div>
</div>