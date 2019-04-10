<?php
add_action('genesis_before_post_title','msd_add_location_image');
add_action('genesis_before_post_content','msd_location_contact_info');

genesis();