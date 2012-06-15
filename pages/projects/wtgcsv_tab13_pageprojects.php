<?php
++$panel_number;// increase panel counter so this panel has unique ID
$panel_array = array();
$panel_array['panel_name'] = 'databasetablesmapping';// slug to act as a name and part of the panel ID 
$panel_array['panel_number'] = $panel_number;// number of panels counted on page, used to create object ID
$panel_array['panel_title'] = __('Database Tables Mapping *current project*');// user seen panel header text 
$panel_array['pageid'] = $pageid;// store the $pageid for sake of ease
$panel_array['tabnumber'] = $wtgcsv_tab_number; 
$panel_array['panel_id'] = $panel_array['panel_name'].$panel_number;// creates a unique id, may change from version to version but within a version it should be unique
$panel_array['panel_intro'] = __('Set columns that connects records across multiple tables together');
$panel_array['panel_help'] = __('This is considered an advanced feature but required if your project is making use of multiple database tables. You need to tell the plugin how to match records in different tables to each other, unless you are confident all data in all selected tables is in a matching order. Then you must configure Wordpress CSV Importer so that it works by the standard MySQL ID column or select a single column name for a column of data that exists in every table, that data will then allow Wordpress CSV Importer to make the connection from record to record.');
$panel_array['help_button'] = 'Still Under Construction';
// Form Settings - create the array that is passed to jQuery form functions
$jsform_set_override = array();
$jsform_set = wtgcsv_jqueryform_commonarrayvalues($pageid,$panel_array['tabnumber'],$panel_array['panel_number'],$panel_array['panel_name'],$panel_array['panel_title'],$jsform_set_override);            
$jsform_set['dialoguebox_title'] = 'Map Projects Database Tables';
$jsform_set['noticebox_content'] = 'Mapping is important for multiple table projects, if you do not map the key columns correctly then the plugin will not be able to create posts properly or fail when trying to do so. Do you wish to continue saving now?';?>
<?php wtgcsv_panel_header( $panel_array );?>

    <?php wtgcsv_formstart_standard($jsform_set['form_name'],$jsform_set['form_id'],'post','wtgcsv_form','');?>
    
    <strong>This feature is not yet available, post creation scripts will currently assume all records in all
    database tables are in matching order. If your records are not currently in matching order then
    you require this feature to be complete so that Wordpress CSV Importer can query each database table
    individually and combine the correct records to make a post with.</strong>
    
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
$panel_array['panel_name'] = 'currentprojectdatabasetables';// slug to act as a name and part of the panel ID 
$panel_array['panel_number'] = $panel_number;// number of panels counted on page, used to create object ID
$panel_array['panel_title'] = __('Database Tables *current project*');// user seen panel header text 
$panel_array['pageid'] = $pageid;// store the $pageid for sake of ease
$panel_array['tabnumber'] = $wtgcsv_tab_number; 
$panel_array['panel_id'] = $panel_array['panel_name'].$panel_number;// creates a unique id, may change from version to version but within a version it should be unique
$panel_array['panel_intro'] = __('A list of the tables and columns being used in your project');
$panel_array['panel_help'] = __('You currently cannot change the tables added to your project due to complications that it could lead to when other configuration in project settings has been complete. If you find yourself in need of the ability to make changes to your projects tables long into post creation, it is possible but the features do not exist yet. Let me know you need the ability to make changes here.');?>
<?php wtgcsv_panel_header( $panel_array );?>

    <?php wtgcsv_display_project_database_tables_and_columns(); ?>

<?php wtgcsv_panel_footer();?>
