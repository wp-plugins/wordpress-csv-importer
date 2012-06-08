<?php
++$panel_number;// increase panel counter so this panel has unique ID
$panel_array = array();
$panel_array['panel_name'] = 'createbasiccustomfieldrules';// slug to act as a name and part of the panel ID 
$panel_array['panel_number'] = $panel_number;// number of panels counted on page, used to create object ID
$panel_array['panel_title'] = __('Create Basic Custom Field Rules');// user seen panel header text 
$panel_array['pageid'] = $pageid;// store the $pageid for sake of ease
$panel_array['tabnumber'] = $wtgcsv_tab_number; 
$panel_array['panel_id'] = $panel_array['panel_name'].$panel_number;// creates a unique id, may change from version to version but within a version it should be unique
$panel_array['panel_intro'] = __('Put your data into custom fields by entering the key and selecting the data column');
$panel_array['panel_help'] = __('You can setup as menu custom fields as you wish. Enter your custom field key (meta-key) you wish all your posts to have. Then select the column of data that you wish to populate your custom field value with.');
// Form Settings - create the array that is passed to jQuery form functions
$jsform_set_override = array();
$jsform_set = wtgcsv_jqueryform_commonarrayvalues($pageid,$panel_array['tabnumber'],$panel_array['panel_number'],$panel_array['panel_name'],$panel_array['panel_title'],$jsform_set_override);               
$jsform_set['dialoguebox_title'] = 'Save Custom Field Rule';
$jsform_set['noticebox_content'] = 'Do you want to save your new custom field rule and put the selected data into your entered meta-key?';
// TODO:MEDIUMPRIORITY,add option for default value for null data or option for custom field not to be added at all
?>
<?php wtgcsv_panel_header( $panel_array );?>

    <?php wtgcsv_formstart_standard($jsform_set['form_name'],$jsform_set['form_id'],'post','wtgcsv_form','');?>

    Enter Custom Field Key: <input type="text" name="wtgcsv_key" />
    
    <br /><br />

    Select Data Column: 
    <select name="wtgcsv_customfield_select_columnandtable" id="wtgcsv_customfield_select_columnandtable_formid" style="width: 350px; display: none; ">
        <?php wtgcsv_display_project_columnsandtables_menuoptions($wtgcsv_currentproject_code);?>                                                                                                                     
    </select>

    <script>
    $("#wtgcsv_customfield_select_columnandtable_formid").multiselect({
       multiple: false,
       header: "Select Database Column (table - column)",
       noneSelectedText: "Select Database Table",
       selectedList: 1
    });
    </script>

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
$panel_array['panel_name'] = 'deletebasiccustomfieldrules';// slug to act as a name and part of the panel ID 
$panel_array['panel_number'] = $panel_number;// number of panels counted on page, used to create object ID
$panel_array['panel_title'] = __('Delete Basic Custom Field Rules');// user seen panel header text 
$panel_array['pageid'] = $pageid;// store the $pageid for sake of ease
$panel_array['tabnumber'] = $wtgcsv_tab_number; 
$panel_array['panel_id'] = $panel_array['panel_name'].$panel_number;// creates a unique id, may change from version to version but within a version it should be unique
$panel_array['panel_intro'] = __('Your basic custom field rules are listed here for reference and to delete');
$panel_array['panel_help'] = __('Deleting a custom field/meta-key rule has no effect on posts already created with your current project. It will only discontinue the custom field from being created for new posts.');
$panel_array['help_button'] = 'Still Under Construction';
// Form Settings - create the array that is passed to jQuery form functions
$jsform_set_override = array();
$jsform_set = wtgcsv_jqueryform_commonarrayvalues($pageid,$panel_array['tabnumber'],$panel_array['panel_number'],$panel_array['panel_name'],$panel_array['panel_title'],$jsform_set_override);               
$jsform_set['dialoguebox_title'] = 'Delete Custom Field Rule';
$jsform_set['noticebox_content'] = 'Do you want to delete your custom field rule and discontinue the meta-key plus value being added to posts created by this project?';
// TODO:MEDIUMPRIORITY,add option for default value for null data or option for custom field not to be added at all
?>
<?php wtgcsv_panel_header( $panel_array );?>

    <?php wtgcsv_formstart_standard($jsform_set['form_name'],$jsform_set['form_id'],'post','wtgcsv_form','');?>

    <?php wtgcsv_table_customfield_rules_basic(); ?>

    <?php
    // add the javascript that will handle our form action, prevent submission and display dialogue box
    wtgcsv_jqueryform_singleaction_middle($jsform_set,$wtgcsv_options_array);

    // add end of form - dialogue box does not need to be within the <form>
    wtgcsv_formend_standard('Submit',$jsform_set['form_id']);?>

    <?php wtgcsv_jquery_form_prompt($jsform_set);?>

<?php wtgcsv_panel_footer();?> 

<?php // TODO: HIGHPRIORITY, add advanced custom field form, menu for selecting content designs, menu for special functions and text field for default values ?>

<?php
++$panel_number;// increase panel counter so this panel has unique ID
$panel_array = array();
$panel_array['panel_name'] = 'usedcustomfieldkeys';// slug to act as a name and part of the panel ID 
$panel_array['panel_number'] = $panel_number;// number of panels counted on page, used to create object ID
$panel_array['panel_title'] = __('Used Custom Field Keys *global panel*');// user seen panel header text 
$panel_array['pageid'] = $pageid;// store the $pageid for sake of ease
$panel_array['tabnumber'] = $wtgcsv_tab_number; 
$panel_array['panel_id'] = $panel_array['panel_name'].$panel_number;// creates a unique id, may change from version to version but within a version it should be unique
$panel_array['panel_intro'] = __('A list of all distinct meta-keys (used as custom field keys) for reference only');
$panel_array['panel_help'] = __('Any meta-key (custom field key) key you enter for a post will appear in this list. Some of Wordpress own standard/default meta-keys will also show, some will not. I have excluded custom field keys that I feel no one will ever wish to use. Should your project require a standard Wordpress meta-key not displayed in the list or in the menus please request it to be removed from the list of exclusions.');
$panel_array['help_button'] = 'Still Under Construction';?>
<?php wtgcsv_panel_header( $panel_array );?>
<?php wtgcsv_list_customfields(); ?>
<?php wtgcsv_panel_footer();?>