<?php   
global $wtgcsv_installstatus_intro_0510,$wtgcsv_installstatus_help_0510,$wtgcsv_logfilestatus_intro_0710 ,$wtgcsv_logfilestatus_help_0710,$wtgcsv_databasetablesstatus_intro_0810,$wtgcsv_databasetablesstatus_help_0810; 
   
// include premium services status panel
include(WTG_CSV_PANELFOLDER_PATH.'premiumservicesstatus'.'.php');   

++$panel_number;// increase panel counter so this panel has unique ID
$panel_array = array();
$panel_array['panel_name'] = 'optionrecordsstatus';// slug to act as a name and part of the panel ID 
$panel_array['panel_number'] = $panel_number;// number of panels counted on page, used to create object ID
$panel_array['panel_title'] = __('Critical Option Records (settings) Status');// user seen panel header text 
$panel_array['pageid'] = $pageid;// store the $pageid for sake of ease
$panel_array['tabnumber'] = $wtgcsv_tab_number; 
$panel_array['panel_id'] = $panel_array['panel_name'].$panel_number;// creates a unique id, may change from version to version but within a version it should be unique
$panel_array['panel_intro'] = $wtgcsv_installstatus_intro_0510;
$panel_array['panel_help'] = $wtgcsv_installstatus_help_0510;
$panel_array['help_button'] = 'Still Under Construction'; 
?>
<?php wtgcsv_panel_header( $panel_array );?>
    
    <?php wtgcsv_install_optionstatus_list();?>
<?php wtgcsv_panel_footer();?> 

<?php
++$panel_number;// increase panel counter so this panel has unique ID
$panel_array = array();
$panel_array['panel_name'] = 'optionrecordtrace';// slug to act as a name and part of the panel ID 
$panel_array['panel_number'] = $panel_number;// number of panels counted on page, used to create object ID
$panel_array['panel_title'] = __('Option Record Trace');// user seen panel header text 
$panel_array['pageid'] = $pageid;// store the $pageid for sake of ease
$panel_array['tabnumber'] = $wtgcsv_tab_number; 
$panel_array['panel_id'] = $panel_array['panel_name'].$panel_number;// creates a unique id, may change from version to version but within a version it should be unique
$panel_array['panel_intro'] = 'Displays all records in the Wordpress options table created by Wordpress CSV Importer';
$panel_array['panel_help'] = 'This feature does a search for records that begin with "wtgcsv_". We can use it to fully cleanup the Wordpress option table.'; 
?>
<?php wtgcsv_panel_header( $panel_array );?>
    <?php wtgcsv_display_optionrecordtrace(); ?>       
<?php wtgcsv_panel_footer();?> 

<?php
++$panel_number;// increase panel counter so this panel has unique ID
$panel_array = array();
$panel_array['panel_name'] = 'contentfolderstatus';// slug to act as a name and part of the panel ID 
$panel_array['panel_number'] = $panel_number;// number of panels counted on page, used to create object ID
$panel_array['panel_title'] = __('Content Folder Status');// user seen panel header text 
$panel_array['pageid'] = $pageid;// store the $pageid for sake of ease
$panel_array['tabnumber'] = $wtgcsv_tab_number; 
$panel_array['panel_id'] = $panel_array['panel_name'].$panel_number;// creates a unique id, may change from version to version but within a version it should be unique
$panel_array['panel_intro'] = 'Status of the folder created to store CSV files within the Wordpress content directory';
$panel_array['panel_help'] = 'It is important that CSV files are not stored within the plugins own folder, currently named '.WTG_CSV_FOLDERNAME.' in order to prevent deletion of files. The plugin allows you to store CSV files in custom file paths but the default path and folder is created when installing '.WTG_CSV_PLUGINTITLE.'. The folder should be named '.WTG_CSV_CONTENTFOLDER_DIR.' and you will find it in the wp-content folder. If it is missing for any reason, you may create it manually.'; 
?>
<?php wtgcsv_panel_header( $panel_array );?>
    <?php wtgcsv_contentfolder_display_status(); ?>       
<?php wtgcsv_panel_footer();?> 

<?php
++$panel_number;// increase panel counter so this panel has unique ID
$panel_array = array();
$panel_array['panel_name'] = 'logfilesstatus';// slug to act as a name and part of the panel ID 
$panel_array['panel_number'] = $panel_number;// number of panels counted on page, used to create object ID
$panel_array['panel_title'] = __('Log Files Status');// user seen panel header text 
$panel_array['pageid'] = $pageid;// store the $pageid for sake of ease
$panel_array['tabnumber'] = $wtgcsv_tab_number; 
$panel_array['panel_id'] = $panel_array['panel_name'].$panel_number;// creates a unique id, may change from version to version but within a version it should be unique
$panel_array['panel_intro'] = $wtgcsv_logfilestatus_intro_0710;
$panel_array['panel_help'] = $wtgcsv_logfilestatus_help_0710; 
?>
<?php wtgcsv_panel_header( $panel_array );?>
    <?php wtgcsv_logfile_exists_notice('general');?>
    <?php wtgcsv_logfile_exists_notice('sql');?>
    <?php wtgcsv_logfile_exists_notice('admin');?>
    <?php wtgcsv_logfile_exists_notice('user');?>
    <?php wtgcsv_logfile_exists_notice('error');?>
<?php wtgcsv_panel_footer();?> 