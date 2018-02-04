=== Mailgun Dashboard ===
Contributors: kmgalanakis
Donate link: https://github.com/kmgalanakis
Tags: mailgun, dashboard, email, transactional, mail, gun
Requires at least: 4.2
Tested up to: 4.7
Stable tag: 0.1.0
License: GPLv2 or later
License URI: http://www.gnu.org/licenses/gpl-2.0.html

Get the statistics and the events of a Mailgun domain inside your WordPress site's backend.

== Description ==

Mailgun Dashboard creates an administration page that contains a graph and a table that depict the statistics and the events of the selected Mailgun domain. The plugin allows to either set the domain information internally or use the information coming from the official Mailgun plugin.

== Installation ==

1. Upload the 'mailgun-dashboard' to the `/wp-content/plugins/` directory.
2. Activate the plugin through the 'Plugins' menu in WordPress.
3. Insert the Mailgun domain and the Mailgun API key on the settings section of the plugin or use the relevan checkbox to use the settings of the official Mailgun plugin.


== Frequently Asked Questions ==

= Can I add multiple Mailgun domains? =

At the moment this is not possible.

= I am frequently getting the error "Mailgun API failed!" error. When inspective the JS console I see "cURL error 28: Resolving timed out after XXXX milliseconds", what's wrong? =

This error is related to the DNS settings of your server. Contact your host to help you resolve this.

= I cannot see all the event or the messages on my dashboard, why is that? =

Depending on your Mailgun account type, there are certain limitations on the number of resourses you are able to access.

== Screenshots ==

== Changelog ==

= 1.0 =
* Release date: 2018-02-04
* Initial release.

== Upgrade Notice ==

= 1.0 =
Initial release.