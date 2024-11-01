<?php
/**
 * Plugin Name: Who delete my posts
 * Description: Record who, when and what post or page is deleted in your wordpress site.
 * Author: TocinoDev
 * Author URI: https://tocino.mx
 * Version: 0.1.1
 * Tested up to: 6.0
 * Requires PHP: 7.4
*/
use Whodeletemyposts\App as Whodeleteapp;

defined('ABSPATH') || exit;

if(!defined('WHODELETEMYPOSTS_FILE'))
	define('WHODELETEMYPOSTS_FILE', __FILE__);
if(!defined('WHODELETEMYPOSTS_URL'))
	define('WHODELETEMYPOSTS_URL', plugin_dir_url(WHODELETEMYPOSTS_FILE));

require 'vendor/autoload.php';

function whodeletemyposts()
{
	static $instance = null;

	if($instance === null){
		$instance = new Whodeleteapp();
	}

	return $instance;
}

whodeletemyposts();
