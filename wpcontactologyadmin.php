<?php
/*
 Plugin Name: WP Contactology Admin
 Description: Creates account creation and management system for Contactology. Branched from WP Cakemail 0.91. Customized for All Star Cheer Sites
 Version: 0.923
 Author: Jeremy Ferguson
 */
 
//requires Contactology wrapper objects
if ( !class_exists('Contactology') ) 
	require "lib/class.Contactology.php";	
if ( !class_exists('Contactology_Admin') ) 
	require "lib/class.Contactology_Admin.php";

//requires custom meta boxes
require "lib/cmb_custom_meta_boxes.php";	

//requires WP_Contactology_Post_Type 
if ( !class_exists('WP_Contactology_Post_Type') ) 
	require "lib/class.WP_Contactology_Post_Type.php";		
 
//define wpmu paths if not already defined 
if ( ! defined( 'WP_PLUGIN_URL' ) )
       define( 'WP_PLUGIN_URL', WP_CONTENT_URL. '/plugins' );
if ( ! defined( 'WP_PLUGIN_DIR' ) )
       define( 'WP_PLUGIN_DIR', WP_CONTENT_DIR . '/plugins' );

/**
 * This class creates the Contactology Admin interface and post type
 * @author Jeremy Ferguson
 * @since 0.8
*/
class WP_Contactology_Admin {

	/** @var WP_Contactology_Settings refers to settings object*/ 
	private $Settings; 
	/** @var string holds api_key saved in db*/ 
	private $api_key; 
	/** @var Contactology refers to main api object*/
	private $c; 
	/** @var Contactology_Admin refers to admin api object*/
	private $c_admin; 
	
	/**
	 * Create objects, get settings, add actions
	 *
	 * @author Jeremy Ferguson
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
				
			case 'activate_account':
				
				$response['message'] = $this->activate_account( $_POST ) ;
				
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
	 * creates new client with BLOCK_SENDS accountType
	 *
	 * @author Jeremy Ferguson
	 * @since 0.9
	 * @param $_POST data from ajax submission
	 * @return string campaign ID on success, error message on fail
	**/
	function client_setup( $_POST ) {
		 	
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
			$new_campaign = $this->c_admin->Admin_Create_Account_Minimal( $clientName, $adminEmail, $userName,  $password, $optionalParameters );
		}
		catch(Exception $e) {
			$new_campaign = $e->getMessage();
		}
		
		return $new_campaign;
	}
	
	/**
	 * Get active mailing lists and return as select dropdown
	 * 
	 * @author Jeremy Ferguson
	 * @since 0.92
 	 * @param $_POST data from ajax submission
	 * @return string html select list of active mailing lists for selected client
	**/
	function get_lists( $_POST ) {
		
		//get Contactology object for client account
		if ( class_exists('Contactology') ) 
			$c_current = new Contactology( $_POST['api_key'] );
		
		//get active mailing lists for selected client
		$active_lists = $c_current->List_Get_Active_Lists();
		
		//create select list
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
	 * @since 0.9
	 * @param $_POST data from ajax submission
	 * @return string Success on success, error message on fail
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
	 * Activate account by switching from block send to contact quota
	 *
	 * @author Jeremy Ferguson
	 * @since 0.922
	 * @param $_POST data from ajax submission
	 * @return string Success message on success, error message on fail
	**/
	function activate_account( $_POST ) {
		
		//gather the variables from $_POST
		$client_apikey = $_POST['client_apikey'];
		$client_id = $_POST['client_id'];
				
		//get account checklist, catch exceptions
		try { 
			$results = $this->c_admin->Admin_Change_Account_Type($client_id, 'CONTACT_QUOTA');
			
			$results .= $this->c_admin->Admin_Change_Account_Contact_Quota($client_id, 100);
			
		}
		catch(Exception $e) {
			return $e->getMessage();
		}
		
		return 'Account successfully activated';
	}
	
	/**
	 * register admin javascript
	 *
	 * @author Jeremy Ferguson
	 * @since 0.9
	**/	
	function admin_init() {
        /* Register our script. */
		wp_register_script('wp_contactology_admin_js', WP_PLUGIN_URL . '/wpcontactologyadmin/js/wpcontactologyadmin.js',
			array('jquery'));
    }
	
	/**
	 * enqueue admin javascript
	 *
	 * @author Jeremy Ferguson
	 * @since 0.9
	**/	
	function admin_script() {
		wp_enqueue_script( 'wp_contactology_admin_js' );
	}
	
	/**
	 * add options page to plugin menu and print javascript 
	 * 
	 * @author Jeremy Ferguson
	 * @since 0.9
	**/	
	function admin_menu() {
		//add account admin page to campaign post type and get slug
		$page = add_submenu_page(
			'edit.php?post_type=campaign',
			'Account Admin', 
			'Account Admin', 
			'manage_options', 
			'wpcontactologyacctadmin', 
			array(&$this, 'account_admin_panel'
			)
		);
		//add our script to admin page
		add_action('admin_print_scripts-' . $page, array(&$this, 'admin_script') );
	}
	
	/**
	 * create admin panel
	 * 
	 * @author Jeremy Ferguson
	 * @since 0.9
	**/	
	function account_admin_panel() {
		//check user permissions
		if (!current_user_can('manage_options'))  {
			wp_die( __('You do not have sufficient permissions to access this page.') );
		}
		
		//get the account checklist to use in the forms
		$account_checklist = $this->account_checklist();
		//get the list of active clients select html 
		$account_select = $this->account_select();
		//get the campaigns available to copy
		$campaign_select = $this->campaign_select();
?>		
		<!-- Form that creates new Contactology account -->
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
		<!-- Form that activates Contactology account -->
		<h3>Activate Account</h3>
		<form id="wp_contactology_activate_account_form">
			<ul>
				<li><label for="fname">Select client to activate:</label><br/>
					<?php echo $account_select; ?>
					
				</li>
				<li>
					<button id="wp_contactology_activate_account_submit">Activate</button>
					<div id="loading" style="display:none; width:100%; height:100%; ">
						<p><img src="/wp-admin/images/loading.gif" /> Please Wait</p>
					</div>
					<div id="wp_contactology_activate_account_results"></div>
				</li>
			</ul>
		</form>
		<!-- Form copies autoresponders from post type to selected Contactology accounts -->
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
		<!-- TODO: reset form after each use automatically-->
<?php 
	}
	
	/**
	 * get account list with API keys and return checkbox list
	 * 
	 * @author Jeremy Ferguson
	 * @since 0.92
	 * @return string checklist of active accounts on success, error message on fail
	**/
	function account_checklist() {
		//get account checklist, catch exceptions
		try { 
			$account_list = $this->c_admin->Admin_Get_Accounts();
		}
		catch(Exception $e) {
			return $e->getMessage();
		}
		//to hold apikeys
		$apikey_array = array();
		//start html output
		$apikey_list_output .= '<div id="wp_contactology_current_client_checklist">';
		
		//go each account
		foreach( $account_list as $clientID => $clientName ) {
			//get the API key for 
			$apikey_array[$clientID] = $this->c_admin->Admin_Get_Account_Key( $clientID );
			
			//add to html output
			$apikey_list_output .= "<div class='{$apikey_array[$clientID]}'>";
			
			$apikey_list_output .= "<input type='checkbox' name='clientapikey' value='{$apikey_array[$clientID]}' id='{$apikey_array[$clientID]}'/> ";
			$apikey_list_output .= "<input type='hidden' name='clientid' value='{$clientID}' /> ";
			$apikey_list_output .= "<label for='{$apikey_array[$clientID]}'>$clientName</label></div>";
		}
		//return html output
		return $apikey_list_output;
	}
	
	/**
	 * get account list with API keys and return select list
	 * 
	 * @author Jeremy Ferguson
	 * @since 0.922
	 * @return string html select of active accounts on success, error message on fail
	**/	
	function account_select(  ) {
		//get account list, catch exceptions
		try { 
			$account_list = $this->c_admin->Admin_Get_Accounts();
		}
		catch(Exception $e) {
			return $e->getMessage();
		}
		//to hold API keys
		$apikey_array = array();
		//start html output
		$apikey_list_output = '<select id="wp_contactology_admin_client_to_activate" name="wp_contactology_admin_client_to_activate">';
		$apikey_list_output .= '<option value="0">Select a Client</option>';
		//go through each account
		foreach( $account_list as $clientID => $clientName ) {
			$apikey_list_output .= "<option value='{$clientID}'>{$clientName}</option>";
		}
		//end output
		$apikey_list_output .= '</select>';
		//return html output
		return $apikey_list_output;
	}

	/**
	 * Assemble campaign select menu
	 *
	 * @author Jeremy Ferguson
	 * @since 0.921
	 * @return string html select of available campaigns
	**/
	function campaign_select() {
		//start html output
		$campaign_select .= '<select id="wp_contactology_admin_campaigns" name="wp_contactology_admin_campaigns">';
		$campaign_select .= '<option value="0">Select a Folder</option>';
		//arguments for WP_Query
		$args = ( array( 
			'post_type' => 'campaign',
			'posts_per_page' => -1,
			'orderby' => 'title',
			'order' => 'ASC'
		));
		//make new query
		$campaign_query = new WP_Query( $args );
		//go through each post, create html
		while ( $campaign_query->have_posts() ) : $campaign_query->the_post();
			$campaign_select .= '<option value="' . get_the_ID() . '">' . get_the_title() . '</option>';
		endwhile;
		//reset query
		wp_reset_postdata();
		//end select
		$campaign_select .= '</select>';
		//return html output
		return $campaign_select;
	}
}


/**
 * This class creates and holds WordPress settings data for WP Contactology
 * 
 * @author Jeremy Ferguson
 * @since 0.8
 * 
**/
class WP_Contactology_Settings {

	/** @var string settings group name*/ 
	private $settings_group;
	/** @var string settings page name*/ 
	private $settings_page;
	/** @var string settings section name*/ 
	private $settings_section;
	/** @var array WordPress options array*/ 
	public $options;

	/**
	 * Set variables, add actions
	 *
	 * @author Jeremy Ferguson
	 * @since 0.8
	**/
	function __construct() {
		
		//setup variables for settings screens
		$this->settings_group = 'wpcntctadmn_plugin_settings';
		$this->settings_page = 'wpcntctadmn_plugin_settings_section';
		$this->settings_section = 'wpcntctadmn_plugin_main';
		
		//add menu and init actions
		add_action('admin_menu', array(&$this, 'admin_menu'));
		add_action( 'admin_init', array( &$this, 'admin_init' ) );
	} 
	
	/**
	 * add options page to plugin menu
	 *
	 * @author Jeremy Ferguson
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
		<?php settings_fields( $this->settings_group ); ?>
		<?php do_settings_sections( $this->settings_page ); ?>

		<input name="Submit" type="submit" value="<?php esc_attr_e('Save Changes'); ?>" />
		</form>
		</div>
		<?php 
	}
	
	/**
	 * register settings
	 *
	 * @author Jeremy Ferguson
	 * @since 0.8
	**/	
	function admin_init() {
		
		//register settings array and add settings section
		register_setting( $this->settings_group , $this->settings_group, array(&$this, 'plugin_settings_validate') );
		add_settings_section( $this->settings_section, 'API Key', array(&$this, 'plugin_section_text'), $this->settings_page );
		
		//add setting fields
		add_settings_field(
			'wpcntctadmn_contactology_api_key', 
			'Contactology API Key', 
			array(&$this, 'input_string'), 
			$this->settings_page, 
			$this->settings_section, 
			array('setting_name' => 'contactology_api_key')
		);
	}
	
	/**
	 * validate settings
	 * TODO: WRITE VALIDATION FUNCTIONS
	 * @author Jeremy Ferguson
	 * @since 0.8
	 * @param string settings string to validate
	 * @return string validated and filtered string
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
	 * 
	 * @author Jeremy Ferguson
	 * @since 0.8
	**/	
	function plugin_section_text() {
		echo '<p>Enter the current API key for your Contactology Admin account. <a href="https://admin.contactology.com/ApiKeys.php" target="_BLANK">Find your key</a>.';
	}
	
	/**
	 * Creates input for settings screen
	 *
	 * @author Jeremy Ferguson
	 * @since 0.8
	 * @param array callback function arguments - settings_page, settings_section, setting_name (array)
	**/	
	function input_string($args) {
		//get the arguments
		extract($args);
		
		//get current options fields
		$this->get_options();
		
		echo "<input id='wpcntctadmn_{$setting_name}' name='{$this->settings_group}[{$setting_name}]' size='40' type='text' value='{$this->options[$setting_name]}' />";
	}
	 	
	/**
	 * handle admin ajax logic
	 * NOT CURRENTLY IN USE
	 * @author Jeremy Ferguson
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
	 * @since 0.8
	**/	
	function admin_script() {
		wp_enqueue_script( 'wp_contactology_admin_js' );
	}	
	
	/**
	 * gets WordPress option and sets class variable
	 * 
	 * @author Jeremy Ferguson
	 * @since 0.8
	**/	
	public function get_options() {
		$this->options = get_option( $this->settings_group );
	}

} 

/**
 * add init function to wordpress init hook
 * 
 * @author Jeremy Ferguson
 * @since 0.9
 * 
**/
add_action("init", "wp_contactology_admin");


/**
 * Init the admin object
 *
 * @author Jeremy Ferguson
 * @since 0.9
**/
function wp_contactology_admin() {
	// Initiate the plugin
	if (class_exists("WP_Contactology_Admin")) {
		$wp_contactology_admin = new WP_Contactology_Admin();
	}	
}
