<?php
/*
  * @package IMKServicePlugin
  * */
namespace TBC\IMK;

use TBC\IMK\BaseController;
use TBC\IMK\TemplateLoaderInterface;

/**
 * @author  Giuseppe Mazzapica <giuseppe.mazzapica@gmail.com>
 * @license http://opensource.org/licenses/MIT MIT
 */
class TemplateLoader extends BaseController implements TemplateLoaderInterface {
  public $page;
  public function init( PageInterface $page ) {
    $this->templates = $page->getTemplate();
      $this->page = $page;
  }
  
  public function load() {
    do_action( 'template_redirect' );
    $filtered = $this->templates;
    if ( empty( $filtered ) || file_exists( $filtered ) ) {
      $template = $filtered;
    }
    if ( ! empty( $template ) &&file_exists( $template ) ) {
      $imk_data = $this->page->getData() ;
      require_once $template;
    }
  }
}