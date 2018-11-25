<?php

/*
Plugin Name: Tokenization using OST
Plugin URI: http://wordpress.org
Description: This plugin is designed to tokenize your blog where a writer writes the blog and get its OST token on the basis of Jetpack views.
Version: 1.0.0
Author: Tayyab Ejaz
Author URI: http://tayyabejaz.com
License: A "Slug" license name e.g. GPL2
*/

function form()
{
	?>
	First Number: <input placeholder="First Number" id="fir_num">
	Second Number: <input placeholder="Second Number" id="sec_num">
	<input type="submit" id="submit_btn" value="submit">
	<div id="disp"></div>
<?php
}

add_shortcode('newplug','form');
//add_action('admin_enqueue_scripts','ajax_scripts');
//add_action('admin_header', 'ajax_scripts');
add_action('admin_menu','menu_page');



//function ajax_scripts()
//{
//    wp_register_script('ajaxHandle', '/wp-content/themes/twentyfifteen/js/main_ajax.js',array('jquery'),false,true);
//    wp_enqueue_script('ajaxHandle');
//    wp_localize_script('ajaxHandle', 'ajax_object', array('ajaxurl' => admin_url('admin-ajax.php')));
//}

function menu_page()
{
    add_menu_page("My Plugin Posts" ,"Token Plugins", 4, "plugin-posts", "pluginPostMenu","" );
	add_submenu_page("plugin-posts", "Wallet", "Wallet" , 4, "wallet", "wallet");
	add_submenu_page("plugin-posts", "Buy Ads", "Buy Ads" , 4, "buyads", "buy_ads");
    add_submenu_page("plugin-posts", "About Dev", "About" , 4, "about_plugin", "aboutDev");

}

function pluginPostMenu()
{

	include_once( "assets/main_page.php" );
}

function aboutDev() {

	include_once("assets/about_us.html");
}

function wallet()
{
    include_once( "assets/wallet_page.php" );
}

function buy_ads()
{
    include_once("assets/buyads_page.php");
}

