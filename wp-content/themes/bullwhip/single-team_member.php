<?php
add_action('genesis_before_post_title','msd_add_team_member_headshot');
add_action('genesis_before_post_content','msd_team_member_contact_info');
add_action('genesis_after_post_content','msd_team_additional_info');

genesis();