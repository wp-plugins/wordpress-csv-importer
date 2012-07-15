=== Plugin Name ===
Contributors: Zara Walsh
Donate link: http://www.wordpresscsvimporter.com
License: GPLv2 or later
License URI: http://www.gnu.org/licenses/gpl-2.0.html
Tags: Wordpress CSV Importer, Wordpress CSV Importer Plugin, Wordpress CSV Importer Software, WordpressCSVImporter, wordpress data import,wordpress data importer,auto blogging,auto blogger,autoblog,mass create posts,mass post creation,csv file importer,csv file data import,post injection,import spreadsheet,excel file import,excel import,www.wordpresscsvimporter.com,wordpresscsvimporter,wordpresscsv import,wordpress import plugin,wordpress data import tool,wordpress data import plugin,wordpress post creation plugin
Requires at least: 3.3.1
Tested up to: 3.4.1
Stable tag: trunk

Wordpress CSV Importer

== Description ==

Wordpress CSV Importer, data import and post creation plugin new for 2012.

= Versus Other Imports =
Wordpress CSV Importer offers a stand-alone solution for data importing with or without post creation. It is
easily customised to import data to existing database tables and can create posts using any tables in your database.
The flexability of this approach offers endless potential and is usually safer than a direct import to the wp_posts
database table or instant post creation straight from a CSV file.

= Getting Started =
I have  written a start guide and tips on the plugin main page. Please browse the guide there. The main thing 
to know is that any problems encountered are not always due to a bug. The plugin may require further development 
depending on your needs or your project configuration is not compatible with values in your data.

Please seek support by creating a ticket here on the [forum](http://wordpress.org/support/plugin/wordpress-csv-importer/)

= Support = 
The plugin is supported by a dedicated
website at [www.wordpresscsvimporter.com](http://www.wordpresscsvimporter.com/). The interface offers a "Help" button beside every feature. A small hint 
is giving and users can click another button to open a page on www.wordpresscsvimporter.com specific to the feature
the user needs help for. Some pages will have video tutorials and screenshots. The online support content is free.

= Paid Services =
A very advanced paid edition and services are available if needed. However I urge everyone to try the free edition first.
For a donation I can add requests for new features to the top of the to do list. If there is something you would like the
plugin to do but want to avoid paying for the full edition, there are always options.

= Free v Paid =
It is very important to me that I deliver a useful free plugin for the Wordpress community to use. I must also support
the plugin and take responsibility for it. Priority development and focus in the free edition goes to the actual data 
import side of the plugin. It must do what it says in its name and I would like to deliver a tool for uncommon projects
i.e. importing product data to shopping cart plugin tables and manipulating data as it is being imported based on various
conditions. I have a very long term plan for Wordpress CSV Importer, both free and paid. If you budget is tight, keep
checking the free edition and there is no harm in letting me know what you think the free plugin should offer users.

== Installation ==

Initial upload and activation as guided by Wordpress.org standard plugin installation.

1. Upload the `wordpress-csv-importer` folder to the `/wp-content/plugins/` directory
1. Activate the Wordpress CSV Importer plugin through the 'Plugins' menu in WordPress
1. Configure the plugin by going to the Settings screen
1. Always backup your database before mass creating posts unless working on a test blog
1. It is recommended you learn the plugin on a test blog first
1. Always seek support if a CSV file does not appear to work, it is usually something minor

== Frequently Asked Questions ==

= When was Wordpress CSV Importer Beta released? =
This plugins development begun at the end of 2011 and the Beta was released June 2012.

= Is Wordpress CSV Importer well supported? =
Yes, the plugin will be supported for many years and has a dedicated website.

= Will you make changes to the plugin to suit my needs? =
We note every request on a "To Do" list and we intend to keep working through the list
every week. If your needs are more urgent we can be hired. It is mainly Ryan Bayne who 
handles the more intense requests when clients have deadlines, something we take very 
serious. 

= Will development continue for this plugin? =
Our To Do list for Wordpress CSV Importer is already extensive. Ideas, requests and
requirements for new web technology will keep us busy until 2014. This is why the plugins
interface has so many screens and offers the ability to hide features not wanted. 

= Why are the features on so many different plugin screens? =
Long term plans for the development of Wordpress CSV Importer will add an endless list
of new features. Not only do all the form fields and buttons need to be tidy but
every feature and ability has to be offered without assumption. Meaning users
must be able to configure them or opt out of using them or use defaults. Users have that
choice and flexability which is not offered in all plugins. The entire plugin offers a 
sandbox approach, especially for those who know a little PHP and can customise it even
further. The sandbox approach assumes nothing and is expressed on an interface that does
not push users through a linear step by step process.

== Screenshots ==

1. Start by creating a Data Import Job and import your CSV files data to a new database table created by Wordpress CSV Importer. We have the potential here to import to existing tables and more.
2. Create templates for placing your data into post content. Templates are stored under a custom post type so many templates can be created. This gives us the potential to apply different designs based on specific conditions.
3. You can even create title templates, so you do not need pre-made titles, just suitable columns of data to create a title string from. These templates are stored under a custom post type.
4. Set the type of posts you want to create. Many plugins and themes will require you to create a different type of post, something Wordpress CSV Importer will do easily.
5. This screenshot shows "Basic Custom Field Rules", paid edition has advanced features but most users require the basic form which allows you to create post meta with any data.
6. You can use up to 3 columns of data to create 3 levels of categories.
 
== Changelog ==
= 0.2.0 =
* Bug Fixes
    * A data import update query was fixed
* General Improvements
    * Database names are no longer kept in the list of created tables after being deleted
    * Users must delete Data Import Job before deleting a jobs database table
* Web Services, Support and Websites changes
    * None
* Public Messages
    * None
    
= 0.1.9 =
* Bug Fixes
    * Missing file warning for a file no longer included in package
* Interface Improvements
    * Message regarding missing files now includes the expected path
* Web Services, Support and Websites changes
    * Plugin no longer checks web service status, not required until web services are complete
* Public Messages
    * Happy Bastille Day, just make it a weekend
    
= 0.1.8 =
* Bug Fixes
    * Error related to wtgcsv_sql_query_unusedrecords_singletable() missing parameter
* Interface Improvements
    * None
* Web Services, Support and Websites changes
    * New YouTube video, a lot more to come: http://www.youtube.com/watch?v=uGA8R0PVR8M
* Public Messages
    * Thanks to Eric from Quebec for days of good feedback
    
= 0.1.7 = 
* Added support to read CSV files using 2 different methods: PEAR CSV and fget/fgetcsv (each method often suits different files or purposes)
* Can select files separator on the Test CSV File panel
* CSV file test now uses PEAR CSV and php fget function to count columns
* CSV file test compares user submitted separator (if any) with PEAR CSV and fget method separators
    
= 0.1.6 = 
* Bug fix in reading CSV file columns for none comma files
    
= 0.1.5 = 
* Further improvements made for manually applying separator and quote
    
= 0.1.4 = 
* BETA Edition
    * Interfaces for paid edition were being hidden in the paid edition, but should only be hidden in free edition
    * jQuery UI tabs now hold their state when submitting forms
    * jquery.cookie.js added to bundle
    * Done some work on how the plugin handles separators and establishing the correct one, plus warning users of any issues

= 0.1.3 = 
* BETA Edition
    * Plugin no longer uses wp_die when PHP 5.2 in use, a notification is displayed instead
    * Improved the use of deactivate_plugins( 'wordpress-csv-importer' ) when PHP 5.2 detected (plugin requires 5.3)

= 0.1.2 = 
* BETA Edition
    
= 0.1.1 = 
* BETA Edition
    * Reduced the number of files, including the removal of some jQuery UI themes. The plugin size was too large, mainly caused by png images.
    * Complete readme.txt including screen-shots being added to the package

= 0.1.0 =
* BETA Edition
    * Activation errors detected when blog is not using PHP 5.3, error is fixed and now a clear message is displayed