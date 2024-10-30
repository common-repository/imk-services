<?php
/*
  * @package IMKServicePlugin
  * */
namespace TBC\IMK;

/**
 * @author  Giuseppe Mazzapica <giuseppe.mazzapica@gmail.com>
 * @license http://opensource.org/licenses/MIT MIT
 */
interface TemplateLoaderInterface {

  /**
  * Setup loader for a page objects
  *
  * @param \TBC\IMK\VirtualPagesPageInterface $page matched virtual page
  */
  public function init( PageInterface $page );
  
  /**
  * Trigger core and custom hooks to filter templates,
  * then load the found template.
  */
  public function load();
}