<?php
/*
Plugin Name: Increase Upload Max Filesize
Plugin URI: http://smartestthemes.com/downloads/increase-upload-max-filesize-wp-plugin/
Description: Increases your website's upload max filesize limit on your server by adding rules to php5.ini or php.ini.
Version: 1.1-rc-3
Author: Smartest Themes
Author URI: http://smartestthemes.com
License: GPL2
Text Domain: increase-upload-max-filesize

Copyright 2013 - 2014 Smartest Themes(email : isa@smartestthemes.com)

Increase Upload Max Filesize is free software; you can redistribute it and/or modify
it under the terms of the GNU General Public License, version 2, as 
published by the Free Software Foundation.

Increase Upload Max Filesize is distributed in the hope that it will be useful,
but WITHOUT ANY WARRANTY; without even the implied warranty of
MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
GNU General Public License for more details.

You should have received a copy of the GNU General Public License
along with Increase Upload Max Filesize; if not, write to the Free Software
Foundation, Inc., 51 Franklin St, Fifth Floor, Boston, MA  02110-1301  USA
*/
if(!class_exists('Increase_Upload_Max_Filesize')) {
class Increase_Upload_Max_Filesize {

	private static $instance = null;

	public static function get_instance() {
		if ( null == self::$instance ) {
			self::$instance = new self;
		}
		return self::$instance;
	}

	private function __construct() {
		add_action( 'init', array( $this, 'plugin_textdomain' ) );
		add_action('admin_menu', array($this, 'add_plugin_page'));
		add_action('admin_init', array($this, 'page_init'));
	 }
	/** 
	* Only upon plugin activation, set ini rules
	* @since 1.0
	*/
	public static function activate() { 
		self::ini_rules();
	}

	/** 
	* Upon plugin deactivation, delete options
	* @since 1.0.1
	*/
	public static function deactivate() { 
		delete_option( 'increase_upload_filesize_msg' );
		delete_option( 'inc_upload_max_filesize_options' );
		delete_option( 'increase_upload_filesize_msg_err' );
	}

	/**
	* Add the plugin options page under the Tools menu
	* @since 1.0.1
	*/
	public function add_plugin_page(){
			add_management_page(__('Increase Upload Max Filesize', 'increase-upload-max-filesize'), __('Upload Max Filesize', 'increase-upload-max-filesize'), 'manage_options', 'increase-upload-max-filesize', array($this, 'create_admin_page'));
    }
	
	/**
	* HTML for the options page
	* @since 1.0.1
	*/
	
	public function create_admin_page(){ ?>
		<div class="wrap">
		<?php screen_icon(); ?>
		<h2><?php _e( 'Increase Upload Max Filesize', 'increase-upload-max-filesize'); ?></h2>

		<?php $before_plugin = get_option( 'increase_upload_filesize_msg' );
			$before_plugin_error = get_option( 'increase_upload_filesize_msg_err' ); ?>

		<h3>Your Settings Before Running The Plugin:</h3>
		<div class="updated"><p><?php echo $before_plugin; ?></p><p><?php echo $before_plugin_error; ?></p></div>

		<?php $a = ini_get('upload_max_filesize');
		$c = ini_get('post_max_size');
		$d = phpversion();

		$status = sprintf( __( 'Your upload_max_filesize is %s.%s', 'increase-upload-max-filesize' ), $a, '<br />');
		$status .= sprintf( __( 'Your post_max_size is %s.%s', 'increase-upload-max-filesize' ), $c, '<br /><br />');

		if ( $d < 5 ) {
			$status .= __( 'Your PHP version is less than 5, so no changes will be made. This plugin only works with PHP version 5 and above. I am sorry. You may deactivate the plugin, now.', 'increase-upload-max-filesize' );
		} else {
			$status .= sprintf(__( 'Please allow some time for your server to recognize any changes. You can refresh this page in a few minutes to see if any changes have been made. Please note that changes may have already been made, but you may have to clear your cache to see them here.%1$s You may deactivate the plugin once your %2$supload_max_filesize%3$s is at least 32M.%1$s', 'increase-upload-max-filesize' ), '<br /><br />', '<code>', '</code>' );

		$after_status = sprintf(__( 'By default, this plugin uses %1$sphp5.ini%2$s. If your server uses %1$sphp.ini%2$s instead, please check the box below and click the blue button once.', 'increase-upload-max-filesize' ), '<code>', '</code>' );
		} ?>
		<h3>Current Status:</h3>
		<div class="updated"><p><?php echo $status; ?></p></div><p><?php echo $after_status; ?></p>
		<form method="post" action="options.php">
	        <?php settings_fields( 'inc_upload_max_filesize_options' );	// @param 1 must be same as register settings'
				do_settings_sections( 'increase-upload-max-filesize' );// page slug must match 4th param of add_settings_section 
				submit_button( __( 'Run Once', 'increase-upload-max-filesize' ) ); ?>
		</form>
		</div>
		<?php
    }

	/**
	* Register the plugin options/settings
	* @since 1.0.1
	*/
	public function page_init(){	
		register_setting('inc_upload_max_filesize_options', 'inc_upload_max_filesize_options', array($this, 'sanitize'));
		// @param 1 must be same as the group name in settings_fields'
		// 2nd param is name of the option, will be an array
		add_settings_section(
			'iumf_options_main',// unique id for the section
			__('RUN THE PLUGIN ONCE AGAIN', 'increase-upload-max-filesize' ),
			false,// function callback to display
			'increase-upload-max-filesize'// page name. Must match the do_settings_sections function call. and match options menu page
			
		);	
				

		add_settings_field(
			'inc_umf_setting_two', // unique id for the field
			__( 'Set a custom upload_max_filesize', 'increase-upload-max-filesize' ),
			array($this, 'iumf_textfield_callback'),
			'increase-upload-max-filesize',// page name that this is attached to (same as the do_settings_sections)
			'iumf_options_main',	// the id of the settings section that this goes into (same as the first argument to add_settings_section).
			array( 
				'label' => 'textfield_one',
				'desc' => 'Optional. Enter the number of Megabytes to increase your upload_max_filesize to. Enter just the number without the "M".'
			)
		);

	add_settings_field(
			'inc_umf_setting_three', // unique id for the field
			__( 'Set a custom post_max_size', 'increase-upload-max-filesize' ),
			array($this, 'iumf_textfield_callback'),
			'increase-upload-max-filesize',// page name that this is attached to (same as the do_settings_sections)
			'iumf_options_main',	// the id of the settings section that this goes into (same as the first argument to add_settings_section).
			array( 
				'label' => 'textfield_two',
				'desc' => 'Optional. Enter the number of Megabytes to increase your post_max_size to. Enter just the number without the "M".'
			)
		);

		add_settings_field(
			'inc_umf_setting_one',
			sprintf( __( 'USE %1$sphp.ini%2$s INSTEAD OF %1$sphp5.ini%2$s:', 'increase-upload-max-filesize' ), '<code>', '</code>' ),
			array($this, 'iumf_settings_callback'),
			'increase-upload-max-filesize',// page name that this is attached to (same as the do_settings_sections)
			'iumf_options_main');	// the id of the settings section that this goes into (same as the first argument to add_settings_section).
			
	} // end page_init

	/**
	* HTML for checkbox setting
	* @since 1.0.1
	*/

	public function iumf_settings_callback($args){

	// name must start with the second argument passed to register_setting
    $options = get_option( 'inc_upload_max_filesize_options' );
	if(isset($options['checkbox_one'])) {
		$checked = ' checked="checked" ';
	}
    $html = '<input type="checkbox" id="checkbox_one" name="inc_upload_max_filesize_options[checkbox_one]"'; 
	if(isset($checked)) $html .= $checked;
    $html .= '/><label for="checkbox_one">' . sprintf( __(' Check this to use %1$sphp.ini%2$s instead of %1$sphp5.ini%2$s. Then click the button once. Check your status after a few minutes to see if changes took place.%3$s', 'increase-upload-max-filesize' ),
		'<code>',
		'</code>',
		'</label>' );

    echo $html;
    }

	/**
	* HTML for text field setting
	* @since 1.0.2
	*/

	public function iumf_textfield_callback($args) {

		$options = get_option( 'inc_upload_max_filesize_options' );
		$key = $args['label'];
		$desc = $args['desc'];

		?><input type="text" id="checkbox_one" name="inc_upload_max_filesize_options[<?php echo $key; ?>]" value="<?php echo $options["$key"]; ?>" />
		<span class='description'><?php echo $desc; ?></span>
<?php
	}

	/**
	* Run only when saving plugin option
	* @param $options, array of newly-entered plugin settings
	* @since 1.0.1
	*/
    public function run_on_save($options){
		$this->ini_rules($options);
	}

	/**
	* Sanitize input fields
	* @since 1.0.1
	* @todo make sure text field values are integers
	*/
	public function sanitize($input){
		$output = array();  
		foreach( $input as $key => $value ) {  
			if( isset( $input[$key] ) ) {  
			$output[$key] = strip_tags( stripslashes( $input[ $key ] ) );  
			}
		}
		$this->run_on_save($output); // run with newly-entered numbers

		return $output;
    }
	   	
	/**
	* Defines the plugin textdomain.
	* @since 1.0
	*/
	public function plugin_textdomain() {
		load_plugin_textdomain( 'increase-upload-max-filesize', false, dirname( plugin_basename( __FILE__ ) ) . '/lang/' );
	}

	/**
	* Set ini rules, conditionally.
	* @param $new, array of newly-entered options, which have not been saved yet when running from Tools screen
	* @since 1.0
	*/
	public function ini_rules( $options = null ) {
		$rules = '';
		$msg = '';

		$iscustom_uploadmaxfilesize = isset($options['textfield_one']) ? $options['textfield_one'] : '';
		$iscustom_postmaxsize = isset($options['textfield_two']) ? $options['textfield_two'] : '';

		// increase 'upload_max_filesize' if too low, or if a custom is entered
		
		$d = ini_get('upload_max_filesize');
		$e = rtrim($d, 'M');
		$f = intval($e);
		
		if ( $iscustom_uploadmaxfilesize ) {
			$rules .= "upload_max_filesize = " . $iscustom_uploadmaxfilesize . "M\n";
			$msg .= sprintf( __( 'Your upload_max_filesize was %s.%s', 'increase-upload-max-filesize' ), $d, '<br />');
		
		} elseif ( $f < 32 ) {
			$rules .= "upload_max_filesize = 32M\n";
			$msg .= sprintf( __( 'Your upload_max_filesize was %s.%s', 'increase-upload-max-filesize' ), $d, '<br />');
		} else {
			$msg .= sprintf( __( 'Your upload_max_filesize was already %s.%s', 'increase-upload-max-filesize' ), $d, '<br />');
		}

		// increase 'post_max_size' if too low, or if a custom is entered
		
		$x = ini_get('post_max_size');
		$y = rtrim($x, 'M');
		$z = intval($y);
		
		if ( $iscustom_postmaxsize ) {
					
			$rules .= "post_max_size = " . $iscustom_postmaxsize . "M\n";
			$msg .= sprintf( __( 'Your post_max_size was %s.%s', 'increase-upload-max-filesize' ), $x, '<br />');
		} elseif ( $z < 33 ) {
			$rules .= "post_max_size = 33M\n";
			$msg .= sprintf( __( 'Your post_max_size was %s.%s', 'increase-upload-max-filesize' ), $x, '<br />');
		} else {
			$msg .= sprintf( __( 'Your post_max_size was already %s.%s', 'increase-upload-max-filesize' ), $x, '<br />');
		}

		
		// update message option
		update_option( 'increase_upload_filesize_msg', $msg );

		if ( phpversion() < 5 ) {
			$error_msg = __( 'Your PHP version is less than 5, so no changes will be made. This plugin only works with PHP version 5 and above. I am sorry. You may deactivate the plugin, now.', 'increase-upload-max-filesize' );
			
		} else {

			// get url of site root.
			$root = $_SERVER["DOCUMENT_ROOT"];

			// use php.ini or php5.ini ?

			$ischecked = isset($options['checkbox_one']) ? $options['checkbox_one'] : '';
			if( 'on' == $ischecked ) $filename = $root . '/php.ini';
			else $filename = $root . '/php5.ini';

			if ( file_exists( $filename ) ) {
	
				// create or append php5.ini file
				// using the FILE_APPEND flag to append the content to the end of the file
				// and the LOCK_EX flag to prevent anyone else writing to the file at the same time
				$editini = file_put_contents( $filename, $rules, FILE_APPEND | LOCK_EX );
					
				if (	false === $editini ) {
		
					// file_put_contents did not work so try fwrite()
							
					if (is_writable($filename)) {
					
					    // open in append mode with file pointer at the bottom of the file
					    if (!$handle = fopen($filename, 'a')) {
					         $error_msg = sprintf( __( 'Cannot open file (%s), so no changes will be made. Please deactivate the plugin and try again. If it still does not work after trying again, then this plugin may not be for you.', 'increase-upload-max-filesize' ), $filename );
					    }
						
					    // Write $rules to our opened file.
					    if (fwrite($handle, $rules) === FALSE) {
					         $error_msg = sprintf( __( 'Cannot write to file (%s), so no changes will be made. Please deactivate the plugin, and try again. If it still does not work after trying again, then ask your web host to grant you access to write to your <code>%s</code> file.', 'increase-upload-max-filesize' ), $filename );
						
						}
					
					    fclose($handle);
						
					} else {
					    $error_msg = "The file $filename is not writable.";
					} // end	if (is_writable($filename)
						
				} // end if (	false === $editini ) 
	
	
			} else { 
			
				// file does not exist so create it
		
				if (!$handlec = fopen($filename, 'a')) {
			         $error_msg = sprintf( __( 'Could not create file (%s), so no changes will be made. Please deactivate the plugin, and try again. If it still does not work after trying again, then this plugin may not be for you.', 'increase-upload-max-filesize' ), $filename );
				}
							
			    if (fwrite($handlec , $rules) === FALSE) {
							       
			         $error_msg = sprintf( __( 'Cannot write to newly created file (%s), so no changes will be made. Please deactivate the plugin, and try again. If it still does not work after trying again, then ask your web host to grant you access to write to your php5.ini file.', 'increase-upload-max-filesize' ), $filename );
						
			    }
			    fclose($handlec);
					
			} // end if (file_exists($filename)

		} // end if ( phpversion() < 5 )

		if ( !empty($error_msg) )
			update_option( 'increase_upload_filesize_msg_err', $error_msg );

	} // end ini_rules

} // end class
}
$Increase_Upload_Max_Filesize = Increase_Upload_Max_Filesize::get_instance();
register_activation_hook(__FILE__, array( 'Increase_Upload_Max_Filesize', 'activate' ) );
register_deactivation_hook(__FILE__, array( 'Increase_Upload_Max_Filesize', 'deactivate' ) );