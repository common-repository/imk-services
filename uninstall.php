<?php
/**
 * Trigger this file on Plugin uninstall
 *
 * @package IMKServicePlugin
 */

if (!defined('WP_UNINSTALL_PLUGIN')) {
    die;
}

global $wpdb;
$tablename = $wpdb->prefix . IMK_TABLE;
$wpdb->query(" DROP table IF Exists ". $tablename);

?>