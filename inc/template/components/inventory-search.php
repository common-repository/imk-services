<?php
/**
 * The IMK template file
 */
get_header();
$carsDetail = $imk_data;
//    print_r( json_encode( $carsDetail ) );
?>

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
            <div class="">
                <?php if( $imk_data && $imk_data->featuredImage ): ?>
                    <div class="imk-post-image-container">
                        <img src="<?php echo $imk_data->featuredImage  ?>">
                    </div>
                <?php endif; ?>
                <?php the_content(); ?>

                <?php echo do_shortcode('[IMK_inventory limit=12 with=search_bar]') ?>

            </div>

        </div>

    <?php  endwhile;
endif;
?>


<?php get_footer();
