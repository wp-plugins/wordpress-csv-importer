<?php
++$panel_number;// increase panel counter so this panel has unique ID
$panel_array = array();
$panel_array['panel_name'] = 'standardcategories';// slug to act as a name and part of the panel ID 
$panel_array['panel_number'] = $panel_number;// number of panels counted on page, used to create object ID
$panel_array['panel_title'] = __('Standard Categories');// user seen panel header text 
$panel_array['pageid'] = $pageid;// store the $pageid for sake of ease
$panel_array['tabnumber'] = $wtgcsv_tab_number; 
$panel_array['panel_id'] = $panel_array['panel_name'].$panel_number;// creates a unique id, may change from version to version but within a version it should be unique
$panel_array['panel_intro'] = __('Create categories using your data and configure them to suit your theme');
$panel_array['panel_help'] = __('Category features will get more advanced during 2012. A full description and help content will be created when this panel has all planned options added. Currently you can create five levels of categories using your data simply by selecting each category column individually. This panel does not handle multiple levels of categories stored in a single column, that requires the Category Splitter approach.');
$panel_array['help_button'] = 'Still Under Construction';
// Form Settings - create the array that is passed to jQuery form functions
$jsform_set_override = array();
$jsform_set = wtgcsv_jqueryform_commonarrayvalues($pageid,$panel_array['tabnumber'],$panel_array['panel_number'],$panel_array['panel_name'],$panel_array['panel_title'],$jsform_set_override);               
$jsform_set['dialoguebox_title'] = 'Saving Category Columns';
$jsform_set['noticebox_content'] = 'Please ensure you have backed up your Wordpress database before running category creation. Would you like to save your category setup now?';
### TODO:HIGHPRIORITY, add default category option ?>
<?php wtgcsv_panel_header( $panel_array );?>

    <?php wtgcsv_formstart_standard($jsform_set['form_name'],$jsform_set['form_id'],'post','wtgcsv_form','');?>

    <h4>Setup Category Data Tables-Columns</h4>

    <select name="wtgcsv_categorylevel1_select_columnandtable" id="wtgcsv_categorylevel1_select_columnandtable_objectid" style="width: 500px; display: none; ">
        <option value="notselected">Exclude Level One</option>
        <?php 
        if(isset($wtgcsv_project_array['categories']['level1']['table']) && isset($wtgcsv_project_array['categories']['level1']['column'])){
            wtgcsv_display_project_columnsandtables_menuoptions($wtgcsv_currentproject_code,$wtgcsv_project_array['categories']['level1']['table'],$wtgcsv_project_array['categories']['level1']['column']);    
        }else{
            wtgcsv_display_project_columnsandtables_menuoptions($wtgcsv_currentproject_code);            
        }
        ?>                                                                                                                            
    </select>
    
    <script>
    $("#wtgcsv_categorylevel1_select_columnandtable_objectid").multiselect({
       multiple: false,
       header: "Select Level One Categories",
       noneSelectedText: "Select Level One Categories",
       selectedList: 1
    });
    </script>
        
    <br />
        
    <select name="wtgcsv_categorylevel2_select_columnandtable" id="wtgcsv_categorylevel2_select_columnandtable_objectid" style="width: 500px; display: none; ">
        <option value="notselected">Exclude Level Two</option>
        <?php 
        if(isset($wtgcsv_project_array['categories']['level2']['table']) && isset($wtgcsv_project_array['categories']['level2']['column'])){
            wtgcsv_display_project_columnsandtables_menuoptions($wtgcsv_currentproject_code,$wtgcsv_project_array['categories']['level2']['table'],$wtgcsv_project_array['categories']['level2']['column']);    
        }else{
            wtgcsv_display_project_columnsandtables_menuoptions($wtgcsv_currentproject_code);            
        }
        ?>                                                                                                                     
    </select>
        
    <script>
    $("#wtgcsv_categorylevel2_select_columnandtable_objectid").multiselect({
       multiple: false,
       header: "Select Level Two Categories",
       noneSelectedText: "Select Level Two Categories",
       selectedList: 1
    });
    </script>
    
    <br />
        
    <select name="wtgcsv_categorylevel3_select_columnandtable" id="wtgcsv_categorylevel3_select_columnandtable_objectid" style="width: 500px; display: none; ">
        <option value="notselected">Exclude Level Three</option>
        <?php 
        if(isset($wtgcsv_project_array['categories']['level3']['table']) && isset($wtgcsv_project_array['categories']['level3']['column'])){
            wtgcsv_display_project_columnsandtables_menuoptions($wtgcsv_currentproject_code,$wtgcsv_project_array['categories']['level3']['table'],$wtgcsv_project_array['categories']['level3']['column']);    
        }else{
            wtgcsv_display_project_columnsandtables_menuoptions($wtgcsv_currentproject_code);            
        }
        ?>                                                                                                                     
    </select>  
      
    <script>
    $("#wtgcsv_categorylevel3_select_columnandtable_objectid").multiselect({
       multiple: false,
       header: "Select Level Three Categories",
       noneSelectedText: "Select Level Three Categories",
       selectedList: 1
    });
    </script>

    <br />
    
    <?php 
    // if free edition we do not allow use of 4th and 5th level categories
    // bypassing this will only cause faults later, level 4 and 5 is connected to advanced fetures which require more support so are not provided free
    if(!$wtgcsv_is_free){?>
        
        <select name="wtgcsv_categorylevel4_select_columnandtable" id="wtgcsv_categorylevel4_select_columnandtable_objectid" style="width: 500px; display: none; ">
            <option value="notselected">Exclude Level Four</option>
            <?php 
            if(isset($wtgcsv_project_array['categories']['level4']['table']) && isset($wtgcsv_project_array['categories']['level4']['column'])){
                wtgcsv_display_project_columnsandtables_menuoptions($wtgcsv_currentproject_code,$wtgcsv_project_array['categories']['level4']['table'],$wtgcsv_project_array['categories']['level4']['column']);    
            }else{
                wtgcsv_display_project_columnsandtables_menuoptions($wtgcsv_currentproject_code);            
            }
            ?>                                                                                                                     
        </select>  
          
        <script>
        $("#wtgcsv_categorylevel4_select_columnandtable_objectid").multiselect({
           multiple: false,
           header: "Select Level Four Categories",
           noneSelectedText: "Select Level Four Categories",
           selectedList: 1
        });
        </script>

        <br />
        
        <select name="wtgcsv_categorylevel5_select_columnandtable" id="wtgcsv_categorylevel5_select_columnandtable_objectid" style="width: 500px; display: none; ">
            <option value="notselected">Exclude Level Five</option>
            <?php 
            if(isset($wtgcsv_project_array['categories']['level5']['table']) && isset($wtgcsv_project_array['categories']['level5']['column'])){
                wtgcsv_display_project_columnsandtables_menuoptions($wtgcsv_currentproject_code,$wtgcsv_project_array['categories']['level5']['table'],$wtgcsv_project_array['categories']['level5']['column']);    
            }else{
                wtgcsv_display_project_columnsandtables_menuoptions($wtgcsv_currentproject_code);            
            }
            ?>                                                                                                                     
        </select>  
          
        <script>
        $("#wtgcsv_categorylevel5_select_columnandtable_objectid").multiselect({
           multiple: false,
           header: "Select Level Five Categories",
           noneSelectedText: "Select Level Five Categories",
           selectedList: 1
        });
        </script>
        
        <br />
    <?php }?>
               
    <?php
    // add the javascript that will handle our form action, prevent submission and display dialogue box
    wtgcsv_jqueryform_singleaction_middle($jsform_set,$wtgcsv_options_array);

    // add end of form - dialogue box does not need to be within the <form>
    wtgcsv_formend_standard('Submit',$jsform_set['form_id']);?>

    <?php wtgcsv_jquery_form_prompt($jsform_set);?>

 <?php wtgcsv_panel_footer();?> 
  
<?php ### TODO:HIGHPRIORITY, add panel for Single Column Categories (category splitter approach)?>

<?php ### TODO:LOWPRIORITY,add panel that shows columns created with heirarchy ?> 

<?php ### TODO:LOWPRIORITY, add category tools panel (undo categories,categories data test for any possible issues based on settings)?> 