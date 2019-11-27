<?php
/**
 * Copyright (c) 2019 Herborn Software
 *
 * @package kar
 */
?>
<footer class="page-footer container-fluid bg-dark">
    <div class="row">
        <div class="col-12 d-flex justify-content-center align-items-center
">
            <?php $theme = wp_get_theme();
            echo $theme->Name . '&nbsp;' . $theme->Version . '&nbsp;&nbsp;' . '&copy;' . '&nbsp;' . 'Copyright' . '&nbsp;' . date("Y") . '&nbsp;' . '<a href="https://herborn-software.com" class="kar-link">Herborn-Software</a>';
            ?>
        </div>
    </div>
</footer>