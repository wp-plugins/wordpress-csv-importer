<?php
/**
 * Counts rows in CSV file and returns (does no include header row)
 * @uses eci_csvfileexists
 * @param filename $filename
 * @param array $pro
 */
function wtgcsv_count_csvfilerows($csvfile_name){    
    return count(file(WTG_CSV_CONTENTFOLDER_DIR . '/' . $csvfile_name)) - 1;
}

/**
* Counts separator characters per row, compares total over all rows counted to determine probably seperator
* 
* @param mixed $csv_filename
* @param mixed $output
* 
* @todo LOWPRIORITY, add further checks when the difference between two counts is not great, is it possible to ignore the needles with quotes around them? Maybe ignore columns with large text values
*/
function wtgcsv_establish_csvfile_separator_fgetmethod( $testnumber,$csv_filename, $output = false ){
    
    $probable_separator = ','; 
    
    if (($handle = fopen(WTG_CSV_CONTENTFOLDER_DIR . '/' . $csv_filename, "r")) !== FALSE) {
        
        $probable_separator = ',';
        
        // count seperators
        $comma_count = 0;
        $pipe_count = 0;
        $semicolon_count = 0;
        $colon_count = 0;          

        // one row at a time we will count each possible seperator
        while (($rowstring = fgets($handle, 4096)) !== false) {
            
            $comma_count = $comma_count + substr_count ( $rowstring , ',' );
            $pipe_count = $pipe_count + substr_count ( $rowstring , '|' );                    
            $semicolon_count = $semicolon_count + substr_count ( $rowstring , ';' );
            $colon_count = $colon_count + substr_count ( $rowstring , ':' ); 
                                
        }  
        
        if (!feof($handle)) {

            wtgcsv_notice('<h4>Establishing Probable Separator Failed</h4>A failure happened with
            end-of-file function feof and should be reported.','error','Extra ','Establishing Probable Separator Failed');                    
                
        }
        fclose($handle);                
        
        // compare count results - comma
        if($comma_count > $pipe_count && $comma_count > $semicolon_count && $comma_count > $colon_count){
            
            $probable_separator = ',';
            $probable_separator_name = 'comma';
                
        }
        
        // pipe
        if($pipe_count > $comma_count && $pipe_count > $semicolon_count && $pipe_count > $colon_count){ 
            
            $probable_separator = '|';
            $probable_separator_name = 'pipe';
            
        }
        
        // semicolon
        if($semicolon_count > $comma_count && $semicolon_count > $pipe_count && $semicolon_count > $colon_count){
            
            $probable_separator = ';';
            $probable_separator_name = 'semicolon';
            
        }
        
        // colon
        if($colon_count > $comma_count && $colon_count > $pipe_count && $colon_count > $semicolon_count){
            
            $probable_separator = ':';
            $probable_separator_name = 'colon';
            
        }

        // display the result of output required
        if($output){
            wtgcsv_notice('<h4>Test '.$testnumber.': Separator - Method One</h4>Separator was established using method one and resulted in '.$probable_separator_name.' ('.$probable_separator.') being determined
            as the seperator for '.$csv_filename,'success','Extra ','Establish Probable Separator - Method One');
        }
        
    }// if handle open for giving file
    
    return $probable_separator; 
}

/**
* Guesses CSV files separator character
* 
* @param mixed $csv_filename
* @param boolean $display, will cause more output to screen for user
* 
* @todo HIGHPRIORITY, complete function
*/
function wtgcsv_establish_csvfile_separator_PEARCSVmethod( $testnumber,$csv_filename,$output = false){
    wtgcsv_pearcsv_include();
    $csv_file_conf = File_CSV::discoverFormat( WTG_CSV_CONTENTFOLDER_DIR . '/' . $csv_filename );

    // display the result of output required
    if($output){

        if($csv_file_conf['sep'] == ','){
            
            $probable_separator = ',';
            $probable_separator_name = 'comma';
                
        }
        
        // pipe
        if($csv_file_conf['sep'] == '|'){ 
            
            $probable_separator = '|';
            $probable_separator_name = 'pipe';
            
        }
        
        // semicolon
        if($csv_file_conf['sep'] == ';'){
            
            $probable_separator = ';';
            $probable_separator_name = 'semicolon';
            
        }
        
        // colon
        if($csv_file_conf['sep'] == ':'){
            
            $probable_separator = ':';
            $probable_separator_name = 'colon';
            
        }        
        
        wtgcsv_notice('<h4>Test '.$testnumber.': Separator - Method Two</h4>Separator was established using method one and resulted in '.$probable_separator_name.' ('.$probable_separator.') being determined
        as the seperator for '.$csv_filename,'success','Extra ','Establish Probable Separator - Method Two');
    }
            
    return $csv_file_conf['sep'];          
} 

/**
* Guesses CSV files quote character
* 
* @param mixed $csv_filename
* @param boolean $display, will cause more output to screen for user
* 
* @todo HIGHPRIORITY, complete function
*/
function wtgcsv_establish_csvfile_quote_PEARCSVmethod( $testnumber,$csv_filename,$output = false){
    wtgcsv_pearcsv_include();
    $csv_file_conf = File_CSV::discoverFormat( WTG_CSV_CONTENTFOLDER_DIR . '/' . $csv_filename );
           
    // display the result of output required
    if($output){
        
        // pear returns NULL
        if($csv_file_conf['quote'] == NULL){
            
            $probable_quote = '"';
            $probable_quote_name = 'double quote';
                
        }
        
        // double quote       
        if($csv_file_conf['quote'] == '"'){
            
            $probable_quote = '"';
            $probable_quote_name = 'double quote';
                
        }
        
        // single quote
        if($csv_file_conf['quote'] == "'"){ 
            
            $probable_quote = "'";
            $probable_quote_name = 'single quote';
            
        }

        wtgcsv_notice('<h4>Test '.$testnumber.': Quote - Method Two</h4> was established using method two and resulted in '.$probable_quote_name.' ('.$probable_quote.') being determined
        as the quote for '.$csv_filename,'success','Extra ','Establish Probable Quote - Method Two');
    }
                    
    return $csv_file_conf['quote'];   
} 


#### EXAMPLE ONLY PLEASE DELETE BEFORE RELEASE
function EXAMPLE_dataimport( $filename, $output, $event, $set )
{
    // begin script timer
    $mtime = microtime(); 
    $mtime = explode(" ",$mtime); 
    $mtime = $mtime[1] + $mtime[0]; 
    $scriptstarttime = $mtime;
   
    eci_log( 'Data Import Started','Data '.$event.' started',
    'Data import was requested and attempted. The results will be entered in further log entries.'
    ,$filename,$set,'Low',__LINE__,__FILE__,__FUNCTION__ );

    eci_pearcsv_include();
    
    global $wpdb;
    
    $pro = get_option('eci_pro');
    $csv = get_option( 'eci_' . $filename );
    $spe = get_option('eci_spe');        

    // determine if this is a multifile project and what column title array to use
    $titlearray = eci_gettitlearray($csv,$pro,$filename);
    
    if( $pro[$pro['current']]['protype'] == 'postcreation' )
    {
        // get projects speed profile name ( not type )
        $spepro = $pro[$filename]['speed'];
        
        // used collected name to get speed profiles configuration into variables
        $label = $spe[$spepro]['label'];// needed if output is true
        $import = $spe[$spepro]['import'];// number of records to import in a single event
        $update = $spe[$spepro]['update'];// number of records to update in a single event
        $type = $spe[$spepro]['type'];// type of speed profile - determines out
    }
    else 
    {
        /* we will require a full import for postcreation_merge projects and user import, all records are required in database for most of the features in them 
        and user creation cannot be staggered */
        
        $label = 'Default';// needed if output is true
        $import = 999999;// number of records to import in a single event
        $update = 999999;// number of records to update in a single event
        $type = 'fullspeed';// type of speed profile - determines out
    }
    
    // if the import request is a test we only import a single record
    if(isset($_POST['eci_datatransferonerecord_submit']))
    {
        $import = 1;
    }
    
    // get current file date
    $stamp = eci_filetime( $filename,$pro,$csv,$set );

    // establish previous import progress - used to skip records in loop
    $progress = eci_progress( $filename );
    
    // used to compare against event limit on this event only
    $recordsprocessed = 0;

    // records actually looped - on this event only
    $recordslooped = 0;
    
    // csv rows imported to create database record - used for interface output only
    $updatesuccess = 0;
    $updatefailed = 0;
    $importsuccess = 0;
    $importfailed = 0;
    $updateresult = 0;
    $duplicaterecord = 0;
    
    // use pear to read csv file
    $conf = File_CSV::discoverFormat( $pro[$filename]['filepath'] );
    
    // apply stored seperator
    $conf['sep'] = $csv['format']['seperator'];        
    $conf['quote'] = $csv['format']['quote'];
        
    // set limit
    if( $event == 'import' )
    {
        $max = $import;
    }
    elseif( $event == 'update' )
    {
        $max = $update;
    }
    
    // loop through records until speed profiles limit is reached then do exit
    while ( ( $record = File_CSV::read( $pro[$filename]['filepath'], $conf ) ) && $recordsprocessed < $max ) 
    {        
        // skip first record - also skip done rows by using more or equal too
        if( $recordslooped != 0 && $recordslooped > $progress )
        {            
            // count actual records processsed after progress total looped through
            ++$recordsprocessed;
    
            $profilecolumnid = 0;
            
            // duplicate record check
            if($set['allowduplicaterecords'] != 'Yes'){
                $selectfound = $wpdb->query( eci_sqlselect( $record, $filename, 'whereall' ) );
            }
            
            // if a record was found in project table matching current processed record, add it to failed count if duplicates are not allowed
            // on an update, this means the new csv files row has not changed and so no update needs to be done
            if( isset( $selectfound ) && $selectfound != false && $set['allowduplicaterecords'] != 'Yes' )
            {
                ++$pro[$filename]['rowsinsertfail_duplicate'];
                ++$pro[$filename]['rowsinsertfail'];
                ++$importfailed;
            }
            else
            {
                // if update event build and attempt update query
                if( $event == 'update' )
                {
                    $updatequery = eci_sqlupdate( $record, $titlearray, $filename );

                    $updateresult = $wpdb->query($updatequery);

                    // increase statistics for success or fail
                    if( $updateresult === false )
                    {                        
                        // if interface output requested 
                        if( $output )
                        {
                            eci_adminmes( __('SQL Update Error'),'The plugin attempted to run an SQL UPDATE query
                            but Wordpress returned false which usually indicates the correct separator or quote has not bee applied. Please
                            investigate this before running further data import events. The 
                            SQL UPDATE query is below:<br /><br />'.$updatequery.'','err' );
                        }
                    }
                    elseif( $updateresult === 0 )
                    {
                        // csv row has no difference when compared to existing record
                        eci_log('Data Update','Data update resulting in new record','An update to a projects data was not applied as there is no existing record to update, it was be inserted as a new record instead', $filename, $set, 'High',__LINE__,__FILE__,__FUNCTION__);
                        ++$pro[ $filename ]['rowsupdatefail'];
                        ++$updatefailed;
                    }
                    elseif( $updateresult === 1 )
                    {
                        // database record was updated
                        ++$pro[ $filename ]['rowsupdatesuccess'];
                        ++$updatesuccess;
                    }    
    
                    unset( $updatequery );
                }
                
                // if import event OR update event where update query returns 0 do an INSERT query
                if( $event == 'import' || $event == 'update' && $updateresult === 0 )
                {
                    // if this is a multifile project we don't use an INSERT query if it is not the parent file being imported
                    if( $pro[$filename]['filesettype'] == 'multifile' && $pro[$filename]['multifilelev'] != 'parent' )
                    {
                        // $recordslooped acts as the byorder ID, if method is byid we need use the id within the record
                        // byid not currently active
                        // we need to pass only the current csv files own titles so that the record array can be accessed properly
                        $finalquery = eci_sqlupdate_multifileinsert($record,$csv['format']['titles'],$filename,$recordslooped,$pro[$filename]['multifilemethod'],$pro);
                    }
                    else// do a normal insert even if this is a multifile project but this is the parent file
                    {
                        $sqlmiddle = eci_sqlinsert_middle($record,$titlearray,$stamp['current'],$csv);
                        $finalquery = $csv['sql']['insertstart'] . $sqlmiddle;
                    }
                                    
                    $insert = $wpdb->query( $finalquery );

                    // increase statistics for success or fail
                    if( $insert === false )
                    {
                        eci_adminmes( __('SQL Query Failed'),'The plugin attempted to run an SQL query
                        but Wordpress returned false. Please investigate this before running further data import events. The 
                        SQL query is below:<br /><br />'.$finalquery.'','err' );
                    }
                    elseif( $insert === 0 )
                    {
                        ++$pro[ $filename ]['rowsinsertfail'];
                        ++$importfailed;
                    }
                    elseif( $insert === 1 )
                    {
                        ++$pro[ $filename ]['rowsinsertsuccess'];
                        ++$importsuccess;
                    }    
    
                    unset( $insertquery );
                }
            }
        }
        
        // check timer
        $mtime = microtime(); 
       $mtime = explode(" ",$mtime); 
       $mtime = $mtime[1] + $mtime[0]; 
       $endtime = $mtime; 
       $scripttotaltime = ($endtime - $scriptstarttime); 
       if($scripttotaltime > ECIMAXEXE){$stopscript = true;}else{$stopscript = false;}
   
        // if total records processed hits event limit ( $import ) then exit loop
        if( $stopscript == true || $event == 'insert' && $recordsprocessed >= $import || $event == 'update' && $recordsprocessed >= $update )
        {
            // if were breaking due to hitting limit we will log it
            if($stopscript == true)
            {
                eci_log( 'Execution Limit','ECI reached execution limit on data import',
                'ECI reached the execution limit of '.ECIMAXEXE.' seconds during data import and stopped',
                $filename,$set,'Low',__LINE__,__FILE__,__FUNCTION__ );                    
            }
            
            break;
        }
            
        ++$recordslooped;

    }// end of while loop

    // now update project progress counters
    ++$pro[ $filename ]['events'];
    
    // update csv array to save sql queries
    $csv['arraychange'] =  eci_arraychange( __LINE__,__FILE__ );
    update_option( 'eci_' . $filename, $csv );
    update_option('eci_pro',$pro);

    eci_log( 'Import','Imported:'.$importsuccess.' Import Failed:'.$importfailed.' Updated:'.$updatesuccess.' Updated Failed:'.$updatefailed.'',
    'Imported:'.$importsuccess.' Import Failed:'.$importfailed.' Updated:'.$updatesuccess.' Updated Failed:'.$updatefailed.'',
    $filename,$set,'Low',__LINE__,__FILE__,__FUNCTION__ );    

    // on 100% success output result
    if( $output )
    {
        $outputmes = '
        Records Imported Successfully: '. $importsuccess .'<br />
        Records Import Skipped: '. $importfailed .' (due to duplicate records: '.$pro[$filename]['rowsinsertfail_duplicate'].')<br />
        Records Updated Successfully: '. $updatesuccess .'<br />
        Records Update Skipped: '. $updatefailed .'<br />
        <br />
        Total Imported For Project: '.$pro[ $filename ]['rowsinsertsuccess'].'';
        
        // indicate if records imported match csv files lasted count (deduct 1 which is the header)
        if( $pro[ $filename ]['rowsinsertsuccess'] == $csv['format']['rows'] - 1 )
        {
            $outputmes .= '  <strong>(imported record count matches csv row count)</strong>';
        }

        // if this is a multifile project we need to add a button for each file in the set
        $buttons = '';
        if( isset( $pro[$pro['current']]['filesettype'] ) && $pro[$pro['current']]['filesettype'] == 'multifile' && isset( $pro[$pro['current']]['multifileset'] ) )
        {            
            $buttons .=  '<p>This is a multiple file project, your import counters should match before creating posts. You must also
            import each file in the order below or you will upset the progress counters.</p>';
            $buttons .= '<table class="widefat post fixed">';
            $buttons .= '<tr><td width="230"><strong>Import Buttons</strong></td><td><strong>Records Imported</strong></td></tr>';
                            
            // loop through the multifileset paths, getting base filename for adding to form buttons
            foreach( $pro[$pro['current']]['multifileset'] as $path )
            {
                $buttons .= '
                <tr>
                    <td>
                        <input class="button-primary" type="submit" name="eci_datatransfer_submit" value="'.basename($path).'" />
                    </td>
                    <td>'.$pro[basename($path)]['rowsinsertsuccess'].'</td>
                </tr>
                ';
            }
            
            $buttons .= '</table>';
        }
        else
        {
            $buttons = '<input class="button-primary" type="submit" name="eci_datatransfer_submit" value="'.$filename.'" />';
        }

        // output form with further actions if there is still data to be used else display complete message
        if( $pro[ $filename ]['rowsinsertsuccess'] != $csv['format']['rows'] - 1 )
        {
            $outputmes .= '
            <h3>More Actions For '.$filename.'</h3>
                <form method="post" name="eci_importstage_form" action="">  
                    <input name="eci_filename" type="hidden" value="'.$filename.'" />
                    <input name="eci_filesettype" type="hidden" value="'.$pro[$filename]['filesettype'].'" />
                    Encoding To Apply:
                    <select name="eci_encoding_importencoding" size="s">
                        <option value="None">None</option>
                        <option value="UTF8Standard">UTF-8 Standard Function</option>
                        <option value="UTF8Full">UTF-8 Full (extra processing)</option>
                    </select>
                    <br />
                    <h4>Import More Data From...</h4>
                    '.$buttons.'
                </form><br />';
        }
        elseif( $pro[ $filename ]['rowsinsertsuccess'] == $csv['format']['rows'] - 1 )
        {
            $outputmes .= '<strong>All CSV rows imported</strong>';
            $outputmes .= '<p>All rows have been processed. Any rows indicating as a failure can be duplicate records (if duplicate prevention
            is active) or it may be other conditions setup.</p>';
        }
        
        eci_mes( 'Data '.$event.' event complete for '.$filename.'',$outputmes );
    }
}

/**
* Returns array holding the headers of the giving filename
* It also prepares the array to hold other formats of the column headers in prepartion for the plugins various uses
*/
function wtgcsv_get_file_headers_formatted($csv_filename,$fileid){
    
    wtgcsv_pearcsv_include();
    
    $csv_file_conf = File_CSV::discoverFormat( WTG_CSV_CONTENTFOLDER_DIR . '/' . $csv_filename );
        
    $header_array = array();
    
    // read and loop through the first row in the csv file    
    while ( ( $readone = File_CSV::read( WTG_CSV_CONTENTFOLDER_DIR . '/' . $csv_filename,$csv_file_conf ) ) ){                

        for ( $i = 0; $i < $csv_file_conf['fields']; $i++ ){
            $header_array[$i]['original'] = $readone[$i];
            $header_array[$i]['sql'] = wtgcsv_cleansqlcolumnname($readone[$i]);// none adapted/original sql version of headers, could have duplicates with multi-file jobs
            $header_array[$i]['sql_adapted'] = wtgcsv_cleansqlcolumnname($readone[$i]) . $fileid;// add files id to avoid duplicate header names              
        }           
                    
        break;
    }
    
    return $header_array;    
} 

/**
* Gets the last row count for the giving CSV file, the value is stored in the files array.
* This function does not count the rows in the file
*/
function wtgcsv_get_csvfile_rows(){
    return 0;    
}
?>
