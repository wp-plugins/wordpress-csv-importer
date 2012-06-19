<?php
/**
* Advanced post creation script - used in paid edition only, longer processing time due
* to the number of advanced functions that parse data. 
* 
* @param mixed $project_code
* @param mixed $posts_target
* @param mixed $request_method
*/
function wtgcsv_create_posts_advanced($project_code,$posts_target,$request_method){

    global $wpdb;

    // remove hooks/filters meant for manual actions post actions only
        //remove_filter('content_save_pre', 'eci_editpostsync');### TODO: edit post sync to be complete first
             
    // get project array
    $project_array = wtgcsv_get_project_array($project_code);

    // increase events counter for campaign
        //++$pro[$filename]['events'];

    // get data
    $records = wtgcsv_sql_query_unusedrecords_singletable($project_array['tables'][0],$posts_target);
    if(!$records){
        if($request_method == 'manual'){
            wtgcsv_notice('No posts were created as your project does not have any unused records. No project statistics were changed either.','info','Large','No Posts Created','','echo');    
        }else{
            ### TODO:MEDIUMPRIORITY, log this event when log system complete
        }
        return false;        
    }
    
    // initilize variables
    $new_posts = 0;
    $adopted_posts = 0;
    $void_records = 0;
    $invalid_records = 0;  
    $fault_occured = 0;// counted times the loop cannot finish the whole script   
                
    // begin looping through all records
    foreach( $records as $record_array ){

        // modify values in record array based on users rules
            // $record_array = wtgcsv_modify_record_array();
        
        ### TODO check if categories are set before calling this function
        wtgcsv_categorysetup_advancedscript_normalcategories($record_array,$project_array);
        
        // set post type (function uses post type rules if any else defaults)
        if(!isset($project_array['posttyperules'])){
            $post_type = wtgcsv_establish_posttype($project_array,$record_array);    
        }

        
        // store record id if debugging so that we know where scripts ends on failure
            // $wtgcsv_debugmode_strict
        
        // run adoption check (on a single value from selected column i.e product id)
            // wtgcsv_create_posts_adoption();
                // if a post is adopted there is no need to continue this script
            
        // build array using record - info can be added then stored for debugging - is also easier for multiple table                
            // wtgcsv_build_record_array();
        

            
        // apply categories (get existing, create or a mix of both)
            // $category_array = wtgcsv_category_setup($record_array); ### old eci_categorise()
        
        // establish publish date
            //$my_post = eci_postdate( $csv,$my_post,$recordarray,$set,$filename ); 
        
        // create base post (adds all possible values that can be added at this stage)
            //wtgcsv_create_postdraft_advanced($r,$post_date,$post_date_gmt,$category_array,$project_code,$content,$title,$post_type)
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
        //$post_id = $my_post['ID'];
        
        // if a single post has been requested do not unset variables, if many have then unset them
        //if( $posts_target > 1 ){unset($my_post);}
        
        // clear cache                   
        //$wpdb->flush();
        
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
    //return $post_id;
}

### TODO: CRITICAL, to be used in advanced post creation script, makes use of multiple project tables rather than one
function wtgcsv_parse_columnreplacement_advanced($record_array,$value){
    
    // loop through record array values
    foreach( $record_array as $column => $data ){
        
        // loop through project tables so that we can complete the table-column replacement token 
            
            $value = str_replace($table . '#'. $column, $data, $value);
            
    }
    
    return $value;
}

# TODO: CRITICAL
function wtgcsv_create_postdraft_advanced($r,$post_date,$post_date_gmt,$category_array,$project_code,$content,$title,$post_type){
    
} 

/**
* Processes project rules against a record to establish post type.
* This function should only be called in argument checking post type rules exist in project array.
* 
* @param mixed $project_array
* @param mixed $record_array
* @returns post type, defaults to user selected default or post when no rules exist or rules are not applied
*/
function wtgcsv_establish_posttype($project_array,$record_array){
    
    // loop through "byvalue" rules
    foreach($project_array['posttyperules']['byvalue'] as $key => $rule){
        
        // ensure $record_array has expected column_name 
        if(isset($record_array[ $rule['column_name'] ])){
        
            if( $rule['trigger_value'] == $record_array[ $rule['column_name'] ]){
            
                return $rule['post_type'];                        
                
            }       
        }   
    }
    
    // on reaching here we must now use the user set default if any, else use post
    if(isset($project_array['defaultposttype'])){
        return $project_array['defaultposttype'];
    }else{
        return 'post';
    }                
} 

/**
* Category setup, will create categories or get existing category ID
* 1. This is the advanced version
* 2. Allows 5 levels
*/
function wtgcsv_categorysetup_advancedscript_normalcategories($record_array,$project_array){
    global $wpdb;
                
    // array to hold our final category ID's ( all are applied to post in this function )
    $finalcat_array = array();

    // count total new categories inserted into blog
    $cats_created_count = 0;
                
    // total number of categories added to $appliedcat_array
    $cats_used_count = 0;
    
    // create variables to hold each level of category ID
    $catid_levone = 0;$catid_levtwo = 0;$catid_levthree = 0;$catid_levfour = 0;$catid_levfive = 0;               
                                
    // loop three times 0 = level one and so on
    for ($col_lev = 0; $col_lev < 5; $col_lev++) {
        
        // get the current category term
        if($col_lev == 0){
            $cat_term = $record_array[$project_array['categories']['level1']['column']];    
        }elseif($col_lev == 1){
            $cat_term = $record_array[$project_array['categories']['level2']['column']];
        }elseif($col_lev == 2){
            $cat_term = $record_array[$project_array['categories']['level3']['column']];
        }elseif($col_lev == 3){
            $cat_term = $record_array[$project_array['categories']['level4']['column']];
        }elseif($col_lev == 4){
            $cat_term = $record_array[$project_array['categories']['level5']['column']];
        }        

        // set parent ID - if any
        $parent_id = 0;
        if($col_lev == 1){
            $parent_id = $catid_levone;        
        }elseif($col_lev == 2){
            $parent_id = $catid_levtwo;            
        }elseif($col_lev == 3){
            $parent_id = $catid_levthree;            
        }elseif($col_lev == 4){
            $parent_id = $catid_levfour;            
        }
        
        // determine if category term already exists, under the parent if one exists
        $cat_taxonomy_result = wtgcsv_sql_is_categorywithparent(sanitize_title($cat_term),$parent_id);
        // example return: ["term_id"]=> string(1) "3" ["name"]=> string(4) "Aone" ["parent"]=> string(1) "0"

        // did get_term_by provide a negative result, requiring category to be created with required parent
        // if level one - we only attempt to make category if NO result returned at all, the parent part can be ignored
        if(!$cat_taxonomy_result){
            
            $new_cat_id = wp_insert_term($cat_term, "category", array('description' => '', 'parent' => $parent_id));
            // array(2) { ["term_id"]=> int(80) ["term_taxonomy_id"]=> int(81) }
 
            if(!wtgcsv_is_WP_Error($new_cat_id) && $new_cat_id){
                 
                if($col_lev == 0){
                    $catid_levone = $new_cat_id['term_id'];       
                }elseif($col_lev == 1){
                    $catid_levtwo = $new_cat_id['term_id'];       
                }elseif($col_lev == 2){
                    $catid_levthree = $new_cat_id['term_id'];       
                }elseif($col_lev == 3){
                    $catid_levfour = $new_cat_id['term_id'];       
                }elseif($col_lev == 4){
                    $catid_levfive = $new_cat_id['term_id'];       
                }
                
                ++$cats_created_count;
                ++$cats_used_count;
                
            }else{
                ### TODO:HIGHPRIORITY, log error
                ### use wtgcsv_is_WP_Error if returned
            }            
                
        }elseif( $cat_taxonomy_result && $cat_taxonomy_result->parent == $parent_id ){
            
            // get_term_by returned existing category that has the giving parent
            if($col_lev == 0){
                $catid_levone = $cat_taxonomy_result->term_id;       
            }elseif($col_lev == 1){
                $catid_levtwo = $cat_taxonomy_result->term_id;       
            }elseif($col_lev == 2){
                $catid_levthree = $cat_taxonomy_result->term_id;       
            }elseif($col_lev == 3){
                $catid_levfour = $cat_taxonomy_result->term_id;       
            }elseif($col_lev == 4){
                $catid_levfive = $cat_taxonomy_result->term_id;       
            }

            ++$cats_used_count;
        }
   }

   // build final array - I have done this so that 2nd or 3rd level cannot be applied if their parent level 
   // has somehow failed to be found or created. In debugging, if a post is applied to 1st and 3rd category but not
   // the expected 2nd then this is where to begin debugging.
   ### TODO:HIGHPRIORITY, must write this so that user can apply only the last category to post
   $appliedcat_array = array();
   if($catid_levone != 0 && is_numeric($catid_levone)){
        $appliedcat_array[] = $catid_levone;      
   }
   if($catid_levtwo != 0 && is_numeric($catid_levtwo) && $catid_levone != 0 && is_numeric($catid_levone)){
        $appliedcat_array[] = $catid_levtwo;      
   }
   if($catid_levthree != 0 && is_numeric($catid_levthree) && $catid_levtwo != 0 && is_numeric($catid_levtwo)){
        $appliedcat_array[] = $catid_levthree;      
   }
   if($catid_levfour != 0 && is_numeric($catid_levfour) && $catid_levthree != 0 && is_numeric($catid_levthree)){
        $appliedcat_array[] = $catid_levfour;      
   }
   if($catid_levfive != 0 && is_numeric($catid_levfive) && $catid_levfour != 0 && is_numeric($catid_levfour)){
        $appliedcat_array[] = $catid_levfive;      
   }
            
   return $appliedcat_array;      
} 
?>