<?php
/**
 * Copyright (c) 2019 Herborn Software
 *
 * @package kar
 */

?>
<div class="card post-podcast">
    <div class="row no-gutters">
        <?php if (has_post_thumbnail()): ?>
            <div class="col-md-2">
                <div class="embed-responsive embed-responsive-1by1 d-none d-md-block">
                    <img src="<?php echo  get_the_post_thumbnail_url() ?>" class="img-fit embed-responsive-item"
                         alt="<?php echo  get_the_post_thumbnail_caption() ?>">
                    <a href="<?php echo  get_the_permalink() ?>" class="btn img-link"><?php _e("Read More") ?></a>
                </div>
                <div class="embed-responsive embed-responsive-21by9 d-md-none">
                    <img src="<?php echo  get_the_post_thumbnail_url() ?>" class="img-fit embed-responsive-item"
                         alt="<?php echo  get_the_post_thumbnail_caption() ?>">
                    <a href="<?php echo  get_the_permalink() ?>" class="btn img-link"><?php _e("Read More") ?></a>
                </div>
            </div>
        <?php endif; ?>
        <div class="col-12 <?php echo  has_post_thumbnail() ? 'col-md-10' : '' ?>">
            <div class="card-body pb-0">
                <div class="row">
                    <div class="col-12 col-md-6 mb-0 mb-md-2">
                        <div class="text-muted"><?php echo  kar_get_taxonomies('podcast_series') ?></div>
                        <a href="<?php echo  get_the_permalink() ?>" class="kar-text-link">
                            <h5 class="card-title article-font mb-0">
                                <?php echo  get_the_title() ?>
                            </h5>
                        </a>

                    </div>
                    <div class="col-12 text-left col-md-6 text-md-right mb-2">
                        <?php echo  kar_get_post_meta() ?>
                    </div>
                    <div class="col-12">
                        <p class="card-text article-font"><?php echo  get_the_excerpt() ?></p>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-12 text-muted px-0">
            <?php echo do_shortcode(kar_get_audio_as_shortcode(get_the_content())); ?>
        </div>
        <div class="col-12">
            <div class="card-footer py-1">
                <div class="row">
                    <div class="col-12 text-center col-md-6 text-md-left">
                        <?php echo  kar_get_category_list() ?>
                    </div>
                    <div class="col-12 text-center col-md-6 text-md-right">
                        <?php echo  kar_get_post_statistic() ?>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
