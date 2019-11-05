<?php
/**
 * Copyright (c) 2019 Herborn Software
 *
 * @package kar
 */

?>
<div class="col-12 col-md-6 mt-4">
    <div class="card">
        <div class="row no-gutters">
            <? if (has_post_thumbnail()): ?>
            <div class="col-md-4">
                <div class="embed-responsive embed-responsive-1by1">
                    <img src="<?= get_the_post_thumbnail_url() ?>" class="img-fit embed-responsive-item"
                         alt="<?= get_the_post_thumbnail_caption() ?>">
                    <a href="<?= get_the_permalink() ?>" class="btn img-link"><? _e("Read More") ?></a>
                </div>
            </div>
            <div class="col-md-8">

                <? else: ?>
                <div class="col-md-12">
                    <? endif; ?>
                    <div class="card-body">
                        <a href="<?= get_the_permalink() ?>" class="text-primary"><h5
                                    class="card-title article-font"><?= get_the_title() ?></h5></a>
                        <p class="card-text article-font"><?= get_the_excerpt() ?></p>
                        <p class="card-text"><small class="text-muted"><?= hs_time_ago() ?></small></p>
                    </div>
                </div>
            </div>
        </div>
    </div>
