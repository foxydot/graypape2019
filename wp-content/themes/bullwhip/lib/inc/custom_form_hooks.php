<?php
//jobs hooks
            $pop_jobs_id = '2'; //give number of form to use for RSVP
        add_filter( 'gform_pre_render_'.$pop_jobs_id, 'msdlab_populate_jobs' );
        add_filter( 'gform_pre_validation_'.$pop_jobs_id, 'msdlab_populate_jobs' );
        add_filter( 'gform_pre_submission_filter_'.$pop_jobs_id, 'msdlab_populate_jobs' );
        add_filter( 'gform_admin_pre_render_'.$pop_jobs_id, 'msdlab_populate_jobs' );
        function msdlab_populate_jobs( $form ) {
            if(class_exists('GFForms')){
                if(class_exists('WP_Job_Manager')){
                        foreach ( $form['fields'] as &$field ) {
                    
                            if ( $field->type != 'select' || strpos( $field->cssClass, 'populate-jobs' ) === false ) {
                                continue;
                            }
                            
                            $jobs_query = get_job_listings();
                            $jobs = $jobs_query->posts;
                            $choices = array();
                    
                            foreach ( $jobs as $job ) {
                                $choices[] = array( 'text' => $job->post_title, 'value' => $job->post_title );
                            }
                    
                            $field->placeholder = 'Select a position';
                            $field->choices = $choices;
                    
                        }
                
                        return $form;
                    }
                }
            }