<?php
/*
  * @package IMKServicePlugin
  * */
namespace TBC\IMK;
use TBC\IMK\IMKAPI;
use TBC\IMK\Helper;
use TBC\IMK\Controller;
use TBC\IMK\TemplateLoader;
class Frontend extends IMKAPI{
    function __construct(){
        parent::__construct();
        //add_action('init', [ $this, 'IMK_plugin_add_rewrite_rule' ]);
        //add_filter( 'generate_rewrite_rules', [ $this, 'IMK_generate_rewrite_rules_IMK' ]);
        //add_action('query_vars',[ $this, 'IMK_plugin_set_query_var' ]);
        //add_filter('template_include', [ $this, 'IMK_plugin_include_template' ]);
    }

    function register(){
        //shortcodes
        add_action("make_list", array( $this, 'getIMKUIMakeList') );
        add_shortcode( 'IMK_blog', array( $this, 'getIMKBlogs' ) );
        add_shortcode( 'IMK_inventory_filters', array( $this, 'getIMKInventoryFilters' ) );
        add_shortcode( 'IMK_featured_inventory', array( $this, 'getIMKFeaturedInventory' ) );
        add_shortcode( 'IMK_inventory', array( $this, 'getIMKInventory' ) );
        add_shortcode( 'IMK_properties', array( $this, 'getIMKInvestment' ) );
        add_shortcode( 'IMK_featured_investment', array( $this, 'getIMKFeaturedInvestment' ) );

        // DealerReview
        add_shortcode( 'IMK_reviews', array( $this, 'getReviewUI' ) );

        //pets
        add_shortcode( 'IMK_petlist', array( $this, 'renderPetUI' ) );

        $controller = new Controller ( new TemplateLoader );

        add_action( 'init', array( $controller, 'init' ) );

        add_filter( 'do_parse_request', array( $controller, 'dispatch' ), PHP_INT_MAX, 2 );

        add_action( 'loop_end', function( \WP_Query $query ) {
            if ( isset( $query->virtual_page ) && ! empty( $query->virtual_page ) ) {
                $query->virtual_page = NULL;
            }
        } );

        add_filter( 'the_permalink', function( $plink ) {
            global $post, $wp_query;
            if (
                $wp_query->is_page
                && isset( $wp_query->virtual_page )
                && $wp_query->virtual_page instanceof Page
                && isset( $post->is_virtual )
                && $post->is_virtual
            ) {
                $plink = home_url( $wp_query->virtual_page->getUrl() );
            }
            return $plink;
        } );

        add_action("wp_enqueue_scripts", array( $this  , 'init_assets' ) );

        add_action( 'gm_virtual_pages', array($this, 'setupVitualPages'));
    }

    public function init_assets(  ){
        wp_enqueue_style( 'imk-boostrap',  $this->plugin_url . 'admin/css/bootstrap.min.css',  false,  $this->version );
        wp_enqueue_style( 'imk-style1',  $this->plugin_url . 'css/style.css',  false,  $this->version );
        wp_enqueue_script('imk-form-validator', $this->plugin_url. 'scripts/jquery.form-validator.min.js', ['jquery'], $this->version, true);
        wp_enqueue_script('imk-match-height', $this->plugin_url. 'scripts/jquery.matchHeight-min.js', ['jquery'], $this->version, true);
        wp_enqueue_script('imk-script-frontend', $this->plugin_url . 'scripts/imk-frontend.js', ['jquery', 'imk-form-validator', 'imk-match-height'] , $this->version, true);


        $this->imkData = $this->get_active_user_detail($this->APP_IMK);

        $IMK_param = [
            "IMK_API_URL" => $this->imkData->api_url,
            "IMK_USER_ID" => __($this->imkData->user_id),
            "IMK_GROUP_ID" => __($this->imkData->group_id),
            "IMK_PLUGIN_URL" => __($this->plugin_url),
            "IMK_PLUGIN_VERSION" => __($this->version),
            "WEBSITE_API" => home_url() . "/wp-json/imk/api/v1",
            "HOME_URL" => home_url()
        ];

        wp_localize_script( 'imk-script-frontend' , 'IMK_OBJECT', $IMK_param );
    }

    function setupVitualPages ( $controller ) {
        // first page
        $controller->addPage( new \TBC\IMK\Page( "blog-detail/:postId" ) )
            ->setMethod([$this, 'getSingleBlog'])
            ->setTemplate( $this->plugin_path . 'inc/template/components/blog-detail.php' );

        //cars-detail
        $controller->addPage( new \TBC\IMK\Page( "inventory-detail/:investorId" ) )
            ->setMethod([$this, 'getInventoryDetail'])
            ->setTemplate( $this->plugin_path. 'inc/template/components/inventory-detail.php' );

        //cars-search
        $controller->addPage( new \TBC\IMK\Page( "inventory-search" ) )
            ->setContent("")
            ->setTitle("Inventory Search")
            ->setTemplate( $this->plugin_path. 'inc/template/components/inventory-search.php' );

        //property detail
        $controller->addPage( new \TBC\IMK\Page( "investment-detail/:investorId" ) )
            ->setTitle("Investment Detail")
            ->setMethod([$this, 'investmentDetail'])
            ->setTemplate( $this->plugin_path. 'inc/template/components/property-detail.php' );

        //conatct
        $controller->addPage( new \TBC\IMK\Page( "contact-us" ) )
            ->setTitle("Contact Us")
            ->setContent("Contact Form")
            ->setTemplate( $this->plugin_path. 'inc/template/forms/contact-us.php' );

        //Pet Detail Page
        $controller->addPage( new \TBC\IMK\Page( "pet-detail/:petId" ) )
            ->setMethod([$this, 'getPetDetail'])
            ->setTemplate( $this->plugin_path. 'inc/template/components/pet-detail.php' );

    }

    function IMK_plugin_add_rewrite_rule( $t ){
        add_rewrite_rule('^mynewpage$','index.php?blog_slug=12','top');
        //flush the rewrite rules, should be in a plugin activation hook, i.e only run once...
        flush_rewrite_rules();
    }

    function IMK_generate_rewrite_rules_IMK($wp_rewrite  ){
        echo " generate_rewrite_rules ";
        $wp_rewrite->rules = array_merge(
            ['blog-detail/([\d\w-]+)/?$' => 'index.php?blog_slug=$matches[1]'],
            $wp_rewrite->rules
        );

        $wp_rewrite->rules = array_merge(
            ['property-detail/([\d\w-]+)/?$' => 'index.php?property_id=$matches[1]'],
            $wp_rewrite->rules
        );
    }

    function IMK_plugin_set_query_var($vars) {
        echo " query_vars ";
        array_push($vars, 'property_id');
        array_push($vars, 'blog_slug');
        return $vars;
    }

    function IMK_plugin_include_template($template){
        echo " template_include ";
        if(  get_query_var('blog_slug') ){
            $new_template = $this->plugin_path.'inc/template/components/blog-detail.php';
            if(file_exists($new_template)){
                $template = $new_template;
            }
            add_action("blog_detail_render", array( $this, 'getIMKSingleBlog' ) );
        }
        if(  get_query_var('property_id') ){
            $new_template = $this->plugin_path.'inc/template/components/property-detail.php';
            if(file_exists($new_template)){
                $template = $new_template;
            }
        }
        return $template;

    }

    function getIMKBlogs( $atts = [] ) {
        ob_start();
        $limit = 100;
        if( isset( $atts['limit'] ) ){
            $limit =  $atts['limit'];
        }
        $design = "blog_1";
        if( isset( $atts['design'] ) ){
            $design =  $atts['design'];
        }
        $posts = $this->coreBlogs( [ 'limit' => $limit ] );
        echo "<div class='row imk-blog-list'>";
        foreach ($posts as $post) {
            include( $this->plugin_path.'inc/template/components/'.$design.'.php' );
        }
        echo "</div>";
        return ob_get_clean();
    }

    function getIMKFeaturedInventory( $atts = [] ) {
        ob_start();
        $limit = 100;
        $filters = [];
        if( isset( $atts['limit'] ) ){
            $filters['limit'] =  $atts['limit'];
        }

        $design = "inventory_1";
        if( isset( $atts['design'] ) ){
            $design =  $atts['design'];
        }

        $inventoryRecords = $this->featuredInventory( $filters );
        echo "<br><BR><div class='imk-inventory-list'> <div class='row'>";
        foreach ($inventoryRecords->list as $inventory) {
            include( $this->plugin_path.'inc/template/components/'.$design.'.php' );
        }
        echo "</div></div>";
        return ob_get_clean();
    }

    function getIMKUIMakeList(){

        $makeList = $this->getInventoryMakeList();
        $optionStr = "";
        foreach ($makeList as $make=> $count){
            $selected = "";
            if( isset($_GET['makeName']) && $_GET['makeName'] == $make ){
                $selected = "selected";
            }
            $optionStr.="<option value='$make' $selected> $make ( $count ) </option>";
        }
        echo $optionStr;
    }

    function getIMKInventory( $atts = [] ) {
        ob_start();

        $design = "inventory_1";
        if( isset( $atts['design'] ) ){
            $design =  $atts['design'];
        }

        $limit = 100;
        if( isset( $atts['limit'] ) ){
            $limit =  $atts['limit'];
        }
        $filters = ['limit'=> $limit];
        if( isset( $atts['offer'] ) ){
            $filters['offer'] =  $atts['offer'];
        }

        $inventoryRecords = $this->coreInventory( $filters  );

        if( isset( $atts['with'] ) &&  $atts['with'] == "search_bar" ){
            include( $this->plugin_path.'inc/template/filters/inventory_filters_1.php' );
        }
        echo "<br><BR><div class='imk-inventory-list' > <div class='row' id='imk_inventory_list'>";
        foreach ($inventoryRecords->list as $inventory) {
            include( $this->plugin_path.'inc/template/components/'.$design.'.php' );
        }
        echo "</div>";
        if( isset($inventoryRecords->count) && $inventoryRecords->count > $limit ){
            echo "<div id='load_imk_inventory_btn' data-post-container='#imk_inventory_list'  data-post-limit='$limit' data-post-total-count='".$inventoryRecords->count."' ></div>";
        }
        echo"</div>";
        return ob_get_clean();
    }

    function getIMKInvestment( $atts = [] ) {
        ob_start();
        $limit = 100;
        if( isset( $atts['limit'] ) ){
            $limit =  $atts['limit'];
        }
        $design = "properties_1";
        if( isset( $atts['design'] ) ){
            $design =  $atts['design'];
        }
        $listings = $this->coreInvestment( ['featured'=> false], true );

        if( isset($listings['dst'] ) ){
            echo " <h3 class='imk-investment-title'> DST Properties </h3>  <div class='listing-car-items-units row'> ";
            foreach ($listings['dst'] as $listing) {
                include( $this->plugin_path.'inc/template/components/'.$design.'.php' );
            }
            echo "</div>";
        }

        if( isset($listings['nnn'] ) ){
            echo " <h3 class='imk-investment-title'> NNN Properties </h3> <div class='listing-car-items-units row'> 
            
            ";
            foreach ($listings['nnn'] as $listing) {
                include( $this->plugin_path.'inc/template/components/'.$design.'.php' );
            }
            echo "</div>";
        }

        if( isset($listings['sfr'] ) ){
            echo " <h3 class='imk-investment-title'> SFR Properties </h3>  <div class='listing-car-items-units row'> ";
            foreach ($listings['sfr'] as $listing) {
                include( $this->plugin_path.'inc/template/components/'.$design.'.php' );
            }
            echo "</div>";
        }


        return ob_get_clean();
    }

    function getIMKFeaturedInvestment( $atts = [] ) {
        ob_start();
        $limit = 100;
        if( isset( $atts['limit'] ) ){
            $limit =  $atts['limit'];
        }
        $design = "properties_1";
        if( isset( $atts['design'] ) ){
            $design =  $atts['design'];
        }
        $listings = $this->coreInvestment( ['featured'=> true] );

        echo " <h3 class='imk-investment-title'>  List of Offerings </h3>  <div class='listing-car-items-units row'> ";
        foreach ($listings as $listing) {
            include( $this->plugin_path .'inc/template/components/'.$design.'.php' );
        }
        echo "</div>";

        return ob_get_clean();
    }

    function getIMKInventoryFilters( $atts = [] ) {
        ob_start();
        include( $this->plugin_path.'inc/template/filters/inventory_filters_1.php' );
        return ob_get_clean();
    }

    function getReviewUI(  $atts = []  ){

        $design = "review_1";
        if( isset( $atts['design'] ) ){
            $design =  $atts['design'];
        }
        $DealerRater = new DealerRater_API(  );
        $data = $DealerRater->getReviews( $atts  );
        echo "<div class='row imk-review-list' >";
        foreach ( $data->reviews as $review ) {
            include( $this->plugin_path.'inc/template/components/'.$design.'.php' );
        }

        echo "</div>";
    }

    function renderPetUI( $atts = [] ){
        ob_start();
        

        $design = "pet_list_1";
        if( isset( $atts['design'] ) ){
            $design =  $atts['design'];
        }

        $records = $this->corePetList( $atts );

        echo " <div class='imk-pet-list row'> ";
        foreach ($records as $petObject) {
            include( $this->plugin_path.'inc/template/components/'.$design.'.php' );
        }
        echo "</div>";

        return ob_get_clean();
    }

}