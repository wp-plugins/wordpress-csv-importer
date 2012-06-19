<?php
++$panel_number;// increase panel counter so this panel has unique ID
$panel_array = array();
$panel_array['panel_name'] = 'moreoffersspecialoffera';// slug to act as a name and part of the panel ID 
$panel_array['panel_number'] = $panel_number;// number of panels counted on page, used to create object ID
$panel_array['panel_title'] = __('Easy CSV Importer Customers');// user seen panel header text 
$panel_array['pageid'] = $pageid;// store the $pageid for sake of ease
$panel_array['tabnumber'] = $wtgcsv_tab_number; 
$panel_array['panel_id'] = $panel_array['panel_name'].$panel_number;// creates a unique id, may change from version to version but within a version it should be unique
$panel_array['panel_intro'] = __('You may be able to get the plugin free');
$panel_array['panel_help'] = __('Please send payments to paypal@webtechglobal.co.uk with a note explaining what the payment is for. Contact me first if you are not sure how much you should be paying.');
?>
<?php wtgcsv_panel_header( $panel_array );?>
            <p>If you have purchased Easy CSV Importer before 1st May 2012 you are entitled to get
            Wordpress CSV Importer 100% free. This offer was displayed on the Easy CSV Importer
            sales page. No one who purchases Easy CSV Importer after the 1st of May can get Wordpress
            CSV Importer free but they can get a discount equal to the amount they paid.</p>
<?php wtgcsv_panel_footer();?> 
