<?php
/**
 * Copyright (c) 2019 Herborn Software
 *
 * @package kar
 */
$header_image = get_header_image();
?>

<header class="kar-header">
    <div class="overlay"></div>
    <img src="<?= $header_image?>">
    <div class="container h-100">
        <div class="d-flex h-100 text-center align-items-center">
            <div class="w-100 text-white">
                <h1 class="site-title article-font"><?= get_bloginfo('name') ?></h1>
                <p class="site-description mb-0"><?= get_bloginfo('description') ?></p>
            </div>
        </div>
    </div>
</header>
