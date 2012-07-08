<?php
++$panel_number;// increase panel counter so this panel has unique ID
$panel_array = array();
$panel_array['panel_name'] = 'completionbar';// slug to act as a name and part of the panel ID 
$panel_array['panel_number'] = $panel_number;// number of panels counted on page, used to create object ID
$panel_array['panel_title'] = __('Completion Bar');// user seen panel header text 
$panel_array['pageid'] = $pageid;// store the $pageid for sake of ease
$panel_array['tabnumber'] = $wtgcsv_tab_number; 
$panel_array['panel_id'] = $panel_array['panel_name'].$panel_number;// creates a unique id, may change from version to version but within a version it should be unique
$panel_array['panel_intro'] = __('Use the links below to view posts created in your project named My Example Project');
$panel_array['panel_help'] = __('This panel allows you to quickly view specific posts created from any project. This is handy if you have multiple projects running at the same time and want to check what each project created.');
$panel_array['help_button'] = wtgcsv_helpbutton_text(true,false);
wtgcsv_panel_header( $panel_array );?>


<?php wtgcsv_panel_footer();?> 
