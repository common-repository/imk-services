<?php
/**
 * @param $listing
 */

?>

<div class="col-md-4 col-sm-6 col-xs-12">
    <div class="investment-new match-height" >
        <div class="thumbnail">
            <div class="investment-title">
                <h3> <?php echo $listing->title ?> </h3>
            </div>

            <div class="imk-image-container">
                <img src="<?php echo $listing->imageUrl ?>" alt="Four Corners Apartments" class="img-responsive">
                <div class="investment-overlay">
                    <div class="investment-link">
                        <a href="<?php   get_home_url(). "/investment-detail/" . $listing->_id; ?>">
                            See Details
                        </a>
                    </div>
                </div>
            </div>

            <table class="table">
                <tr>
                    <th>
                        Total Equity
                    </th>
                    <th>
                        $<?php echo $listing->totalEquity; ?>
                    </th>
                </tr>

                <tr>
                    <th> Property Type </th>
                    <td> <?php echo $listing->type ?> </td>
                </tr>

                <tr>
                    <th> Total Equity </th>
                    <td> $<?php echo $listing->totalEquity; ?> </td>
                </tr>
                <tr>
                    <th> Location(S) </th>
                    <td> <?php echo $listing->location; ?> </td>
                </tr>
                <tr>
                    <th> First Year Cash-on-Cash </th>
                    <td> $<?php echo $listing->firstYearYield; ?> </td>
                </tr>
                <tr>
                    <th> Loan to Value </th>
                    <td> $<?php echo $listing->loanToValue; ?> </td>
                </tr>

                <tr>
                    <th> Estimated Hold Term </th>
                    <td> $<?php echo $listing->estimatedHoldingPeriod; ?> </td>
                </tr>

            </table>

        </div>
    </div>
</div>


