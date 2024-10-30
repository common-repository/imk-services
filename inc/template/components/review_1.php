<?php
/*
  * @param $review
  * */
?>

<div class="col-lg-3 col-md-4 col-sm-6">
    <div class="review-block match-height">
        <div class="review-author">
            <div class="review-author-image">
                <img src="<?php echo $this->plugin_url ?>images/icon-user-placeholder.png" alt="" class="btn-block">
            </div>
            <div class="review-rating-outer">
                <span class="review-rating">
                    <?php echo $review->ratings->overallExperience; ?>
                    <span class="review-rating-icon"></span>
                </span>
            </div>
            <div class="review-author-name">
                <h5>
                    <?php echo $review->user->username ?>
                    <small class="text-muted"><?php echo $review->visitReason ?></small>
                </h5>

            </div>

        </div>
        <div class="imk-review-title">
            <?php echo $review->title ?>
        </div>
        <div class="imk-review-comment">
            <?php echo \TBC\IMK\Helper::limit_words($review->comments, 30); ?>
        </div>
    </div>
</div>
