=== NextScripts: Social Networks Auto-Poster ===

Contributors: NextScripts
Donate link: http://www.nextscripts.com/social-networks-auto-poster-for-wordpress
Tags: automation, socialnetworks, social networks, facebook, google, twitter, google plus, pinterest, tumblr, plugin, links, Post, posts, api, automatic, seo, integration, bookmark, bookmarking, bookmarks, admin, images, image
Requires at least: 2.8
Tested up to: 3.4.1
Stable tag: 1.8.3
License: GPLv2 or later

This plugin automatically publishes posts from your blog to your Facebook, Twitter, Google+(Google Plus), Pinterest and Tumblr profiles and/or pages

== Description ==

This plugin automatically publishes posts from your blog to your Facebook, Twitter, Google+(Google Plus), Pinterest and Tumblr profiles and/or pages. The whole process is completely automated. Just write a new post and either entire post or it's announcement with back link will be published to all your configured social networks.

Social Networks Auto Poster can automatically publish nicely formatted announcements to your Facebook, Twitter, Tumblr, and Google+ (Google Plus) accounts, so you can reach the most audience and tell all your friends, readers and followers about your new post. Plugin works with profiles, business pages, community pages, Facebook groups, etc. Plugin supports custom wordpress post types. 

Supported Networks:

**Facebook** - Autopost to your profile, business page, community page, or facebook group page. Ability to attach your blogpost to Facebook post. 

**Twitter** - Autopost to your account.

**Tumblr** - Autopost to your account. Ability to attach your blogpost to Tumblr post. 

**Google+** (*with third party library*) - Autopost to your profile or business page. Ability to attach your blogpost to Google+ post. 

**Pinterest** (*with third party library*) - Pin your blogpost's featured image to your Pinterest board.

... more networks are coming soon ...

== Installation ==

You need to have account with either Facebook, Tumblr, Google+, Pinterest, Twitter or all of them.

See more detailed installation instructions with screenshots here: http://www.nextscripts.com/installation-of-social-networks-auto-poster-for-wordpress 

Social Networks Auto Poster (SNAP).

1. Upload plugin folder to the /wp-content/plugins/.
2. Login to your Wordpress Admin Panel, Go to the Plugins->Installed Plugins, Find "Next Scripts Google+ AutoPoster" in your list, click "Activate"

Facebook.

1. Create an app and community page for your website.
   1. Login to your Facebook account.
   2. Go to the Facebook Developers page: https://developers.facebook.com/apps
   3. Click "+ Create New App" button. Fill "App Name", "App Namespace", agree to policies and click "Continue", fill captcha, click "Continue".
     ***Notice App ID and App Secret on this page.
   4. Click "Website", enter your website URL
   5. Enter your domain to the App Domain. Domain should be the same domain from URL that you have entered to the "Website" during the step 4.   
   6. [Optional - you can skip this step and use existing page] Click "Advanced" from the left side menu "Settings.". Scroll all the way down and click "Create Facebook Page" button. Facebook will create Community page for your App. Click on it and see the URL. It will be something like http://www.facebook.com/pages/Your-Site-Community/304945439569358
   
2. Connect Facebook to your Wordpress.
   1. Login to your Wordpress Admin Panel, Go to the Settings->Social Networks AutoPoster Options.
   2. Check "Auto-publish your Posts to your Facebook Page or Profile" checkbox.
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
   2. Check "Auto-publish your Posts to your Twitter Page or Profile" checkbox.
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
   2. Check "Auto-publish your Posts to your Google+ Page or Profile" checkbox.
   3. Fill Google+ Login and Password. Please note that Wordpress is not storing your Google+ password in very secure manner, so you better create a separate G+ account for your website.
   4. Fill the ID of your page. You can get this ID from your URL (Step 2 above). If your URL is https://plus.google.com/u/0/b/117008619877691455570/ - your ID is 117008619877691455570
3. Your Twitter is ready to use. 

== Frequently Asked Questions ==

= Can I use it just for Twitter(Facebook, G+) or it requres all three networks to be set? =

Sure you can use it for just one or two networks.

= Can it post to Facebook and Google+ pages? Not to profiles, but to pages. =

Yes, it can. Specify page IDs in the settings, and it will post to pages. 

== Screenshots ==

1. Settings Page
2. Google+ Post

== Changelog ==

= 1.8.3 =
* Improvement - better compatibility with some other popular plugins.
* Bug fix - Tumblr Authorization Problem. 

= 1.8.2 =
* Bug fix - Tumblr Authorization Problem. 

= 1.8.1 =
* Improvement - Pinterest will look for images in post text if featured image is missing
* Improvement - Pinterest - adbility to change board diring the post writing.
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
* Improvement - Not requred to replace G+ library with each update if placed into /wp-content/plugins/ folder.

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

Please note, that if you have postToGooglePlus.php installed, auto-update from wordpress will remove it. You will need to put it back manually.

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