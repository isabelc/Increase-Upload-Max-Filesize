=== Increase Upload Max Filesize ===
Author URI: http://smartestthemes.com
Plugin URI: http://smartestthemes.com/downloads/increase-upload-max-filesize-wp-plugin/
Contributors: SmartestThemes, isabel104
Donate link: https://www.paypal.com/cgi-bin/webscr?cmd=_donations&business=isa%40smartestthemes%2ecom
Tags: php ini, php5, ini rules, upload max filesize, upload limit, filesize, increase filesize, upload_max_filesize, memory_limit
Requires at least: 3.4
Tested up to: 3.6.1
Stable Tag: 1.0
License: GNU Version 2 or Any Later Version
License URI: http://www.gnu.org/licenses/gpl-2.0.html

Increases your website's upload max filesize limit on your server by adding rules to php5.ini.

== Description ==

Increases your website's upload max filesize limit on your server by adding rules to `php5.ini`. If a `php5.ini` file does not exist on your server in the root of your website, one will be created.

If your memory limit ( `memory_limit` ) is set to less than 64 MB, then it will be set to 64M.

If your upload max filesize ( `upload_max_filesize` ) is set to less than 32 MB, it will be set to 32M.

If your post max size ( `post_max_size` ) is set to less than 33 MB, then it will be set to 33M.

No other will rules will changed.

**Super Easy To Use:**

It works immediately upon activation. Just install it, see the confirmation message that it worked, and then you can deactivate it. The job is complete.

**It will only run once, upon plugin activation.** 
Thus, if you later make any manual changes to decrease your `upload_max_filesize`, the plugin will not increase it again. You must de-activate the plugin, then reactivate it to make it run again.


For support, please use the [Support forum](http://wordpress.org/support/plugin/increase-upload-max-filesize).

Contribute or fork it [on Github](https://github.com/isabelc/increase-upload-max-filesize).

== Installation ==

1. Download the plugin file, `increase-upload-max-filesize.zip`
2. Go to **Plugins -> Add New -> Upload** to upload the plugin
3. Click "Activate" to activate the plugin.

== Frequently Asked Questions ==

None yet.

== Changelog ==

= 1.0 =
* Initial release.