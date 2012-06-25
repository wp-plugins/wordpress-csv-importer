<?php
++$panel_number;// increase panel counter so this panel has unique ID
$panel_array = array();
$panel_array['panel_name'] = 'defaultcategory';// slug to act as a name and part of the panel ID 
$panel_array['panel_number'] = $panel_number;// number of panels counted on page, used to create object ID
$panel_array['panel_title'] = __('Set Default Category');// user seen panel header text 
$panel_array['pageid'] = $pageid;// store the $pageid for sake of ease
$panel_array['tabnumber'] = $wtgcsv_tab_number; 
$panel_array['panel_id'] = $panel_array['panel_name'].$panel_number;// creates a unique id, may change from version to version but within a version it should be unique
$panel_array['panel_intro'] = __('Select an existing category in to act as a default');
$panel_array['panel_help'] = __('If you do not have category data you should select a default category. The menu will allow you to select any category already in your blog. It is recommended that you select a default category even if you want to use category data however it should not be used if your category data is complete. If any issues do arise when trying to create categories using your data, then you can expect to find posts in your default. If this does ever happen, please investigate it and seek support if you feel your data is complete.');
$panel_array['help_button'] = wtgcsv_helpbutton_text(false,false);
// Form Settings - create the array that is passed to jQuery form functions
$jsform_set_override = array();
$jsform_set = wtgcsv_jqueryform_commonarrayvalues($pageid,$panel_array['tabnumber'],$panel_array['panel_number'],$panel_array['panel_name'],$panel_array['panel_title'],$jsform_set_override);               
$jsform_set['dialoguebox_title'] = 'Save Default Category';
$jsform_set['noticebox_content'] = 'Do you want to save a default category now?';
### TODO:HIGHPRIORITY, add default category option ?>
<?php wtgcsv_panel_header( $panel_array );?>

    <?php wtgcsv_formstart_standard($jsform_set['form_name'],$jsform_set['form_id'],'post','wtgcsv_form','');?>

    <select name="wtgcsv_defaultcategory_select" id="wtgcsv_defaultcategory_select_id" class="wtgcsv_multiselect_menu">
        
        <?php
        $selected = ''; 
        if(!isset($wtgcsv_project_array['categories']['default'])){
            $selected = 'selected="selected"';
        }?>
        
        <option value="notselected" <?php echo $selected;?>>ID - Category Name (None Selected)</option> 
                
        <?php wtgcsv_display_categories_options($current_value);?>
                                                                                                                             
    </select>  
      
    <script>
    $("#wtgcsv_defaultcategory_select_id").multiselect({
       multiple: false,
       header: "Select Default Category",
       noneSelectedText: "Select Default Category",
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

<?php
++$panel_number;// increase panel counter so this panel has unique ID
$panel_array = array();
$panel_array['panel_name'] = 'standardcategories';// slug to act as a name and part of the panel ID 
$panel_array['panel_number'] = $panel_number;// number of panels counted on page, used to create object ID
$panel_array['panel_title'] = __('Set Standard Categories');// user seen panel header text 
$panel_array['pageid'] = $pageid;// store the $pageid for sake of ease
$panel_array['tabnumber'] = $wtgcsv_tab_number; 
$panel_array['panel_id'] = $panel_array['panel_name'].$panel_number;// creates a unique id, may change from version to version but within a version it should be unique
$panel_array['panel_intro'] = __('Create categories using your data and configure them to suit your theme');
$panel_array['panel_help'] = __('Category features will get more advanced during 2012. A full description and help content will be created when this panel has all planned options added. Currently you can create five levels of categories using your data simply by selecting each category column individually. This panel does not handle multiple levels of categories stored in a single column, that requires the Category Splitter approach.');
$panel_array['help_button'] = wtgcsv_helpbutton_text(false,false);
// Form Settings - create the array that is passed to jQuery form functions
$jsform_set_override = array();
$jsform_set = wtgcsv_jqueryform_commonarrayvalues($pageid,$panel_array['tabnumber'],$panel_array['panel_number'],$panel_array['panel_name'],$panel_array['panel_title'],$jsform_set_override);               
$jsform_set['dialoguebox_title'] = 'Saving Category Columns';
$jsform_set['noticebox_content'] = 'Please ensure you have backed up your Wordpress database before running category creation. Would you like to save your category setup now?';
### TODO:HIGHPRIORITY, add default category option ?>
<?php wtgcsv_panel_header( $panel_array );?>

    <?php wtgcsv_formstart_standard($jsform_set['form_name'],$jsform_set['form_id'],'post','wtgcsv_form','');?>

    <h4>Setup Category Data Columns</h4>

    <select name="wtgcsv_categorylevel1_select_columnandtable" id="wtgcsv_categorylevel1_select_columnandtable_objectid" class="wtgcsv_multiselect_menu">
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
        
    <select name="wtgcsv_categorylevel2_select_columnandtable" id="wtgcsv_categorylevel2_select_columnandtable_objectid" class="wtgcsv_multiselect_menu">
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
        
    <select name="wtgcsv_categorylevel3_select_columnandtable" id="wtgcsv_categorylevel3_select_columnandtable_objectid" class="wtgcsv_multiselect_menu">
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
        
        <select name="wtgcsv_categorylevel4_select_columnandtable" id="wtgcsv_categorylevel4_select_columnandtable_objectid" class="wtgcsv_multiselect_menu">
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
        
        <select name="wtgcsv_categorylevel5_select_columnandtable" id="wtgcsv_categorylevel5_select_columnandtable_objectid" class="wtgcsv_multiselect_menu">
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

<?php
if(!$wtgcsv_is_free){
++$panel_number;// increase panel counter so this panel has unique ID
$panel_array = array();
$panel_array['panel_name'] = 'advancedcategories';// slug to act as a name and part of the panel ID 
$panel_array['panel_number'] = $panel_number;// number of panels counted on page, used to create object ID
$panel_array['panel_title'] = __('Set Advanced Categories');// user seen panel header text 
$panel_array['pageid'] = $pageid;// store the $pageid for sake of ease
$panel_array['tabnumber'] = $wtgcsv_tab_number; 
$panel_array['panel_id'] = $panel_array['panel_name'].$panel_number;// creates a unique id, may change from version to version but within a version it should be unique
$panel_array['panel_intro'] = __('Take full control of category creation with more advanced category creation features');
$panel_array['panel_help'] = __('If you require a category description or mapping too existing categories rather than creating or only creating new categories, you will need to use this panel
. The plugin will need to do more during category creation, please keep this in mind when running post creation events.');
$panel_array['help_button'] = wtgcsv_helpbutton_text(true,true);
// Form Settings - create the array that is passed to jQuery form functions
$jsform_set_override = array();
$jsform_set = wtgcsv_jqueryform_commonarrayvalues($pageid,$panel_array['tabnumber'],$panel_array['panel_number'],$panel_array['panel_name'],$panel_array['panel_title'],$jsform_set_override);               
$jsform_set['dialoguebox_title'] = 'Saving Advanced Categories';
$jsform_set['noticebox_content'] = 'Please ensure you have backed up your Wordpress database before running category creation. Would you like to save your category setup now?';
### TODO:HIGHPRIORITY, add default category option ?>
<?php wtgcsv_panel_header( $panel_array );?>

    <?php wtgcsv_formstart_standard($jsform_set['form_name'],$jsform_set['form_id'],'post','wtgcsv_form','');?>

    <h4>Setup Category Data Columns</h4>

    <table>
    
        <tr><td>
        Level One:</td><td><select name="wtgcsv_categorylevel1_advanced" id="wtgcsv_categorylevel1_advanced" class="wtgcsv_multiselect_menu">
            <option value="notselected">Exclude</option>
            <?php 
            if(isset($wtgcsv_project_array['categories']['level1']['table']) && isset($wtgcsv_project_array['categories']['level1']['column'])){
                wtgcsv_display_project_columnsandtables_menuoptions($wtgcsv_currentproject_code,$wtgcsv_project_array['categories']['level1']['table'],$wtgcsv_project_array['categories']['level1']['column']);    
            }else{
                wtgcsv_display_project_columnsandtables_menuoptions($wtgcsv_currentproject_code);            
            }?>                                                                                                                            
        </select>
        
        <script>
        $("#wtgcsv_categorylevel1_advanced").multiselect({
           multiple: false,
           header: "Select Level One Categories",
           noneSelectedText: "Select Level One Categories",
           selectedList: 1
        });
        </script>
        </td></tr>
        <tr><td>    
        Level Two:</td><td><select name="wtgcsv_categorylevel2_advanced" id="wtgcsv_categorylevel2_advanced" class="wtgcsv_multiselect_menu">
            <option value="notselected">Exclude</option>
            <?php 
            if(isset($wtgcsv_project_array['categories']['level2']['table']) && isset($wtgcsv_project_array['categories']['level2']['column'])){
                wtgcsv_display_project_columnsandtables_menuoptions($wtgcsv_currentproject_code,$wtgcsv_project_array['categories']['level2']['table'],$wtgcsv_project_array['categories']['level2']['column']);    
            }else{
                wtgcsv_display_project_columnsandtables_menuoptions($wtgcsv_currentproject_code);            
            }?>                                                                                                                     
        </select>
            
        <script>
        $("#wtgcsv_categorylevel2_advanced").multiselect({
           multiple: false,
           header: "Select Level Two Categories",
           noneSelectedText: "Select Level Two Categories",
           selectedList: 1
        });
        </script>
        </td></tr>
        <tr><td>    
        Level Three:</td><td><select name="wtgcsv_categorylevel3_advanced" id="wtgcsv_categorylevel3_advanced" class="wtgcsv_multiselect_menu">
            <option value="notselected">Exclude</option>
            <?php 
            if(isset($wtgcsv_project_array['categories']['level3']['table']) && isset($wtgcsv_project_array['categories']['level3']['column'])){
                wtgcsv_display_project_columnsandtables_menuoptions($wtgcsv_currentproject_code,$wtgcsv_project_array['categories']['level3']['table'],$wtgcsv_project_array['categories']['level3']['column']);    
            }else{
                wtgcsv_display_project_columnsandtables_menuoptions($wtgcsv_currentproject_code);            
            }?>                                                                                                                     
        </select>  
          
        <script>
        $("#wtgcsv_categorylevel3_advanced").multiselect({
           multiple: false,
           header: "Select Level Three Categories",
           noneSelectedText: "Select Level Three Categories",
           selectedList: 1
        });
        </script>
        </td></tr>
        <tr><td>
        Level Four:</td><td><select name="wtgcsv_categorylevel4_advanced" id="wtgcsv_categorylevel4_advanced" class="wtgcsv_multiselect_menu">
            <option value="notselected">Exclude</option>
            <?php 
            if(isset($wtgcsv_project_array['categories']['level4']['table']) && isset($wtgcsv_project_array['categories']['level4']['column'])){
                wtgcsv_display_project_columnsandtables_menuoptions($wtgcsv_currentproject_code,$wtgcsv_project_array['categories']['level4']['table'],$wtgcsv_project_array['categories']['level4']['column']);    
            }else{
                wtgcsv_display_project_columnsandtables_menuoptions($wtgcsv_currentproject_code);            
            }?>                                                                                                                     
        </select>  
          
        <script>
        $("#wtgcsv_categorylevel4_advanced").multiselect({
           multiple: false,
           header: "Select Level Four Categories",
           noneSelectedText: "Select Level Four Categories",
           selectedList: 1
        });
        </script>
        </td></tr>
        <tr><td>
        Level Five:</td><td><select name="wtgcsv_categorylevel5_advanced" id="wtgcsv_categorylevel5_advanced" class="wtgcsv_multiselect_menu">
            <option value="notselected">Exclude</option>
            <?php 
            if(isset($wtgcsv_project_array['categories']['level5']['table']) && isset($wtgcsv_project_array['categories']['level5']['column'])){
                wtgcsv_display_project_columnsandtables_menuoptions($wtgcsv_currentproject_code,$wtgcsv_project_array['categories']['level5']['table'],$wtgcsv_project_array['categories']['level5']['column']);    
            }else{
                wtgcsv_display_project_columnsandtables_menuoptions($wtgcsv_currentproject_code);            
            }?>                                                                                                                     
        </select>  
          
        <script>
        $("#wtgcsv_categorylevel5_advanced ").multiselect({
           multiple: false,
           header: "Select Level Five Categories",
           noneSelectedText: "Select Level Five Categories",
           selectedList: 1
        });
        </script>
        </td></tr>
    
    </table>
    
    <br />

    <h4>Category Description Templates</h4>
    <?php 
    if(wtgcsv_count_contenttemplates('categorydescription') == 0){
        echo '<p><strong>You do not have any category description templates. Please create one or more on the Content screen.</strong></p>';
    }else{?>

        <table>
        
            <tr><td>Level One:</td><td>
            <select name="wtgcsv_categorylevel1_description" id="wtgcsv_categorylevel1_description" class="wtgcsv_multiselect_menu">
                <option value="notselected">No Description Required</option>
                
                <?php 
                $current_value = '';
                if(isset($wtgcsv_project_array['categories']['level1']['description'])){$current_value = $wtgcsv_project_array['categories']['level1']['description'];}
                ?>
                 
                <?php wtgcsv_display_template_options($current_value,'categorydescription');?>
                                                                                                                            
            </select>  
              
            <script>
            $("#wtgcsv_categorylevel1_description ").multiselect({
               multiple: false,
               header: "Select Level One Template",
               noneSelectedText: "Select Level One Template",
               selectedList: 1
            });
            </script>    
            </td></tr>

            <tr><td>Level Two:</td><td>
            <select name="wtgcsv_categorylevel2_description" id="wtgcsv_categorylevel2_description" class="wtgcsv_multiselect_menu">
                <option value="notselected">No Description Required</option>
                
                <?php 
                $current_value = '';
                if(isset($wtgcsv_project_array['categories']['level2']['description'])){$current_value = $wtgcsv_project_array['categories']['level2']['description'];}
                ?>
                 
                <?php wtgcsv_display_template_options($current_value,'categorydescription');?>
                                                                                                                            
            </select>  
              
            <script>
            $("#wtgcsv_categorylevel2_description ").multiselect({
               multiple: false,
               header: "Select Level Two Template",
               noneSelectedText: "Select Level Two Template",
               selectedList: 1
            });
            </script>    
            </td></tr>

            <tr><td>Level Three:</td><td>
            <select name="wtgcsv_categorylevel3_description" id="wtgcsv_categorylevel3_description" class="wtgcsv_multiselect_menu">
                <option value="notselected">No Description Required</option>
                
                <?php 
                $current_value = '';
                if(isset($wtgcsv_project_array['categories']['level3']['description'])){$current_value = $wtgcsv_project_array['categories']['level3']['description'];}
                ?>
                
                <?php wtgcsv_display_template_options($current_value,'categorydescription');?>
                                                                                                                            
            </select>  
              
            <script>
            $("#wtgcsv_categorylevel3_description ").multiselect({
               multiple: false,
               header: "Select Level Three Template",
               noneSelectedText: "Select Level Three Template",
               selectedList: 1
            });
            </script>    
            </td></tr>
            
            <tr><td>Level Four:</td><td>
            <select name="wtgcsv_categorylevel4_description" id="wtgcsv_categorylevel4_description" class="wtgcsv_multiselect_menu">
                <option value="notselected">No Description Required</option>
                
                <?php 
                $current_value = '';
                if(isset($wtgcsv_project_array['categories']['level4']['description'])){$current_value = $wtgcsv_project_array['categories']['level4']['description'];}
                ?>
                 
                <?php wtgcsv_display_template_options($current_value,'categorydescription');?>
                                                                                                                            
            </select>  
              
            <script>
            $("#wtgcsv_categorylevel4_description ").multiselect({
               multiple: false,
               header: "Select Level Four Template",
               noneSelectedText: "Select Level Four Template",
               selectedList: 1
            });
            </script>    
            </td></tr>
            
            <tr><td>Level Five:</td><td>
            <select name="wtgcsv_categorylevel5_description" id="wtgcsv_categorylevel5_description" class="wtgcsv_multiselect_menu">

                <?php 
                $current_value = '';
                $default_selected = '';
                if(isset($wtgcsv_project_array['categories']['level5']['description'])){$current_value = $wtgcsv_project_array['categories']['level5']['description'];}
                else{$default_selected = 'selected="selected"';}
                ?>
                
                <option value="notselected" <?php echo $default_selected;?>>No Description Required</option>
                                 
                <?php wtgcsv_display_template_options($current_value,'categorydescription');?>
                                                                                                                            
            </select>  
              
            <script>
            $("#wtgcsv_categorylevel5_description ").multiselect({
               multiple: false,
               header: "Select Level Five Template",
               noneSelectedText: "Select Level Five Template",
               selectedList: 1
            });
            </script>    
            </td></tr>
                                        
        </table>
    
    <?php }?>
        
    <br />
    
    <?php
    // add the javascript that will handle our form action, prevent submission and display dialogue box
    wtgcsv_jqueryform_singleaction_middle($jsform_set,$wtgcsv_options_array);

    // add end of form - dialogue box does not need to be within the <form>
    wtgcsv_formend_standard('Submit',$jsform_set['form_id']);?>

    <?php wtgcsv_jquery_form_prompt($jsform_set);?>

<?php wtgcsv_panel_footer();
}?> 

<?php
++$panel_number;// increase panel counter so this panel has unique ID
$panel_array = array();
$panel_array['panel_name'] = 'createcategorymappingrules';// slug to act as a name and part of the panel ID 
$panel_array['panel_number'] = $panel_number;// number of panels counted on page, used to create object ID
$panel_array['panel_title'] = __('Set Category Map');// user seen panel header text 
$panel_array['pageid'] = $pageid;// store the $pageid for sake of ease
$panel_array['tabnumber'] = $wtgcsv_tab_number; 
$panel_array['panel_id'] = $panel_array['panel_name'].$panel_number;// creates a unique id, may change from version to version but within a version it should be unique
$panel_array['panel_intro'] = __('Map values in your data to categories in your blog');
$panel_array['panel_help'] = __('This feature will overwrite category creation i.e. instead of using a term in your data to create a category, the post will be put in a category you have mapped the term to. This feature is for users who are auto-blogging but their data does not have matching category values. In that situation you can pair values to existing categories in your blog. You can do it with none category data, just as long as the values are small. This is not just an advanced feature but can be very complex. Consider what happens when a term in your data, is paired with an existing category. The plugin will use the existing categories ID, however if done wrong the next level of category term may not exist under the existing category as a child. I can add more settings to adapt how category creation works, please contact me to discuss your requirements.');
$panel_array['help_button'] = wtgcsv_helpbutton_text(false,true);
// Form Settings - create the array that is passed to jQuery form functions
$jsform_set_override = array();
$jsform_set = wtgcsv_jqueryform_commonarrayvalues($pageid,$panel_array['tabnumber'],$panel_array['panel_number'],$panel_array['panel_name'],$panel_array['panel_title'],$jsform_set_override);               
$jsform_set['dialoguebox_title'] = 'Save Category Mapping Rules';
$jsform_set['noticebox_content'] = 'Do you want to save category mapping rules now?';
### TODO:HIGHPRIORITY, add default category option ?>
<?php wtgcsv_panel_header( $panel_array );?>

    <?php wtgcsv_formstart_standard($jsform_set['form_name'],$jsform_set['form_id'],'post','wtgcsv_form','');?>

    <?php 
    function wtgcsv_display_categories_menu($increment){?>
     
        <select name="wtgcsv_createcategorymapping<?php echo $increment;?>_select" id="wtgcsv_createcategorymapping<?php echo $increment;?>_select_id" class="wtgcsv_multiselect_menu">
                  
            <option value="notselected">Not Selected</option> 
                    
            <?php wtgcsv_display_categories_options($current_value);?>
                                                                                                                                 
        </select>  
          
        <script>
        $("#wtgcsv_createcategorymapping<?php echo $increment;?>_select_id").multiselect({
           multiple: false,
           header: "Select Category",
           noneSelectedText: "Select Category",
           selectedList: 1
        });
        </script><?php    
        return '';
    }
    ?>
    
    <?php
    // we need to loop 5 times, once for each level
    $levels_looped = 0;// used in hidden input
    for($i = 1; $i <= 5; $i++){?> 
    
        <h1>Category Level <?php echo $i;?></h1>
        <?php 
        // is level one category set
        if(!isset($wtgcsv_project_array['categories']['level'.$i.'']['table']) || !isset($wtgcsv_project_array['categories']['level'.$i.'']['column'])){
        
            echo '<p><strong>You have not setup a level one category, this is required for any category creation or mapping</strong></p>';
        
        }else{
            
            // using level 1 table, query that there are records
            $records_count = wtgcsv_sql_counttablerecords($wtgcsv_project_array['categories']['level'.$i.'']['table']);
            if(!$records_count || $records_count == 0){
                
                echo '<p><strong>No records were found in the column and table being used for your level one categories</strong></p>';
            
            }else{
                
                $distinct_values_array = wtgcsv_sql_column_distinctvalues($wtgcsv_project_array['categories']['level'.$i]['table'],$wtgcsv_project_array['categories']['level'.$i]['column']);
                
                echo '<table>';
                    
                echo '<tr><td>Distinct Values</td><td>Blog Categories</td></tr>';
                
                // loop through distinct values, creating a table row for each, with menu for selecting category
                $increment = 0;// increment counts number if distinct values    
                foreach($distinct_values_array as $key => $distinct_value){
                    
                    ++$increment;
                    
                    $current_distinct_value = $distinct_value[ $wtgcsv_project_array['categories']['level'.$i]['column'] ];
                    
                    echo '<tr><td><input type="text" name="wtgcsv_distinct_value_lev'.$i.'_inc'.$increment.'" value="' . $current_distinct_value . '" size="30" ></td><td>';?>

                    <?php 
                    // check if DISTINCT value has been stored with a category already, if so pass the current value
                    $current_value = '';
                    $notselected = '';
                    if(isset($wtgcsv_project_array['categories']['level'.$i]['mapping'][ $current_distinct_value ])){
                        $current_value = $wtgcsv_project_array['categories']['level'.$i]['mapping'][ $current_distinct_value ];   
                    }else{
                        $notselected = 'selected="selected"';
                    }?>

                    <select name="wtgcsv_createcategorymapping_lev<?php echo $i;?>_inc<?php echo $increment;?>_select" id="wtgcsv_createcategorymapping_lev<?php echo $i;?>_inc<?php echo $increment;?>_select_id" class="wtgcsv_multiselect_menu">
                              
                        <option value="notselected" <?php echo $notselected;?>>Not Selected</option> 
                                
                        <?php wtgcsv_display_categories_options($current_value);?>
                                                                                                                                             
                    </select>  
                      
                    <script>
                    $("#wtgcsv_createcategorymapping_lev<?php echo $i;?>_inc<?php echo $increment;?>_select_id").multiselect({
                       multiple: false,
                       header: "Select Category",
                       noneSelectedText: "Select Category",
                       selectedList: 1
                    });
                    </script><?php

                }

                echo '</table>';             

                // add hidden value for holding total number of DISTINCT values
                echo '<input type="hidden" name="wtgcsv_distinct_values_count_lev'.$i.'" value="'.$increment.'">';
            }
     
            ++$levels_looped;
        }

    }// for
  
    echo '<input type="hidden" name="wtgcsv_category_levels" value="'.$levels_looped.'">';
             
    ?>
      
    <br />
       
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
