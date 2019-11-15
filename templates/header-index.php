<?php
/**
 * Copyright (c) 2019 Herborn Software
 *
 * @package kar
 */

$header_image = get_header_image();
?>

<header class="kar-header header-index">
    <div class="overlay"></div>
    <img src="<?php echo $header_image ?>">
    <div class="container h-100">
        <div class="d-flex h-100 text-center align-items-center">
            <div class="w-100 text-white">
                <h1 class="display-3 site-title article-font"><?php echo get_bloginfo('name') ?></h1>
                <p class="lead site-description mb-0"><?php echo get_bloginfo('description') ?></p>
            </div>
        </div>
    </div>
</header>


