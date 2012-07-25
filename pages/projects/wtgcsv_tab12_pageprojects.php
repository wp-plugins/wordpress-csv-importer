<?php
++$panel_number;// increase panel counter so this panel has unique ID
$panel_array = array();
$panel_array['panel_name'] = 'createrandomvalueshortcodes';// slug to act as a name and part of the panel ID 
$panel_array['panel_number'] = $panel_number;// number of panels counted on page, used to create object ID
$panel_array['panel_title'] = __('Create Advanced Random Value Shortcodes');// user seen panel header text 
$panel_array['pageid'] = $pageid;// store the $pageid for sake of ease
$panel_array['tabnumber'] = $wtgcsv_tab_number; 
$panel_array['panel_id'] = $panel_array['panel_name'].$panel_number;// creates a unique id, may change from version to version but within a version it should be unique
$panel_array['panel_intro'] = __('Create custom shortcodes for copying and pasting into content designs');
$panel_array['panel_help'] = __('Warning, not all content types will currently processing shortcodes i.e. custom field
content designs or category description content. This will eventually change but as I write this shortcode are intended
for use in post content designs. These shortcodes allow us to apply a random html value to our post html
content. This means we can randomise everything from plain text too html code such as images or links. We can add the random
value to any part of our content using shortcodes. There are endless things we can do, some simple, some complex. Not
everything Wordpress CSV Importer can do is obvious from looking at the interface, so if you have specific text spinning
needs please contact us.');
$panel_array['help_button'] = wtgcsv_helpbutton_text(false,true);
// Form Settings - create the array that is passed to jQuery form functions
$jsform_set_override = array();
$jsform_set = wtgcsv_jqueryform_commonarrayvalues($pageid,$panel_array['tabnumber'],$panel_array['panel_number'],$panel_array['panel_name'],$panel_array['panel_title'],$jsform_set_override);     
$jsform_set['dialoguebox_title'] = 'Save Text Spinning Rules';
$jsform_set['noticebox_content'] = 'Please remember to copy and paste text spinning shortcodes into your templates. Your changes to any rules in this panel will effect all posts using the related shortcodes, do you wish to continue?';?>
<?php wtgcsv_panel_header( $panel_array );?>

    <?php wtgcsv_formstart_standard($jsform_set['form_name'],$jsform_set['form_id'],'post','wtgcsv_form','');?>

    Shortcode Name: <input type="text" name="wtgcsv_shortcodename" size="40" value="" />
    <br /><br />
    Value 1: <input type="text" name="wtgcsv_textspin_v1" size="60" value="" />
    <br />                
    Value 2: <input type="text" name="wtgcsv_textspin_v2" size="60" value="" />
    <br />                
    Value 3: <input type="text" name="wtgcsv_textspin_v3" size="60" value="" />
    <br />
    Value 4: <input type="text" name="wtgcsv_textspin_v4" size="60" value="" />
    <br />
    Value 5: <input type="text" name="wtgcsv_textspin_v5" size="60" value="" />
    <br />
    Value 6: <input type="text" name="wtgcsv_textspin_v6" size="60" value="" />
    <br />
    Value 7: <input type="text" name="wtgcsv_textspin_v7" size="60" value="" />
    <br />
    Value 8: <input type="text" name="wtgcsv_textspin_v8" size="60" value="" />

    <?php 
    // if more fields are added, must update functions like this one wtgcsv_shortcode_textspinning_random as they expect 8
    ?>
    
    <br />
                                                 
    <?php
    // add the javascript that will handle our form action, prevent submission and display dialogue box
    wtgcsv_jqueryform_singleaction_middle($jsform_set,$wtgcsv_options_array);

    // add end of form - dialogue box does not need to be within the <form>
    wtgcsv_formend_standard('Submit',$jsform_set['form_id']);
                 
    wtgcsv_jquery_form_prompt($jsform_set);?>

<?php wtgcsv_panel_footer();?> 

<?php
++$panel_number;// increase panel counter so this panel has unique ID
$panel_array = array();
$panel_array['panel_name'] = 'deleterandomvalueshortcodes';// slug to act as a name and part of the panel ID 
$panel_array['panel_number'] = $panel_number;// number of panels counted on page, used to create object ID
$panel_array['panel_title'] = __('Delete Advanced Random Value Shortcodes');// user seen panel header text 
$panel_array['pageid'] = $pageid;// store the $pageid for sake of ease
$panel_array['tabnumber'] = $wtgcsv_tab_number; 
$panel_array['panel_id'] = $panel_array['panel_name'].$panel_number;// creates a unique id, may change from version to version but within a version it should be unique
$panel_array['panel_intro'] = __('Delete any of the advanced random value shortcodes previously created');
$panel_array['panel_help'] = __('Warning, if you delete advanced shortcodes that have already been used in post content. 
The shortcode will fail, the shortcode will be displayed as the raw shortcode. This is a standard behaviour by 
Wordpress if the supporting plugin is no longer active. This this case these advanced shortcodes in Wordpress
CSV Importer require the rules/settings data entered by the user to be kept stored in the Wordpress database.');
$panel_array['help_button'] = wtgcsv_helpbutton_text(false,true);
// Form Settings - create the array that is passed to jQuery form functions
$jsform_set_override = array();
$jsform_set = wtgcsv_jqueryform_commonarrayvalues($pageid,$panel_array['tabnumber'],$panel_array['panel_number'],$panel_array['panel_name'],$panel_array['panel_title'],$jsform_set_override);     
$jsform_set['dialoguebox_title'] = 'Save Text Spinning Rules';
$jsform_set['noticebox_content'] = 'Please remember to copy and paste text spinning shortcodes into your templates. Your changes to any rules in this panel will effect all posts using the related shortcodes, do you wish to continue?';?>
<?php wtgcsv_panel_header( $panel_array );?>

    <?php wtgcsv_formstart_standard($jsform_set['form_name'],$jsform_set['form_id'],'post','wtgcsv_form','');?>
    
    <?php 
    if(!isset($wtgcsv_textspin_array['randomvalue'])){
        echo '<p><strong>You do not have any advanced random shortcodes setup</strong></p>';    
    }else{
        foreach($wtgcsv_textspin_array['randomvalue'] as $name => $shortcode){
        echo $name;
        var_dump($shortcode);    
        }
    }
    ?>
                                                 
    <?php
    // add the javascript that will handle our form action, prevent submission and display dialogue box
    wtgcsv_jqueryform_singleaction_middle($jsform_set,$wtgcsv_options_array);

    // add end of form - dialogue box does not need to be within the <form>
    wtgcsv_formend_standard('Submit',$jsform_set['form_id']);
                 
    wtgcsv_jquery_form_prompt($jsform_set);?>

<?php wtgcsv_panel_footer();?>

<?php
if($wtgcsv_is_dev){
++$panel_number;// increase panel counter so this panel has unique ID
$panel_array = array();
$panel_array['panel_name'] = 'textspingarraydump';// slug to act as a name and part of the panel ID 
$panel_array['panel_number'] = $panel_number;// number of panels counted on page, used to create object ID
$panel_array['panel_title'] = __('Text Spin Array Dump');// user seen panel header text 
$panel_array['pageid'] = $pageid;// store the $pageid for sake of ease
$panel_array['tabnumber'] = $wtgcsv_tab_number; 
$panel_array['panel_id'] = $panel_array['panel_name'].$panel_number;// creates a unique id, may change from version to version but within a version it should be unique
$panel_array['panel_intro'] = __('Create custom shortcodes for copying and pasting into content designs');
$panel_array['panel_help'] = __('A dump of the text spin array. It holds all rules for text spinning.');
$panel_array['help_button'] = wtgcsv_helpbutton_text(false,true);?>
<?php wtgcsv_panel_header( $panel_array );?>
<?php
    if(!isset($wtgcsv_textspin_array) || !is_array($wtgcsv_textspin_array)){
        echo '<p>Text Spin array variable $wtgcsv_textspin_array is not set or is not an array so nothing can be displayed at this time.</p>';
    }else{
        echo '<pre>';
        var_dump($wtgcsv_textspin_array);
        echo '</pre>';        
    }    
wtgcsv_panel_footer();
}?> 