<?php
++$panel_number;// increase panel counter so this panel has unique ID
$panel_array = array();
$panel_array['panel_name'] = 'textspinningshortcodes';// slug to act as a name and part of the panel ID 
$panel_array['panel_number'] = $panel_number;// number of panels counted on page, used to create object ID
$panel_array['panel_title'] = __('Text Spinning Shortcodes');// user seen panel header text 
$panel_array['pageid'] = $pageid;// store the $pageid for sake of ease
$panel_array['tabnumber'] = $wtgcsv_tab_number; 
$panel_array['panel_id'] = $panel_array['panel_name'].$panel_number;// creates a unique id, may change from version to version but within a version it should be unique
$panel_array['panel_intro'] = __('Create manually written shortcodes for copying and pasting into content designs');
$panel_array['panel_help'] = __('These shortcodes allow us to apply a randomness to specific parts of our content so that everytime the page is refreshed it is different. Enter multiple values, similar in most cases so that your content reads the same thing but uses different grammer to do so. This helps to appear as if content on your site is constantly being updated, constantly changing and so your site is being maintained. To improve this effect I can add options to pause the shortcode on a single value for a set time i.e. a week. I am yet to determine the best approach for Google, research in this area would be very welcome.');
$panel_array['help_button'] = wtgcsv_helpbutton_text(true,true);
// Form Settings - create the array that is passed to jQuery form functions
$jsform_set_override = array();
$jsform_set = wtgcsv_jqueryform_commonarrayvalues($pageid,$panel_array['tabnumber'],$panel_array['panel_number'],$panel_array['panel_name'],$panel_array['panel_title'],$jsform_set_override);     
$jsform_set['dialoguebox_title'] = 'Save Text Spinning Rules';
$jsform_set['noticebox_content'] = 'Please remember to copy and paste text spinning shortcodes into your templates. Your changes to any rules in this panel will effect all posts using the related shortcodes, do you wish to continue?';?>
<?php wtgcsv_panel_header( $panel_array );?>

    <?php wtgcsv_formstart_standard($jsform_set['form_name'],$jsform_set['form_id'],'post','wtgcsv_form','');?>

        <h4>This panel is still under construction, thank you for your patience.<h4>
        
        <br />
        Shortcode Name: <input type="text" name="wtgcsv_shortcodename" size="40" />
        <br />
        Value 1: <input type="text" name="wtgcsv_valueone" size="100" />
        <br />                
        Value 2: <input type="text" name="wtgcsv_valuetwo" size="100" />
        <br />                
        Value 3: <input type="text" name="wtgcsv_valuethree" size="100" />
        <br />
        Value 4: <input type="text" name="wtgcsv_valuefour" size="100" />
        <br />
        Value 5: <input type="text" name="wtgcsv_valuefive" size="100" />
        <br />
    
        <h4>This panel is still under construction, thank you for your patience.<h4>
                                                            
    <?php
    // add the javascript that will handle our form action, prevent submission and display dialogue box
    wtgcsv_jqueryform_singleaction_middle($jsform_set,$wtgcsv_options_array);

    // add end of form - dialogue box does not need to be within the <form>
    wtgcsv_formend_standard('Submit',$jsform_set['form_id']);?>

    <h4>Your Shortcodes</h4>

  <table class="widefat post fixed">
        <tr class="first">
            <td width="150"><strong>Shortcode Name</strong></td>
            <td><strong>Value One</strong></td>
            <td><strong>Value Two</strong></td>
            <td><strong>Value Three</strong></td>
            <td><strong>Value Four</strong></td>
            <td><strong>Value Five</strong></td>                                                                                                               
        </tr>
        <tr>
            <td><strong>Mid Text Praise</strong></td>
            <td>brilliant</td>
            <td>great</td>
            <td>amazing</td>
            <td>excellent</td>
            <td>perfect</td>                                                                                                               
        </tr>
        <tr>
            <td><strong>Author Mention</strong></td>
            <td>Written By Zara Walsh</td>
            <td>By Zara Walsh</td>
            <td>Published By Ryan</td>
            <td>Author: Zara Walsh</td>
            <td>Zara Walsh wrote this post</td>                                                                                                               
        </tr>
        <tr>
            <td><strong>Offer Introduction</strong></td>
            <td>We have a short time offer for buy one get one free right now.</td>
            <td>Buy one get one free with our current limited time offer.</td>
            <td>You can take advantage of our buy one get one free offer.</td>
            <td></td>
            <td></td>                                                                                                               
        </tr>                                                                       
    </table>                
    
                        
    <?php wtgcsv_jquery_form_prompt($jsform_set);?>

<?php wtgcsv_panel_footer();?> 