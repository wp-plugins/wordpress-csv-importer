<?php
++$panel_number;// increase panel counter so this panel has unique ID
$panel_array = array();
$panel_array['panel_name'] = 'morecommunitydonations';// slug to act as a name and part of the panel ID 
$panel_array['panel_number'] = $panel_number;// number of panels counted on page, used to create object ID
$panel_array['panel_title'] = __('Donations');// user seen panel header text 
$panel_array['pageid'] = $pageid;// store the $pageid for sake of ease
$panel_array['tabnumber'] = $wtgcsv_tab_number; 
$panel_array['panel_id'] = $panel_array['panel_name'].$panel_number;// creates a unique id, may change from version to version but within a version it should be unique
$panel_array['panel_intro'] = __('Information on how to donate and a list of donators who were happy to have their names mentioned on the plugin.');
$panel_array['panel_help'] = __('WebTechGlobal now has a lot of unseen support from investors who know they can trust, well mainly myself Zara Walsh. I have shown time after time that I can make money go a long way and eventually give a return on it. In most cases donations are £10.00 to £20.00 and this can easily be responded to with 4-5 more hours spent working on the plugin. If you donate and you are also waiting on a specific feature being added that I have already agreed to then please remind me and I will be happy to increase the priority of that feature. Please remember however that a donation is not a hire payment, I cannot promise completion dates and I must stress that if I have not agreed to a feature prior to your donation, the donation is not enough for me to agree to the addition of the feature. I will only make changes to the plugin when I feel it fits into my long term plans for the plugin.'); 
?>
<?php wtgcsv_panel_header( $panel_array );?>

            <p>No Donations Submitted</p>

<?php wtgcsv_panel_footer();?> 

<?php
++$panel_number;// increase panel counter so this panel has unique ID
$panel_array = array();
$panel_array['panel_name'] = 'morecommunitytestimonials';// slug to act as a name and part of the panel ID 
$panel_array['panel_number'] = $panel_number;// number of panels counted on page, used to create object ID
$panel_array['panel_title'] = __('Testimonials');// user seen panel header text 
$panel_array['pageid'] = $pageid;// store the $pageid for sake of ease
$panel_array['tabnumber'] = $wtgcsv_tab_number; 
$panel_array['panel_id'] = $panel_array['panel_name'].$panel_number;// creates a unique id, may change from version to version but within a version it should be unique
$panel_array['panel_intro'] = __('Read testimonials from other users for both the free edition and premium edition.');
$panel_array['panel_help'] = __('You can submit a testmonial here and testimonials by other users. There is no need to tell us what edition you are using, that is detected automatically. We really appreciate any time you take to provide any type of feedback to us.'); 
?>
<?php wtgcsv_panel_header( $panel_array );?>

            <p>No Donations Submitted</p>

<?php wtgcsv_panel_footer();?> 

<?php
++$panel_number;// increase panel counter so this panel has unique ID
$panel_array = array();
$panel_array['panel_name'] = 'morecommunitylatestforumthreads';// slug to act as a name and part of the panel ID 
$panel_array['panel_number'] = $panel_number;// number of panels counted on page, used to create object ID
$panel_array['panel_title'] = __('Latest Forum Threads');// user seen panel header text 
$panel_array['pageid'] = $pageid;// store the $pageid for sake of ease
$panel_array['tabnumber'] = $wtgcsv_tab_number; 
$panel_array['panel_id'] = $panel_array['panel_name'].$panel_number;// creates a unique id, may change from version to version but within a version it should be unique
$panel_array['panel_intro'] = __('Read the latest forum conversations related to this plugin from the plugins official forum.');
$panel_array['panel_help'] = __('I plan to make this feature more advanced with the ability to hold discussions on the plugins forum from your own blog. For now however it will show the latest discussions by all authors.'); 
?>
<?php wtgcsv_panel_header( $panel_array );?>

    <p>No Forum Threads Created</p>

<?php wtgcsv_panel_footer();?> 

<?php
++$panel_number;// increase panel counter so this panel has unique ID
$panel_array = array();
$panel_array['panel_name'] = 'morecommunityexampleblogs';// slug to act as a name and part of the panel ID 
$panel_array['panel_number'] = $panel_number;// number of panels counted on page, used to create object ID
$panel_array['panel_title'] = __('Example Blogs');// user seen panel header text 
$panel_array['pageid'] = $pageid;// store the $pageid for sake of ease
$panel_array['tabnumber'] = $wtgcsv_tab_number; 
$panel_array['panel_id'] = $panel_array['panel_name'].$panel_number;// creates a unique id, may change from version to version but within a version it should be unique
$panel_array['panel_intro'] = __('Submit your blog to be listed here if you are a proud user of this plugin.');
$panel_array['panel_help'] = __('We will list your website here and tweet that you are a proud user of this plugin. We are creating support software to make all of this automated, if you use any of our other plugins you will be able to quickly tell the world with the click of a button.'); 
?>
<?php wtgcsv_panel_header( $panel_array );?>

    <p>No Example Blogs Submitted</p>

<?php wtgcsv_panel_footer();?> 
