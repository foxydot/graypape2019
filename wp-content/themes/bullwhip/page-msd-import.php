<?php
global $post;
get_template_directory();
$csv_file = ABSPATH.get_post_meta($post->ID,'csv_file',true);
$projects_array = parse_csvfile($csv_file);
foreach($projects_array AS $project){
    if($project['Notes'] == ''){
        $projects[$project['Project #']] = $project;
    } else {
        $featured_projects[$project['Project #']] = $project;
    }
}
foreach($featured_projects AS $project){
    if($existing_project = does_project_exist($project['Project Title'])){
        //add the id number
        $project_fields = get_post_meta($existing_project->ID,'_project_information_fields',true);
        if(!in_array('_project_project_id',$project_fields)){
            $project_fields[] = '_project_project_id';
        }
        if(!in_array('_project_feature',$project_fields)){
            $project_fields[] = '_project_feature';
        }
        update_post_meta($existing_project->ID, '_project_information_fields', $project_fields);
        update_post_meta($existing_project->ID, '_project_project_id', $project['Project #']);
        update_post_meta($existing_project->ID, '_project_feature', 'true');
                
        //add the states
        $location_fields = get_post_meta($existing_project->ID,'_location_information_fields',true);
        if(!in_array('_location_project_states',$location_fields)){
            $location_fields[] = '_location_project_states';
            update_post_meta($existing_project->ID, '_location_information_fields', $location_fields);
        }
        $location_states = preg_split('@[,|\/]@i',$project['State']);
        update_post_meta($existing_project->ID, '_location_project_states', $location_states);
    } else {
        //add the post, get the id
        $postarr = array(
          'comment_status' => 'closed', // 'closed' means no comments.
          'ping_status'    => 'closed', // 'closed' means pingbacks or trackbacks turned off
          'post_author'    => 3, //The user ID number of the author.
          'post_content'   => $project['Notes'], //The full text of the post.
          'post_name'      => sanitize_title_with_dashes($project['Project Title']), // The name (slug) for your post
          'post_status'    => 'publish', //Set the status of the new post.
          'post_title'     => $project['Project Title'], //The title of your post.
          'post_type'      => 'project' //You may want to insert a regular post, page, link, a menu item or some custom post type
        );  
        $post_id = wp_insert_post($postarr);
        //add the id number
        $project_fields = get_post_meta($post_id,'_project_information_fields',true);
        if(!in_array('_project_project_id',$project_fields)){
            $project_fields[] = '_project_project_id';
        }
        if(!in_array('_project_feature',$project_fields)){
            $project_fields[] = '_project_feature';
        }
        update_post_meta($post_id, '_project_information_fields', $project_fields);
        update_post_meta($post_id, '_project_project_id', $project['Project #']);
        update_post_meta($post_id, '_project_feature', 'true');
        //add the states
        $location_fields = get_post_meta($post_id,'_location_information_fields',true);
        if(!in_array('_location_project_states',$location_fields)){
            $location_fields[] = '_location_project_states';
            update_post_meta($post_id, '_location_information_fields', $location_fields);
        }
        $location_states = preg_split('@[,|\/]@i',$project['State']);
        update_post_meta($post_id, '_location_project_states', $location_states);
        //add the client info
        $client_fields = get_post_meta($post_id,'_Client_information_fields',true);
        if(!in_array('_client_client',$client_fields)){
            $client_fields[] = '_client_client';
            update_post_meta($post_id, '_Client_information_fields', $client_fields);
        }
        $client_client[] = array('name'=>$project['Client']);
        update_post_meta($post_id, '_client_client', $client_client);
        //add service areas
        set_my_taxonomy($post_id,'project_type',$project['Project Type']);
        //add market sectors
        set_my_taxonomy($post_id,'market_sector',$project['Market Sector']);
    }
}

foreach ($projects as $project) {
   if($existing_project = does_project_exist($project['Project Title'])){
        //add the id number
        $project_fields = get_post_meta($existing_project->ID,'_project_information_fields',true);
        if(!in_array('_project_project_id',$project_fields)){
            $project_fields[] = '_project_project_id';
        }
        if(!in_array('_project_feature',$project_fields)){
            $project_fields[] = '_project_feature';
        }
        update_post_meta($existing_project->ID, '_project_information_fields', $project_fields);
        update_post_meta($existing_project->ID, '_project_project_id', $project['Project #']);
        update_post_meta($existing_project->ID, '_project_feature', '');
                
        //add the states
        $location_fields = get_post_meta($existing_project->ID,'_location_information_fields',true);
        if(!in_array('_location_project_states',$location_fields)){
            $location_fields[] = '_location_project_states';
            update_post_meta($existing_project->ID, '_location_information_fields', $location_fields);
        }
        $location_states = preg_split('@[,|\/]@i',$project['State']);
        update_post_meta($existing_project->ID, '_location_project_states', $location_states);
    } else {
        //add the post, get the id
        $postarr = array(
          'comment_status' => 'closed', // 'closed' means no comments.
          'ping_status'    => 'closed', // 'closed' means pingbacks or trackbacks turned off
          'post_author'    => 3, //The user ID number of the author.
          'post_content'   => $project['Notes'], //The full text of the post.
          'post_name'      => sanitize_title_with_dashes($project['Project Title']), // The name (slug) for your post
          'post_status'    => 'publish', //Set the status of the new post.
          'post_title'     => $project['Project Title'], //The title of your post.
          'post_type'      => 'project' //You may want to insert a regular post, page, link, a menu item or some custom post type
        );  
        $post_id = wp_insert_post($postarr);
        //add the id number
        $project_fields = get_post_meta($post_id,'_project_information_fields',true);
        if(!in_array('_project_project_id',$project_fields)){
            $project_fields[] = '_project_project_id';
        }
        if(!in_array('_project_feature',$project_fields)){
            $project_fields[] = '_project_feature';
        }
        update_post_meta($post_id, '_project_information_fields', $project_fields);
        update_post_meta($post_id, '_project_project_id', $project['Project #']);
        update_post_meta($post_id, '_project_feature', '');
        //add the states
        $location_fields = get_post_meta($post_id,'_location_information_fields',true);
        if(!in_array('_location_project_states',$location_fields)){
            $location_fields[] = '_location_project_states';
            update_post_meta($post_id, '_location_information_fields', $location_fields);
        }
        $location_states = preg_split('@[,|\/]@i',$project['State']);
        update_post_meta($post_id, '_location_project_states', $location_states);
        //add the client info
        $client_fields = get_post_meta($post_id,'_Client_information_fields',true);
        if(!in_array('_client_client',$client_fields)){
            $client_fields[] = '_client_client';
            update_post_meta($post_id, '_Client_information_fields', $client_fields);
        }
        $client_client[] = array('name'=>$project['Client']);
        update_post_meta($post_id, '_client_client', $client_client);
        //add service areas
        set_my_taxonomy($post_id,'project_type',$project['Project Type']);
        //add market sectors
        set_my_taxonomy($post_id,'market_sector',$project['Market Sector']);
    }
}

function does_project_exist($page_title){
    $project = get_page_by_title($page_title,OBJECT,'project');
    if($project){
        return $project;
    }
    return false;
}

function parse_csvfile($file,$delimiter=",",$indexrow=TRUE){
    global $csv_index;
    if($csv_handle = fopen($file, "rb")){
        while (($data = fgetcsv($csv_handle, 1000, $delimiter)) !== FALSE) {
            $csv_array[] = $data;
        }
        fclose($csv_handle);
    } 
    if($indexrow){
        //remove the title level from the csv
        $csv_index = array_shift($csv_array);
    }
    $i = 0;
    //get the categories and reorganize the array
    foreach($csv_array AS $csv_item){
        $j = 0;
        foreach($csv_item AS $csv_datum){
            if($indexrow){
                $list[$i][$csv_index[$j]] = $csv_datum;
            } else {
                $list[$i][$j] = $csv_datum;
            }
            $j++;
        }
        $i++;
    }
    return $list;
}

function set_my_taxonomy($post_id,$tax,$terms){
    $term_array = preg_split('@[-|\/]@i',$terms);
    foreach($term_array AS $term){
        $term = trim($term);
        if($term_row = get_term_by('slug',$term,$tax)){
            $terms_to_input[] = $term_row->term_id;
        }
    }
    wp_set_post_terms( $post_id, $terms_to_input, $tax, true );
}

genesis();