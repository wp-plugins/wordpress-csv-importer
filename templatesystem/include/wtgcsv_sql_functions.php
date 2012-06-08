<?php
/**
* Uses get_results and finds all DISTINCT meta_keys, returns the result.
* Currently does not have any measure to ensure keys are custom field only.
* @todo, LOWPRIORITY, investigate an efficient way to exclude none custom fields
*/
function wtgcsv_get_customfield_keys_distinct(){
    global $wpdb;
    return $wpdb->get_results("SELECT DISTINCT meta_key FROM $wpdb->postmeta 
                                  WHERE meta_key != '_encloseme' 
                                  AND meta_key != '_wp_page_template'
                                  AND meta_key != '_edit_last'
                                  AND meta_key != '_edit_lock'
                                  AND meta_key != '_wp_trash_meta_time'
                                  AND meta_key != '_wp_trash_meta_status'
                                  AND meta_key != '_wp_old_slug'
                                  AND meta_key != '_pingme'
                                  AND meta_key != '_thumbnail_id'
                                  AND meta_key != '_wp_attachment_image_alt'
                                  AND meta_key != '_wp_attachment_metadata'
                                  AND meta_key != '_wp_attached_file'");    
}

/**
* Uses get_results and finds all DISTINCT meta_keys, returns the result  
*/
function wtgcsv_get_metakeys_distinct(){
    global $wpdb;
    return $wpdb->get_results("SELECT DISTINCT meta_key FROM $wpdb->postmeta 
                                  WHERE meta_key != '_encloseme' 
                                  AND meta_key != '_wp_page_template'
                                  AND meta_key != '_edit_last'
                                  AND meta_key != '_edit_lock'
                                  AND meta_key != '_wp_trash_meta_time'
                                  AND meta_key != '_wp_trash_meta_status'
                                  AND meta_key != '_wp_old_slug'
                                  AND meta_key != '_pingme'
                                  AND meta_key != '_thumbnail_id'
                                  AND meta_key != '_wp_attachment_image_alt'
                                  AND meta_key != '_wp_attachment_metadata'
                                  AND meta_key != '_wp_attached_file'");    
}

/**
* Returns the number of rows imported for a giving database table
* @deprecated, function was a duplicate, prefer the function containing sql in the name
*/
function wtgcsv_count_records( $table_name ){
    return wtgcsv_sql_counttablerecords( $table_name );
} 

/**
* Returns the number of rows imported for a giving database table and also the giving file.
* Required in multi-file jobs
* @todo CRITICAL, add WHERE wtgcsv_filedone 
*/
function wtgcsv_sql_count_records_forfile( $tablename,$csvfile_name,$csvfile_id ){
    global $wpdb;
    $query = "SELECT COUNT(*) FROM ". $tablename . " WHERE wtgcsv_filedone".$csvfile_id." = 1";
    $records = $wpdb->get_var( $query );
    if( $records ){return $records;}else{ return '0';}   
} 
/**
 * counts total records in giving project table
 * @return 0 on fail or no records or the number of records in table
 */
function wtgcsv_sql_counttablerecords( $table_name ){
    global $wpdb;
    $query = "SELECT COUNT(*) FROM ". $table_name . ";";
    $records = $wpdb->get_var( $query );
    if( $records ){return $records;}else{return '0';}    
}

/**
* Returns SQL query result of all option records in Wordpress options table that begin with the giving 
*/
function wtgcsv_get_options_beginning_with($prependvalue){
    
    // set variables
    global $wpdb;
    $optionrecord_array = array();
    
    // first get all records
    $optionrecords = $wpdb->get_results( "SELECT option_name FROM $wpdb->options" );
    
    // loop through each option record and check their name value for wtgcsv_ at the beginning
    foreach( $optionrecords as $optkey => $option ){

        if(strpos( $option->option_name , $prependvalue ) === 0){
            $optionrecord_array[] = $option->option_name;
        }
    } 
    
    return $optionrecord_array;   
}

/**
* SELECT query to check if a rows record has already been prepared for updating too.
* This function is only required for multi-file data import jobs
* 
* @param mixed $table_name
*/
function wtgcsv_sql_row_id_check($table_name,$id){
    global $wpdb;
    $query = "SELECT COUNT(*) FROM ". $table_name . " WHERE wtgcsv_id = ".$id.";";
    $records = $wpdb->get_var( $query );
    if( $records != false && $records != 0 && $records != wtgcsv_is_WP_Error($records) ){return true;}else{return false;}      
}

/**
 * Builds and executes a MySQL UPATE query
 * The WHERE part is simple in this query, it uses a single value, usually record ID to apply the update. 
 */
function wtgcsv_sql_update_record( $row, $csvfile_name, $column_total, $jobcode, $record_id, $header_array){        

    $col = 0;
    
    // wtgcsv_get_csvfile_id() = gets csv file id for updating the wtgcsv_filedone column
    // start SET data part of query
    $set = ' SET wtgcsv_imported = NOW(),wtgcsv_updated = NOW(),wtgcsv_filedone'.wtgcsv_get_csvfile_id($csvfile_name,$jobcode).' = 1';
    
    // start where part of query
    $where = ' WHERE wtgcsv_id = ' . $record_id;
    
    // count how many keys are used
    $keysused = 0;      
                     
    foreach( $header_array as $header_key => $header ){
      
        $set .= ',';

        // apply single quotes if data is wrapped in double quotes already
        $set .= $header['sql_adapted'] ." = '". $row[$col] ."'";
        // TODO:HIGHPRIORITY, apply single or double quotes depending on what quotes (if any) the data value is wrapped in
         
        ++$col;
    }    
    
    // put together parts of query
    $updatequery_complete = 'UPDATE wtgcsv_' . $jobcode . $set . $where;
    global $wpdb;    
    $updatequery_result = $wpdb->query($updatequery_complete);
    return $updatequery_result;
}

/**
* Creates a new record in giving table. Only 
*/
function wtgcsv_query_insert_new_record ( $table_name,$csvfile_modtime ){
    global $wpdb;
    $sql_query = $wpdb->prepare( "INSERT INTO `".$table_name."` (wtgcsv_postid) VALUES (0)" );
    $wpdb->query( $sql_query );
    return $wpdb->insert_id;
}
                     
/**
* Create the table for a data import job, table is named using job code
* 
* @param mixed $jobcode
*/
function wtgcsv_create_dataimportjob_table($jobcode){

    /**
    * wtgcsv_id          - record id within data table, not imported id
    * wtgcsv_postid      - if record used to make post, this is the wordpress post id
    * wtgcsv_postcontent - where required, post content will also be stored
    * wtgcsv_inuse       - boolean, false means the record is not to be used for whatever reason
    * wtgcsv_imported    - date of first import of row from csv file, not the last update
    * wtgcsv_updated     - date a newer row was imported from csv file and updated the record
    * wtgcsv_changed     - latest date that the plugin auto changed or user manually changed values in record
    * wtgcsv_applied     - last date and time the record was applied too its post
    * wtgcsv_filetime    - csv file datestamp when record imported, can then be compared against a newer file to trigger updates
    */
    
    // CREATE TABLE beginning
    $table = "CREATE TABLE `wtgcsv_". $jobcode ."` (
    `wtgcsv_id` int(10) unsigned NOT NULL auto_increment,    
    `wtgcsv_postid` int(10) unsigned default NULL COMMENT '',    
    `wtgcsv_postcontent` int(10) unsigned default NULL COMMENT '',    
    `wtgcsv_inuse` int(10) unsigned default NULL COMMENT '',        
    `wtgcsv_imported` datetime NOT NULL COMMENT '',
    `wtgcsv_updated` datetime NOT NULL COMMENT '',    
    `wtgcsv_changed` datetime NOT NULL COMMENT '',
    `wtgcsv_applied` datetime NOT NULL COMMENT '',";
                                             
    $column_int = 0;
    $fileid = 1;
                
    $job_array = wtgcsv_get_dataimportjob($jobcode);
    
    // loop through jobs files
    foreach($job_array['files'] as $fileid => $csv_filename){
        
        // add file modification time column for each file
        $table .= "
        `wtgcsv_filemoddate".$fileid."` datetime default NULL COMMENT '',
        `wtgcsv_filedone".$fileid."` text default NULL COMMENT '',";
        /**
        * wtgcsv_filemod(ID) - the CSV files last checked modification
        * wtgcsv_filedone(ID) - boolean true or false, indicates if the row has been update using specific file (with ID)
        */
            
        // loop through each files set of headers (3 entries in array per header)
        foreach( $job_array[$csv_filename]['headers'] as $header_key => $header){
            $table .= "
            `" . $header['sql_adapted'] . "` text default NULL COMMENT '',";                                                                                                              
        }
        
        ++$fileid;
    }

    // end of table
    $table .= "PRIMARY KEY  (`wtgcsv_id`)
    ) ENGINE=MyISAM DEFAULT CHARSET=utf8 COMMENT='Table created by Easy CSV Importer';";
    
    global $wpdb;        
    $createresult1 = $wpdb->query( $table );

    if( $createresult1 ){
        // update the job table array - it is simple a record of the tables creating for jobs for tracing then deleting them
        wtgcsv_add_jobtable('wtgcsv_' . $jobcode);  
        return true; 
    }else{
        return false;
    }        
}


/**
 * Returns a cleaned string so that it is suitable to be used as an SQL column name
 * @param string $column (characters removed = , / \ . - # _ and spaces)
 */
function wtgcsv_cleansqlcolumnname( $column ){
    global $wpdb;
    return str_replace( array( ",","/","'\'"," ",".",'-','#','_'),"", strtolower($column) );
}

#################################################################
#                                                               #
#          OLD ECI FUNCTIONS NOT YET EDITED OR USED             # 
#                                                               #
#################################################################
  

/**
 * Select query
 * @param string $table (full table name included prepend value)
 */
function wtgcsv_query_select($table,$select,$where){
    global $wpdb;

    if($wtgcsv_display_errors){$wpdb->show_errors();}
    $sql_query = $wpdb->prepare("SELECT ".$select." FROM `".$table."` WHERE ".$where.";");
    $select_result = $wpdb->get_var($sql_query);
    if($wtgcsv_display_errors){$wpdb->hide_errors();$wpdb->print_error();}

    // make log entry
    $atts = array();
    $atts['projectname'] = 'NA';// Project name (usually csv file name)               
    $atts['date'] = wtgcsv_date();// wtgcsv_date()   
    $atts['line'] = __LINE__;// __LINE__
    $atts['file'] = __FILE__;// __FILE__
    $atts['function'] = __FUNCTION__;// __FUNCTION__
    $atts['logtype'] = 'sql';// general, sql, admin, user, error (can be others but all fit into these categories)
    $atts['dump'] = 'None';// anything, variable, text, url, html, php
    $atts['comment'] = 'None';// comment to help users or developers (recommended 60-80 characters long)
    $atts['sql_result'] = $select_result;// wordpress sql result value
    $atts['sql_query'] = $sql_query;// wordpress sql query value
    $atts['style'] = 'processing';// Notice box style (info,success,warning,error,question,processing,stop)
    $atts['category'] = 'select,sql,query,database';// used to filter entries, a lowercase keyword // TODO: make this comma seperated values but also make the log viewer search through them
    wtgcsv_log($atts);

    // clear cache then return result
    $wpdb->flush();
    return $select_result;
}
        

/**
 * Standard wordpress prepared insert query with sprintf like ability
 * My function requires the type specifier string, not sure if this works the same as coding it at this time
 * 
 * % - a literal percent character. No argument is required.
 * b - the argument is treated as an integer, and presented as a binary number.
 * c - the argument is treated as an integer, and presented as the character with that ASCII value.
 * d - the argument is treated as an integer, and presented as a (signed) decimal number.
 * e - the argument is treated as scientific notation (e.g. 1.2e+2). The precision specifier stands for the number of digits after the decimal point since PHP 5.2.1. In earlier versions, it was taken as number of significant digits (one less).
 * E - like %e but uses uppercase letter (e.g. 1.2E+2).
 * u - the argument is treated as an integer, and presented as an unsigned decimal number.
 * f - the argument is treated as a float, and presented as a floating-point number (locale aware).
 * F - the argument is treated as a float, and presented as a floating-point number (non-locale aware). Available since PHP 4.3.10 and PHP 5.0.3.
 * g - shorter of %e and %f.
 * G - shorter of %E and %f.
 * o - the argument is treated as an integer, and presented as an octal number.
 * s - the argument is treated as and presented as a string.
 * x - the argument is treated as an integer and presented as a hexadecimal number (with lowercase letters).
 * X - the argument is treated as an integer and presented as a hexadecimal number (with uppercase letters).
 * 
 * @param string $table (full database table name, with prepend value already added)
 * @param string $columns (comma seperated string of columns)
 * @param string $sprint_types (comma seperated string of type specifiers)
 * @param array $value_array (array of values, prepare requires this to be an array)
 */
function wtgcsv_query_insert($table,$columns,$sprint_types,$value_array){
    global $wpdb;

    if($wtgcsv_display_errors){$wpdb->show_errors();}
    $sql_query = $wpdb->prepare( "INSERT INTO `".$table."` (".$columns.") VALUES (".$sprint_types.")",$value_array );
    $insert_result = $wpdb->query( $sql_query );
    if($wtgcsv_display_errors){$wpdb->hide_errors();$wpdb->print_error();}

    // clear cache then return result
    $wpdb->flush();
    return $insert_result;
}       

/**
 * Delete query
 * @param string $table (full database table name, with prepend value already added)
 * @param string $argument (argument part of query after WHERE)
 */
function wtgcsv_query_delete($table,$argument){
    global $wpdb;

    if($wtgcsv_display_errors){$wpdb->show_errors();}
    $sql_query = $wpdb->prepare("DELETE FROM ".$table." WHERE ".$argument."" );
    $delete_result = $wpdb->query( $sql_query );
    if($wtgcsv_display_errors){$wpdb->hide_errors();$wpdb->print_error();}

    // make log entry
    $atts = array();
    $atts['projectname'] = 'NA';// Project name (usually csv file name)               
    $atts['date'] = wtgcsv_date();// wtgcsv_date()   
    $atts['line'] = __LINE__;// __LINE__
    $atts['file'] = __FILE__;// __FILE__
    $atts['function'] = __FUNCTION__;// __FUNCTION__
    $atts['logtype'] = 'sql';// general, sql, admin, user, error (can be others but all fit into these categories)
    $atts['dump'] = 'None';// anything, variable, text, url, html, php
    $atts['comment'] = 'None';// comment to help users or developers (recommended 60-80 characters long)
    $atts['sql_result'] = $delete_result;// wordpress sql result value
    $atts['sql_query'] = $sql_query;// wordpress sql query value
    $atts['style'] = 'processing';// Notice box style (info,success,warning,error,question,processing,stop)
    $atts['category'] = 'select,sql,query,database';// used to filter entries, a lowercase keyword // TODO: make this comma seperated values but also make the log viewer search through them
    wtgcsv_log($atts);

    // clear cache then return result
    $wpdb->flush();
    return $delete_result;
}

/**
 * Select count query
 * @param string $table (full table name included prepend value)
 */
function wtgcsv_query_count($table){
    global $wpdb;

    if($wtgcsv_display_errors){$wpdb->show_errors();}
    $sql_query = $wpdb->prepare("SELECT COUNT(*) FROM `".$table."`;");
    $count_result = $wpdb->get_var( $wpdb->prepare("SELECT COUNT(*) FROM `".$table."`;") );
    if($wtgcsv_display_errors){$wpdb->hide_errors();$wpdb->print_error();}

    // make log entry
    $atts = array();
    $atts['projectname'] = 'NA';// Project name (usually csv file name)               
    $atts['date'] = wtgcsv_date();// wtgcsv_date()   
    $atts['line'] = __LINE__;// __LINE__
    $atts['file'] = __FILE__;// __FILE__
    $atts['function'] = __FUNCTION__;// __FUNCTION__
    $atts['logtype'] = 'sql';// general, sql, admin, user, error (can be others but all fit into these categories)
    $atts['dump'] = 'None';// anything, variable, text, url, html, php
    $atts['comment'] = 'None';// comment to help users or developers (recommended 60-80 characters long)
    $atts['sql_result'] = $count_result;// wordpress sql result value
    $atts['sql_query'] = $sql_query;// wordpress sql query value
    $atts['style'] = 'processing';// Notice box style (info,success,warning,error,question,processing,stop)
    $atts['category'] = 'select,sql,query,database';// used to filter entries, a lowercase keyword // TODO: make this comma seperated values but also make the log viewer search through them
    wtgcsv_log($atts);

    // clear cache then return result
    $wpdb->flush();
    return $count_result;
}


/**
 * Returns an array of all the tables in the giving or default database
 * @param string $database
 * @return false if query fails or an array of table names
 */
function wtgcsv_SHOW_TABLES_FROM($database_name = 'thewordpressdefault'){
    global $wpdb;

    // if no attribute value giving for database name we use the default
    if($database_name == 'thewordpressdefault'){$database_name = $wpdb->dbname;}

    // query the database
    if($wtgcsv_display_errors){$wpdb->show_errors();}
    $show_result = mysql_query("SHOW TABLES FROM `".$database_name."`");
    if($wtgcsv_display_errors){$wpdb->hide_errors();$wpdb->print_error();}

    // record query in history
    wtgcsv_log(wtgcsv_log_sql_default());

    // clear cache then return result
    $wpdb->flush();
    return $show_result;
}

/**
 * Returns array of columns for the giving table
 * @param string $table_name
 */
function wtgcsv_SHOW_COLUMNS_FROM($table_name){
    global $wpdb;

    // query the database
    if($wtgcsv_display_errors){$wpdb->show_errors();}
    $show_result = mysql_query("SHOW COLUMNS FROM `".$table_name."`");
    if($wtgcsv_display_errors){$wpdb->hide_errors();$wpdb->print_error();}

    wtgcsv_log(wtgcsv_log_sql_default());

    // clear cache then return result
    $wpdb->flush();
    return $show_result;
}

/**
 * Checks if a database table name exists or not
 * @global array $wpdb
 * @param string $table_name
 * @return boolean, true if table found, else if table does not exist
 */
function wtgcsv_does_table_exist( $table_name ){
    global $wpdb;
    if( $wpdb->query("SHOW TABLES LIKE '".$table_name."'") ){return true;}else{return false;}
}

/**
* Returns all tables from the Wordpress blogs database
* @returns direct result of query SHOW TABLES FROM
*/
function wtgcsv_sql_get_tables(){
    global $wpdb;
    $result = mysql_query("SHOW TABLES FROM `".$wpdb->dbname."`");
    return $result;
}

/**
* Returns an array holding the column names for the giving table
*/
function wtgcsv_sql_get_tablecolumns($t){
    global $wpdb;
    return mysql_query("SHOW COLUMNS FROM `".$t."`");      
}

/**
* Checks if a category already exists with the giving parent.
* 
* @param mixed $cat_encoded
* @param mixed $parent
* @return mixed
*/
function wtgcsv_sql_is_categorywithparent( $cat_encoded,$parent ){
    global $wpdb;
    return $wpdb->get_row("SELECT
    $wpdb->terms.term_id,
    $wpdb->terms.name,
    $wpdb->term_taxonomy.parent
    FROM $wpdb->terms
    JOIN $wpdb->term_taxonomy
    WHERE $wpdb->terms.name = '".$cat_encoded."'
    AND $wpdb->terms.term_id = $wpdb->term_taxonomy.term_id
    AND $wpdb->term_taxonomy.parent = '".$parent."'
    LIMIT 1");
}

/**
* Queries a giving project record and gets records that have not been applied to their post yet.
* 
* @param integer $limit (number of rec)
* @param mixed $tablename
* @return mixed
* 
* @todo CRITICAL,need to pass file modification date to this function, we then 
*/
function wtgcsc_sql_getrecords_requiringupdate($limit,$tablename){
    global $wpdb;
    return $wpdb->get_row("SELECT * 
    FROM " . $csv['sql']['tablename'] . " 
    WHERE ecipostid IS NOT NULL 
    AND eciapplied < eciupdated 
    LIMIT ".$limit);
}

/**
* Builds a query using up to 3 key column values to locate a csv file rows matching
* record. The query can be used to obtain record ID then use that ID to run an UPDATE query 
* 
* @param mixed $record
* @param mixed $filename
* @param mixed $type
*/
function wtgcsv_sql_get_rowsmatchingrecord( $record, $filename, $type )
{
    $csv = get_option('eci_' . $filename);
    $pro = get_option('eci_pro');
    
    $col = 0;
    
    // start where part of query
    $where = ' WHERE ';
    
    // count how many keys are used
    $k = 0;
    $w = 0;
    
    $titlearray = eci_gettitlearray($csv,$pro,$filename);
        
    foreach( $titlearray as $column )
    {        
        // if select is being done as part of an update event, we only want to select giving unique key columns
        // this helps us to get the record matching the unique key by the key only
        if( $type == 'partofupdate' )
        {
            // is this column part of unique key ?
            if( isset( $csv['updating']['key1'] ) && $csv['updating']['key1'] == $column ){$select .= eci_cleansqlcolumnname($column);$key = true;++$k;}
            elseif( isset( $csv['updating']['key2'] ) && $csv['updating']['key2'] == $column ){$select .= eci_cleansqlcolumnname($column);$key = true;++$k;}
            elseif( isset( $csv['updating']['key3'] ) && $csv['updating']['key3'] == $column ){$select .= eci_cleansqlcolumnname($column);$key = true;++$k;}
            else{$key = false;} 
            
            // if column is 2nd,3rd or more then apply comma
            if( $k > 0 )
            {
                $select .= ',';
            }            
        }

        // if part of unique column or $type is whereall add the column and data to where part of query
        if( isset( $key ) && $key == true || $type == 'whereall' )
        {
            if( $w > 0 )
            {
                $where .= ' AND ';
            }
            
            // does user want mysql real escape string used here, true by default
            if(isset($set['selectescapedata']) && $set['selectescapedata'] == 'Yes' || !isset($set['selectescapedata']))
            {
                $where .= eci_cleansqlcolumnname( $column ) ." = '". mysql_real_escape_string( $record[$col] ) ."'";
            }
            else
            {
                $where .= eci_cleansqlcolumnname( $column ) ." = '". mysql_real_escape_string( $record[$col] ) ."'";                
            }
            
            ++$w;
        }
        
        ++$col;
    }    
    
    // if this is a standard select getting specific columns or a where all query (used for finding matching row to record) 
    if( $type == 'standardselect' || $type == 'whereall' )
    {
        $select = 'SELECT * ';
    }
    else
    {
        $select = ' SELECT '.$select;    
    }

    // put together parts of query
    $q = $select . ' FROM '. $csv['sql']['tablename'] . $where;
    
    return $q;    
}

// builds part of sql query that holds data - requires $record value from open csv file and $insertquery_start
function wtgcsv_sql_insert_datapart( $record, $totalcolumns,$filetime,$csv )
{        
    $insertquery = '(NOW(),"'.$filetime.'",';
    
    $columnid = 0;
    
    foreach( $record as $data )
    {            
        // does user want utf8 encoding on import
        if( $csv['importencoding'] == 'UTF8Standard' )
        {
            $data = utf8_encode( $data );
        }
        
        if( $columnid > 0 )
        {
            $insertquery .= ",";
        }

        // does user want mysql real escape string used here, true by default
        if(isset($set['insertescapedata']) && $set['insertescapedata'] == 'Yes' || !isset($set['insertescapedata']))
        {
            $insertquery .= '"' . mysql_real_escape_string( $data ) . '"';
        }
        else
        {
            $insertquery .= '"' . mysql_real_escape_string( $data ) . '"';                
        }
        
        ++$columnid;
    }
    
    $insertquery .= ')';
    
    return $insertquery;
}

/**
* Drops giving database table and logs the event
* 
* @param mixed $tablename
*/
function eci_deletetable( $tablename )
{
    global $wpdb;

    $set = get_option( 'eci_set' );
    
    // get project table name
    $table_name = eci_wptablename( $filename );
    
    // drop existing table and display message
    $query = 'DROP TABLE '. $table_name .'';
    
    // run drop table query
    $result = $wpdb->query( $query );

    // 0 = success anything else is error
    if( $result == true )
    {        
        eci_log( __('Table Deleted'),'The table named '.$table_name.' was deleted',false,$filename,$set,'High',__LINE__,__FILE__,__FUNCTION__ );    
        // reset data import progress (in most cases it will be required)
        eci_reset_dataimportprogress($filename);

        eci_mes('Table '.$table_name.' Deleted','All data and the table itself has been dropped and your projects data import progress reset to reflect the new status. If you need to you may create the table again manually in the same way you deleted it.');
        return true;
    }
    else
    {
        eci_mes('Failed To Delete Table '.$table_name.'',__('The plugin could not delete your project table, please try again then seek support.'));
        return false;
    }    
    
}

/**
 * Builds first part of SQL INSERT query for inserting CSV file row too the project database.
 * This is not an update to an existing record, it is usually the first time import of
 * a row that has never been imported before.
 *  
 * @param sql table name $table
 * @param csv file $filename
 * @param boolean $multi_file (is the $filename part of multifile project)
 * @param boolean $multi_all (must be used with $multi_file, indicate if we want to use only multi related data or individual file)
 */
function wtgcsv_sql_insert_start( $table,$filename,$multi_file = false,$multi_all = false )
{
    $csv = get_option('eci_' . $filename);
    $pro = get_option('eci_pro');
    
    // tablename may not be current $filename project, it could be a parent table of a multifileproject
    $insertquery = "INSERT INTO `" . $table . "` (`eciimported`,`ecifiletime`,";
    
    $columnid = 0;

    // 1: if were not on a multifile project or we are on a multifile but dont want the multifile values i.e. merged titles array
    // 2: if on multiple file
    if(!$multi_file || $multi_file && !$multi_all){
        // pass normal array as were not on a multi project or we dont want this
        // use of eci_sqlinsert_start to return multi project title array
        $titlearray = $csv['format']['titles'];
    }elseif($multi_file && $multi_all){
        // were on a multi project and we want multi data
        // eci_gettitlearray will do this check and return the multi array
        $titlearray = eci_gettitlearray($csv,$pro,$filename);
    }


    foreach( $titlearray as $key=>$column )
    {            
        // we need to prepare column names (no spaces or special characters etc)
        $sqlcolumn = eci_cleansqlcolumnname($column);    
        if( $columnid != 0 ){ $insertquery .= ","; }// apply comma until last column
        
        $insertquery .= "`" . $sqlcolumn . "`";
        
        ++$columnid;
    }
    
    $insertquery .= ') VALUES ';
    
    // save query start to csv profile
    $csv['sql']['insertstart'] = $insertquery;
    $csv['arraychange'] =  eci_arraychange( __LINE__,__FILE__ );
    update_option( 'eci_' . $filename, $csv );
}

/**
 * Selects values from giving column and checks that the column to determine if it holds all numeric values or not
 * @param integer $column
 * @param string $tablename
 * @return true if all numeric or false if strings/none numeric values found in column
 * 
 * @todo ECI FUNCTION NOT CHANGED YET
 */
function wtgcsv_sql_numericcolumncheck( $column,$tablename )
{
    global $wpdb;
    $q1 = "SELECT $column 
    FROM $tablename 
    LIMIT 100";
    
    $result1 = $wpdb->get_results( $q1 );

    if( $result1 )
    {
        // loop through returned records
        $numeric = true;
        foreach( $result1 as $key )
        {
            // if data value is not number but is also not a null or empty then this column wont work
            if( !is_numeric( $key->$column ) && $key->$column != NULL && !is_empty( $key->$column )  )
            {
                $numeric = false;
                break;
            }
        }
        
        if( $numeric )
        {
            return true;
        }
        else 
        {
            return false;
        }        
    }
    else
    {
        return false;
    }
} 

/**
 * Counts number of updated records in project table that have not been applied to their post since update
 * @param array $csv
 * @global $wpdb
 * @uses get_row
 * 
  * @todo ECI FUNCTION NOT CHANGED YET 
 */
function wtgcsv_sql_countupdatedrecords_notapplied($tablename)
{    
    global $wpdb;

    $r = $wpdb->get_var($wpdb->prepare("SELECT COUNT(*) 
    FROM " . $tablename . " 
    WHERE ecipostid IS NOT NULL 
    AND eciinuse NOT LIKE 'No' OR eciinuse IS NULL
    AND eciupdated IS NOT NULL
    AND eciapplied IS NOT NULL
    AND eciapplied < eciupdated;"));
        
    // only continue if a record was found
    if( isset( $r ) && $r != false )
    {
        return $r;
    }
    else
    {
        return 0;
    }    
}   


/**
 * Counts number of updated records in project table that have been applied to their post since update
 * @param array $csv
 * @global $wpdb
 * @uses get_row
 * 
 * @todo ECI FUNCTION NOT CHANGED YET 
 */
function wtgcsv_sql_countupdatedrecords_applied($tablename)
{    
    global $wpdb;

    $r = $wpdb->get_var($wpdb->prepare("SELECT COUNT(*) 
    FROM " . $tablename . " 
    WHERE ecipostid IS NOT NULL 
    AND eciinuse NOT LIKE 'No' OR eciinuse IS NULL
    AND eciupdated IS NOT NULL
    AND eciapplied IS NOT NULL
    AND eciapplied > eciupdated;"));        
        
    // only continue if a record was found
    if( isset( $r ) && $r != false )
    {
        return $r;
    }
    else
    {
        return 0;
    }    
}

/**
 * SELECT Query - get records that have been updated since their original import and HAVE BEEN applied to post since it was updateda
 *  * @todo ECI FUNCTION NOT CHANGED YET
 */
function wtgcsv_sql_getupdatedrecords_applied($tablename,$limit)
{
    global $wpdb;
    $query = "SELECT * 
    FROM " . $tablename . " 
    WHERE ecipostid IS NOT NULL
    AND eciinuse NOT LIKE 'No' OR eciinuse IS NULL
    AND eciupdated IS NOT NULL
    AND eciapplied IS NOT NULL
    AND eciapplied > eciupdated
    LIMIT $limit;";
    $records = $wpdb->get_results($wpdb->prepare($query));
    return $records;
}
?>