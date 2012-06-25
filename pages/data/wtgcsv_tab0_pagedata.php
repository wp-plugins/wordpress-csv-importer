<?php
++$panel_number;// increase panel counter so this panel has unique ID
$panel_array = array();
$panel_array['panel_name'] = 'uploadcsvfile';// slug to act as a name and part of the panel ID 
$panel_array['panel_number'] = $panel_number;// number of panels counted on page, used to create object ID
$panel_array['panel_title'] = __('Upload CSV File');// user seen panel header text 
$panel_array['pageid'] = $pageid;// store the $pageid for sake of ease
$panel_array['tabnumber'] = $wtgcsv_tab_number; 
$panel_array['panel_id'] = $panel_array['panel_name'].$panel_number;// creates a unique id, may change from version to version but within a version it should be unique
$panel_array['panel_intro'] = __('Upload a new .csv file to the plugins own content folder.');
$panel_array['help_button'] = wtgcsv_helpbutton_text(false,false);
$panel_array['panel_help'] = __('    
    <script type="text/javascript"><!--
google_ad_client = "ca-pub-4923567693678329";
/* Wordpress CSV Importer */
google_ad_slot = "9636544082";
google_ad_width = 468;
google_ad_height = 60;
//-->
</script>
<script type="text/javascript"
src="http://pagead2.googlesyndication.com/pagead/show_ads.js">
</script>Upload a new .csv file to the plugins own content folder. This file uploader allows .csv files to be uploaded to the plugins own content folder which you will find in the wp-content directory.');
// Form Settings - create the array that is passed to jQuery form functions
$jsform_set_override = array();
$jsform_set = wtgcsv_jqueryform_commonarrayvalues($pageid,$panel_array['tabnumber'],$panel_array['panel_number'],$panel_array['panel_name'],$panel_array['panel_title'],$jsform_set_override);            
$jsform_set['dialoguebox_title'] = 'Upload CSV File';
$jsform_set['noticebox_content'] = 'You are about to upload a CSV file, it will overwrite any existing CSV file with the same name. Do you want to upload the file now?';?>
<?php wtgcsv_panel_header( $panel_array );?>
  
    <h4><?php _e('Upload CSV File')?> <?php echo ini_get( "upload_max_filesize").'B Limit';?></h4>
       <form method="post" enctype="multipart/form-data" name="uploadform" class="form-table">                
           <input type="file" name="file" size="70" /><br /><br />

            <div class="jquerybutton">
                <input class="button-primary" type="submit" value="Upload CSV File" name="eci_csvupload_submit" />
            </div>  
    <?php
    // add the javascript that will handle our form action, prevent submission and display dialogue box
    wtgcsv_jqueryform_singleaction_middle($jsform_set,$wtgcsv_options_array);?>
    </form>

    <?php wtgcsv_jquery_form_prompt($jsform_set);?>

<?php wtgcsv_panel_footer();?>        
        
<?php
++$panel_number;// increase panel counter so this panel has unique ID
$panel_array = array();
$panel_array['panel_name'] = 'createdataimportjobcsvfiles';// slug to act as a name and part of the panel ID 
$panel_array['panel_number'] = $panel_number;// number of panels counted on page, used to create object ID
$panel_array['panel_title'] = __('Create Data Import Jobs With CSV Files');// user seen panel header text 
$panel_array['pageid'] = $pageid;// store the $pageid for sake of ease
$panel_array['tabnumber'] = $wtgcsv_tab_number; 
$panel_array['panel_id'] = $panel_array['panel_name'].$panel_number;// creates a unique id, may change from version to version but within a version it should be unique
$panel_array['panel_intro'] = __('Create a data import job using one or more CSV files');
$panel_array['help_button'] = wtgcsv_helpbutton_text(false,false);
$panel_array['panel_help'] = __('First you must enter a name for your project. Some uses of your name will require the
plugin to adapt the name for different uses i.e. for the database name. So look out for for one or two variations of
the name you enter. You must also enter a data import job Name that has never been used, unless you want your new
project to adopt older data, this can be complicated however and is an approach that is being worked on.
You can select more than one file to setup a new data import job. Click either the single file you want to import
data from or click on multiple files to import data from those files and merge it to make a single set of data.
A data import project has nothing to do with post creation or creation or anything other than a database table that
 holds data. This plugin requires you to import data before creating posts, users or anything else in Wordpress. 
 Partly finish or complete your entire data import project to then use your prepared database table for a Post 
 Creation Project.');
// <form> values, seperate from panel value
$jsform_set_override = array();
$jsform_set = wtgcsv_jqueryform_commonarrayvalues($pageid,$panel_array['tabnumber'],$panel_array['panel_number'],$panel_array['panel_name'],$panel_array['panel_title'],$jsform_set_override);            
$jsform_set['dialoguebox_title'] = 'Create Data Import Job';
$jsform_set['noticebox_content'] = 'Do you want to continue creating a new Data Import Job? <p>Please note that this
action will not import data. You must begin data importing on the Import tab.</p>';
// create nonce - done in wtgcsv_ajax_is_dataimportjobname_used
$nonce = wp_create_nonce( "wtgcsv_referer_" . $panel_array['panel_name'] );
// TODO: HIGHPRIORITY, when existing table is selected, display another form option to select the existing table?>

<?php wtgcsv_panel_header( $panel_array );?>
    
    <?php wtgcsv_formstart_standard($jsform_set['form_name'],$jsform_set['form_id'],'post','wtgcsv_form',$wtgcsv_form_action);?>

    <?php // set ID and NAME variables
    $jobname_id = 'wtgcsv_jobname_id_' . $panel_array['panel_name'];
    $jobname_name = 'wtgcsv_jobname_name_' . $panel_array['panel_name'];?>
    
    <script  type='text/javascript'>
    <!--
    var count = 0;

    // When the document loads do everything inside here ...
    jQuery(document).ready(function(){

        // run validation on data import job name when key is pressed
        $("#<?php echo $jobname_id;?>").change(function() { 

            var usr = $("#<?php echo $jobname_id;?>").val();

            if(usr.length >= 4){
                
                // remove any status display by adding blank value too html
                $("#wtgcsv_status_<?php echo $jsform_set['form_id'];?>").html('');
                                                 
                                       
                jQuery.ajax({
                    type: "post",url: "admin-ajax.php",data: { action: 'action_createdataimportjobcsvfiles_validatefield', wtgcsv_jobname: escape( jQuery( '#<?php echo $jobname_id;?>' ).val() ), _ajax_nonce: '<?php echo $nonce; ?>' },
                    beforeSend: function() {jQuery("#<?php echo $jsform_set['form_id'];?>loading_jobnamechange").fadeIn('fast');
                    jQuery("#<?php echo $jsform_set['form_id'];?>formstatus").fadeOut("fast");}, //fadeIn loading just when link is clicked
                    success: function(html){ //so, if data is retrieved, store it in html
                        jQuery("#<?php echo $jsform_set['form_id'];?>loading_jobnamechange").fadeOut('slow');
                        jQuery("#<?php echo $jsform_set['form_id'];?>formstatus").html( html ); //show the html inside formstatus div
                        jQuery("#<?php echo $jsform_set['form_id'];?>formstatus").fadeIn("fast"); //animation
                    }
                }); //close jQuery.ajax
                     
            }else{
                $("#wtgcsv_status_<?php echo $jsform_set['form_id'];?>").html('<font color="red">' + 'The username should have at least <strong>4</strong> characters.</font>');
                $("#<?php echo $jobname_id;?>").removeClass('object_ok'); // if necessary
                $("#<?php echo $jobname_id;?>").addClass("object_error");
            }

        });
    })
    -->
    </script>

    <!-- TODO:LOWPRIORITY, put this style into stylesheet -->
    <style type='text/css'>
    #<?php echo $jsform_set['form_id'];?>loading_jobnamechange { clear:both; background:url(images/loading.gif) center top no-repeat; text-align:center;padding:33px 0px 0px 0px; font-size:12px;display:none; font-family:Verdana, Arial, Helvetica, sans-serif; }                
    </style>
            
    <p>The Value: <input type='text' name='wtgcsv_jobname_name' id='<?php echo $jobname_id;?>' value='' /><span id="wtgcsv_status_<?php echo $jsform_set['form_id'];?>"></span></p>

    <?php wtgcsv_selectables_csvfiles('all',$panel_array['panel_name']);?><br /><br />
        
    <!-- jquery and ajax output start -->
    <div id='<?php echo $jsform_set['form_id'];?>loading_jobnamechange'>Checking Job Name Please Wait 10 Seconds</div>                 
    <div id='<?php echo $jsform_set['form_id'];?>formstatus'></div>  
    <!-- jquery and ajax output end -->
                
    <script>
    $(function() {
        $( "#wtgcsv_tabletype<?php echo $panel_array['panel_name'];?>" ).buttonset();
    });
    </script>

    <div id="wtgcsv_tabletype<?php echo $panel_array['panel_name'];?>">
        <input type="radio" id="wtgcsv_radio1<?php echo $panel_array['panel_name'];?>" name="radio" checked="checked" /><label for="wtgcsv_radio1<?php echo $panel_array['panel_name'];?>">New Table</label>
        <input type="radio" id="wtgcsv_radio2<?php echo $panel_array['panel_name'];?>" name="radio" disabled="disabled" /><label for="wtgcsv_radio2<?php echo $panel_array['panel_name'];?>">Existing Table</label>
    </div>

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
$panel_array['panel_name'] = 'csvfilelist';// slug to act as a name and part of the panel ID 
$panel_array['panel_number'] = $panel_number;// number of panels counted on page, used to create object ID
$panel_array['panel_title'] = __('CSV File List');// user seen panel header text 
$panel_array['pageid'] = $pageid;// store the $pageid for sake of ease
$panel_array['tabnumber'] = $wtgcsv_tab_number; 
$panel_array['panel_id'] = $panel_array['panel_name'].$panel_number;// creates a unique id, may change from version to version but within a version it should be unique
$panel_array['panel_intro'] = __('A list of all CSV files available to use in ' . WTG_CSV_PLUGINTITLE .', used or not');
$panel_array['panel_help'] = __('This panel shows all the .csv files for using with '.WTG_CSV_PLUGINTITLE.' and each 
files statistics i.e. size, number of rows. This panel does not provide controls to begin importing data, please do 
that on the Import tab. You may use the same file in multiple data import jobs, an ability for those who want to create
different sets of data with a different combination of CSV files. To check on the progress related to a specific file
please go to the Import tab and see the Used CSV File List panel. It shows a table of all the used CSV files within all
the data import jobs and their individual import progress.');
$panel_array['help_button'] = wtgcsv_helpbutton_text(false,false);?>
<?php wtgcsv_panel_header( $panel_array );?>
    <?php wtgcsv_available_csv_file_list();?>
<?php wtgcsv_panel_footer();?>


<?php
++$panel_number;// increase panel counter so this panel has unique ID
$panel_array = array();
$panel_array['panel_name'] = 'testcsvfiles';// slug to act as a name and part of the panel ID 
$panel_array['panel_number'] = $panel_number;// number of panels counted on page, used to create object ID
$panel_array['panel_title'] = __('Test CSV Files');// user seen panel header text 
$panel_array['pageid'] = $pageid;// store the $pageid for sake of ease
$panel_array['tabnumber'] = $wtgcsv_tab_number; 
$panel_array['panel_id'] = $panel_array['panel_name'].$panel_number;// creates a unique id, may change from version to version but within a version it should be unique
$panel_array['panel_intro'] = __('Run a series of tests on a file to help determine its suitability as a CSV file');
$panel_array['panel_help'] = __('This tool will run some tests on your file and try to detect potential problems. You may get recommendations for your file which you can ignore, you will know best but the plugin will try to suggest changes that you can try should you experience any problems with your file. Where a fault is detected and confirmed to prevent proper use of a file it will be made very clear to you. The most common cause of problems is a CSV file that is not properly formatted. A CSV files rows should be spread over a single line within the file when opened in Notepad and Excel. Many files are created with data covering multiple rows within the file, missing commas or headers/titles that are not suitable as an identifier i.e. too many words and special characters. Most of the requirements of a properly formatted CSV file follow standards often expected within database management because the data usually comes from a database and is eventually being imported to a database.');
$panel_array['help_button'] = wtgcsv_helpbutton_text(false,false);
// <form> values, seperate from panel value
$jsform_set_override = array();
$jsform_set = wtgcsv_jqueryform_commonarrayvalues($pageid,$panel_array['tabnumber'],$panel_array['panel_number'],$panel_array['panel_name'],$panel_array['panel_title'],$jsform_set_override);            
$jsform_set['dialoguebox_title'] = 'Test CSV File';
$jsform_set['noticebox_content'] = 'Do you want to run the full series of tests on the selected file? <p>The plugin will run a series of tests that could use your servers full processing time depending on how large your file is. Please keep this in mind, if you experience problems you may want to run tests on a smaller version of your file as a first step. Please create a support ticket (full users) or forum thread (free users) if you do experience problems so that I can investigate possible improvements.</p>';
/**
* TODO:LOWPRIORITY,would it work to add a test that checks every row and every data value counting total number of columns then output exact row numbers where possible issues detected?
*/?>

<?php wtgcsv_panel_header( $panel_array );?>

    <?php wtgcsv_formstart_standard($jsform_set['form_name'],$jsform_set['form_id'],'post','wtgcsv_form');?>

    <?php wtgcsv_menu_csvfiles('all',$panel_array['panel_name']);?>

    <?php
    // add the javascript that will handle our form action, prevent submission and display dialogue box
    wtgcsv_jqueryform_singleaction_middle($jsform_set,$wtgcsv_options_array);

    // add end of form - dialogue box does not need to be within the <form>
    wtgcsv_formend_standard('Run Test',$jsform_set['form_id']);?>

    <?php wtgcsv_jquery_form_prompt($jsform_set);?> 
        
<?php wtgcsv_panel_footer();?>

<?php
++$panel_number;// increase panel counter so this panel has unique ID
$panel_array = array();
$panel_array['panel_name'] = 'dataimportjoblist';// slug to act as a name and part of the panel ID 
$panel_array['panel_number'] = $panel_number;// number of panels counted on page, used to create object ID
$panel_array['panel_title'] = __('Data Import Job List');// user seen panel header text 
$panel_array['pageid'] = $pageid;// store the $pageid for sake of ease
$panel_array['tabnumber'] = $wtgcsv_tab_number; 
$panel_array['panel_id'] = $panel_array['panel_name'].$panel_number;// creates a unique id, may change from version to version but within a version it should be unique
$panel_array['panel_intro'] = __('A list of all your data import jobs with the ability to delete them');
$panel_array['panel_help'] = __('A list of all your data import jobs. It is for reference only but has the ability to quickly delete jobs. The delete action will only delete the jobs history and remove the job record from Wordpress options table. It will not delete the jobs database table. All data management is handled in seperate actions. View Data Tables tab for a list of database tables created using Wordpress CSV Importer. It will not delete CSV files being used in the job, that also must be done elsewhere or manually on your server.');
$panel_array['help_button'] = wtgcsv_helpbutton_text(false,false);
// <form> values, seperate from panel value
$jsform_set_override = array();
$jsform_set = wtgcsv_jqueryform_commonarrayvalues($pageid,$panel_array['tabnumber'],$panel_array['panel_number'],$panel_array['panel_name'],$panel_array['panel_title'],$jsform_set_override);            
$jsform_set['dialoguebox_title'] = 'Delete Selected Jobs';
$jsform_set['noticebox_content'] = 'You are about to delete select data import jobs, are you sure you want to continue?';
// TODO: LOWPRIORITY, replace table using data table script
// TODO: LOWPRIORITY, update dialogue content with a list of the selected jobs
?>

<?php wtgcsv_panel_header( $panel_array );?>

    <?php wtgcsv_formstart_standard($jsform_set['form_name'],$jsform_set['form_id'],'post','wtgcsv_form','','');?>

    <?php wtgcsv_list_dataimportjobs();?>

    <?php 
    wtgcsv_jqueryform_singleaction_middle($jsform_set,$wtgcsv_options_array);
    wtgcsv_formend_standard('Delete',$jsform_set['form_id']);
    wtgcsv_jquery_form_prompt($jsform_set);
    ?>

<?php wtgcsv_panel_footer();?>