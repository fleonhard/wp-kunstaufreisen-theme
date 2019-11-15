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
    <div class="col-lg-4">
        <div class="embed-responsive embed-responsive-1by1">
            <img src="<?php echo get_the_post_thumbnail_url() ?>" class="img-fit embed-responsive-item"
                 alt="<?php echo get_the_post_thumbnail_caption() ?>">
        </div>
    </div>
<?php endif; ?>
<div class="<?php echo has_post_thumbnail() ? 'col-lg-8' : 'col-lg-12' ?>">
    <small><?php _e('Podcast', 'kar') ?></small>
    <h1 class="article-font"><?php the_title() ?></h1>
    <div>
        <table>
            <tr>
                <th><?php echo _n('Episode', 'Episodes', 2, 'kar') ?>:</th>
                <td><?php echo apply_filters('kar_get_episode_count', get_the_ID()) ?></td>
            </tr>
            <tr>
                <th><?php _e('Duraton', 'kar') ?>:</th>
                <td><?php echo apply_filters('kar_get_podcast_duration', get_the_ID()) ?></td>
            </tr>
        </table>
    </div>
    <div class="article-font">
        <?php the_excerpt() ?>
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

        $the_query = apply_filters('kar_get_podcast_episodes');
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