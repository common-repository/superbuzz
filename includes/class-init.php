<?php

namespace Superbuzz\Includes;

// prevent public user to directly access your file 
defined('ABSPATH') or die('This page may not be accessed directly.');

class Init
{

	private static $sapp_superbuzz = 'https://app.superbuzz.io/appid/';

	public function __construct()
	{
		$this->cpt_registered();
		$this->custom_css_js();

		add_action('wp_ajax_appid_superbuzz_submit', [$this, 'appid_superbuzz_submit']);
		add_action('wp_ajax_crul_app_id_validated', [$this, 'crul_app_id_validated']);
		add_action('wp_ajax_table_superbuzz_apiresponse', [$this, 'table_superbuzz_apiresponse']);
		add_action('wp_head', [$this, 'superbuzz_scripts']);
	}

	public function cpt_registered()
	{
		$cpt_registered = new Custom_Post_Type_Superbuzz();
		add_action('admin_menu', array($cpt_registered, 'custom_add_menu_page'), 0);

		return $cpt_registered;
	}

	public function custom_css_js()
	{
		$custom_css_js = new Custom_Css_And_Js();
		add_action('admin_enqueue_scripts', array($custom_css_js, 'add_custom_js_and_css_file_to_admin'), 0);
		
		return $custom_css_js;
	}

	public function superbuzz_scripts()
	{
		$fetchData = $this->get_apikey_from_db();

		if (isset($fetchData->api_response) && $fetchData->api_response == 'true') {
			$apiKey = $fetchData->app_id;

			wp_enqueue_script('superbuzz-sdk', 'https://app.superbuzz.io/SuperBuzzSDK.lib.js', array('jquery'));

			$inline_script = "
    			jQuery(document).ready(function($) {
        			var SuperBuzzSDK = window.SuperBuzzSDK || {};
        				SuperBuzzSDK.init({
            			app_id: '$apiKey',
            			wp: true
        			});
    			});
			";
			wp_add_inline_script('superbuzz-sdk', $inline_script);
		}
	}

	public function appid_superbuzz_submit()
	{
		global $wpdb;

		$table_name = $wpdb->prefix . 'superbuzz';

		$user_id = get_current_user_id();		
	
		// Verify the nonce
		if ( ! isset( $_GET['_wpnonce'] ) || ! wp_verify_nonce( $_GET['_wpnonce'], 'SuperBuzzSubmitNonuce' ) ) {
			// Handle the invalid nonce case here, or simply exit the function
			wp_send_json(['success' => false, 'message' => 'Invalid nonce']);
			return;
		}
		
		$api_id = sanitize_text_field($_POST['apiId']);

		if (isset($api_id)) {
			$created_date = date("Y-m-d");

			// Use $wpdb->prepare() to prepare the query with placeholders
			$query = $wpdb->prepare('TRUNCATE TABLE %s', $table_name);	

			$wpdb->query($this->remove_backticks($query));		
			$wpdb->insert(
				$table_name,
				array('user_id' => $user_id, 'app_id' => $api_id, 'created_date' => $created_date),
				array('%d', '%s', '%s')
			);			

			$response = [
				'lastId'  => $wpdb->insert_id,
				'success' => true
			];

		} else {

			$response = [
				'lastId'  => $wpdb->insert_id,
				'success' => false,
				'message' => 'Please enter API ID'
			];
		}

		wp_send_json($response);
	}

	public function getDomain()
	{
		$url = sanitize_text_field($_SERVER['SERVER_NAME']);
		$pieces = parse_url($url);
		$domain = isset($pieces['path']) ? $pieces['path'] : '';

		return str_replace('www.', '', $domain);
	}

	public function crul_app_id_validated()
	{
		$serverUrl = $this->getDomain();
		$url = self::$sapp_superbuzz . $serverUrl;
		$response = wp_remote_get($url);

		$res = wp_remote_retrieve_body($response);
		$result = json_decode($res, true);
		$result['domain'] = $serverUrl;

		wp_send_json($result);
	}

	public function table_superbuzz_apiresponse()
	{
		global $wpdb;

		$table_name = $wpdb->prefix . 'superbuzz';
		$fetchData = $this->get_apikey_from_db();
		$lastId = $fetchData->id;

		$wpdb->update(
			$table_name,
			array('api_response' => sanitize_text_field($_POST['apiResponse'])),
			array('ID' => $lastId)
		);

		$response = [
			'lastId' => $lastId,
			'success' => true
		];

		wp_send_json($response);
	}

	private function get_apikey_from_db()
	{
		global $wpdb;
		
		$table_name = $wpdb->prefix . 'superbuzz';

		return $wpdb->get_row(
			$this->remove_backticks($wpdb->prepare("SELECT * FROM %s LIMIT %d", $table_name, 1))
		);
	}	

	public function remove_backticks( $s ) {
		return str_replace("'", "", $s);
	}
}
?>