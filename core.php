<?php
/*
Plugin Name: Custom Code Adder by iWebX
Plugin URI: http://software.iwebx.info/custom-code-adder-wordpress-plugin-by-iwebx
Description: Custom Code Adder by iWebX. Add any code to your website without editing the core files. Easily add HTML, PHP, JavaScript, or almost any other code to your Wordpress site. Codes are stored safely in the database so that when switching themes or updating the plugin your custom code is not lost.
Version: 0.0.5
Author: iWebX
Author URI: http://software.iwebx.info
*/
 
// Init plugin options to white list our options
function iwebx_cca_init(){
	register_setting( 'iwebx_cca_plugin_options', 'iwebx_cca_options', 'iwebx_cca_validate_options' );
}
add_action('admin_init', 'iwebx_cca_init' );
 
 #### Global Values
if (!defined('IWEBXCCA_THEME_DIR'))
    define('IWEBXCCA_THEME_DIR', ABSPATH . 'wp-content/themes/' . get_template());

if (!defined('IWEBXCCA_PLUGIN_NAME'))
    define('IWEBXCCA_PLUGIN_NAME', trim(dirname(plugin_basename(__FILE__)), '/'));

if (!defined('IWEBXCCA_PLUGIN_DIR'))
    define('IWEBXCCA_PLUGIN_DIR', WP_PLUGIN_DIR . '/' . IWEBXCCA_PLUGIN_NAME);

if (!defined('IWEBXCCA_PLUGIN_URL'))
    define('IWEBXCCA_PLUGIN_URL', WP_PLUGIN_URL . '/' . IWEBXCCA_PLUGIN_NAME);
	
#### Custom Globals
$logo = IWEBXCCA_PLUGIN_URL . '/images/logo.png';
 
##### Add plugin admin page
add_action('admin_menu', 'ccaplugin_menu_pages');

function ccaplugin_menu_pages() {
    // Add the top-level admin menu
    $page_title = 'Custom Code Adder Plugin Settings';
    $menu_title = 'Custom Code Adder';
    $capability = 'manage_options';
    $menu_slug = 'ccaplugin-settings';
    $function = 'ccaplugin_settings';
    add_menu_page($page_title, $menu_title, $capability, $menu_slug, $function);

    // Add submenu page with same slug as parent to ensure no duplicates
    $sub_menu_title = 'Code Settings';
    add_submenu_page($menu_slug, $page_title, $sub_menu_title, $capability, $menu_slug, $function);

    // Now add the submenu page for Help
    $submenu_page_title = 'Custom Code Adder Plugin Help';
    $submenu_title = 'Help';
    $submenu_slug = 'ccaplugin-help';
    $submenu_function = 'ccaplugin_help';
    add_submenu_page($menu_slug, $submenu_page_title, $submenu_title, $capability, $submenu_slug, $submenu_function);
	
    // Now add the submenu page for Premim Plugin
    $submenu_page_title = 'Custom Code Adder Donations';
    $submenu_title = 'Donations';
    $submenu_slug = 'ccaplugin-premium';
    $submenu_function = 'ccaplugin_premium';
    add_submenu_page($menu_slug, $submenu_page_title, $submenu_title, $capability, $submenu_slug, $submenu_function);
}

#adding settings link
add_filter('plugin_action_links', 'iwebx_cca_plugin_action_links', 10, 2);

function iwebx_cca_plugin_action_links($links, $file) {
    static $this_plugin;

    if (!$this_plugin) {
        $this_plugin = plugin_basename(__FILE__);
    }

    if ($file == $this_plugin) {
        $settings_link = '<a href="' . get_bloginfo('wpurl') . '/wp-admin/admin.php?page=ccaplugin-settings">Settings</a>';
        array_unshift($links, $settings_link);
    }

    return $links;
}

#Settings page
function ccaplugin_settings() {
    if (!current_user_can('manage_options')) {
        wp_die('You do not have sufficient permissions to access this page.');
    }



// Render the Plugin options form

#function iwebx_cca_render_form() {
	?>
	<div class="wrap" width="50%">
		<div class="icon32" id="icon-options-general"><br></div>
		<h2>Custom Code Adder by iWebX</h2>
		<p><?php settings_fields('iwebx_cca_plugin_options'); ?>
			<?php $options = get_option('iwebx_cca_options'); ?>
			<?php 
			if ($options['textarea_five'] == 'OFF') {
			print "<h2>Checkout our <a href=\"http://software.iwebx.info/wordpress-plugin-creator-by-iwebx\" taerget=\"_blank\">Wordpress Plugin Creator</a> SOFTWARE by iWebX<br />Simply create plugins with a click!</h2>";
			print "Powered link is <font color=\"darkred\"><b>disabled</b></font>. Please concider making a small donation to our plugin. <br />";
			$iwebx_donations = <<<IWEBXDONATIONS
<form action="https://www.paypal.com/cgi-bin/webscr" method="post">
<input type="hidden" name="cmd" value="_s-xclick">
<input type="hidden" name="hosted_button_id" value="YR5GPJAVGBARN">
<input type="image" src="https://www.paypalobjects.com/en_GB/i/btn/btn_donate_LG.gif" border="0" name="submit" alt="PayPal — The safer, easier way to pay online.">
<img alt="" border="0" src="https://www.paypalobjects.com/en_GB/i/scr/pixel.gif" width="1" height="1">
</form>
IWEBXDONATIONS;
			echo $iwebx_donations;
			}
			

			else {
			
			}
			?></p>
		<p style="float: right"><a href="http://software.iwebx.info" target="_blank"><img src="<?php 
		$logo = IWEBXCCA_PLUGIN_URL . '/images/logo.png';
		print $logo;
		?>"></a></p>
		<form method="post" action="options.php">
			<?php settings_fields('iwebx_cca_plugin_options'); ?>
			<?php $options = get_option('iwebx_cca_options'); ?>
			<p class="submit">
			<input type="submit" class="button-primary" value="<?php _e('Save All Custom Codes') ?>" />
			</p>
			<table class="form-table">
				<tr>
					<td>
						<h2>Above post &amp; page content</h2>
						<?php
							$args = array("textarea_name" => "iwebx_cca_options[textarea_one]");
							wp_editor( $options['textarea_one'], "iwebx_cca_options[textarea_one]", $args );
						?>
					</td>
					<td>(Using the_content filter/echo)
					<p>Usefull for advert promotions or any extra code you would like to show above your content posts and pages.</p>
					<td>
				</tr>
				<tr>
					<td>
						<h2>Below post &amp; page content</h2>
						<?php
							$args = array("textarea_name" => "iwebx_cca_options[textarea_two]");
							wp_editor( $options['textarea_two'], "iwebx_cca_options[textarea_two]", $args );
						?>
					</td>
					<td>(Using the_content filter/return)
					<p>Usefull for advert promotions or any extra code you would like to show below your content posts and pages.</p>
					<td>
				</tr>
				<tr>
					<td>
						<h2>Footer</h2>
						<?php
							$args = array("textarea_name" => "iwebx_cca_options[textarea_four]");
							wp_editor( $options['textarea_four'], "iwebx_cca_options[textarea_four]", $args );
						?>
					</td>
					<td> (Using wp_footer action/echo)
					<p>Usefull for advert promotions or any extra code you would like to show in the footer of your website.</p>
					<td>
				</tr>
				<tr>
					<td>
						<h2>Head Code</h2>
						<p><font color="darkred"><b>WARNING:</b></font> <em>Use with caution incorrect code entererd in here may stop your site from responding correctly.</em></p>
						<textarea id="iwebx_cca_options[textarea_three]" name="iwebx_cca_options[textarea_three]" rows="7" cols="150" type='textarea'><?php echo $options['textarea_three']; ?></textarea>
					</td>
					<td> (Using wp_head action)
					<p>Used for insering code and scripts into the head of your website.<br />
					Recomended for experianced coders...<br /></p>
					<p><font color="darkred"><b>WARNING:</b></font> <em>Use with caution incorrect code entererd in here may stop your site from responding correctly.</em></p>
					<td>
				</tr>
				<tr>
					<td>
						<h2>Powered link</h2>
						<p><font color="darkred"><b>To remove 'Powered' link:</b></font> <em>Just type OFF in the box.</em></p>
						<textarea id="iwebx_cca_options[textarea_five]" name="iwebx_cca_options[textarea_five]" rows="1" cols="5" type='textarea'><?php echo $options['textarea_five']; ?></textarea>
					</td>
					<td>&nbsp;
					<td>
				</tr>
			</table>
			<p class="submit">
			<input type="submit" class="button-primary" value="<?php _e('Save All Custom Codes') ?>" />
			</p>
		</form>
	</div>
			<?php settings_fields('iwebx_cca_plugin_options'); ?>
			<?php $options = get_option('iwebx_cca_options'); ?>
			<?php 
			if ($options['textarea_five'] == 'OFF') {
			print "<h2>Checkout our <a href=\"http://software.iwebx.info/wordpress-plugin-creator-by-iwebx\" taerget=\"_blank\">Wordpress Plugin Creator</a> SOFTWARE by iWebX<br />Simply create plugins with a click!</h2>";
			print "Powered link is <font color=\"darkred\"><b>disabled</b></font>. Please concider making a small donation to our plugin. <br />";
			$iwebx_donations = <<<IWEBXDONATIONS
<form action="https://www.paypal.com/cgi-bin/webscr" method="post">
<input type="hidden" name="cmd" value="_s-xclick">
<input type="hidden" name="hosted_button_id" value="YR5GPJAVGBARN">
<input type="image" src="https://www.paypalobjects.com/en_GB/i/btn/btn_donate_LG.gif" border="0" name="submit" alt="PayPal — The safer, easier way to pay online.">
<img alt="" border="0" src="https://www.paypalobjects.com/en_GB/i/scr/pixel.gif" width="1" height="1">
</form>
IWEBXDONATIONS;
			echo $iwebx_donations;
			}
			

			else {
			print "<h2><font color=\"darkgreen\">You ROCK!</font> Thank you for displaying our 'Powered' link</h2>";
			}
			?>
	<p style="text-align: center"><a href="http://software.iwebx.info" target="_blank"><img src="<?php 
		$logo = IWEBXCCA_PLUGIN_URL . '/images/logo.png';
		print $logo;
		?>"></a></p>
	<?php	
}

function ccaplugin_help() {
    if (!current_user_can('manage_options')) {
        wp_die('You do not have sufficient permissions to access this page.');
    }

    // Render the HTML for the Help page or include a file that does
	?>
	<div class="wrap">
		<div class="icon32" id="icon-options-general"><br></div>
		<h2>Custom Code Adder Help</h2>
		<h3>Our contact details for our help &amp; support!</h3>
		<p>Help and Support: pluginhelp@iwebx.info<br />
		Premium Plugins and Software: premium@iwebx.info<br />
		If you think you have found a bug: bugs@iwebx.info<br />
		Suggestions for updates, new plugins or software: suggest@iwebx.info</p>
		<p>Please also include your version number (FREE v0.0.2) that you are using.</p>
		<p>We will usually answer emails within 48 hours (please be paitent as we dont have many staff.</p>
		<p style="text-align: center"><a href="http://software.iwebx.info" target="_blank"><img src="<?php 
		$logo = IWEBXCCA_PLUGIN_URL . '/images/logo.png';
		print $logo;
		?>"></a></p>
		
	</div>
	<?php
}

function ccaplugin_premium() {
    if (!current_user_can('manage_options')) {
        wp_die('You do not have sufficient permissions to access this page.');
    }

    // Render the HTML for the Premium Plugin page or include a file that does
	?>
	<div class="wrap">
		<div class="icon32" id="icon-options-general"><br></div>
		<h2>Custom Code Adder Donations</h2>
		<p>Please concider donation to the progress of this plugin...</p>
		<?php
		$iwebx_donations = <<<IWEBXDONATIONS
<form action="https://www.paypal.com/cgi-bin/webscr" method="post">
<input type="hidden" name="cmd" value="_s-xclick">
<input type="hidden" name="hosted_button_id" value="YR5GPJAVGBARN">
<input type="image" src="https://www.paypalobjects.com/en_GB/i/btn/btn_donate_LG.gif" border="0" name="submit" alt="PayPal — The safer, easier way to pay online.">
<img alt="" border="0" src="https://www.paypalobjects.com/en_GB/i/scr/pixel.gif" width="1" height="1">
</form>
IWEBXDONATIONS;
			echo $iwebx_donations;
		?>
		<?php settings_fields('iwebx_cca_plugin_options'); ?>
			<?php $options = get_option('iwebx_cca_options'); ?>
			<?php 
			if ($options['textarea_five'] == 'OFF') {
			print "<h2>Checkout our <a href=\"http://software.iwebx.info/wordpress-plugin-creator-by-iwebx\" taerget=\"_blank\">Wordpress Plugin Creator</a> SOFTWARE by iWebX<br />Simply create plugins with a click!</h2>";
			print "Powered link is <font color=\"darkred\"><b>disabled</b></font>. Please concider making a small donation to our plugin. <br />";
			}
			
			else {
			print "<h2><font color=\"darkgreen\">You ROCK!</font> Thank you for displaying our 'Powered' link</h2>";
			}
			?>
	</div>
	<?php
}

#}
 
// Sanitize and validate input. Accepts an array, return a sanitized array.
function iwebx_cca_validate_options($input) {
	// Sanitize textarea input (strip html tags, and escape characters)
	//$input['textarea_one'] = wp_filter_nohtml_kses($input['textarea_one']);
	//$input['textarea_two'] = wp_filter_nohtml_kses($input['textarea_two']);
	//$input['textarea_three'] = wp_filter_nohtml_kses($input['textarea_three']);
	return $input;
}

// Functions
# head
function iwebx_cca_head_func($options) {
$options = get_option('iwebx_cca_options');
echo ''.$options['textarea_three'].'';
}

#content
function iwebx_cca_content_func($contentcode1) {
$options = get_option('iwebx_cca_options');
echo ''.$options['textarea_one'].'';

	if (!is_page()&&!is_feed()) {
		$options = get_option('iwebx_cca_options');
		$contentcode1 .= ''.$options['textarea_two'].'';
		return $contentcode1;

	}
	else {
		$options = get_option('iwebx_cca_options');
		$contentcode1 .= ''.$options['textarea_two'].'';
		return $contentcode1;
	}
	
}

#content home
function iwebx_cca_home_content_func($contentcode1) {
$options = get_option('iwebx_cca_options');
echo ''.$options['textarea_one'].'';

	if (!is_page()&&!is_feed()) {
		$options = get_option('iwebx_cca_options');
		$contentcode1 .= '';
		return $contentcode1;

	}
	else {
		$options = get_option('iwebx_cca_options');
		$contentcode1 .= '';
		return $contentcode1;
	}
	
}

#footer
function iwebx_cca_footer_func($options) {
$options = get_option('iwebx_cca_options');
echo ''.$options['textarea_four'].'';
}

#footer2
function iwebx_cca_footer_func2($options) {
$options = get_option('iwebx_cca_options');
if ($options['textarea_five'] == 'OFF') {
}
else {
echo '<br /><div style="float:left"><span style="color: #c0c0c0;">Powered with</span>
<a title="Custom Code Adder by iWebX" href="http://software.iwebx.info/custom-code-adder-wordpress-plugin-by-iwebx" target="_blank" style="text-decoration=none">Custom Code Adder</a></div>'; // *** Do not remove our 'Powered' code *** >>> You can disable from setting page !!!
}
}

function iwebx_cca_query($query) {
	if ( !is_front_page() ) {
		return add_filter('the_content', 'iwebx_cca_content_func', 1);
    }
	else {
		return;
	}

}

// Action Hooks
add_action('wp_head', 'iwebx_cca_head_func', 1);
add_action('wp_footer', 'iwebx_cca_footer_func', 1);
add_filter( 'pre_get_posts', 'iwebx_cca_query' );

add_action('wp_footer', 'iwebx_cca_footer_func2', 2);


?>