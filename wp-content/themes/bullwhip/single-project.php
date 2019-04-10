<?php
add_action('wp_enqueue_scripts','msd_add_single_project_assets');
add_action('wp_enqueue_scripts','msd_add_single_project_assets');
add_action('wp_print_footer_scripts','msd_single_project_print_footer_scripts',99);

add_filter( 'genesis_pre_get_option_site_layout', '__genesis_return_sidebar_content_sidebar' );

add_action( 'genesis_before_content', 'msd_post_image', 8 );
remove_action( 'genesis_before_post', 'msd_post_image', 8 );
remove_action( 'genesis_sidebar_alt', 'genesis_do_sidebar_alt' );
add_action( 'genesis_sidebar_alt', array($msd_custom->project_class,'msd_sidebar_project_info_box' ));

add_action('genesis_sidebar','msd_sidebar_taxonomy_menu');
genesis();

function msd_add_single_project_assets(){
    wp_enqueue_style('single-project',get_stylesheet_directory_uri().'/lib/css/single-project-style.css',array('msd-style'));
}
    
    function msd_single_project_print_footer_scripts()
        {
            global $areas,$decorations;
            $cats = implode(',', $decorations['cats']);
            $parents = implode(',', $decorations['parents']);
                print '<script type="text/javascript">/* <![CDATA[ */
                    jQuery(function($)
                    {
                        var img = $(\'.imagemap\');
                        
                        img.mapster({
                            mapKey: \'state\',
                            isSelectable: false,
                            highlight: false,
                            areas : ['.$areas.']
                        });
                        
                        $("'.$cats.'").addClass("current-cat");
                        $("'.$parents.'").addClass("current-cat-parent");
                    });
                /* ]]> */</script>';
        }
