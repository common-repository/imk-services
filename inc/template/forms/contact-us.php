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
        <div class="imk-section">
            <div class="container ">
                <div class="section-pad">
                    <div class="row">
                        <div class="col-sm-6">
                            <div class="imk-contact-form ">
                                <h2> Stay Connected </h2>
                                <p>Stay in touch with us and get updated with new happenings.</p>
                                <hr>
                                <?php echo do_shortcode('[IMK_contact_form]') ?>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    <?php  endwhile;
endif;
?>

<?php get_footer();
