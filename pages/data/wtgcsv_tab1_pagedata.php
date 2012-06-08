<?php
++$panel_number;// increase panel counter so this panel has unique ID
$panel_array = array();
$panel_array['panel_name'] = 'usedcsvfilelist';// slug to act as a name and part of the panel ID 
$panel_array['panel_number'] = $panel_number;// number of panels counted on page, used to create object ID
$panel_array['panel_title'] = __('Used CSV File List');// user seen panel header text 
$panel_array['pageid'] = $pageid;// store the $pageid for sake of ease
$panel_array['tabnumber'] = $wtgcsv_tab_number; 
$panel_array['panel_id'] = $panel_array['panel_name'].$panel_number;// creates a unique id, may change from version to version but within a version it should be unique
$panel_array['panel_intro'] = __('A list of used files, scroll further down to view import jobs');
$panel_array['panel_help'] = __('Refreshing the browser will show the latest statistics in this table if you have imported data on this page. This list of files are those used in data import jobs. If a file shows twice it is because you are using it in more than one job. This panel is not for importing data. Scroll further down the Import screen to view individual job panels to begin manual data importing and view their progress.');
?>
<?php wtgcsv_panel_header( $panel_array );?>
 
    <?php $usedcsvfile_count = wtgcsv_used_csv_file_list();?>
    
    <?php
    if($usedcsvfile_count == 0){
        wtgcsv_notice('You do not have any data import jobs and so no CSV files are in use either.','info','Small');
    }
    ?>

<?php wtgcsv_panel_footer();?> 
   
<?php
##################################################################################
#                                                                                #
#         START OF DATA IMPORT JOB PANELS LOOP - CREATES A PANEL PER JOB         #
#                                                                                #
##################################################################################
global $wtgcsv_dataimportjobs_array;
    if($wtgcsv_dataimportjobs_array){
    foreach( $wtgcsv_dataimportjobs_array as $jobcode => $job ){

    $job_array = wtgcsv_get_dataimportjob($jobcode);?>

    <?php
    ++$panel_number;// increase panel counter so this panel has unique ID
    $panel_array = array();                       
    $panel_array['panel_name'] = 'dataimportjob' . $jobcode;// slug to act as a name and part of the panel ID 
    $panel_array['panel_number'] = $panel_number;// number of panels counted on page, used to create object ID
    $panel_array['panel_title'] = __('Data Import Job: ' . $job['name']);// user seen panel header text 
    $panel_array['pageid'] = $pageid;// store the $pageid for sake of ease
    $panel_array['tabnumber'] = $wtgcsv_tab_number; 
    $panel_array['panel_id'] = $panel_array['panel_name'] . $panel_number;// creates a unique id, may change from version to version but within a version it should be unique
    $panel_array['panel_intro'] = __('This is your data import job named ' . $job['name'] .' and the job code is ' . $jobcode);
    $panel_array['panel_help'] = __('Please refresh the browser in order to view the latest job statistics (until more Ajax is added). Your data import panel for ' . $job['name'] . ' allows you to import rows from individual CSV files. You can also manually update the statistics. By default rows are imported and put into the database table using an UPDATE query, not an INSERT. The rows are placed in order as found in the CSV files. Meaning all CSV files within a single job must be in matching order for the finished table of data to be correct. This is only the default behaviour as there is the ability to save a set of key columns which contain some type of ID per record. Each CSV file must contain the same column of ID values in order for this ability to work. This allows the rows in each CSV file to be in any order and still be updated to the correct record in the database.');
    ?>
    <?php wtgcsv_panel_header( $panel_array );?>    
                    
        <?php $nonce = wp_create_nonce( "wtgcsv_referer_dataimportjob_csvfileimport" );?>
        
        <?php $total_job_progress_bar_value = wtgcsv_calculate_dataimportjob_progress_percentage($jobcode);?>
        
        <script>
            $(function() {
                $( <?php echo '"#wtgcsv_progressbar_dataimport_'.$jobcode.'"';?> ).progressbar({
                    value: <?php echo $total_job_progress_bar_value;?>
                });
            });
        </script>  
        <div id="wtgcsv_progressbar_dataimport_<?php echo $jobcode;?>"></div>                 
         
        <?php foreach($job_array['files'] as $key => $csv_filename){?>
             
            <?php $csv_filename_cleaned = wtgcsv_clean_string($csv_filename);?>
           
            <script  type='text/javascript'>
            <!--
            var count = 0;

            // When the document loads do everything inside here ...
            jQuery(document).ready(function(){
                
                <?php
                // get import rate buttons array (sets the numbe per button)
                $importrate_array = array();// TODO: add ability for user to set each buttons import rate
                $importrate_array[0]['letter'] = 'a';
                $importrate_array[0]['rate'] = '9000999';
                $importrate_array[1]['letter'] = 'b';
                $importrate_array[1]['rate'] = '1'; 
                $importrate_array[2]['letter'] = 'c';
                $importrate_array[2]['rate'] = '100';
                $importrate_array[3]['letter'] = 'd';
                $importrate_array[3]['rate'] = '1000';
                $importrate_array[4]['letter'] = 'e';
                $importrate_array[4]['rate'] = '10000';
                                                                                        
                foreach( $importrate_array as $key => $button ){?>                       
                    
                    // request data import event - E (10,000 by default)
                    jQuery('#request_import_<?php echo $button['letter'];?>_<?php echo $jobcode . $csv_filename_cleaned;?>').click(function() { //start function when Random button is clicked
                        
                        jQuery.ajax({
                            type: "post",url: "admin-ajax.php",data: { action: 'action_dataimportjob_csvfileimport', jobcode: '<?php echo $jobcode;?>', csvfilename: '<?php echo $csv_filename;?>', targetrows:<?php echo $button['rate'];?>, _ajax_nonce: '<?php echo $nonce; ?>' },
                            beforeSend: function() {jQuery("#loading_div_for_<?php echo $jobcode;?>").fadeIn('fast');
                            jQuery("#formstatus_div_for_<?php echo $jobcode;?>").fadeOut("fast");}, //fadeIn loading just when link is clicked
                            success: function(html){ //so, if data is retrieved, store it in html
                                jQuery("#loading_div_for_<?php echo $jobcode;?>").fadeOut('slow');
                                jQuery("#formstatus_div_for_<?php echo $jobcode;?>").html( html ); //show the html inside formstatus div
                                jQuery("#formstatus_div_for_<?php echo $jobcode;?>").fadeIn("fast"); //animation
                            }
                        }); //close jQuery.ajax
                        
                        // update the imported rows counter
                        jQuery.ajax({                       
                            type: "post",url: "admin-ajax.php",data: { action: 'dataimportjob_requestupdate', jobcode: '<?php echo $jobcode;?>', csvfilename: '<?php echo $csv_filename;?>', _ajax_nonce: '<?php echo $nonce; ?>' },
                            beforeSend: function() {jQuery("#loading_div_for_<?php echo $jobcode;?>").fadeIn('fast');}, //fadeIn loading just when link is clicked
                            success: function(html){ //so, if data is retrieved, store it in html
                                jQuery("#loading_div_for_<?php echo $jobcode;?>").fadeOut('slow');
                                jQuery("#thevalue_<?php echo $jobcode . $csv_filename_cleaned;?>").val(html); //fadeIn the html inside helloworld div
                            }       
                        }); //close jQuery.ajax 
                                                        
                    })
                       
                            
                <?php }?>
                                            
            })
            -->
            </script>
            
        <?php }?>
        
        
        
        <!-- Start Of Buttons Table --> 
                        
            <?php foreach($job_array['files'] as $key => $csv_filename){?>
            
            <h4><?php echo $csv_filename;?> (<?php echo wtgcsv_count_csvfilerows($csv_filename); ?> rows)</h4>
            
            <table>
                <?php $csv_filename_cleaned = wtgcsv_clean_string($csv_filename);?>
                <tr>                            
                    <td><input type='submit' name='action' id='request_import_a_<?php echo $jobcode . $csv_filename_cleaned;?>' value='All Rows' /></td>            
                    <td><input type='submit' name='action' id='request_import_b_<?php echo $jobcode . $csv_filename_cleaned;?>' value='1 Row' /></td>            
                    <td><input type='submit' name='action' id='request_import_c_<?php echo $jobcode . $csv_filename_cleaned;?>' value='100 Rows' /></td>            
                    <td><input type='submit' name='action' id='request_import_d_<?php echo $jobcode . $csv_filename_cleaned;?>' value='1000 Rows' /></td>                                    
                    <td><input type='submit' name='action' id='request_import_e_<?php echo $jobcode . $csv_filename_cleaned;?>' value='10,000 Rows' /></td> 
                    <td><strong>Imported</strong><input type='text' name='thevalue' id='thevalue_<?php echo $jobcode . $csv_filename_cleaned;?>' value='<?php echo wtgcsv_sql_count_records_forfile('wtgcsv_'.$jobcode,$csv_filename,$key); ?>' size="10" readonly /> </td>
                </tr>
            </table>
            <?php }?>
                        
              
        <!-- End Of Buttons Table -->
        
        <style type='text/css'>
        #loading_div_for_<?php echo $jobcode;?> { clear:both; background:url(images/loading.gif) center top no-repeat; text-align:center;padding:33px 0px 0px 0px; font-size:12px;display:none; font-family:Verdana, Arial, Helvetica, sans-serif; }
        </style>
            
        <!-- Ajax Output - applies to entire job panel, all forms within -->
        <br />
        <div id='formstatus_div_for_<?php echo $jobcode;?>'></div>
        <div id='loading_div_for_<?php echo $jobcode;?>'>Importing Data Please Wait!</div>  

    <?php wtgcsv_panel_footer();?>             
                    
    <?php }// end of main loop
}?> 
