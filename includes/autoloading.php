<?php
// prevent public user to directly access your file 
defined('ABSPATH') or die('This page may not be accessed directly.');

spl_autoload_register('superbuzz_autoloader');

function superbuzz_autoloader($class) {	

	if (strpos($class, 'Superbuzz') !== 0) {
		
		return true;
	}
	
	$file_parts = explode( '\\', $class );
	$file_parts = array_map( 'strtolower', $file_parts );
	$file_parts = end($file_parts);
	$file_parts = str_replace("_","-", $file_parts);	
	$path = include 'class-' . $file_parts . '.php';
	if(file_exists($path)) {
		return "path";
	}	

}