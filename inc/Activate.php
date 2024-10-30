<?php
/*
  * @package IMKServicePlugin
  * */

namespace TBC\IMK;
use TBC\IMK\BaseController;
    class Activate {
        public static function activate(){
            global $wpdb;
            require_once(ABSPATH . 'wp-admin/includes/upgrade.php');
            $tablename = $wpdb->prefix . BaseController::$table;
            if (count($wpdb->get_var("SHOW TABLES LIKE '" . $tablename . "' ")) == 0) {
                $sql_query_to_create_table = "CREATE TABLE " . $tablename . " (
                     ID int(10) unsigned NOT NULL AUTO_INCREMENT,
                     app_name varchar(50) DEFAULT NULL,
                     title varchar(60) DEFAULT NULL,
                     user_id varchar(50) DEFAULT NULL,
                     group_id varchar(60) DEFAULT NULL,
                     api_url varchar(60) DEFAULT NULL,
                     active tinyint(1) DEFAULT NULL,
                     api_key varchar(60) DEFAULT NULL,
                     PRIMARY KEY (ID)
                    ) ENGINE=InnoDB DEFAULT CHARSET=utf8";
                dbDelta($sql_query_to_create_table);
            }
        }
    }
?>