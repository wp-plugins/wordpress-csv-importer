=== Plugin Name ===
Contributors: Zara Walsh
Donate link: http://www.wordpresscsvimporter.com
License: GPLv2 or later
License URI: http://www.gnu.org/licenses/gpl-2.0.html
Tags: wordpress data import,wordpress data importer,auto blogging,auto blogger,autoblog,mass create posts,mass post creation,csv file importer,csv file data import,post injection,import spreadsheet,excel file import,excel import,www.wordpresscsvimporter.com,wordpresscsvimporter,wordpresscsv import,wordpress import plugin,wordpress data import tool,wordpress data import plugin,wordpress post creation plugin
Requires at least: 3.3.1
Tested up to: 3.4.1
Stable tag: trunk

Wordpress CSV Importer (Beta)

== Description ==

Wordpress CSV Importer (Beta), data import and post creation plugin new for 2012.

= Versus Other Imports =
Wordpress CSV Importer offers a stand-alone solution for data importing with or without post creation. It is
easily customised to import data to existing database tables and can create posts using any tables in your database.
The flexability of this approach offers endless potential and is usually safer than a direct import to the wp_posts
database table or instant post creation straight from a CSV file.

= Getting Started =
I have actually written a start guide and tips on the plugin main page. Please browse the guide there. The main thing 
to know is that any problems encountered are not always due to a bug. The plugin may require further development 
depending on your needs or your project configuration is not compatible with values in your data. 

Please seek support by creating a ticket here on the [forum](http://wordpress.org/support/plugin/wordpress-csv-importer/)

= Support = 
As I write this the plugin is in Beta so documentation has not begun. The plugin will be supported by a dedicated
website at [www.wordpresscsvimporter.com](http://www.wordpresscsvimporter.com/). The interface offers a "Help" button beside every feature. A small hint 
is giving and users can click another button to open a page on www.wordpresscsvimporter.com specific to the feature
the user needs help for. Some pages will have video tutorials and screenshots. The online support content will be free.

= Paid Services =
I doubt any developer will deny that plugins of this nature generate a high level of support demand. I've created a 
paid edition of the plugin with many unique features and new approaches. The ability to export data, delete any database
table and generally manipulate the blog are available. The paid edition will be supported with Web Services created with
SOAP and generally aimed at developers who will use it more or anyone who has a complex project. I can also be hired
however I urge everyone to try the free edition, the consider buying the full edition and only then considering paying me.
It is my goal to make Wordpress CSV Importer easy and quick enough for anyone to use.

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

== Screenshots ==

1. Start by creating a Data Import Job and import your CSV files data to a new database table created by Wordpress CSV Importer. We have the potential here to import to existing tables and more.
2. Create templates for placing your data into post content. Templates are stored under a custom post type so many templates can be created. This gives us the potential to apply different designs based on specific conditions.
3. You can even create title templates, so you do not need pre-made titles, just suitable columns of data to create a title string from. These templates are stored under a custom post type.
4. Set the type of posts you want to create. Many plugins and themes will require you to create a different type of post, something Wordpress CSV Importer will do easily.
5. This screenshot shows "Basic Custom Field Rules", paid edition has advanced features but most users require the basic form which allows you to create post meta with any data.
6. You can use up to 3 columns of data to create 3 levels of categories.
 
== Changelog ==
= 0.1.2 =
* BETA Edition
	* Disabled error reporting (it shows all other plugins errors)

= 0.1.1 = 
* BETA Edition
    * Reduced the number of files, including the removal of some jQuery UI themes. The plugin size was too large, mainly caused by png images.
    * Complete readme.txt including screen-shots being added to the package

= 0.1.0 =
* BETA Edition
    * Activation errors detected when blog is not using PHP 5.3, error is fixed and now a clear message is displayed