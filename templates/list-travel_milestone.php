<?php
/**
 * @author Florian Herborn
 * @copyright 2019 Herborn Software
 * @license GPL-2.0-or-later
 *
 * @package kar
 */
?>


<div class="card milestone-card card-primary card-inverse">
    <?php if (has_post_thumbnail()): ?>
        <div class="card-img-top">
            <div class="embed-responsive embed-responsive-21by9">
                <img src="<?php echo get_the_post_thumbnail_url() ?>" class="img-fit embed-responsive-item"
                     alt="<?php echo get_the_post_thumbnail_caption() ?>">
            </div>
        </div>
    <?php endif; ?>
    <div class="card-body">
        <small class="d-flex w-100">
            <?php _e('Day', 'kar') ?>
            <?php echo '&nbsp' . apply_filters('kar_get_milestone_day', get_the_ID()) ?>
        </small>
        <small class="d-flex w-100">
            <?php echo apply_filters('kar_get_milestone_date', get_the_ID()) ?>
        </small>
        <small class="d-flex w-100"><?php echo apply_filters('kar_get_milestone_location', get_the_ID()) ?></small>
        <div class="d-flex w-100 justify-content-between">
            <h3 class="mb-1 article-font"><?php the_title() ?></h3>
        </div>
        <div class="article-font">
            <?php
            echo do_blocks(get_the_content());
            ?>
        </div>
    </div>

    <!--    <div class="d-flex w-100 card-footer justify-content-end">-->
    <!--        --><?php //echo kar_get_post_statistic() ?>
    <!--    </div>-->

</div>
