<?php
/**
 * @param $imk_data;
 */

get_header(); ?>
<?php if (have_posts()) :
    while (have_posts()) : the_post();

        ?>
        <div class="entry-header small_title_box" >
            <div class="container">
                <div class="entry-title">
                    <h2 class="h1" >
                        <?php echo the_title() ?>
                    </h2>

                </div>
            </div>
        </div>
        <div class="container ">
            <div class="section-pad">
                <?php if( $imk_data && $imk_data->featuredImage ): ?>
                    <div class="imk-post-image-container">
                        <img src="<?php echo $imk_data->featuredImage  ?>">
                    </div>
                <?php endif; ?>
                <?php

                the_content();
                print_r($imk_data);
                ?>

            </div>

        </div>

    <?php  endwhile;
endif;
get_footer();