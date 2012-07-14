<?php
####################################################################
####                                                            ####
####           TABS NAVIGATION ARRAY ($wtgcsv_mpt_arr)          ####
####                                                            ####
####################################################################

/**
* Returns value for displaying or hiding a page based on edition (free or full).
* These is no point bypassing this. The pages hidden require PHP that is only provided with
* the full edition. You may be able to use the forms, but the data saved won't do anything or might
* cause problems.
* 
* @param mixed $package_allowed, 0=free 1=full/paid 2=dont ever display
*/
function wtgcsv_page_show_hide($package_allowed = 0){
    global $wtgcsv_is_free;
    
    if($package_allowed == 2){
        return false;// do not display in any package
    }elseif($wtgcsv_is_free && $package_allowed == 0){
        return true;     
    }elseif($wtgcsv_is_free && $package_allowed == 1){
        return false;// paid edition page not be displayed in free edition
    }
    return true;
}

global $wtgcsv_homeslug;
$wtgcsv_mpt_arr = array();
// main page
$wtgcsv_mpt_arr['main']['active'] = true;// boolean -is this page in use
$wtgcsv_mpt_arr['main']['slug'] = $wtgcsv_homeslug;// home page slug set in main file
$wtgcsv_mpt_arr['main']['menu'] = WTG_CSV_PLUGINTITLE;// plugin dashboard page title
$wtgcsv_mpt_arr['main']['name'] = "mainpage";// name of page (slug) and unique
$wtgcsv_mpt_arr['main']['role'] = 'activate_plugins';// minimum required role in order to VIEW the page
$wtgcsv_mpt_arr['main']['title'] = 'Wordpress CSV Importer';// page title seen once page is opened
$wtgcsv_mpt_arr['main']['pagehelp'] = 'http://www.wordpresscsvimporter.com';// url to the help content on plugin site for this page
$wtgcsv_mpt_arr['main']['headers'] = false;// boolean - display a content area above selected tabs i.e. introductions or status
$wtgcsv_mpt_arr['main']['vertical'] = false;// boolean - is the menu vertical rather than horizontal
$wtgcsv_mpt_arr['main']['statusicons'] = false;// boolean - instead of related icons we use cross & tick etc indicating completion or not
// main sub page 1 tab 1
$wtgcsv_mpt_arr['main']['tabs'][0]['active'] = true;
$wtgcsv_mpt_arr['main']['tabs'][0]['slug'] = 'tab0_main';
$wtgcsv_mpt_arr['main']['tabs'][0]['label'] = 'Screens';
$wtgcsv_mpt_arr['main']['tabs'][0]['name'] = 'screens';     
$wtgcsv_mpt_arr['main']['tabs'][0]['helpurl'] = 'http://www.wordpresscsvimporter.com/'; 
$wtgcsv_mpt_arr['main']['tabs'][0]['allowhide'] = false;// is tab screen allowed to be hidden (boolean)
$wtgcsv_mpt_arr['main']['tabs'][0]['display'] = wtgcsv_page_show_hide(); 
// main sub page 1 tab 2
$wtgcsv_mpt_arr['main']['tabs'][1]['active'] = true;
$wtgcsv_mpt_arr['main']['tabs'][1]['slug'] = 'tab1_main';
$wtgcsv_mpt_arr['main']['tabs'][1]['label'] = 'Updates';
$wtgcsv_mpt_arr['main']['tabs'][1]['name'] = 'updates'; 
$wtgcsv_mpt_arr['main']['tabs'][1]['helpurl'] = 'http://www.wordpresscsvimporter.com/'; 
$wtgcsv_mpt_arr['main']['tabs'][1]['allowhide'] = false;
$wtgcsv_mpt_arr['main']['tabs'][1]['display'] = wtgcsv_page_show_hide();
// main sub page 1 tab 3
$wtgcsv_mpt_arr['main']['tabs'][2]['active'] = true;
$wtgcsv_mpt_arr['main']['tabs'][2]['slug'] = 'tab2_main';
$wtgcsv_mpt_arr['main']['tabs'][2]['label'] = 'Quick Start';
$wtgcsv_mpt_arr['main']['tabs'][2]['name'] = 'quickstart'; 
$wtgcsv_mpt_arr['main']['tabs'][2]['helpurl'] = 'http://www.wordpresscsvimporter.com/'; 
$wtgcsv_mpt_arr['main']['tabs'][2]['allowhide'] = false;
$wtgcsv_mpt_arr['main']['tabs'][2]['display'] = wtgcsv_page_show_hide();
// main sub page 1 tab 4
$wtgcsv_mpt_arr['main']['tabs'][3]['active'] = true;
$wtgcsv_mpt_arr['main']['tabs'][3]['slug'] = 'tab3_main';
$wtgcsv_mpt_arr['main']['tabs'][3]['label'] = 'About';
$wtgcsv_mpt_arr['main']['tabs'][3]['name'] = 'about'; 
$wtgcsv_mpt_arr['main']['tabs'][3]['helpurl'] = 'http://www.wordpresscsvimporter.com/'; 
$wtgcsv_mpt_arr['main']['tabs'][3]['allowhide'] = false;
$wtgcsv_mpt_arr['main']['tabs'][3]['display'] = wtgcsv_page_show_hide();

// settings page
$wtgcsv_mpt_arr['settings']['active'] = true;
$wtgcsv_mpt_arr['settings']['slug'] = WTG_CSV_ABB . "settings";
$wtgcsv_mpt_arr['settings']['menu'] = "Plugin Settings";
$wtgcsv_mpt_arr['settings']['role'] = 'activate_plugins';
$wtgcsv_mpt_arr['settings']['title'] = WTG_CSV_PLUGINTITLE.' Settings';
$wtgcsv_mpt_arr['settings']['name'] = 'settings'; 
$wtgcsv_mpt_arr['settings']['icon'] = 'options-general';
$wtgcsv_mpt_arr['settings']['pagehelp'] = 'http://www.wordpresscsvimporter.com/';
$wtgcsv_mpt_arr['settings']['headers'] = false;
$wtgcsv_mpt_arr['settings']['vertical'] = false;
$wtgcsv_mpt_arr['settings']['statusicons'] = false;    
// settings sub page 1 tab 1
$wtgcsv_mpt_arr['settings']['tabs'][0]['active'] = true;
$wtgcsv_mpt_arr['settings']['tabs'][0]['slug'] = 'tab0_settings';
$wtgcsv_mpt_arr['settings']['tabs'][0]['label'] = 'General Settings';
$wtgcsv_mpt_arr['settings']['tabs'][0]['name'] = 'generalsettings';
$wtgcsv_mpt_arr['settings']['tabs'][0]['helpurl'] = 'http://www.wordpresscsvimporter.com/';
$wtgcsv_mpt_arr['settings']['tabs'][0]['allowhide'] = false;
$wtgcsv_mpt_arr['settings']['tabs'][0]['display'] = wtgcsv_page_show_hide();
// settings sub page 1 tab 2
$wtgcsv_mpt_arr['settings']['tabs'][1]['active'] = true;
$wtgcsv_mpt_arr['settings']['tabs'][1]['slug'] = 'tab1_settings';
$wtgcsv_mpt_arr['settings']['tabs'][1]['label'] = 'Interface Settings';
$wtgcsv_mpt_arr['settings']['tabs'][1]['name'] = 'interfacesettings';
$wtgcsv_mpt_arr['settings']['tabs'][1]['helpurl'] = 'http://www.wordpresscsvimporter.com/';
$wtgcsv_mpt_arr['settings']['tabs'][1]['allowhide'] = false;
$wtgcsv_mpt_arr['settings']['tabs'][1]['display'] = wtgcsv_page_show_hide(); 
// settings sub page 1 tab 3
$wtgcsv_mpt_arr['settings']['tabs'][2]['active'] = true;
$wtgcsv_mpt_arr['settings']['tabs'][2]['slug'] = 'tab2_settings';
$wtgcsv_mpt_arr['settings']['tabs'][2]['label'] = 'Easy Configuration Questions';
$wtgcsv_mpt_arr['settings']['tabs'][2]['name'] = 'easyconfigurationquestions';
$wtgcsv_mpt_arr['settings']['tabs'][2]['helpurl'] = 'http://www.wordpresscsvimporter.com/';
$wtgcsv_mpt_arr['settings']['tabs'][2]['allowhide'] = true; 
$wtgcsv_mpt_arr['settings']['tabs'][2]['display'] = wtgcsv_page_show_hide();

// install page
$wtgcsv_mpt_arr['install']['active'] = true;
$wtgcsv_mpt_arr['install']['slug'] = WTG_CSV_ABB . "install";
$wtgcsv_mpt_arr['install']['menu'] = WTG_CSV_PLUGINTITLE." Install";
$wtgcsv_mpt_arr['install']['role'] = 'activate_plugins';
$wtgcsv_mpt_arr['install']['title'] = WTG_CSV_PLUGINTITLE.' Install';
$wtgcsv_mpt_arr['install']['name'] = 'install';
$wtgcsv_mpt_arr['install']['icon'] = 'install';
$wtgcsv_mpt_arr['install']['pagehelp'] = 'http://www.wordpresscsvimporter.com/';
$wtgcsv_mpt_arr['install']['headers'] = false;
$wtgcsv_mpt_arr['install']['vertical'] = false;
$wtgcsv_mpt_arr['install']['statusicons'] = false;  
// install sub page 1 tab 1
$wtgcsv_mpt_arr['install']['tabs'][0]['active'] = true;
$wtgcsv_mpt_arr['install']['tabs'][0]['slug'] = 'tab0_install';
$wtgcsv_mpt_arr['install']['tabs'][0]['label'] = 'Install Actions';
$wtgcsv_mpt_arr['install']['tabs'][0]['name'] = 'installactions';
$wtgcsv_mpt_arr['install']['tabs'][0]['helpurl'] = 'http://www.wordpresscsvimporter.com/';
$wtgcsv_mpt_arr['install']['tabs'][0]['allowhide'] = false;// is tab screen allowed to be hidden (boolean)
$wtgcsv_mpt_arr['install']['tabs'][0]['display'] = wtgcsv_page_show_hide(); 
// install sub page 1 tab 2
$wtgcsv_mpt_arr['install']['tabs'][1]['active'] = true;
$wtgcsv_mpt_arr['install']['tabs'][1]['slug'] = 'tab1_install';
$wtgcsv_mpt_arr['install']['tabs'][1]['label'] = 'Install History';
$wtgcsv_mpt_arr['install']['tabs'][1]['name'] = 'installhistory';
$wtgcsv_mpt_arr['install']['tabs'][1]['helpurl'] = 'http://www.wordpresscsvimporter.com/';
$wtgcsv_mpt_arr['install']['tabs'][1]['allowhide'] = false;// is tab screen allowed to be hidden (boolean)
$wtgcsv_mpt_arr['install']['tabs'][1]['display'] = wtgcsv_page_show_hide();  
// install sub page 1 tab 3
$wtgcsv_mpt_arr['install']['tabs'][2]['active'] = true;
$wtgcsv_mpt_arr['install']['tabs'][2]['slug'] = 'tab2_install';
$wtgcsv_mpt_arr['install']['tabs'][2]['label'] = 'Install Status';
$wtgcsv_mpt_arr['install']['tabs'][2]['name'] = 'installstatus';
$wtgcsv_mpt_arr['install']['tabs'][2]['helpurl'] = 'http://www.wordpresscsvimporter.com/';
$wtgcsv_mpt_arr['install']['tabs'][2]['allowhide'] = true;// is tab screen allowed to be hidden (boolean)
$wtgcsv_mpt_arr['install']['tabs'][2]['display'] = wtgcsv_page_show_hide();   
// install sub page 1 tab 4
$wtgcsv_mpt_arr['install']['tabs'][3]['active'] = true;
$wtgcsv_mpt_arr['install']['tabs'][3]['slug'] = 'tab3_install';
$wtgcsv_mpt_arr['install']['tabs'][3]['label'] = 'Your Server Status';
$wtgcsv_mpt_arr['install']['tabs'][3]['name'] = 'yourserverstatus';
$wtgcsv_mpt_arr['install']['tabs'][3]['helpurl'] = 'http://www.wordpresscsvimporter.com/';
$wtgcsv_mpt_arr['install']['tabs'][3]['allowhide'] = true;// is tab screen allowed to be hidden (boolean)
$wtgcsv_mpt_arr['install']['tabs'][3]['display'] = wtgcsv_page_show_hide();   
// install sub page 1 tab 5
$wtgcsv_mpt_arr['install']['tabs'][4]['active'] = true;
$wtgcsv_mpt_arr['install']['tabs'][4]['slug'] = 'tab4_install';
$wtgcsv_mpt_arr['install']['tabs'][4]['label'] = 'Activation Controls';
$wtgcsv_mpt_arr['install']['tabs'][4]['name'] = 'activationcontrols';
$wtgcsv_mpt_arr['install']['tabs'][4]['helpurl'] = 'http://www.wordpresscsvimporter.com/';
$wtgcsv_mpt_arr['install']['tabs'][4]['allowhide'] = false  ;// is tab screen allowed to be hidden (boolean) 
$wtgcsv_mpt_arr['install']['tabs'][4]['display'] = wtgcsv_page_show_hide();
// install sub page 1 tab 6
$wtgcsv_mpt_arr['install']['tabs'][5]['active'] = true;
$wtgcsv_mpt_arr['install']['tabs'][5]['slug'] = 'tab5_install';
$wtgcsv_mpt_arr['install']['tabs'][5]['label'] = 'Files Status';
$wtgcsv_mpt_arr['install']['tabs'][5]['name'] = 'filesstatus';
$wtgcsv_mpt_arr['install']['tabs'][5]['helpurl'] = 'http://www.wordpresscsvimporter.com/';
$wtgcsv_mpt_arr['install']['tabs'][5]['allowhide'] = false  ;// is tab screen allowed to be hidden (boolean) 
$wtgcsv_mpt_arr['install']['tabs'][5]['display'] = wtgcsv_page_show_hide(1); 
  
// more page - includes a sub-menu for offering far more pages without adding to plugn menu
$wtgcsv_mpt_arr['more']['active'] = true;
$wtgcsv_mpt_arr['more']['slug'] = "wtgcsv_more";
$wtgcsv_mpt_arr['more']['menu'] = "More";
$wtgcsv_mpt_arr['more']['role'] = 'activate_plugins';
$wtgcsv_mpt_arr['more']['title'] = 'More';
$wtgcsv_mpt_arr['more']['name'] = 'more'; 
$wtgcsv_mpt_arr['more']['icon'] = 'install';
$wtgcsv_mpt_arr['more']['pagehelp'] = 'http://www.wordpresscsvimporter.com/';
$wtgcsv_mpt_arr['more']['headers'] = false;
$wtgcsv_mpt_arr['more']['vertical'] = false;
$wtgcsv_mpt_arr['more']['statusicons'] = false;      
// more sub page 1 tab 1
$wtgcsv_mpt_arr['more']['tabs'][0]['active'] = true;
$wtgcsv_mpt_arr['more']['tabs'][0]['slug'] = 'tab0_more';
$wtgcsv_mpt_arr['more']['tabs'][0]['label'] = 'Support';
$wtgcsv_mpt_arr['more']['tabs'][0]['name'] = 'support';
$wtgcsv_mpt_arr['more']['tabs'][0]['helpurl'] = 'http://www.wordpresscsvimporter.com/';
$wtgcsv_mpt_arr['more']['tabs'][0]['allowhide'] = false;// is tab screen allowed to be hidden (boolean)
$wtgcsv_mpt_arr['more']['tabs'][0]['display'] = wtgcsv_page_show_hide();   
// more sub page 1 tab 2
$wtgcsv_mpt_arr['more']['tabs'][1]['active'] = true;
$wtgcsv_mpt_arr['more']['tabs'][1]['slug'] = 'tab1_more';
$wtgcsv_mpt_arr['more']['tabs'][1]['label'] = 'Community';
$wtgcsv_mpt_arr['more']['tabs'][1]['name'] = 'community';
$wtgcsv_mpt_arr['more']['tabs'][1]['helpurl'] = 'http://www.wordpresscsvimporter.com/';
$wtgcsv_mpt_arr['more']['tabs'][1]['allowhide'] = false;// is tab screen allowed to be hidden (boolean)
$wtgcsv_mpt_arr['more']['tabs'][1]['display'] = wtgcsv_page_show_hide();  
// more sub page 1 tab 3
$wtgcsv_mpt_arr['more']['tabs'][2]['active'] = true;
$wtgcsv_mpt_arr['more']['tabs'][2]['slug'] = 'tab2_more';
$wtgcsv_mpt_arr['more']['tabs'][2]['label'] = 'Downloads';
$wtgcsv_mpt_arr['more']['tabs'][2]['name'] = 'downloads';
$wtgcsv_mpt_arr['more']['tabs'][2]['helpurl'] = 'http://www.wordpresscsvimporter.com/';
$wtgcsv_mpt_arr['more']['tabs'][2]['allowhide'] = true;// is tab screen allowed to be hidden (boolean)
$wtgcsv_mpt_arr['more']['tabs'][2]['display'] = wtgcsv_page_show_hide();  
// more sub page 1 tab 4
$wtgcsv_mpt_arr['more']['tabs'][3]['active'] = true;
$wtgcsv_mpt_arr['more']['tabs'][3]['slug'] = 'tab3_more';
$wtgcsv_mpt_arr['more']['tabs'][3]['label'] = 'Affiliates';// Affiliate, payment history, traffic stats, display banners
$wtgcsv_mpt_arr['more']['tabs'][3]['name'] = 'affiliates';
$wtgcsv_mpt_arr['more']['tabs'][3]['helpurl'] = 'http://www.wordpresscsvimporter.com/';
$wtgcsv_mpt_arr['more']['tabs'][3]['allowhide'] = true;// is tab screen allowed to be hidden (boolean)
$wtgcsv_mpt_arr['more']['tabs'][3]['display'] = wtgcsv_page_show_hide(); 
// more sub page 1 tab 5
$wtgcsv_mpt_arr['more']['tabs'][4]['active'] = true;
$wtgcsv_mpt_arr['more']['tabs'][4]['slug'] = 'tab4_more';
$wtgcsv_mpt_arr['more']['tabs'][4]['label'] = 'Development';// RSS feed link, blog entries directly, coming soon (top feature coming next)
$wtgcsv_mpt_arr['more']['tabs'][4]['name'] = 'development';
$wtgcsv_mpt_arr['more']['tabs'][4]['helpurl'] = 'http://www.wordpresscsvimporter.com/';
$wtgcsv_mpt_arr['more']['tabs'][4]['allowhide'] = true;// is tab screen allowed to be hidden (boolean)
$wtgcsv_mpt_arr['more']['tabs'][4]['display'] = wtgcsv_page_show_hide();
// more sub page 1 tab 6
$wtgcsv_mpt_arr['more']['tabs'][5]['active'] = true;
$wtgcsv_mpt_arr['more']['tabs'][5]['slug'] = 'tab5_more';
$wtgcsv_mpt_arr['more']['tabs'][5]['label'] = 'Testing';// test blogs, beta tester list, test forum discussion, RSS for testers and developers, short TO DO list (not whole list)
$wtgcsv_mpt_arr['more']['tabs'][5]['name'] = 'testing';
$wtgcsv_mpt_arr['more']['tabs'][5]['helpurl'] = 'http://www.wordpresscsvimporter.com/';
$wtgcsv_mpt_arr['more']['tabs'][5]['allowhide'] = true;// is tab screen allowed to be hidden (boolean)
$wtgcsv_mpt_arr['more']['tabs'][5]['display'] = wtgcsv_page_show_hide(); 
// more sub page 1 tab 7
$wtgcsv_mpt_arr['more']['tabs'][6]['active'] = true;
$wtgcsv_mpt_arr['more']['tabs'][6]['slug'] = 'tab6_more';
$wtgcsv_mpt_arr['more']['tabs'][6]['label'] = 'Offers';// display a range of main offers, hosting packages with premium plugin purchase, free installs, setup etc
$wtgcsv_mpt_arr['more']['tabs'][6]['name'] = 'offers';
$wtgcsv_mpt_arr['more']['tabs'][6]['helpurl'] = 'http://www.wordpresscsvimporter.com/';
$wtgcsv_mpt_arr['more']['tabs'][6]['allowhide'] = false;// is tab screen allowed to be hidden (boolean)
$wtgcsv_mpt_arr['more']['tabs'][6]['display'] = wtgcsv_page_show_hide(); 
// more sub page 1 tab 8
$wtgcsv_mpt_arr['more']['tabs'][7]['active'] = true;
$wtgcsv_mpt_arr['more']['tabs'][7]['slug'] = 'tab7_more';
$wtgcsv_mpt_arr['more']['tabs'][7]['label'] = 'My Tickets';// users submitted tickets, if API can access
$wtgcsv_mpt_arr['more']['tabs'][7]['name'] = 'mytickets';
$wtgcsv_mpt_arr['more']['tabs'][7]['helpurl'] = 'http://www.wordpresscsvimporter.com/';
$wtgcsv_mpt_arr['more']['tabs'][7]['allowhide'] = false;// is tab screen allowed to be hidden (boolean)
$wtgcsv_mpt_arr['more']['tabs'][7]['display'] = wtgcsv_page_show_hide(); 
// more sub page 1 tab 9
$wtgcsv_mpt_arr['more']['tabs'][8]['active'] = true;
$wtgcsv_mpt_arr['more']['tabs'][8]['slug'] = 'tab8_more';
$wtgcsv_mpt_arr['more']['tabs'][8]['label'] = 'My Account';// purchased plugins, users account, transactions, loyalty points, stored API key, special permissions and access indicators etc
$wtgcsv_mpt_arr['more']['tabs'][8]['name'] = 'myaccount';
$wtgcsv_mpt_arr['more']['tabs'][8]['helpurl'] = 'http://www.wordpresscsvimporter.com/';
$wtgcsv_mpt_arr['more']['tabs'][8]['allowhide'] = true;// is tab screen allowed to be hidden (boolean)
$wtgcsv_mpt_arr['more']['tabs'][8]['display'] = wtgcsv_page_show_hide(); 
// more sub page 1 tab 10
$wtgcsv_mpt_arr['more']['tabs'][9]['active'] = true;
$wtgcsv_mpt_arr['more']['tabs'][9]['slug'] = 'tab9_more';
$wtgcsv_mpt_arr['more']['tabs'][9]['label'] = 'Contact';// advanced contact form, creates ticket, forum post and sends email
$wtgcsv_mpt_arr['more']['tabs'][9]['name'] = 'contact';
$wtgcsv_mpt_arr['more']['tabs'][9]['helpurl'] = 'http://www.wordpresscsvimporter.com/';
$wtgcsv_mpt_arr['more']['tabs'][9]['allowhide'] = false;// is tab screen allowed to be hidden (boolean) 
$wtgcsv_mpt_arr['more']['tabs'][9]['display'] = wtgcsv_page_show_hide(1);
/**************** Varied Sub Pages Begin Here *****************/

######################################################
#                                                    #
#                         DATA                       #
#                                                    #
######################################################
$wtgcsv_mpt_arr['data']['active'] = true;
$wtgcsv_mpt_arr['data']['slug'] =  "wtgcsv_yourdata";
$wtgcsv_mpt_arr['data']['menu'] = "1. Your Data";
$wtgcsv_mpt_arr['data']['name'] = "yourdata";
$wtgcsv_mpt_arr['data']['role'] = 'administrator';
$wtgcsv_mpt_arr['data']['title'] = 'Current Job: ';
$wtgcsv_mpt_arr['data']['icon'] = 'options-general';
$wtgcsv_mpt_arr['data']['pagehelp'] = 'http://www.wordpresscsvimporter.com/';
$wtgcsv_mpt_arr['data']['headers'] = false;
$wtgcsv_mpt_arr['data']['vertical'] = false;
$wtgcsv_mpt_arr['data']['statusicons'] = true;     
// 1. Data sub page 1 tab 1
$wtgcsv_mpt_arr['data']['tabs'][0]['active'] = true;
$wtgcsv_mpt_arr['data']['tabs'][0]['slug'] = 'tab0_pagedata';
$wtgcsv_mpt_arr['data']['tabs'][0]['label'] = 'Start';
$wtgcsv_mpt_arr['data']['tabs'][0]['name'] = 'start';
$wtgcsv_mpt_arr['data']['tabs'][0]['helpurl'] = 'http://www.wordpresscsvimporter.com/';
$wtgcsv_mpt_arr['data']['tabs'][0]['allowhide'] = false;// is tab screen allowed to be hidden (boolean)
$wtgcsv_mpt_arr['data']['tabs'][0]['display'] = wtgcsv_page_show_hide(); 
// 1. Data sub page 1 tab 2
$wtgcsv_mpt_arr['data']['tabs'][1]['active'] = true;
$wtgcsv_mpt_arr['data']['tabs'][1]['slug'] = 'tab1_pagedata';
$wtgcsv_mpt_arr['data']['tabs'][1]['label'] = 'Import Jobs';
$wtgcsv_mpt_arr['data']['tabs'][1]['name'] = 'dataimport';
$wtgcsv_mpt_arr['data']['tabs'][1]['helpurl'] = 'http://www.wordpresscsvimporter.com/';
$wtgcsv_mpt_arr['data']['tabs'][1]['allowhide'] = true;
$wtgcsv_mpt_arr['data']['tabs'][1]['display'] = wtgcsv_page_show_hide(); 
// 1. Data sub page 1 tab 3
$wtgcsv_mpt_arr['data']['tabs'][2]['active'] = true;
$wtgcsv_mpt_arr['data']['tabs'][2]['slug'] = 'tab2_pagedata';
$wtgcsv_mpt_arr['data']['tabs'][2]['label'] = 'Export Tools';
$wtgcsv_mpt_arr['data']['tabs'][2]['name'] = 'dataexport';
$wtgcsv_mpt_arr['data']['tabs'][2]['helpurl'] = 'http://www.wordpresscsvimporter.com/';
$wtgcsv_mpt_arr['data']['tabs'][2]['allowhide'] = true;
$wtgcsv_mpt_arr['data']['tabs'][2]['display'] = wtgcsv_page_show_hide();
// 1. Data sub page 1 tab 4
$wtgcsv_mpt_arr['data']['tabs'][3]['active'] = true;
$wtgcsv_mpt_arr['data']['tabs'][3]['slug'] = 'tab3_pagedata';
$wtgcsv_mpt_arr['data']['tabs'][3]['label'] = 'Created Tables';
$wtgcsv_mpt_arr['data']['tabs'][3]['name'] = 'createdtables';
$wtgcsv_mpt_arr['data']['tabs'][3]['helpurl'] = 'http://www.wordpresscsvimporter.com/';
$wtgcsv_mpt_arr['data']['tabs'][3]['allowhide'] = true;
$wtgcsv_mpt_arr['data']['tabs'][3]['display'] = wtgcsv_page_show_hide();
// 1. Data sub page 1 tab 5
$wtgcsv_mpt_arr['data']['tabs'][4]['active'] = true;
$wtgcsv_mpt_arr['data']['tabs'][4]['slug'] = 'tab4_pagedata';
$wtgcsv_mpt_arr['data']['tabs'][4]['label'] = 'Data Rules';
$wtgcsv_mpt_arr['data']['tabs'][4]['name'] = 'datarules';
$wtgcsv_mpt_arr['data']['tabs'][4]['helpurl'] = 'http://www.wordpresscsvimporter.com/';
$wtgcsv_mpt_arr['data']['tabs'][4]['allowhide'] = true;
$wtgcsv_mpt_arr['data']['tabs'][4]['display'] = wtgcsv_page_show_hide(2);
// 1. Data sub page 1 tab 6
$wtgcsv_mpt_arr['data']['tabs'][5]['active'] = true;
$wtgcsv_mpt_arr['data']['tabs'][5]['slug'] = 'tab5_pagedata';
$wtgcsv_mpt_arr['data']['tabs'][5]['label'] = 'History';
$wtgcsv_mpt_arr['data']['tabs'][5]['name'] = 'datahistory';
$wtgcsv_mpt_arr['data']['tabs'][5]['helpurl'] = 'http://www.wordpresscsvimporter.com/';
$wtgcsv_mpt_arr['data']['tabs'][5]['allowhide'] = true;
$wtgcsv_mpt_arr['data']['tabs'][5]['display'] = wtgcsv_page_show_hide(2);
// 1. Data sub page 1 tab 7
$wtgcsv_mpt_arr['data']['tabs'][6]['active'] = true;
$wtgcsv_mpt_arr['data']['tabs'][6]['slug'] = 'tab6_pagedata';
$wtgcsv_mpt_arr['data']['tabs'][6]['label'] = 'Data Sources';
$wtgcsv_mpt_arr['data']['tabs'][6]['name'] = 'datasources';
$wtgcsv_mpt_arr['data']['tabs'][6]['helpurl'] = 'http://www.wordpresscsvimporter.com/';
$wtgcsv_mpt_arr['data']['tabs'][6]['allowhide'] = true;
$wtgcsv_mpt_arr['data']['tabs'][6]['display'] = wtgcsv_page_show_hide(2);
// 1. Data sub page 1 tab 8 TODO: LOWPRIORITY, search tools for the data management side of things
$wtgcsv_mpt_arr['data']['tabs'][7]['active'] = false;
$wtgcsv_mpt_arr['data']['tabs'][7]['slug'] = 'tab7_pagedata';
$wtgcsv_mpt_arr['data']['tabs'][7]['label'] = 'Search';
$wtgcsv_mpt_arr['data']['tabs'][7]['name'] = 'datasearch';
$wtgcsv_mpt_arr['data']['tabs'][7]['helpurl'] = 'http://www.wordpresscsvimporter.com/';
$wtgcsv_mpt_arr['data']['tabs'][7]['allowhide'] = true;
$wtgcsv_mpt_arr['data']['tabs'][7]['display'] = wtgcsv_page_show_hide(2);
### TODO:LOWPRIORITY, add a page that tests all CSV files and lists their status and profile 
### if however it effects loading, could do it with the files in use or latest files uploaded
### full edition only 

######################################################
#                                                    #
#                      YOUR PROJECTS                 #
#                                                    #
######################################################
$wtgcsv_mpt_arr['projects']['active'] = true;
$wtgcsv_mpt_arr['projects']['slug'] =  "wtgcsv_yourprojects";
$wtgcsv_mpt_arr['projects']['menu'] = "2. Your Projects";
$wtgcsv_mpt_arr['projects']['name'] = "yourprojects";
$wtgcsv_mpt_arr['projects']['role'] = 'administrator';
$wtgcsv_mpt_arr['projects']['title'] = 'Current Project: ';
$wtgcsv_mpt_arr['projects']['icon'] = 'options-general';
$wtgcsv_mpt_arr['projects']['pagehelp'] = 'http://www.wordpresscsvimporter.com/';
$wtgcsv_mpt_arr['projects']['headers'] = false;
$wtgcsv_mpt_arr['projects']['vertical'] = false;
$wtgcsv_mpt_arr['projects']['statusicons'] = true;     
// 2. Project sub page 1 tab 1
$wtgcsv_mpt_arr['projects']['tabs'][0]['active'] = true;
$wtgcsv_mpt_arr['projects']['tabs'][0]['slug'] = 'tab0_pageprojects';
$wtgcsv_mpt_arr['projects']['tabs'][0]['label'] = 'Projects';
$wtgcsv_mpt_arr['projects']['tabs'][0]['name'] = 'projects';
$wtgcsv_mpt_arr['projects']['tabs'][0]['helpurl'] = 'http://www.wordpresscsvimporter.com/';
$wtgcsv_mpt_arr['projects']['tabs'][0]['allowhide'] = false;
$wtgcsv_mpt_arr['projects']['tabs'][0]['display'] = wtgcsv_page_show_hide(); 
// 2. Project sub page 1 tab 2
$wtgcsv_mpt_arr['projects']['tabs'][1]['active'] = true;
$wtgcsv_mpt_arr['projects']['tabs'][1]['slug'] = 'tab1_pageprojects';
$wtgcsv_mpt_arr['projects']['tabs'][1]['label'] = 'Content';
$wtgcsv_mpt_arr['projects']['tabs'][1]['name'] = 'content';
$wtgcsv_mpt_arr['projects']['tabs'][1]['helpurl'] = 'http://www.wordpresscsvimporter.com/';
$wtgcsv_mpt_arr['projects']['tabs'][1]['allowhide'] = true;
$wtgcsv_mpt_arr['projects']['tabs'][1]['display'] = wtgcsv_page_show_hide(); 
// 2. Project sub page 1 tab 3
$wtgcsv_mpt_arr['projects']['tabs'][2]['active'] = true;
$wtgcsv_mpt_arr['projects']['tabs'][2]['slug'] = 'tab2_pageprojects';
$wtgcsv_mpt_arr['projects']['tabs'][2]['label'] = 'Titles';
$wtgcsv_mpt_arr['projects']['tabs'][2]['name'] = 'titles';
$wtgcsv_mpt_arr['projects']['tabs'][2]['helpurl'] = 'http://www.wordpresscsvimporter.com/';
$wtgcsv_mpt_arr['projects']['tabs'][2]['allowhide'] = true;
$wtgcsv_mpt_arr['projects']['tabs'][2]['display'] = wtgcsv_page_show_hide();
// 2. Project sub page 1 tab 4
$wtgcsv_mpt_arr['projects']['tabs'][3]['active'] = true;
$wtgcsv_mpt_arr['projects']['tabs'][3]['slug'] = 'tab3_pageprojects';
$wtgcsv_mpt_arr['projects']['tabs'][3]['label'] = 'SEO';
$wtgcsv_mpt_arr['projects']['tabs'][3]['name'] = 'seo';
$wtgcsv_mpt_arr['projects']['tabs'][3]['helpurl'] = 'http://www.wordpresscsvimporter.com/';
$wtgcsv_mpt_arr['projects']['tabs'][3]['allowhide'] = true;
$wtgcsv_mpt_arr['projects']['tabs'][3]['display'] = wtgcsv_page_show_hide(1);
// 2. Project sub page 1 tab 5
$wtgcsv_mpt_arr['projects']['tabs'][4]['active'] = true;
$wtgcsv_mpt_arr['projects']['tabs'][4]['slug'] = 'tab4_pageprojects';
$wtgcsv_mpt_arr['projects']['tabs'][4]['label'] = 'Post Types';
$wtgcsv_mpt_arr['projects']['tabs'][4]['name'] = 'posttypes';
$wtgcsv_mpt_arr['projects']['tabs'][4]['helpurl'] = 'http://www.wordpresscsvimporter.com/';
$wtgcsv_mpt_arr['projects']['tabs'][4]['allowhide'] = true;
$wtgcsv_mpt_arr['projects']['tabs'][4]['display'] = wtgcsv_page_show_hide();
// 2. Project sub page 1 tab 6
$wtgcsv_mpt_arr['projects']['tabs'][5]['active'] = true;
$wtgcsv_mpt_arr['projects']['tabs'][5]['slug'] = 'tab5_pageprojects';
$wtgcsv_mpt_arr['projects']['tabs'][5]['label'] = 'Post Dates';
$wtgcsv_mpt_arr['projects']['tabs'][5]['name'] = 'postdates';
$wtgcsv_mpt_arr['projects']['tabs'][5]['helpurl'] = 'http://www.wordpresscsvimporter.com/';
$wtgcsv_mpt_arr['projects']['tabs'][5]['allowhide'] = true;// is tab screen allowed to be hidden (boolean)
$wtgcsv_mpt_arr['projects']['tabs'][5]['display'] = wtgcsv_page_show_hide();
// 2. Project sub page 1 tab 7
$wtgcsv_mpt_arr['projects']['tabs'][6]['active'] = true;
$wtgcsv_mpt_arr['projects']['tabs'][6]['slug'] = 'tab6_pageprojects';
$wtgcsv_mpt_arr['projects']['tabs'][6]['label'] = 'Custom Fields';
$wtgcsv_mpt_arr['projects']['tabs'][6]['name'] = 'customfields';
$wtgcsv_mpt_arr['projects']['tabs'][6]['helpurl'] = 'http://www.wordpresscsvimporter.com/';
$wtgcsv_mpt_arr['projects']['tabs'][6]['allowhide'] = true;// is tab screen allowed to be hidden (boolean)
$wtgcsv_mpt_arr['projects']['tabs'][6]['display'] = wtgcsv_page_show_hide();
// 2. Project sub page 1 tab 8
$wtgcsv_mpt_arr['projects']['tabs'][7]['active'] = true;
$wtgcsv_mpt_arr['projects']['tabs'][7]['slug'] = 'tab7_pageprojects';
$wtgcsv_mpt_arr['projects']['tabs'][7]['label'] = 'Categories';
$wtgcsv_mpt_arr['projects']['tabs'][7]['name'] = 'categories';
$wtgcsv_mpt_arr['projects']['tabs'][7]['helpurl'] = 'http://www.wordpresscsvimporter.com/';
$wtgcsv_mpt_arr['projects']['tabs'][7]['allowhide'] = true;// is tab screen allowed to be hidden (boolean)
$wtgcsv_mpt_arr['projects']['tabs'][7]['display'] = wtgcsv_page_show_hide(); 
// 2. Project sub page 1 tab 9
$wtgcsv_mpt_arr['projects']['tabs'][8]['active'] = true;
$wtgcsv_mpt_arr['projects']['tabs'][8]['slug'] = 'tab8_pageprojects';
$wtgcsv_mpt_arr['projects']['tabs'][8]['label'] = 'Update Options';
$wtgcsv_mpt_arr['projects']['tabs'][8]['name'] = 'updateoptions';
$wtgcsv_mpt_arr['projects']['tabs'][8]['helpurl'] = 'http://www.wordpresscsvimporter.com/';
$wtgcsv_mpt_arr['projects']['tabs'][8]['allowhide'] = true;// is tab screen allowed to be hidden (boolean)
$wtgcsv_mpt_arr['projects']['tabs'][8]['display'] = wtgcsv_page_show_hide(1);
// 2. Project sub page 1 tab 10
$wtgcsv_mpt_arr['projects']['tabs'][9]['active'] = true;
$wtgcsv_mpt_arr['projects']['tabs'][9]['slug'] = 'tab9_pageprojects';
$wtgcsv_mpt_arr['projects']['tabs'][9]['label'] = 'Images';
$wtgcsv_mpt_arr['projects']['tabs'][9]['name'] = 'images';
$wtgcsv_mpt_arr['projects']['tabs'][9]['helpurl'] = 'http://www.wordpresscsvimporter.com/';
$wtgcsv_mpt_arr['projects']['tabs'][9]['allowhide'] = true;// is tab screen allowed to be hidden (boolean)
$wtgcsv_mpt_arr['projects']['tabs'][9]['display'] = wtgcsv_page_show_hide(2);
// 2. Project sub page 1 tab 10
$wtgcsv_mpt_arr['projects']['tabs'][10]['active'] = true;
$wtgcsv_mpt_arr['projects']['tabs'][10]['slug'] = 'tab10_pageprojects';
$wtgcsv_mpt_arr['projects']['tabs'][10]['label'] = 'URL Cloaking';
$wtgcsv_mpt_arr['projects']['tabs'][10]['name'] = 'urlcloaking';
$wtgcsv_mpt_arr['projects']['tabs'][10]['helpurl'] = 'http://www.wordpresscsvimporter.com/';
$wtgcsv_mpt_arr['projects']['tabs'][10]['allowhide'] = true;// is tab screen allowed to be hidden (boolean)
$wtgcsv_mpt_arr['projects']['tabs'][10]['display'] = wtgcsv_page_show_hide(2);
// 2. Project sub page 1 tab 11
$wtgcsv_mpt_arr['projects']['tabs'][11]['active'] = true;
$wtgcsv_mpt_arr['projects']['tabs'][11]['slug'] = 'tab11_pageprojects';
$wtgcsv_mpt_arr['projects']['tabs'][11]['label'] = 'Tags';
$wtgcsv_mpt_arr['projects']['tabs'][11]['name'] = 'tags';
$wtgcsv_mpt_arr['projects']['tabs'][11]['helpurl'] = 'http://www.wordpresscsvimporter.com/';
$wtgcsv_mpt_arr['projects']['tabs'][11]['allowhide'] = true;// is tab screen allowed to be hidden (boolean)
$wtgcsv_mpt_arr['projects']['tabs'][11]['display'] = wtgcsv_page_show_hide();
// 2. Project sub page 1 tab 12
$wtgcsv_mpt_arr['projects']['tabs'][12]['active'] = true;
$wtgcsv_mpt_arr['projects']['tabs'][12]['slug'] = 'tab12_pageprojects';
$wtgcsv_mpt_arr['projects']['tabs'][12]['label'] = 'Text Spinning';
$wtgcsv_mpt_arr['projects']['tabs'][12]['name'] = 'textspinning';
$wtgcsv_mpt_arr['projects']['tabs'][12]['helpurl'] = 'http://www.wordpresscsvimporter.com/';
$wtgcsv_mpt_arr['projects']['tabs'][12]['allowhide'] = true;// is tab screen allowed to be hidden (boolean)
$wtgcsv_mpt_arr['projects']['tabs'][12]['display'] = wtgcsv_page_show_hide(2);
// 2. Project sub page 1 tab 13
$wtgcsv_mpt_arr['projects']['tabs'][13]['active'] = true;
$wtgcsv_mpt_arr['projects']['tabs'][13]['slug'] = 'tab13_pageprojects';
$wtgcsv_mpt_arr['projects']['tabs'][13]['label'] = 'Project Data';
$wtgcsv_mpt_arr['projects']['tabs'][13]['name'] = 'projectdata';
$wtgcsv_mpt_arr['projects']['tabs'][13]['helpurl'] = 'http://www.wordpresscsvimporter.com/';
$wtgcsv_mpt_arr['projects']['tabs'][13]['allowhide'] = true;// is tab screen allowed to be hidden (boolean)
$wtgcsv_mpt_arr['projects']['tabs'][13]['display'] = wtgcsv_page_show_hide();


######################################################
#                                                    #
#                    CREATION                        #
#                                                    #
######################################################
$wtgcsv_mpt_arr['creation']['active'] = true;
$wtgcsv_mpt_arr['creation']['slug'] =  WTG_CSV_ABB . "yourcreation";
$wtgcsv_mpt_arr['creation']['menu'] = "3. Your Creation";
$wtgcsv_mpt_arr['creation']['name'] = "yourcreation";
$wtgcsv_mpt_arr['creation']['role'] = 'administrator';
$wtgcsv_mpt_arr['creation']['title'] = 'Your Creation';
$wtgcsv_mpt_arr['creation']['icon'] = 'options-general';
$wtgcsv_mpt_arr['creation']['pagehelp'] = 'http://www.wordpresscsvimporter.com/';
$wtgcsv_mpt_arr['creation']['headers'] = false;
$wtgcsv_mpt_arr['creation']['vertical'] = false;
$wtgcsv_mpt_arr['creation']['statusicons'] = true; 
// 3. Results sub page 1 tab 1
$wtgcsv_mpt_arr['creation']['tabs'][0]['active'] = true;
$wtgcsv_mpt_arr['creation']['tabs'][0]['slug'] = 'tab0_pagecreation';
$wtgcsv_mpt_arr['creation']['tabs'][0]['label'] = 'Create Posts';
$wtgcsv_mpt_arr['creation']['tabs'][0]['name'] = 'selectables';
$wtgcsv_mpt_arr['creation']['tabs'][0]['helpurl'] = 'http://www.wordpresscsvimporter.com/';
$wtgcsv_mpt_arr['creation']['tabs'][0]['allowhide'] = true;// is tab screen allowed to be hidden (boolean)
$wtgcsv_mpt_arr['creation']['tabs'][0]['display'] = wtgcsv_page_show_hide();    
// 3. Results sub page 1 tab 2
$wtgcsv_mpt_arr['creation']['tabs'][1]['active'] = true;
$wtgcsv_mpt_arr['creation']['tabs'][1]['slug'] = 'tab1_pagecreation';
$wtgcsv_mpt_arr['creation']['tabs'][1]['label'] = 'Schedule';
$wtgcsv_mpt_arr['creation']['tabs'][1]['name'] = 'schedule';
$wtgcsv_mpt_arr['creation']['tabs'][1]['helpurl'] = 'http://www.wordpresscsvimporter.com/';
$wtgcsv_mpt_arr['creation']['tabs'][1]['allowhide'] = false;// is tab screen allowed to be hidden (boolean)
$wtgcsv_mpt_arr['creation']['tabs'][1]['display'] = wtgcsv_page_show_hide(1); 
// 3. Results sub page 1 tab 3
$wtgcsv_mpt_arr['creation']['tabs'][2]['active'] = true;
$wtgcsv_mpt_arr['creation']['tabs'][2]['slug'] = 'tab2_pagecreation';
$wtgcsv_mpt_arr['creation']['tabs'][2]['label'] = 'Update Posts';
$wtgcsv_mpt_arr['creation']['tabs'][2]['name'] = 'updateposts';
$wtgcsv_mpt_arr['creation']['tabs'][2]['helpurl'] = 'http://www.wordpresscsvimporter.com/';
$wtgcsv_mpt_arr['creation']['tabs'][2]['allowhide'] = true;// is tab screen allowed to be hidden (boolean)
$wtgcsv_mpt_arr['creation']['tabs'][2]['display'] = wtgcsv_page_show_hide(1);
// 3. Results sub page 1 tab 4
$wtgcsv_mpt_arr['creation']['tabs'][3]['active'] = true;
$wtgcsv_mpt_arr['creation']['tabs'][3]['slug'] = 'tab3_pagecreation';
$wtgcsv_mpt_arr['creation']['tabs'][3]['label'] = 'Delete Posts';
$wtgcsv_mpt_arr['creation']['tabs'][3]['name'] = 'Delete Posts';
$wtgcsv_mpt_arr['creation']['tabs'][3]['helpurl'] = 'http://www.wordpresscsvimporter.com/';
$wtgcsv_mpt_arr['creation']['tabs'][3]['allowhide'] = true;// is tab screen allowed to be hidden (boolean)
$wtgcsv_mpt_arr['creation']['tabs'][3]['display'] = wtgcsv_page_show_hide(2);
// 3. Results sub page 1 tab 5
$wtgcsv_mpt_arr['creation']['tabs'][4]['active'] = true;
$wtgcsv_mpt_arr['creation']['tabs'][4]['slug'] = 'tab4_pagecreation';
$wtgcsv_mpt_arr['creation']['tabs'][4]['label'] = 'View Posts';
$wtgcsv_mpt_arr['creation']['tabs'][4]['name'] = 'viewposts';
$wtgcsv_mpt_arr['creation']['tabs'][4]['helpurl'] = 'http://www.wordpresscsvimporter.com/';
$wtgcsv_mpt_arr['creation']['tabs'][4]['allowhide'] = true;// is tab screen allowed to be hidden (boolean)
$wtgcsv_mpt_arr['creation']['tabs'][4]['display'] = wtgcsv_page_show_hide(2);    
?>
