<?php
/*
 Plugin Name: WP Contactology Admin
 Description: Creates account creation and management system for Contactology. Branched from WP Cakemail 0.91. Customized for All Star Cheer Sites
 Version: 0.92
 Author: Jeremy Ferguson
 */
 
//requires Contactology wrapper functions
if ( !class_exists('Contactology') ) 
	require "lib/class.Contactology.php";
	
if ( !class_exists('Contactology_Admin') ) 
	require "lib/class.Contactology_Admin.php";
 
//define wpmu paths if not already defined 
if ( ! defined( 'WP_PLUGIN_URL' ) )
       define( 'WP_PLUGIN_URL', WP_CONTENT_URL. '/plugins' );
 
if ( ! defined( 'WP_PLUGIN_DIR' ) )
       define( 'WP_PLUGIN_DIR', WP_CONTENT_DIR . '/plugins' );


/*
	Contactology Class
	Campaign in the API is actually a folder in the UI. 
	Mailing in the API is actually a standard email campaign 
	Trigger in the API is actually an autoresponder
*/

class WP_Contactology_Admin {

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
	
		$this->api_key = '6db657cea372169d34da93be7b1a4941';
		
		//initialize Contactology objects
		if ( class_exists('Contactology') ) 
			$this->c = new Contactology( $this->api_key );
		if ( class_exists('Contactology_Admin') ) 	
			$this->c_admin = new Contactology_Admin( $this->api_key );
				
		//add ajax handler for admin screen		
		add_action('wp_ajax_wp_contactology_admin', array(&$this, 'admin_ajax_handler') );
		
		//add function to gravity forms hook to create a new client
		add_action('gform_after_submission', array(&$this, 'client_setup'), 10, 2);
		
		//add admin menu function and init for script
		add_action( 'admin_init', array(&$this, 'admin_init'));
		add_action('admin_menu', array(&$this, 'admin_menu'));
				
	}
		
	/**
	 * creates and activates new client, adds two lists
	 *
	 * @author Jeremy Ferguson
	 * @package wpcntlgy
	 * @since 0.9
	 * @param $entry, $form
	**/
	
	function client_setup($entry, $form) {
			
		
		 if($form["cssClass"] != 'cakemail_signup'){
			return;
		}

		//gather data for new client
		$client_create_data = array(
			'company_name'     => $entry['3'],
			'admin_email'  => $entry['7'],
			'admin_first_name' =>	$entry['6.3'],
			'admin_last_name' =>	$entry['6.6'],
			'admin_password' =>	'changeme',
			'admin_password_confirmation' => 'changeme',
			'contact_same_as_admin' =>	'1',
			'parent_id' => '100006' 
		);

		//create new client
	//	$client_create = $this->curl_process( 'https://api.wbsrvc.com/Client/create/', $client_create_data );
		
		//set the confirmation number needed to activate client
		$client_activate_data = array(
			'confirmation' => $client_create
		);	
			
		//activate client
	//	$client_activate = $this->curl_process( 'https://api.wbsrvc.com/Client/activate/', $client_activate_data);
		
		
		//gather data for new client
		$client_setup_data = array(
			'user_key' => $client_activate['admin_key'],
			'address1'  => $entry['81.1'],
			'city' =>	$entry['81.3'],
			'country_id' =>	'us',
			'province_id' =>	$entry['81.4'],
			'postal_code' =>	$entry['81.5'], 
			'phone' => $entry['82'],
			'website' => $entry['77']
		);
		
		//setup new client
	//	$client_setup = $this->curl_process( 'https://api.wbsrvc.com/Client/setInfo/', $client_setup_data );
		
				
		//set the data for prospect list
		$prospect_list_data = array(
			'user_key' => $client_activate['admin_key'],
			'name' =>  'Prospect List',
			'sender_name' => $client_create_data['admin_first_name'] . ' '. $client_create_data['admin_last_name'],
			'sender_email' => $client_create_data['admin_email']
		);	

		//create prospect list - return is list_id
	//	$prospect_list = $this->curl_process( 'https://api.wbsrvc.com/List/create/', $prospect_list_data);
		
		//set the details for prospect list
		$prospect_list_setup_data = array(
			'user_key' => $client_activate['admin_key'],
			'list_id' =>  $prospect_list,
			'policy' => 'accepted'
		);	
		
		
	//	$prospect_list_setup = $this->curl_process( 'https://api.wbsrvc.com/List/setInfo/', $prospect_list_setup_data);
		
		//set the details for prospect list
		$prospect_list_structure_data = array(
			'user_key' => $client_activate['admin_key'],
			'list_id' =>  $prospect_list,
			'action' => 'add',
			'field' => 'Name',
			'type' => 'text'
		);	
		
		
	//	$prospect_list_structure = $this->curl_process( 'https://api.wbsrvc.com/List/editStructure/', $prospect_list_structure_data);
		
		//set the data for prospect list
		$member_list_data = array(
			'user_key' => $client_activate['admin_key'],
			'name' =>  'Member List',
			'sender_name' => $client_create_data['admin_first_name'] . ' '. $client_create_data['admin_last_name'],
			'sender_email' => $client_create_data['admin_email']
		);	

		//create member list - return is list_id
	//	$member_list = $this->curl_process( 'https://api.wbsrvc.com/List/create/', $member_list_data);
		
		//set the details for member list
		$member_list_setup_data = array(
			'user_key' => $client_activate['admin_key'],
			'list_id' =>  $member_list,
			'policy' => 'accepted'
		);	
		
		
	//	$member_list_setup = $this->curl_process( 'https://api.wbsrvc.com/List/setInfo/', $member_list_setup_data);
		
		//set the details for prospect list
		$member_list_structure_data = array(
			'user_key' => $client_activate['admin_key'],
			'list_id' =>  $member_list,
			'action' => 'add',
			'field' => 'Name',
			'type' => 'text'
		);	
		
		
	//	$member_list_structure = $this->curl_process( 'https://api.wbsrvc.com/List/editStructure/', $member_list_structure_data);
		
		
				
		/* Debug
			
		echo 'client data <pre>';
		print_r($client_setup);
		echo '</pre>';	

		echo 'campaign data <pre>';
		print_r($campaign_create);
		echo '</pre>';

		echo 'mailing data <pre>';
		print_r($trigger_create);
		echo '</pre>';

		*/
		
		
	}
	
	/**
	 * handle admin ajax logic
	 *
	 * @author Jeremy Ferguson
	 * @package wpcntlgy
	 * @since 0.9
	**/
	function admin_ajax_handler() {
	
		//gather the variables from $_POST
		$campaign_id = $_POST['folder_id'];
		$campaign_name = $_POST['folder_name'];
		$client_id = $_POST['client'];
		$list_id = $_POST['list'];
		
		//for the response
		$response = array();
		
		//check on type of operation
		switch( $_POST['type']){
			
			//get the lists and send 'em back
			case 'get_lists':
				
				//set data for list of triggers 
				$list_data = array(
						'user_key' => $this->user_key,
						'client_id' => $_POST['client']
					);	
		
				//get list of triggers
		//		$mailing_lists = $this->curl_process( 'https://api.wbsrvc.com/List/getList/', $list_data);
				
				//make an option out of each one
				foreach($mailing_lists['lists'] as $list) {
					$response['message'] .= '<option value="'. $list['id'] .'">' . $list['name'] . '</option>';
				}
				break;
			//copy the folder that was set
			case 'copy_folder':
				
				$this->copy_trigger( $campaign_id, $campaign_name, $list_id, $client_id ) ;
				$response['message'] = 'you did it!';
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
	 * Copy list of triggers
	 *
	 * @author Jeremy Ferguson
	 * @package wpcntlgy
	 * @since 0.9
	**/
	function copy_trigger( $campaign_id, $campaign_name, $list_id, $client_id ) {
	
		//set data for list of triggers 
		$trigger_list_data = array(
				'user_key' => $this->user_key,
				'campaign_id' =>  $campaign_id,
				'client_id' => $this->client_id
			);	
		
		//get list of triggers
	//	$trigger_list = $this->curl_process( 'https://api.wbsrvc.com/Trigger/getList/', $trigger_list_data);
	
		//set the data for autoresponder folder
		$campaign_create_data = array(
			'user_key' => $this->user_key,
			'name' =>  $campaign_name,
			'client_id' => $client_id
		);	
		
		//create campaign (folder) - return is campaign_id (folder)
	//	$campaign_create = $this->curl_process( 'https://api.wbsrvc.com/Campaign/create/', $campaign_create_data);
	
		// Loop out triggers and create new ones in current account
		foreach( $trigger_list['triggers'] as $trigger ) {
		
			//assemble the rest of the data for the trigger to be copied
			$trigger_to_copy_data = array(
				'user_key' => $this->user_key,
				'trigger_id' => $trigger['id'],
				'client_id' =>  $this->client_id
			);	
			
			//get the details about the trigger
		//	$trigger_to_copy_details = $this->curl_process( 'https://api.wbsrvc.com/Trigger/getInfo/', $trigger_to_copy_data);
		
			//set the data for new autoresponder (trigger) 
			$trigger_data = array(
				'user_key' => $this->user_key,
				'client_id' =>  $client_id,
				'name' => $trigger['name'],
				'list_id' =>  $list_id
			);	
			
			//create autoresponder - return is autoresponder id
		//	$trigger_create = $this->curl_process( 'https://api.wbsrvc.com/Trigger/create/', $trigger_data);
			
			//set the detailed data for new autoresponder / trigger
			$trigger_details_data = array(
				'user_key' => $this->user_key,
				'client_id' =>  $client_id,
				'trigger_id' => $trigger_create,
				'campaign_id' => $campaign_create,
				'action' => $trigger['action'],
				'delay' => $trigger['delay'],
				'html_message' => $trigger_to_copy_details['html_message'],
				'subject' => $trigger['subject']/*,
				'sender_name' => '',
				'sender_email' => ''*/
				
			);
			
			//set the info on the new autoresponder / trigger
		//	$trigger_setup = $this->curl_process( 'https://api.wbsrvc.com/Trigger/setInfo/', $trigger_details_data);
			
			
		} // end trigger_list foreach
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
		wp_register_script('wp_contactology_admin_js', WP_PLUGIN_URL . '/wpcontactology/js/wpcontactology.js',
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
		$page = add_options_page('WP Contactology Options', 'WP Contactology', 'manage_options', 'wpcontactology', array(&$this, 'admin_panel'));
		
		add_action('admin_print_scripts-' . $page, array(&$this, 'admin_script') );
	}
	
	/**
	 * create admin panel
	 *
	 * @author Jeremy Ferguson
	 * @package wpcntlgy
	 * @since 0.9
	**/	
	function admin_panel() {
		if (!current_user_can('manage_options'))  {
			wp_die( __('You do not have sufficient permissions to access this page.') );
		}
		
		//TESTING NEW API
		$campaign_list = $this->c->Campaign_Find();
		
		krumo( $campaign_list );
				
		//data for get list
		$get_folder_list_data = array(
			'user_key' => $this->user_key,
			'client_id' => $this->client_id
		);
		
		//get folder list
	//	$folder_list = $this->curl_process( 'https://api.wbsrvc.com/Campaign/getList/', $get_folder_list_data);
		
		//data for get list
		$get_client_list_data = array(
			'user_key' => $this->user_key,
			'status' => 'active'
		);
		
		//get folder list
	//	$client_list = $this->curl_process( 'https://api.wbsrvc.com/Client/getList/', $get_client_list_data);
		/* DEBUG
		echo '<pre>';
		print_r( $client_list );
		echo '</pre>';
		*/
		
?>
		<form>
			<ul>
				<li><label for="fname">Select Folder to Copy: </label>
				
					<select id="wp_contactology_admin_folder" name="wp_contactology_admin_folder">
						<option value='0'>Select a Folder</option>
					<?php 
						foreach($folder_list['campaigns'] as $folder) {
							echo '<option value="'. $folder['id'] .'">' . $folder['name'] . '</option>';
							
						}
					
					?>
					</select>
				
				</li>
				<li><label for="fname">Select Client to Receive Folder: </label>
				
					<select id="wp_contactology_admin_client" name="wp_contactology_admin_client">
						<option value='0'>Select a Client</option>
					<?php 
						foreach($client_list['clients'] as $client) {
							echo '<option value="'. $client['id'] .'">' . $client['company_name'] . '</option>';
						}
					
					?>
					</select>
				
				</li>
				<li><label for="fname">Select List to Assign Folder: </label>
				
					<select id="wp_contactology_admin_list" name="wp_contactology_admin_list" disabled="true">
						<option value='0'>Select a List</option>
						
					</select>
				
				</li>
				<li>
					<input type="submit" id="wp_contactology_admin_submit" value="Copy" />
					<div id="loading" style="display:none; width:100%; height:100%; ">
						<p><img src="/wp-admin/images/loading.gif" /> Please Wait</p>
					</div>
				</li>
			</ul>
		</form>
		<div id="wp_contactology_admin_results"></div>

<?php 
	}
	
	/**
	 * add options page to plugin menu
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
