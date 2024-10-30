<?php
/*
  * @package IMKServicePlugin
  * */
namespace TBC\IMK;
use TBC\IMK\ControllerInterface;
/**
 * @author  Giuseppe Mazzapica <giuseppe.mazzapica@gmail.com>
 * @license http://opensource.org/licenses/MIT MIT
 */


class Controller implements ControllerInterface {

  private $pages;
  private $loader;
  private $matched;
  
  function __construct( TemplateLoaderInterface $loader ) {
    $this->pages = new \SplObjectStorage;
    $this->loader = $loader;
  }
  
  function init() {
    do_action( 'gm_virtual_pages', $this ); 
  }
  
  function addPage( PageInterface $page ) {
    $this->pages->attach( $page );
    return $page;
  }
  
  function dispatch( $bool, \WP $wp ) {
    if ( $this->checkRequest() && $this->matched instanceof Page ) {
      $this->loader->init( $this->matched );
      $wp->virtual_page = $this->matched;
      do_action( 'parse_request', $wp );
      $this->setupQuery();
      do_action( 'wp', $wp );
      $this->loader->load();
      $this->handleExit();
    }
    return $bool;

  }
  
  private function checkRequest() {
    $this->pages->rewind();
    while( $this->pages->valid() ) {
      $route = $this->getPathInfo();
      $pattren = $route->pathUrlEncode;
      if (preg_match("/^$pattren/", $route->fullUrl )){
        $this->matched = $this->pages->current();
        $this->matched->setRoute( $route );
        return true;
      }
      $this->pages->next();
    }
  }        
  
  private function getPathInfo() {
    $home_path = parse_url( home_url(), PHP_URL_PATH );
    $path = trim(preg_replace( "#^/?{$home_path}/#", '/', add_query_arg( array() ) ), "/");
    $route = new \stdClass;
    $route->{'fullUrl'} = $path;
    $givenRegex = trim( $this->pages->current()->getUrl(), '/' );
    preg_match("/^[\d\w\/-]+/i", $givenRegex, $output1);

    if( isset( $output1[0] ) ){
      $route->pathUrl = trim( $output1[0] , "/");
      $route->{'pathUrlEncode'} = preg_replace("/\//i", "\/", $route->pathUrl );
      $urlParamKey ="";
      preg_match("/\:[\d\w_]+/i", $givenRegex, $output2);
      if(isset( $output2[0] )){
        $urlParamKey =  preg_replace("/:/", "", $output2[0]);
        // get URl pAram 
        $urlAfterDecode = $route->pathUrlEncode;  
        $urlParamValue = preg_replace("/^$urlAfterDecode/i", "", $path);
        $urlParamValue = trim($urlParamValue, "/");
        $route->{'paramKey'} = $urlParamKey;
        $route->{$urlParamKey} = $urlParamValue;
      }
    }

    return $route;

  }
  
  private function setupQuery() {
    global $wp_query;
    $wp_query->init();
    $wp_query->is_page       = TRUE;
    $wp_query->is_singular   = TRUE;
    $wp_query->is_home       = FALSE;
    $wp_query->found_posts   = 1;
    $wp_query->post_count    = 1;
    $wp_query->max_num_pages = 1;
    $posts = (array) apply_filters(
      'the_posts', array( $this->matched->asWpPost() ), $wp_query
    );
    $post = $posts[0];
    $wp_query->posts          = $posts;
    $wp_query->post           = $post;
    $wp_query->queried_object = $post;
    $GLOBALS['post']          = $post;
    $wp_query->virtual_page   = $post instanceof \WP_Post && isset( $post->is_virtual )
      ? $this->matched
      : NULL;
  }


  
  public function handleExit() {
    exit();
  }
}