<?php
++$panel_number;// increase panel counter so this panel has unique ID
$panel_array = array();
$panel_array['panel_name'] = 'createddatabasetableslist';// slug to act as a name and part of the panel ID 
$panel_array['panel_number'] = $panel_number;// number of panels counted on page, used to create object ID
$panel_array['panel_title'] = __('Created Database Tables List');// user seen panel header text 
$panel_array['pageid'] = $pageid;// store the $pageid for sake of ease
$panel_array['tabnumber'] = $wtgcsv_tab_number; 
$panel_array['panel_id'] = $panel_array['panel_name'].$panel_number;// creates a unique id, may change from version to version but within a version it should be unique
$panel_array['panel_intro'] = __('A list of database tables created by data import jobs');
$panel_array['panel_help'] = __('This list helps to monitor the number of tables created by the plugin. It indicates the rows inside each table. Should you be running multiple import jobs this table may help you to decide what data is ready to use for creating posts.');
$panel_array['help_button'] = wtgcsv_helpbutton_text(false,false);?>
<?php wtgcsv_panel_header( $panel_array );?> 

    <?php wtgcsv_display_jobtables();?>

<?php wtgcsv_panel_footer();?>