<?php
function msd_add_location_image($post = false){
    if(!$post){global $post;}
    //setup thumbnail image args to be used with genesis_get_image();
    $size = 'headshot'; // Change this to whatever add_image_size you want
    $default_attr = array(
            'class' => "alignleft attachment-$size $size",
            'alt'   => $post->post_title,
            'title' => $post->post_title,
    );

    // This is the most important part!  Checks to see if the post has a Post Thumbnail assigned to it. You can delete the if conditional if you want and assume that there will always be a thumbnail
    if ( has_post_thumbnail($post->ID) ) {
        printf( '%s', genesis_get_image( array( 'size' => $size, 'attr' => $default_attr ) ) );
    }
}
function msd_location_contact_info($post = false){
    if(!$post){global $post;}
    global $contact_info,$location_info;
    $contact_info->the_meta($post->ID);
    $location_info->the_meta($post->ID);
    ?>
    <ul class="team-member-contact-info">
        <?php $contact_info->the_field('_team_phone'); ?>
        <?php if($contact_info->get_the_value() != ''){ ?>
            <li class="phone"><i class="icon-phone icon-large"></i> <?php print msd_str_fmt($contact_info->get_the_value(),'phone'); ?></li>
        <?php } ?>
        
        <?php $contact_info->the_field('_team_fax'); ?>
        <?php if($contact_info->get_the_value() != ''){ ?>
            <li class="mobile"><i class="icon-print icon-large"></i> <?php print msd_str_fmt($contact_info->get_the_value(),'phone'); ?></li>
        <?php } ?>
        
        <?php $location_info->the_field('address'); ?>
        <?php if($location_info->get_the_value() != ''){ ?>
            <?php 
            $addresses = $location_info->get_the_value(); 
            foreach($addresses AS $address){
                if($address['street']){ $theaddress .= $address['street']; }
                if($address['street2']){ $theaddress .= ' '.$address['street2']; }
                if($address['city']){ $theaddress .= ', '.$address['city']; }
                if($address['state']){ $theaddress .= ', '.$address['state']; }
                if($address['zip']){ $theaddress .= ' '.$address['zip']; }
            }
            ?>
            <li class="address"><i class="icon-flag icon-large"></i><?php print $theaddress; ?></li>
        <?php } ?>
    </ul>
    <?php
}
function display_all_locations_shortcode_handler($atts){
    $args = array(
        'post_type' => 'location',
        'order' => 'ASC',
        'orderby' => 'post_title',
        'numberposts' => -1,
    );
    $all_locations = get_posts($args);
    foreach($all_locations AS $location){
        $ret .= '<div class="location">';
        if(has_post_thumbnail($location->ID)){
            $ret .= get_the_post_thumbnail($location->ID,'headshot',array(
            'class' => "alignleft attachment-headshot headshot",
            'alt'   => $location->post_title,
            'title' => $location->post_title,
    ));
        }
        $ret .= '<h3 class="location-title">'.$location->post_title.'</h3>';
        ob_start();
        msd_location_contact_info($location);
        $ret .= ob_get_contents();
        ob_end_clean();
        $ret .= $location->post_content;
        $ret .= '</div>';
    }
    return $ret;
}
add_shortcode('locations', 'display_all_locations_shortcode_handler');
