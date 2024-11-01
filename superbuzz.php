<?php
// Namespace declare
namespace Superbuzz;
// prevent public user to directly access your file 
defined('ABSPATH') or die('This page may not be accessed directly.');

/*
 * Plugin Name: SuperBuzz
 * Plugin URI: https://www.superbuzz.io/
 * Description: SuperBuzz - Boost retention traffic and profits using GPT-3 technology.
 * Version: 1.2.0
 * Author: SuperBuzz
 * Author URI: https://www.superbuzz.io/
 */

// Added autoloading file 
require_once( trailingslashit( dirname( __FILE__ ) ) . 'includes/autoloading.php' );

class SuperBuzz {

    public static $init;

    public function __construct() {  
        register_activation_hook( __FILE__ , [ $this, 'superbuzz_database_table' ] );
        $this->init();
    }
   
    public function superbuzz_database_table() {   
        global $wpdb;        
        $db_table_name = $wpdb->prefix . 'superbuzz';  // table name
        $charset_collate = $wpdb->get_charset_collate();
        $superbuzz_version = "1.0";   
        // create table only if doesnot exits
        if($wpdb->get_var("SHOW TABLES LIKE '$db_table_name'") != $db_table_name){
            // create table 
            $sql = "CREATE TABLE $db_table_name(
                id int(11) NOT NULL auto_increment,
                user_id int(11) NOT NULL,
                app_id varchar(255) NOT NULL,
                api_response varchar(255) NULL ,
                created_date DATETIME NOT NULL,                
                PRIMARY KEY  (id)
            ) $charset_collate";
            require_once( ABSPATH . 'wp-admin/includes/upgrade.php' );
            dbDelta( $sql );
            add_option( 'superbuzz_version' , $superbuzz_version );  
        }            
              
    }

    public function init() {
        $init = new Includes\Init();
        return $init;       
    }   
}

$Superbuzz = new SuperBuzz();
