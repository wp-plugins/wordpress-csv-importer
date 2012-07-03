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

    // if project has no rules for dynamically changing title template, we can load content prior to loop, thus loading it once
    ### TODO: HIGHPRIORITY, add argument to check if title template rules exist, if they do not then load default template now
    $title_template = 'Content template not used, please ensure your project has a default content template';   
    if(!isset($project_array['default_titletemplate_id'])){
        $title_template = 'No Default Title Template Selected';    
    }else{
        $title_template = wtgcsv_get_titletemplate_design($project_array['default_titletemplate_id']);    
    }

    // if project has no rules for dynamically changing content template, we can load content prior to loop, thus loading it once
    $content_template = 'Content template not used, please ensure your project has a default content template';
    if(!isset($_POST['contenttemplaterules'])){
        if(!isset($project_array['default_contenttemplate_id'])){
            $content_template = 'No Default Content Template Selected! Please create and select a content design so the plugin knows where to put your data within post content.';
        }else{
            $content_template = wtgcsv_get_contenttemplate_design($project_array['default_contenttemplate_id']);    
        }
    }      
    
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
        
        // store record id if debugging so that we know where scripts ends on failure
        // $wtgcsv_debugmode_strict
            
        // begin $my_post array, we will udpate it with the draft post details
        $my_post = array();
 
        // establish publish date - returns $my_post array with added dates
        $my_post = wtgcsv_post_publishdate_advanced( $my_post,$record_array,$project_code,$project_array );
        
        // generate tags
        if(isset($project_array['tags']['default']) || isset($project_array['tags']['generator'])){
            $my_post = wtgcsv_post_tags($my_post,$record_array,$project_code,$project_array);
        }
           
        // check if categories are set before calling this function
        if(isset($project_array['categories'])){
  
            // if a default is set and no levels set we can apply the default now
            if(isset($project_array['categories']['default']) && !isset($project_array['categories']['level1'])){
            
                $my_post = array($project_array['categories']['default']);                    
                
            }else{
                
                $my_post = wtgcsv_categorysetup_advancedscript_normalcategories($record_array,$project_array);
            
            }
            
        }// else posts will be put into blogs default

        // set post type (function uses post type rules if any else defaults)
        if(!isset($project_array['posttyperules'])){
            $post_type = wtgcsv_establish_posttype($project_array,$record_array);    
        }
                
        // if project has dynamic title template rules, process rules now
        ### TODO:HIGHPRIORITY, add argument for checking title template rule exist in project array
            if(!isset($project_array['default_titletemplate_id'])){
                $title_template = 'No Default Title Template Selected';    
            }else{
                $title_template = wtgcsv_get_titletemplate_design($project_array['default_titletemplate_id']);    
            }
        
        // if project has dynamic content template rules, process rules now
        if(!isset($_POST['contenttemplaterules'])){
            if(!isset($project_array['default_contenttemplate_id'])){
                $content_template = 'No Default Content Template Selected! Please create and select a content design so the plugin knows where to put your data within post content.';
            }else{
                $content_template = wtgcsv_get_contenttemplate_design($project_array['default_contenttemplate_id']);    
            }
        } 
         
        // parse and put the template variables into the $my_post array
        $my_post['post_title'] = wtgcsv_parse_columnreplacement_advanced($record_array,$project_array,$title_template);
        $my_post['post_content'] = wtgcsv_parse_columnreplacement_advanced($record_array,$project_array,$content_template);
      
        // create base post (adds all possible values that can be added at this stage)
        $my_post = wtgcsv_create_postdraft_advanced($my_post,$record_array,$project_code,$my_post['post_content'],$my_post['post_title'],$post_type);
        
        // add custom fields and meta values (basic array only in this function)
        wtgcsv_post_add_metadata_advanced($my_post['ID']); 
                           
                     

                                     
        // populate taxonomy
            //  eci_populatetaxonomy($recordarray,$csv,$filename,$set,$my_post['post_type'],$my_post['ID']);
        
        // create thumbnail/featured image attachment if activated ( wordpress post image )
            // eci_isthumbnailneeded( $csv,$recordarray,$my_post['ID'] );                                       

        // apply url cloaking
            // $my_post = eci_applyurlcloaking( $csv,$recordarray,$my_post );
            
        // put the post id into variable for returning as the $my_post object is destroyed
        $post_id =  $my_post['ID'];
        
        // if more than 1 post requested unset $my_post to prevent its values contaminating new object
        if( $posts_target > 1 ){unset($my_post);}
        
        // clear cache                   
        //$wpdb->flush();
        
    }// end for each record
    
    // if a single post is created add specific post information too the output                          
  
                  
    // return last post ID - only really matters for testing or single post create requests
    return $post_id;
}

/**
* Advanced tag generator, not used in free edition.
* You must ensure [tag][generator] array is set in project array before calling this function. 
* 
* @param mixed $my_post
* @param mixed $record_array
* @param mixed $project_code
* @param mixed $project_array
* @return string
*/
function wtgcsv_post_generate_tags_advanced($my_post,$record_array,$project_code,$project_array){
 
    // get all text to be used for generating tags (if single column or multiple)
    $tag_string = '';
    
    // loop through selected columns - put each column data value into our $text 
    foreach($project_array['tags']['generator']['data'] as $key => $data ){
        $tag_string .= ' ' . $record_array[$data['column']];
    }
    
    // remove multiple spaces, returns, tabs, etc.
    $tag_string = preg_replace("/[\n\r\t\s ]+/i", " ", $tag_string); 
        
    // replace full stops and spaces with a comma (command required in explode)
    $tag_string = str_replace(array("   ","  "," ",".","..","...",'"',"'"),",", $tag_string );
    
    // exlode $tag_string
    $tag_array = explode(",", $tag_string);
    
    // remove duplicates            
    $new_tag_array = array();
    foreach ($tag_array as $single_tag) {
        
        // does user want all characters to be lowercase? Do this first before comparison
        ### TODO:MEDIUMPRIORITY, create setting to allow all characters lowercase at this point, before comparison
        //$single_tag = strtolower($single_tag);
           
        if (strlen($single_tag) > 2 && !(in_array(strtolower($single_tag), $new_tag_array))){
            $new_tag_array[] = $single_tag;
        }
    }                   
    
    // loop through the values we have left and do further cleaning
    // 1. Remove numeric values if user does not want them
    // 2. Remove individual tags under a specific length ### TODO:LOWPRIORITY
    // 3. Remove user excluded terms
    foreach ( $new_tag_array as $single_tag ) {
        
        // remove numerics
        ### TODO:HIGHPRIORITY
        
        // remove values under specific length (default 2 characters)
        ### TODO:HIGHPRIORITY

    }    

    // implode string (makes array a string again)
    $tag_string = implode(",", $new_tag_array);
    
    // prepend users selected value i.e. they may want product title to be a tag phrase, or maybe a category with two words
    ### TODO:LOWPRIORITY, create the setting for this, allow user to select column for prepend value
    
    // shorten string of tags if longer than maximum designed
    $tag_string = wtgcsv_truncatestring($tag_string,50);### TODO, HIGHPRIORITY, change the integer to users setting
     
    $my_post['tags_input'] = $tag_string; 
    
    return $my_post; 
}


/**
* Adds meta to posts based on user project configuration.
* This is the advanced function which makes use of rules in the full edition.
* 
* @param mixed $my_post
*/
function wtgcsv_post_add_metadata_advanced($post_ID){
    
    // process basic custom fields first
    if(isset($project_array['custom_fields']['basic'])){
     
        foreach($project_array['custom_fields']['basic'] as $key => $cfrule){
            add_metadata('post', $my_post['ID'], $cfrule['meta_key'], $record_array[$cfrule['column_name']]);      
        }

    }
    
    // are advanced custom fields set
    if(isset($project_array['custom_fields']['advanced'])){
    
        
    }    
}

/**
* Replace column replacement tokens with data in any giving value.
* This is the advanced function that loops through multiple tables also.
* 
* @param mixed $record_array
* @param mixed $project_array
* @param mixed $value
* @return mixed
*/
function wtgcsv_parse_columnreplacement_advanced($record_array,$project_array,$value){
    
    // loop through record array values
    foreach( $record_array as $column => $data ){
        
        // loop through project tables so that we can complete the table-column replacement token 
        foreach( $project_array['tables'] as $key => $table ){    
            $value = str_replace($table . '#'. $column, $data, $value);
        }
            
    }
    
    return $value;
}

/**
* Create a draft post, used in full edition only
* 
* @param int|WP_Error $my_post
* @param mixed $record_array
* @param mixed $project_code
* @param mixed $content
* @param mixed $title
* @param mixed $post_type
* @return int|WP_Error
*/
function wtgcsv_create_postdraft_advanced( $my_post,$record_array,$project_code,$content,$title,$post_type ){

    $my_post['post_author'] = 1;    
    $my_post['post_date'] = date("Y-m-d H:i:s", time());    
    $my_post['post_date_gmt'] = gmdate("Y-m-d H:i:s", time());
    $my_post['post_title'] = $title;
    $my_post['post_content'] = $content;
    $my_post['post_status'] = 'draft';// set to draft until end of post creation processing
    $my_post['post_type'] = 'post';// free edition offers no features to change this, users can change it manually here if they wish
    $my_post['ID'] = wp_insert_post( $my_post );
    if( !$my_post['ID'] ){
        return false;
        ### TODO:MEDIUMPRIORITY, log this    
    }
    
    // add default meta/custom fields
    wtgcsv_post_default_projectmeta($my_post['ID'],$project_code);
                           
    return $my_post;                                          
} 

# TODO decide posts publish date
function wtgcsv_post_publishdate_advanced($my_post,$record_array,$project_code,$project_array){ 
                                         
    // is a date method set, if not we return the current time
    if(!isset($project_array['currentmethod'])){
        $my_post['post_date'] =  date("Y-m-d H:i:s", time());
        $my_post['post_date_gmt'] = gmdate("Y-m-d H:i:s", time() );           
    }else{
        
        // call the require function for specific method  
        if($project_array['currentmethod'] == 'increment'){

            // establish minutes increment
            $increment = rand( $project_array['dates']['increment']['short'], $project_array['dates']['increment']['long'] );    

            // establish start time and end time
            $start_time = strtotime($project_array['dates']['increment']['start']);// updated with latest post creation date and time

            // add increment too start time to establish publish time
            $publish_time = $start_time + $increment; 
  
            $my_post['post_date'] =  date("Y-m-d H:i:s", $publish_time );
            $my_post['post_date_gmt'] = gmdate("Y-m-d H:i:s", $publish_time ); 
            
            // update project array with latest date
            $project_array['dates']['increment']['start'] = date('Y-m-d H:i:s',$publish_time); 

        }elseif($project_array['currentmethod'] == 'random'){

            // establish start time and end time
            $start_time = strtotime($project_array['dates']['random']['start']);
            $end_time = strtotime($project_array['dates']['random']['end']);            
            
            // make random time between our start and end
            $publish_time = rand( $start_time, $end_time );  
  
            $my_post['post_date'] =  date("Y-m-d H:i:s", $publish_time );
            $my_post['post_date_gmt'] = gmdate("Y-m-d H:i:s", $publish_time );            

            // update project array with latest date
            $project_array['dates']['random']['start'] = date('Y-m-d H:i:s',$publish_time);
            
        }
        
    } 

    // update the start time with the latest published date
    wtgcsv_update_option_postcreationproject($project_code,$project_array);
            
    return $my_post;                              
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
    if(isset($project_array['posttyperules'])){
        foreach($project_array['posttyperules']['byvalue'] as $key => $rule){
            
            // ensure $record_array has expected column_name 
            if(isset($record_array[ $rule['column_name'] ])){
            
                if( $rule['trigger_value'] == $record_array[ $rule['column_name'] ]){
                
                    return $rule['post_type'];                        
                    
                }       
            }   
        }
    }
    
    // on reaching here we must now use the user set default if any, else use post
    if(isset($project_array['defaultposttype'])){
        return $project_array['defaultposttype'];
    }
    
    return 'post';          
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
                       
    // loop up to five times times 0 = level one and so on
    for ($col_lev = 0; $col_lev < 5; $col_lev++) {
        
        $actual_level = $col_lev + 1;// $col_lev starts at 0, the array starts at 1 for ease of understanding it
        
        // get the current category term
        if(isset($project_array['categories']['level'.$actual_level]['column']) ){
            $cat_term = $record_array[$project_array['categories']['level'.$actual_level]['column']]; 
        }
        
        // if mapping exists for level, check if the term matches a mapping rule
        if(isset( $project_array['categories']['level'.$actual_level]['mapping'][$cat_term] )){
            
            // user set an existing category id - apply it to the correct level variable
            if($col_lev == 0){
                $catid_levone = $project_array['categories']['level'.$actual_level]['mapping'][$cat_term];       
            }elseif($col_lev == 1){
                $catid_levtwo = $project_array['categories']['level'.$actual_level]['mapping'][$cat_term];       
            }elseif($col_lev == 2){
                $catid_levthree = $project_array['categories']['level'.$actual_level]['mapping'][$cat_term];       
            }elseif($col_lev == 3){
                $catid_levfour = $project_array['categories']['level'.$actual_level]['mapping'][$cat_term];       
            }elseif($col_lev == 4){
                $catid_levfive = $project_array['categories']['level'.$actual_level]['mapping'][$cat_term];       
            }     
            
        }else{

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
                
            }// if no existing taxonomy result
        }// if mapping is set
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

/**
* Easy Configuration Questions
* 
* @link http://www.erichynds.com/jquery/jquery-ui-multiselect-widget/
*/
function wtgcsv_easy_configuration_questionlist(){
    global $wtgcsv_easyquestions_array;
             
    // count number of each type of question added for adding ID value to script
    $singles_created = 0;// count number of single answer questions added (radio button)
    $multiple_created = 0;
    $text_created = 0;
    $slider_created = 0;
    $noneactive = 0; 
            
    foreach($wtgcsv_easyquestions_array as $key => $question){
        
        if($question['active'] != true){
        
            ++$slider_created;
            
        }else{
                
            if($question['type'] == 'single'){?>
                        
                <script type="text/javascript">
                $(function(){
                    $("select#<?php echo WTG_CSV_ABB.'single'.$singles_created;?>").multiselect({
                       // TODO: LOWPRIORITY, get single select working, it still shows checkboxes instead of a radio button approach
                       selectedList: 10,
                       minWidth: 600,
                       multiple: false,
                       header: "Please select a single option",
                       noneSelectedText: "Please select a single option",
                       selectedList: 1   
                    });
                });
                </script>

                <?php
                // build list of option values
                $opt_array = explode(",", $question['answers']);
                $optionlist = '';
                foreach($opt_array as $key => $optanswer){
                    $optionlist .= '<option value="'.$optanswer.'"> '.$optanswer.' </option> ';     
                } 
           
                echo wtgcsv_notice($question['question'] . ' ' . wtgcsv_link('?',$question['helpurl'],'','_blank','','return','Click here to get more help for this question') .'
                <p> 
                    <select id="'.WTG_CSV_ABB.'single'.$singles_created.'" title="Please click on a single option" multiple="multiple" name="example-basic" class="wtgcsv_multiselect_menu">
                        '.$optionlist.'
                    </select>
                </p>','question','Small','','','return');?>
                
                <script type="text/javascript">
                $("select#<?php echo WTG_CSV_ABB.'single'.$singles_created;?>").multiselect().multiselectfilter();
                </script>

                <?php ++$singles_created;
                
            }elseif($question['type'] == 'multiple'){?>
                   
                <script type="text/javascript">
                $(function(){
                    $("select#<?php echo WTG_CSV_ABB.'multiple'.$multiple_created;?>").multiselect({
                        selectedList: 10,
                        minWidth: 600,
                        header: "Please select one or more options",
                        noneSelectedText: "Please select one or more options",
                    });
                });
                </script>

                <?php 
                // build list of option values
                $opt_array = explode(",", $question['answers']);
                $optionlist = '';
                foreach($opt_array as $key => $optanswer){
                    $optionlist .= '<option value="'.$optanswer.'"> '.$optanswer.' </option> ';     
                } 
                             
                echo wtgcsv_notice($question['question'] . ' ' . wtgcsv_link('?',$question['helpurl'],'','_blank','','return','Click here to get more help for this question') .'
                <p> 
                    <select id="'.WTG_CSV_ABB.'multiple'.$multiple_created.'" title="You may select multiple options" multiple="multiple" name="example-basic" class="wtgcsv_multiselect_menu">
                        '.$optionlist.'
                    </select>
                </p>','question','Small','','','return');?>
                
                <script type="text/javascript">
                $("select#<?php echo WTG_CSV_ABB.'multiple'.$multiple_created;?>").multiselect().multiselectfilter();
                </script>
                
                <?php ++$multiple_created;
                
            }elseif($question['type'] == 'text'){?>
                
                <script>
                $(function() {
                    
                        <?php
                        $opt_array = explode(",", $question['answers']);
                        $optionlist = 'var availableTags = [';
                        $first = true;
                        foreach($opt_array as $key => $optanswer){
                            if($first == false){$optionlist .= ',';} 
                            $optionlist .= '"'.$optanswer.'"';
                            $first = false;   
                        }
                        $optionlist .= '];';
                        echo $optionlist;?>
                    
                    $( "#<?php echo WTG_CSV_ABB . 'text'.$text_created;?>" ).autocomplete({
                        source: availableTags
                    });
                });
                </script>

                <?php  
                echo wtgcsv_notice($question['question'] . ' ' . wtgcsv_link('?',$question['helpurl'],'','_blank','','return','Click here to get more help for this question') .'
                <div class="ui-widget">
                    <p>
                        <label for="'. WTG_CSV_ABB . 'text'.$text_created .'">Tags: </label>
                        <input id="'. WTG_CSV_ABB . 'text'.$text_created .'" />
                    </p>
                </div>','question','Small','','','return');?>

                <?php ++$text_created;
            }elseif($question['type'] == 'slider'){?>

                <style>
                #demo-frame > div.demo { padding: 10px !important; };
                </style>
                <script>
                $(function() {
                    $( "#<?php echo WTG_CSV_ABB;?>slider-range-min<?php echo $slider_created;?>" ).slider({
                        range: "min",
                        value: 20,
                        min: 1,
                        max: 5000,
                        slide: function( event, ui ) {
                            $( "#amount<?php echo $slider_created;?>" ).val( "" + ui.value );
                            //                   ^ prepend value
                        }
                    });
                                      
                    $( "#amount<?php echo $slider_created;?>" ).val( "" + $( "#<?php echo WTG_CSV_ABB;?>slider-range-min<?php echo $slider_created;?>" ).slider( "value" ) );
                    //                   ^ prepend value
                });
                </script>

                <?php  
                echo wtgcsv_notice($question['question'] . ' ' . wtgcsv_link('?',$question['helpurl'],'','_blank','','return','Click here to get more help for this question') .'
                <p> 
                    <div id="wtgcsv_slider-range-min'. $slider_created .'"></div> 
                </p>
                <p>
                    <label for="amount'. $slider_created .'">Maximum price:</label>
                    <input type="text" id="amount'. $slider_created .'" style="border:0; color:#f6931f; font-weight:bold;" />
                </p>','question','Small','','','return');
                
                ++$slider_created;
            }
        }//
    }// end for each question
        
    // output total number of questions if developer information active
    $wtgcsv_is_dev = true;// ### TODO:HIGHPRIORITY, change this to the global variable    
    if($wtgcsv_is_dev){
        
        echo '<h4>Total Questions</h4>';
        echo '<p>';
        $total = $singles_created + $multiple_created + $text_created + $slider_created;
        echo 'All Questions: '.$total;// count number of single answer questions added (radio button)
        echo '<br />';                       
        echo 'Single Answer Questions: '.$singles_created;// count number of single answer questions added (radio button)
        echo '<br />';
        echo 'Multiple Answer Questions: '.$multiple_created;
        echo '<br />';
        echo 'Text Answer Questions: '.$text_created;
        echo '<br />';
        echo 'Slider Questions:'.$slider_created; 
        echo '</p>';  
        echo '<br />';
        echo 'Inactive Questions:'.$noneactive; 
        echo '</p>';  
         
    }    
} 

/**
* Outputs a menu of content template types with checkbox selection as one template can
* be used for many purposes. Free edition does not have this ability in interface or during
* processing etc.
* 
* @param mixed $post_id
*/
function wtgcsv_display_designtype_menu_advanced($post_id){
    global $wtgcsv_is_free;
    
    // set an array of all the template types
    $templatetypes_array = array();
    $templatetypes_array[0]['slug'] = 'postcontent';
    $templatetypes_array[0]['label'] = 'Post/Page Content';    
    $templatetypes_array[1]['slug'] = 'customfieldvalue';
    $templatetypes_array[1]['label'] = 'Custom Field Value';
    $templatetypes_array[2]['slug'] = 'categorydescription';
    $templatetypes_array[2]['label'] = 'Category Description';
    $templatetypes_array[3]['slug'] = 'postexcerpt';
    $templatetypes_array[3]['label'] = 'Post Excerpt';
    $templatetypes_array[4]['slug'] = 'keywordstring';
    $templatetypes_array[4]['label'] = 'Keyword String';
    $templatetypes_array[5]['slug'] = 'dynamicwidgetcontent';
    $templatetypes_array[5]['label'] = 'Dynamic Widget Content';
    
    // get posts custom field holding template type string
    $customfield_types_array = explode ( ',' , get_post_meta($post_id, 'wtgcsv_templatetypes', true) );?>

    <p>        
        Design Type: 
        <?php $optionmenu = '';?>
        
        <?php $optionmenu .= '<select name="wtgcsv_designtype" id="wtgcsv_select_designtype" multiple="multiple" class="wtgcsv_multiselect_menu">';?>
 
            <?php
            // loop through all template types 
            foreach($templatetypes_array as $key => $type){

                // set selected status by checking if current $type is in the custom field value for wtgcsv_templatetypes
                $selected = '';
                if(in_array($type['slug'],$customfield_types_array,false)){
                    $selected = ' selected="selected"';
                }
                $optionmenu .= '<option value="';
                $optionmenu .= $type['slug'];
                $optionmenu .= '" ';                
                $optionmenu .= $selected;
                $optionmenu .= '>';
                $optionmenu .= $type['label'];
                $optionmenu .= '</option>';
            }?>
                                                                                                            
        <?php $optionmenu .= '</select>';?>

        <?php echo $optionmenu;?>
        
        <script>
        $("#wtgcsv_select_designtype").multiselect({
        multiple: true,
        header: "Select Template Types",
        noneSelectedText: "Select Template Types",
        selectedList: 1
        });
        </script>
            
    </p>
<?php 
}
?>