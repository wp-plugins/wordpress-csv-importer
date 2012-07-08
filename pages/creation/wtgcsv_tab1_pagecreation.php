<?php
++$panel_number;// increase panel counter so this panel has unique ID
$panel_array = array();
$panel_array['panel_name'] = 'dripfeedprojects';// slug to act as a name and part of the panel ID 
$panel_array['panel_number'] = $panel_number;// number of panels counted on page, used to create object ID
$panel_array['panel_title'] = __('Drip Feed Projects *global panel*');// user seen panel header text 
$panel_array['pageid'] = $pageid;// store the $pageid for sake of ease
$panel_array['tabnumber'] = $wtgcsv_tab_number; 
$panel_array['panel_id'] = $panel_array['panel_name'].$panel_number;// creates a unique id, may change from version to version but within a version it should be unique
$panel_array['panel_intro'] = __('Assign or remove a projects drip feed status');
$panel_array['panel_help'] = __('Setup projects for drip feeding by moving projects into the Drip Feeding list.
Wordpress CSV Importer will then include that project in the automated process of creating posts at the rate
set within schedule parameters. The schedule is global meaning it will continue running in the same way no matter
how many projects you setup for post drip feeding. Project settings allow you to determine how many posts are created 
per hour. The plugin can do them all at once or try to spread them out through the hour in increments of 1 minute gaps.');
$panel_array['help_button'] = wtgcsv_helpbutton_text(false,true);
// Form Settings - create the array that is passed to jQuery form functions
$jsform_set_override = array();
$jsform_set = wtgcsv_jqueryform_commonarrayvalues($pageid,$panel_array['tabnumber'],$panel_array['panel_number'],$panel_array['panel_name'],$panel_array['panel_title'],$jsform_set_override);   
$jsform_set['dialoguebox_title'] = 'Saving Drip Feed Status';
$jsform_set['noticebox_content'] = 'You are about to stop or start drip feeding for the selected projects, posts may be created automatically, do you wish to continue?';
// TODO: LOWPRIORITY, add a checkbox to allow user to save design for the mapping approach
// TODO: LOWPRIORITY, add the search box ability too the selectables lists
?>
    <?php wtgcsv_panel_header( $panel_array );?>

    <?php wtgcsv_formstart_standard($jsform_set['form_name'],$jsform_set['form_id'],'post','wtgcsv_form','');?>

    <?php 
    if(!isset($wtgcsv_projectslist_array) || $wtgcsv_projectslist_array == false){
        
        echo '<strong>You do not have any projects</strong>';
            
    }else{?>
    
        <script type="text/javascript">
         $(function(){
            $('#wtgcsv_multiselect_dripfeedprojectsactivation').multiSelect({
                      selectableHeader : '<h3>Manual Only</h3>',
                      selectedHeader : '<h3>Drip Feeding</h3>'
            });
         });
        </script>
                
        <div id="wtgcsv_multiselect_dripfeedprojectsactivation">
        <?php ### TODO:CRITICAL, this select has two ID?>
            <select multiple='multiple' id="listtestid" id="wtgcsv_multiselect_dripfeedprojectsactivation" name="wtgcsv_dripfeedprojects_list[]">
                <?php     
                global $wtgcsv_projectslist_array;
                foreach($wtgcsv_projectslist_array as $project_code => $project){
                    
                    $selected = '';
                    if($wtgcsv_projectslist_array[$project_code]['dripfeeding'] == 'on'){$selected = 'selected="selected"';}
                    echo '<option value="'.$project_code.'" '.$selected.'>'.$project['name'].'</option>';
                        
                }
                ?> 
            </select>
        </div>
        
    <?php 
    }?>    

    <?php
    // add the javascript that will handle our form action, prevent submission and display dialogue box
    wtgcsv_jqueryform_singleaction_middle($jsform_set,$wtgcsv_options_array);

    // add end of form - dialogue box does not need to be within the <form>
    wtgcsv_formend_standard('Submit',$jsform_set['form_id']);?>

    <?php wtgcsv_jquery_form_prompt($jsform_set);?>

<?php wtgcsv_panel_footer();?> 

<?php
++$panel_number;// increase panel counter so this panel has unique ID
$panel_array = array();
$panel_array['panel_name'] = 'dripfeedschedule';// slug to act as a name and part of the panel ID 
$panel_array['panel_number'] = $panel_number;// number of panels counted on page, used to create object ID
$panel_array['panel_title'] = __('Drip Feed Schedule *global panel*');// user seen panel header text 
$panel_array['pageid'] = $pageid;// store the $pageid for sake of ease
$panel_array['tabnumber'] = $wtgcsv_tab_number; 
$panel_array['panel_id'] = $panel_array['panel_name'].$panel_number;// creates a unique id, may change from version to version but within a version it should be unique
$panel_array['panel_intro'] = __('Green indicates posts will be created on that day or time');
$panel_array['panel_help'] = __('A simple approach to controlling when your projects drip feeding is allowed to happen. These settings/times are global and effect all projects with drip feeding applied above.');
$panel_array['help_button'] = wtgcsv_helpbutton_text(false,true);
// Form Settings - create the array that is passed to jQuery form functions
$jsform_set_override = array();
$jsform_set = wtgcsv_jqueryform_commonarrayvalues($pageid,$panel_array['tabnumber'],$panel_array['panel_number'],$panel_array['panel_name'],$panel_array['panel_title'],$jsform_set_override);   
$jsform_set['dialoguebox_title'] = 'Saving Drip Feed Schedule';
$jsform_set['noticebox_content'] = 'You are about to change the drip-feeding schedule, do you want to continue?';
// TODO: LOWPRIORITY, add a checkbox to allow user to save design for the mapping approach
?>
<?php wtgcsv_panel_header( $panel_array );?>

    <?php wtgcsv_formstart_standard($jsform_set['form_name'],$jsform_set['form_id'],'post','wtgcsv_form','');?>

    <h4>Allowed Days</h4>    
    <script>
    $(function() {
        $( "#wtgcsv_dripfeed_days_format" ).buttonset();
    });
    </script>
    <style>
    #wtgcsv_dripfeed_days_format { margin-top: 2em; }
    </style>

    <div id="wtgcsv_dripfeed_days_format">    
    <?php 
    $days_array = array('monday','tuesday','wednesday','thursday','friday','saturday','sunday');
    $days_counter = 1;
    foreach($days_array as $key => $day){
        
        // set checked status
        if(isset($wtgcsv_schedule_array['times']['days'][$day])){
            $day_checked = 'checked';
        }else{
            $day_checked = '';            
        }
        
        echo '<input type="checkbox" name="wtgcsv_scheduleday_list[]" id="daycheck'.$days_counter.'" value="'.$day.'" '.$day_checked.' /><label for="daycheck'.$days_counter.'">'.ucfirst($day).'</label>';    
        ++$days_counter;
    }
    ?>
    </div>
    
    <h4>Allowed Hours</h4>    
    <script>
    $(function() {
        $( "#wtgcsv_dripfeed_hours_format" ).buttonset();
    });
    </script>
    <style>
    #wtgcsv_dripfeed_hours_format { margin-top: 2em; }
    </style>

    <div id="wtgcsv_dripfeed_hours_format">    
    <?php
    // loop 24 times and create a checkbox for each hour
    for($i=0;$i<24;$i++){
        
        // check if the current hour exists in array, if it exists then it is permitted, if it does not exist it is not permitted
        if(isset($wtgcsv_schedule_array['times']['hours'][$i])){
            $hour_checked = ' checked'; 
        }else{
            $hour_checked = '';
        }
        
        echo '<input type="checkbox" name="wtgcsv_schedulehour_list[]" id="hourcheck'.$i.'"  value="'.$i.'" '.$hour_checked.' /><label for="hourcheck'.$i.'">'.$i.'</label>';    
    }
    ?>                                                                                     
    </div>  
                       
    <?php
    // add the javascript that will handle our form action, prevent submission and display dialogue box
    wtgcsv_jqueryform_singleaction_middle($jsform_set,$wtgcsv_options_array);

    // add end of form - dialogue box does not need to be within the <form>
    wtgcsv_formend_standard('Submit',$jsform_set['form_id']);?>

    <?php wtgcsv_jquery_form_prompt($jsform_set);?>

  <?php wtgcsv_panel_footer();?> 

<?php
++$panel_number;// increase panel counter so this panel has unique ID
$panel_array = array();
$panel_array['panel_name'] = 'dripfeedlimits';// slug to act as a name and part of the panel ID 
$panel_array['panel_number'] = $panel_number;// number of panels counted on page, used to create object ID
$panel_array['panel_title'] = __('Drip Feed Limits *global panel*');// user seen panel header text 
$panel_array['pageid'] = $pageid;// store the $pageid for sake of ease
$panel_array['tabnumber'] = $wtgcsv_tab_number; 
$panel_array['panel_id'] = $panel_array['panel_name'].$panel_number;// creates a unique id, may change from version to version but within a version it should be unique
$panel_array['panel_intro'] = __('Quick and easy controls to apply the general rate of post creation during drip feeding sessions');
$panel_array['panel_help'] = __('These controls tell the plugin how many posts to create during a drip feed session. It is a quick and easy approach to applying the rate of post creation. The plugin strictly avoids going over limits, this is considered higher priority than reaching the limit. The plugin will only begin a drip feed session when someone visits the blog; Wordpress loading triggers the schedule to be checked. The plugin will avoid doing this too often so that users do not get a negative experience. A cooldown between drip feed sessions also helps to avoid triggering server problems and using up too much bandwidth within a very short time which can also cause hosting to raise concerns.');
$panel_array['help_button'] = wtgcsv_helpbutton_text(false,true);
// Form Settings - create the array that is passed to jQuery form functions
$jsform_set_override = array();
$jsform_set = wtgcsv_jqueryform_commonarrayvalues($pageid,$panel_array['tabnumber'],$panel_array['panel_number'],$panel_array['panel_name'],$panel_array['panel_title'],$jsform_set_override);            
$jsform_set['dialoguebox_title'] = 'Save Drip Feeding Limits';
$jsform_set['noticebox_content'] = 'These are global settings and will take effect on all projects straight away. Do you wish to continue?';
?>
<?php wtgcsv_panel_header( $panel_array );?>

<?php wtgcsv_formstart_standard($jsform_set['form_name'],$jsform_set['form_id'],'post','wtgcsv_form','');?>

    <h4>Maximum Per Day</h4>
    <script>
    $(function() {
        $( "#wtgcsv_dripfeedrate_maximumperday" ).buttonset();
    });
    </script>

    <div id="wtgcsv_dripfeedrate_maximumperday">
        <input type="radio" id="wtgcsv_radio1_dripfeedrate_maximumperday" name="day" value="1" <?php if(isset($wtgcsv_schedule_array['limits']['day']) && $wtgcsv_schedule_array['limits']['day'] == 1){echo 'checked';} ?> /><label for="wtgcsv_radio1_dripfeedrate_maximumperday">1</label>
        <input type="radio" id="wtgcsv_radio2_dripfeedrate_maximumperday" name="day" value="5" <?php if(isset($wtgcsv_schedule_array['limits']['day']) && $wtgcsv_schedule_array['limits']['day'] == 5){echo 'checked';} ?> /><label for="wtgcsv_radio2_dripfeedrate_maximumperday">5</label>
        <input type="radio" id="wtgcsv_radio3_dripfeedrate_maximumperday" name="day" value="10" <?php if(isset($wtgcsv_schedule_array['limits']['day']) && $wtgcsv_schedule_array['limits']['day'] == 10){echo 'checked';} ?> /><label for="wtgcsv_radio3_dripfeedrate_maximumperday">10</label>  
        <input type="radio" id="wtgcsv_radio9_dripfeedrate_maximumperday" name="day" value="24" <?php if(isset($wtgcsv_schedule_array['limits']['day']) && $wtgcsv_schedule_array['limits']['day'] == 24){echo 'checked';} ?> /><label for="wtgcsv_radio9_dripfeedrate_maximumperday">24</label>                    
        <input type="radio" id="wtgcsv_radio4_dripfeedrate_maximumperday" name="day" value="50" <?php if(isset($wtgcsv_schedule_array['limits']['day']) && $wtgcsv_schedule_array['limits']['day'] == 50){echo 'checked';} ?> /><label for="wtgcsv_radio4_dripfeedrate_maximumperday">50</label>
        <input type="radio" id="wtgcsv_radio5_dripfeedrate_maximumperday" name="day" value="250" <?php if(isset($wtgcsv_schedule_array['limits']['day']) && $wtgcsv_schedule_array['limits']['day'] == 250){echo 'checked';} ?> /><label for="wtgcsv_radio5_dripfeedrate_maximumperday">250</label>
        <input type="radio" id="wtgcsv_radio6_dripfeedrate_maximumperday" name="day" value="1000" <?php if(isset($wtgcsv_schedule_array['limits']['day']) && $wtgcsv_schedule_array['limits']['day'] == 1000){echo 'checked';} ?> /><label for="wtgcsv_radio6_dripfeedrate_maximumperday">1000</label>                                                                                                                       
    </div>
                
    <h4>Maximum Per Hour</h4>
    <script>
    $(function() {
        $( "#wtgcsv_dripfeedrate_maximumperhour" ).buttonset();
    });
    </script>

    <div id="wtgcsv_dripfeedrate_maximumperhour">
        <input type="radio" id="wtgcsv_radio1_dripfeedrate_maximumperhour" name="hour" value="1" <?php if(isset($wtgcsv_schedule_array['limits']['hour']) && $wtgcsv_schedule_array['limits']['hour'] == 1){echo 'checked';} ?> /><label for="wtgcsv_radio1_dripfeedrate_maximumperhour">1</label>
        <input type="radio" id="wtgcsv_radio2_dripfeedrate_maximumperhour" name="hour" value="5" <?php if(isset($wtgcsv_schedule_array['limits']['hour']) && $wtgcsv_schedule_array['limits']['hour'] == 5){echo 'checked';} ?> /><label for="wtgcsv_radio2_dripfeedrate_maximumperhour">5</label>
        <input type="radio" id="wtgcsv_radio3_dripfeedrate_maximumperhour" name="hour" value="10" <?php if(isset($wtgcsv_schedule_array['limits']['hour']) && $wtgcsv_schedule_array['limits']['hour'] == 10){echo 'checked';} ?> /><label for="wtgcsv_radio3_dripfeedrate_maximumperhour">10</label>
        <input type="radio" id="wtgcsv_radio9_dripfeedrate_maximumperhour" name="hour" value="24" <?php if(isset($wtgcsv_schedule_array['limits']['hour']) && $wtgcsv_schedule_array['limits']['hour'] == 24){echo 'checked';} ?> /><label for="wtgcsv_radio9_dripfeedrate_maximumperhour">24</label>                    
        <input type="radio" id="wtgcsv_radio4_dripfeedrate_maximumperhour" name="hour" value="50" <?php if(isset($wtgcsv_schedule_array['limits']['hour']) && $wtgcsv_schedule_array['limits']['hour'] == 50){echo 'checked';} ?> /><label for="wtgcsv_radio4_dripfeedrate_maximumperhour">50</label>
        <input type="radio" id="wtgcsv_radio5_dripfeedrate_maximumperhour" name="hour" value="250" <?php if(isset($wtgcsv_schedule_array['limits']['hour']) && $wtgcsv_schedule_array['limits']['hour'] == 250){echo 'checked';} ?> /><label for="wtgcsv_radio5_dripfeedrate_maximumperhour">250</label>
        <input type="radio" id="wtgcsv_radio6_dripfeedrate_maximumperhour" name="hour" value="1000" <?php if(isset($wtgcsv_schedule_array['limits']['hour']) && $wtgcsv_schedule_array['limits']['hour'] == 1000){echo 'checked';} ?> /><label for="wtgcsv_radio6_dripfeedrate_maximumperhour">1000</label>                                                                                                                        
    </div>
                    
    <h4>Maximum Per Session Session</h4>
    <script>
    $(function() {
        $( "#wtgcsv_dripfeedrate_maximumpersession" ).buttonset();
    });
    </script>                
    <div id="wtgcsv_dripfeedrate_maximumpersession">
        <input type="radio" id="wtgcsv_radio1_dripfeedrate_maximumpersession" name="session" value="1" <?php if(isset($wtgcsv_schedule_array['limits']['session']) && $wtgcsv_schedule_array['limits']['session'] == 1){echo 'checked';} ?> /><label for="wtgcsv_radio1_dripfeedrate_maximumpersession">1</label>
        <input type="radio" id="wtgcsv_radio2_dripfeedrate_maximumpersession" name="session" value="5" <?php if(isset($wtgcsv_schedule_array['limits']['session']) && $wtgcsv_schedule_array['limits']['session'] == 5){echo 'checked';} ?> /><label for="wtgcsv_radio2_dripfeedrate_maximumpersession">5</label>
        <input type="radio" id="wtgcsv_radio3_dripfeedrate_maximumpersession" name="session" value="10" <?php if(isset($wtgcsv_schedule_array['limits']['session']) && $wtgcsv_schedule_array['limits']['session'] == 10){echo 'checked';} ?> /><label for="wtgcsv_radio3_dripfeedrate_maximumpersession">10</label>
        <input type="radio" id="wtgcsv_radio9_dripfeedrate_maximumpersession" name="session" value="24" <?php if(isset($wtgcsv_schedule_array['limits']['session']) && $wtgcsv_schedule_array['limits']['session'] == 24){echo 'checked';} ?> /><label for="wtgcsv_radio9_dripfeedrate_maximumpersession">24</label>                    
        <input type="radio" id="wtgcsv_radio4_dripfeedrate_maximumpersession" name="session" value="50" <?php if(isset($wtgcsv_schedule_array['limits']['session']) && $wtgcsv_schedule_array['limits']['session'] == 50){echo 'checked';} ?> /><label for="wtgcsv_radio4_dripfeedrate_maximumpersession">50</label>
        <input type="radio" id="wtgcsv_radio5_dripfeedrate_maximumpersession" name="session" value="250" <?php if(isset($wtgcsv_schedule_array['limits']['session']) && $wtgcsv_schedule_array['limits']['session'] == 250){echo 'checked';} ?> /><label for="wtgcsv_radio5_dripfeedrate_maximumpersession">250</label>
        <input type="radio" id="wtgcsv_radio6_dripfeedrate_maximumpersession" name="session" value="1000" <?php if(isset($wtgcsv_schedule_array['limits']['session']) && $wtgcsv_schedule_array['limits']['session'] == 1000){echo 'checked';} ?> /><label for="wtgcsv_radio6_dripfeedrate_maximumpersession">1000</label>                                                                                                                        
    </div>
    
     <?php
    // add the javascript that will handle our form action, prevent submission and display dialogue box
    wtgcsv_jqueryform_singleaction_middle($jsform_set,$wtgcsv_options_array);

    // add end of form - dialogue box does not need to be within the <form>
    wtgcsv_formend_standard('Submit',$jsform_set['form_id']);?>

    <?php wtgcsv_jquery_form_prompt($jsform_set);?>

<?php wtgcsv_panel_footer();?> 

<?php
if(!$wtgcsv_is_free){
    ++$panel_number;// increase panel counter so this panel has unique ID
    $panel_array = array();
    $panel_array['panel_name'] = 'dripfeedprojectsarraydump';// slug to act as a name and part of the panel ID 
    $panel_array['panel_number'] = $panel_number;// number of panels counted on page, used to create object ID
    $panel_array['panel_title'] = __('Drip Feed Project Array Dump');// user seen panel header text 
    $panel_array['pageid'] = $pageid;// store the $pageid for sake of ease
    $panel_array['tabnumber'] = $wtgcsv_tab_number; 
    $panel_array['panel_id'] = $panel_array['panel_name'].$panel_number;// creates a unique id, may change from version to version but within a version it should be unique
    $panel_array['panel_intro'] = __('Dump of project list array, also holds settings for drip feed activation');
    $panel_array['panel_help'] = __('A dump of the array that holds all projects. It also holds the value that causes a project to be included in automatic post creation. Automatic post creation is also known as drip feeding posts into Wordpress or auto-blogging. In Wordpress CSV Importer drip-feeding events are triggered when the blog is visited on both public and admin side. During the loading of Wordpress, this plugin is also loaded and any due events are processed.');
    $panel_array['help_button'] = wtgcsv_helpbutton_text(false,true);?>
    <?php wtgcsv_panel_header( $panel_array );?>
        
        <pre><?php var_dump($wtgcsv_projectslist_array); ?></pre>
        
    <?php wtgcsv_panel_footer();
}?> 

<?php
if(!$wtgcsv_is_free){
    ++$panel_number;// increase panel counter so this panel has unique ID
    $panel_array = array();
    $panel_array['panel_name'] = 'dripfeedschedulearraydump';// slug to act as a name and part of the panel ID 
    $panel_array['panel_number'] = $panel_number;// number of panels counted on page, used to create object ID
    $panel_array['panel_title'] = __('Drip Feed Schedule Array Dump');// user seen panel header text 
    $panel_array['pageid'] = $pageid;// store the $pageid for sake of ease
    $panel_array['tabnumber'] = $wtgcsv_tab_number; 
    $panel_array['panel_id'] = $panel_array['panel_name'].$panel_number;// creates a unique id, may change from version to version but within a version it should be unique
    $panel_array['panel_intro'] = __('Dump of schedule and limits array used for drip-feeding events');
    $panel_array['panel_help'] = __('This array dump shows the permitted days of the week and hours per day for drip-feed events to happen. The values apply to all projects, contact us if you need projects to run different schedules.');
    $panel_array['help_button'] = wtgcsv_helpbutton_text(false,true);?>
    <?php wtgcsv_panel_header( $panel_array );?>
        
        <h4>Days</h4>
        <pre><?php var_dump($wtgcsv_schedule_array['times']['days']); ?></pre>

        <h4>Hours</h4>
        <pre><?php var_dump($wtgcsv_schedule_array['times']['hours']); ?></pre>
        
        <h4>Creation Limits</h4>
        <p>These are used to avoid over processing on the server</p>
        <pre><?php var_dump($wtgcsv_schedule_array['limits']); ?></pre>
        
        <h4>Entire Array</h4>
        <pre><?php var_dump($wtgcsv_schedule_array); ?></pre>
                
    <?php wtgcsv_panel_footer();
}?> 