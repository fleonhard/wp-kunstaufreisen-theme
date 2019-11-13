<?php
/**
 * @author Florian Herborn
 * @copyright 2019 Herborn Software
 * @license GPL-2.0-or-later
 *
 * @package kar
 */
?>
<div class="col-12 mb-4">
    <div class="card post-card">

        <?php if (has_post_thumbnail()): ?>
            <div class="card-img-top">
                <div class="embed-responsive embed-responsive-21by9">
                    <img src="<?php echo get_the_post_thumbnail_url() ?>" class="img-fit embed-responsive-item"
                         alt="<?php echo get_the_post_thumbnail_caption() ?>">
                    <a href="<?php echo apply_filters('kar_get_milestone_link', get_the_ID()) ?>"
                       class="btn img-link"><?php _e("Read More") ?></a>
                </div>
            </div>
        <?php endif; ?>
        <div class="card-body">
            <div class="d-flex w-100 justify-content-between">
                <small>
                    <?php echo apply_filters('kar_get_milestone_trip_link', get_the_ID()). ' &#183 ' ?>
                    <?php _e('Day', 'kar') ?>
                    <?php echo ' ' . apply_filters('kar_get_milestone_day', get_the_ID()) ?>
                </small>
                <?php echo kar_get_post_meta() ?>
            </div>
            <small class="d-flex w-100">
                <?php echo apply_filters('kar_get_milestone_location', get_the_ID()) ?>
            </small>
            <div class="d-flex w-100">
                <a href="<?php echo apply_filters('kar_get_milestone_link', get_the_ID()) ?>" class="kar-text-link">
                    <h5 class="card-title article-font mb-0"><?php echo get_the_title() ?></h5>
                </a>
            </div>
            <p class="card-text article-font w-100 mt-2"><?php echo get_the_excerpt() ?></p>
        </div>
        <div class="card-footer py-1 d-flex justify-content-between">
            <div><?php echo kar_get_tag_list() ?></div>
            <div><?php echo kar_get_post_statistic() ?></div>
        </div>
    </div>
</div>
