<?php
/*
  * @package IMKServicePlugin
  * */
    namespace TBC\IMK;
    class BaseController{
        public $plugin_path;
        public $plugin_url;
        public $APP_DEALER;
        public $APP_IMK;
        public static $table = 'imk_apps';
        public $version;
        public $db;
        public $tablename;
        function __construct(){
            global $wpdb;

            $this->plugin_path = plugin_dir_path( dirname(__FILE__) );
            $this->plugin_url = plugin_dir_url( dirname(__FILE__) );
            $this->APP_DEALER = 'DealerRater';
            $this->APP_IMK = 'IMKService';
            $this->version = "0.1";
            $this->db = $wpdb;
            $this->tablename = $this->db->prefix . self::$table;
        }

        public function get_active_user_detail( $app_name ){
            return $this->db->get_row( "SELECT * FROM " . $this->tablename . " where active=1 and app_name='$app_name' ");
        }

    }
?>