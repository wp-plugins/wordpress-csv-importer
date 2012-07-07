<?php 
if($wtgcsv_is_free){?>
<script type="text/javascript"><!--
google_ad_client = "ca-pub-4923567693678329";
/* Wordpress CSV Importer Wide */
google_ad_slot = "2263056755";
google_ad_width = 728;
google_ad_height = 90;
//-->
</script>
<script type="text/javascript"
src="http://pagead2.googlesyndication.com/pagead/show_ads.js">
</script>
<?php }?>

<?php
if(!isset($wtgcsv_projectslist_array) || $wtgcsv_projectslist_array == false){
    
    echo '<strong>You do not have any post creation projects, please create a project on the Your Projects page</strong>';
        
}else{

    $i = 0; 
    foreach($wtgcsv_projectslist_array as $project_code => $project ){?>

        <?php
        ++$panel_number;// increase panel counter so this panel has unique ID
        $panel_array = array();
        $panel_array['panel_name'] = 'createpostsproject';// slug to act as a name and part of the panel ID 
        $panel_array['panel_number'] = $panel_number;// number of panels counted on page, used to create object ID
        $panel_array['panel_title'] = __('Create Posts: ' . $project['name']);// user seen panel header text 
        $panel_array['pageid'] = $pageid;// store the $pageid for sake of ease
        $panel_array['tabnumber'] = $wtgcsv_tab_number; 
        $panel_array['panel_id'] = $panel_array['panel_name'].$panel_number;// creates a unique id, may change from version to version but within a version it should be unique
        $panel_array['panel_intro'] = __('Create posts for project named ' . $project['name']);
        $panel_array['panel_help'] = __('This panel allows you to create posts manually. Use the slider to decide how many posts to create using the records imported to your projects data table. The slider will take the number of records available into consideration and set a limit. If you are using multiple database tables, the default approach is for Wordpress CSV Importer to link records in each table together by the order they come in. The alternative is using primary key columns and manually mapping the tables too each other. This is considered an advanced feature and watching tutorials is recommended. Importer more rows from your CSV file if you have not finished doing so to create more records in your projects data tables.');
        $panel_array['help_button'] = wtgcsv_helpbutton_text(false,false);
        // Form Settings - create the array that is passed to jQuery form functions
        $jsform_set_override = array();
        $jsform_set = wtgcsv_jqueryform_commonarrayvalues($pageid,$panel_array['tabnumber'],$panel_array['panel_number'],$panel_array['panel_name'],$panel_array['panel_title'],$jsform_set_override);   
        $jsform_set['dialoguebox_title'] = 'Create Posts';
        $jsform_set['noticebox_content'] = 'You are about to create posts based on the configuration applied on the Your Projects screen, do you want to begin?';?>
            
            <?php wtgcsv_panel_header( $panel_array );?>

                <?php wtgcsv_formstart_standard($jsform_set['form_name'],$jsform_set['form_id'],'post','wtgcsv_form','');?>             

                <table class="widefat post fixed">
                    <tr class="first">
                        <td width="120"><strong>Table Names</strong></td>
                        <td width="120"><strong>Total Records</strong></td>                                                                                               
                    </tr>
                            
                    <?php
                    ### TODO:MEDIUMPRIORITY,add columns showing number of records used, number void, number available
                    $project_array = wtgcsv_get_project_array($project_code);
                    $table_name_string = '';
                    $tablerecords_leastcounted = 99999999;// least counted will be the number of posts that can be created
                    foreach($project_array['tables'] as $test => $table_name){
                        
                        $tablerecords_count = wtgcsv_sql_counttablerecords( $table_name );
                        
                        // if current table has less records then it becomes the least counted 
                        if($tablerecords_count < $tablerecords_leastcounted){
                            $tablerecords_leastcounted = $tablerecords_count;    
                        }
                        
                        echo '
                        <tr class="first">
                            <td width="120"><strong>'.$table_name.'</strong></td>
                            <td width="120"><strong>'.$tablerecords_count.'</strong></td>
                        </tr>';
                    }?>
                
                </table>
                
                <?php 
                // TODO:HIGHPRIORITY, get project posts total created
                $project_posts_total_created = 0;
                ?>
                
                <h4>Total Posts Created In This Project: 0</h4>
                
                <?php 
                // deduct number of posts already created from lowest record count, this tells us the number of posts that can be made
                $tablerecords_leastcounted = $tablerecords_leastcounted - $project_posts_total_created;
                ?>

                <?php 
                // if free edition, hide the slider bars script, free edition will use all records at once for even easier use
                if(!$wtgcsv_is_free){?>
                
                    <script>
                    $(function() {
                        $( "#wtgcsv_createposts_slider_<?php echo $project_code; ?>" ).slider({
                            range: "min",
                            value: 1,
                            min: 1,
                            max: <?php echo $tablerecords_leastcounted;?>,
                            slide: function( event, ui ) {
                                $( "#wtgcsv_postsamount_<?php echo $project_code; ?>" ).val( "" + ui.value + "" );
                            }
                        });
                        $( "#wtgcsv_postsamount_<?php echo $project_code; ?>" ).val( "" + $( "#wtgcsv_createposts_slider_<?php echo $project_code; ?>" ).slider( "value" ) + "" );
                    });
                    </script>

                    <br />
                    
                    <p>
                        Create <input type="text" name="wtgcsv_postsamount" id="wtgcsv_postsamount_<?php echo $project_code; ?>" style="border:0; color:#f6931f; font-weight:bold;" size="20" /> Posts
                        <div id="wtgcsv_postsamount_<?php echo $project_code; ?>"></div>
                    </p>


                    <div id="wtgcsv_createposts_slider_<?php echo $project_code; ?>"></div>
                    <br />
                     
                <?php }?>

                <input type="hidden" name="wtgcsv_project_code" value="<?php echo $project_code; ?>">

                <?php
                // add the javascript that will handle our form action, prevent submission and display dialogue box
                wtgcsv_jqueryform_singleaction_middle($jsform_set,$wtgcsv_options_array);

                // add end of form - dialogue box does not need to be within the <form>
                wtgcsv_formend_standard('Submit',$jsform_set['form_id']);?>

            <?php wtgcsv_jquery_form_prompt($jsform_set);?>

        <?php wtgcsv_panel_footer();?> 

        <?php             
        ++$i;
    }
}?>
