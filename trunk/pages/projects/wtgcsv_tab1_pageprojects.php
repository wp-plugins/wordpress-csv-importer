<?php  
++$panel_number;// increase panel counter so this panel has unique ID
$panel_array = array();
$panel_array['panel_name'] = 'defaultcontenttemplate';// slug to act as a name and part of the panel ID 
$panel_array['panel_number'] = $panel_number;// number of panels counted on page, used to create object ID
$panel_array['panel_title'] = __('Set Default Content Template (Currently: '.wtgcsv_get_default_contenttemplate_name().')');// user seen panel header text 
$panel_array['pageid'] = $pageid;// store the $pageid for sake of ease
$panel_array['tabnumber'] = $wtgcsv_tab_number; 
$panel_array['panel_id'] = $panel_array['panel_name'].$panel_number;// creates a unique id, may change from version to version but within a version it should be unique
$panel_array['panel_intro'] = __('Set the default template to be used for building post content for your current project');
$panel_array['panel_help'] = __('Click on any design to make it your default for the current project. Every project requires a default design to be selected. Eventually the plugin will automatically set a default based on the first template design edited in the WYSIWYG editor. Currently users must set it themselves using this panel. All post content will be based on the default design unless rules with conditions are setup for other templates to be applied instead. Doing that is considerd an advanced ability that most users do not need.');
$panel_array['help_button'] = wtgcsv_helpbutton_text(false,false);
?>
<?php wtgcsv_panel_header( $panel_array );?>
                 
    <form id="wtgcsv_form_opentemplate_id" action="<?php echo $wtgcsv_form_action;?>" method="post" name="wtgcsv_form_opentemplate_name">
        <input type="hidden" id="wtgcsv_post_processing_required" name="wtgcsv_post_processing_required" value="true">               
        
        <input type="hidden" name="wtgcsv_current_project_id" value="<?php echo $wtgcsv_currentproject_code;?>">
        <input type="hidden" name="wtgcsv_change_default_contenttemplate" value="true">        

        <h4>Current Projects Default Content Template</h4> 
        <?php echo wtgcsv_get_default_contenttemplate_name();?>
            
        <h4>Current Project Content Templates</h4>
        <?php wtgcsv_displayproject_contenttemplates_buttonlist('wtgcsv_selecttemplate_fromproject_id');?>          
    
        <h4>All Content Designs</h4>
        <?php wtgcsv_display_all_contentdesigns_buttonlist();?>            

    </form>

 <?php wtgcsv_panel_footer();?> 
 
<?php
++$panel_number;// increase panel counter so this panel has unique ID
$panel_array = array();
$panel_array['panel_name'] = 'opentemplates';// slug to act as a name and part of the panel ID 
$panel_array['panel_number'] = $panel_number;// number of panels counted on page, used to create object ID
$panel_array['panel_title'] = __('Open Template For Editing');// user seen panel header text 
$panel_array['pageid'] = $pageid;// store the $pageid for sake of ease
$panel_array['tabnumber'] = $wtgcsv_tab_number; 
$panel_array['panel_id'] = $panel_array['panel_name'].$panel_number;// creates a unique id, may change from version to version but within a version it should be unique
$panel_array['panel_intro'] = __('Open existing template designs, any template made private will not show in these lists');
$panel_array['panel_help'] = __('You can open and edit an existing content template. Click on the template you wish to edit. When the browser loads the WYSIWYG editor will contain your selected template.');
$panel_array['help_button'] = wtgcsv_helpbutton_text(false,false);
?>
<?php wtgcsv_panel_header( $panel_array );?>

    <form id="wtgcsv_form_opentemplate_id" method="post" name="wtgcsv_form_opentemplate_name" action="<?php echo $wtgcsv_form_action;?>">
        <input type="hidden" id="wtgcsv_post_processing_required" name="wtgcsv_post_processing_required" value="true">               
        
        <input type="hidden" name="wtgcsv_current_project_id" value="<?php echo $wtgcsv_currentproject_code;?>">
        <input type="hidden" name="wtgcsv_opencontentdesign" value="true">        
    
        <h4>Current Project Designs</h4>
        <?php wtgcsv_displayproject_contenttemplates_buttonlist('wtgcsv_selecttemplate_fromproject_id');?>          
    
        <h4>All Designs</h4>
        <?php wtgcsv_display_all_contentdesigns_buttonlist();?>            

    </form>

<?php wtgcsv_panel_footer();?> 

<?php
++$panel_number;// increase panel counter so this panel has unique ID
$panel_array = array();
$panel_array['panel_name'] = 'columnreplacementtokens';// slug to act as a name and part of the panel ID 
$panel_array['panel_number'] = $panel_number;// number of panels counted on page, used to create object ID
$panel_array['panel_title'] = __('Column Replacement Tokens');// user seen panel header text 
$panel_array['pageid'] = $pageid;// store the $pageid for sake of ease
$panel_array['tabnumber'] = $wtgcsv_tab_number; 
$panel_array['panel_id'] = $panel_array['panel_name'].$panel_number;// creates a unique id, may change from version to version but within a version it should be unique
$panel_array['panel_intro'] = __('Copy and paste these to place your data where you will need it in your final posts');
$panel_array['panel_help'] = __('There is a token for each of your database columns and a group of them for each table included in your project. You will notice numbers appended to the column names. Those numbers increment per table and prevent duplicate column names conflicting. These are replaced with data from the column they represent. Please be aware that any content which matches these strings will also be replaced however the asterix between table name and column name should help to prevent this.');
$panel_array['help_button'] = wtgcsv_helpbutton_text(false,false);?>
<?php wtgcsv_panel_header( $panel_array );?>

<?php wtgcsv_list_replacement_tokens($wtgcsv_currentproject_code);?>

<?php wtgcsv_panel_footer();?>                           
                          
                          
<?php
++$panel_number;// increase panel counter so this panel has unique ID
$panel_array = array();
$panel_array['panel_name'] = 'templateeditor';// slug to act as a name and part of the panel ID 
$panel_array['panel_number'] = $panel_number;// number of panels counted on page, used to create object ID
$panel_array['panel_title'] = __('Create Content Templates - WYSIWYG Editor *global panel*');// user seen panel header text 
$panel_array['pageid'] = $pageid;// store the $pageid for sake of ease
$panel_array['tabnumber'] = $wtgcsv_tab_number; 
$panel_array['panel_id'] = $panel_array['panel_name'].$panel_number;// creates a unique id, may change from version to version but within a version it should be unique
$panel_array['panel_intro'] = __('Create html designs/templates for various types of Wordpress content');
$panel_array['panel_help'] = __('The WYSIWYG editor can be used to create the html content design/template for post content, post excerpts, category descriptions, custom field values and even widget content. The idea is that you tell the plugin what your new design is for, you take control of your design and ensure it is suitable for the type of content it will be used to create. All you need to do is copy and paste your column replacement tokens where you want that columns data to be placed, keeping in mind that this happens on the html side because the design view is created using the html.');
$panel_array['help_button'] = wtgcsv_helpbutton_text(false,false);
// Form Settings - create the array that is passed to jQuery form functions
$jsform_set_override = array();
$jsform_set = wtgcsv_jqueryform_commonarrayvalues($pageid,$panel_array['tabnumber'],$panel_array['panel_number'],$panel_array['panel_name'],$panel_array['panel_title'],$jsform_set_override);   
$jsform_set['dialoguebox_title'] = 'Saving Content Design';
$jsform_set['noticebox_content'] = 'You are about to create or update a content template design, click submit if you would like to continue?';         
// get request template design, if none request will return empty array
$templatedesign_array = wtgcsv_get_template_bypostrequest();?>
<?php wtgcsv_panel_header( $panel_array );?>
  
    <?php wtgcsv_formstart_standard($jsform_set['form_name'],$jsform_set['form_id'],'post','wtgcsv_form',$wtgcsv_form_action);?>
    
    <!-- add template title to hidden field for comparison on submission, if user has changed the title it will trigger new template to be created -->
    <input type="hidden" name="wtgcsv_templatename_previous" value="<?php echo $templatedesign_array['template_name'];?>">
         
    <?php 
    if($wtgcsv_is_free){
        wtgcsv_display_designtype_menu($templatedesign_array['template_id']);
    }else{
        wtgcsv_display_designtype_menu_advanced($templatedesign_array['template_id']);
    }?>
    

    Design Name: <input type="text" name="wtgcsv_templatename" size="50" value="<?php echo $templatedesign_array['template_name'];?>" /> 
    Design ID: <input type="text" name="wtgcsv_templateid" size="5" value="<?php echo $templatedesign_array['template_id'];?>" readonly />
                                        
    <div id="poststuff">
        <?php the_editor($templatedesign_array['template_content'],'wtgcsv_wysiwyg_editor','', false);?>
    </div>

    <?php
    // add the javascript that will handle our form action, prevent submission and display dialogue box
    wtgcsv_jqueryform_singleaction_middle($jsform_set,$wtgcsv_options_array);

    // add end of form - dialogue b25ox does not need to be within the <form>
    wtgcsv_formend_standard('Submit',$jsform_set['form_id']);?>
              
    <?php wtgcsv_jquery_form_prompt($jsform_set);?>

<?php wtgcsv_panel_footer();?> 

<?php
if(!$wtgcsv_is_free){
++$panel_number;// increase panel counter so this panel has unique ID
$panel_array = array();
$panel_array['panel_name'] = 'dynamiccontentdesignrulesbyvalue';// slug to act as a name and part of the panel ID 
$panel_array['panel_number'] = $panel_number;// number of panels counted on page, used to create object ID
$panel_array['panel_title'] = __('Create Content Template Rules (by value)');// user seen panel header text 
$panel_array['pageid'] = $pageid;// store the $pageid for sake of ease
$panel_array['tabnumber'] = $wtgcsv_tab_number; 
$panel_array['panel_id'] = $panel_array['panel_name'].$panel_number;// creates a unique id, may change from version to version but within a version it should be unique
$panel_array['panel_intro'] = __('Setup rules to apply different content designs based on values in a specific column of your data');
$panel_array['panel_help'] = __('This is a great tool for many important reasons and all users should consider where it fits into their project. You can make slight changes to your content to appear genuine, you can apply designs that suit specific categories or types of content and a big factor is SEO. Technically you can use the same record of data twice to make two different posts and Google would consider both pages as being very different simply by making the most of your designs.');
$panel_array['help_button'] = wtgcsv_helpbutton_text(false,true);
// Form Settings - create the array that is passed to jQuery form functions
$jsform_set_override = array();
$jsform_set = wtgcsv_jqueryform_commonarrayvalues($pageid,$panel_array['tabnumber'],$panel_array['panel_number'],$panel_array['panel_name'],$panel_array['panel_title'],$jsform_set_override);   
$jsform_set['dialoguebox_title'] = 'Saving New Content Template Rule';
$jsform_set['noticebox_content'] = 'You are about to setup a new condition for applying different template designs during post creation, do you wish to save now?';?>
<?php wtgcsv_panel_header( $panel_array );?>

    <?php wtgcsv_formstart_standard($jsform_set['form_name'],$jsform_set['form_id'],'post','wtgcsv_form',$wtgcsv_form_action);?>

    <p>        
        Table Column: 
        <select name="wtgcsv_dynamiccontentdesignrules_select_columnandtable" id="wtgcsv_dynamiccontentdesignrules_select_columnandtable_formid" class="wtgcsv_multiselect_menu">
            <?php wtgcsv_display_project_columnsandtables_menuoptions($wtgcsv_currentproject_code);?>                                                                                                                     
        </select>
    </p>
    
    <script>
    $("#wtgcsv_dynamiccontentdesignrules_select_columnandtable_formid").multiselect({
       multiple: false,
       header: "Select Database Column (table - column)",
       noneSelectedText: "Select Database Table",
       selectedList: 1
    });
    </script>

    <?php //TODO: MEDIUMPRIORITY, change to a menu populated with unique values from selected table, using Ajax ?>
    <p>        
        Trigger Value: <input type="text" name="wtgcsv_dynamiccontentdesignrules_text_triggervalue" id="wtgcsv_dynamiccontentdesignrules_text_trigger_formid" value="" />
    </p>
     
    <p>                    
        Template: 
        <select name="wtgcsv_dynamiccontentdesignrules_select_templateid" id="wtgcsv_dynamiccontentdesignrules_select_template_formid" class="wtgcsv_multiselect_menu">
            <?php wtgcsv_display_contenttemplate_menuoptions();?>                                                                                                                     
        </select>        
    </p>
    
    <script>
    $("#wtgcsv_dynamiccontentdesignrules_select_template_formid").multiselect({
       multiple: false,
       header: "Template",
       noneSelectedText: "Template",
       selectedList: 1
    });
    </script>

    <h2>Current Rules</h2>
    <?php wtgcsv_display_templatedesignrules_byvalue_table(); ?>      

    <?php
    // add the javascript that will handle our form action, prevent submission and display dialogue box
    wtgcsv_jqueryform_singleaction_middle($jsform_set,$wtgcsv_options_array);

    // add end of form - dialogue box does not need to be within the <form>
    wtgcsv_formend_standard('Submit',$jsform_set['form_id']);?>

    <?php wtgcsv_jquery_form_prompt($jsform_set);?>

<?php wtgcsv_panel_footer();
}?> 
