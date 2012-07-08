<?php 
/**
* Enqueues scripts using Wordpress functions.
* This is where new .js files should be added. 
*/
function wtgcsv_print_admin_scripts() {
    
    // load switches for scripts, used to respond to failed script loading
    global $wtgcsv_js_switch;
                 
     // $wtgcsv_js_switch and similiar variables set in main file
     if($wtgcsv_js_switch == true){
        
        ########################################
        #                                      #
        #                jquery                #
        #                                      #
        ######################################## 
        wp_deregister_script( 'jquery' );
            //wp_register_script( 'jquery');                
            wp_register_script( 'jquery','http://ajax.googleapis.com/ajax/libs/jquery/1.7.1/jquery.min.js');
            //wp_register_script( 'jquery',WTG_CSV_URL.'templatesystem/script/jquery-1.7.1.js');
        wp_enqueue_script( 'jquery' );
     
        ########################################
        #                                      #
        #                jquery-ui             #
        #                                      #
        ########################################    
        wp_deregister_script( 'jquery-ui' );
            //wp_register_script( 'jquery-ui');
            //wp_register_script( 'jquery-ui', 'http://jquery-ui.googlecode.com/svn/tags/latest/ui/jquery-ui.js');
            wp_register_script( 'jquery-ui', WTG_CSV_URL.'templatesystem/script/jquery-ui.js');
        wp_enqueue_script( 'jquery-ui' );
        
        #####################################################################################
        #                                                                                   #
        #                        SCRIPTS NOT PACKAGED WITH WORDPRESS                        #
        #                                                                                   #
        #####################################################################################
        // multiselect (checkbox menus)
        wp_register_script('jquery-multiselect',WTG_CSV_URL.'templatesystem/script/multiselect/src/jquery.multiselect.js');
        wp_enqueue_script('jquery-multiselect');
        
        // multiselect (theming I think)
        wp_register_script('jquery-multiselect-prettify',WTG_CSV_URL.'templatesystem/script/multiselect/assets/prettify.js');
        wp_enqueue_script('jquery-multiselect-prettify');
                
        // multiselect menu filter (filter may not be used much until 2013 but the menu is used a lot)
        wp_register_script('jquery-multiselect-filter',WTG_CSV_URL.'templatesystem/script/multiselect/src/jquery.multiselect.filter.js');
        wp_enqueue_script('jquery-multiselect-filter');
                
        // multi-select (lists, not the same as multiselect menus)
        wp_register_script('jquery-multi-select',WTG_CSV_URL.'templatesystem/script/multi-select-basic/jquery.multi-select.js');
        wp_enqueue_script('jquery-multi-select');
        
        // multi-select (lists, not the same as multiselect menus)
        wp_register_script('jquery-cookie',WTG_CSV_URL.'templatesystem/script/jquery.cookie.js');
        wp_enqueue_script('jquery-cookie');        
       
     }
}
    
/**
* Put the function in the head of a file to prevent it being called directly. 
* Uses function_exists to check if a common Wordpress function has loaded, indicating
* Wordpress has loaded. Wordpress security would the be in effect. 
*/
function wtgcsv_exit_forbidden_request($file = 'Unknown'){
    if (!function_exists('add_action')) {
        header('Status: 403 Forbidden');
        header('HTTP/1.1 403 Forbidden');
        exit();
    }
}

/**
* When request will display maximum php errors including Wordpress errors 
*/
function wtgcsv_debugmode(){
    global $wtgcsv_display_errors;
    if($wtgcsv_display_errors){
        global $wtgcsv_debugmode_strict,$wpdb;
        ini_set('display_errors',1);
        if($wtgcsv_debugmode_strict == 1){error_reporting(E_ALL);}else{error_reporting(-1);}
        $wpdb->show_errors();
        $wpdb->print_error();
    }
}

/**
* Compares plugins required minimum php version too the servers. 
* Uses wp_die if version does not match and displays message 
*/
function wtgcsv_php_version_check_wp_die(){
    global $wtgcsv_php_version_minimum,$wtgcsv_currentversion;
    if ( version_compare(PHP_VERSION, $wtgcsv_php_version_minimum, '<') ) {
        if ( is_admin() && (!defined('DOING_AJAX') || !DOING_AJAX) ) {
            require_once ABSPATH.'/wp-admin/includes/plugin.php';
            deactivate_plugins( 'wordpress-csv-importer' );
            wtgcsv_notice('Wordpress 3.2.1 (or a later version) and '.WTG_CSV_PLUGINTITLE.' '.$wtgcsv_currentversion.' requires PHP '.$wtgcsv_php_version_minimum.' 
            or later to operate fully. Your PHP version was detected as '.PHP_VERSION.', it is recommended that you upgrade your PHP
            on your hosting for security and reliability. You can get suitable hosting here at <a href="http://www.webtechglobal.co.uk">WebTechGlobal Hosting</a> for
            free simply for trying the plugin.','error','Extra','Wordpress CSV Importer Requires PHP 5.3 Above','','echo');
        }
    }
}

/**
* Processes data import from giving filename and project ID
* 
* @todo HIGHPRIORITY, add ability too work with a key within a group of files, all files must have the same column however
*/
function wtgcsv_data_import_from_csvfile( $csvfile_name, $table_name, $rate, $jobcode ){
    
    global $wpdb;
    
    wtgcsv_pearcsv_include();    

    // get files modification time
    $csvfile_modtime = filemtime(WTG_CSV_CONTENTFOLDER_DIR .'/'. $csvfile_name);
    
    // set loop counters
    $loop_count = 0;// once $progress marker reached, $loop_count should continue to be the current row ID
    $processed = 0;// new rows processed in the current event
    $inserted = 0;// INSERT to table
    $updated = 0;// UPDATE on a record in table
    $deleted = 0;// DELETE record when CSV file does not appear to still include it
    $void = 0;// IMPORTED OR UPDATE, but then made void so it cannot be used
    $dropped = 0;// number or rows that have been skipped, possibly through failure or users configuration
    $duplicates = 0;
    
    // get the data import job array
    $dataimportjob_array = wtgcsv_get_dataimportjob($jobcode); 
      
    // get files past progress - this is used to skip CSV file rows until reaching a row not yet processed
    $progress = $dataimportjob_array['stats']['allevents']['progress'];

    // TEMPORARY VARIABLES TODO: HIGHPRIORITY, set these variables using the job details
    $multiplefile_project = true;
    $duplicate_check = false;
    $default_order = true;
    
    // use pear to read csv file
    $conf = File_CSV::discoverFormat( WTG_CSV_CONTENTFOLDER_DIR .'/'. $csvfile_name );
    
    // apply auto determined or user defined separator and quote values
    if(isset($dataimportjob_array[$csvfile_name]['separator'])){
        $conf['sep'] = $dataimportjob_array[$csvfile_name]['separator'];        
    }
    
    if(isset($dataimportjob_array[$csvfile_name]['quote'])){
        $conf['quote'] = $dataimportjob_array[$csvfile_name]['quote'];        
    }    

    // loop through records   
    while ( ( $record = File_CSV::read( WTG_CSV_CONTENTFOLDER_DIR .'/'. $csvfile_name, $conf ) ) && $processed < $rate ) {        

        // skip first row of csv file at all times
        if( $loop_count == 0){
            // do nothing - on header row (could possibly do some sort of check on the headers here, maybe use it as a chance to ensure everything is in order)              
        }else{

            ++$processed;// number of records process on this event (will be added too the files progress total)
            
            // determine what the next row ID should be for validation
            // combine $progress (for this file) + $processed rows + 1 
            $expected_rowid = $dataimportjob_array['stats'][$csvfile_name]['progress'] + $processed;
                     
            // is our $expectedrow_id equal too the current row which should also be equal to $loop_count
            if($expected_rowid == $loop_count){
            
                // set $record_id too false, it will need to be an integer before row is updated too record (applies too single or multi file)
                $record_id = false;

                /**
                * Determine Record ID
                * 1. May require new record to be made
                * 2. If multifile, any one of many files may be imported first, must check for existing record
                * 3. Also check using default order $loop_count OR Primary Key between all 3 files
                */
                if( $multiplefile_project && $default_order ){

                    $row_id_check_result = wtgcsv_sql_row_id_check($table_name,$loop_count);// returns boolean
                    if($row_id_check_result){
                        // record already exists for updating our row too - under default order, the ID should equal $loop_count
                        $record_id = $loop_count;    
                    }
                                         
                }elseif($record_id === false && $default_order === false){
                    
                    // user has setup Primary Key, we must do row_id_check using key values and not default row id
                    ### TODO:MEDIUMPRIORITY, add function that takes in a key value (all files must have the same column and same values)
                }    
                
                // if $record_id still false, default to creating a new record
                if( $record_id === false ){
                    $record_id = wtgcsv_query_insert_new_record( $table_name, $csvfile_modtime );    
                }
       
                /**
                * UPDATE RATHER THAN INSERT
                * 
                * Statistics will be recorded as an INSERT because the CSV file row is being used for the first time.
                * My approach of creating the record first is required for multiple file import. So although an SQL
                * UPDATE query is used, we are in the eyes of users, inserting the data for the first time.
                */

                $updaterecord_result = wtgcsv_sql_update_record_dataimportjob( $record, $csvfile_name, $conf['fields'], $jobcode,$record_id, $dataimportjob_array[$csvfile_name]['headers'],$dataimportjob_array['filegrouping'] );
                if($updaterecord_result){
                    ++$inserted;    
                }else{
                    ++$dropped;
                }      
                
            }else{
                // we do not nothing, loop will continue until $progress = $loop_count - 1
            }
            
            // if total records processed hits event limit ( $import ) then exit while loop
            if( $processed >= $rate ){
                break;
            }
                     
        }// end if first row or not
        
        // count number of times WHILE happens
        ++$loop_count;
        
    }// end while $record
    
    #############################################################################
    #                                                                           #
    #             END OF DATA IMPORT JOB EVENT - STORE STATISTICS               #
    #                                                                           #
    #############################################################################
    
    // update last event values
    $dataimportjob_array['stats']['lastevent']['loop_count'] = $loop_count;
    $dataimportjob_array['stats']['lastevent']['processed'] = $processed;
    $dataimportjob_array['stats']['lastevent']['inserted'] = $inserted;    
    $dataimportjob_array['stats']['lastevent']['updated'] = $updated;    
    $dataimportjob_array['stats']['lastevent']['deleted'] = $deleted;        
    $dataimportjob_array['stats']['lastevent']['void'] = $void;        
    $dataimportjob_array['stats']['lastevent']['dropped'] = $dropped;        
    $dataimportjob_array['stats']['lastevent']['duplicates'] = $duplicates;
    // update all event values
    $dataimportjob_array['stats']['allevents']['progress'] = $processed + $dataimportjob_array['stats']['allevents']['progress'];
    $dataimportjob_array['stats']['allevents']['inserted'] = $inserted + $dataimportjob_array['stats']['allevents']['inserted'];    
    $dataimportjob_array['stats']['allevents']['updated'] = $updated + $dataimportjob_array['stats']['allevents']['updated'];
    $dataimportjob_array['stats']['allevents']['deleted'] = $deleted + $dataimportjob_array['stats']['allevents']['deleted'];
    $dataimportjob_array['stats']['allevents']['void'] = $void + $dataimportjob_array['stats']['allevents']['void'];    
    $dataimportjob_array['stats']['allevents']['dropped'] = $dropped + $dataimportjob_array['stats']['allevents']['dropped'];    
    $dataimportjob_array['stats']['allevents']['duplicates'] = $duplicates + $dataimportjob_array['stats']['allevents']['duplicates'];    
    // update the current files statistics
    $dataimportjob_array['stats'][$csvfile_name]['progress'] = $processed + $dataimportjob_array['stats'][$csvfile_name]['progress'];
    $dataimportjob_array['stats'][$csvfile_name]['inserted'] = $inserted + $dataimportjob_array['stats'][$csvfile_name]['inserted'];    
    $dataimportjob_array['stats'][$csvfile_name]['updated'] = $updated + $dataimportjob_array['stats'][$csvfile_name]['updated'];
    $dataimportjob_array['stats'][$csvfile_name]['deleted'] = $deleted + $dataimportjob_array['stats'][$csvfile_name]['deleted'];
    $dataimportjob_array['stats'][$csvfile_name]['void'] = $void + $dataimportjob_array['stats'][$csvfile_name]['void'];    
    $dataimportjob_array['stats'][$csvfile_name]['dropped'] = $dropped + $dataimportjob_array['stats'][$csvfile_name]['dropped'];    
    $dataimportjob_array['stats'][$csvfile_name]['duplicates'] = $duplicates + $dataimportjob_array['stats'][$csvfile_name]['duplicates'];
                        
    // save the $function_result_array in the job array
    wtgcsv_save_dataimportjob($dataimportjob_array,$jobcode);
        
    return $dataimportjob_array;    
}    

function wtgcsv_test_csvfile_columntitles( $csvfile_name, $separator, $quote ){
    
    global $wpdb;
    
    wtgcsv_pearcsv_include();    

    // use pear to read csv file
    $conf = File_CSV::discoverFormat( WTG_CSV_CONTENTFOLDER_DIR .'/'. $csvfile_name );
    $conf['sep'] = $separator;        
    $conf['quote'] = $quote;
    
    if($conf['fields'] == 0 || $conf['fields'] == 1){
        wtgcsv_notice('Sorry I could not establish your CSV file column headers/titles. This is probably because the wrong quote was detected. You can create a Data Import Job with this file, then set the separator manually to avoid any problems.','error','Large','Test 4: Count CSV File Column Headers','','echo');    
    }else{
        wtgcsv_notice('I counted '.$conf['fields'].' fields. If this happens to be incorrect it must be investigated.','success','Large','Test 4: Count CSV File Column Headers','','echo');
    }   
}   

/**
* Updates empty premade record in data job table using CSV file row.
* Reports errors too server log.
* 
* @returns boolean, true if an update was done with success else returns false
* 
* @param mixed $record
* @param mixed $csvfile_name
* @param mixed $fields
* @param mixed $jobcode
* @param mixed $record_id
* @param mixed $headers_array
*/
function wtgcsv_sql_update_record_dataimportjob( $record, $csvfile_name, $fields, $jobcode,$record_id, $headers_array,$filegrouping ){
    // using new record id - update the record
    $updaterecord_result = wtgcsv_sql_update_record( $record, $csvfile_name, $fields, $jobcode,$record_id, $headers_array, $filegrouping );
    // increase $inserted counter if the update was a success, the full process counts as a new inserted record            
    if($updaterecord_result === false){
        return false;
        wtgcsv_error_log('Wordpress CSV Importer: wtgcsv_sql_update_record() returned FALSE for JOB:'.$jobcode.' FILE:'.$csvfile_name.'. Please investigate.');                
    }elseif($updaterecord_result === 1){ 
        return true;   
    }elseif($updaterecord_result === 0){
        wtgcsv_error_log('Wordpress CSV Importer: wtgcsv_sql_update_record() returned 0 for JOB:'.$jobcode.' FILE:'.$csvfile_name.'. Please investigate.');
        return false;
    }  
}   
                
/**
* Establishes the giving files ID within the giving job
* 
* @param mixed $_POST
* @returns integer $fileid, if a match is found, the ID applies to the giving job and file only. It is appened too table column names
* @returns boolean false if csv file loop does not match a file up too the giving $csvfile_name
*/
function wtgcsv_get_csvfile_id($csvfile_name,$jobcode){
    $dataimportjob_array = wtgcsv_get_dataimportjob($jobcode);
    // loop through the jobs files until we reach the giving file name then return its ID
    foreach($dataimportjob_array['files'] as $fileid => $filename ){
        if($filename == $csvfile_name){
            return $fileid;
        }
    }
    return false;
}

/**
* Loads scripts
* 
* @param string $side, admin, public
*/
function wtgcsv_script($side = 'admin'){
    global $wtgcsv_mpt_arr;
    include_once(WTG_CSV_DIR.'templatesystem/script/wtgcsv_script_parent.php');
}

/**
* Loads CSS
* 
* @param string $side, admin, public
*/
function wtgcsv_css($side = 'admin'){
    include_once(WTG_CSV_DIR.'templatesystem/css/wtgcsv_css_parent.php');
}

/**
 * Standard HTTP forwarding
 * @param array $attributes_array
 * @property string status (standard http code i.e. 301 Moved Permanently)
 * @property url location (must begin with http)#
 * 
 * @todo add log recording and possibly statistical storing
 * @todo validate passed url before forwarding, provide option to not apply validation also
 * @todo ensure url validation includes forward slash at end
 * @todo validate status to expected values
 */
function wtgcsv_header_forward($atts){
    extract( shortcode_atts( array(
            'status' => '301 Moved Permanently',
            'location' => 'http://www.webtechglobal.co.uk',
    ), $atts ) );

    // clear cache then return result
    $wpdb->flush();
    
    // TODO: LOWPRIORITY, confirm this is the best way to do this in Wordpress (is there possibly a Wordpress method)
    header("HTTP/1.1 ".$status."");
    header("Status: ".$status."");
    header("Location: ".$location."");
    header("Connection: close");
    exit(0); // Optional, prevents any accidental output
}

/**
 * Establishes if an arrays element count is odd or even (currently divided by 2)
 * For using when balancing tables
 * @param array $array
 * 
 * @todo divide by any giving number, validate the number, the function that builds the table will need to handle that
 */
function wtgcsv_oddeven_arrayelements($array){
    $oddoreven_array = array();

    // store total number of items in totalelements key
    $oddoreven_array['totalelements'] = count($array);

    // store the calculation result from division before rounding up or down, usually up
    $oddoreven_array['divisionbeforerounded'] = $oddoreven_array['totalelements'] / 2;

    // round divisionbeforerounded using ceil and store the answer in columnlimitceil, this is the first columns maximum number of items
    $oddoreven_array['columnlimitceil'] = ceil($oddoreven_array['divisionbeforerounded']);

    // compare our maths answer with the ceil value - if they are not equal then the total is odd
    // if the total is oddd we then know the last column must have one less item, a blank row in the table
    if($oddoreven_array['divisionbeforerounded'] == $oddoreven_array['columnlimitceil']){
        $oddoreven_array['balance'] = 'even';
    }else{
        $oddoreven_array['balance'] = 'odd';
    }

    return $oddoreven_array;
}

/**
 * Starts A Timer - Used To Time Scripts
 * @return the current time in micro
 * 
 * @todo microtime function has caused problems with some users, research alternatives if any
 * @todo put error reporting in this to handle problems better
 */
function wtgcsv_microtimer_start(){
    list($utime, $time) = explode(" ", microtime());
    return ((float)$utime + (float)$time);
}

/**
 * Validate a url (http https ftp)
 * @return true if valid false if not a valid url
 * @param url $url
 */
function wtgcsv_validate_url($url){
	if (!preg_match('/^(http|https|ftp):\/\/([A-Z0-9][A-Z0-9_-]*(?:\.[A-Z0-9][A-Z0-9_-]*)+):?(\d+)?\/?/i',$url)){
            return false;
	} else {
            return true;
	}
}

/**
 * Checks if a database table exist
 * @param string $table_name (possible database table name)
 *
 * @todo SHOW TABLES can cause problems, invistagate another approach such as querying the table, ignoring the error if it does not exist
 */
function wtgcsv_database_table_exist( $table_name ){
	global $wpdb;
	
	// check if table name exists in wordpress database
	if( $wpdb->get_var("SHOW TABLES LIKE `".$table_name."`") != $table_name) {
            return false;
	}else{
            return true;
	}
}

/**
 * Generates a username using a single value by incrementing an appended number until a none used value is found
 * @param string $username_base
 * @return string username, should only fail if the value passed to the function causes so
 * 
 * @todo log entry functions need to be added, store the string, resulting username
 */
function wtgcsv_create_username($username_base){
    $attempt = 0;
    $limit = 500;// maximum trys - would we ever get so many of the same username with appended number incremented?
    $exists = true;// we need to change this to false before we can return a value

    // clean the string
    $username_base = preg_replace('/([^@]*).*/', '$1', $username_base );

    // ensure giving string does not already exist as a username else we can just use it
    $exists = username_exists( $username_base );
    if( $exists == false ){
        return $username_base;
    }else{
        // if $suitable is true then the username already exists, increment it until we find a suitable one
        while( $exists != false ){
            ++$attempt;
            $username = $username_base.$attempt;

            // username_exists returns id of existing user so we want a false return before continuing
            $exists = username_exists( $username );

            // break look when hit limit or found suitable username
            if($attempt > $limit || $exists == false ){
                break;
            }
        }

        // we should have our login/username by now
        if ( $exists == false ) {
            return $username;
        }
    }
}


/**
 * Includes PEAR CSV
 */
function wtgcsv_pearcsv_include(){
    if (strtoupper(substr(PHP_OS, 0, 3)) === 'WIN'){
        ini_set('include_path',rtrim(ini_get('include_path'),';').';'.dirname(__FILE__).'/pear/');
    }else{
        ini_set('include_path',rtrim(ini_get('include_path'),':').':'.dirname(__FILE__).'/pear/');
    }
    require_once 'File/CSV.php';
}

/**
 * Checks if the giving history file (by label not filename) is active
 * @global array $wtgcsv_adm_set
 * @param string $historyfile (not file name: general,sql,admin,user,error)
 * @return true or false
 */
function wtgcsv_ishistory_active($historyfile = false){
    if(!$historyfile){
        return false;
    }else{
        global $wtgcsv_adm_set;// set in wtgcsv_variables.php       
        if($wtgcsv_adm_set['log_general_active'] == false
            && $wtgcsv_adm_set['log_sql_active'] == false
                && $wtgcsv_adm_set['log_admin_active'] == false
                    && $wtgcsv_adm_set['log_user_active'] == false
                        && $wtgcsv_adm_set['log_error_active'] == false){
            return false;
        }else{
            if($historyfile == 'any'){
                return true;
            }else{
                return $wtgcsv_adm_set['log_'.$historyfile.'_active'];
            }
        }
    }
    return false;
}

/**
 * Returns the current url as viewed with all variables etc
 * @return string current url with all GET variables
 */
function wtgcsv_currenturl() {
    $pageURL = 'http';
    if (isset($_SERVER["HTTPS"]) && $_SERVER["HTTPS"] == "on") {$pageURL .= "s";}
    
        $pageURL .= "://";
        
    if (isset($_SERVER["SERVER_PORT"]) && $_SERVER["SERVER_PORT"] != "80" && isset($_SERVER["SERVER_NAME"]) && isset($_SERVER["REQUEST_URI"])) {

        $pageURL .= $_SERVER["SERVER_NAME"].":".$_SERVER["SERVER_PORT"].$_SERVER["REQUEST_URI"];

    }elseif(isset($_SERVER["SERVER_NAME"]) && isset($_SERVER["REQUEST_URI"])){
        
        $pageURL .= $_SERVER["SERVER_NAME"].$_SERVER["REQUEST_URI"];
        
    }else{
        
        return 'Error Unexpected State In Current URL Function';
        
    }
    return $pageURL;
}

/**
 * Checks if history file exists
 * @param string $historytype (general,sql,admin,user,error)
 * @return boolean
 * 
 * @todo adapt to allow log file location to be changed and this function too use the new location not the default   
 */
function wtgcsv_logfile_exists($logtype){
    return file_exists(wtgcsv_logfilepath($logtype));
}

/**
* Deletes log file if over specific size 
* Must ensure file exists before calling this function
* 
* @param string $logtype (general,error,sql,admin,user)
* @param integer $sizelimit, state the maximum bytes (307200 = 300kb which is a reasonable size and is default)
*/
function wtgcsv_logfile_autodelete($logtype,$sizelimit = 307200){
    // check file size - delete if over 300kb as stated on Log page
    if( filesize( wtgcsv_logfilepath($logtype) ) > $sizelimit ){
        return unlink( wtgcsv_logfilepath($logtype) );
    }    
}             
                    
/**
* Returns the full path to giving log file
* 
* @param string $logtype (admin,user,error,sql,general = default)
*/
function wtgcsv_logfilepath($logtype = 'general'){
    return WTG_CSV_CONTENTFOLDER_DIR.'/'.WTG_CSV_ABB.'log_'.$logtype.'.csv'; 
}                        

/**
* Checks if a domain exists by ensuring a site is online
* 
* @return boolean, false if domain does not exist or site is not online, true otherwise
* 
* @param string $domain
*/
function wtgcsv_domain_online($domain){
    // @todo look into these 2 lines, benefits they have when used in Wordpress if any
   //ini_set("default_socket_timeout","05");
   //set_time_limit(5);
   $f = @fopen($domain,"r");
   if($f == false){
       return false;
   }else{
       $r=fread($f,1000);
       fclose($f);
       if(strlen($r)>1) {
           return true;
       }
       else {
           return false;
       }      
   }  
}

/**
* Returns a value for Tab Number, if $_GET[WTG_##_ABB . 'tabnumber'] not set returns 0 
*/
function wtgcsv_get_tabnumber(){                      
    if(!isset($_GET['wtgcsv_tabnumber'])){
        return 0;
    }else{
        return $_GET['wtgcsv_tabnumber'];                   
    }                                                      
}     

/**
 * Intercepts and processes form subsmission, requiring user to be logged in, adding further security 
 * to the blog within this plugin.
 */
function wtgcsv_form_submission_processing(){  
    if(is_user_logged_in()){

        if(isset($_POST['wtgcsv_post_processing_required'])){
            
            // if wtgcsv_post_processing_required true 
            if($_POST['wtgcsv_post_processing_required']){
                
                // has $_POST dump been request?
                global $wtgcsv_dumppostget;// set in main file for development use only
                if($wtgcsv_dumppostget){
                    echo '<h1>$_POST</h1>';
                    echo '<pre>';
                    var_dump($_POST);
                    echo '</pre>';            
                    echo '<h1>$_GET</h1>';
                    echo '<pre>';
                    var_dump($_GET);
                    echo '</pre>';                  
                }
                
                // include file that handles $_POST submission
                require_once(WTG_CSV_DIR.'templatesystem/include/wtgcsv_form_processing.php');                  
            }
        }

    } 
}

/**
 * Checks existing plugins and displays notices with advice or informaton
 * This is not only for code conflicts but operational conflicts also especially automated processes
 *
 * $return $critical_conflict_result true or false (true indicatesd a critical conflict found, prevents installation, this should be very rare)
 * 
 * @todo make this function available to process manually so user can check notices again
 * @todo re-enable warnings for none activated plugins is_plugin_inactive, do so when Notice Boxes have closure button
 */
function wtgcsv_plugin_conflict_prevention(){
    // track critical conflicts, return the result and use to prevent installation
    // only change $conflict_found to true if the conflict is critical, if it only effects partial use
    // then allow installation but warn user
    $conflict_found = false;
    
    // we create an array of profiles for plugins we want to check
    $plugin_profiles = array();

    // WTG Notice Boxes (example only)
    $plugin_profiles[0]['switch'] = 1;//used to use or not use this profile, 0 is no and 1 is use
    $plugin_profiles[0]['title'] = 'WTG Notice Boxes';
    $plugin_profiles[0]['slug'] = 'wtg-notice-boxes';
    $plugin_profiles[0]['author'] = 'WebTechGlobal';
    $plugin_profiles[0]['title_active'] = 'WTG Notice Boxes In Use, Thank You';
    $plugin_profiles[0]['message_active'] = __('WebTechGlobal would like to thank you for also using WTG Notice Boxes, we hope you find it useful. Please let us know if there is anything you would like us to improve for your needs.');
    $plugin_profiles[0]['title_inactive'] = 'WTG Notice Boxes In Installed, Thank You';
    $plugin_profiles[0]['message_inactive'] = __('We have noticed you are also using WTG Notice Boxes, we hope you are satisfied with it.');
    $plugin_profiles[0]['type'] = 'info';//passed to the message function to apply styling and set type of notice displayed
    $plugin_profiles[0]['criticalconflict'] = true;// true indicates that the conflict will happen if plugin active i.e. not specific settings only, simply being active has an effect
    /*******  type values =  success,warning,error,question,processing,stop    *****/

    // loop through the profiles now
    if(isset($plugin_profiles) && $plugin_profiles != false){
        foreach($plugin_profiles as $key=>$plugin){
            if(is_plugin_active($plugin['slug'])){
                // recommend that the user does not use the plugin
                wtgcsv_notice($plugin['message_active'],'warning','Tiny',false);

                // if the conflict is critical, we will prevent installation
                if($plugin['criticalconflict'] == true){
                    $conflict_found = true;// indicates critical conflict found
                }
            }elseif(is_plugin_inactive($plugin['slug'])){
                // warn user about potential problems if they active the plugin
                // wtgcsv_notice($plugin['message_inactive'],'info','Tiny',false);
            }
        }
    }

    return $conflict_found;
}

/**
* Updates the current project , calls project array globally 
*/
function wtgcsv_update_currentproject_array(){
    
}

/**
* Called when plugin is being activated in Wordpress.
* I am avoiding anything actually being installed during this process. * 
*/
function wtgcsv_register_activation_hook(){
    global $wtgcsv_isbeingactivated;
    $wtgcsv_isbeingactivated = true;// used to avoid loading files not required during activation
}

/**
* Wrapper, uses wtgcsv_link_toadmin to create local admin url
* 
* @param mixed $page
* @param mixed $values 
*/
function wtgcsv_create_adminurl($page,$values = ''){
    return wtgcsv_link_toadmin($page,$values);    
}

/**
* Returns the admin theme 
*/
function wtgcsv_get_theme(){
    return get_option(WTG_CSV_ABB.'theme');
}

/**
* Update admin theme
* 
* @param mixed $theme_name
*/
function wtgcsv_update_theme($theme_name){
    update_option(WTG_CSV_ABB.'theme',$theme_name);  
}

 /**
 * Delets plugin main navigation 
 */
function wtgcsv_delete_tabmenu(){
    return delete_option(WTG_CSV_ABB . 'tabmenu');
}
                  
/**
 * Template used to create new functions, uses utility functions and common globals etc
 * Copy and paste this function
 */
function wtgcsv_templatefunction(){

    // standard option update with standard result output - only if your updating option else remove
    wtgcsv_option_array_update( $option_key, $option_value, $line = __LINE__, $file = __FILE__, $function = __FUNCTION__ );

    // general result
    if($function_result_success){
        return true;
    }else{
        return false;
    }
}

/**
 * Displays a notification with a long list of style options available
 * Requires visitor to be logged in and on an admin page, dont need to do prevalidation before calling function
 *     
 * Dont include a title attribute if you want to use defaults and stick to a standard format
 *  
 * @param string $message, main message
 * @param mixed $type, determines colour styling (question,info,success,warning,error,processing,stop)
 * @param mixed $size, determines box size (Tiny,Small,Large,Extra)
 * @param mixed $title, a simple header
 * @param mixed $helpurl, when required can offer link too help content (will be added closer to 2013)
 * @param mixed $output_type, how to handle message (echo will add notice too $wtgcsv_notice_array and passing return will return the entire html)
 * @param mixed $persistent, boolean (true will mean the message stays until user deletes it manually)
 * 
 * @todo LOWPRIORITY, provide permanent closure button, will this be done with a dialogue ID to prevent it opening again 
 * @todo LOWPRIORITY, make use of the help url, style it as a button or link within messages i.e. appended sentence.
 * @todo LOWPRIORITY, add a paragraphed section of the message for a second $message variable for extra information
 */
function wtgcsv_notice($message,$type = 'success',$size = 'Extra',$title = false, $helpurl = 'http://www.webtechglobal.co.uk/forum', $output_type = 'echo',$persistent = false){
    if(is_admin()){

        if($output_type == 'return'){
            // begin building output
            $output = '';
            $output .= '<div class="'.$type.$size.'">';

            // set h4 where required
            if($size == 'Large' || $size == 'Extra'){$output .= '<h4>'.$title.'</h4>';}
            elseif($size == 'Small'){$output .= $title;}

            $output .= $message.'</div>';           

            return $output;
        }else{
            global $wtgcsv_notice_array;

            $next_key = wtgcsv_get_array_nextkey($wtgcsv_notice_array);

            $wtgcsv_notice_array[$next_key]['message'] = $message;
            $wtgcsv_notice_array[$next_key]['type'] = $type;
            $wtgcsv_notice_array[$next_key]['size'] = $size;
            $wtgcsv_notice_array[$next_key]['title'] = $title;
            $wtgcsv_notice_array[$next_key]['helpurl'] = $helpurl;           
        }
    }
}

/**
 * Adds a jquery effect submit button, for using in form
 * 
 * @param string $panel_name (original use for in panels,panel name acts as an identifier)
 * @uses csvip_helpbutton function uses jquery script required by this button to have any jquery effect
 */
function wtgcsv_formsubmitbutton_jquery($form_name){?>
    <div class="jquerybutton"><input type="submit" name="<?php echo WTG_CSV_ABB;?><?php echo $form_name;?>_submit" value="Submit"/></div><?php
}

/**
 * Displays a simple and standard wordpress formatted message, this is not the WTG Notice Boxes function
 * Should only be used in processing functions, not header functions i.e. hooks
 */
function wtgcsv_mes($title,$content,$type,$button_array = false){
    // only output anything if user logged and is on admin page
    if(is_user_logged_in() && is_admin()){
        // does user want buttons for actions in footer of the notice? this will add a table of buttons to end of notice
        if($button_array){
                $content .= wtgcsv_form_buttons_table_return($button_array);}

        // build final notice (only if admin logged in)
        if($type==0){$id='message';$class='updated';}elseif($type==1){$id='error';$class='$error';}
        echo '<div id="'.$id.'" class="'.$class.'"><strong>'.$title.'</strong><p>'. $content .'</p></div>';
    }
}

/**
 * Updates option array, records history to aid debugging
 * @return true on success or false on failure
 * @param string $option_key (wordpress options table key value)
 * @param mixed $option_value (can be string or array)
 * @param integer $line (line number passed by __LINE__)
 * @param string $file (file name passed by __FILE__)
 * @param string $function (function name passed by __FUNCTION__)
 * 
 * @todo check if including the php constants in the attributes applyes where this function is or where it is used
 * @todo what is the best way to determine if update actually failed or there was no difference in array ?
 * @todo complete logging (no output that will be handled where function called)
 */
function wtgcsv_option_array_update( $option_key, $option_value, $line = __LINE__, $file = __FILE__, $function = __FUNCTION__ ){
    // store an array of values indicating the update time and where it occured
    $change = array();
    $change['date'] = date("Y-m-d H:i:s");
    $change['time'] = time();
    $change['line'] = $line;
    $change['file'] = $file;

    $option_value['arrayhistory'] = $change;

    $option_update_result = update_option($option_key,$option_value);

    if($option_update_result){
            // log result
    }else{
            // log result
    }

    return $change;
}

/**
 * Exit on error or use misconfiguration but offer help information, links etc
 * The information will be based on keywords related to the area of the plugin that the user is experiencing problems with
 * @param array $keywords (an array of keywords that will be used to determine extra help content)
 * @param url $url (link from a big button to the related page for the feature the exit is used in connection with)
 * 
 * @todo display notice box that will hold the help button
 * @todo design or have designed a suitable help button
 * @todo display further possible help options crawled on the plugins site
 * @todo display Google AdWords search lastly
 */
function wtgcsv_exit($url,$keywords){
    // call a function here that displays a notice box of helpful information including links and a humerous image
    // in relation to any sort of problem

    // we'll use the keywords to retrieve the information using RSS or API, not 100% sure at this time
    exit;
}

/**
 * Echos the html beginning of a form and beginning of widefat post fixed table
 * 
 * @param string $name (a unique value to identify the form)
 * @param string $method (optional, default is post, post or get)
 * @param string $action (optional, default is null for self submission - can give url)
 * @param string $enctype (pass enctype="multipart/form-data" to create a file upload form)
 */
function wtgcsv_formstart_standard($name,$id = 'none', $method = 'post',$class,$action = '',$enctype = ''){
    if($class){
        $class = 'class="'.$class.'"';
    }else{
        $class = '';         
    }
    echo '<form '.$class.' '.$enctype.' id="'.$id.'" method="'.$method.'" name="'.$name.'" action="'.$action.'">
    <input type="hidden" id="'.WTG_CSV_ABB.'post_processing_required" name="'.WTG_CSV_ABB.'post_processing_required" value="true">';
}

/**
 * Adds <button> with jquerybutton class and </form>, for using after a function that outputs a form
 * Add all parameteres or add none for defaults
 * @param string $buttontitle
 * @param string $buttonid
 */
function wtgcsv_formend_standard($buttontitle = 'Submit',$buttonid = 'notrequired'){
    if($buttonid == 'notrequired'){
        $buttonid = 'wtgcsv_notrequired';
    }else{
        $buttonid = $buttonid.'_formbutton';
    }?>

    <br />
    
    <div class="jquerybutton">
        <button id="<?php echo $buttonid;?>"><?php echo $buttontitle;?></button>
    </div>
    
    </form><?php
}


/**
 * Used to build a query history file,intention is to display the history
 * Type Values: general,sql,admin,user,error
 * General History File Filters: install

 * @var WTG_CSV_RECORD_ALL_SQL_QUERIES boolean switch to disable this logging
 * @global $wpdb
 * @uses extract, shortcode_atts
 *
 * @param string Project name (usually csv filename)
 * @param integer line __LINE__
 * @param string file __FILE__
 * @param string function __FUNCTION__
 * @param string type indicates what log file to write to (general,sql,admin,user,error)
 * @param mixed dump this is the main variable that holds sql query, comments, results etc
 * @param string comment comment by developer that may help users or help other developers
 * @param string sql_result mysql result, use wtgcsv_log_sql_default for ease
 * @param string sql_query mysql query,  use wtgcsv_log_sql_default for ease
 *
 * @todo create other constants like the one setup for sql log entries
 * @todo create option to add entries to server error log file
 */
function wtgcsv_log($atts){
    
    /* COPY AND PASTE ARRAY
    $atts = array();
    $atts['projectname'] = 'NA';// Project name (usually csv file name)
    $atts['projectid'] = 'Unknown';    
    $atts['jobcode'] = 'Unknown';    
    $atts['csvfile'] = 'Unknown';                   
    $atts['date'] = wtgcsv_date();// wtgcsv_date()   
    $atts['line'] = __LINE__;
    $atts['file'] = __FILE__;
    $atts['function'] = __FUNCTION__;
    $atts['logtype'] = 'general';// general, sql, admin, user, error (can be others but all fit into these categories)
    $atts['dump'] = 'None';// anything, variable, text, url, html, php
    $atts['comment'] = 'None';// comment to help users or developers (recommended 60-80 characters long)
    $atts['sql_result'] = 'NA';// wordpress sql result value
    $atts['sql_query'] = 'NA';// wordpress sql query value
    $atts['style'] = 'success';// Notice box style (info,success,warning,error,question,processing,stop)
    $atts['category'] = 'Unknown';// used to filter entries, a lowercase keyword // TODO: make this comma seperated values but also make the log viewer search through them
    wtgcsv_log($atts);*/    
    
    extract( shortcode_atts( array(
        'projectname' => 'NA',// Project name or ID (usually csv file name)               
        'date' => wtgcsv_date(),// wtgcsv_date()   
        'line' => 'Unknown',// __LINE__
        'file' => 'Unknown',// __FILE__
        'function' => 'Unknown',// __FUNCTION__
        'logtype' => 'general',// general, sql, admin, user, error (can be others but all fit into these categories)
        'dump' => 'None',// anything, variable, text, url, html, php
        'comment' => 'None',// comment to help users or developers (recommended 60-80 characters long)
        'sql_result' => 'NA',// wordpress sql result value
        'sql_query' => 'NA',// wordpress sql query value
        'style' => 'success',// Notice box style (info,success,warning,error,question,processing,stop)
        'category' => 'Unknown',// used to filter entries, a lowercase keyword // TODO: make this comma seperated values but also make the log viewer search through them
    ), $atts ) );
    
    /**
    * USED CATEGORIES
    * 1. Data Import Job
    */    

    // ensure the log file giving is active
    $historyfileactive = wtgcsv_ishistory_active($logtype);

    if($historyfileactive){
        // boolean switch prevents log writing until it is confirmed settings require it
        $write_to_log = false;
        // is log request sql and does user or plugin author want sql logging
        if($logtype == 'general' || $logtype == 'install' || $logtype == 'installation' || $logtype == 'uninstall' || $logtype == 'reinstal'){
            
            $write_to_log = true;
            $logfile_path = wtgcsv_logfilepath('general');
            
        }elseif($logtype == 'sql' || $logtype == 'database' || $logtype == 'mysql'){ 
        
            if(defined(WTG_CSV_RECORD_ALL_SQL_QUERIES) && WTG_CSV_RECORD_ALL_SQL_QUERIES == true){
                $write_to_log = true;
                $logfile_path = wtgcsv_logfilepath('sql');
            }
            
        }elseif($logtype == 'admin' || $logtype == 'administrator' || $logtype == 'owner' || $logtype == 'adm'){
            
            $write_to_log = true;
            $logfile_path = wtgcsv_logfilepath('admin');
                       
        }elseif($logtype == 'user' || $logtype == 'users' || $logtype == 'public' || $logtype == 'visitors'){
            
            $write_to_log = true;
            $logfile_path = wtgcsv_logfilepath('user');
            
        }elseif($logtype == 'error' || $logtype == 'err' || $logtype == 'fault' || $logtype == 'bug' || $logtype == 'problem'){
            
            $write_to_log = true;
            $logfile_path = wtgcsv_logfilepath('error');
                      
        }
              
        // if required continue to make log entry
        if($write_to_log){
            global $wpdb;
                      
            // check if file exists, else create it with a header
            if(!wtgcsv_logfile_exists($logtype)){
                wtgcsv_create_logfile($logtype);
            }else{
                wtgcsv_logfile_autodelete($logtype,307200);// TODO: LOW PRIORITY,create setting for auto delete size limit
            }

            // make final write to log, this will also create the log file if it does not exist            
            $write = array( '"'.$projectname.'"','"'.wtgcsv_date().'"','"'.$line.'"','"'.$file.'"','"'.$function.'"','"'.$dump.'"','"'.$comment.'"','"'.$sql_result.'"','"'.$sql_query.'"','"'.$style.'"','"'.$category.'"' );
            @$fp = fopen( $logfile_path, 'a');
            @fputcsv($fp, $write);
            @fclose($fp);
            
        }// if write to log or not
    }// is history file active
}

/**
* Returns the plugins standard date with common format used in code, not seen by user in most cases
* 
* @param integer $timeaddition, number of seconds to add to the current time to create a future date and time
* @todo adapt this to return various date formats to suit interface
*/
function wtgcsv_date($timeaddition = 0){
    return date('Y-m-d H:i:s',time()+$timeaddition);    
} 

/**
* Determines if giving varaible is WP_Error or not
* Logs error if boolean true result determined
* 
* @uses class WP_Error($code, $message, $data)
* @uses is_wp_error(),$wpval->get_error_data(),$wpval->get_error_message() 
* @param mixed $v is the value being checked as possible Wordpress error
* @param boolean $returnv, default is false, true causes $v to be returned instead of boolean when value is NOT an error
* @returns boolean true if giving value is WP_Error and false if not
*/
function wtgcsv_is_WP_Error($wpval,$returnv = false){
    if(is_wp_error($wpval)){

        $atts = array();
        $atts['projectname'] = 'NA';// TODO:LOWPRIORITY, get current project name from global
        //$atts['date'] = wtgcsv_date();// wtgcsv_date()   
        $atts['logtype'] = 'error';
        $atts['dump'] = 'Data Value: ' . $wpval->get_error_data();
        $atts['comment'] = 'Created by wtgcsv_is_WP_Error, Wordpress message is: ' . $wpval->get_error_message();
        $atts['style'] = 'error';

        wtgcsv_log($atts);  
        
        $result = true;   
    }else{
        $result = false;
    }

    if($result == false && $returnv == true){
        // not an error and value to be returned
        return $wpval;
    } elseif($result == true){
        // is an error or request needs boolean returned
        return true;
    }elseif($result == false && $returnv == false){
        // not an error but request wants boolean returned, false to indicate not WP_Error
        return false;
    }
}

/**
* Uses error_log to record an error too servers main error log.
*  
* @param string $m, the message to be recorded
*/
function wtgcsv_error_log($m){ 
   error_log($m);
}

function wtgcsv_get_default_contenttemplate_name(){
    global $wtgcsv_currentproject_code;
    $default_template_id = wtgcsv_get_default_contenttemplate_id( $wtgcsv_currentproject_code );

    if(!$default_template_id || $default_template_id == '0'){
        return 'No Default Content Template';
    }else{
        // get wtgcsvtemplate post title

        $template_post = get_post($default_template_id); 
        return $template_post->post_title;        
    }
}

function wtgcsv_get_default_titletemplate_name(){
    global $wtgcsv_currentproject_code;
    $default_template_id = wtgcsv_get_default_titletemplate_id( $wtgcsv_currentproject_code );

    if(!$default_template_id || $default_template_id == '0'){
        return 'No Default Title Template';
    }else{
        // get wtgcsvtemplate post title
        $template_post = get_post($default_template_id); 
        return $template_post->post_title;        
    }
}

/**
* Gets the content of giving title template ID (also a post id)
* 
* @returns string indicating fault and includes giving ID
* @param mixed $project_array
*/
function wtgcsv_get_titletemplate_design($title_template_id){
    $template_post = get_post($title_template_id);
    if(!$template_post){
        return 'Fault:title template post not found with ID ' . $title_template_id;
    } 
    return $template_post->post_content;    
}

/**
* Gets the content of giving content template id (also a post id)
* 
* @param mixed $title_template_id
*/
function wtgcsv_get_contenttemplate_design($content_template_id){
    $template_post = get_post($content_template_id);
    if(!$template_post){
        return 'Fault:content template post not found with ID ' . $content_template_id;
    } 
    return $template_post->post_content;    
}
         
/**
* Gets the default content template id (post_id for wtgcsvcontent post type) for the giving project
* 
* @param mixed $project_code
* @returns false if no current project or current project has no default template yet
*/
function wtgcsv_get_default_contenttemplate_id( $project_code ){
    if(!isset($project_code)){
        return false;
    }else{
        $project_array = wtgcsv_get_project_array($project_code);

        if(isset($project_array['default_contenttemplate_id'])){
            return $project_array['default_contenttemplate_id'];            
        }
    }  
    return false;  
}

/**
* Gets the default title template id (post_id for wtgcsvtitle post type) for the giving project
* 
* @param mixed $wtgcsv_currentproject_code
* @returns false if no current project or current project has no default title template yet
*/
function wtgcsv_get_default_titletemplate_id( $wtgcsv_currentproject_code ){
    if(!isset($wtgcsv_currentproject_code)){
        return false;
    }else{
        $project_array = wtgcsv_get_project_array($wtgcsv_currentproject_code);

        if(isset($project_array['default_titletemplate_id'])){
            return $project_array['default_titletemplate_id'];            
        }
    }  
    return false;  
}

/**
* Returns the Wordpress option record value for giving project code
* 
* @uses unserialize before return 
* @param mixed $project_code
* @return mixed
*/
function wtgcsv_get_project_array($project_code){
    $getproject_array = get_option( 'wtgcsv_' . $project_code );
    $getproject_array_unserialized = maybe_unserialize($getproject_array);
    return $getproject_array_unserialized;
}

/**
* Updates (also adds) new option record for post creation project
* 
* @param string $project_code
* @param array $project_array
*/
function wtgcsv_update_option_postcreationproject($project_code,$project_array){
    $project_array_serialized = maybe_serialize($project_array);
    $update_result = update_option('wtgcsv_' . $project_code,$project_array_serialized);
    return $update_result;    
}

/**
* Returns specified part of array resulting from explode on string.
* Can only be used properly when the number of values in resulting array is known
* and when the returned parts type is known.
* 
* @param mixed $delimeter
* @param mixed $returnpart
* @param mixed $string
*/
function wtgcsv_explode_tablecolumn_returnnode($delimeter,$returnpart,$string){
    $explode_array = explode($delimeter,$string);
    return $explode_array[$returnpart];    
}

/**
* add new post creation project too data import job array
* @param mixed $project_code
* @param mixed $project_name
* @return bool
*/
function wtgcsv_update_option_postcreationproject_list_newproject($project_code,$project_name){
    global $wtgcsv_projectslist_array;
    if(!is_array($wtgcsv_projectslist_array)){$wtgcsv_projectslist_array = array();}
    $wtgcsv_projectslist_array[$project_code]['name'] = $project_name; 
    return update_option('wtgcsv_projectslist',serialize($wtgcsv_projectslist_array));     
}

/**
* Returns array of all existing projects, used to create a list of projects
* @returns false if no option for wtgcsv_projectslist exists else returns unserialized array 
*/
function wtgcsv_get_projectslist(){
    $get_projectlist_result = get_option('wtgcsv_projectslist');
    if(!$get_projectlist_result){
        return false;
    }
    return unserialize(get_option('wtgcsv_projectslist'));
}

/**
* Returns last key from giving array. Sorts the array by key also (only works if not mixed numeric alpha).
* Use before adding new entry to array. This approach allows the key to be displayed to user for reference or returned for other use.
* 
* @uses ksort, sorts array key order should the keys be random order
* @uses end, moves internal pointer too end of array
* @uses key, returns the key for giving array element
* @returns mixed, key value could be string or numeric depending on giving array
*/
function wtgcsv_get_array_lastkey($array){
    ksort($array);
    end($array);
    return key($array);
}

/**
* Get arrays next key (only works with numeric key)
*/
function wtgcsv_get_array_nextkey($array){
    ksort($array);
    end($array);
    return key($array) + 1;
}

/**
* Gets the schedule array from wordpress option table.
* Array [times] holds permitted days and hours.
* Array [limits] holds the maximum post creation numbers 
*/
function wtgcsv_get_option_schedule_array(){
    $getschedule_array = get_option( 'wtgcsv_schedule');
    $getschedule_array_unserialized = maybe_unserialize($getschedule_array);
    return $getschedule_array_unserialized;    
}

/**
* Updates the schedule array from wordpress option table.
* Array [times] holds permitted days and hours.
* Array [limits] holds the maximum post creation numbers 
*/
function wtgcsv_update_option_schedule_array($schedule_array){
    $schedule_array_serialized = maybe_serialize($schedule_array);
    $update_result = update_option('wtgcsv_schedule',$schedule_array_serialized);
    return $update_result;    
}


/**
* Gets array of data import jobs and returns it as it is from Wordpress options record
* @returns false if no array stored or problem accessing options table 
*/
function wtgcsv_get_option_dataimportjobs_array(){
    $dataimportjobs_array = get_option('wtgcsv_dataimportjobs');
    $val = maybe_unserialize($dataimportjobs_array);
    if(!is_array($val)){
        return false;    
    }
    return $val;
}

/**
* Gets array of job tables and returns it as it is from Wordpress options record.
* Can use this to check what tables belong to which jobs and for quickly deleting all job tables etc.
* 
* @returns false if no array stored or problem accessing options table 
*/
function wtgcsv_get_option_jobtable_array(){
    $jobtables_array = get_option('wtgcsv_jobtables');
    $val = maybe_unserialize($jobtables_array);
    if(!is_array($val)){
        return false;
    }
    return $val;    
}

/**
* Creates or updates existing a job tables array record in wordpress options table
*/
function wtgcsv_save_jobtables_array($jobtables_array){
    $jobtables_array_seralized = maybe_serialize($jobtables_array);
    $result = update_option('wtgcsv_jobtables',$jobtables_array_seralized);
    $wperror_result = wtgcsv_is_WP_Error($result);
    if($wperror_result){
        return false;
    }else{
        return true;
    }
}

/**
* Gets a data import job option record using the giving code
* @return false if get_option does not return an array else the array is returned
*/
function wtgcsv_get_dataimportjob($jobcode){
    $val = unserialize(get_option('wtgcsv_' . $jobcode));// should return an array
    if(!is_array($val)){
        return false;    
    }
    return $val;    
}

/**
* Returns just the headers for a single giving CSV file within a single giving job
*/
function wtgcsv_get_dataimportjob_headers_singlefile($jobcode,$csvfile_name){
    $dataimportjob_array = wtgcsv_get_dataimportjob($jobcode);
    return $dataimportjob_array[$csvfile_name]['headers'];    
} 

/**
* Creates or updates existing data import job record in wordpress options table
* 
* @param mixed $jobarray
* @param string $code
* @returns boolean, true if success or false if failed
*/
function wtgcsv_save_dataimportjob($jobarray,$code){
    $jobarray_seralized = maybe_serialize($jobarray);
    $result = update_option('wtgcsv_' . $code,$jobarray_seralized);
    $wperror_result = wtgcsv_is_WP_Error($result);
    if($wperror_result){
        return false;
    }else{
        return true;
    }
} 

/**
* Uses wtgcsv_save_dataimportjob which uses update_option and serialize on the $jobarray
* 
* @param mixed $jobarray
* @param mixed $code
* @return boolean,
*/
function wtgcsv_update_dataimportjob($jobarray,$code){
    return wtgcsv_save_dataimportjob($jobarray,$code);
}    

/**
* Gets and unserializes public settings array (publicset) 
*/
function wtgcsv_get_option_publicset(){
    return unserialize(get_option('wtgcsv_publicset'));
}

/**
* Deletes the option record for giving data import job code
* 
* @param mixed $jobcode
*/
function wtgcsv_delete_dataimportjob_optionrecord($jobcode){
    return delete_option('wtgcsv_' . $jobcode);    
}

/**
* Updates data import jobs array
*/
function wtgcsv_update_option_dataimportjobs($dataimportjob_array){
    $dataimportjob_array_serialized = maybe_serialize($dataimportjob_array);
    update_option('wtgcsv_dataimportjobs',$dataimportjob_array_serialized);
}

/**
* add new job to data import job array
* 
* @param string $code, acts as an ID for the job but I never called it ID due to so many other ID existing in Wordpress
* @param string $jobname, human identifier of the job
*/
function wtgcsv_add_dataimportjob_to_list($code,$jobname){
    global $wtgcsv_dataimportjobs_array;
    if(!$wtgcsv_dataimportjobs_array || $wtgcsv_dataimportjobs_array == false){
        $wtgcsv_dataimportjobs_array = array();    
    }
    $wtgcsv_dataimportjobs_array[$code]['name'] = $jobname;
    wtgcsv_update_option_dataimportjobs($wtgcsv_dataimportjobs_array);    
}

# truncate string to a specific length

/**
* truncate string to a specific length 
* 
* @param string $string, string to be shortened if too long
* @param integer $max_length, maximum length the string is allowed to be
* @return string, possibly shortened if longer than
*/
function wtgcsv_truncatestring( $string, $max_length ){
    if (strlen($string) > $max_length) {
        $split = preg_split("/\n/", wordwrap($string, $max_length));
        return ($split[0]);
    }
    return ( $string );
}

/**
* Checks if DOING_AJAX is set, indicating header is loaded for ajax request only
* @return boolean true if Ajax request ongoing else false        
*/
function wtgcsv_DOING_AJAX(){
    if(defined('DOING_AJAX')){
        return true;
    }
    return false;    
}       
?>