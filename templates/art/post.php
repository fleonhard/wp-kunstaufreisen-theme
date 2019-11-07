<?php
/**
 * Copyright (c) 2019 Herborn Software
 *
 * @package kar
 */
?>
<div class="col-4 mb-4">
    <div class="card post-card">
        <div class="row no-gutters">
            <?php if (has_post_thumbnail()): ?>
                <div class="col-12">
                    <div class="embed-responsive embed-responsive-1by1">
                        <img src="<?php echo get_the_post_thumbnail_url() ?>" class="img-fit embed-responsive-item"
                             alt="<?php echo get_the_post_thumbnail_caption() ?>">
                        <a href="<?php echo get_the_permalink() ?>" class="btn img-link"><?php _e("Read More") ?></a>
                    </div>
                </div>
            <?php endif; ?>
            <div class="col-12">
                <div class="card-body">
                    <div class="row">
                        <div class="col-12 col-md-6 mb-0 mb-md-2">
                            <?php echo kar_get_category_list() ?>
                            <a href="<?php echo get_the_permalink() ?>" class="kar-text-link"><h5
                                        class="card-title article-font mb-0"><?php echo get_the_title() ?></h5></a>
                        </div>
<!--                        <div class="col-12 text-left col-md-6 text-md-right mb-2">-->
<!--                            --><?php //echo kar_get_post_meta() ?>
<!--                        </div>-->
                        <div class="col-12">
                            <p class="card-text article-font"><?php echo get_the_excerpt() ?></p>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-12">
                <div class="card-footer py-1">
                    <div class="row">
                        <div class="col-12 text-center col-md-6 text-md-left">
                            <?php echo kar_get_tag_list() ?>
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
