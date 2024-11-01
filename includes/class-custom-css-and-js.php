<?php

namespace Superbuzz\Includes;

// prevent public user to directly access your file 
defined('ABSPATH') or die('This page may not be accessed directly.');

// Custom CSS and JS Function
class Custom_Css_And_Js
{

	function add_custom_js_and_css_file_to_admin()
	{	

		wp_enqueue_script('custom-script', plugin_dir_url(__DIR__) . 'assets/js/custom-script.js', array('jquery'));
		wp_enqueue_style('custom-style', plugin_dir_url(__DIR__) . 'assets/css/custom-style.css');	
			
        $nonce_url = wp_nonce_url(admin_url('admin-ajax.php'), 'SuperBuzzSubmitNonuce');     
		wp_localize_script('custom-script', 'my_nonce_data', array(
			'nonceUrl' => esc_js($nonce_url),
		));
		
	}	

}