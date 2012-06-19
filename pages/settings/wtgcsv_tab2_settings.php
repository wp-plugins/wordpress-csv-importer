<?php global $wtgcsv_options_array;?> 

<?php
++$panel_number;// increase panel counter so this panel has unique ID
$panel_array = array();
$panel_array['panel_name'] = 'easyconfigurationquestions';// slug to act as a name and part of the panel ID 
$panel_array['panel_number'] = $panel_number;// number of panels counted on page, used to create object ID
$panel_array['panel_title'] = __('Easy Configuration Questions');// user seen panel header text 
$panel_array['pageid'] = $pageid;// store the $pageid for sake of ease
$panel_array['tabnumber'] = $wtgcsv_tab_number; 
$panel_array['panel_id'] = $panel_array['panel_name'].$panel_number;// creates a unique id, may change from version to version but within a version it should be unique
$panel_array['panel_intro'] = __('Answer questions to help configure the plugin interface the way you need it');
$panel_array['panel_help'] = __('Easy Configuration Questions are a great way for new Wordpress CSV Importer users to configure the plugin. Each answer works like a setting which change how the plugin works and what you see on the interface. Answers you give to the Easy Configuration Questions will cause the plugin to hide, display or change how a feature works. It is a great way to apply such configuration without knowing the features well. Please remember to reset the answers if you find the configuration is hiding something you need etc.'); 
$panel_array['help_button'] = 'Still Under Construction';?>
<?php wtgcsv_panel_header( $panel_array );?>

<?php wtgcsv_easy_configuration_questionlist(); ?>

<?php wtgcsv_panel_footer();?> 