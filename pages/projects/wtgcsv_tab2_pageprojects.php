<?php
++$panel_number;// increase panel counter so this panel has unique ID
$panel_array = array();
$panel_array['panel_name'] = 'defaulttitletemplate';// slug to act as a name and part of the panel ID 
$panel_array['panel_number'] = $panel_number;// number of panels counted on page, used to create object ID
$panel_array['panel_title'] = __('Set Default Title Template');// user seen panel header text 
$panel_array['pageid'] = $pageid;// store the $pageid for sake of ease
$panel_array['tabnumber'] = $wtgcsv_tab_number; 
$panel_array['panel_id'] = $panel_array['panel_name'].$panel_number;// creates a unique id, may change from version to version but within a version it should be unique
$panel_array['panel_intro'] = __('Copy and paste these to place your data where you will need it in your final posts');
$panel_array['panel_help'] = __('There is a token for each of your database columns and for all tables included in your project. These are replaced with data from the column they represent. Please be aware that any content which matches these strings will also be replaced however the asterix between table name and column name should help to prevent this.');
$panel_array['help_button'] = wtgcsv_helpbutton_text(false,true);?>
<?php wtgcsv_panel_header( $panel_array );?>

    <form id="wtgcsv_form_opentemplate_id" action="<?php echo $wtgcsv_form_action;?>" method="post" name="wtgcsv_form_opentemplate_name">
        <input type="hidden" id="wtgcsv_post_processing_required" name="wtgcsv_post_processing_required" value="true">               
        
        <input type="hidden" name="wtgcsv_current_project_id" value="<?php echo $wtgcsv_currentproject_code;?>">
        <input type="hidden" name="wtgcsv_change_default_titletemplate" value="true">        

        <h4>Current Projects Default Title Template</h4> 
        Template Name: <?php echo wtgcsv_get_default_titletemplate_name();?><br />
        Template Design: <?php echo wtgcsv_get_default_titletemplate_design(wtgcsv_get_default_titletemplate_id($wtgcsv_currentproject_code));?>

        <h4>Current Project Title Templates</h4>
        <?php wtgcsv_displayproject_titletemplates_buttonlist('wtgcsv_selecttemplate_fromproject_id');?>          
    
        <h4>All Title Templates</h4>
        <?php wtgcsv_display_all_titledesigns_buttonlist();?>            

    </form>    

<?php wtgcsv_panel_footer();?>

<?php
++$panel_number;// increase panel counter so this panel has unique ID
$panel_array = array();
$panel_array['panel_name'] = 'columnreplacementtokens2';// slug to act as a name and part of the panel ID 
$panel_array['panel_number'] = $panel_number;// number of panels counted on page, used to create object ID
$panel_array['panel_title'] = __('Column Replacement Tokens');// user seen panel header text 
$panel_array['pageid'] = $pageid;// store the $pageid for sake of ease
$panel_array['tabnumber'] = $wtgcsv_tab_number; 
$panel_array['panel_id'] = $panel_array['panel_name'].$panel_number;// creates a unique id, may change from version to version but within a version it should be unique
$panel_array['panel_intro'] = __('Copy and paste these to place your data where you will need it in your final posts');
$panel_array['panel_help'] = __('There is a token for each of your database columns and for all tables included in your project. These are replaced with data from the column they represent. Please be aware that any content which matches these strings will also be replaced however the asterix between table name and column name should help to prevent this.');
$panel_array['help_button'] = wtgcsv_helpbutton_text(false,false);?>
<?php wtgcsv_panel_header( $panel_array );?>

<?php wtgcsv_list_replacement_tokens($wtgcsv_currentproject_code);?>

<?php wtgcsv_panel_footer();?>

<?php
++$panel_number;// increase panel counter so this panel has unique ID
$panel_array = array();
$panel_array['panel_name'] = 'createtitletemplates';// slug to act as a name and part of the panel ID 
$panel_array['panel_number'] = $panel_number;// number of panels counted on page, used to create object ID
$panel_array['panel_title'] = __('Create Title Templates *global panel*');// user seen panel header text 
$panel_array['pageid'] = $pageid;// store the $pageid for sake of ease
$panel_array['tabnumber'] = $wtgcsv_tab_number; 
$panel_array['panel_id'] = $panel_array['panel_name'].$panel_number;// creates a unique id, may change from version to version but within a version it should be unique
$panel_array['panel_intro'] = __('Create title templates by inserting your column replacement tags');
$panel_array['panel_help'] = __('The plugin allows us to create multiple post title templates for selecting when creating a new project. Over 2012 I will be adding features to allow dynamic or random designs to be applied based on values within your data or any other criteria users want to be taking into consideration.');
$panel_array['help_button'] = wtgcsv_helpbutton_text(false,true);
// Form Settings - create the array that is passed to jQuery form functions
$jsform_set_override = array();
$jsform_set = wtgcsv_jqueryform_commonarrayvalues($pageid,$panel_array['tabnumber'],$panel_array['panel_number'],$panel_array['panel_name'],$panel_array['panel_title'],$jsform_set_override);   
$jsform_set['dialoguebox_title'] = 'Creating New Title Template';
$jsform_set['noticebox_content'] = 'Do you want to create your new title template now?';
// TODO: LOWPRIORITY, add a checkbox to allow user to save design for the mapping approach?>
<?php wtgcsv_panel_header( $panel_array );?>

    <?php wtgcsv_formstart_standard($jsform_set['form_name'],$jsform_set['form_id'],'post','wtgcsv_form',$wtgcsv_form_action);?>

    <h4>Title Template Design Name</h4>
    <input type="text" name="wtgcsv_titletemplate_name" size="65" value="" id="wtgcsv_titletemplate_form_id" /> 
    
    <h4>Title Template (add column replacement tokens)</h4>                                 
    <input type="text" name="wtgcsv_titletemplate_title" size="65" value="" id="title" />

    <br /><br />

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
$panel_array['panel_name'] = 'edittitletemplates';// slug to act as a name and part of the panel ID 
$panel_array['panel_number'] = $panel_number;// number of panels counted on page, used to create object ID
$panel_array['panel_title'] = __('Edit Title Templates *global panel*');// user seen panel header text 
$panel_array['pageid'] = $pageid;// store the $pageid for sake of ease
$panel_array['tabnumber'] = $wtgcsv_tab_number; 
$panel_array['panel_id'] = $panel_array['panel_name'].$panel_number;// creates a unique id, may change from version to version but within a version it should be unique
$panel_array['panel_intro'] = __('Change existing title templates, this does not change existing posts');
$panel_array['panel_help'] = __('You can edit existing title templates here. Please note that changes these does not automatically update posts already created using the designs. The feature to do that will be added to the plugin but as I write this it does not exist, try searching on the plugins site for instructions.217');
$panel_array['help_button'] = wtgcsv_helpbutton_text(false,false);
// Form Settings - create the array that is passed to jQuery form functions
$jsform_set_override = array();
$jsform_set = wtgcsv_jqueryform_commonarrayvalues($pageid,$panel_array['tabnumber'],$panel_array['panel_number'],$panel_array['panel_name'],$panel_array['panel_title'],$jsform_set_override);   
$jsform_set['dialoguebox_title'] = 'Update Title Templates';
$jsform_set['noticebox_content'] = 'Do you want to update any changes made to your title templates, all posts made after the update will use the new design?';
// TODO: LOWPRIORITY, add a checkbox to allow user to save design for the mapping approach?>
<?php wtgcsv_panel_header( $panel_array );?>

    <?php wtgcsv_formstart_standard($jsform_set['form_name'],$jsform_set['form_id'],'post','wtgcsv_form',$wtgcsv_form_action);?>

    <?php wtgcsv_list_titletemplate_foredit();?> 
    
    <br />
     
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
$panel_array['panel_name'] = 'titlecolumn';// slug to act as a name and part of the panel ID 
$panel_array['panel_number'] = $panel_number;// number of panels counted on page, used to create object ID
$panel_array['panel_title'] = __('Set Title Column');// user seen panel header text 
$panel_array['pageid'] = $pageid;// store the $pageid for sake of ease
$panel_array['tabnumber'] = $wtgcsv_tab_number; 
$panel_array['panel_id'] = $panel_array['panel_name'].$panel_number;// creates a unique id, may change from version to version but within a version it should be unique
$panel_array['panel_intro'] = __('Select the column that holds your post title string or use templates instead');
$panel_array['panel_help'] = __('Selecting a title column will override title templates. You can only use one method, this is the most simple but requires your data to already have title strings in a single column. If you do not have pre-made title strings you should not use this single column feature.');
$panel_array['help_button'] = wtgcsv_helpbutton_text(false,false);
// Form Settings - create the array that is passed to jQuery form functions
$jsform_set_override = array();
$jsform_set = wtgcsv_jqueryform_commonarrayvalues($pageid,$panel_array['tabnumber'],$panel_array['panel_number'],$panel_array['panel_name'],$panel_array['panel_title'],$jsform_set_override);   
$jsform_set['dialoguebox_title'] = 'Save Title Column';
$jsform_set['noticebox_content'] = 'You are saving a column of data to be used as titles alone, do you wish to continue?';
// TODO: LOWPRIORITY, add a checkbox to allow user to save design for the mapping approach?>
<?php wtgcsv_panel_header( $panel_array );?>

    <?php wtgcsv_formstart_standard($jsform_set['form_name'],$jsform_set['form_id'],'post','wtgcsv_form',$wtgcsv_form_action);?>

    <strong>This feature is under construction, please use the template method which can do the same thing plus more</strong>

    <?php
    // add the javascript that will handle our form action, prevent submission and display dialogue box
    wtgcsv_jqueryform_singleaction_middle($jsform_set,$wtgcsv_options_array);

    // add end of form - dialogue box does not need to be within the <form>
    wtgcsv_formend_standard('Submit',$jsform_set['form_id']);?>

    <?php wtgcsv_jquery_form_prompt($jsform_set);?>

<?php wtgcsv_panel_footer();?>