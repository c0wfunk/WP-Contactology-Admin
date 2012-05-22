<?php
/*
 Plugin Name: WP Contactology Admin
 Description: Creates account creation and management system for Contactology. Branched from WP Cakemail 0.91. Customized for All Star Cheer Sites
 Version: 0.921
 Author: Jeremy Ferguson
 */
 
//requires Contactology wrapper functions
if ( !class_exists('Contactology') ) 
	require "lib/class.Contactology.php";
	
if ( !class_exists('Contactology_Admin') ) 
	require "lib/class.Contactology_Admin.php";
	
require "lib/cmb_custom_meta_boxes.php";	
	
if ( !class_exists('WP_Contactology_Post_Type') ) 
	require "lib/class.WP_Contactology_Post_Type.php";		

 
//define wpmu paths if not already defined 
if ( ! defined( 'WP_PLUGIN_URL' ) )
       define( 'WP_PLUGIN_URL', WP_CONTENT_URL. '/plugins' );
if ( ! defined( 'WP_PLUGIN_DIR' ) )
       define( 'WP_PLUGIN_DIR', WP_CONTENT_DIR . '/plugins' );


/*
	Contactology Class
*/

class WP_Contactology_Admin {

	private $Settings; //hold settings object
	private $api_key; //admin API key
	private $c; //holds Contactology object
	private $c_admin; //holds Contactology Admin object
	
		
	/**
	 * Add to wordpress hooks
	 *
	 * @author Jeremy Ferguson
	 * @package wpcntlgy
	 * @since 0.9
	**/
	function __construct () {
		// Initiate settings object
		if (class_exists("WP_Contactology_Settings")) 
			$this->Settings = new WP_Contactology_Settings();
		//setup admin API key
		$this->Settings->get_options();
		$this->api_key = $this->Settings->options['contactology_api_key'];
		
		//initialize Contactology objects
		if ( class_exists('Contactology') ) 
			$this->c = new Contactology( $this->api_key );
		if ( class_exists('Contactology_Admin') ) 	
			$this->c_admin = new Contactology_Admin( $this->api_key );
				
		//add ajax handler for admin screen		
		add_action('wp_ajax_wp_contactology_admin', array(&$this, 'admin_ajax_handler') );
			
		//add admin menu function and init for script
		add_action( 'admin_init', array(&$this, 'admin_init'));
		add_action('admin_menu', array(&$this, 'admin_menu'));
	}
		
	/**
	 * handle admin ajax logic
	 *
	 * @author Jeremy Ferguson
	 * @package wpcntlgy
	 * @since 0.9
	**/
	function admin_ajax_handler() {
			
		
		//for the response
		$response = array();
		
		//check on type of operation
		switch( $_POST['type']){
		
			case 'client_setup': 
				
				$response['message'] = $this->client_setup( $_POST );
				
				break;
			
			//get the lists and send 'em back
			case 'get_lists':
				
				$response['message'] = $this->get_lists( $_POST );
				
				break;
			//copy the folder that was set
			case 'copy_trigger':
				
				$response['message'] = $this->copy_trigger( $_POST ) ;
				
				break;
		}
		
		$arr = $response;
		
		$response =  json_encode($arr);		
		//header for json response
		header( "Content-Type: application/json" );
		echo $response;
		
		die();
	}
	
	/**
	 * creates new client
	 *
	 * @author Jeremy Ferguson
	 * @package wpcntlgy
	 * @since 0.9
	 * @param $entry, $form
	**/
	
	function client_setup( $_POST ) {
		 
		//$campaign_list = $this->c->Campaign_Find();
			
		//gather data for new client
		$clientName = $_POST['client_name'];
		$adminEmail = $_POST['client_email'];
		$userName = $_POST['client_username'];
		$password = $_POST['client_password'];
		$optionalParameters = array( 
			'accountType' => 'BLOCK_SENDS'
		);
		
		//create new client, checking for exceptions
		try { 
			$campaign_list = $this->c_admin->Admin_Create_Account_Minimal( $clientName, $adminEmail, $userName,  $password, $optionalParameters );
		}
		catch(Exception $e) {
			$campaign_list = $e->getMessage();
		}
		
		return $campaign_list;
	}
	
	/**
	 * get account list with API keys and return checkbox list
	 * 
	 * @author Jeremy Ferguson
	 * @package wpcntlgy
	 * @since 0.92
	 * @return $apikey_list_output
	**/
	
	function account_checklist(  ) {

		//get account checklist, catch exceptions
		try { 
			$account_list = $this->c_admin->Admin_Get_Accounts();
		}
		catch(Exception $e) {
			return $e->getMessage();
		}
		
		$apikey_array = array();
		
		$apikey_list_output .= '<div id="wp_contactology_current_client_checklist">';
		
		foreach( $account_list as $clientID => $clientName ) {
			$apikey_array[$clientID] = $this->c_admin->Admin_Get_Account_Key( $clientID );
			
			$apikey_list_output .= "<div class='{$apikey_array[$clientID]}'>";
			
			$apikey_list_output .= "<input type='checkbox' name='clientapikey' value='{$apikey_array[$clientID]}' id='{$apikey_array[$clientID]}'/> ";
			$apikey_list_output .= "<input type='hidden' name='clientid' value='{$clientID}' /> ";
			$apikey_list_output .= "<label for='{$apikey_array[$clientID]}'>$clientName</label></div>";
		}
		
		return $apikey_list_output;
		
	}
	
	/**
	 * Get active mailing lists and return as select dropdown
	 * 
	 * @author Jeremy Ferguson
	 * @package wpcntlgy
	 * @since 0.92
	**/
	function get_lists( $_POST ) {
		
		if ( class_exists('Contactology') ) 
			$c_current = new Contactology( $_POST['api_key'] );
			
		$active_lists = $c_current->List_Get_Active_Lists();
		
		$response .= ' <select>';
		//make an option out of each one
		foreach($active_lists as $list) {
			$response .= '<option value="'. $list['listId'] .'">' . $list['name'] . '</option>';
		}
		$response .= '</select>';
		
		//add feedback icons
		$response .='<span class="success" style="display:none;"><img src="/wp-admin/images/yes.png" /> </span>';
		$response .= '<span class="fail" style="display:none;"><img src="/wp-admin/images/no.png" />  </span>';
					
				
		return $response;
	}


	/**
	 * Copy campaign
	 *
	 * @author Jeremy Ferguson
	 * @package wpcntlgy
	 * @since 0.9
	**/
	function copy_trigger( $_POST ) {
		
		//gather the variables from $_POST
		$campaign_id = $_POST['folder_id'];
		$campaign_name = $_POST['folder_name'];
		$client_apikey = $_POST['client_apikey'];
		$client_id = $_POST['client_id'];
		$listId = $_POST['list'];
	
		//create object with selected API key
		$Contactology_Object = new Contactology( $client_apikey );
		
		//get account details for sender data 
		$account_details = $this->c_admin->Admin_Get_Account_Info( $client_id );
		
		//get campaign details and custom fields
		$campaign_details = wp_get_single_post( $campaign_id );
		$custom_fields = get_post_custom( $campaign_id );
				
		//Copy trigger, catch exceptions
		try { 
			
			$Contactology_Object->Campaign_Create_Triggered_On_List_Subscription( 
				$listId, 
				$custom_fields['_wpcntctlgyadmin_timeType'][0],
				$custom_fields['_wpcntctlgyadmin_timeValue'][0],
				$campaign_details->post_title,
				$custom_fields['_wpcntctlgyadmin_subject'][0], 
				$account_details['adminEmail'],
				$account_details['clientBusinessName'],	
				array( 'html' => apply_filters( 'the_content',$campaign_details->post_content ) )
			);
			
		}
		catch(Exception $e) {
			return 'Fail &mdash; ' . $e->getMessage();
		}
		
		return 'Success';
	}
	
	/**
	 * Assemble campaign select menu
	 *
	 * @author Jeremy Ferguson
	 * @package wpcntlgy
	 * @since 0.921
	 * @return $campaign_select
	**/
	function campaign_select() {
		$campaign_select .= '<select id="wp_contactology_admin_campaigns" name="wp_contactology_admin_campaigns">';
		$campaign_select .= '<option value="0">Select a Folder</option>';
		
		$args = ( array( 
			'post_type' => 'campaign',
			'posts_per_page' => -1,
			'orderby' => 'title',
			'order' => 'ASC'
		));
		
		$campaign_query = new WP_Query( $args );
		
		while ( $campaign_query->have_posts() ) : $campaign_query->the_post();

			$campaign_select .= '<option value="' . get_the_ID() . '">' . get_the_title() . '</option>';
			
		endwhile;
		
		wp_reset_postdata();
		
		$campaign_select .= '</select>';
		return $campaign_select;
	}
	
	/**
	 * register javascript file
	 *
	 * @author Jeremy Ferguson
	 * @package wpcntlgy
	 * @since 0.9
	**/	
	function admin_init() {
        /* Register our script. */
		wp_register_script('wp_contactology_admin_js', WP_PLUGIN_URL . '/wpcontactologyadmin/js/wpcontactologyadmin.js',
			array('jquery'));
    }
	
	/**
	 * add options page to plugin menu
	 *
	 * @author Jeremy Ferguson
	 * @package wpcntlgy
	 * @since 0.9
	**/	
	function admin_menu() {
		$page = add_submenu_page(
			'edit.php?post_type=campaign',
			'Account Admin', 
			'Account Admin', 
			'manage_options', 
			'wpcontactologyacctadmin', 
			array(&$this, 'account_admin_panel'
			)
		);
		
		add_action('admin_print_scripts-' . $page, array(&$this, 'admin_script') );
	}
	
	/**
	 * create admin panel
	 * 
	 * @author Jeremy Ferguson
	 * @package wpcntlgy
	 * @since 0.9
	**/	
	function account_admin_panel() {
		
		if (!current_user_can('manage_options'))  {
			wp_die( __('You do not have sufficient permissions to access this page.') );
		}
		
		//get the account checklist to use in the forms
		$account_checklist = $this->account_checklist();
		//get the campaign dropdown menu
		$campaign_select = $this->campaign_select();
?>		
		<h3>Create new account:</h3>
		<form id="wp_contactology_client_setup_form">
			<ul>
				<li><label for="wp_contactology_admin_client_name">Client Name</label><br/>
				
					<input id="wp_contactology_admin_client_name" name="wp_contactology_admin_client_name" />
					
				
				</li>
				<li><label for="wp_contactology_admin_email">Admin Email Address</label><br/>
				
					<input id="wp_contactology_admin_email" name="wp_contactology_admin_email" />
				
				</li>
				<li><label for="wp_contactology_admin_username">Username (must be unique to system)</label><br/>
				
					<input id="wp_contactology_admin_username" name="wp_contactology_admin_username" />
				
				</li>
				<li><label for="wp_contactology_admin_password">Password (min. 6 characters)</label><br/>
				
					<input id="wp_contactology_admin_password" name="wp_contactology_admin_password" />
				</li>
				<li>
					<button id="wp_contactology_new_client_submit">Create Account</button>
					<div id="loading" style="display:none; width:100%; height:100%; ">
						<p><img src="/wp-admin/images/loading.gif" /> Please Wait</p>
					</div>
					<div id="wp_contactology_client_setup_results"></div>
				</li>
			</ul>
		</form>
		
		<h3>Copy Autoresponders</h3>
		<form id="wp_contactology_copy_trigger_form">
			<ul>
				<li><label for="fname">Select campaign to copy: </label><br/>
					<?php echo $campaign_select; ?>
					
				
				</li>
				<li><label for="fname">Select client to receive campaign, then select list to copy folder to:</label><br/>
					<?php echo $account_checklist; ?>
					
				</li>
				<li>
					<button id="wp_contactology_copy_trigger_submit">Copy</button>
					<div id="loading" style="display:none; width:100%; height:100%; ">
						<p><img src="/wp-admin/images/loading.gif" /> Please Wait</p>
					</div>
					<div id="wp_contactology_copy_trigger_results"></div>
				</li>
			</ul>
		</form>
		
		<div><strong>Please reload this page after each campaign copy!</strong></div>
<?php 
	}
	
	/**
	 * enqueue admin javascript
	 *
	 * @author Jeremy Ferguson
	 * @package wpcntlgy
	 * @since 0.9
	**/	
	function admin_script() {
		wp_enqueue_script( 'wp_contactology_admin_js' );
	}
}


/**
 * WP_Contactology_Settings Class
 * 
 * @author Jeremy Ferguson
 * @package wpdbsb
 * @since 0.8
 * 
**/
class WP_Contactology_Settings {
	
	private $settings_group;
	private $settings_page;
	private $settings_section;
	public $options;


	function __construct() {
		
		//setup variables for settings screens
		$this->Settings_group = 'wpcntctadmn_plugin_settings';
		$this->Settings_page = 'wpcntctadmn_plugin_settings_section';
		$this->Settings_section = 'wpcntctadmn_plugin_main';
		
		//add menu and init actions
		add_action('admin_menu', array(&$this, 'admin_menu'));
		add_action( 'admin_init', array( &$this, 'admin_init' ) );
	} 
	
	/**
	 * add options page to plugin menu
	 *
	 * @author Jeremy Ferguson
	 * @package wpdbsb
	 * @since 0.8
	**/	
	function admin_menu() {
		$page = add_submenu_page(
			'edit.php?post_type=campaign',
			'Settings', 
			'Settings', 
			'manage_options', 
			'wpcontactologyadmin', 
			array(&$this, 'admin_panel')
		);
	}
		
	/**
	 * create admin panel
	 *
	 * @author Jeremy Ferguson
	 * @package wpdbsb
	 * @since 0.8
	**/	
	function admin_panel() {
		if (!current_user_can('manage_options'))  {
			wp_die( __('You do not have sufficient permissions to access this page.') );
		}
		
		?>
		<div class="wrap">
		<h2>WP Contactology Admin</h2>

		<form action="options.php" method="post">
		<?php settings_fields( $this->Settings_group ); ?>
		<?php do_settings_sections( $this->Settings_page ); ?>

		<input name="Submit" type="submit" value="<?php esc_attr_e('Save Changes'); ?>" />
		</form>
		</div>
		<?php 
	}
	
	/**
	 * register settings
	 *
	 * @author Jeremy Ferguson
	 * @package wpdbsb
	 * @since 0.8
	**/	
	function admin_init() {
		
		//register settings array and add settings section
		register_setting( $this->Settings_group , $this->Settings_group, array(&$this, 'plugin_settings_validate') );
		add_settings_section( $this->Settings_section, 'API Key', array(&$this, 'plugin_section_text'), $this->Settings_page );
		
		//add setting fields
		add_settings_field(
			'wpcntctadmn_contactology_api_key', 
			'Contactology API Key', 
			array(&$this, 'input_string'), 
			$this->Settings_page, 
			$this->Settings_section, 
			array('setting_name' => 'contactology_api_key')
		);
	}
	
	/**
	 * validate settings
	 * TODO: WRITE VALIDATION FUNCTIONS
	 * @author Jeremy Ferguson
	 * @package wpdbsb
	 * @since 0.8
	**/	
	function plugin_settings_validate($input) {
		
		/*
		$newinput['text_string'] = trim($input['text_string']);
		if(!preg_match('/^[a-z0-9]{32}$/i', $newinput['text_string'])) {
		$newinput['text_string'] = '';
		}
		*/
		
		return $input;
	}
	
	/**
	 * Plugin Section Text
	 * TODO: WRITE PLUGIN SETTINGS INTRO
	 * @author Jeremy Ferguson
	 * @package wpdbsb
	 * @since 0.8
	**/	
	function plugin_section_text() {
		//echo 'TODO: description of plugin settings here please';
	}
	
	/**
	 * Creates input for settings screen
	 *
	 * @author Jeremy Ferguson
	 * @package wpdbsb
	 * @since 0.8
	**/	
	function input_string($args) {
	
		extract($args);
		
		//get current options fields
		$this->get_options();
		
		echo "<input id='wpcntctadmn_{$setting_name}' name='{$this->Settings_group}[{$setting_name}]' size='40' type='text' value='{$this->options[$setting_name]}' />";
	}
	 
	
	/**
	 * handle admin ajax logic
	 * TODO - Add 
	 * @author Jeremy Ferguson
	 * @package wpdbsb
	 * @since 0.8
	**/
	function admin_ajax_handler() {
	
		
		$arr = $response;
		
		$response =  json_encode($arr);		
		//header for json response
		header( "Content-Type: application/json" );
		echo $response;
		
		die();
	}
	
	/**
	 * add options script to admin
	 * NOT CURRENTLY IN USE
	 * @author Jeremy Ferguson
	 * @package wpdbsb
	 * @since 0.8
	**/	
	function admin_script() {
		wp_enqueue_script( 'wp_contactology_admin_js' );
	}	
	
	public function get_options() {
		$this->options = get_option( $this->Settings_group );
	}

} 

/**
 * add init function to wordpress init hook
 * 
 * @author Jeremy Ferguson
 * @package wpcntlgy
 * @since 0.9
 * 
**/
add_action("init", "wp_contactology_admin");


/**
 * Init the multisite object and enqueue scripts and styles, add localization variable
 *
 * @author Jeremy Ferguson
 * @package wpcntlgy
 * @since 0.9
**/
function wp_contactology_admin() {
	
	// Initiate the plugin
	if (class_exists("WP_Contactology_Admin")) {
		$wp_contactology_admin = new WP_Contactology_Admin();
				
	}	
}
