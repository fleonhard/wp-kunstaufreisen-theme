<?php
/**
 *  Copyright (c) 2019 Herborn Software
 *
 * @package kar
 */
?>


<article <?php post_class('col-12 article-font'); ?>>
    <div class="row">
        <?php if (has_post_thumbnail()): ?>
            <div class="col-lg-4 mb-4">
                <div class="embed-responsive embed-responsive-1by1">
                    <img src="<?php echo get_the_post_thumbnail_url() ?>" class="img-fit embed-responsive-item"
                         alt="<?php echo get_the_post_thumbnail_caption() ?>" style="background-color: red">
                </div>
            </div>
        <?php endif; ?>
        <div class="mb-4 <?php echo has_post_thumbnail() ? 'col-lg-8' : 'col-lg-12' ?>">
            <?php echo get_the_excerpt() ?>
        </div>

        <div class="col-12 mb-4">
            <?php the_content(); ?>
        </div>

        <div class="col-12 mb-4">
            <h4 class="article-font">Folgen:</h4>
        </div>
        <div class="col-12">
            <table class="table table-borderless table-dark">
                <thead>
                <tr>
                    <th scope="col">#</th>
                    <th scope="col">First</th>
                    <th scope="col">Last</th>
                    <th scope="col">Handle</th>
                </tr>
                </thead>
                <tbody>
                <?php
                global $post;

                $args = array(
                    'post_parent' => $post->ID,
                    'posts_per_page' => -1,
                    'post_type' => 'lesson', //you can use also 'any'
                );

                $the_query = new WP_Query($args);
                // The Loop
                if ($the_query->have_posts()) :
                    while ($the_query->have_posts()) : $the_query->the_post(); ?>
                        <tr>
                            <th scope="row">Folge 1</th>
                            <td>Titel der Folge</td>
                            <td>Untertiteld er Folge csaldjnv asg ga a fgaklf mlajngf</td>
                            <td>Untertiteld er Folge csaldjnv asg ga a fgaklf mlajngf</td>
                            <td>@mdo</td>
                        </tr>
                        <tr>
                            <th scope="row"></th>
                            <td colspan="4">Hier kommt der Player hin</td>
                        </tr>
                    <? endwhile;
                endif;
                // Reset Post Data
                wp_reset_postdata();
                ?>
                </tbody>
            </table>
        </div>
    </div>

</article>
