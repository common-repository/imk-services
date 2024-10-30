<?php
/*
  * @package IMKServicePlugin
  * */
/*
	Plugin Name: IMK services
	Plugin URI: https://tradebuilderinc.com/
	Description: IMKLOUD is Tradebuilder’s next generation Marketing Automation and Interaction Management Platform.
	Author: Vinesh
	Author URI: 
	Version: 0.2
	License: GPLv2 or later
    License URI: http://www.gnu.org/licenses/gpl-2.0.html
*/

defined( 'ABSPATH' ) or die("Access Denied");
if( file_exists( dirname( __FILE__ ). '/vendor/autoload.php' ) ){
    require_once dirname( __FILE__ ). '/vendor/autoload.php';
}

use TBC\IMK\Activate;
use TBC\IMK\Deactivate;
use TBC\IMK\BaseController;

function IMK_activate(){
    Activate::activate();
}
function IMK_deactivate(){
    Deactivate::deactivate();
}
function IMKPluginUninsall(){
    global $wpdb;
    $tablename = $wpdb->prefix . BaseController::$table;
    $wpdb->query(" DROP table IF Exists ". $tablename);
}
register_activation_hook( __FILE__ , 'IMK_activate' );
register_deactivation_hook( __FILE__ ,'IMK_deactivate' );
register_uninstall_hook( __FILE__ , 'IMKPluginUninsall');



use TBC\IMK\Admin\AdminDashboard;
use TBC\IMK\Forms;
use TBC\IMK\Frontend;
use TBC\IMK\REST_API;

if( !class_exists('IMKServicePlugin') ){

    class IMKServicePlugin  {
        public static function get_services(){
            return [
                AdminDashboard::class,
                Forms::class,
                Frontend::class,
                REST_API::class
            ];
        }

        public static function register_services(){
            foreach ( self::get_services() as $key => $class ){
                $service = self::instantiate($class);
                if( method_exists( $service, 'register' ) ){
                    $service->register();
                }
            }
        }

        private static function instantiate( $class ){
            $service = new $class();
            return $service;
        }
    }
    IMKServicePlugin::register_services();
}



