<div class="imk-inventory-filter">
    <form action="<?php echo get_home_url(). "/inventory-search";  ?>" method="get">
        <div class="row">


            <div class="col-sm-3">
                <div class="form-group">
                    <select name="year" id="" class="form-control">
                        <option value="">Any Year</option>
                        <?php
                            $cur_year = date('Y');
                            for ($i=0; $i<=20; $i++) {

                                $selected = "";
                                if( isset($_GET['year']) && $_GET['year'] == $cur_year ){
                                    $selected = "selected";
                                }

                                echo "<option $selected>". $cur_year ."</option>";
                                $cur_year--;
                             }
                        ?>
                    </select>
                </div>
            </div>

            <div class="col-sm-3">
                <div class="form-group">
                    <select name="makeName" id="" data-container="#modelListContainer" class="form-control on-chnage-get-models">
                        <option value="">Any Make</option>
                        <?php do_action('make_list') ?>
                    </select>
                </div>
            </div>

            <div class="col-sm-3">
                <div class="form-group">
                    <select name="model" id="modelListContainer" disabled class="form-control">
                        <option value="">Any Model</option>
                    </select>
                </div>
            </div>

            <div class="col-sm-3">
                <div class="form-group">
                    <label for="" class="text-muted pad-b-10">Price Range <span id="showPrice"></span> </label>
                    <div id="price-slider" data-show-price="#showPrice" ></div>
                </div>
            </div>

            <div class="col-sm-12">
                <div class="form-group">
                    <div class="input-group">
                        <input type="text" placeholder="Search inventory by Inventory number, Stock number, VIN and colors..." name="keyword" class="form-control" value="<?php echo (isset($_GET['keyword']) && $_GET['keyword'] ) ? $_GET['keyword'] : '' ?>" >
                        <span class="input-group-btn">
                        <button class="btn btn-primary" > <i class="fa fa-search"></i> &nbsp;Search Inventory</button>
                      </span>
                    </div><!-- /input-group -->


                </div>
            </div>

        </div>
    </form>
</div>