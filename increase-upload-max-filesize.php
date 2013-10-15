<?php
/*
Plugin Name: Increase Upload Max Filesize
Plugin URI: http://smartestthemes.com/downloads/increase-upload-max-filesize-wp-plugin/
Description: Increases your website's upload max filesize limit on your server by adding rules to php5.ini.
Version: 1.0
Author: Smartest Themes
Author URI: http://smartestthemes.com
License: GPL2
Text Domain: inc-upload-max-filesize

Copyright 2013 Smartest Themes(email : isa@smartestthemes.com)

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

	public function __construct() {
		add_action( 'init', array( $this, 'plugin_textdomain' ) );
		add_action( 'admin_notices', array( $this, 'notice' ) );
	 }

	/** 
	* Only upon plugin activation, set ini rules
	* @since 1.0
	*/
	public static function activate() { 
		self::ini_rules();
	}

	/**
	* Defines the plugin textdomain.
	* @since 1.0
	*/
	public function plugin_textdomain() {
		$domain = 'inc-upload-max-filesize';
		$locale = apply_filters( 'inc-upload-max-filesize', get_locale(), $domain );
		load_textdomain( $domain, WP_LANG_DIR . '/' . $domain . '/' . $domain . '-' . $locale . '.mo' );
		load_plugin_textdomain( $domain, FALSE, dirname( plugin_basename( __FILE__ ) ) . '/lang/' );
	}

	/**
	* Set ini rules, conditionally.
	* @since 1.0
	*/
	public function ini_rules() {
	
		$rules = '';
		$msg = __( 'The Increase Upload Max Filesize plugin has completed its job.', 'inc-upload-max-filesize' ) . '<br />';
		
		// increase 'memory_limit' if too low
		$a = ini_get('memory_limit');
		$b = rtrim($a, 'M');
		$c = intval($b);
		if ( $c < 64 ) {
			$rules .= "set memory_limit = 64M\n";
			$msg .= sprintf( __( 'Your memory_limit was %s and will be set to 64M.%s', 'inc-upload-max-filesize' ), $a, '<br />');
		} else {
			$msg .= sprintf( __( 'Your memory_limit is already %s so it will not be changed.%s', 'inc-upload-max-filesize' ), $a, '<br />');
		}
		
		// increase 'upload_max_filesize' if too low
		
		$d = ini_get('upload_max_filesize');
		$e = rtrim($d, 'M');
		$f = intval($e);
		if ( $f < 32 ) {
			$rules .= "upload_max_filesize = 32M\n";
			$msg .= sprintf( __( 'Your upload_max_filesize was %s and will be set to 32M.%s', 'inc-upload-max-filesize' ), $d, '<br />');
		} else {
			$msg .= sprintf( __( 'Your upload_max_filesize is already %s so it will not be changed.%s', 'inc-upload-max-filesize' ), $d, '<br />');
		}
	
		// increase 'post_max_size' if too low
		
		$x = ini_get('post_max_size');
		$y = rtrim($x, 'M');
		$z = intval($y);
		if ( $z < 33 ) {
			$rules .= "post_max_size = 33M\n";
			$msg .= sprintf( __( 'Your post_max_size was %s and will be set to 33M.%s', 'inc-upload-max-filesize' ), $x, '<br />');
		} else {
			$msg .= sprintf( __( 'Your post_max_size is already %s so it will not be changed.%s', 'inc-upload-max-filesize' ), $x, '<br />');
		}
		
		$msg .= sprintf( __( '%s Please allow some time for your server to recognize any changes. %s You may deactivate the plugin, now, since its job is complete.', 'inc-upload-max-filesize' ), '<br />', '<br /><br />' );

		// update message option here
		update_option( 'increase_upload_filesize_msg', $msg );
		

		if ( phpversion() < 5 ) {
			$msg = __( 'Your PHP version is less than 5, so no changes will be made. You may deactivate the plugin, now.', 'inc-upload-max-filesize' );
			
		} else {

			// get url of site root.
			$root = $_SERVER["DOCUMENT_ROOT"];
			$filename = $root . '/php5.ini';

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
					         $msg = sprintf( __( 'Cannot open file (%s), so no changes will be made. Please deactivate the plugin, and try again. If it still does not work after trying again, then this plugin may not be for you.', 'inc-upload-max-filesize' ), $filename );
						         
					    }
						
					    // Write $rules to our opened file.
					    if (fwrite($handle, $rules) === FALSE) {
					         $msg = sprintf( __( 'Cannot write to file (%s), so no changes will be made. Please deactivate the plugin, and try again. If it still does not work after trying again, then ask your web host to grant you access to write to your php5.ini file.', 'inc-upload-max-filesize' ), $filename );
						
						}
						
						$msg = get_option( 'increase_upload_filesize_msg', $msg );
						
					    fclose($handle);
						
					} else {
					    $msg = "The file $filename is not writable.";
					} // end	if (is_writable($filename)
						
				} // end if (	false === $editini ) 
	
	
			} else { 
			
				// file does not exist so create it
		
				if (!$handlec = fopen($filename, 'a')) {
			         $msg = sprintf( __( 'Could not create file (%s), so no changes will be made. Please deactivate the plugin, and try again. If it still does not work after trying again, then this plugin may not be for you.', 'inc-upload-max-filesize' ), $filename );
				}
							
			    if (fwrite($handlec , $rules) === FALSE) {
							       
			         $msg = sprintf( __( 'Cannot write to newly created file (%s), so no changes will be made. Please deactivate the plugin, and try again. If it still does not work after trying again, then ask your web host to grant you access to write to your php5.ini file.', 'inc-upload-max-filesize' ), $filename );
						
			    }
							
				$msg = get_option( 'increase_upload_filesize_msg', $msg );
						
			    fclose($handlec);
					
			} // end if (file_exists($filename)

		} // end if ( phpversion() < 5 )

		update_option( 'increase_upload_filesize_msg', $msg );

	} // end ini_rules

	/*
	* Admin notice output.
	* @since 1.0
	*/

	public function notice() {
		$html = '<div class="updated">';
			$html .= '<p>';
				$html .= get_option( 'increase_upload_filesize_msg' );
			$html .= '</p>';
		$html .= '</div>';
		echo $html;
	}

} // end class
}
$Increase_Upload_Max_Filesize = new Increase_Upload_Max_Filesize();
register_activation_hook(__FILE__, array( 'Increase_Upload_Max_Filesize', 'activate' ) );