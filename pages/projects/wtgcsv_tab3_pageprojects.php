<?php
++$panel_number;// increase panel counter so this panel has unique ID
$panel_array = array();
$panel_array['panel_name'] = 'metadescription';// slug to act as a name and part of the panel ID 
$panel_array['panel_number'] = $panel_number;// number of panels counted on page, used to create object ID
$panel_array['panel_title'] = __('Meta Description');// user seen panel header text 
$panel_array['pageid'] = $pageid;// store the $pageid for sake of ease
$panel_array['tabnumber'] = $wtgcsv_tab_number; 
$panel_array['panel_id'] = $panel_array['panel_name'].$panel_number;// creates a unique id, may change from version to version but within a version it should be unique
$panel_array['panel_intro'] = __('Create a template for your post meta description (not excerpt)');
$panel_array['panel_help'] = __('Copy and paste column replacement tokens into the text area to create a meta description template. This panel is not global so anything you create here will only effect the project you are working on at the moment.');
$panel_array['help_button'] = wtgcsv_helpbutton_text(true,false);
// Form Settings - create the array that is passed to jQuery form functions
$jsform_set_override = array();
$jsform_set = wtgcsv_jqueryform_commonarrayvalues($pageid,$panel_array['tabnumber'],$panel_array['panel_number'],$panel_array['panel_name'],$panel_array['panel_title'],$jsform_set_override);     
$jsform_set['dialoguebox_title'] = 'Save Meta Description';
$jsform_set['noticebox_content'] = 'Do you want to save your meta description?';
// TODO:LOWPRIORITY, add column replacement tokens too a dialogue window and button and do the same for other panels that require them?>
<?php wtgcsv_panel_header( $panel_array );?>

    <?php wtgcsv_formstart_standard($jsform_set['form_name'],$jsform_set['form_id'],'post','wtgcsv_form','');?>

    <?php
    $get_metadescription_result = wtgcsv_get_project_metadescription();
    if(!$get_metadescription_result){
        $metadescription = 'Type your template here';
    }else{
        $metadescription = $get_metadescription_result;
    }?>

    <textarea rows="7" cols="50" name="wtgcsv_metadescription"><?php echo $metadescription;?></textarea> 
    <input type="hidden" name="wtgcsv_metadescription_original" value="<?php echo $metadescription;?>">
    
    <br />
    
    <?php
    // add the javascript that will handle our form action, prevent submission and display dialogue box
    wtgcsv_jqueryform_singleaction_middle($jsform_set,$wtgcsv_options_array);

    // add end of form - dialogue box does not need to be within the <form>
    wtgcsv_formend_standard('Submit',$jsform_set['form_id']);?>

    <?php wtgcsv_jquery_form_prompt($jsform_set);?>

<?php wtgcsv_panel_footer();?>