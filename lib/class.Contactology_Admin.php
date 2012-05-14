<?php
class Contactology_Admin extends Contactology {
			
			
	/**
	 * Create a new Contactology account with the given parameters
	 *
	 * 
	 *
	 * @param string clientName Name for the account
	 * @param string adminEmail Default email address for the account
	 * @param string userName User name for the new account - must be unique
	 * @param string password Password for the new account
	 * @param string homePage URL for the account - will be included in the unsubscribe screen
	 * @param string logoUrl URL to the logo image - will be included in the unsubscribe screen
	 * @param string clientAddr1 Physical address for the account - will be included in the footer of messages
	 * @param string clientCity Physical address for the account - will be included in the footer of messages
	 * @param string clientState Physical address for the account - will be included in the footer of messages
	 * @param string clientZip Physical address for the account - will be included in the footer of messages
	 * @param struct optionalParameters A struct of optional parameters, see below for valid keys
	 * @return int The user ID of the new account
	 */ 
	public function Admin_Create_Account( $clientName, $adminEmail, $userName, $password, $homePage, $logoUrl, $clientAddr1, $clientCity, $clientState, $clientZip, $optionalParameters = array() ) {
		$args = array( 'key' => $this->key, 'method' => 'Admin_Create_Account' );
		$args['clientName'] = $clientName;
		$args['adminEmail'] = $adminEmail;
		$args['userName'] = $userName;
		$args['password'] = $password;
		$args['homePage'] = $homePage;
		$args['logoUrl'] = $logoUrl;
		$args['clientAddr1'] = $clientAddr1;
		$args['clientCity'] = $clientCity;
		$args['clientState'] = $clientState;
		$args['clientZip'] = $clientZip;
		$args['optionalParameters'] = $optionalParameters;

		$data = $this->makeCall( $args );
		
		return $data;
	}
			
			
	/**
	 * Create a new Contactology account using minimal initial information
	 *
	 * This call enables you to sign up accounts with less information (does not require address, logo or homepage), but these accounts must provide this information before sending
	 *
	 * @param string clientName Name for the account
	 * @param string adminEmail Default email address for the account
	 * @param string userName User name for the new account - must be unique
	 * @param string password Password for the new account
	 * @param struct optionalParameters A struct of optional parameters, see below for valid keys
	 * @return int The user ID of the new account
	 */ 
	public function Admin_Create_Account_Minimal( $clientName, $adminEmail, $userName, $password, $optionalParameters = array() ) {
		$args = array( 'key' => $this->key, 'method' => 'Admin_Create_Account_Minimal' );
		$args['clientName'] = $clientName;
		$args['adminEmail'] = $adminEmail;
		$args['userName'] = $userName;
		$args['password'] = $password;
		$args['optionalParameters'] = $optionalParameters;

		$data = $this->makeCall( $args );
		
		return $data;
	}
			
			
	/**
	 * Get a list of your current clients
	 *
	 * 
	 *
	 * @return struct Array of current client accounts
	 */ 
	public function Admin_Get_Accounts(  ) {
		$args = array( 'key' => $this->key, 'method' => 'Admin_Get_Accounts' );

		$data = $this->makeCall( $args );
		
		return $data;
	}
			
			
	/**
	 * Admin_Get_Account_Info retrieves all the info about an Account, including the Plan information
	 *
	 * 
	 *
	 * @param int clientId The client ID number you want to modify
	 * @return struct Returns a struct of the account's properties
	 */ 
	public function Admin_Get_Account_Info( $clientId ) {
		$args = array( 'key' => $this->key, 'method' => 'Admin_Get_Account_Info' );
		$args['clientId'] = $clientId;

		$data = $this->makeCall( $args );
		
		return $data;
	}
			
			
	/**
	 * Modifies multiple properties of an existing Contactology account created by your account.  Only fields in optionalParameters will be modified
	 *
	 * This API call will ignore any parameters not explicitly allowed in optionalParameters.
	 * 
	 * This API call does not handle any changes that could result in a change in billing.  For quotas, limits and similar changes, please use the Admin_Change_Account_Plan API call
	 *
	 * @param int clientId The client ID number you want to modify
	 * @param struct optionalParameters A struct of optional parameters, see below for valid keys
	 * @return int The number of fields successfully modified
	 */ 
	public function Admin_Modify_Account( $clientId, $optionalParameters ) {
		$args = array( 'key' => $this->key, 'method' => 'Admin_Modify_Account' );
		$args['clientId'] = $clientId;
		$args['optionalParameters'] = $optionalParameters;

		$data = $this->makeCall( $args );
		
		return $data;
	}
			
			
	/**
	 * Admin_Get_Account_Plan returns an struct of Account Plan details for one of your clients
	 *
	 * 
	 *
	 * @param int clientId The client ID number you want to modify
	 * @return struct Returns a struct of Account Plan details
	 */ 
	public function Admin_Get_Account_Plan( $clientId ) {
		$args = array( 'key' => $this->key, 'method' => 'Admin_Get_Account_Plan' );
		$args['clientId'] = $clientId;

		$data = $this->makeCall( $args );
		
		return $data;
	}
			
			
	/**
	 * Change Account Plan allows you to change everything about an Account's Plan at once
	 *
	 * If you only want to modify one of the properties, it may be easier to use the individual change API calls
	 *
	 * @param int clientId The client ID number you want to modify
	 * @param int hostedLimit hostedClientLimit Hosted Client Limit, valid values: 10, 20, 30, 40, 50
	 * @param int maxCustomUsers maximumCustomUsers Maximum Custom Users, valid values: 3, 4, 8, 13, 28
	 * @param string package [DEPRECATED] Package is no longer required - an empty string will work fine. Previous valid values: PACKAGE_CONTACTS_EMAIL, PACKAGE_SURVEYS, PACKAGE_FULL, PACKAGE_MIGRATE will be accepted to keep legacy code from breaking
	 * @param string accountType Account type, valid values: NO_EMAIL, CONTACT_QUOTA, BLOCK_SENDS, UNLIMITED
	 * @param int contactQuota Contact quota, valid values: 500, 1000, 2500, 5000, 7500, 10000, 15000, 20000, 25000
	 * @param int contractLength Contract length, valid values: 1, 12
	 * @param int paymentFrequency Payment frequency, valid values: 1, 3, 6, 12
	 * @param bool poweredBy Display Powered By Contactology logo
	 * @return bool Returns true on success
	 */ 
	public function Admin_Change_Account_Plan( $clientId, $hostedLimit, $maxCustomUsers, $package, $accountType, $contactQuota, $contractLength, $paymentFrequency, $poweredBy ) {
		$args = array( 'key' => $this->key, 'method' => 'Admin_Change_Account_Plan' );
		$args['clientId'] = $clientId;
		$args['hostedLimit'] = $hostedLimit;
		$args['maxCustomUsers'] = $maxCustomUsers;
		$args['package'] = $package;
		$args['accountType'] = $accountType;
		$args['contactQuota'] = $contactQuota;
		$args['contractLength'] = $contractLength;
		$args['paymentFrequency'] = $paymentFrequency;
		$args['poweredBy'] = $poweredBy;

		$data = $this->makeCall( $args );
		
		return $data;
	}
			
			
	/**
	 * Admin_Change_Account_Hosted_Limit allows you to change the Hosted Limit (in Megabytes) for your clients
	 *
	 * Valid values: 10, 20, 30, 40, 50
	 *
	 * @param int clientId The client ID number you want to modify
	 * @param int hostedLimit Hosted Limit, valid values: 10, 20, 30, 40, 50
	 * @return bool Returns true on success
	 */ 
	public function Admin_Change_Account_Hosted_Limit( $clientId, $hostedLimit ) {
		$args = array( 'key' => $this->key, 'method' => 'Admin_Change_Account_Hosted_Limit' );
		$args['clientId'] = $clientId;
		$args['hostedLimit'] = $hostedLimit;

		$data = $this->makeCall( $args );
		
		return $data;
	}
			
			
	/**
	 * Admin_Change_Account_Maximum_Custom_Users allows you to change the Maximum Custom Users of your clients
	 *
	 * Valid values: 3, 4, 8, 13, 28
	 *
	 * @param int clientId The client ID number you want to modify
	 * @param int maxCustomUsers Maximum Custom Users, valid values: 3, 4, 8, 13, 28
	 * @return bool Returns true on success
	 */ 
	public function Admin_Change_Account_Maximum_Custom_Users( $clientId, $maxCustomUsers ) {
		$args = array( 'key' => $this->key, 'method' => 'Admin_Change_Account_Maximum_Custom_Users' );
		$args['clientId'] = $clientId;
		$args['maxCustomUsers'] = $maxCustomUsers;

		$data = $this->makeCall( $args );
		
		return $data;
	}
			
			
	/**
	 * Admin_Change_Account_Package allows you to change the Package of your clients
	 *
	 * Valid values: PACKAGE_CONTACTS_EMAIL, PACKAGE_SURVEYS, PACKAGE_FULL, PACKAGE_MIGRATE
	 *
	 * @param int clientId The client ID number you want to modify
	 * @param string package Package, valid values: PACKAGE_CONTACTS_EMAIL, PACKAGE_SURVEYS, PACKAGE_FULL, PACKAGE_MIGRATE
	 * @return bool Returns true on success
	 */ 
	public function Admin_Change_Account_Package( $clientId, $package ) {
		$args = array( 'key' => $this->key, 'method' => 'Admin_Change_Account_Package' );
		$args['clientId'] = $clientId;
		$args['package'] = $package;

		$data = $this->makeCall( $args );
		
		return $data;
	}
			
			
	/**
	 * Admin_Change_Account_Type allows you to change the Account Type of your clients
	 *
	 * Valid values: NO_EMAIL, CONTACT_QUOTA, BLOCK_SENDS, UNLIMITED
	 *
	 * @param int clientId The client ID number you want to modify
	 * @param string accountType Account type, valid values: NO_EMAIL, CONTACT_QUOTA, BLOCK_SENDS, UNLIMITED
	 * @return bool Returns true on success
	 */ 
	public function Admin_Change_Account_Type( $clientId, $accountType ) {
		$args = array( 'key' => $this->key, 'method' => 'Admin_Change_Account_Type' );
		$args['clientId'] = $clientId;
		$args['accountType'] = $accountType;

		$data = $this->makeCall( $args );
		
		return $data;
	}
			
			
	/**
	 * Admin_Change_Account_Contact_Quota allows you to change the Contact Quota of your clients
	 *
	 * Valid values: 500, 1000, 2500, 5000, 7500, 10000, 15000, 20000, 25000
	 *
	 * @param int clientId The client ID number you want to modify
	 * @param int contactQuota Contact quota, valid values: 500, 1000, 2500, 5000, 7500, 10000, 15000, 20000, 25000
	 * @return bool True on success
	 */ 
	public function Admin_Change_Account_Contact_Quota( $clientId, $contactQuota ) {
		$args = array( 'key' => $this->key, 'method' => 'Admin_Change_Account_Contact_Quota' );
		$args['clientId'] = $clientId;
		$args['contactQuota'] = $contactQuota;

		$data = $this->makeCall( $args );
		
		return $data;
	}
			
			
	/**
	 * Admin_Change_Account_Contract_Length allows you to change the Contract Length of your clients
	 *
	 * Valid values: 1, 12 (months)
	 *
	 * @param int clientId The client ID number you want to modify
	 * @param int contractLength Contract length, valid values: 1, 12
	 * @return bool Returns true on success
	 */ 
	public function Admin_Change_Account_Contract_Length( $clientId, $contractLength ) {
		$args = array( 'key' => $this->key, 'method' => 'Admin_Change_Account_Contract_Length' );
		$args['clientId'] = $clientId;
		$args['contractLength'] = $contractLength;

		$data = $this->makeCall( $args );
		
		return $data;
	}
			
			
	/**
	 * Admin_Change_Account_Payment_Frequency allows you to change the Payment Frequency of your clients
	 *
	 * Valid values: 1, 3, 6, 12 (months)
	 *
	 * @param int clientId The client ID number you want to modify
	 * @param int paymentFrequency Payment frequency, valid values: 1, 3, 6, 12
	 * @return bool Returns true on success
	 */ 
	public function Admin_Change_Account_Payment_Frequency( $clientId, $paymentFrequency ) {
		$args = array( 'key' => $this->key, 'method' => 'Admin_Change_Account_Payment_Frequency' );
		$args['clientId'] = $clientId;
		$args['paymentFrequency'] = $paymentFrequency;

		$data = $this->makeCall( $args );
		
		return $data;
	}
			
			
	/**
	 * Admin_Change_Account_Powered_By allows you to toggle the display of the Powered By logo for your clients
	 *
	 * Valid values: true, false
	 *
	 * @param int clientId The client ID number you want to modify
	 * @param bool poweredBy Display Powered By Contactology logo
	 * @return bool Returns true on success
	 */ 
	public function Admin_Change_Account_Powered_By( $clientId, $poweredBy ) {
		$args = array( 'key' => $this->key, 'method' => 'Admin_Change_Account_Powered_By' );
		$args['clientId'] = $clientId;
		$args['poweredBy'] = $poweredBy;

		$data = $this->makeCall( $args );
		
		return $data;
	}
			
			
	/**
	 * Admin_Add_Block_Sends allows you to add block quota sends to one of your clients
	 *
	 * 
	 *
	 * @param int clientId The client ID number you want to modify
	 * @param int numSendsToAdd numSendsToAdd
	 * @return bool Returns true on success
	 */ 
	public function Admin_Add_Block_Sends( $clientId, $numSendsToAdd ) {
		$args = array( 'key' => $this->key, 'method' => 'Admin_Add_Block_Sends' );
		$args['clientId'] = $clientId;
		$args['numSendsToAdd'] = $numSendsToAdd;

		$data = $this->makeCall( $args );
		
		return $data;
	}
			
			
	/**
	 * Suspend an Account you created
	 *
	 * 
	 *
	 * @param int clientId The client ID number you want to modify
	 * @return bool true on success
	 */ 
	public function Admin_Suspend_Account( $clientId ) {
		$args = array( 'key' => $this->key, 'method' => 'Admin_Suspend_Account' );
		$args['clientId'] = $clientId;

		$data = $this->makeCall( $args );
		
		return $data;
	}
			
			
	/**
	 * Reinstate an Account you suspended
	 *
	 * 
	 *
	 * @param int clientId The client ID number you want to modify
	 * @return bool true on success
	 */ 
	public function Admin_Reinstate_Account( $clientId ) {
		$args = array( 'key' => $this->key, 'method' => 'Admin_Reinstate_Account' );
		$args['clientId'] = $clientId;

		$data = $this->makeCall( $args );
		
		return $data;
	}
			
			
	/**
	 * Delete an Account you created
	 *
	 * 
	 *
	 * @param int clientId The client ID number you want to modify
	 * @return bool true on success
	 */ 
	public function Admin_Delete_Account( $clientId ) {
		$args = array( 'key' => $this->key, 'method' => 'Admin_Delete_Account' );
		$args['clientId'] = $clientId;

		$data = $this->makeCall( $args );
		
		return $data;
	}
			
			
	/**
	 * Get a list of all completed campaigns for your clients
	 *
	 * 
	 *
	 * @param struct optionalParameters Optional Parameters: sortBy, sortDir, startDate, endDate
	 * @return struct An array of clientIds with a value array containing: campaign_id, campaign_name, campaign_description, start_time, campaign_email_from, campaign_email_from_alias, campaign_email_subject
	 */ 
	public function Admin_Get_Accounts_Completed_Campaigns( $optionalParameters = array() ) {
		$args = array( 'key' => $this->key, 'method' => 'Admin_Get_Accounts_Completed_Campaigns' );
		$args['optionalParameters'] = $optionalParameters;

		$data = $this->makeCall( $args );
		
		return $data;
	}
			
			
	/**
	 * Get the total number of sends for each of your accounts
	 *
	 * 
	 *
	 * @param struct optionalParameters Optional Parameters: startDate, endDate
	 * @return struct An array of clientIds each with a value of the total sends
	 */ 
	public function Admin_Get_Accounts_Sends( $optionalParameters = array() ) {
		$args = array( 'key' => $this->key, 'method' => 'Admin_Get_Accounts_Sends' );
		$args['optionalParameters'] = $optionalParameters;

		$data = $this->makeCall( $args );
		
		return $data;
	}
			
			
	/**
	 * Get the total number of subscribed emails for each list owned by each of your accounts
	 *
	 * 
	 *
	 * @return struct A struct of clientIds each with a value struct of listId => count
	 */ 
	public function Admin_Get_Accounts_List_Count(  ) {
		$args = array( 'key' => $this->key, 'method' => 'Admin_Get_Accounts_List_Count' );

		$data = $this->makeCall( $args );
		
		return $data;
	}
			
			
	/**
	 * Get an API key for one of your accounts
	 *
	 * 
	 *
	 * @param int clientId The client ID number you want an API key for
	 * @return string API Key
	 */ 
	public function Admin_Get_Account_Key( $clientId ) {
		$args = array( 'key' => $this->key, 'method' => 'Admin_Get_Account_Key' );
		$args['clientId'] = $clientId;

		$data = $this->makeCall( $args );
		
		return $data;
	}
			
			
	/**
	 * Send an Alert Message to a set of your clients
	 *
	 * 
	 *
	 * @param array clientIds An array of Client IDs who will receive the message
	 * @param string shortMessage A short message, up to 255 characters
	 * @param struct optionalParameters A struct of optional parameters, see below for valid keys
	 */ 
	public function Admin_Send_Message( $clientIds, $shortMessage, $optionalParameters = array() ) {
		$args = array( 'key' => $this->key, 'method' => 'Admin_Send_Message' );
		$args['clientIds'] = $clientIds;
		$args['shortMessage'] = $shortMessage;
		$args['optionalParameters'] = $optionalParameters;

		$data = $this->makeCall( $args );
		
		return $data;
	}
			
			
	/**
	 * Get the current Webhooks settings for the specified Account
	 *
	 * 
	 *
	 * @param int clientId The client ID number you want to modify
	 * @return struct A struct of Webhook data
	 */ 
	public function Admin_Get_Account_Webhooks( $clientId ) {
		$args = array( 'key' => $this->key, 'method' => 'Admin_Get_Account_Webhooks' );
		$args['clientId'] = $clientId;

		$data = $this->makeCall( $args );
		
		return $data;
	}
			
			
	/**
	 * Admin_Set_Account_Webhooks allows you to set the Webhooks information for the specified Account
	 *
	 * The Account's current settings will be overriden, so specify all the hooks and locations you want
	 * 
	 * Note:  To receive WEBHOOK_BOUNCED or WEBHOOK_CAMPAIGN_CLICKED, you must have the System location active
	 *
	 * @param int clientId The client ID number you want to modify
	 * @param string url The URL to receive the Webhook
	 * @param string webhooksKey webhookKey The Webhook Key passed along with the data to verify the origin, this is important for security
	 * @param array hooks An array of events you would like to receive Webfor. Valid values are: WEBHOOK_SUBSCRIBE, WEBHOOK_UNSUBSCRIBE, WEBHOOK_GLOBAL_UNSUBSCRIBE, WEBHOOK_PROFILE_UPDATE, WEBHOOK_BOUNCED, WEBHOOK_EMAIL_CHANGED, WEBHOOK_CAMPAIGN_OPENED, WEBHOOK_CAMPAIGN_CLICKED, WEBHOOK_CAMPAIGN_SENDING_STARTED, WEBHOOK_CAMPAIGN_SENT, WEBHOOK_CAMPAIGN_SENT_TO_ADDITIONAL_RECIPIENT, WEBHOOK_REACTIVATED, WEBHOOK_LIST_CREATED, WEBHOOK_LIST_DELETED
	 * @param array locations An array of Locations a particular Hook can be fired from. Valid values are: ContactDirect, Webapp, WebappBulk, System, API
	 * @param array customFieldIds An array of Custom Field IDs (See: Custom_Field_Get_All) - if specified, the values of the specified fields will be included in the data payload for any Webhook sending contact data
	 * @param array optionalParameters 
	 * @return struct A struct of Webhook data
	 */ 
	public function Admin_Set_Account_Webhooks( $clientId, $url, $webhooksKey, $hooks, $locations, $customFieldIds = array(), $optionalParameters = array() ) {
		$args = array( 'key' => $this->key, 'method' => 'Admin_Set_Account_Webhooks' );
		$args['clientId'] = $clientId;
		$args['url'] = $url;
		$args['webhooksKey'] = $webhooksKey;
		$args['hooks'] = $hooks;
		$args['locations'] = $locations;
		$args['customFieldIds'] = $customFieldIds;
		$args['optionalParameters'] = $optionalParameters;

		$data = $this->makeCall( $args );
		
		return $data;
	}
			
			
	/**
	 * Deactivate all Webhooks for an account
	 *
	 * Note: Webhooks already queued for sending will still be sent
	 *
	 * @param int clientId The client ID number you want to modify
	 * @return bool True on success
	 */ 
	public function Admin_Deactivate_Account_Webhooks( $clientId ) {
		$args = array( 'key' => $this->key, 'method' => 'Admin_Deactivate_Account_Webhooks' );
		$args['clientId'] = $clientId;

		$data = $this->makeCall( $args );
		
		return $data;
	}
			
			
	/**
	 * Get Purchase Order data for an account
	 *
	 * 
	 *
	 * @return struct A struct of purchase order data
	 */ 
	public function Admin_Get_Purchase_Orders(  ) {
		$args = array( 'key' => $this->key, 'method' => 'Admin_Get_Purchase_Orders' );

		$data = $this->makeCall( $args );
		
		return $data;
	}
			
			
	/**
	 * Get the number of Inbox Analysis Tests remaining for the specified Account
	 *
	 * 
	 *
	 * @param int clientId The client ID number for which you want the number of remaining tests
	 * @return int The number of Inbox Analysis tests available for use by the specified Account
	 */ 
	public function Admin_Get_Inbox_Analysis_Remaining( $clientId ) {
		$args = array( 'key' => $this->key, 'method' => 'Admin_Get_Inbox_Analysis_Remaining' );
		$args['clientId'] = $clientId;

		$data = $this->makeCall( $args );
		
		return $data;
	}
			
			
	/**
	 * Find Inbox Analysis Tests used
	 *
	 * Defaults to this month if no date parameters are given
	 *
	 * @param int clientId The client ID number for which you want the number of remaining tests
	 * @return struct The Inbox Analysis tests used
	 */ 
	public function Admin_Get_Inbox_Analysis_Tests( $clientId ) {
		$args = array( 'key' => $this->key, 'method' => 'Admin_Get_Inbox_Analysis_Tests' );
		$args['clientId'] = $clientId;

		$data = $this->makeCall( $args );
		
		return $data;
	}
			
			
	/**
	 * Purchase additional Inbox Analysis Tests for your the specified Account
	 *
	 * Warning: This API call will cause your account to be billed for the tests you grant to the specified account.  Please contact sales@contactology.com for more info.
	 *
	 * @param int clientId The client ID number for which you are purchasing tests
	 * @param int numTests The number of tests you are ordering to be added to the specified Account
	 * @return bool True on success
	 */ 
	public function Admin_Purchase_Inbox_Analysis_Tests( $clientId, $numTests ) {
		$args = array( 'key' => $this->key, 'method' => 'Admin_Purchase_Inbox_Analysis_Tests' );
		$args['clientId'] = $clientId;
		$args['numTests'] = $numTests;

		$data = $this->makeCall( $args );
		
		return $data;
	}
			
			
	/**
	 * Get Campaign data for an account
	 *
	 * 
	 *
	 * @return struct A struct of Campaign data
	 */ 
	public function Admin_Find_Campaigns(  ) {
		$args = array( 'key' => $this->key, 'method' => 'Admin_Find_Campaigns' );

		$data = $this->makeCall( $args );
		
		return $data;
	}
	
}
