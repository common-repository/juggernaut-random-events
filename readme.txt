=== Plugin Name ===
Contributors: JuggernautPlugins, kaser
Donate link: http://JuggernautPlugins.com/
Tags: promotion, marketing, give aways, giveaway, prize, prizes, give, free, freebies, win, chances, visitors
Requires at least: 3.0.1
Tested up to: 4.5.3
Stable tag: 2.1
License: GPLv2 or later
License URI: http://www.gnu.org/licenses/gpl-2.0.html

Create random events anywhere on your website to get your visitors excited while increasing your pageviews, time on site, and customer loyalty.

== Description ==

Create random events anywhere on your website to get your visitors excited while increasing your pageviews, time on site, and customer loyalty. You can give away anything you want to rewards visitors. For example you could give away a free report, product, software license, or coupon code with Juggernaut Random Events. You also get to choose the how often your random events are triggered so it's a total surprise for visitors. :)

== Installation ==

1. Go to your wp-admin > plugins > Add New
2. Search For Random Events Pro and find Random Events Pro by JuggernautPlugins.com
3. Click Install Now

== Frequently Asked Questions ==

= How can I give my customers a coupon to my store? =

I would create a page that either displays the coupon code or has an optin form to automatically receive an email with the coupon when someone signs up. Auto Responder email services like MailChimp.com or aWeber.com etc... have those features.

== Screenshots ==

1. The Random Events Dashboard is easy to understand and configure, allowing you to quickly set up and start running your new Random Event in seconds flat. 

2. The Overview page displays important information at a glance

3. an example modal popup

== Changelog ==

= 1.0 =
initial release

= 1.1 =
added options page, 
chance option, 
added random looping through multiple locations

= 1.2 =
new pop up and admin page designs,
added new call to action field
choose specific locations instead of just random looping, widget's and shortcodes coming in next couple versions :)
added destination URL for prize ( squeeze page )
added 100% chance for testing or other creative purposes
added popup modal option for location and a close buttons
added default field values 

= 1.3 =
fixed random and model views to function properly
added photo uploading through wp admin
popup can be closed even when it's not a modal.
added modal / nonmodal logic for displaying and closing correctly
cleaned up undefined variables!

= 2.0 =
introducing Juggernaut Random Event Pro!
Added widget for displaying the Random Event in any widget area.
Added shortcode support so that you can have full control over where your pop up happens! 
Added custom post type for multiple random event support.
Added option for modal window pop up
Added live preview on Random Event Creation Page
Added Customization options for background and font colors for the random even titles, description, call to action, no thanks link, and the modal overlay background.
Added view counts for each random event
added some information to the "All Events" overview page of Random Events. 

= 2.1 =
Cleaned up the cluster of metaboxes for the random even:
Put view count in the publishing box,
removed enabled/disabled ( core WP features draft/publish will be used now),
combined 'jre_chance()' and 'jre_location()', with jre_customize!
Added `resources/juggernaut_random_event_pro_admin.css` to `random_event` post types for styling and moved some static CSS from juggernaut_random_event_pro.php over
can now schedule your random events to start at a specific date.
changed modal positioning back to `position: fixed;`
updated author names for WordPress.org - they're case sensitive!
removed enabled/disabled column from All Events overview page.
reduced `if ( $modal_active > 0 ) { // do nothing } else { }` to `if ( $modal_active == 0 ) { }

== Upgrade Notice ==

= 1.3 =
Introducing Juggernaut Random Event from Juggernaut Plugins

= 2.0 = 
Introducing Juggernaut Random Events Pro from Juggernaut Plugins, multiple Random Events, widgets and shortcode support for full control over your Random Events!
each event now can have unique chances, location, call to action, destination URL, featured image, and color scheme!
each Random Event now has an enabled and disabled parameter to allow for better control during set up.