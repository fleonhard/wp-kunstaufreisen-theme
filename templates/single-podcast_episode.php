<?php
/**
 * @author Florian Herborn
 * @copyright 2019 Herborn Software
 * @license GPL-2.0-or-later
 *
 * @package kar
 */
?>
    <div class="col-lg-12">
        <small><?php echo get_the_title(get_post_meta(get_the_ID(), 'kar_episode_podcast', true)) ?></small>
        <div class="d-flex w-100 justify-content-between">
            <small><?php _e('Episode', 'kar') ?>
                &nbsp<?php echo get_post_meta(get_the_ID(), 'kar_episode_number', true) ?></small>
            <small><?php echo gmdate("H:i:s", get_post_meta(get_the_ID(), 'kar_episode_audio_duration', true)) ?></small>
        </div>
        <h1 class="article-font"><?php the_title() ?></h1>
    </div>
<?php if (have_posts()): ?>
    <?php while (have_posts()): the_post(); ?>
        <div <?php post_class('col-12 article-font'); ?>>
            <?php the_content(); ?>
        </div>
    <?php endwhile; ?>
<?php endif; ?>
<div class="col-12">
    <?php echo do_shortcode('[audio src="' . get_post_meta(get_the_ID(), 'kar_episode_audio', true) . '"]') ?>
</div>
