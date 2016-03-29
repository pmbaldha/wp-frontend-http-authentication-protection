<?php
/*
Plugin Name: Frontend HTTP Authentication Protection for Wordpress
Plugin URI:  https://1stphp.wordpress.com/
Description: Frontend HTTP Auth Protection for Wordpress provides simple HTTP authentication layer to your wordpress frontend site. There is often need to prevent normal visitors and search engine from navigating front website, when website development in development environment before release website in production environment. This plugin serves this need very well by adding HTTP authentication layer to frontend site. This wordpress plugin is fully configurable from admin panel. 
Version:     0.1
Author:      Prashant Baldha
Author URI:  https://1stphp.wordpress.com/
License:     Private
*/

/**
 * This function and hook set default value of setting
 * This function will run when plugin will activated	
 */
register_activation_hook( __FILE__, 'fhauth_activate');
function fhauth_activate() {
	if( get_option( 'fhauth_setting' , false) === false ) {
		add_option( 'fhauth_setting', 
					array(
							'enabled' => '1',
							'username'	=> 'user',
							'password'	=> 'user123',
							'dialog_message' => 'Please enter username and password to access this web site',
							'error_content' => '<h1>Unauthorized</h1>',					
						)
					);
	}
}


// This is for setting up HTTP Authentication at fronend
add_action( 'send_headers', 'fhauth_authenticate' );
function fhauth_authenticate() {
    // HTTP Authentication require only for frontend site
    // This code prevent HTTP authentication from admin panel
    if(is_admin()) {
        return true;
    }
    
    // Get configuration which was already saved or default saved when activated the plugin
    $fhauth_options = get_option('fhauth_setting');
    
    // If configured as disabled http auth, this code will prevent from prompting login
    if( isset( $fhauth_options['enabled'] ) && $fhauth_options["enabled"] != '1' ) {
        return true;
    }

    // Get variable values from configuration which require in HTTP Basic Authentication
    $user = $fhauth_options['username'];
    $password = $fhauth_options['password'];
    if( isset($fhauth_options['dialog_message'])) {
        $dialog_message  = $fhauth_options['dialog_message'];
    } else {
        $dialog_message  = '';
    }
	
    // Check user have already logged in as per credential
    if (!( isset($_SERVER['PHP_AUTH_USER']) && $_SERVER['PHP_AUTH_USER'] == $user && isset($_SERVER['PHP_AUTH_PW']) && $_SERVER['PHP_AUTH_PW'] == $password)) {
        header('WWW-Authenticate: Basic realm="'.$dialog_message.'"');
        header('HTTP/1.0 401 Unauthorized');
        // Show error content when user click on cancel button of login prompt
        // When set error content from admin site
        if( isset($fhauth_options["error_content"]) ) {
            echo $fhauth_options["error_content"];
        } else {
			//Default error content
            echo '<h1>Unauthorized Access</h1>';
        }
        exit;
    }
}


// This function is for adding plugin setting configuration page under setting in wordpress admin panel
add_action('admin_menu', 'fhauth_add_setting_page');
function fhauth_add_setting_page() {                        
    add_options_page('Frontend HTTP Authentication', 'Frontend HTTP Authentication', 'manage_options', 'fhauth_setting', 'fhauth_options_page'); //Add setting page in wordpress admin panel
}

// This function is for setting content of setting content of setting page
function fhauth_options_page() {
?>
	<div class="wrap">
		<h1>Frontend HTTP Authentication Protection</h1>
		<form method="POST" action="options.php">
			<?php settings_fields( 'fhauth_setting' );	//pass slug name of page, also referred
													    //to in Settings API as option group name
			do_settings_sections( 'fhauth_setting' ); 	//pass slug name of page
			submit_button();
			?>
		</form>
	</div>
<?php
}
 // This function and hook are registering setting section, setting and all setting field
 add_action( 'admin_init', 'fhauth_settings_api_init' );
 function fhauth_settings_api_init() {
 	// Add the section to fhauth_setting setting page so we can add our
 	// fields to it
 	add_settings_section(
		'fhauth_setting_section',
		'Configure Frontend HTTP Authentication Protection',
		'fhauth_setting_section_callback_function',
		'fhauth_setting'
	);
 	
	// Register our setting so that $_POST handling is done for us and our configuration will be saved
 	register_setting( 'fhauth_setting', 'fhauth_setting' );
	
 	// Add Enable HTTP Auth field in Frontend HTTP Authentication Protection Setting page 
 	add_settings_field(
		'fhauth_enabled',
		'Enable HTTP Auth',
		'fhauth_enabled_callback_function',
		'fhauth_setting',
		'fhauth_setting_section'
	); 	
	
 	// Add HTTP Auth User Name field in Frontend HTTP Authentication Protection Setting page 
 	add_settings_field(
		'fhauth_username',
		'HTTP Auth User Name',
		'fhauth_username_callback_function',
		'fhauth_setting',
		'fhauth_setting_section'
	);
	
	// Add HTTP Auth Password field in Frontend HTTP Authentication Protection Setting page 
 	add_settings_field(
		'fhauth_password',
		'HTTP Auth Password',
		'fhauth_password_callback_function',
		'fhauth_setting',
		'fhauth_setting_section'
	);

	// Add HTTP Auth Dialog Message field in Frontend HTTP Authentication Protection Setting page 
 	add_settings_field(
		'fhauth_dialog_message',
		'HTTP Auth Dialog Message',
		'fhauth_dialog_message_callback_function',
		'fhauth_setting',
		'fhauth_setting_section'
	);
	
	// Add HTTP Auth Error Content field in Frontend HTTP Authentication Protection Setting page 
	add_settings_field(
		'fhauth_error_content',
		'HTTP Auth Error Content',
		'fhauth_error_content_callback_function',
		'fhauth_setting',
		'fhauth_setting_section'
	);
 }
 
 // fhauth_setting_section callback function
 // This function is needed if we added a new section. This function 
 // will be run at the start of our section
 function fhauth_setting_section_callback_function() {
 	echo '<p>The below setting will be configured for front side http authentication</p>';
 }
 
 // Callback function for Enable HTTP Auth field
 // This function renders an enabled checkbox true/false option.  
 function fhauth_enabled_callback_function() {
 	//echo '<input name="eg_setting_name" id="eg_setting_name" type="checkbox" value="1" class="code" ' . checked( 1, get_option( 'eg_setting_name' ), false ) . ' /> Explanation text';
	$options = get_option("fhauth_setting");
	if(isset($options['enabled'])) {
		$enabled = $options['enabled'];
	} else {
		$enabled = false;
	}	
	echo '<label><input name="fhauth_setting[enabled]" id="fhauth_enabled" type="checkbox" value="1" class="code" ' . checked( 1, $enabled, false ) . ' /> Yes</label>
		<p id="enabled-description" class="description">Please enable or disable front side HTTP authentication.</p>';
 }
 
 // Callback function for Enable HTTP User Name
 // This function renders a textbox
function fhauth_username_callback_function() {
	$options = get_option('fhauth_setting');
	if(isset($options['username']))	{
		$username = $options['username'];
	} else {
		$username = '';
	}
	echo '<input name="fhauth_setting[username]" id="fhauth_username" type="text" value="' . $username. '" class="code" />';
}
 
 // Callback function for Enable HTTP Password
 // This function renders a textbox
function fhauth_password_callback_function() {
	$options = get_option('fhauth_setting');
	if(isset($options['password']))	{
		$password = $options['password'];
	} else {
		$password = '';
	}
	echo '<input name="fhauth_setting[password]" id="fhauth_username" type="text" value="' . $password. '" class="code"  />';
}

// Callback function for HTTP Auth Dialog Message
// This function renders a long sized textbox 
function fhauth_dialog_message_callback_function() {
	$options = get_option('fhauth_setting');
	if(isset($options['dialog_message'])) {
		$dialog_message = $options['dialog_message'];
	} else {
		$dialog_message = '';
	}
	echo '<input name="fhauth_setting[dialog_message]" id="fhauth_dialog_message" type="text" value="' . $dialog_message. '" class="regular-text code" /> 
			<p id="dialog_message-description" class="description">This message will be displayed in login prompt. It is up to browser to display this message.';
}

// Callback function for HTTP Auth Error Content
// This function renders a WYSIWYG editor
function fhauth_error_content_callback_function() {
	$options = get_option('fhauth_setting');
	if(isset($options['error_content'])) {
		$error_content = $options['error_content'];
	} else {
		$error_content = '';
	}
	wp_editor( $error_content, 
				'error_content',
				array(
						'wpautop' => false, 
						'textarea_name' => 'fhauth_setting[error_content]',
						'textarea_rows' => 7,
					)
			);
	echo '<p id="error_content-description" class="description">This error content will be displayed when user click on cancel button in login prompt.</p>';
 }
 
 