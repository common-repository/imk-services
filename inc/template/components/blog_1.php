<?php
/**
@param: $post
*/

?>

<div class="col-sm-6">
	<div class="imk-post-container">
		<div class="imk-post-image image-loader" style="background-image: url('<?php echo $post->featuredImage; ?>');" > </div>
		<p class="imk-post-date">
            <?php echo date("F, d Y",strtotime($post->createdAt)); ?>
		</p>
		<h2 class="imk-post-title">
			<?php echo $post->title; ?>
		</h2>
		<div class="imk-post-description">
			<?php echo substr(strip_tags($post->body), 0, 150); ?>
		</div>
		
		<div class="">
			<a href="<?php echo get_home_url(). "/blog-detail/". \TBC\IMK\Helper::slugify($post->title) ."-" . $post->_id ?>" class="btn imk-post-btn">Read More</a>
		</div>
	</div>
	
</div>

