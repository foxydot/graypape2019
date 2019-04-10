<?php
add_filter('the_content_limit','wp_strip_all_tags');

add_theme_support( 'genesis-structural-wraps', array( 'header', 'nav', 'subnav', 'inner', 'footer-widgets', 'footer' ) );
add_theme_support( 'genesis-footer-widgets', 1 );

remove_action( 'genesis_after_header', 'genesis_do_subnav' );
add_action('genesis_before_header','genesis_do_subnav');

add_action('genesis_before_footer','msd_do_location_nav');
function msd_do_location_nav(){
	//* If menu is assigned to theme location, output
	if ( has_nav_menu( 'location_menu' ) ) {
	
		$class = 'menu genesis-nav-menu menu-location';
		if ( genesis_superfish_enabled() )
			$class .= ' js-superfish';
	
		$args = array(
				'theme_location' => 'location_menu',
				'container'      => '',
				'menu_class'     => $class,
				'echo'           => 0,
		);
	
		$nav = wp_nav_menu( $args );
	
		//* Do nothing if there is nothing to show
		if ( ! $nav )
			return;
	
		$nav_markup_open = genesis_markup( array(
				'html5'   => '<nav %s>',
				'xhtml'   => '<div id="location-nav"><div class="wrap">',
				'context' => 'nav-location',
				'echo'    => false,
		) );
		$nav_markup_open .= genesis_structural_wrap( 'menu-location', 'open', 0 );
	
		$nav_markup_close  = genesis_structural_wrap( 'menu-location', 'close', 0 );
		$nav_markup_close .= genesis_html5() ? '</nav>' : '</div></div>';
	
		$nav_output = $nav_markup_open . $nav . $nav_markup_close;
	
		echo apply_filters( 'genesis_do_nav', $nav_output, $nav, $args );
	
	}
}

add_action('after_setup_theme','msd_child_add_homepage_hero3_sidebars');
function msd_child_add_homepage_hero3_sidebars(){
	genesis_register_sidebar(array(
	'name' => 'Homepage Hero',
	'description' => 'Homepage hero space',
	'id' => 'homepage-top'
			));
	genesis_register_sidebar(array(
	'name' => 'Homepage Widget Area',
	'description' => 'Homepage central widget areas',
	'id' => 'homepage-widgets'
			)); 
}
//add_action('after_setup_theme','msd_child_add_homepage_callout_sidebars');
function msd_child_add_homepage_callout_sidebars(){
	genesis_register_sidebar(array(
	'name' => 'Homepage Callout',
	'description' => 'Homepage call to action',
	'id' => 'homepage-callout'
			));
}
add_action('wp_head', 'collections');

/** Customize search form input box text */
add_filter( 'genesis_search_text', 'custom_search_text' );
function custom_search_text($text) {
	return esc_attr( 'Begin your search here...' );
}

add_filter('genesis_breadcrumb_args', 'custom_breadcrumb_args');
function custom_breadcrumb_args($args) {
	$args['labels']['prefix'] = ''; //marks the spot
	$args['sep'] = ' > ';
	return $args;
}

remove_action('genesis_before_loop', 'genesis_do_breadcrumbs');
add_action('genesis_before_content_sidebar_wrap', 'genesis_do_breadcrumbs');

remove_action( 'genesis_before_post_content', 'genesis_post_info' );
remove_action( 'genesis_after_post_content', 'genesis_post_meta' );
/**
 * Add extra menu locations
 */
register_nav_menus( array(
'location_menu' => 'Location Menu',
'footer_menu' => 'Footer Menu'
) );
/**
 * Replace footer
 */
remove_action('genesis_footer','genesis_do_footer');
add_action('genesis_footer','msdsocial_do_footer');
function msdsocial_do_footer(){
	global $msd_social;
	if(has_nav_menu('footer_menu')){$copyright .= wp_nav_menu( array( 'theme_location' => 'footer_menu','container_class' => 'ftr-menu ftr-links','echo' => FALSE ) );}
	if($msd_social){
		$copyright .= '&copy;Copyright '.date('Y').' '.$msd_social->get_bizname().' &middot; All Rights Reserved<br />';
	} else {
		$copyright .= '&copy;Copyright '.date('Y').' '.get_bloginfo('name').' &middot; All Rights Reserved<br />';
	}
	$copyright .= 'Another <a href="http://adviainternet.com" target="_blank">Cincinnati Web Design by Advia Internet</a>';
	print '<div id="copyright" class="copyright gototop">'.$copyright.'</div>';
}

/**
 * Reversed out style SCS
 * This ensures that the primary sidebar is always to the left.
 */
add_action('genesis_before', 'msd_new_custom_layout_logic');
function msd_new_custom_layout_logic() {
	$site_layout = genesis_site_layout();	 
	if ( $site_layout == 'sidebar-content-sidebar' ) {
		// Remove default genesis sidebars
		remove_action( 'genesis_after_content', 'genesis_get_sidebar' );
		remove_action( 'genesis_after_content_sidebar_wrap', 'genesis_get_sidebar_alt');
		// Add layout specific sidebars
		add_action( 'genesis_before_content_sidebar_wrap', 'genesis_get_sidebar' );
		add_action( 'genesis_after_content', 'genesis_get_sidebar_alt');
	}
}

function msd_child_excerpt( $post_id = NULL, $excerpt_length = 30, $trailing_character = '&nbsp;<i class="icon-circle-arrow-right"></i>' ) {
if($post_id){$the_post = get_post( $post_id );}else{global $post;$the_post = $post;}
    $the_excerpt = strip_tags( strip_shortcodes( $the_post->post_excerpt ) );
     
    if ( empty( $the_excerpt ) )
    $the_excerpt = strip_tags( strip_shortcodes( $the_post->post_content ) );
     
    $words = explode( ' ', $the_excerpt, $excerpt_length + 1 );
     
    if( count( $words ) > $excerpt_length )
    $words = array_slice( $words, 0, $excerpt_length );
     
    $the_excerpt = implode( ' ', $words ) . ' <a href="'.get_post_permalink($post_id).'">'.$trailing_character.'</a>';
    print $the_excerpt;
}
add_filter('get_the_content_limit_allowedtags','msdlab_get_the_content_limit_allowedtags');
function msdlab_get_the_content_limit_allowedtags($tags){
    $tags .= ',<a>';
    return $tags;
}
