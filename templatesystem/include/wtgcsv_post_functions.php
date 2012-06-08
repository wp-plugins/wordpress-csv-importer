<?php

function wtgcsv_create_posts($project_code,$posts_target,$request_method,$script){
   
    if($script == 'advanced'){
        wtgcsv_create_posts_advanced();// paid edition only
    }elseif($script == 'basic'){
        wtgcsv_create_posts_basic(); 
    }
       
}

function wtgcsv_create_posts_advanced($project_code,$posts_target,$request_method){

    global $wpdb;

    // remove hooks/filters meant for manual actions post actions only
        //remove_filter('content_save_pre', 'eci_editpostsync');### TODO: edit post sync to be complete first
    
    // increase events counter for campaign
        //++$pro[$filename]['events'];

    // get data
        // $records   use different functions depending on single or multiple table et 
        // $records = $wpdb->get_results( 'SELECT * FROM '. $csv['sql']['tablename'] .' WHERE ecipostid IS NULL OR ecipostid = 0 LIMIT '. $posts .'' );

    // end here if no records found but report it also
        // if( !$records )  wtgcsv_notice
    
    // initilize variables
    $new_posts = 0;
    $adopted_posts = 0;
    $void_records = 0;
    $invalid_records = 0;  
    $fault_occured = 0;// counted times the loop cannot finish the whole script   
                
    // begin looping through all records
    foreach( $records as $r ){
        
        // store record id if debugging so that we know where scripts ends on failure
            // $wtgcsv_debugmode_strict
        
        // run adoption check (on a single value from selected column i.e product id)
            // wtgcsv_create_posts_adoption();
                // if a post is adopted there is no need to continue this script
            
        // build array using record - info can be added then stored for debugging - is also easier for multiple table                
            // wtgcsv_build_record_array();
        
        // modify values in record array based on users rules
            // $record_array = wtgcsv_modify_record_array();
            
        // apply categories (get existing, create or a mix of both)
            // $category_array = wtgcsv_category_setup($record_array); ### old eci_categorise()
        
        // establish publish date
            //$my_post = eci_postdate( $csv,$my_post,$recordarray,$set,$filename );
        
        // create base post (adds all possible values that can be added at this stage)
            // $my_post = wtgcsv_create_posts_draft($category_array); ## eci_createbasepost(date("Y-m-d H:i:s"),gmdate("Y-m-d H:i:s"),$designarray,'pending',$recordarray,$csv)  
                // do this in draft function eci_conditions_postauthor_return($recordarray,$csv);
                    // also create the title using actual data straight away so the initial url does not get changed
                        
                       //    if( !isset( $my_post['post_name'] ) ){       $my_post['post_name'] = sanitize_title( $my_post['post_title'] );
                           // duplication check using title before draft created  $duplicate = eci_duplicateposts( $csv,$my_post,$filename,$set );  return boolean and increase duplicate counter
                            
                            //  using final publish date - make final decision on post status (users selected or future for schedule posts)
                                   // $my_post = eci_poststatus( $csv,$my_post,$pro );
                                          //  eci_postbuildmeta( $my_post['ID'], $filename, $record->eciid, $csv['sql']['tablename'] ); 
                                                 //                                         // update this record with its matching post id in the project database table
                                       // $wpdb->query('UPDATE '. $csv['sql']['tablename'] .'
                                        //SET ecipostid = '.$my_post['ID'].',eciapplied = NOW()
                                        //WHERE eciid = '.$record->eciid.'');
                                                                               // update post - apply users selected status - ID returned on success
                                        //$my_post['post_status'] = $csv['poststatus'];
                                        //$my_post['ID'] = wp_update_post( $my_post );

                                        // update project progress if success
                                        //if( $my_post['ID'] )
                                        //{
                                        //        ++$pro[$filename]['postscreated'];
                                        //        ++$progress['createsuccess'];
                                        //}
                                        //elseif( !$my_post['ID'] )
                                        //{
                                        //        eci_log( 'Create Post Fail','No post id returned by update',
                                      //
                                        //}
                                        
                                         
        // store last publish date used
            // for incrementing etc, use publish date from $my_post to store                                
                                        
        // end here if there is a failure creating the draft post
            // if( !$my_post )  wtgcsv_notice(create persistent message if not manual)   

        // do tags, $my_post = eci_populatemypost_specialfunctions( $my_post,$recordarray,$csv,$set );
        
        // populate post content
            // $my_post = eci_populatedesign( $designarray, $recordarray, $csv, $filename,$pro );
                  // conditions content replace  $my_post = eci_conditionreplace( $my_post, $csv );
                                        
        // add custom fields and meta values                        
            // eci_addcustomfields( $my_post['ID'],$recordarray,$csv,$filename,$set,$csv['format']['titles'],$csv['format']['titles_sql'],$csv['format']['tokensymbols'] );
            // populate seo related meta fields  eci_seo($csv,$recordarray,$designarray,$my_post['ID']);
                                     
        // populate taxonomy
            //  eci_populatetaxonomy($recordarray,$csv,$filename,$set,$my_post['post_type'],$my_post['ID']);
        
        // create thumbnail/featured image attachment if activated ( wordpress post image )
            // eci_isthumbnailneeded( $csv,$recordarray,$my_post['ID'] );                                       

        // apply url cloaking
            // $my_post = eci_applyurlcloaking( $csv,$recordarray,$my_post );
            
        // put the post id into variable for returning as the $my_post object is destroyed
        $post_id = $my_post['ID'];
        
        // if a single post has been requested do not unset variables, if many have then unset them
        if( $posts_target > 1 ){unset($my_post);}
        
        // clear cache                   
        $wpdb->flush();
        
    }// end for each record
    
    // if a single post is created add specific post information too the output                          

    /*
    if( !isset( $my_post['tags_input'] ) )
    {
            $my_post['tags_input'] = 'No Tags Added';
    }

    $singlepost = '<h4>Post Details</h4>
    Title: '.$my_post['post_title'].'<br />
    ID: '.$my_post['ID'].'<br />
    Publish Date: '.$my_post['post_date'].'<br />
    Tags: '.$my_post['tags_input'].'<br />';

     $args = array(
    'orderby'            => 'name',
    'order'              => 'ASC',
    'show_last_update'   => 0,
    'style'              => 'list',
    'show_count'         => 1,
    'hide_empty'         => 0,
    'use_desc_for_title' => 1,
    'include'            => $my_post['post_category'],
    'hierarchical'       => true,
    'title_li'           => __( '<h4>Categories</h4>' ),
    'show_option_none'   => __('No categories'),
    'number'             => NULL,
    'echo'               => 0,
    'depth'              => 99,
    'taxonomy'           => 'category' );

    $singlepost .= ''.wp_list_categories( $args ).'';
    $singlepost    .= '<h4>Featured Image</h4>';
    if ( has_post_thumbnail( $my_post['ID'] ) )
    {
            $image = wp_get_attachment_image_src( get_post_thumbnail_id( $my_post['ID'] ), 'single-post-thumbnail' );
            $singlepost    .= $image[0];
    }
    else
    {
            $singlepost    .= 'No Featured Image';
    }
    
    $singlepost    .= '<h4>Post Content Dump</h4>
    '.$my_post['post_content'].'<br />';
        */           
                  
    // return last post ID - only really matters for testing or single post create requests
    return $post_id;
}

function wtgcsv_create_posts_basic(){ 

    global $wpdb;
             // remove hooks/filters meant for manual actions post actions only
        //remove_filter('content_save_pre', 'eci_editpostsync');### TODO: edit post sync to be complete first
    
    // increase events counter for campaign
        //++$pro[$filename]['events'];

    // get data
        // $records   use different functions depending on single or multiple table et 
        // $records = $wpdb->get_results( 'SELECT * FROM '. $csv['sql']['tablename'] .' WHERE ecipostid IS NULL OR ecipostid = 0 LIMIT '. $posts .'' );

    // end here if no records found but report it also
        // if( !$records )  wtgcsv_notice
    
    // initilize variables
    $new_posts = 0;
    $adopted_posts = 0;
    $void_records = 0;
    $invalid_records = 0;  
    $fault_occured = 0;// counted times the loop cannot finish the whole script   
                
    // begin looping through all records
    foreach( $records as $r ){
        
        // store record id if debugging so that we know where scripts ends on failure
            // $wtgcsv_debugmode_strict
        
        // run adoption check (on a single value from selected column i.e product id)
            // wtgcsv_create_posts_adoption();
                // if a post is adopted there is no need to continue this script
            
        // build array using record - info can be added then stored for debugging - is also easier for multiple table                
            // wtgcsv_build_record_array();
        
        // modify values in record array based on users rules
            // $record_array = wtgcsv_modify_record_array();
            
        // apply categories (get existing, create or a mix of both)
            // $category_array = wtgcsv_category_setup($record_array); ### old eci_categorise()
        
        // establish publish date
            //$my_post = eci_postdate( $csv,$my_post,$recordarray,$set,$filename );
        
        // create base post (adds all possible values that can be added at this stage)
            // $my_post = wtgcsv_create_posts_draft($category_array); ## eci_createbasepost(date("Y-m-d H:i:s"),gmdate("Y-m-d H:i:s"),$designarray,'pending',$recordarray,$csv)  
                // do this in draft function eci_conditions_postauthor_return($recordarray,$csv);
                    // also create the title using actual data straight away so the initial url does not get changed
                        
                       //    if( !isset( $my_post['post_name'] ) ){       $my_post['post_name'] = sanitize_title( $my_post['post_title'] );
                           // duplication check using title before draft created  $duplicate = eci_duplicateposts( $csv,$my_post,$filename,$set );  return boolean and increase duplicate counter
                            
                            //  using final publish date - make final decision on post status (users selected or future for schedule posts)
                                   // $my_post = eci_poststatus( $csv,$my_post,$pro );
                                          //  eci_postbuildmeta( $my_post['ID'], $filename, $record->eciid, $csv['sql']['tablename'] ); 
                                                 //                                         // update this record with its matching post id in the project database table
                                       // $wpdb->query('UPDATE '. $csv['sql']['tablename'] .'
                                        //SET ecipostid = '.$my_post['ID'].',eciapplied = NOW()
                                        //WHERE eciid = '.$record->eciid.'');
                                                                               // update post - apply users selected status - ID returned on success
                                        //$my_post['post_status'] = $csv['poststatus'];
                                        //$my_post['ID'] = wp_update_post( $my_post );

                                        // update project progress if success
                                        //if( $my_post['ID'] )
                                        //{
                                        //        ++$pro[$filename]['postscreated'];
                                        //        ++$progress['createsuccess'];
                                        //}
                                        //elseif( !$my_post['ID'] )
                                        //{
                                        //        eci_log( 'Create Post Fail','No post id returned by update',
                                      //
                                        //}
                                        
                                         
        // store last publish date used
            // for incrementing etc, use publish date from $my_post to store                                
                                        
        // end here if there is a failure creating the draft post
            // if( !$my_post )  wtgcsv_notice(create persistent message if not manual)   

        // do tags, $my_post = eci_populatemypost_specialfunctions( $my_post,$recordarray,$csv,$set );
        
        // populate post content
            // $my_post = eci_populatedesign( $designarray, $recordarray, $csv, $filename,$pro );
                  // conditions content replace  $my_post = eci_conditionreplace( $my_post, $csv );
                                        
        // add custom fields and meta values                        
            // eci_addcustomfields( $my_post['ID'],$recordarray,$csv,$filename,$set,$csv['format']['titles'],$csv['format']['titles_sql'],$csv['format']['tokensymbols'] );
            // populate seo related meta fields  eci_seo($csv,$recordarray,$designarray,$my_post['ID']);
                                     
        // populate taxonomy
            //  eci_populatetaxonomy($recordarray,$csv,$filename,$set,$my_post['post_type'],$my_post['ID']);
        
        // create thumbnail/featured image attachment if activated ( wordpress post image )
            // eci_isthumbnailneeded( $csv,$recordarray,$my_post['ID'] );                                       

        // apply url cloaking
            // $my_post = eci_applyurlcloaking( $csv,$recordarray,$my_post );
            
        // put the post id into variable for returning as the $my_post object is destroyed
        $post_id = $my_post['ID'];
        
        // if a single post has been requested do not unset variables, if many have then unset them
        if( $posts_target > 1 ){unset($my_post);}
        
        // clear cache                   
        $wpdb->flush();
        
    }// end for each record
    
    // if a single post is created add specific post information too the output                          

    /*
    if( !isset( $my_post['tags_input'] ) )
    {
            $my_post['tags_input'] = 'No Tags Added';
    }

    $singlepost = '<h4>Post Details</h4>
    Title: '.$my_post['post_title'].'<br />
    ID: '.$my_post['ID'].'<br />
    Publish Date: '.$my_post['post_date'].'<br />
    Tags: '.$my_post['tags_input'].'<br />';

     $args = array(
    'orderby'            => 'name',
    'order'              => 'ASC',
    'show_last_update'   => 0,
    'style'              => 'list',
    'show_count'         => 1,
    'hide_empty'         => 0,
    'use_desc_for_title' => 1,
    'include'            => $my_post['post_category'],
    'hierarchical'       => true,
    'title_li'           => __( '<h4>Categories</h4>' ),
    'show_option_none'   => __('No categories'),
    'number'             => NULL,
    'echo'               => 0,
    'depth'              => 99,
    'taxonomy'           => 'category' );

    $singlepost .= ''.wp_list_categories( $args ).'';
    $singlepost    .= '<h4>Featured Image</h4>';
    if ( has_post_thumbnail( $my_post['ID'] ) )
    {
            $image = wp_get_attachment_image_src( get_post_thumbnail_id( $my_post['ID'] ), 'single-post-thumbnail' );
            $singlepost    .= $image[0];
    }
    else
    {
            $singlepost    .= 'No Featured Image';
    }
    
    $singlepost    .= '<h4>Post Content Dump</h4>
    '.$my_post['post_content'].'<br />';
        */           
                  
    // return last post ID - only really matters for testing or single post create requests
    return $post_id;
}       
    
/**
* Creates post creation project
* @returns boolean false on fail and project code on success
* @returns string, $project_array['code'] if success
*/
function wtgcsv_create_post_creation_project($project_name,$projecttables_array,$mapping_method){
    // initialize a new post creation project array
    $project_array = wtgcsv_initialize_postcreationproject_array($project_name);

    // generate a unique project code
    $project_array['code'] = 'pro' . wtgcsv_create_code();
    $project_array['mappingmethod'] = $mapping_method;

    // add tables
    $tablecounter = 0; 
    foreach( $projecttables_array as $key => $table_name ){
        $project_array['tables'][$tablecounter] = $table_name; 
        ++$tablecounter;   
    }
    
    // create option record for project
    $createoptionrecord_result = wtgcsv_update_option_postcreationproject($project_array['code'],$project_array);
    if($createoptionrecord_result === false){
        return false;
    }else{
        $save_result = wtgcsv_update_option_postcreationproject_list_newproject($project_array['code'],$project_name);
        if($save_result === false){
            return false;    
        }else{
            return $project_array['code'];
        }    
    }
    return false;            
}

/**
* Returns an array holding the default values for a post creation project array
* 
* @param mixed $project_name
*/
function wtgcsv_initialize_postcreationproject_array($project_name){
    $project_array = array();
    $project_array['name'] = $project_name;
    $project_array['type'] = 'post';// post, user, comment, media, custom i.e. ticket,question    
    $project_array['tables'] = array();
    
    // statistics
    $project_array['stats']['creationevents'] = 0;
    $project_array['stats']['updateevents'] = 0;    
    $project_array['stats']['postscreated'] = 0;    
    $project_array['stats']['postsupdated'] = 0;
    $project_array['stats']['pagescreated'] = 0;
    $project_array['stats']['pagesupdated'] = 0;
    
    // drip feeding settings
    $project_array['drip']['rate']['hourly'] = 10;// target of 10 items created per hour and no more    
    
    // schedule settings - allowed days
    $project_array['drip']['days']['mon'] = true;
    $project_array['drip']['days']['tue'] = true;    
    $project_array['drip']['days']['wed'] = true;
    $project_array['drip']['days']['thu'] = true;
    $project_array['drip']['days']['fri'] = true;
    $project_array['drip']['days']['sat'] = true;
    $project_array['drip']['days']['sun'] = true;
    $project_array['drip']['days']['sun'] = true;
    
    // scheduled settings - allowed hours
    $project_array['drip']['hours']['1'] = true;    
    $project_array['drip']['hours']['2'] = true;
    $project_array['drip']['hours']['3'] = true;
    $project_array['drip']['hours']['4'] = true;
    $project_array['drip']['hours']['5'] = true;
    $project_array['drip']['hours']['6'] = true;
    $project_array['drip']['hours']['7'] = true;
    $project_array['drip']['hours']['8'] = true;
    $project_array['drip']['hours']['9'] = true;
    $project_array['drip']['hours']['10'] = true;
    $project_array['drip']['hours']['11'] = true;
    $project_array['drip']['hours']['12'] = true;
    $project_array['drip']['hours']['13'] = true;
    $project_array['drip']['hours']['14'] = true;
    $project_array['drip']['hours']['15'] = true;
    $project_array['drip']['hours']['16'] = true;
    $project_array['drip']['hours']['17'] = true;
    $project_array['drip']['hours']['18'] = true;
    $project_array['drip']['hours']['19'] = true;
    $project_array['drip']['hours']['20'] = true; 
    $project_array['drip']['hours']['21'] = true;
    $project_array['drip']['hours']['22'] = true;
    $project_array['drip']['hours']['23'] = true;
    $project_array['drip']['hours']['24'] = true;
                                        
    return $project_array;                
}

/**
* Update a single post
* 
* @param mixed $filename
* @param mixed $csv
* @param mixed $output
* @param mixed $postid
* @param mixed $eciid
*/
function wtgcsv_post_updatepost($filename,$csv,$output,$postid,$eciid)
{                        
    if( !isset( $csv['updating']['ready'] ) || $csv['updating']['ready'] != true )
    {
        eci_mes('Project Updating Not Configured','You need to configure updating on the Project Configuration screen
        to run updating. The main thing is to tell the plugin what CSV column holds your datas key. The key data is used to
        pair CSV file rows with database records. ');                
    }
    elseif(!isset( $set['postupdating'] ) || $set['postupdating'] != 'Yes' )
    {
        eci_mes('Plugin Global Updating Disabled','Please go to the Settings page and activate Global Post Updating
        under the Advanced Settings tab. This setting allows us to disable updating for any number of projects, quickly
        and it is disabled by default.');
    }
    else 
    {
        global $wpdb;
    
        // get the post
        query_posts( 'p='.$postid.'' );
    
        if ( have_posts() ) : while ( have_posts() ) : the_post();
            
            // get the current post object
            global $post;
            
            // call update the post and pass current post object too it
            $result = eci_updatethepost($post);    
            
            ###### WE NEED A GOOD WAY TO DETECT IF UPDATE HAPPENED
            eci_mes('Post Updated','Your post with ID '.$postid.' and record ID '.$eciid.' has been updated');
            
        
        endwhile; else:
            
            if($output == true)
            {
                eci_mes('Post Not Found','Could not get a post with ID '.$postid.', no update has been carried out');
            }
        
        endif;
    }
}


/**
 * Create post title. 
 * Replaces tokens in giving value with any matching giving column title values
 * 
 * @param string $value (value of tokens to build a string of values)
 * @param array $record_array (the current record being used to create post)
 * @param array $titles (must pass the sql prepared array)
 * @param array $tokensymbols 
 */
function wtgcsv_post_strreplacetokens_titlesloop($value,$record_array,$columns,$columns_sql,$tokensymbols)
{
    foreach($columns as $key=>$col)
    {
        // use original column title to create token
        $token = $tokensymbols[$key].$col.$tokensymbols[$key];
        // use sql column title to extract data value
        $value = str_replace($token, $record_array[ $columns_sql[$key] ], $value);
    }

    return $value;
}


/**
 * Returns post status based on giving criteria
 * makes final decisision on status based on generated post publish date
 * 
 * @param array $csv
 * @param array $my_post (wordpress post object)
 * @return $my_post (wordpress post object)
 * @link http://www.webtechglobal.co.uk/blog/php-mysql/strange-problem-with-date-function
 */
function wtgcsv_post_poststatus_calculate( $csv,$my_post ){
    $timenow = strtotime( date("Y-m-d H:i:s") );
    $timeset = strtotime( $my_post['post_date'] );

    if( $timeset > $timenow )// if posts time is greater than current
    {
        $my_post['post_status'] = 'future';
    }
    elseif( $timeset < $timenow )// if posts time is less than current
    {
        $my_post['post_status'] = $csv['poststatus'];
    }
    elseif( $timeset == $timenow )// if matching times
    {
        $my_post['post_status'] = $csv['poststatus'];
    }

    return $my_post;
}

/**
* Establishes the post date
* 
* @param mixed $csv
* @param string $my_post
* @param mixed $recordarray
* @param mixed $set
* @param mixed $filename
* @return string
* 
* @todo ECI FUNCTIONS TO BE CHANGED
*/
function wtgcsv_post_publishdate( $csv,$my_post,$recordarray,$set,$filename )
{       

             
    if( isset( $csv['specials']['col']['dates_col'] ) && isset( $csv['specials']['state']['dates_col'] ) 
    && $csv['specials']['state']['dates_col'] == 'On' 
    && isset( $csv['datemethod'] ) && $csv['datemethod'] == 'data' )
    {        
        // if string to time could not be done, output some help information
        if ( ( $timestamp = strtotime( $recordarray[ $csv['specials']['col']['dates_col'] ] ) ) === false ){} 
        else 
        {
            $my_post['post_date'] =  date("Y-m-d H:i:s", $timestamp);
            $my_post['post_date_gmt'] = gmdate("Y-m-d H:i:s", $timestamp );
        }
    }
    elseif( isset( $csv['datemethod'] ) && $csv['datemethod'] == 'random' )
    {    
        $time = rand(
        mktime( 23, 59, 59, $set['rd_monthstart'], $set['rd_daystart'], $set['rd_yearstart'] ),
        mktime( 23, 59, 59, $set['rd_monthend'], $set['rd_dayend'], $set['rd_yearend'] ) );// end of rand 
        $my_post['post_date'] = date("Y-m-d H:i:s", $time);
        $my_post['post_date_gmt'] = gmdate("Y-m-d H:i:s", $time);
    }        
    elseif( isset( $csv['datemethod'] ) && $csv['datemethod'] == 'increment' )
    {
        if( isset( $pro['lastpublishdate'] ) )
        {
            $lastdate = strtotime( $pro[$filename]['lastpublishdate'] );
            $increment = rand( $set['incrementstart'], $set['incrementend'] );
            $time = $lastdate + $increment;
            $my_post['post_date'] = date("Y-m-d H:i:s", $time);    
            $my_post['post_date_gmt'] = gmdate("Y-m-d H:i:s", $time);
        }
        if( !isset( $pro['lastpublishdate'] ) )
        {
            $time = mktime( 23, 59, 59, $set['incrementmonthstart'], $set['incrementdaystart'], $set['incrementyearstart'] );
            $my_post['post_date'] = date("Y-m-d H:i:s", $time);
            $my_post['post_date_gmt'] = gmdate("Y-m-d H:i:s", $time);
        }
    }
    elseif( isset( $csv['datemethod'] ) && $csv['datemethod'] == 'default' )
    {
        $my_post['post_date'] = date( "Y-m-d H:i:s",time() );
        $my_post['post_date_gmt'] = gmdate( "Y-m-d H:i:s",time() );
    }                    
                    
    return $my_post;
}

# Ensures Pre-Made Tags Are Valid 
# TODO: ECI FUNCTIONS NOT COMPLETE
function wtgcsv_post_createtags_premade($str) {
    // split passed value - expecting a comma delimited string of values including phrases
    $splitstr = @explode(",", $str);
    $new_splitstr = array();
    foreach ($splitstr as $spstr) 
    {
        // ensure individual value is not already in tags array
        if (strlen($spstr) > 2 && !(in_array(strtolower($spstr), $new_splitstr))){$new_splitstr[] = strtolower($spstr);}
    }
    return @implode(", ", $new_splitstr);
}

/**
* Inserts a new content template as post type wtgcsvcontent
*/
function wtgcsv_insert_post_contenttemplate(){
    // no ID exists, create a new template
    $post = array(
      'comment_status' => 'closed',
      'ping_status' => 'closed',
      'post_author' => get_current_user_id(),
      'post_content' => $_POST['wtgcsv_wysiwyg_editor'],
      'post_status' => 'publish', 
      'post_title' => $_POST['wtgcsv_templatename'],
      'post_type' => 'wtgcsvcontent'
    );  
                
    return wp_insert_post( $post, true );// returns WP_Error on fail                
}

/**
* Saves new title template design
* 
* @param mixed $_POST
*/
function wtgcsv_insert_titletemplate($titletemplate_name,$titletemplate_title){
    // no ID exists, create a new template
    $post = array(
      'comment_status' => 'closed',
      'ping_status' => 'closed',
      'post_author' => get_current_user_id(),
      'post_content' => $titletemplate_title,
      'post_status' => 'publish', 
      'post_title' => $titletemplate_name,
      'post_type' => 'wtgcsvtitle'
    );  
                
    return wp_insert_post( $post, true );// returns WP_Error on fail     
}

/**
* Returns the giving projects default post type.
* @returns boolean false if no default post type set or fault
*/
function wtgcsv_get_project_defaultposttype($project_code){
    $project_array = wtgcsv_get_project_array($project_code);
    if(isset( $project_array['defaultposttype'] )){
        return $project_array['defaultposttype'];
    }else{
        return false;
    } 
    return false;   
}

/**
* Changes the default post type for giving project
*/
function wtgcsv_update_project_defaultposttype($project_code,$default_post_type){
    $project_array = wtgcsv_get_project_array($project_code);
    $project_array['defaultposttype'] = $default_post_type;
    return wtgcsv_update_option_postcreationproject($project_code,$project_array);    
}
?>
