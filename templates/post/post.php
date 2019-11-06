<?php
/**
 * Copyright (c) 2019 Herborn Software
 *
 * @package kar
 */
?>
<div class="card post-card">
    <div class="row no-gutters">
        <? if (has_post_thumbnail()): ?>
            <div class="col-12">
                <div class="embed-responsive embed-responsive-16by9">
                    <img src="<?= get_the_post_thumbnail_url() ?>" class="img-fit embed-responsive-item"
                         alt="<?= get_the_post_thumbnail_caption() ?>">
                    <a href="<?= get_the_permalink() ?>" class="btn img-link"><? _e("Read More") ?></a>
                </div>
            </div>
        <? endif; ?>
        <div class="col-12">
            <div class="card-body">
                <div class="row">
                    <div class="col-12 col-md-6 mb-0 mb-md-2">
                        <?= kar_get_category_list() ?>
                        <a href="<?= get_the_permalink() ?>" class="kar-text-link"><h5 class="card-title article-font mb-0"><?= get_the_title() ?></h5></a>
                    </div>
                    <div class="col-12 text-left col-md-6 text-md-right mb-2">
                        <?= kar_get_post_meta() ?>
                    </div>
                    <div class="col-12">
                        <p class="card-text article-font"><?= get_the_excerpt() ?></p>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-12">
            <div class="card-footer py-1">
                <div class="row">
                    <div class="col-12 text-center col-md-6 text-md-left">
                        <?= kar_get_tag_list() ?>
                    </div>
                    <div class="col-12 text-center col-md-6 text-md-right">
                        <?= kar_get_post_statistic() ?>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
