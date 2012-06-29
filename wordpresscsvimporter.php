<?php
/*
Plugin Name: Wordpress CSV Importer
Version: 0.1.0
Plugin URI: http://www.wordpresscsvimporter.com
Description: Wordpress CSV Importer released 2012 by Zara Walsh and Ryan Bayne
Author: Zara Walsh
Author URI: http://www.wordpresscsvimporter.com
*/

/*********************************************************************************************************************************
* BETA PHASE DEVELOPMENT NOTES
* 0. Create blank option values for all options, prevent SQL error and insert them on activation. The only thing currently to be done during activation.
* 1. Change failure messages for returned falses on saving options to indicate no changes made rather than error/fault
* 2. Complete form submission and return to tab bug where it loads at bottom of screen, add this too all pages $wtgcsv_form_action = wtgcsv_link_toadmin($_GET['page'],'#tabs-' . $counttabs); , then add $wtgcsv_form_action too all forms 
* 3. Test forms in not selected state with any menus and change form submission functions to expect forms that have it
* 4. Look into removing traces of none required array nodes, it will prevent over processing* 
* 5. Switch all web service calls off for 5 min when fault detected and display message
* 6. Avoid checking web service status for 60 minutes. It should hardly ever go down and this will reduce traffic a lot.
*******************************************************************************************************************************/

/*********************************************************************************************************************************
* DEVELOPMENT NOTES
* 1. Avoid loading so much and calling so many installation related functions during Wordpress activation, move file includes and arrays inside admin area until public side exists
* 2. Change failure messages for returned falses on saving options to indicate no changes made rather than error/fault
* 3. Add ability to stop dialogue from being displayed (where dialogue is not REQUIRED), this allows us to not print the script
* 4. Create a better form ID value with short variable and apply too object ID to ensure unique
* 5. Improve function comment - add a line explaining how best to use function - add a line too the functions own blog post (will need to install syntax plugin)
* 6. Add options to hide help buttons
* 7. Complete form submission and return too tab bug where it loads at bottom of screen, add this too all pages $wtgcsv_form_action = wtgcsv_link_toadmin($_GET['page'],'#tabs-' . $counttabs); , then add $wtgcsv_form_action too all forms 
* 8. Add submission values to notices and apply a standard format
* 9. Web Services must record traffic and prevent spamming etc
* 10. Display data import job name, table name and column name above table-column menus
* 11. Write up a page detailing any security steps i.e. use of is_super_admin()
*******************************************************************************************************************************/

if ( !function_exists( 'add_option' ) ) {echo "www.WordpressCSVImporter.com by Zara Walsh";exit;}

// testing/development variables (will be removed on completion)
$UNDERCONSTRUCTIONS_SWITCH = 0;// 0=off  1=on  2=$_POST only
$wtgcsv_js_switch = true;
$wtgcsv_display_errors = 0;
$wtgcsv_debugmode_strict = 0;
$wtgcsv_dumpsoapcalls = 0;
$wtgcsv_dumppostget = 0;
$wtgcsv_nav_type = 'jquery';// css,jquery,nonav
if(is_admin() && $UNDERCONSTRUCTIONS_SWITCH != 0 && (!defined('DOING_AJAX') || !DOING_AJAX)){
    if($UNDERCONSTRUCTIONS_SWITCH == 1){     
        $wtgcsv_js_switch = true;// switch scripts on and off includes javascript,ajax,jQuery (overiding all other switchings)
        $wtgcsv_display_errors = 1;// true or 1 to display ALL Wordpress errors for all themes and plugins, false or 0 to hide them all which is Wordpress default
        $wtgcsv_debugmode_strict = 1;// true or 1(shows very strict error reporting)  , false or 0   
        $wtgcsv_dumpsoapcalls = 0;// dumps the results of all soap calls
        $wtgcsv_dumppostget = 1;// dump $_POST and $_GET
        $wtgcsv_nav_type = 'jquery';// css,jquery,nonav 
    }elseif($UNDERCONSTRUCTIONS_SWITCH == 2){
        $wtgcsv_dumppostget = 1;// dump $_POST and $_GET
    }   
}
unset($underconstruction);

// development variable values
$wtgcsv_currentversion = '0.0.1';
$wtgcsv_php_version_tested = '5.3.1';// current version the plugin is being developed on
$wtgcsv_php_version_minimum = '5.3.0';// minimum version required for plugin to operate
// plugin build
$wtgcsv_pluginname = 'wordpresscsvimporter';// should not be used to make up paths
$wtgcsv_homeslug = $wtgcsv_pluginname;// @todo page slug for plugin main page used in building menus
$wtgcsv_isbeingactivated = false;
$wtgcsv_notice_array = array(); 
$wtgcsv_disableapicalls = 0;// 1 = yes, disable all api calls (handy if errors are being created), 0 allows api calls
$wtgcsv_is_free = true;// changing this in free copy does not activate a paid edition, it may break the plugin
$wtgcsv_is_dev = false;// boolean, true displays more panels with even more data i.e. array dumps
      
##########################################################################################
#                                                                                        #
#                            LOAD CORE VARIABLES AND FILES                               #
#                                                                                        #
##########################################################################################                 
if(!defined("WTG_CSV_ABB")){define("WTG_CSV_ABB","wtgcsv_");}
if(!defined("WTG_CSV_URL")){define("WTG_CSV_URL", plugin_dir_url(__FILE__) );}//http://localhost/wordpress-testing/wtgplugintemplate/wp-content/plugins/wtgplugintemplate/
if(!defined("WTG_CSV_DIR")){define("WTG_CSV_DIR", plugin_dir_path(__FILE__) );}//C:\AppServ\www\wordpress-testing\wtgplugintemplate\wp-content\plugins\wtgplugintemplate/

require_once(WTG_CSV_DIR.'templatesystem/wtgcsv_load_templatesystem_constants.php'); 
if(is_admin()){require_once(WTG_CSV_DIR.'templatesystem/include/variables/wtgcsv_variables_adminconfig.php');}
require_once(WTG_CSV_DIR.'templatesystem/wtgcsv_load_admin_arrays_templatesystem.php');
require_once(WTG_CSV_DIR.'templatesystem/include/wtgcsv_core_functions.php');// must be loaded before initialplugin_configuration.php
require_once(WTG_CSV_DIR.'templatesystem/wtgcsv_load_initialplugin_configuration.php');// must be loaded after core_functions.php
require_once(WTG_CSV_DIR.'templatesystem/include/webservices/wtgcsv_api_parent.php');
if(!$wtgcsv_is_free){require_once(WTG_CSV_DIR.'templatesystem/include/wtgcsv_advanced_functions.php');}

// admin end and public load different                  
if(is_admin()){ 
    // register plugin activation hook - must be in the main file
    register_activation_hook( __FILE__ ,'wtgcsv_register_activation_hook');

    // init custom post types and custom box
    // content template
    add_action( 'init', 'wtgcsv_register_customposttype_contentdesigns' );
    add_action( 'add_meta_boxes', 'wtgcsv_add_custom_boxes_contenttemplate' );
    add_action( 'save_post', 'wtgcsv_save_postdata_contenttemplate' );
    // title template
    add_action( 'init', 'wtgcsv_register_customposttype_titledesigns' );
    add_action( 'add_meta_boxes', 'wtgcsv_add_custom_boxes_titletemplate' );
    add_action( 'save_post', 'wtgcsv_save_postdata_titletemplate' );
    
    // initialise core admin only variables ### TODO:MEDIUMPRIORITY, remove variables from here that are always set later even if set to false       
    $wtgcsv_installation_required = true;
    $wtgcsv_apiservicestatus = 'unknown';
    $wtgcsv_is_webserviceavailable = false;                                                       
    $wtgcsv_is_subscribed = false;
    $wtgcsv_is_installed = false;        
    $wtgcsv_was_installed = false;
    $wtgcsv_is_domainregistered = false;
    $wtgcsv_is_emailauthorised = false;
    $wtgcsv_log_maindir = 'unknown';
    $wtgcsv_callcode = '000000000000';
    $wtgcsv_twitter = 'WPCSVImporter';
    $wtgcsv_feedburner = 'wordpresscsvimporter';
    $wtgcsv_currentproject = 'No Project Set'; 

    #################################################################################################
    #                                                                                               #
    #                                      LOAD FUNCTION FILES                                      #
    #                                                                                               #
    #################################################################################################   
    // load core global templatesystem files 
    require_once(WTG_CSV_DIR.'templatesystem/include/wtgcsv_file_functions.php');// file management related functions
    require_once(WTG_CSV_DIR.'templatesystem/include/wtgcsv_post_functions.php');// post creation,update related functions       
    // load core admin templatesystem files
    require_once(WTG_CSV_DIR.'templatesystem/wtgcsv_load_admin_includes_templatesystem.php');        
    require_once(WTG_CSV_DIR.'pages/wtgcsv_variables_tabmenu_array.php');    

    ################################################################
    ####                                                        ####
    ####   VARIABLES REQUIRING PLUGIN FUNCTIONS OR SETTINGS     ####
    ####                                                        ####
    ################################################################
    //$wtgcsv_activationcode = wtgcsv_get_activationcode(); ### TODO:MEDIUMPRIORITY, part of activation code system 
    $wtgcsv_is_installed = wtgcsv_is_installed();// boolean - if false either plugin has never been installed or installation has been tampered with 
    if(!$wtgcsv_is_free){$wtgcsv_is_webserviceavailable = wtgcsv_is_webserviceavailable();}else{$wtgcsv_is_webserviceavailable == false;}
                  
    // if web services are available, we can then check if domain is registered or not
    if(!$wtgcsv_is_free && $wtgcsv_is_webserviceavailable){
        
        # TODO: CRITICAL, change call code design so that it does not expire but may be changed from time to time
        # TODO: CRITICAL, once call code does not expire, avoid using wtgcsv_is_domainregistered() every page call, leave a local value indicating domain is registered 
        
        $wtgcsv_is_domainregistered = wtgcsv_is_domainregistered();// returns boolean AND stores call code 
        
        // if domain is within membership then we can continue doing further api calls 
        if($wtgcsv_is_domainregistered){
            
            // continue other api calls
            $wtgcsv_callcode = get_option('wtgcsv_callcode');                              
            $wtgcsv_is_callcodevalid = wtgcsv_is_callcodevalid();
            //$wtgcsv_is_subscribed = wtgcsv_is_subscribed();// returns boolean       
        }
    }
    
    // get data import jobs related variables
    $wtgcsv_currentjob_code = wtgcsv_get_option_currentjobcode();
    $wtgcsv_job_array = wtgcsv_get_dataimportjob($wtgcsv_currentjob_code);
    $wtgcsv_jobtable_array = wtgcsv_get_option_jobtable_array(); 
    $wtgcsv_dataimportjobs_array = wtgcsv_get_option_dataimportjobs_array();

    // get post creation project related variables
    $wtgcsv_currentproject_code = wtgcsv_get_current_project_code();
    $wtgcsv_project_array = wtgcsv_get_project_array($wtgcsv_currentproject_code);
    $wtgcsv_projectslist_array = wtgcsv_get_projectslist();
    
    // get all other admin variables    
    $wtgcsv_was_installed = wtgcsv_was_installed();// boolean - indicates if a trace of previous installation found       
    $wtgcsv_schedule_array = wtgcsv_get_option_schedule_array();
    $wtgcsv_panels_closed = true;// boolean true forces all panels closed, false opens them all
        
    ###########################################################################
    ####                                                                   ####
    ####                       LOAD JAVASCRIPT FILES                       ####
    ####                                                                   ####
    ###########################################################################
    // add action for main script loading only if on plugins own pages
    // loop through all page slugs - only if not currently being activated
    ### TODO: HIGHPRIORITY, if continue to get script problems in firefox, try putting these conditions inside the add_action
    if(!isset($wtgcsv_isbeingactivated) || isset($wtgcsv_isbeingactivated) && $wtgcsv_isbeingactivated != true){
        $looped = 0;
        // loop through pages in page array
        foreach($wtgcsv_mpt_arr as $key=>$pagearray){
            // if admin url contains the page value and we have a slug (should do) - are the equal
            // this prevents the plugins scripts loading unless we are on the plugins own pages
            if (isset($_GET['page']) && isset($pagearray['slug']) && $_GET['page'] == $pagearray['slug']){
                add_action( 'wp_print_scripts', 'wtgcsv_print_admin_scripts' );
            }
            ++$looped;
        }
    }        
    
    add_action('admin_menu','wtgcsv_admin_menu');// main navigation 
    add_action('init','wtgcsv_export_singlesqltable_as_csvfile');// export CSV file request by $_POST
        
    wtgcsv_script('admin');
    wtgcsv_css('admin');        
}

// load public only scripts,styles,files (can use this to avoid loading files only required on the admin side)
if(!is_admin()){
    // load core files
    //require_once(WTG_CSV_DIR.'templatesystem/include/wtgcsv_core_functions.php');
    //wtgcsv_script('public');
    //wtgcsv_css('public');
}
?>