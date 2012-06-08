<?php  
// include premium services status panel
include(WTG_CSV_PANELFOLDER_PATH.'premiumservicesstatus'.'.php');   

++$panel_number;// increase panel counter so this panel has unique ID
$panel_array = array();
$panel_array['panel_name'] = 'loyaltypoints';// slug to act as a name and part of the panel ID 
$panel_array['panel_number'] = $panel_number;// number of panels counted on page, used to create object ID
$panel_array['panel_title'] = __('Loyalty Points');// user seen panel header text 
$panel_array['pageid'] = $pageid;// store the $pageid for sake of ease
$panel_array['tabnumber'] = $wtgcsv_tab_number; 
$panel_array['panel_id'] = $panel_array['panel_name'].$panel_number;// creates a unique id, may change from version to version but within a version it should be unique
$panel_array['panel_intro'] = __('Everything from purchases to beta testing is rewarded with points');
$panel_array['panel_help'] = __('This is a very new scheme and normally associated with a well established business however I would like to start early even if we simply issue points without a means to spend them until the proper systems are in place. I can however manage your points manually so if you happen to collect a lot do not ignore them. I plan to have points issued automatically for various activities. You can spend them on all of my services and products including web hosting.');
?>
 <?php wtgcsv_panel_header( $panel_array );?>

              <p>Points Available To Spend: 10</p>

              <p>No Loyalty Score history has been recorded.</p>

<?php wtgcsv_panel_footer();?> 

<?php
++$panel_number;// increase panel counter so this panel has unique ID
$panel_array = array();
$panel_array['panel_name'] = 'loyaltyscore';// slug to act as a name and part of the panel ID 
$panel_array['panel_number'] = $panel_number;// number of panels counted on page, used to create object ID
$panel_array['panel_title'] = __('Loyalty Score');// user seen panel header text 
$panel_array['pageid'] = $pageid;// store the $pageid for sake of ease
$panel_array['tabnumber'] = $wtgcsv_tab_number; 
$panel_array['panel_id'] = $panel_array['panel_name'].$panel_number;// creates a unique id, may change from version to version but within a version it should be unique
$panel_array['panel_intro'] = __('This score can be used to gain access to more special offers for the larger Web Development services');
$panel_array['panel_help'] = __('The Loyalty Score will not be in full use for a while. I have a lot of work to do to launch enough services and products to make the real purpose of the score worth it. The score will be used to access discounts on larger services such as web development, logo design or renting servers. The score will also act as a way to indicate your investment in WebTechGlobal. This score does not decrease but Loyalty Points do so Loyalty Points cannot tell me the same thing.');
?>
<?php wtgcsv_panel_header( $panel_array );?>

              <p>Score: 10</p>

              <p>No Loyalty Score history has been recorded.</p>

  <?php wtgcsv_panel_footer();?> 
