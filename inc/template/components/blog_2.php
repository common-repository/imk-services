<div class="gdlr-core-item-list gdlr-core-blog-full  gdlr-core-item-mglr gdlr-core-style-left">
   <div class="gdlr-core-blog-thumbnail gdlr-core-media-image  gdlr-core-opacity-on-hover gdlr-core-zoom-on-hover">
	   	<a href="<?php echo get_home_url(). "/blog-detail/". \TBC\IMK\Helper::slugify($post->title) ."-" . $post->_id ?>">
	   		<img src="<?php echo $post->featuredImage; ?>" alt="">
	   	</a>
   </div>
   <div class="gdlr-core-blog-full-head clearfix">
      <div class="gdlr-core-blog-full-head-right">
         <h3 class="gdlr-core-blog-title gdlr-core-skin-title">
         	<a href="<?php echo get_home_url(). "/blog-detail/". \TBC\IMK\Helper::slugify($post->title) ."-" . $post->_id ?>">
         		<?php echo $post->title; ?>
         	</a>
         </h3>
         <div class="gdlr-core-blog-info-wrapper gdlr-core-skin-divider">
         	<span class="gdlr-core-blog-info gdlr-core-blog-info-font gdlr-core-skin-caption gdlr-core-blog-info-date">
         		<span class="gdlr-core-head">
         			<i class="icon_clock_alt"></i>
         		</span>
         		<a >
         			<?php echo date("l, d F Y",$post->createdAt); ?>
         		</a>
         	</span>
         	<span class="gdlr-core-blog-info gdlr-core-blog-info-font gdlr-core-skin-caption gdlr-core-blog-info-author">
         		<span class="gdlr-core-head">
         			<i class="icon_documents_alt"></i>
         		</span>
         		<a href="#" title="Posts by admin" rel="author"><?php echo $post->user->profile->firstName; ?></a>
         	</span>
         	<?php if( count( $post->tags ) ){ ?>
         	<span class="gdlr-core-blog-info gdlr-core-blog-info-font gdlr-core-skin-caption gdlr-core-blog-info-category">
         		<span class="gdlr-core-head">
         			<i class="icon_folder-alt"></i>
         		</span>
         		<a href="#" rel="tag"><?php echo implode(", ", $post->tags) ?></a>
         	</span>
         	<?php } ?>
         </div>
      </div>
   </div>
   <div class="gdlr-core-blog-content">
      <?php echo substr(strip_tags($post->body), 0, 150); ?>
      <div class="clear"></div>
      <a class="gdlr-core-excerpt-read-more gdlr-core-button gdlr-core-rectangle" href="<?php echo get_home_url(). "/blog-detail/". \TBC\IMK\Helper::slugify($post->title) ."-" . $post->_id ?>">Read More</a>
   </div>
</div>