<?php
/*
  * @package IMKServicePlugin
  * */
namespace TBC\IMK;
/**
 * @author  Giuseppe Mazzapica <giuseppe.mazzapica@gmail.com>
 * @license http://opensource.org/licenses/MIT MIT
 */
interface PageInterface {
  function getUrl();
  
  function getTemplate();
  
  function getTitle();
  
  function setTitle( $title );
  
  function setContent( $content );
  
  function setTemplate( $template );
  
  /**
  * Get a WP_Post build using viryual Page object
  *
  * @return \WP_Post
  */
  function asWpPost();
}