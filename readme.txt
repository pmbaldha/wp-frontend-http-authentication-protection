=== Frontend HTTP Auth Protection  ===
Contributors: pmbaldha
Tags: frontend,http,auth,protection,security
Requires at least: 3.3.0
Tested up to: 4.4.2
Stable tag: trunk
License: Private

Frontend HTTP Authentication Protection makes private front part of your website. When web developer like to give demo of website to end client before release of final website, this plugin provides security to your site from search engine and other visitors. 

== Description ==
Frontend HTTP Authentication Protection provides simple HTTP authentication layer to your  fronend site. There is often need to prevent normal visitors and search engine from navigating front website, when website is in development environment before release website in production environment. This plugin serves this need very well by adding HTTP authentication layer to frontend site. This Wordpress Plugin uses HTTP Basic Authentication. This wordpress plugin is fully configurable from admin panel.  This Plugin will not give role based access or multiple usename and password setting facility.== Installation ==
1. Upload the entire `frontend-http-auth-protection` folder to the `/wp-content/plugins/` directory.
2. Activate the plugin through the \'Plugins\' menu in admin panel.
3. Go to setting > Frontend HTTP Authentication for configuring setting and test functionality given by plugin.

== Frequently Asked Questions ==
= Is there any configuration require for this plugin? =
Yes, You have to set username and password and other basic configurations.

= What is default HTTP auth username and password when plugin will be activated ? =
Default username is user and default password is user123

= Can I set empty username and password when plugin will be activated ? =
Yes, You can set blank value in empty and password.

== Screenshots ==
1. Admin side configuration setting
2. Frontend login prompt dialogue
3. Frontend login prompt cancel error display

== Changelog ==
0.1
Initial release

== Upgrade Notice ==

= 0.1 =
No need to upgrade, you only need to install plugin. This is first version of plugin

== Arbitrary section ==

Features:
1.Enable Disable Frontend HTTP Authentication
2.Set Custom Username and Password
3.Set Login Message which will display in login dialog prompt
4.Set error content by WYSWYG editor