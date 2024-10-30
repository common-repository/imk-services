<form id="imk_trade_appraisal" method="post">
    <div class="imk-appraisal-message hide alert"></div>

    <?php
        if( isset($_GET['VIN']) ){
            echo '<input type="hidden" class="form-control" value="'.$_GET['VIN'] .'"  name="VIN">';
        }
    ?>

    <div class="row">
        <div class="col-sm-6">
            <div class="form-group">
                <label for="">First Name<sup class="text-danger">*</sup></label>
                <input type="text" class="form-control" data-validation="required" name="firstName">
            </div>
        </div>
        <div class="col-sm-6">
            <div class="form-group">
                <label for="">Last Name<sup class="text-danger">*</sup></label>
                <input type="text" class="form-control" data-validation="required" name="lastName">
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col-sm-6">
            <div class="form-group">
                <label for="">Email<sup class="text-danger">*</sup></label>
                <input type="text" class="form-control" data-validation="email" name="emailId">
            </div>
        </div>
        <div class="col-sm-6">
            <div class="form-group">
                <label for="">Phone<sup class="text-danger">*</sup></label>
                <input type="text" class="form-control" data-validation="required" name="phone">
            </div>
        </div>
    </div>

    <br>
    <h4>Vehicle details</h4>
    <div class="form-group">
        <label for="">Year<sup class="text-danger">*</sup></label>
        <select date-validation="required" name="year" id="" class="form-control">
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

    <div class="form-group">
        <label for="">Make<sup class="text-danger">*</sup></label>
        <input type="text" class="form-control" data-validation="required" name="make">
    </div>

    <div class="form-group">
        <label for="">Model<sup class="text-danger">*</sup></label>
        <input type="text" class="form-control" data-validation="required" name="model">
    </div>

    <div class="form-group">
        <label for="">Mileage<sup class="text-danger">*</sup></label>
        <input type="text" class="form-control" data-validation="required" required name="Mileage">
    </div>


    <div class="form-group">
        <label for="">Additional Comments<sup class="text-danger">*</sup></label>
        <textarea rows="4" class="form-control bg-gray" data-validation="required" name="additional_comments"></textarea>
    </div>

    <button type="submit" class="btn btn-primary">Send</button>
</form>