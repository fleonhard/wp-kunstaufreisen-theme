<?
/**
 * Copyright (c) 2019 Herborn Software
 *
 * @package kar
 */
$sidebar_active = is_active_sidebar('main_sidebar');
get_header("index"); ?>
    <!--        --><?// get_template_part('theme-test'); ?>
    <main class="site-content container">
        <div class="row mt-4">
            <div class="col-12 <?= $sidebar_active ? 'd-md-none' : '' ?>">
                <? get_template_part('templates/post-loop') ?>
            </div>

            <? if ($sidebar_active): ?>
                <div class="col-12 col-md-4 mb-4">
                    <? get_sidebar('main_sidebar'); ?>
                </div>
            <? endif; ?>

            <div class="col-12 <?= $sidebar_active ? 'col-md-8 d-none d-md-block' : '' ?>">
                <? get_template_part('templates/post-loop') ?>
            </div>
        </div>
    </main>


<? get_footer("index");
