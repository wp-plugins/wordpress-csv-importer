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
$panel_array['help_button'] = wtgcsv_helpbutton_text(true,true);
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
$panel_array['panel_help'] = __('You currently cannot change the tables added to your project due to complications that it could lead to when other configuration in project settings has been complete. If you find yourself in need of the ability to make changes to your projects tables long into post creation, it is possible but the features do not exist yet. Let me know you need the ability to make changes here.');
$panel_array['help_button'] = wtgcsv_helpbutton_text(false,false);?>
<?php wtgcsv_panel_header( $panel_array );?>

    <?php wtgcsv_display_project_database_tables_and_columns(); ?>

<?php wtgcsv_panel_footer();?>

<?php
if($wtgcsv_is_dev && $wtgcsv_project_array){
    ++$panel_number;// increase panel counter so this panel has unique ID
    $panel_array = array();
    $panel_array['panel_name'] = 'projectarraydump';// slug to act as a name and part of the panel ID 
    $panel_array['panel_number'] = $panel_number;// number of panels counted on page, used to create object ID
    $panel_array['panel_title'] = __('Project Array Dump');// user seen panel header text 
    $panel_array['pageid'] = $pageid;// store the $pageid for sake of ease
    $panel_array['tabnumber'] = $wtgcsv_tab_number; 
    $panel_array['panel_id'] = $panel_array['panel_name'].$panel_number;// creates a unique id, may change from version to version but within a version it should be unique
    $panel_array['panel_intro'] = __('A dump of your Current Project settings, stored as a PHP Array in the Wordpress option table');
    $panel_array['panel_help'] = __('The array dump shows the values that Wordpress CSV Importer works with and is intended for advanced users. This panel only shows when Developer Mode is active, with the idea that only developers would really have use for what is then displayed. The more data in this array, the higher chance there is of post creation being slower. Not because there are more values in this array, but because the values trigger more functions to be used. If you see values in the array for settings and features you realise you do not need. It is recommended that you remove them by visiting the applicable screens and panels.');
    $panel_array['help_button'] = wtgcsv_helpbutton_text(false,true);?>
    <?php wtgcsv_panel_header( $panel_array );?>

        <h4>Post Meta (Custom Fields)</h4>
        <?php
        if(!isset($wtgcsv_project_array['custom_fields'])){
            echo '<p>No [custom_fields] array found, your project is not prepared for adding post meta</p>';
        }else{ 
            echo '<pre>';
            var_dump($wtgcsv_project_array['custom_fields']);
            echo '</pre>';
        }?>
            
        <h4>Statistics (stats)</h4>
        <?php
        if(!isset($wtgcsv_project_array['stats'])){
            echo '<p>No [stats] array found, this is required and must be investigated</p>';
        }else{ 
            echo '<pre>';
            var_dump($wtgcsv_project_array['stats']);
            echo '</pre>';
        }?>   
             
        <h4>Dates</h4>
        <?php
        if(!isset($wtgcsv_project_array['dates'])){
            echo '<p>No [dates] array exists, publish dates will default to the time they are created on</p>';
        }else{ 
            echo '<pre>';
            var_dump($wtgcsv_project_array['dates']);
            echo '</pre>';
        }?> 
        
        <h4>Post Type Rules (posttyperules)</h4>
        <?php
        if(!isset($wtgcsv_project_array['posttyperules'])){
            echo '<p>No post-type rules have been setup in this project, all posts will be "posts"</p>';    
        }else{ 
            echo '<pre>';
            var_dump($wtgcsv_project_array['posttyperules']);
            echo '</pre>';    
        }?>
        
        <h4>Categories</h4>
        <?php
        if(!isset($wtgcsv_project_array['categories'])){
            echo '<p>No categories or category rules setup for this project, category with ID one will be the default.</p>';    
        }else{ 
            echo '<pre>';
            var_dump($wtgcsv_project_array['categories']);
            echo '</pre>';    
        }?>        
               
        <h4>Entire Array</h4>
        <?php 
        echo '<pre>';
        var_dump($wtgcsv_project_array);
        echo '</pre>';?>

    <?php wtgcsv_panel_footer();
}?>
