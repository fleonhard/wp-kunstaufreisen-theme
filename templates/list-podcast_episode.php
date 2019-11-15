<?php
/**
 * @author Florian Herborn
 * @copyright 2019 Herborn Software
 * @license GPL-2.0-or-later
 *
 * @package kar
 */
?>

<div class="post-podcast mb-1">
    <a href="<?php echo get_the_permalink() ?>"
       class="list-group-item list-group-item-action bg-dark flex-column align-items-start mt-2">
        <div class="d-flex w-100 justify-content-between">
            <!--        <small>-->
            <?php //echo get_the_title(get_post_meta(get_the_ID(), 'kar_episode_podcast', true)) ?><!--</small>-->
            <small><?php _e('Episode', 'kar') ?><?php echo get_post_meta(get_the_ID(), 'kar_episode_number', true) ?></small>
            <small><?php echo gmdate("H:i:s", get_post_meta(get_the_ID(), 'kar_episode_audio_duration', true)) ?></small>
        </div>
        <div class="d-flex w-100 justify-content-between">
            <h6 class="mb-1 article-font"><?php the_title() ?></h6>
        </div>
        <div class="article-font">
            <?php echo wp_trim_words(get_the_excerpt(), 20); ?>
        </div>
    </a>
    <?php echo do_shortcode('[audio src="' . get_post_meta(get_the_ID(), 'kar_episode_audio', true) . '"]') ?>
</div>