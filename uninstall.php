<?php 

// prevent public user to directly access your file 
defined('ABSPATH') or die('This page may not be accessed directly.');

// exit if uninstall constant is not defined
if (!defined('WP_UNINSTALL_PLUGIN')) exit;

// delete plugin options
delete_option('superbuzz_version');

// delete database table
global $wpdb;
$table_name = $wpdb->prefix .'superbuzz';

$data = $wpdb->query(remove_backticks($wpdb->prepare("DROP TABLE IF EXISTS %s", $table_name)));

function remove_backticks( $s ) {
    return str_replace("'", "", $s);
}
