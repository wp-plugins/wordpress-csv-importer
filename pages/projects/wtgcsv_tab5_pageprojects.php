<?php
++$panel_number;// increase panel counter so this panel has unique ID
$panel_array = array();
$panel_array['panel_name'] = 'postdatescolumn';// slug to act as a name and part of the panel ID 
$panel_array['panel_number'] = $panel_number;// number of panels counted on page, used to create object ID
$panel_array['panel_title'] = 'Post Dates Column: ' .  wtgcsv_get_project_datecolumn($wtgcsv_currentproject_code);// user seen panel header text 
$panel_array['pageid'] = $pageid;// store the $pageid for sake of ease
$panel_array['tabnumber'] = $wtgcsv_tab_number; 
$panel_array['panel_id'] = $panel_array['panel_name'].$panel_number;// creates a unique id, may change from version to version but within a version it should be unique
$panel_array['panel_intro'] = __('You can select a column of dates if you do not want Wordpress to decide');
$panel_array['panel_help'] = __('The creation page allows you to configure dates and allow the plugin to set dates for posts. However if you have a column of pre-set dates you can over-ride all other date settings by selecting the dates column in this panel.');
// Form Settings - create the array that is passed to jQuery form functions
$jsform_set_override = array();
$jsform_set = wtgcsv_jqueryform_commonarrayvalues($pageid,$panel_array['tabnumber'],$panel_array['panel_number'],$panel_array['panel_name'],$panel_array['panel_title'],$jsform_set_override);                
$jsform_set['dialoguebox_title'] = 'Auto Complete Example '.WTG_CSV_PLUGINTITLE;
$jsform_set['noticebox_content'] = 'Do you want to install any required option records or tables for '.WTG_CSV_PLUGINTITLE.'?';
// TODO: HIGHPRIORITY, write function to test dates, display on this panel and use in processing?>
<?php wtgcsv_panel_header( $panel_array );?>

    <?php wtgcsv_formstart_standard($jsform_set['form_name'],$jsform_set['form_id'],'post','wtgcsv_form','');?>
 
    <h4>Current Date Table-Column: <?php echo wtgcsv_get_project_datecolumn($wtgcsv_currentproject_code); ?></h4>
    
    <select name="wtgcsv_datecolumn_select_columnandtable" id="wtgcsv_datecolumn_select_columnandtable_formid" style="width: 500px; display: none; ">
        <?php wtgcsv_display_project_columnsandtables_menuoptions($wtgcsv_currentproject_code);?>                                                                                                                     
    </select>

    <script>
    $("#wtgcsv_datecolumn_select_columnandtable_formid").multiselect({
       multiple: false,
       header: "Select Database Column (table - column)",
       noneSelectedText: "Select Database Table",
       selectedList: 1
    });
    </script>
    
    <br />
    
    <?php
    // add the javascript that will handle our form action, prevent submission and display dialogue box
    wtgcsv_jqueryform_singleaction_middle($jsform_set,$wtgcsv_options_array);

    // add end of form - dialogue box does not need to be within the <form>
    wtgcsv_formend_standard('Submit',$jsform_set['form_id']);?>

    <?php wtgcsv_jquery_form_prompt($jsform_set);?>

<?php wtgcsv_panel_footer();?>