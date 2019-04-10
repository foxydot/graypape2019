<?php
add_action('genesis_before_post_title','msd_add_team_member_headshot');
add_action('genesis_before_post_content','msd_team_member_contact_info');
remove_action('genesis_post_content','genesis_do_post_image');
remove_action('genesis_post_content','genesis_do_post_content');
add_action('genesis_post_content','msd_child_excerpt');
genesis();