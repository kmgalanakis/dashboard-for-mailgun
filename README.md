# Dashboard for Mailgun

[![Build Status](https://travis-ci.org/kmgalanakis/dashboard-for-mailgun.svg?branch=master)](https://travis-ci.org/kmgalanakis/dashboard-for-mailgun)

Dashboard for Mailgun creates an administration page that contains a graph and a table that depict the statistics and the events of the selected Mailgun domain. The plugin allows to either set the domain information internally or use the information coming from the official Mailgun plugin.

## Installation

1. Upload the 'dashboard-for-mailgun' to the `/wp-content/plugins/` directory.
2. Activate the plugin through the 'Plugins' menu in WordPress.
3. Insert the Mailgun domain and the Mailgun API key on the settings section of the plugin or use the relevan checkbox to use the settings of the official Mailgun plugin.


## Frequently Asked Questions

* __Q:__ Can I add multiple Mailgun domains?
* __A:__ At the moment this is not possible.


* __Q:__ I am frequently getting the error "Mailgun API failed!" error. When inspective the JS console I see "cURL error 28: Resolving timed out after XXXX milliseconds", what's wrong? =
* __A:__ This error is related to the DNS settings of your server. Contact your host to help you resolve this.


* __Q:__ I cannot see all the event or the messages on my dashboard, why is that? =
* __A:__ Depending on your Mailgun account type, there are certain limitations on the number of resourses you are able to access.

## Screenshots

* To be added...

## Changelog

* __0.1.0__
    * Release date: 2018-02-04
    * Initial release.