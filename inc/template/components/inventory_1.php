<?php
/**
@param: $inventory
*/

$title = $inventory->year. " ". $inventory->make. " " .$inventory->model;
$price = 0;
$specialPrice = 0;

if( $inventory->otherPrice && ($inventory->otherPrice!=0 && $inventory->otherPrice != $inventory->price) ){
    $price = '$'.number_format($inventory->otherPrice);
    $specialPrice = '$'.number_format($inventory->price);
} else {
    $price  = '$'.number_format($inventory->price);
}
$CarImage = $this->plugin_url . "images/car-placeholder.jpg";

$imageCount = 0;

if(isset($inventory->images) && $inventory->images->images && count( $inventory->images->images )  ){
    $imageCount = count($inventory->images->images);
    if( $inventory->images->images[0]->{'url'} ){
        $CarImage = $inventory->images->images[0]->{'url'};
    }
}

?>
	<div class="col-sm-6 col-md-4">
		<div class="imk-listing-car-item-inner match-height imk-single-post-for-count">
			<a href="<?php echo get_home_url(). "/inventory-detail/". \TBC\IMK\Helper::slugify($title) ."-" . $inventory->_id ?>" class="rmv_txt_drctn" title='<?php echo $inventory->year. " ". $inventory->make. " " .$inventory->model ." ". $inventory->vehicleType  ?>'>
				<div class="text-center inventory-image-container">
                    <div class="imk-inventory-image">
						<img src="<?php echo $CarImage; ?>" data-retina="<?php echo $CarImage; ?>" class="img-responsive">
					</div>
                    <div class="image-counter">
                        <img src="<?php echo $this->plugin_url?>images/photo-camera.png" alt="total images" width="18px"><b> <?php echo $imageCount ?></b>
                    </div>
                    <?php if( $specialPrice ) { ?>
                        <div class="special-price">
                            Offer  <b> <?php echo $specialPrice; ?></b>
                        </div>
                    <?php } ?>
				</div>
				<div class="imk-listing-car-item-meta">
					<div class="imk-car-meta-top">
						<div class="inventory-price">
							<div class="imk-normal-price <?php if( $specialPrice ) { echo 'line-through'; }?> "> <?php echo $price ?></div>
						</div>
						<div class="imk-car-title"> <?php echo $title  ?> </div>

					</div>
					<div class="imk-car-meta-bottom form-group">
						<ul>

							<li title="Inventory ID">
								<b >#</b>
								<span><?php echo $inventory->stockNumber ?></span>
							</li>
                            <li title="Mileage">
                                <b>Mileage</b>
                                <span> <?php echo $inventory->mileage ?></span>
                            </li>
							<li title="VIN">
								<b>VIN</b>
								<span><?php echo $inventory->VIN ?></span>
							</li>
                            <li title="Exterior Color">
                                <b>Ext Color</b>
                                <span><?php echo $inventory->exteriorColor ?></span>
                            </li>
                            <li title="Interior Color">
                                <b>Int Color</b>
                                <span><?php echo isset($inventory->interiorColor) ? $inventory->interiorColor : '' ?></span>
                            </li>
						</ul>
					</div>
                    <div class="third-party-services">
                        <?php if( $inventory->VIN ): ?>
                        <a href="https://www.carfax.com/cfm/ccc_DisplayHistoryRpt.cfm?vin=<?php echo $inventory->VIN ?>" target="_blank" class="carfax service-container">
                            <img src="<?php echo $this->plugin_url?>images/download.png" />
                        </a>
                        <?php endif ?>
                    </div>
				</div>
			</a>
		</div>
	</div>
