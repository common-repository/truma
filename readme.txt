=== Truma ===
Contributors: rotemtamir
Tags: donations, walla!pay service
Requires at least: 2.8
Tested up to: 2.8

After registering at http://www.wallapay.co.il, use this plugin to collect donations
from your visitors using the walla!Pay service.

== Disclaimer ==

Use this plugin at your own risk.  The author is not responsible for any consequences, wanted or unwanted
of using this plugin.

השתמשו בתוסף זה על אחריותכם בלבד.  המחבר אינו אחראי לכל תוצאות רצויות או לא רצויות של השימוש בתוסף זה.

== Description ==

Using this plugin you can collect donations to your organization using the Israeli wallaPay service.
This plugin was built according to the specs created by Walla and stated in this document:
http://www.wallapay.co.il/PO/WPO.pdf

Features:
* Works according to wallapay protocol
* Records user requests and wallaPay server replies

== Installation ==

1. Upload `truma` and all the contained files to the `/wp-content/plugins/` directory
2. Activate the plugin through the 'Plugins' menu in WordPress
3. Configure the plugin.
4. Configure your wallaPay account.
5. Place `[truma-form]` in one of your posts/pages to display form.

== Frequently Asked Questions ==

= איך להגדיר את החשבון בוואלה? =

כתובת למשיכת פרטי עסקה
http://yourblog.co.il/wp-content/plugins/truma/php/getDetails.php

כתובת לאישור עסקה
http://yourblog.co.il/wp-content/plugins/truma/php/tr-confirmation.php

תקלה
http://yourblog.co.il/

הצלחת העסקה
http://yourblog.co.il/wp-content/plugins/truma/php/tr-thankyou.php

= How do I change the image in tr-thankyou.php =

Just put any image you like in img/thank-you.jpg

== Screenshots ==

1. This screen shot description corresponds to screenshot-1.(png|jpg|jpeg|gif). Note that the screenshot is taken from
the directory of the stable readme.txt, so in this case, `/tags/4.3/screenshot-1.png` (or jpg, jpeg, gif)
2. This is the second screen shot

== Changelog ==

= 0.1 =
* New release.
