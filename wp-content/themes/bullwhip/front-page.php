<?php
//global $wp_filter;ts_var( $wp_filter['the_content_limit'] );
//remove sidebars (jsut in case)
remove_all_actions('genesis_sidebar');
remove_all_actions('genesis_sidebar_alt');
/**
 * hero + 3 widgets
 */
//add the hero
add_action('genesis_after_header','msd_child_hero');
//add the callout
//add_action('genesis_after_header','msd_call_to_action');
//move footer and add three homepage widgets
remove_action('genesis_before_footer','genesis_footer_widget_areas');
remove_action('genesis_before_footer','msd_do_location_nav');
add_action('genesis_before_footer','msd_child_homepage_widgets');
add_action('genesis_before_footer','msd_do_location_nav');
add_action('genesis_before_footer','genesis_footer_widget_areas');
/**
 * long scrollie
 */
//remove_all_actions('genesis_loop');
//add_action('genesis_loop','msd_scrollie_page');

genesis();