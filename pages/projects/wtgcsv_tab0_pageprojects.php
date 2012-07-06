<?php 
if($wtgcsv_is_free){?>
<script type="text/javascript"><!--
google_ad_client = "ca-pub-4923567693678329";
/* Wordpress CSV Importer Wide */
google_ad_slot = "2263056755";
google_ad_width = 728;
google_ad_height = 90;
//-->
</script>
<script type="text/javascript"
src="http://pagead2.googlesyndication.com/pagead/show_ads.js">
</script>
<?php }?>

<?php 
if(count($wtgcsv_projectslist_array) == 0){
    echo wtgcsv_notice('Start here on this screen if you want to create posts. You need to create a project, then continue by clicking on the other tabs above.','warning','Tiny','','','return');
}
?>

<?php 
if(!$wtgcsv_is_free){         
++$panel_number;// increase panel counter so this panel has unique ID
$panel_array = array();
$panel_array['panel_name'] = 'selectcurrentproject';// slug to act as a name and part of the panel ID 
$panel_array['panel_number'] = $panel_number;// number of panels counted on page, used to create object ID
$panel_array['panel_title'] = __('Select Current Project');// user seen panel header text 
$panel_array['pageid'] = $pageid;// store the $pageid for sake of ease
$panel_array['tabnumber'] = $wtgcsv_tab_number; 
$panel_array['panel_id'] = $panel_array['panel_name'].$panel_number;// creates a unique id, may change from version to version but within a version it should be unique
if($wtgcsv_is_free){$panel_array['panel_intro'] = __('Full edition allows multiple projects to be created');}else{$panel_array['panel_intro'] = __('You can activate any project to make changes to its configuration');}
$panel_array['panel_help'] = __('This panel allows you to make any of your projects your Current project. Your current project settings will be displayed in the Your Projects screens. The Your Project screens also allow you to change global settings so take care when making changes if you are running multiple projects.');
$panel_array['help_button'] = wtgcsv_helpbutton_text(false,true);
// Form Settings - create the array that is passed to jQuery form functions
$jsform_set_override = array();
$jsform_set = wtgcsv_jqueryform_commonarrayvalues($pageid,$panel_array['tabnumber'],$panel_array['panel_number'],$panel_array['panel_name'],$panel_array['panel_title'],$jsform_set_override);            
$jsform_set['dialoguebox_title'] = 'Change Your Current Project';
$jsform_set['noticebox_content'] = 'You are about to change your current project, all forms will now offer changes for your new current project.';?>

<?php wtgcsv_panel_header( $panel_array );?>

<?php wtgcsv_formstart_standard($jsform_set['form_name'],$jsform_set['form_id'],'post','wtgcsv_form','');?>

    <script>
    $(function() {
        $( "#wtgcsv_radios_<?php echo $panel_array['panel_name'];?>" ).buttonset();
    });
    </script>

    <div id="wtgcsv_radios_<?php echo $panel_array['panel_name'];?>">
        
        <?php
        if(!isset($wtgcsv_projectslist_array) || $wtgcsv_projectslist_array == false){
            
            echo '<strong>You do not have any projects</strong>';
                
        }else{
            
            $i = 0; 
            foreach($wtgcsv_projectslist_array as $project_code => $project ){
                $checked = '';
                if($wtgcsv_currentproject_code == $project_code){
                    $checked = 'checked="checked"';    
                }
                echo '<input type="radio" id="wtgcsv_radio'.$i.$panel_array['panel_name'].'" name="wtgcsv_radio_projectcode" value="'.$project_code.'" '.$checked.' /><label for="wtgcsv_radio'.$i.$panel_array['panel_name'].'">'.$project['name'].'</label>';    
                ++$i;
            }
        }
        ?>
        
    </div>           

    <?php
    // add the javascript that will handle our form action, prevent submission and display dialogue box
    wtgcsv_jqueryform_singleaction_middle($jsform_set,$wtgcsv_options_array);

    // add end of form - dialogue box does not need to be within the <form>
    wtgcsv_formend_standard('Submit',$jsform_set['form_id']);?>

    <?php wtgcsv_jquery_form_prompt($jsform_set);?>

<?php wtgcsv_panel_footer();
}?> 

<?php
++$panel_number;// increase panel counter so this panel has unique ID
$panel_array = array();
$panel_array['panel_name'] = 'createpostcreationproject';// slug to act as a name and part of the panel ID 
$panel_array['panel_number'] = $panel_number;// number of panels counted on page, used to create object ID
$panel_array['panel_title'] = __('Create Post Creation Project');// user seen panel header text 
$panel_array['pageid'] = $pageid;// store the $pageid for sake of ease
$panel_array['tabnumber'] = $wtgcsv_tab_number; 
$panel_array['panel_id'] = $panel_array['panel_name'].$panel_number;// creates a unique id, may change from version to version but within a version it should be unique
$panel_array['panel_intro'] = __('Make a post creation project that makes use of one or more database tables');
$panel_array['panel_help'] = __('Create a new project for creating posts. This should be done after you have imported your data too your Wordpress database or already have a suitable table holding your data. Wordpress CSV Importer allows us to use multiple database tables in a single project. You can make use of columns from one table and some columns from a totally different table. The more this approach is used the more time it will take per post to be created so please consider the increased strain it will put on your server. Each post published manually, one at a time, requires multiple SQL queries too your database. Mass creating posts generates a lot of queries and the more tables you use the more querying this plugin will have to do. Simply slow the rate of creation down if your server is experiencing problems and avoid over 20 seconds of processing.');
$panel_array['help_button'] = wtgcsv_helpbutton_text(false,false);
// Form Settings - create the array that is passed to jQuery form functions
$jsform_set_override = array();
$jsform_set = wtgcsv_jqueryform_commonarrayvalues($pageid,$panel_array['tabnumber'],$panel_array['panel_number'],$panel_array['panel_name'],$panel_array['panel_title'],$jsform_set_override);            
$jsform_set['dialoguebox_title'] = 'Create Post Creation Project';
$jsform_set['noticebox_content'] = 'Do you want to continue creating a new Post Creation Project? <p>Please note after submitting this form you will need to configure your project settings and tell the plugin exactly how you want your posts to be.</p>';
// create nonce - done in wtgcsv_ajax_is_dataimportjobname_used
$nonce = wp_create_nonce( "wtgcsv_referer_createproject_checkprojectname" );
// TODO: HIGHPRIORITY, when existing table is selected, display another form option to select the existing table
// TODO: HIGHPRIORITY, ajax function that checks the project name is not displaying results?>

<?php wtgcsv_panel_header( $panel_array );?>
    
    <?php wtgcsv_formstart_standard($jsform_set['form_name'],$jsform_set['form_id'],'post','wtgcsv_form','');?>

    <?php // set ID and NAME variables
    $projectname_id = 'wtgcsv_projectname_id_' . $panel_array['panel_name'];
    $projectname_name = 'wtgcsv_projectname_name_' . $panel_array['panel_name'];?>
    
    <script  type='text/javascript'>
    <!--
    var count = 0;

    // When the document loads do everything inside here ...
    jQuery(document).ready(function(){

        // run validation on data import job name when key is pressed
        $("#<?php echo $projectname_id;?>").change(function() { 

            var usr = $("#<?php echo $projectname_id;?>").val();

            if(usr.length >= 4){
                
                // remove any status display by adding blank value too html
                $("#wtgcsv_status_<?php echo $jsform_set['form_id'];?>").html('');
                                                                        
                jQuery.ajax({
                    type: "post",url: "admin-ajax.php",data: { action: 'action_createproject_checkprojectname', wtgcsv_projectname: escape( jQuery( '#<?php echo $projectname_id;?>' ).val() ), _ajax_nonce: '<?php echo $nonce; ?>' },
                    beforeSend: function() {jQuery("#<?php echo $jsform_set['form_id'];?>loading_projectnamechange").fadeIn('fast');
                    jQuery("#<?php echo $jsform_set['form_id'];?>formstatus").fadeOut("fast");}, //fadeIn loading just when link is clicked
                    success: function(html){ //so, if data is retrieved, store it in html
                        jQuery("#<?php echo $jsform_set['form_id'];?>loading_projectnamechange").fadeOut('slow');
                        jQuery("#<?php echo $jsform_set['form_id'];?>formstatus").html( html ); //show the html inside formstatus div
                        jQuery("#<?php echo $jsform_set['form_id'];?>formstatus").fadeIn("fast"); //animation
                    }
                }); //close jQuery.ajax
                     
            }else{
                $("#wtgcsv_status_<?php echo $jsform_set['form_id'];?>").html('<font color="red">' + 'The username should have at least <strong>4</strong> characters.</font>');
                $("#<?php echo $projectname_id;?>").removeClass('object_ok'); // if necessary
                $("#<?php echo $projectname_id;?>").addClass("object_error");
            }

        });
    })
    -->
    </script>

    <style type='text/css'>
    #<?php echo $jsform_set['form_id'];?>loading_projectnamechange { clear:both; background:url(images/loading.gif) center top no-repeat; text-align:center;padding:33px 0px 0px 0px; font-size:12px;display:none; font-family:Verdana, Arial, Helvetica, sans-serif; }                
    </style>
    
    <?php 
    if($wtgcsv_is_free){?>

        <input type='hidden' name='wtgcsv_projectname_name' value='Project' /><?php 

    }else{?>
      
        <p>Enter Project Name: <input type='text' name='wtgcsv_projectname_name' id='<?php echo $projectname_id;?>' value='' /><span id="wtgcsv_status_<?php echo $jsform_set['form_id'];?>"></span></p><?php 
    
    }?>
    
    <!-- jquery and ajax output start -->
    <div id='<?php echo $jsform_set['form_id'];?>loading_projectnamechange'>Checking Project Name Please Wait 10 Seconds</div>                 
    <div id='<?php echo $jsform_set['form_id'];?>formstatus'></div>  
    <!-- jquery and ajax output end -->            
    <h4>Select CSV File</h4>
    <?php wtgcsv_display_databasetables_withjobnames(true);?>

    <?php // TODO: LOWPRIORITY, only display mapping methods when user selects more than 1 table, only show the third method when user selects 3 or more ?>
    <h4>Select Table Mapping Type <?php if($wtgcsv_is_free){echo '(not required in free edition)';}?></h4>
    <script>
    $(function() {
        $( "#wtgcsv_projecttableselection_mapping_option" ).buttonset();
    });
    </script>

    <div id="wtgcsv_projecttableselection_mapping_option">
        <?php
        $checked_defaultorder = '';
        $checked_singlekeycolumn = '';
        $checked_manykeycolumns = '';
        if(isset($wtgcsv_project_array['mappingmethod']) && $wtgcsv_project_array['mappingmethod'] == 'defaultorder'){
            $checked_defaultorder = 'checked'; 
        }elseif(isset($wtgcsv_project_array['mappingmethod']) && $wtgcsv_project_array['mappingmethod'] == 'singlekeycolumn'){
            $checked_singlekeycolumn = 'checked';    
        }elseif(isset($wtgcsv_project_array['mappingmethod']) && $wtgcsv_project_array['mappingmethod'] == 'manykeycolumns'){
            $checked_manykeycolumns = 'checked';                 
        }else{
            $checked_defaultorder = 'checked';    
        }?>

        <input type="radio" id="wtgcsv_projecttables_mappingmethod_defaultorder" name="wtgcsv_projecttables_mappingmethod_inputname" value="defaultorder" <?php echo $checked_defaultorder;?> <?php if($wtgcsv_is_free){echo 'disabled="disabled"';}?> /><label for="wtgcsv_projecttables_mappingmethod_defaultorder">Default Order</label>
        <input type="radio" id="wtgcsv_projecttables_mappingmethod_singlekeycolumn" name="wtgcsv_projecttables_mappingmethod_inputname" value="singlekeycolumn" <?php echo $checked_singlekeycolumn;?> <?php if($wtgcsv_is_free){echo 'disabled="disabled"';}?> /><label for="wtgcsv_projecttables_mappingmethod_singlekeycolumn">Single Key Column</label>
        <input type="radio" id="wtgcsv_projecttables_mappingmethod_manykeycolumns" name="wtgcsv_projecttables_mappingmethod_inputname" value="manykeycolumns" <?php echo $checked_manykeycolumns;?> <?php if($wtgcsv_is_free){echo 'disabled="disabled"';}?> /><label for="wtgcsv_projecttables_mappingmethod_manykeycolumns">Many Key Columns</label>            

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
    $panel_array['panel_name'] = 'postcreationprojectlist';// slug to act as a name and part of the panel ID 
    $panel_array['panel_number'] = $panel_number;// number of panels counted on page, used to create object ID
    $panel_array['panel_title'] = __('Post Creation Project List');// user seen panel header text 
    $panel_array['pageid'] = $pageid;// store the $pageid for sake of ease
    $panel_array['tabnumber'] = $wtgcsv_tab_number; 
    $panel_array['panel_id'] = $panel_array['panel_name'].$panel_number;// creates a unique id, may change from version to version but within a version it should be unique
    $panel_array['panel_intro'] = __('Your current post creation projects, both drip-feed and manual');
    $panel_array['panel_help'] = __('All your post creation projects are listed here. You must not delete a project if you plan to update data in the Wordpress database that is related too posts in any way. The project configuration data includes history/statistical values that may be required for future changes too posts created by the project.');
    $panel_array['help_button'] = wtgcsv_helpbutton_text(false,true);?>
    <?php wtgcsv_panel_header( $panel_array );?>
        <?php wtgcsv_postcreationproject_table();?>
    <?php wtgcsv_panel_footer();
}?> 

<?php
++$panel_number;// increase panel counter so this panel has unique ID
$panel_array = array();
$panel_array['panel_name'] = 'deleteprojects';// slug to act as a name and part of the panel ID 
$panel_array['panel_number'] = $panel_number;// number of panels counted on page, used to create object ID
$panel_array['panel_title'] = __('Delete Project');// user seen panel header text 
$panel_array['pageid'] = $pageid;// store the $pageid for sake of ease
$panel_array['tabnumber'] = $wtgcsv_tab_number; 
$panel_array['panel_id'] = $panel_array['panel_name'].$panel_number;// creates a unique id, may change from version to version but within a version it should be unique
$panel_array['panel_intro'] = __('This panel allows you to delete one or more projects');
$panel_array['panel_help'] = __('You can select multiple project to be deleted. This cannot be reversed, please take care when selecting your projects.');
$panel_array['help_button'] = wtgcsv_helpbutton_text(false,false);
// Form Settings - create the array that is passed to jQuery form functions
$jsform_set_override = array();
$jsform_set = wtgcsv_jqueryform_commonarrayvalues($pageid,$panel_array['tabnumber'],$panel_array['panel_number'],$panel_array['panel_name'],$panel_array['panel_title'],$jsform_set_override);            
$jsform_set['dialoguebox_title'] = 'Delete Project';
$jsform_set['noticebox_content'] = 'Are you sure you want to delete the selected post creation projects?';?>
<?php wtgcsv_panel_header( $panel_array );?>

    <?php wtgcsv_formstart_standard($jsform_set['form_name'],$jsform_set['form_id'],'post','wtgcsv_form','');?>
    
    <?php 
    if(!isset($wtgcsv_projectslist_array) || $wtgcsv_projectslist_array == false){
        
        if($wtgcsv_is_free){
            echo '<strong>You have not created your project yet, there is nothing to be deleted</strong>';
        }else{
            echo '<strong>You do not have any projects</strong>';
        }
            
    }else{
        
        if($wtgcsv_is_free){
            // display nothing - free edition works with a single project    
        }else{?>
            
            <br />
            <div id="wtgcsv-multiselect-createnewproject-div-id">
                <select multiple='multiple' id="wtgcsv_project_listid" class='wtgcsv_multiselect_projects_deleteprojects' name="wtgcsv_projectcodes_array[]">
                    <?php wtgcsv_option_items_postcreationprojects();?> 
                </select>
            </div> 
                       
            <script type="text/javascript">
                $(function(){
                    $('.wtgcsv_multiselect_projects_deleteprojects').multiSelect({
                      selectableHeader : '<h3>Projects Available</h3>',
                      selectedHeader : '<h3>Delete These</h3>'                
                    });
                });
            </script><?php 
        }
        
        // add the javascript that will handle our form action, prevent submission and display dialogue box
        wtgcsv_jqueryform_singleaction_middle($jsform_set,$wtgcsv_options_array);

        // add end of form - dialogue box does not need to be within the <form>
        wtgcsv_formend_standard('Delete Project',$jsform_set['form_id']);
                
        wtgcsv_jquery_form_prompt($jsform_set);    
    }?>

<?php wtgcsv_panel_footer();?>