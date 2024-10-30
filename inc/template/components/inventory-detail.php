<?php
/**
 * The IMK template file
 */
    get_header();
    $carsDetail = $imk_data;

    $CarImages = [];

    $imageCount = 0;

    if(isset($carsDetail->images) && $carsDetail->images->images && count( $carsDetail->images->images )  ){
        $imageCount = count($carsDetail->images->images);
        if( $carsDetail->images->images[0]->{'url'} ){
            $CarImages = $carsDetail->images->images;
        }
    }


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

            <div class="single-inventory-page inventory-dark">
                <div id="wrapper">
                    <div class="wpb_wrapper">
                        <div class="stm-single-car-page">
                            <div class="container">
                                <div class="row">
                                    <div class="col-sm-9  ">
                                        <div class="stm-listing-single-price-title heading-font clearfix">
                                            <div class="price">
                                                <?php echo '$'.number_format($carsDetail->price); ?>
                                            </div>
                                            <h1 class="title">
                                                <?php echo $carsDetail->title ." " . $carsDetail->vehicleType;; ?>
                                            </h1>
                                        </div>

                                        <div class="IMK-detail-section form-group">
                                            <h4 class="item-title text-muted">
                                                VIN: <?php echo $carsDetail->VIN ?>
                                                <span class="pull-right">

                                                    <?php echo $carsDetail->exteriorColor ?> <?php  echo isset($carsDetail->interiorColor) ? "/ ". $carsDetail->interiorColor : '' ?>
                                                </span>
                                            </h4>
                                            <hr>

                                        </div>

                                        <div class="stm-car-carousels stm-listing-car-gallery">
                                            <div class="stm-gallery-actions">
                                                <div class="stm-gallery-action-unit stm-listing-print-action">
                                                    <a href="javascript:window.print()" class="car-action-unit stm-car-print heading-font">
                                                        <i class="fa fa-print"></i>
                                                    </a>
                                                </div>
                                            </div>
                                            <?php if( $imageCount ): ?>
                                            <div class="stm-car-medias">
                                                <div class="stm-listing-videos-unit">
                                                    <i class="stm-service-icon-photo"></i>
                                                    <span><?php echo $imageCount; ?> Images</span>
                                                </div>
                                            </div>
                                            <?php endif; ?>

                                            <!-- <div class="special-label h5"></div> -->

                                            <div class="stm-big-car-gallery owl-theme owl-carousel">

                                                <?php if( count( $CarImages ) ): ?>
                                                    <?php foreach ( $CarImages as $gallery_image ): ?>
                                                        <div class="stm-single-image" data-id="big-image-<?php echo esc_attr($carsDetail->_id); ?>">
                                                            <img src="<?php echo $gallery_image->url; ?>" alt="<?php $carsDetail->make.' '.$carsDetail->model.' '.$carsDetail->vehicleType.' '.$carsDetail->year; ?>"/>
                                                        </div>
                                                    <?php endforeach; ?>
                                                <?php
                                                else:
                                                    echo "<img src='".$this->plugin_url."images/car-placeholder.jpg' />";
                                                endif; ?>

                                            </div>

                                            <?php if(isset($carsDetail->images->images)): ?>
                                                <div class="stm-thumbs-car-gallery">
                                                    <?php foreach ( $carsDetail->images->images as $gallery_image ): ?>
                                                        <div class="stm-single-image" id="big-image-<?php echo esc_attr($carsDetail->_id); ?>">
                                                            <img src="<?php echo $gallery_image->url; ?>" alt="<?php $car->make.' '.$car->model.' '.$car->vehicleType.' '.$car->year; ?>"/>
                                                        </div>
                                                    <?php endforeach; ?>
                                                </div>
                                            <?php endif; ?>
                                        </div>

                                        <div class="car-data">
                                            <ul class="nav nav-tabs">
                                                <li class="active"><a data-toggle="tab" href="#home">Description</a></li>
                                                <li><a data-toggle="tab" href="#menu1">Overview</a></li>
                                                <li><a data-toggle="tab" href="#menu2">Features & Options</a></li>
<!--                                                <li><a data-toggle="tab" href="#menu3">Location</a></li>-->
<!--                                                <li><a data-toggle="tab" href="#menu4">Contact</a></li>-->
                                            </ul>

                                            <div class="tab-content">
                                                <div id="home" class="tab-pane fade in active">
                                                    <p>
                                                        <?php echo $carsDetail->description; ?>
                                                    </p>
                                                </div>

                                                <div id="menu1" class="tab-pane fade">
                                                    <ul class="feature-info">
                                                        <?php if($carsDetail->mileage): ?>
                                                            <li>
				                              <span>
				                                  <b>Miles: </b><?php echo number_format($carsDetail->mileage, 0); ?>
				                              </span>
                                                            </li>
                                                        <?php endif; ?>

                                                        <?php if($carsDetail->exteriorColor): ?>
                                                            <li>
				                              <span>
				                                  <b>Exterior Color: </b><?php echo $carsDetail->exteriorColor; ?>
				                              </span>
                                                            </li>
                                                        <?php endif; ?>

                                                        <?php if($carsDetail->interiorColor): ?>
                                                            <li>
				                              <span>
				                                  <b>Interior Color: </b><?php echo isset($carsDetail->interiorColor) ? $carsDetail->interiorColor : ''; ?>
				                              </span>
                                                            </li>
                                                        <?php endif; ?>

                                                        <?php if($carsDetail->transmission): ?>
                                                            <li>
				                              <span>
				                                  <b>Transmission: </b><?php echo $carsDetail->transmission; ?>
				                              </span>
                                                            </li>
                                                        <?php endif; ?>

                                                        <?php if($carsDetail->VIN): ?>
                                                            <li>
				                              <span>
				                                  <b>VIN: </b><?php echo $carsDetail->VIN; ?>
				                              </span>
                                                            </li>
                                                        <?php endif; ?>

                                                        <?php if($carsDetail->fuelType): ?>
                                                            <li>
				                              <span>
				                                  <b>Fuel Type: </b><?php echo $carsDetail->fuelType; ?>
				                              </span>
                                                            </li>
                                                        <?php endif; ?>

                                                        <?php if($carsDetail->stockNumber): ?>
                                                            <li>
				                              <span>
				                                  <b>Stock Number: </b><?php echo $carsDetail->stockNumber; ?>
				                              </span>
                                                            </li>
                                                        <?php endif; ?>

                                                        <?php if($carsDetail->trim): ?>
                                                            <li>
				                              <span>
				                                  <b>Trim: </b><?php echo $carsDetail->trim; ?>
				                              </span>
                                                            </li>
                                                        <?php endif; ?>

                                                        <?php if($carsDetail->transmission): ?>
                                                            <li>
				                              <span>
				                                  <b>Transmission: </b><?php echo $carsDetail->transmission; ?>
				                              </span>
                                                            </li>
                                                        <?php endif; ?>

                                                        <?php if($carsDetail->driveTrain): ?>
                                                            <li>
				                              <span>
				                                  <b>Drive Train: </b><?php echo $carsDetail->driveTrain; ?>
				                              </span>
                                                            </li>
                                                        <?php endif; ?>

                                                        <?php if($carsDetail->driveTrain): ?>
                                                            <li>
				                              <span>
				                                  <b>Drive Train: </b><?php echo $carsDetail->driveTrain; ?>
				                              </span>
                                                            </li>
                                                        <?php endif; ?>
                                                    </ul>
                                                </div>

                                                <div id="menu2" class="tab-pane fade">
                                                    <p>
                                                    <div class="options">
                                                        <div class="row">
                                                            <?php if(count($carsDetail->options)): ?>
                                                                <?php foreach ( $carsDetail->options as $option): ?>
                                                                    <?php if(count($option->list)): ?>
                                                                        <div class="col-sm-6 match-height">
                                                                            <h3 class="other-title"><?php echo $option->category; ?></h3>
                                                                            <ul class="feature-info">
                                                                                <?php foreach( $option->list as $list): ?>
                                                                                    <li><?php echo $list; ?></li>
                                                                                <?php endforeach; ?>
                                                                            </ul>
                                                                        </div>
                                                                    <?php endif; ?>
                                                                <?php endforeach; ?>
                                                            <?php endif; ?>
                                                        </div>

                                                        <div class="row">
                                                            <hr>
                                                            <?php if(isset($carsDetail->standardEquipments) && count($carsDetail->standardEquipments)): ?>
                                                                <?php foreach ( $carsDetail->standardEquipments as $option): ?>
                                                                    <?php if(count($option->options)): ?>
                                                                        <div class="col-sm-6 match-height">
                                                                            <h3 class="other-title"><?php echo $option->category; ?></h3>
                                                                            <ul class="feature-info">
                                                                                <?php  foreach ( $option->options as $list): ?>
                                                                                    <li><?php echo $list; ?></li>
                                                                                <?php endforeach; ?>
                                                                            </ul>
                                                                        </div>
                                                                    <?php endif; ?>
                                                                <?php endforeach; ?>
                                                            <?php endif; ?>

                                                            <?php  if(isset($carsDetail->additionalOptionalEquipments) && count($carsDetail->additionalOptionalEquipments)): ?>
                                                                <?php foreach ( $carsDetail->additionalOptionalEquipments as $option): ?>
                                                                    <?php if(count($option->options)): ?>
                                                                        <div class="col-sm-6 match-height">
                                                                            <h3 class="other-title"><?php echo $option->category; ?></h3>
                                                                            <ul class="feature-info">
                                                                                <?php foreach ( $option->options as $list): ?>
                                                                                    <li><?php echo $list; ?></li>
                                                                                <?php endforeach; ?>
                                                                            </ul>
                                                                        </div>
                                                                    <?php endif; ?>
                                                                <?php endforeach; ?>
                                                            <?php endif; ?>
                                                        </div>
                                                    </div>
                                                    </p>
                                                </div>

<!--                                                <div id="menu3" class="tab-pane fade">-->
<!--                                                    <p>-->
<!--                                                        <iframe width="100%" height="350" src="https://maps.google.com/maps?width=100%&height=350&hl=en&q=2466%20Covington%20Pike%2C%20Memphis%2C%20TN%2038128+(eautouniverse)&ie=UTF8&t=&z=14&iwloc=B&output=embed" frameborder="0" scrolling="no" marginheight="0" marginwidth="0">-->
<!--                                                        </iframe>-->
<!--                                                    </p>-->
<!--                                                </div>-->
<!--                                                <div id="menu4" class="tab-pane fade">-->
<!--                                                    <p>-->
<!--                                                        <i class="fa fa-map-marker"></i> 2466 Covington Pike, Memphis, TN 38128<br>-->
<!--                                                        <i class="fa fa-phone"></i> (901) 300-4172<br>-->
<!--                                                        <i class="fa fa-envelope"></i> info@eautouniverse.com-->
<!--                                                    </p>-->
<!--                                                </div>-->
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-sm-3  classic-filter-row sidebar-sm-mg-bt ">
                                        <a  href="<?php echo get_permalink( 3882 ); ?>?VIN=<?php echo $carsDetail->VIN ?>&inventory_title=<?php echo $carsDetail->title.'-'.$carsDetail->_id ?>" target="_blank">
                                            <button class="stm-slider-lm-btn heading-font btn-block form-group btn-skyplue">Schedule a Test-drive</button>
                                        </a>

                                        <a  href="<?php echo get_permalink( 3885 ); ?>?VIN=<?php echo $carsDetail->VIN ?>" target="_blank">
                                            <button class="stm-slider-lm-btn heading-font form-group btn-block btn-skyplue">Get Pre-approved</button>
                                        </a>

                                        <a  href="<?php echo get_permalink( 3962 ); ?>?VIN=<?php echo $carsDetail->VIN ?>"  target="_blank">
                                            <button class="stm-slider-lm-btn heading-font form-group btn-block btn-skyplue">Trade Appraisal</button>
                                        </a>

                                        <a href="<?php echo get_home_url(). '/contact-us'; ?>" target="_blank">
                                            <button class="stm-slider-lm-btn heading-font form-group btn-block btn-skyplue" >Contact Us</button>
                                        </a>

                                       <center> <?php echo do_shortcode('[DISPLAY_ULTIMATE_SOCIAL_ICONS]'); ?></center>

                                    </div>
                                </div>
                            </div>
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
