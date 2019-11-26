<?php
/**
 * @author Florian Herborn
 * @copyright 2019 Herborn Software
 * @license GPL-2.0-or-later
 *
 * @package kar
 */

$parent = get_post(get_post_meta(get_the_ID(), 'kar_episode_podcast', true));

?>
<div class="col-12 mb-4">
    <div class="card post-podcast">
        <div class="row no-gutters">
            <?php if (has_post_thumbnail($parent)): ?>
                <div class="col-md-2">
                    <div class="embed-responsive embed-responsive-1by1 d-none d-md-block">
                        <img src="<?php echo get_the_post_thumbnail_url($parent) ?>"
                             class="img-fit embed-responsive-item"
                             alt="<?php echo get_the_post_thumbnail_caption($parent) ?>">
                        <a href="<?php echo get_the_permalink() ?>" class="btn img-link"><?php _e("Read More") ?></a>
                    </div>
                    <div class="embed-responsive embed-responsive-21by9 d-md-none">
                        <img src="<?php echo get_the_post_thumbnail_url($parent) ?>"
                             class="img-fit embed-responsive-item"
                             alt="<?php echo get_the_post_thumbnail_caption($parent) ?>">
                        <a href="<?php echo get_the_permalink() ?>" class="btn img-link"><?php _e("Read More") ?></a>
                    </div>
                </div>
            <?php endif; ?>
            <div class="col-12 <?php echo has_post_thumbnail($parent) ? 'col-md-10' : '' ?>">
                <div class="card-body pb-0">
                    <div class="row">
                        <div class="col-12 col-md-6 mb-0 mb-md-2">
                            <?php $podcast = get_post_meta(get_the_ID(), 'kar_episode_podcast', true); ?>
                            <a class="kar-link"
                               href="<?php echo get_the_permalink($podcast) ?>"><small><?php echo get_the_title($podcast) ?>
                                    &#183;</small></a>
                            <small><?php _e('Episode', 'kar') ?>
                                &nbsp<?php echo get_post_meta(get_the_ID(), 'kar_episode_number', true) ?></small>
                            <a href="<?php echo get_the_permalink() ?>" class="kar-text-link">
                                <h5 class="card-title article-font mb-0">
                                    <?php echo get_the_title() ?>
                                </h5>
                            </a>
                        </div>
                        <div class="col-12 text-left col-md-6 text-md-right mb-2">
                            <?php echo kar_get_post_meta() ?>
                        </div>
                        <div class="col-12">
                            <p class="card-text article-font"><?php echo get_the_excerpt() ?></p>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-12 text-muted px-0">
                <?php echo do_shortcode('[audio src="' . get_post_meta(get_the_ID(), 'kar_episode_audio', true) . '"]') ?>
            </div>
            <div class="col-12">
                <div class="card-footer py-1">
                    <div class="row">
                        <div class="col-12 text-center col-md-6 text-md-left">
                            <?php echo kar_get_category_list() ?>
                        </div>
                        <div class="col-12 text-center col-md-6 text-md-right">
                            <?php echo kar_get_post_statistic() ?>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
