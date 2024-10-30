<?php
/*
  * @package IMKServicePlugin
  * */
namespace TBC\IMK;

/**
 * @author  Giuseppe Mazzapica <giuseppe.mazzapica@gmail.com>
 * @license http://opensource.org/licenses/MIT MIT
 */

use  TBC\IMK\PageInterface;
class Page implements PageInterface {

  private $url;
  private $title;
  private $content;
  private $template;
  private $methodName;
  private $wp_post;
  private $route;
  private $data;

  function __construct( $url, $title = 'Untitled', $template = 'page.php' ) {
    $this->url = filter_var( $url, FILTER_SANITIZE_URL );
    $this->setTitle( $title );
    $this->setTemplate( $template);
  }

  function setData($data){
      $this->data = $data;
  }

    function getData(){
        return $this->data;
    }


    function getUrl() {
    return $this->url;
  }
  
  function getRoute() {
    return $this->route;
  }
  
  function getTemplate() {
    return $this->template;
  }
  
  function getTitle() {
    return $this->title;
  }
  function getMethod(){
    return $this->methodName;
  }

  function setRoute( $route ) {
    $this->route = $route;
    return $this;

  }

  function setTitle( $title ) {
    $this->title = filter_var( $title, FILTER_SANITIZE_STRING );
    return $this;
  }
  
  function setContent( $content ) {
    $this->content = $content;
    return $this;
  }
  
  function setTemplate( $template ) {
    $this->template = $template;
    return $this;
  }

  function setMethod($methodName){
    $this->methodName = $methodName;
    return $this;
  }
  
  function asWpPost() {
    if ( is_null( $this->wp_post ) ) {
//       print_r($this->route);
      $imk_data = [];
      if( isset( $this->route->paramKey ) && !empty( $this->route->paramKey ) ){
        $imk_data = $this->methodName[0]->{$this->methodName[1]}( $this->route->{ $this->route->paramKey } ,  $this->methodName[0] );
        if( $imk_data->title ){
            $this->setTitle( $imk_data->title );
        }
        if( $imk_data->body ){
            $this->setContent( $imk_data->body );
        }


      }
      
      $post = array(
        'ID'             => 0,
        'post_title'     => $this->title,
        'post_name'      => sanitize_title( $this->title ),
        'post_content'   => $this->content ? : '',
        'post_excerpt'   => '',
        'post_parent'    => 0,
        'menu_order'     => 0,
        'post_type'      => 'imk-page',
        'post_status'    => 'publish',
        'comment_status' => 'closed',
        'ping_status'    => 'closed',
        'comment_count'  => 0,
        'post_password'  => '',
        'to_ping'        => '',
        'pinged'         => '',
        'guid'           => home_url( $this->getUrl() ),
        'post_date'      => current_time( 'mysql' ),
        'post_date_gmt'  => current_time( 'mysql', 1 ),
        'post_author'    => is_user_logged_in() ? get_current_user_id() : 0,
        'is_virtual'     => TRUE,
        'filter'         => 'raw',
      );
      $this->wp_post = new \WP_Post( (object) $post );
      $this->setData($imk_data);
    }
    return $this->wp_post;
  }
}