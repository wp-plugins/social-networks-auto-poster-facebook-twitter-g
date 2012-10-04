=== NextScripts: Social Networks Auto-Poster ===

Contributors: NextScripts
Donate link: http://www.nextscripts.com/social-networks-auto-poster-for-wordpress
Tags: automation, auto, autopost, auto-post, auto post, socialnetworks, socialnetwork, social networks, social network, facebook, google, google+, twitter, google plus, pinterest, tumblr, blogger, blogspot, blogpost, linkedin, delicious, delicious.com, plugin, links, Post, posts, api, automatic, seo, integration, bookmark, bookmarking, bookmarks, admin, images, image, social, sharing, share, repost, re-post, wordpress.com
Requires at least: 2.8
Tested up to: 3.4.2
Stable tag: 2.2.4
License: GPLv2 or later

Automatically publish blogposts to your Facebook, Twitter, Google+, Pinterest, LinkedIn, Delicious, Blogger and Tumblr profiles and/or pages.

== Description ==

**This plugin automatically publishes posts from your blog to your Social Network accoutns** such as Facebook, Twitter, Google+(Google Plus), Blogger, Delicious, LinkedIn, Pinterest, Wordpress, and Tumblr profiles and/or pages. The whole process is completely automated. Just write a new post and either entire post or it's announcement with back link will be published to all your configured social networks.

Social Networks Auto Poster can automatically publish nicely formatted announcements to your Facebook, Twitter, LinkedIn, Tumblr, Wordpress, Delicious, Blogger, and Google+ (Google Plus) accounts, so you can reach the most audience and tell all your friends, readers and followers about your new post. Plugin works with profiles, business pages, community pages, Facebook groups, etc. Plugin supports custom wordpress post types. 

Latest version 2.2 - Support for LinkedIn Company pages (*with third party library*), History/Log Tab, improved interface and numerous bug fixes.

Supported Networks:

**Facebook** - Autopost to your profile, business page, community page, or facebook group page. Ability to attach your blogpost to Facebook post. 

**Twitter** - Autopost to your account.

**Google+** (*with third party library*) - Autopost to your profile or business page. Ability to attach your blogpost to Google+ post. 

**Pinterest** (*with third party library*) - Pin your blogpost's featured image to your Pinterest board.

**LinkedIn** - Autopost to your account. Ability to attach your blogpost to LinkedIn post. Autopost to LinkedIn Company pages (*with third party library*)

**Blogger/Blogspot** - Autopost to your Blog.

**Delicious** - Auto-submit bookmark to your account. 

**Tumblr** - Autopost to your account. Ability to attach your blogpost to Tumblr post. 

**Wordpress** - Auto-submit your blogpost to another blog based on Wordpress. This options includes Wordpress.com, Blog.com, etc..

... more networks are coming soon ...

Please see <a href="http://www.nextscripts.com/installation-of-social-networks-auto-poster-for-wordpress/">complete installation instructions with screenshots</a>

<a href="http://www.nextscripts.com/support/">Contact support/Open Support Ticket</a>

== Installation ==

You need to have account with either Facebook, Tumblr, Google+, LinkedIn, Pinterest, Blogger, Twitter, Delicious or all of them.

**Please, see more detailed installation instructions with screenshots here: http://www.nextscripts.com/installation-of-social-networks-auto-poster-for-wordpress **

Social Networks Auto Poster (SNAP).

1. Upload plugin folder to the /wp-content/plugins/.
2. Login to your Wordpress Admin Panel, Go to the Plugins->Installed Plugins, Find "Next Scripts Google+ AutoPoster" in your list, click "Activate"

Facebook.

1. Create an app and community page for your website.
   1. Login to your Facebook account.
   2. Go to the Facebook Developers page: https://developers.facebook.com/apps
   3. Click "+ Create New App" button. Fill "App Name", "App Namespace", agree to policies and click "Continue", fill captcha, click "Continue".
     ***Notice App ID and App Secret on this page.
   4. Click "Website with Faceook Login", enter your website URL
   5. Enter your domain to the App Domain. Domain should be the same domain from URL that you have entered to the "Website  with Faceook Login" during the step 4.   
   6. [Optional - you can skip this step and use existing page] Click "Advanced" from the left side menu "Settings.". Scroll all the way down and click "Create Facebook Page" button. Facebook will create Community page for your App. Click on it and see the URL. It will be something like http://www.facebook.com/pages/Your-Site-Community/304945439569358
   
2. Connect Facebook to your Wordpress.
   1. Login to your Wordpress Admin Panel, Go to the Settings->Social Networks AutoPoster Options.
   2. Click green "Add new account" button, select "Facebook" from the list. 
   3. Fill URL of your Community page from step 6 above.
   4. Fill "App ID" and "App Secret" from step 3 above.
3. Authorize Facebook for your Wordpress.
   1. Click "Update Settings". Notice new link "Authorize Your FaceBook Account".
   2. Click "Authorize Your FaceBook Account" and follow the Facebook authorization wizard. If you get any errors at this step, please make sure that domain for your Wordpress site is entered to your App as "App Domain".
4. Your facebook is ready to use.

Twitter.

1. Create a Twitter App for your website.
   1. Login to your Twitter account.
   2. Go to the Twitter Developers website: https://dev.twitter.com/ Sign in again if asked.
   3. Click "Create an app" link from the right panel. Fill details, click "Create your Twitter application".
     ***Notice Consumer key and Consumer secret on this page.    
   4. Click "Settings" tab. Scroll to the "Application type", change Access level from "Read Only" to "Read and Write". Click "Update this Twitter application settings".    
   5. Come back to "Details" tab. Scroll to the "Your access token" and click "Create my access token" button. Refresh page and notice "Access token" and "Access token secret". Make sure you have "Read and Write" access level. 
   
2. Connect Twitter to your Wordpress.    
   1. Login to your Wordpress Admin Panel, Go to the Settings->Social Networks AutoPoster Options.
   2. Click green "Add new account" button, select "Twitter" from the list. 
   3. Fill your Twitter URL.
   4. Fill "Consumer key" and "Consumer secret" from step 3 above.
   5. Fill "Access token" and "Access token secret" from step 5 above.
3. Your Twitter is ready to use.

Google+.

Google+ don't yet have API for automated posts. You need to get special library module to be able to publish Google+ posts.

1. Create Google+ page for your website.
   1. Login to your Google+ account.
   2. Click "Create a Google+ page" link from the right panel. Choose category, fill details, click "Create".
     ***Notice the URL of your page. 
2. Connect Google+ to your Wordpress.
   1. Login to your Wordpress Admin Panel, Go to the Settings->Social Networks AutoPoster Options.
   2. Click green "Add new account" button, select "Google+" from the list. 
   3. Fill Google+ Login and Password. Please note that Wordpress is not storing your Google+ password in very secure manner, so you better create a separate G+ account for your website.
   4. Fill the ID of your page. You can get this ID from your URL (Step 2 above). If your URL is https://plus.google.com/u/0/b/117008619877691455570/ - your ID is 117008619877691455570
3. Your Twitter is ready to use. 

Please see <a href="http://www.nextscripts.com/installation-of-social-networks-auto-poster-for-wordpress/">complete installation instructions with screenshots</a>

== Frequently Asked Questions ==

= Can I use it just for Twitter(Facebook, G+) or it requres all three networks to be set? =

Sure you can use it for just one or two networks.

= Can it post to Facebook and Google+ pages? Not to profiles, but to pages. =

Yes, it can. Specify page IDs in the settings, and it will post to pages. 

Please see more <a href="http://www.nextscripts.com/faq/">Frequently asked questions</a>

== Screenshots ==

1. Settings  Page
2. Google+  Post 

== Changelog ==

= 2.2.5 =

* Improvement - Better Facebook Authorization handling
* Bug fix - Facebook Formatting problems.
* Bug fix - Blogger Connections.
* Bug fix - Errors in WP 2.8

= 2.2.4 =

* New - Installation/Configuration links.
* Bug fix - Plugin Activation problem on system with short_open_tag off
* Bug fix - Pinterest posting problems.
* Bug fix - Delicious Login problems.
* Bug fix - LinkedIn Company Pages posting improvements.
* Bug fix - Facebook re-posting without attached post problems.
* Bug fix - Blogger - "These characters are not allowed in a post label" error.
* Bug fix - Another try to work around Chrome bugs adding multiple Blogger accounts.

= 2.2.3 =

* New - New Tab - Help/Suppoer with some usefull info.
* Bug fix - Important performance fix.
* Bug fix - Log/History Refresh and Clear Buttons.

= 2.2.2 =

* Improvement - Some interface improvements.
* Bug fix - Important performance and stability fix.
* Bug fix - Problem with disappearing accounts.

= 2.2.1 =

* New - Admin can decide what user level can see the SNAP Meta Box on the "New Post" page.
* Bug fix - Better Facebook authorization errors handling
* Bug fix - LinkedIn was still attaching a post if not selected.
* Bug fix - Problem with Log/History saving.

= 2.2.0 =

* New - NextScript LinkeIn API support for company pages auto-posting (Beta).
* New - Actions Log - see the log of the auto-postings.
* Improvement - Better interface.
* Bug fix - "headers already sent by line 344" Error.
* Bug fix - Workaround fix for non-numeric "Facebook Group" pages. We hope that Facebook will fix it soon.
* Bug fix - Saving problems for the "Settings" page.
* Bug fix - LinkedIn post Formatting problems. 
* Bug fix - Facebook was still attaching a post if not selected.

= 2.1.3 =

* Improvement - Include/Exclude categories are now a select/unselect inteface, not a field for entering numbers.
* Improvement - Better Facebook attachement images handling.
* Improvement/Bug fix - Detection of the Select Google Analytics for WordPress plugin that causes authorization troubles.
* Bug fix - Twitter was missing URL if Title is too long.
* Bug fix - Include/Exclude categories

= 2.1.2 =
* Bug fix - 404 Errors during reactivation.
* Bug fix - Message for Multiuser Wordpress.
* Bug fix - Tumblr Authorization problems.

= 2.1.1 =
* Bug fix - Unselected Networks were still published.
* Bug fix - Broken quotes in the "Message Format".
* Bug fix - "Post Immediately" was broken for free accounts.

= 2.1.0 =
* New - New network: Wordpress based websites. This option includes Wordpress.com, Blog.com, and and any other blogs based on WP.
* Improvement - nicknames for your accounts. You can give each account a nickname to make it easier to identify in the list.
* Improvement - better looking settings pages. 
* Improvement - new option to either schedule auto-posts (recommended) or do it immediately. This could be useful to the people with disabled or broken WP Cron.
* Critical Stability fix - The next GoDaddy crush should not break your website.
* Bug fix - disappearing accounts.
* Bug fix - custom post settings weren't saved in some cases.
* Bug fix - format and settings fixes for almost all networks.

= 2.0.12 =
* Bug fix - Some Facebook connectivity isses.
* Bug fix - Unselected Custom post types were still published in some cases.

= 2.0.11 =
* Bug fix - Compatibility issue with some browsers.

= 2.0.10 =
* Bug fix - Facebook "Share link" fix.
* Improvement/Bug fix - some interface cosmetic changes.

= 2.0.9 =
* Bug fix - Facebook Authorization "Error 100" Fix.

= 2.0.8 =
* Improvement - Better list of available accounts.
* Improvement/Bug fix - a lot of cosmetic interface changes and code optimizations for problem fixing and better looking.
* Bug fix - Google+ Wrong options when using "Repost Button"
* Bug fix - Google+ Fixed publishing of new lines in messages.
* Bug fix - Pinterest Settings Disappearance

= 2.0.7 =
* Improvement - Better list of available accounts.
* Bug fix - "Facebok Options Save" error fix.

= 2.0.6 =
* Improvement - Ability to check/uncheck all networks during post writing
* Bug fix - Unchecked networks were still getting posts
* Bug fix - Pinterest "Retrieve Boards" problem
* Bug fix - Delicious broken tags error.

= 2.0.5 =
* New - Delicious support (test)
* Bug fix - Pinterest "Cannot access empty property" error.

= 2.0.4 =
* Improvement - Pinterest is caching login info to prevent "multiple logins" issues.
* Bug fix - Pinterest special characters

= 2.0.3 =
* Initial public 2.0 Release.

= 1.9.13 =
* Improvement - Pinterest is caching login info to prevent "multiple logins" issues.
* Bug fix - Pinterest special characters
* Bug fix - Tumblr Authorization issue.

= 1.9.12 =
* New - Version 2.0.3 Beta is available to try.
* Bug fix - Removed many (\\\) Slashes from some Google+ Posts.
* Bug fix - Tumblr Authorization fix.
* Bug fix -  New LinkedIn oAuth model support fix.
* Bug fix -  Twitter New "Smarter" Twitter 140 characters limit handling fix.

= 1.9.11 =
* Bug fix - Google+ Fix for new interfaces.
* Improvement/Bug fix - New "Smarter" Twitter 140 characters limit handling. URL won't be cut anymore.

= 1.9.10 =
* Improvement/Bug fix - New LinkedIn oAuth model support.  

= 1.9.9 =
* Bug fix - Javascript/JQuery Error fixed  

= 1.9.8 =
* Improvement - Now you have a choice between "attaching" your post to Facebook or "Sharing a link" to it  
* Improvement - Better Twitter connection for non SSL
* Bug fix - Pinterest Default Settings
* Bug fix - Pinterest Board Selection

= 1.9.7 =
* Improvement - New Internal DB Structure preparing for 2.0
* Bug fix - Google Connectivity issues
* Bug fix - Blogger Connectivity issues

= 1.9.6 =
* Bug fix - Twitter formatting
* Bug fix - Google incorrect page issue.
* Bug fix - Facebook Personal Page Authorization Issue.
* Bug fix - SSL connectivity issued for some hosts.

= 1.9.5 =
* Bug fix - Twitter short URLS
* Bug fix - Google/Pinterest Connectivity issues

= 1.9.4 =
* Bug fix - Tumblr, LinkedIn and Blogger compatibility issues..

= 1.9.3 =
* Bug fix - Missing "No custom posts" option.

= 1.9.2 =
* Improvement - Ability to Include/Exclude "Custom Post Types" from autoposting.
* Improvement - Better "Custom Post Types" support.
* Bug fix - Tumblr Authorization issues

= 1.9.1 =
* Bug fix - Correct Special Character Encoding
* Bug fix - Blooger Encoding issues.

= 1.9.0 =
* New - LinkedIn Support
* Improvement - Post Options are now movable
* Improvement - Security for Google+, Pinterest, Blogger - passwords are better encoded in the DB.
* Improvement - Tumblr - Better compatibility with other plugins.
* Bug fix - Twitter URL length fix.
* Bug fix - Google+, Pinterest, Blogger - Incorrect Username/Problem due to the magic quotes being "On"
* Bug fix - More then 10 stability, compatibility, security fixes.

= 1.8.7 =
* Bug fix - Tumblr/Blogger issue with missing function.

= 1.8.6 =
* New - If blogpost has video it can be used as attachment in Facebook post. 
* Bug fix - Facebook %TEXT% and %FULLTEXT% formatiing issues.
* Bug fix - Some Blogger Authorization issues.

= 1.8.5 =
* Bug fix - Format settings disappeared after update post
* Bug fix - Twitter 140 characters limit when used with %TEXT% and %FULLTEXT%

= 1.8.4 =
* New - Blogger Support
* New/Improvement - Post to Tumblr and Blogger/Blogspot could be posted with tags
* New/Improvement - Tumblr is now open_basedir safe. 
* Bug fix - G+ Authorization problem with non google.com domains (like google.com.sg, google.com.br, google.ru, etc). 
* Bug fix - Pinterest "Test" Button

= 1.8.3 =
* Improvement - better compatibility with some other popular plugins.
* Bug fix - Tumblr Authorization Problem. 

= 1.8.2 =
* Bug fix - Tumblr Authorization Problem. 

= 1.8.1 =
* Improvement - Pinterest will look for images in post text if featured image is missing
* Improvement - Pinterest - ability to change board during the post writing
* Bug fix - Several small bugs and formating fixes.

= 1.8.0 =
* New - Pinterest Support
* New - Tumblr Support
* New/Improvement - %IMG% replacement tag - Inserts Featured Image URL
* Improvement - Better Image Handling  
* Improvement - Better Facebook Authorization
* Improvement - Google+ Interactive Phone and Email Account Verification Support
* Bug fix - Google+ "You are not authorized for this page" Error

= 1.7.6 =
* Improvement - Better Facebook Posts Formatting  
* Improvement - Better Google+ Posts Formatting  
* Improvement - Google+ Phone Verification support
* Bug fix - Google+ "You are not authorized for this page" Error

= 1.7.5 =
* New/Improvement - %SURL% replacement tag - Shortens URL
* Improvement - Wordpress 3.4 Compatibility
* Improvement - Better handling of Twitter's "140 characters limit" 
* Bug fix - Facebook posts to use Home URL instead of Site URL
* Bug fix - Better error handling

= 1.7.3 =
* Bug fix - Some Facebook Authorization/Connection issues.

= 1.7.2 =
* New/Improvement - %AUTHORNAME% - Inserts the author's name.
* Improvement - better Facebook errors handling
* Bug fix - Facebook 1000 character limit error fixed.

= 1.7.1 =
* Bug fix - Repost button fixed.

= 1.7.0 =
* New - Support for Wordpress "Custom Post Types".
* New - Ability to add open graph tags without third party plugins.
* Improvement - Better compatibility/faster Google+ posting.
* Improvement - If post thumbnail (featured image) is not set, script will look for images in the post.
* Improvement - If excerpt is not set, script will auto-generate it.
* Bug fix - Fixed "Changing format of the message for each individual post" problem.
* Bug fix - Fixed missing "Pending-to-Publish" status change.
* Bug fix - Twitter settings page format fixed.

= 1.6.2 =
* Bug fix - Fix for "Cannot modify header information" message while posting to Twitter.

= 1.6.1 =
* Improvement - New posting format: %TEXT% - Inserts the excerpt of your post. %FULLTEXT% - Inserts the body(text) of your post.
* Bug fix - Activation Problem "unexpected $end" for servers with no support for short php tags <? ?>.

= 1.6.0 =
* Improvement - New improved settings page with test buttons.
* Bug fix - Rare Facebook crush.
* Bug fix - G+ Stability Fix.

= 1.5.9 =
* Improvement/Bug fix - Fixed compatibility with another plugins using the same Facebook and Twitter APIs.

= 1.5.8 =
* Bug fix - G+ problem with Wordpress installed on Windows Servers.
* Bug fix - Problem with Facebook and empty Website title.

= 1.5.7 =
* Improvement - Updated Facebook posting - support for Facebook Groups, faster profile posting.
* Improvement - Better compatibility with older WP versions (<3.1).
* Improvement - Not required to replace G+ library with each update if placed into /wp-content/plugins/ folder.

= 1.5.6 =
* Bug fix - Wrong Options Page Placement.
* Improvement - Better G+ Attachments Handling.

= 1.5.5 =
* Bug fix - Included/Excluded Categories.
* Improvement - Easier Facebook setup.

= 1.5.4 =
* Bug fix - Wrong Re-Post Buttons.
* Improvement - Better G+ Compatibility.
 
= 1.5.3 =
* Bug fix - Correct Message after the post.

= 1.5.2 =
* Bug fixes - default checkboxes.

= 1.5.1 =
* Initial public release

= 1.2.0 =
* Closed Beta

= 1.0.0 =
* Closed Beta

== Upgrade Notice ==

Just repllace plugin files, the rest will be updated automatically.

== Other/Copyrights ==

Plugin Name: Next Scripts Social Networks Auto-Poster

Plugin URI: http://www.nextscripts.com/social-networks-auto-poster-for-wordpress

Description: This plugin automatically publishes posts from your blog to your Facebook, Twitter, and Google+ profiles and/or pages.

Author: Next Scripts

Author URL: http://www.nextscripts.com

Copyright 2012  Next Scripts, Inc

PHP Twitter API: Copyright 2012 -  themattharris - tmhOAuth

PHP Facebook API: Copyright 2011 Facebook, Inc.

NextScripts, Inc