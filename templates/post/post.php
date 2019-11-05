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
                <a href="<?= get_the_permalink() ?>" class="text-primary"><h5
                            class="card-title article-font"><?= get_the_title() ?></h5></a>
                <p class="card-text article-font"><?= esc_html(get_the_excerpt()) ?></p>
            </div>
        </div>
        <div class="col-12 card-footer text-muted text-center">
            <small class="text-muted"><?= hs_time_ago() ?></small>
            <small class="text-muted"><?= kar_get_post_views() ?></small>
        </div>
    </div>
</div>
