Increase-Upload-Max-Filesize
============================

WordPress plugin to increase your upload max filesize limit on your server by setting rules on php5.ini or php.ini.

**New since version 1.0.1: option to use `php.ini`.**

Increases your website's upload max filesize limit on your server by adding rules to `php5.ini`. If a `php5.ini` file does not exist on your server in the root of your website, one will be created.

If your memory limit ( `memory_limit` ) is set to less than 64 MB, then it will be set to 64M.

If your upload max filesize ( `upload_max_filesize` ) is set to less than 32 MB, it will be set to 32M.

If your post max size ( `post_max_size` ) is set to less than 33 MB, then it will be set to 33M.

No other will rules will changed.

**Super Easy To Use:**

1.  It works immediately upon activation.
2.  After installing it, go to **Tools -> Upload Max Filesize** to see your current status. If the current status shows that your `'upload_max_filesize'` is 32M or greather, the plugin has worked. You may deactivate the plugin.
3.  If the plugin did not work, it may be because your server does not recognize `php5.ini`, and only recognizes `php.ini`. So, check the box labeled "USE php.ini INSTEAD OF php5.ini" and click the blue button once. Then, check your current status again after a few minutes.

**It will only run once, upon plugin activation, or upon clicking the blue button in `Tools -> Upload Max Filesize`.**
Thus, if you later make any manual changes to decrease your `upload_max_filesize`, the plugin will not increase it again. You must de-activate the plugin, then reactivate it to make it run again.

For support, please use the [Support forum](http://wordpress.org/support/plugin/increase-upload-max-filesize). 

See the [Installation Instructions](http://wordpress.org/plugins/increase-upload-max-filesize/installation/).
