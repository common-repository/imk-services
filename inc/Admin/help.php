<div class="container-fluid">
	<h1 class="imk-admin-header">IMK Shortcode</h1>
	<h4>Get Blog List</h4>
	<pre>[IMK_blog]</pre>
	<h4>Get Blog List By Category</h4>
	<pre>[IMK_blog category="cate1, cate2"]</pre>
	<h4> Set Limit and skip</h4>
	<pre>[IMK_blog limit=2 skip=3]</pre>

    <?php $path = get_home_path(); ?>

    <h3>API End Points</h3>
    <pre> <b>Domain</b>/wp-json/imk/api/v1/modelList</pre>
    <pre> <b>Domain</b>/wp-json/imk/api/v1/getInventory</pre>
    <pre> <b>Domain</b>/wp-json/dealer-rater/api/v1/reviews</pre>
</div>