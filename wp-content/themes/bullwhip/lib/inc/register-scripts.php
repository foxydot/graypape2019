<?php
/*
 * Add styles and scripts
*/
add_action('wp_print_styles', 'msd_add_styles');

function msd_add_styles() {
	global $is_IE;
	if(!is_admin()){
		wp_enqueue_style('bootstrap-style',get_stylesheet_directory_uri().'/lib/bootstrap/css/bootstrap.css');
		wp_enqueue_style('msd-style',get_stylesheet_directory_uri().'/lib/css/style.css',array('bootstrap-style'));
		if($is_IE){
			wp_enqueue_script('ie-style',get_stylesheet_directory_uri().'/lib/css/ie.css');
		}
		if(is_front_page()){
			wp_enqueue_style('msd-homepage-style',get_stylesheet_directory_uri().'/lib/css/homepage.css');
		}
	}
}
add_action('wp_print_scripts', 'msd_add_scripts');

function msd_add_scripts() {
	global $is_IE;
	if(!is_admin()){
		wp_enqueue_script('bootstrap-jquery',get_stylesheet_directory_uri().'/lib/bootstrap/js/bootstrap.min.js',array('jquery'));
        wp_enqueue_script('font-awesome','//use.fontawesome.com/9bfefedfcf.js');
        wp_enqueue_script('nav-scripts',get_stylesheet_directory_uri().'/lib/js/nav-scripts.js',array('jquery','bootstrap-jquery'));
        wp_enqueue_script('msd-jquery',get_stylesheet_directory_uri().'/lib/js/theme-jquery.js',array('jquery','bootstrap-jquery'));
		wp_enqueue_script('equalHeights',get_stylesheet_directory_uri().'/lib/js/jquery.equalheights.js');
		if($is_IE){
			wp_enqueue_script('columnizr',get_stylesheet_directory_uri().'/lib/js/jquery.columnizer.js');
			wp_enqueue_script('ie-fixes',get_stylesheet_directory_uri().'/lib/js/ie-jquery.js');
		}
		if(is_front_page()){
			wp_enqueue_script('msd-homepage-jquery',get_stylesheet_directory_uri().'/lib/js/homepage-jquery.js');
		}
	}
}