<?php
++$panel_number;// increase panel counter so this panel has unique ID
$panel_array = array();
$panel_array['panel_name'] = 'premadetagscolumn';// slug to act as a name and part of the panel ID 
$panel_array['panel_number'] = $panel_number;// number of panels counted on page, used to create object ID
$panel_array['panel_title'] = __('Pre-made Tags Column');// user seen panel header text 
$panel_array['pageid'] = $pageid;// store the $pageid for sake of ease
$panel_array['tabnumber'] = $wtgcsv_tab_number; 
$panel_array['panel_id'] = $panel_array['panel_name'].$panel_number;// creates a unique id, may change from version to version but within a version it should be unique
$panel_array['panel_intro'] = __('Select a column of pre-made tags as your default tags data if you have it');
$panel_array['panel_help'] = __('This feature does not generate tags for you. The plugin can generate tags for you and there are options to configure them for the best results but those features are in other panels. This is also an area I am willing to adapt to suit specific users needs should they request it. The default tags column must be a column of pre-made tags separated by comma. Comma is required by Wordpress, if you have tags separated by another character I could add a feature to replace them.');
$panel_array['help_button'] = wtgcsv_helpbutton_text(true,false);
// Form Settings - create the array that is passed to jQuery form functions
$jsform_set_override = array();
$jsform_set = wtgcsv_jqueryform_commonarrayvalues($pageid,$panel_array['tabnumber'],$panel_array['panel_number'],$panel_array['panel_name'],$panel_array['panel_title'],$jsform_set_override);     
$jsform_set['dialoguebox_title'] = 'Save Default Tags Column';
$jsform_set['noticebox_content'] = 'Posts created from here on will use the data in the selected column as tags, do you wish to continue?';
// TODO: MEDIUMPRIORITY, add ajax for adding and deleting tags
// TODO: LOWPRIORITY, add this panel too main settings to apply a global default to all projects, this panel will start by using those settings on first use?>
<?php wtgcsv_panel_header( $panel_array );?>

    <?php wtgcsv_formstart_standard($jsform_set['form_name'],$jsform_set['form_id'],'post','wtgcsv_form','');?>

    <p>         
        <select name="wtgcsv_defaulttagsdata_select_columnandtable" id="wtgcsv_defaulttagsdata_select_columnandtable_formid" class="wtgcsv_multiselect_menu">
            <option value="notselected">Tags Not Required</option>
            <?php 
            if(isset($wtgcsv_project_array['tags']['default']['table'])){
                $table = $wtgcsv_project_array['tags']['default']['table'];    
            }else{
                $table = 'Value Not Set';
            }   
            
            if(isset($wtgcsv_project_array['tags']['default']['column'])){
                $column = $wtgcsv_project_array['tags']['default']['column'];    
            }else{
                $column = 'Value Not Set';    
            }   
            ?>      
            <?php wtgcsv_display_project_columnsandtables_menuoptions($wtgcsv_currentproject_code,$table,$column);?>                                                                                                                     
        </select>
    </p>

    <script>
    $("#wtgcsv_defaulttagsdata_select_columnandtable_formid").multiselect({
       multiple: false,
       header: "Select Tags Data Column (table - column)",
       noneSelectedText: "Select Tags Data Column",
       selectedList: 1
    });
    </script>
         
    <?php
    // add the javascript that will handle our form action, prevent submission and display dialogue box
    wtgcsv_jqueryform_singleaction_middle($jsform_set,$wtgcsv_options_array);

    // add end of form - dialogue box does not need to be within the <form>
    wtgcsv_formend_standard('Submit',$jsform_set['form_id']);?>

    <?php wtgcsv_jquery_form_prompt($jsform_set);?>

<?php wtgcsv_panel_footer();?> 

<?php
if(!$wtgcsv_is_free){
++$panel_number;// increase panel counter so this panel has unique ID
$panel_array = array();
$panel_array['panel_name'] = 'tagrules';// slug to act as a name and part of the panel ID 
$panel_array['panel_number'] = $panel_number;// number of panels counted on page, used to create object ID
$panel_array['panel_title'] = __('Tag Rules *global panel*');// user seen panel header text 
$panel_array['pageid'] = $pageid;// store the $pageid for sake of ease
$panel_array['tabnumber'] = $wtgcsv_tab_number; 
$panel_array['panel_id'] = $panel_array['panel_name'].$panel_number;// creates a unique id, may change from version to version but within a version it should be unique
$panel_array['panel_intro'] = __('This panel has not been complete, thank you for your patience');
$panel_array['panel_help'] = __('This panel has not been complete, thank you for your patience');
$panel_array['help_button'] = wtgcsv_helpbutton_text(true,true);
// Form Settings - create the array that is passed to jQuery form functions
$jsform_set_override = array();
$jsform_set = wtgcsv_jqueryform_commonarrayvalues($pageid,$panel_array['tabnumber'],$panel_array['panel_number'],$panel_array['panel_name'],$panel_array['panel_title'],$jsform_set_override);     
$jsform_set['dialoguebox_title'] = 'Save Tag Rules';
$jsform_set['noticebox_content'] = 'Your changes will effect all posts created from here on, do you wish to continue?';
// TODO: MEDIUMPRIORITY, add ajax for adding and deleting tags
// TODO: LOWPRIORITY, add this panel too main settings to apply a global default to all projects, this panel will start by using those settings on first use?>
<?php wtgcsv_panel_header( $panel_array );?>

    <?php wtgcsv_formstart_standard($jsform_set['form_name'],$jsform_set['form_id'],'post','wtgcsv_form','');?>

        <h4>This panel is still under construction, thank you for your patience.<h4>
        
    <script>
    $(function() {
        $( "#radiod" ).buttonset();
    });
    </script>

    <div id="radiod">
        <input type="radio" id="radio1d" name="radiod" /><label for="radio1d">Disallow Numerics</label>
        <input type="radio" id="radio2d" name="radiod" checked="checked" /><label for="radio2d">Allow Numerics</label>
    </div>

    <br /><br />
    
    Tag String Length:<input type="text" name="wtgcsv_tagstringlength" size="5" />
    
    <br /><br />
    
    Tags Per Post:<input type="text" name="wtgcsv_tagsperpost" size="5" />
    
    <br /><br />
    
    Enter Excluded Tags:<input type="text" name="wtgcsv_excludedtag" size="70" /> (comma seperated)
    
    <br /><br />        

    <script>
        $(function() {
            $( ".wtgcsv_tagexclusioncheckboxes" ).button();
        });
    </script>
    <style>
        #format { margin-top: 2em; }
    </style>

    <table class="widefat post fixed">
        <tr class="first">
            <td width="78"></td>                    
            <td><strong>Excluded Term</strong></td>                                                                                  
        </tr>
        <tr>
            <td><input type="checkbox" id="check1test1" class="wtgcsv_tagexclusioncheckboxes" /><label for="check1test1">Delete</label></td>                                            
            <td>2011</td>                                                                                                               
        </tr> 
        <tr>
            <td><input type="checkbox" id="check1test2" class="wtgcsv_tagexclusioncheckboxes" /><label for="check1test2">Delete</label></td>                        
            <td>profanity</td>                                                                                                               
        </tr>
        <tr>
            <td><input type="checkbox" id="check1test3" class="wtgcsv_tagexclusioncheckboxes" /><label for="check1test3">Delete</label></td>                        
            <td>http</td>                                                                                                               
        </tr>                                                                                                              
    </table>
    
    <h4>This panel is still under construction, thank you for your patience.<h4>
    
    <?php
    // add the javascript that will handle our form action, prevent submission and display dialogue box
    wtgcsv_jqueryform_singleaction_middle($jsform_set,$wtgcsv_options_array);

    // add end of form - dialogue box does not need to be within the <form>
    wtgcsv_formend_standard('Submit',$jsform_set['form_id']);?>

    <?php wtgcsv_jquery_form_prompt($jsform_set);?>

<?php wtgcsv_panel_footer();
}?> 