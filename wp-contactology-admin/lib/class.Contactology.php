<?php
/**
 * Contactology API class
 * 
 * @link     http://www.contactology.com/email-marketing-api/wrappers#PHP
 */
class Contactology {
	protected $url = "api.emailcampaigns.net/2/REST/";
	protected $key;
	private $version = "1.1.2";
	
	/**
	 * Create a new Contactology object to interact with your Contactology account
	 *
	 * @param string key Your API Key - used to authenticate your account
	 */
	public function __construct( $key, $useHTTPS = false ) {
		$this->key = $key;
		$proto = "http://";
		if ( $useHTTPS )
			$proto = "https://";
		$this->url = "{$proto}{$this->url}";
	}
	
			
			
	/**
	 * Upload csv data for mapping and importing via the Contactology UI
	 *
	 * 
	 *
	 * @param string csv Your CSV data
	 * @return struct A struct containing a path key to be used with the webapp
	 */ 
	public function Integration_Upload_Csv( $csv ) {
		$args = array( 'key' => $this->key, 'method' => 'Integration_Upload_Csv' );
		$args['csv'] = $csv;

		$data = $this->makeCall( $args );
		
		return $data;
	}
			
			
	/**
	 * Integrate Contactology into your own web application by setting your CAMPAIGNS_SSO_COOKIE
	 *
	 * Please contact Contactology support for details, this function is only available to reseller accounts
	 *
	 * @param int clientId The client ID you are creating the cookie for, must be a client belonging to your reseller account
	 * @param string username The belonging to the client ID you are logging in with the cookie
	 * @param int time Cookie timeout in seconds
	 * @return string Cookie value to be set in CAMPAIGNS_SSO_COOKIE
	 */ 
	public function Integration_Get_Cookie( $clientId, $username, $time = null ) {
		$args = array( 'key' => $this->key, 'method' => 'Integration_Get_Cookie' );
		$args['clientId'] = $clientId;
		$args['username'] = $username;
		$args['time'] = $time;

		$data = $this->makeCall( $args );
		
		return $data;
	}
			
			
	/**
	 * Integrate Contactology into your own web application by setting your CAMPAIGNS_SSO_COOKIE using your client's credentials
	 *
	 * Please contact Contactology support for details, this function is only available to reseller accounts
	 *
	 * @param string username The of the client
	 * @param string password The current of the client
	 * @param int time Cookie timeout in seconds
	 * @return struct Returns a struct on a valid username/password combo
	 */ 
	public function Integration_Login_Get_Cookie( $username, $password, $time = null ) {
		$args = array( 'key' => $this->key, 'method' => 'Integration_Login_Get_Cookie' );
		$args['username'] = $username;
		$args['password'] = $password;
		$args['time'] = $time;

		$data = $this->makeCall( $args );
		
		return $data;
	}
			
			
	/**
	 * Add a single email address - does not support Custom Fields
	 *
	 * If you need to add a single contact with custom fields, use Contact_Add
	 *
	 * @param string email An address
	 * @param struct optionalParameters A struct of optional parameters, see below for valid keys
	 * @return bool Returns true on success
	 */ 
	public function Contact_Add_Email( $email, $optionalParameters = array() ) {
		$args = array( 'key' => $this->key, 'method' => 'Contact_Add_Email' );
		$args['email'] = $email;
		$args['optionalParameters'] = $optionalParameters;

		$data = $this->makeCall( $args );
		
		return $data;
	}
			
			
	/**
	 * Add a contact with custom fields
	 *
	 * <structnotes customFields>
	 * customFields is a struct which can contain:
	 *  - customFieldToken => value
	 *  - customFieldId => value
	 * 
	 * Ex:
	 * customFields = {'first_name':'Test','last_name':'User'}
	 * 
	 * or
	 * 
	 * customFields = {1:'Test',2:'User'}
	 * </structnotes customFields>
	 *
	 * @param string email Email address of the contact
	 * @param struct customFields is a container for custom field data
	 * @param struct optionalParameters A struct of optional parameters, see below for valid keys
	 * @return bool Returns true on success
	 */ 
	public function Contact_Add( $email, $customFields, $optionalParameters = array() ) {
		$args = array( 'key' => $this->key, 'method' => 'Contact_Add' );
		$args['email'] = $email;
		$args['customFields'] = $customFields;
		$args['optionalParameters'] = $optionalParameters;

		$data = $this->makeCall( $args );
		
		return $data;
	}
			
			
	/**
	 * Add multiple email addresses - does not support Custom Fields
	 *
	 * To add multiple with custom fields, use Contact_Add_Multiple
	 *
	 * @param array emails An array of email addresses
	 * @param struct optionalParameters A struct of optional parameters, see below for valid keys
	 * @return struct Returns a list of email addresses, each marked "true" or "false" showing whether they were suppressed
	 */ 
	public function Contact_Add_Email_Multiple( $emails, $optionalParameters = array() ) {
		$args = array( 'key' => $this->key, 'method' => 'Contact_Add_Email_Multiple' );
		$args['emails'] = $emails;
		$args['optionalParameters'] = $optionalParameters;

		$data = $this->makeCall( $args );
		
		return $data;
	}
			
			
	/**
	 * Add multiple contacts with Custom Fields
	 *
	 * <structnotes contacts>
	 * contacts is an array of structs, where each struct can contain:
	 *  - email => value
	 *  - customFieldToken => value
	 *  - customFieldId => value
	 * 
	 * Each contact struct must have a valid email
	 * 
	 * Ex:
	 * contacts = [
	 * {'email':'test@contactology.com', 'first_name':'Test', 'last_name':'User'},
	 * {'email':'support@contactology.com', 1:'Support', 2:'User'}
	 * ];
	 * 
	 * Ex:
	 * contacts = [
	 * {'email':'test@contactology.com'},
	 * {'email':'support@contactology.com'}
	 * ];
	 * </structnotes contacts>
	 *
	 * @param array contacts Array of contact structs
	 * @param struct optionalParameters A struct of optional parameters, see below for valid keys
	 * @return struct Aggregate import results
	 */ 
	public function Contact_Add_Multiple( $contacts, $optionalParameters = array() ) {
		$args = array( 'key' => $this->key, 'method' => 'Contact_Add_Multiple' );
		$args['contacts'] = $contacts;
		$args['optionalParameters'] = $optionalParameters;

		$data = $this->makeCall( $args );
		
		return $data;
	}
			
			
	/**
	 * Import a collection of contacts.  Can import up to 1000 contacts with a single call.
	 *
	 * This call can be used to import new contacts or update existing contacts.
	 * 
	 * <structnotes contacts>
	 * contacts is an array of structs, where each struct can contain:
	 *  - email => value
	 *  - customFieldToken => value
	 *  - customFieldId => value
	 * 
	 * Each contact_hash must have a valid email
	 * 
	 * Ex:
	 * contacts = [
	 * {'email':'test@contactology.com', 'first_name':'Test', 'last_name':'User'},
	 * {'email':'support@contactology.com', 1:'Support', 2:'User'}
	 * ];
	 * </structnotes contacts>
	 *
	 * @param array contacts Array of contact_hash items, as explained above
	 * @param string source A short description of the of your contacts
	 * @param struct optionalParameters A struct of optional parameters, see below for valid keys
	 * @return struct Aggregate import results
	 */ 
	public function Contact_Import( $contacts, $source, $optionalParameters = array() ) {
		$args = array( 'key' => $this->key, 'method' => 'Contact_Import' );
		$args['contacts'] = $contacts;
		$args['source'] = $source;
		$args['optionalParameters'] = $optionalParameters;

		$data = $this->makeCall( $args );
		
		return $data;
	}
			
			
	/**
	 * Get a count of active contacts
	 *
	 * 
	 *
	 * @return int Returns the number of active contacts for your account
	 */ 
	public function Contact_Get_Active_Count(  ) {
		$args = array( 'key' => $this->key, 'method' => 'Contact_Get_Active_Count' );

		$data = $this->makeCall( $args );
		
		return $data;
	}
			
			
	/**
	 * Get a list of active contacts
	 *
	 * 
	 *
	 * @return array Returns an array containing the email addresses of active contacts
	 */ 
	public function Contact_Get_Active(  ) {
		$args = array( 'key' => $this->key, 'method' => 'Contact_Get_Active' );

		$data = $this->makeCall( $args );
		
		return $data;
	}
			
			
	/**
	 * Get information on a single contact
	 *
	 * 
	 *
	 * @param string email The address of the contact you want information for
	 * @param struct optionalParameters A struct of optional parameters, see below for valid keys
	 * @return struct Returns a struct of structs, keyed off of email address, each containing the keys specified above
	 */ 
	public function Contact_Get( $email, $optionalParameters = array() ) {
		$args = array( 'key' => $this->key, 'method' => 'Contact_Get' );
		$args['email'] = $email;
		$args['optionalParameters'] = $optionalParameters;

		$data = $this->makeCall( $args );
		
		return $data;
	}
			
			
	/**
	 * Get a list of contacts
	 *
	 * score and rating are only returned if one or more of these optionalParameters: scoreMin, scoreMax, ratingMin, or ratingMax is provided.
	 *
	 * @param struct optionalParameters A struct of optional parameters, see below for valid keys
	 * @return struct Returns a struct of structs, keyed off of email address, each containing the keys specified above
	 */ 
	public function Contact_Find( $optionalParameters = array() ) {
		$args = array( 'key' => $this->key, 'method' => 'Contact_Find' );
		$args['optionalParameters'] = $optionalParameters;

		$data = $this->makeCall( $args );
		
		return $data;
	}
			
			
	/**
	 * Update the custom fields of an existing contact
	 *
	 * <structnotes customFields>
	 * customFields is an array which can contain:
	 *  - customFieldToken => value
	 *  - customFieldId => value
	 * </structnotes customFields>
	 *
	 * @param string email The address of the contact you want to update
	 * @param array customFields The custom fields you want to update and their new values
	 * @param struct optionalParameters A struct of optional parameters, see below for valid keys
	 * @return bool Returns true on success
	 */ 
	public function Contact_Update( $email, $customFields, $optionalParameters = array() ) {
		$args = array( 'key' => $this->key, 'method' => 'Contact_Update' );
		$args['email'] = $email;
		$args['customFields'] = $customFields;
		$args['optionalParameters'] = $optionalParameters;

		$data = $this->makeCall( $args );
		
		return $data;
	}
			
			
	/**
	 * Change the email address for an existing contact while preserving list and group subscriptions
	 *
	 * Once you change the contact's email address, you will need to use the new email address to access the contact
	 *
	 * @param string email The current address of the contact
	 * @param string newEmail The contact's new email address
	 * @return struct Returns a struct of structs, keyed off of email address, each containing the keys specified below
	 */ 
	public function Contact_Change_Email( $email, $newEmail ) {
		$args = array( 'key' => $this->key, 'method' => 'Contact_Change_Email' );
		$args['email'] = $email;
		$args['newEmail'] = $newEmail;

		$data = $this->makeCall( $args );
		
		return $data;
	}
			
			
	/**
	 * Return a contact to active status
	 *
	 * 
	 *
	 * @param string email The address of the contact you wish to activate
	 * @return bool Returns true on success
	 */ 
	public function Contact_Activate( $email ) {
		$args = array( 'key' => $this->key, 'method' => 'Contact_Activate' );
		$args['email'] = $email;

		$data = $this->makeCall( $args );
		
		return $data;
	}
			
			
	/**
	 * Delete a contact
	 *
	 * 
	 *
	 * @param string email An address
	 * @return bool True if delete was successful
	 */ 
	public function Contact_Delete( $email ) {
		$args = array( 'key' => $this->key, 'method' => 'Contact_Delete' );
		$args['email'] = $email;

		$data = $this->makeCall( $args );
		
		return $data;
	}
			
			
	/**
	 * Suppress a contact
	 *
	 * 
	 *
	 * @param string email An address
	 * @return bool Returns true or false indicating whether the contact was suppressed
	 */ 
	public function Contact_Suppress( $email ) {
		$args = array( 'key' => $this->key, 'method' => 'Contact_Suppress' );
		$args['email'] = $email;

		$data = $this->makeCall( $args );
		
		return $data;
	}
			
			
	/**
	 * Suppress multiple contacts
	 *
	 * 
	 *
	 * @param array emails An array of email addresses
	 * @return struct Returns a list of email addresses, each marked "true" or "false" showing whether they were suppressed
	 */ 
	public function Contact_Suppress_Multiple( $emails ) {
		$args = array( 'key' => $this->key, 'method' => 'Contact_Suppress_Multiple' );
		$args['emails'] = $emails;

		$data = $this->makeCall( $args );
		
		return $data;
	}
			
			
	/**
	 * Purge a contact
	 *
	 * 
	 *
	 * @param string email An address
	 * @return bool Returns true or false indicating whether the contact was purged
	 */ 
	public function Contact_Purge( $email ) {
		$args = array( 'key' => $this->key, 'method' => 'Contact_Purge' );
		$args['email'] = $email;

		$data = $this->makeCall( $args );
		
		return $data;
	}
			
			
	/**
	 * Get the history for a contact
	 *
	 * All contact history data is returned by default
	 * 
	 * <structnotes optionalParameters>
	 * Format for minDate and maxDate:
	 *  - A specified date/time in the format: YYYY-MM-DD HH:MM:SS
	 *  - A relative time string including, but not limited to:
	 *    * 'now'
	 *    * 'today'
	 *    * 'tomorrow'
	 *    * 'first day of January 2010' (example)
	 *    * 'last day of March 2010' (example)
	 *    * 'Monday this week' (example)
	 *    * 'Tuesday next week' (example)
	 * 
	 * - Values allowed in the historyTypes:
	 *    * click
	 *    * open
	 *    * send
	 *    * status
	 *    * customFields
	 *    * subscription
	 *    * bounce
	 * </structnotes optionalParameters>
	 *
	 * @param string email An address
	 * @param struct optionalParameters A struct of optional parameters, see below for valid keys
	 * @return struct Returns a struct of history information
	 */ 
	public function Contact_Get_History( $email, $optionalParameters = array() ) {
		$args = array( 'key' => $this->key, 'method' => 'Contact_Get_History' );
		$args['email'] = $email;
		$args['optionalParameters'] = $optionalParameters;

		$data = $this->makeCall( $args );
		
		return $data;
	}
			
			
	/**
	 * Get the history for a set of contacts
	 *
	 * All contact history data is returned by default
	 * 
	 * <structnotes optionalParameters>
	 * Format for minDate and maxDate:
	 *  - A specified date/time in the format: YYYY-MM-DD HH:MM:SS
	 *  - A relative time string including, but not limited to:
	 *    * 'now'
	 *    * 'today'
	 *    * 'tomorrow'
	 *    * 'first day of January 2010' (example)
	 *    * 'last day of March 2010' (example)
	 *    * 'Monday this week' (example)
	 *    * 'Tuesday next week' (example)
	 * 
	 * - Values allowed in the historyTypes:
	 *    * click
	 *    * open
	 *    * send
	 *    * status
	 *    * customFields
	 *    * subscription
	 *    * bounce
	 * </structnotes optionalParameters>
	 *
	 * @param struct optionalParameters A struct of optional parameters, see below for valid keys
	 * @return struct Returns a struct of history information
	 */ 
	public function Contact_Get_History_Multiple( $optionalParameters = array() ) {
		$args = array( 'key' => $this->key, 'method' => 'Contact_Get_History_Multiple' );
		$args['optionalParameters'] = $optionalParameters;

		$data = $this->makeCall( $args );
		
		return $data;
	}
			
			
	/**
	 * Get the listIds for all the lists this contact is subscribed to
	 *
	 * 
	 *
	 * @param string email An address
	 * @return array Returns an array of listIds
	 */ 
	public function Contact_Get_Subscriptions( $email ) {
		$args = array( 'key' => $this->key, 'method' => 'Contact_Get_Subscriptions' );
		$args['email'] = $email;

		$data = $this->makeCall( $args );
		
		return $data;
	}
			
			
	/**
	 * Unsubscribe a contact from all lists, then subscribe the contact to the
  specified lists.
	 *
	 * 
	 * After this function is finished, the contact will only be subscribed to
	 *   the lists specified in listIds
	 *
	 * @param string email The address of the contact you wish to set the subscriptions of
	 * @param array listIds An array of the contact should be subscribed to and unsubscribed from all others
	 * @param struct optionalParameters A struct of optional parameters, see below for valid keys
	 * @return array An array of all the lists the contact is subscribed to after the operation, should have the same values as listIds
	 */ 
	public function Contact_Set_Subscriptions( $email, $listIds, $optionalParameters = array() ) {
		$args = array( 'key' => $this->key, 'method' => 'Contact_Set_Subscriptions' );
		$args['email'] = $email;
		$args['listIds'] = $listIds;
		$args['optionalParameters'] = $optionalParameters;

		$data = $this->makeCall( $args );
		
		return $data;
	}
			
			
	/**
	 * Get a list of all email addresses that are not subscribed to any list
	 *
	 * 
	 *
	 * @return array A list of email addresses that are not subscribed to any list
	 */ 
	public function Contact_Get_No_Subscriptions(  ) {
		$args = array( 'key' => $this->key, 'method' => 'Contact_Get_No_Subscriptions' );

		$data = $this->makeCall( $args );
		
		return $data;
	}
			
			
	/**
	 * Get a list of all email addresses that have been sent campaigns but have not opened or clicked
	 *
	 * 
	 *
	 * @return array A list of email addresses have been sent campaigns but have not opened or clicked
	 */ 
	public function Contact_Get_No_Activity(  ) {
		$args = array( 'key' => $this->key, 'method' => 'Contact_Get_No_Activity' );

		$data = $this->makeCall( $args );
		
		return $data;
	}
			
			
	/**
	 * Delete all email addresses that have been sent campaigns but have not opened or clicked
	 *
	 * 
	 *
	 * @return int The number of contacts that were deleted
	 */ 
	public function Contact_Delete_No_Activity(  ) {
		$args = array( 'key' => $this->key, 'method' => 'Contact_Delete_No_Activity' );

		$data = $this->makeCall( $args );
		
		return $data;
	}
			
			
	/**
	 * Retrieve the Person Code for a given contact
	 *
	 * A Contact Person Code is often used in tracking components, link tracking, open tracking, reply tracking
	 *
	 * @param string email The contact's address you want the Person Code for
	 * @return string Person Code
	 */ 
	public function Contact_Get_Person_Code( $email ) {
		$args = array( 'key' => $this->key, 'method' => 'Contact_Get_Person_Code' );
		$args['email'] = $email;

		$data = $this->makeCall( $args );
		
		return $data;
	}
			
			
	/**
	 * Delete all email addresses that are not subscribed to any list
	 *
	 * 
	 *
	 * @return int The number of contacts that were deleted
	 */ 
	public function Contact_Delete_No_Subscriptions(  ) {
		$args = array( 'key' => $this->key, 'method' => 'Contact_Delete_No_Subscriptions' );

		$data = $this->makeCall( $args );
		
		return $data;
	}
			
			
	/**
	 * Add a Textbox CustomField to your signup form
	 *
	 * The CustomField token will be automatically generated based on the fieldName you provide
	 *
	 * @param string fieldName The name of your CustomField - this will be the label for your field on the form
	 * @param bool required Is this field when the form is filled out?
	 * @param bool subscriberCanEdit Can the subscriber edit this value later?
	 * @param struct optionalParameters A struct of optional parameters, see below for valid keys
	 * @return struct Returns a struct containing the new CustomFields fieldId and token
	 */ 
	public function CustomField_Add_Textbox( $fieldName, $required, $subscriberCanEdit, $optionalParameters = array() ) {
		$args = array( 'key' => $this->key, 'method' => 'CustomField_Add_Textbox' );
		$args['fieldName'] = $fieldName;
		$args['required'] = $required;
		$args['subscriberCanEdit'] = $subscriberCanEdit;
		$args['optionalParameters'] = $optionalParameters;

		$data = $this->makeCall( $args );
		
		return $data;
	}
			
			
	/**
	 * Add a Decimal CustomField to your signup form
	 *
	 * The CustomField token will be automatically generated based on the fieldName you provide
	 *
	 * @param string fieldName The name of your CustomField - this will be the label for your field on the form
	 * @param bool required Is this field when the form is filled out?
	 * @param bool subscriberCanEdit Can the subscriber edit this value later?
	 * @param struct optionalParameters A struct of optional parameters, see below for valid keys
	 * @return struct Returns a struct containing the new CustomFields fieldId and token
	 */ 
	public function CustomField_Add_Decimal( $fieldName, $required, $subscriberCanEdit, $optionalParameters = array() ) {
		$args = array( 'key' => $this->key, 'method' => 'CustomField_Add_Decimal' );
		$args['fieldName'] = $fieldName;
		$args['required'] = $required;
		$args['subscriberCanEdit'] = $subscriberCanEdit;
		$args['optionalParameters'] = $optionalParameters;

		$data = $this->makeCall( $args );
		
		return $data;
	}
			
			
	/**
	 * Add an Integer CustomField to your signup form
	 *
	 * The CustomField token will be automatically generated based on the fieldName you provide
	 *
	 * @param string fieldName The name of your CustomField - this will be the label for your field on the form
	 * @param bool required Is this field when the form is filled out?
	 * @param bool subscriberCanEdit Can the subscriber edit this value later?
	 * @param struct optionalParameters A struct of optional parameters, see below for valid keys
	 * @return struct Returns a struct containing the new CustomFields fieldId and token
	 */ 
	public function CustomField_Add_Integer( $fieldName, $required, $subscriberCanEdit, $optionalParameters = array() ) {
		$args = array( 'key' => $this->key, 'method' => 'CustomField_Add_Integer' );
		$args['fieldName'] = $fieldName;
		$args['required'] = $required;
		$args['subscriberCanEdit'] = $subscriberCanEdit;
		$args['optionalParameters'] = $optionalParameters;

		$data = $this->makeCall( $args );
		
		return $data;
	}
			
			
	/**
	 * Add a Dropdown CustomField to your signup form
	 *
	 * The CustomField token will be automatically generated based on the fieldName you provide
	 *
	 * @param string fieldName The name of your CustomField - this will be the label for your field on the form
	 * @param bool required Is this field when the form is filled out?
	 * @param bool subscriberCanEdit Can the subscriber edit this value later?
	 * @param array options An array of strings to be shown in the dropdown
	 * @param struct optionalParameters A struct of optional parameters, see below for valid keys
	 * @return struct Returns a struct containing the new CustomFields fieldId and token
	 */ 
	public function CustomField_Add_Dropdown( $fieldName, $required, $subscriberCanEdit, $options, $optionalParameters = array() ) {
		$args = array( 'key' => $this->key, 'method' => 'CustomField_Add_Dropdown' );
		$args['fieldName'] = $fieldName;
		$args['required'] = $required;
		$args['subscriberCanEdit'] = $subscriberCanEdit;
		$args['options'] = $options;
		$args['optionalParameters'] = $optionalParameters;

		$data = $this->makeCall( $args );
		
		return $data;
	}
			
			
	/**
	 * Add a Radio CustomField to your signup form
	 *
	 * The CustomField token will be automatically generated based on the fieldName you provide
	 *
	 * @param string fieldName The name of your CustomField - this will be the label for your field on the form
	 * @param bool required Is this field when the form is filled out?
	 * @param bool subscriberCanEdit Can the subscriber edit this value later?
	 * @param array options An array of strings, each value will have its own radio button
	 * @param struct optionalParameters A struct of optional parameters, see below for valid keys
	 * @return struct Returns a struct containing the new CustomFields fieldId and token
	 */ 
	public function CustomField_Add_Radio( $fieldName, $required, $subscriberCanEdit, $options, $optionalParameters = array() ) {
		$args = array( 'key' => $this->key, 'method' => 'CustomField_Add_Radio' );
		$args['fieldName'] = $fieldName;
		$args['required'] = $required;
		$args['subscriberCanEdit'] = $subscriberCanEdit;
		$args['options'] = $options;
		$args['optionalParameters'] = $optionalParameters;

		$data = $this->makeCall( $args );
		
		return $data;
	}
			
			
	/**
	 * Add a single Checkbox CustomField to your signup form
	 *
	 * The CustomField token will be automatically generated based on the fieldName you provide
	 *
	 * @param string fieldName The name of your CustomField - this will be the label for your field on the form
	 * @param bool required Is this field when the form is filled out?
	 * @param bool subscriberCanEdit Can the subscriber edit this value later?
	 * @param struct optionalParameters A struct of optional parameters, see below for valid keys
	 * @return struct Returns a struct containing the new CustomFields fieldId and token
	 */ 
	public function CustomField_Add_Checkbox( $fieldName, $required, $subscriberCanEdit, $optionalParameters = array() ) {
		$args = array( 'key' => $this->key, 'method' => 'CustomField_Add_Checkbox' );
		$args['fieldName'] = $fieldName;
		$args['required'] = $required;
		$args['subscriberCanEdit'] = $subscriberCanEdit;
		$args['optionalParameters'] = $optionalParameters;

		$data = $this->makeCall( $args );
		
		return $data;
	}
			
			
	/**
	 * Add a CheckboxList CustomField to your signup form
	 *
	 * The CustomField token will be automatically generated based on the fieldName you provide
	 *
	 * @param string fieldName The name of your CustomField - this will be the label for your field on the form
	 * @param bool required Is this field when the form is filled out?
	 * @param bool subscriberCanEdit Can the subscriber edit this value later?
	 * @param array options An array of strings, each value will have its own checkbox
	 * @param struct optionalParameters A struct of optional parameters, see below for valid keys
	 * @return struct Returns a struct containing the new CustomFields fieldId and token
	 */ 
	public function CustomField_Add_CheckboxList( $fieldName, $required, $subscriberCanEdit, $options, $optionalParameters = array() ) {
		$args = array( 'key' => $this->key, 'method' => 'CustomField_Add_CheckboxList' );
		$args['fieldName'] = $fieldName;
		$args['required'] = $required;
		$args['subscriberCanEdit'] = $subscriberCanEdit;
		$args['options'] = $options;
		$args['optionalParameters'] = $optionalParameters;

		$data = $this->makeCall( $args );
		
		return $data;
	}
			
			
	/**
	 * Add a Date CustomField to your signup form
	 *
	 * The CustomField token will be automatically generated based on the fieldName you provide
	 *
	 * @param string fieldName The name of your CustomField - this will be the label for your field on the form
	 * @param bool required Is this field when the form is filled out?
	 * @param bool subscriberCanEdit Can the subscriber edit this value later?
	 * @param struct optionalParameters A struct of optional parameters, see below for valid keys
	 * @return struct Returns a struct containing the new CustomFields fieldId and token
	 */ 
	public function CustomField_Add_Date( $fieldName, $required, $subscriberCanEdit, $optionalParameters = array() ) {
		$args = array( 'key' => $this->key, 'method' => 'CustomField_Add_Date' );
		$args['fieldName'] = $fieldName;
		$args['required'] = $required;
		$args['subscriberCanEdit'] = $subscriberCanEdit;
		$args['optionalParameters'] = $optionalParameters;

		$data = $this->makeCall( $args );
		
		return $data;
	}
			
			
	/**
	 * Add an Email CustomField to your signup form
	 *
	 * The CustomField token will be automatically generated based on the fieldName you provide
	 *
	 * @param string fieldName The name of your CustomField - this will be the label for your field on the form
	 * @param bool required Is this field when the form is filled out?
	 * @param bool subscriberCanEdit Can the subscriber edit this value later?
	 * @param struct optionalParameters A struct of optional parameters, see below for valid keys
	 * @return struct Returns a struct containing the new CustomFields fieldId and token
	 */ 
	public function CustomField_Add_Email( $fieldName, $required, $subscriberCanEdit, $optionalParameters = array() ) {
		$args = array( 'key' => $this->key, 'method' => 'CustomField_Add_Email' );
		$args['fieldName'] = $fieldName;
		$args['required'] = $required;
		$args['subscriberCanEdit'] = $subscriberCanEdit;
		$args['optionalParameters'] = $optionalParameters;

		$data = $this->makeCall( $args );
		
		return $data;
	}
			
			
	/**
	 * Add a Phone CustomField to your signup form
	 *
	 * The CustomField token will be automatically generated based on the fieldName you provide
	 *
	 * @param string fieldName The name of your CustomField - this will be the label for your field on the form
	 * @param bool required Is this field when the form is filled out?
	 * @param bool subscriberCanEdit Can the subscriber edit this value later?
	 * @param struct optionalParameters A struct of optional parameters, see below for valid keys
	 * @return struct Returns a struct containing the new CustomFields fieldId and token
	 */ 
	public function CustomField_Add_Phone( $fieldName, $required, $subscriberCanEdit, $optionalParameters = array() ) {
		$args = array( 'key' => $this->key, 'method' => 'CustomField_Add_Phone' );
		$args['fieldName'] = $fieldName;
		$args['required'] = $required;
		$args['subscriberCanEdit'] = $subscriberCanEdit;
		$args['optionalParameters'] = $optionalParameters;

		$data = $this->makeCall( $args );
		
		return $data;
	}
			
			
	/**
	 * Add a StateDropdown CustomField to your signup form
	 *
	 * The CustomField token will be automatically generated based on the fieldName you provide
	 *
	 * @param string fieldName The name of your CustomField - this will be the label for your field on the form
	 * @param bool required Is this field when the form is filled out?
	 * @param bool subscriberCanEdit Can the subscriber edit this value later?
	 * @param struct optionalParameters A struct of optional parameters, see below for valid keys
	 * @return struct Returns a struct containing the new CustomFields fieldId and token
	 */ 
	public function CustomField_Add_StateDropdown( $fieldName, $required, $subscriberCanEdit, $optionalParameters = array() ) {
		$args = array( 'key' => $this->key, 'method' => 'CustomField_Add_StateDropdown' );
		$args['fieldName'] = $fieldName;
		$args['required'] = $required;
		$args['subscriberCanEdit'] = $subscriberCanEdit;
		$args['optionalParameters'] = $optionalParameters;

		$data = $this->makeCall( $args );
		
		return $data;
	}
			
			
	/**
	 * Add an Address CustomField to your signup form
	 *
	 * The CustomField token will be automatically generated based on the fieldName you provide
	 *
	 * @param string fieldName The name of your CustomField - this will be the label for your field on the form
	 * @param bool required Is this field when the form is filled out?
	 * @param bool subscriberCanEdit Can the subscriber edit this value later?
	 * @param struct optionalParameters A struct of optional parameters, see below for valid keys
	 * @return struct Returns a struct containing the new CustomFields fieldId and token
	 */ 
	public function CustomField_Add_Address( $fieldName, $required, $subscriberCanEdit, $optionalParameters = array() ) {
		$args = array( 'key' => $this->key, 'method' => 'CustomField_Add_Address' );
		$args['fieldName'] = $fieldName;
		$args['required'] = $required;
		$args['subscriberCanEdit'] = $subscriberCanEdit;
		$args['optionalParameters'] = $optionalParameters;

		$data = $this->makeCall( $args );
		
		return $data;
	}
			
			
	/**
	 * Get a list of CustomFields - excluding searchParameters indicates you want a list of all CustomFields
	 *
	 * 
	 *
	 * @param struct searchParameters Provide a struct to narrow your results
	 * @return struct Returns a struct for each CustomField found
	 */ 
	public function CustomField_Find( $searchParameters = array() ) {
		$args = array( 'key' => $this->key, 'method' => 'CustomField_Find' );
		$args['searchParameters'] = $searchParameters;

		$data = $this->makeCall( $args );
		
		return $data;
	}
			
			
	/**
	 * Get a list of all current CustomFields
	 *
	 * 
	 *
	 * @return struct Returns a struct for each CustomField found
	 */ 
	public function CustomField_Get_All(  ) {
		$args = array( 'key' => $this->key, 'method' => 'CustomField_Get_All' );

		$data = $this->makeCall( $args );
		
		return $data;
	}
			
			
	/**
	 * Update an existing CustomField
	 *
	 * 
	 *
	 * @param int fieldId The of the CustomField you want to modify
	 * @param struct updateParameters A struct of replacement values for your CustomField - only specify fields that you want to change
	 * @return struct Returns a struct with your CustomField's new properties
	 */ 
	public function CustomField_Update( $fieldId, $updateParameters ) {
		$args = array( 'key' => $this->key, 'method' => 'CustomField_Update' );
		$args['fieldId'] = $fieldId;
		$args['updateParameters'] = $updateParameters;

		$data = $this->makeCall( $args );
		
		return $data;
	}
			
			
	/**
	 * Update the displayOrder property on multiple fields at once
	 *
	 * 
	 *
	 * @param struct reorder A struct where each key is a fieldId and each value is the new displayOrder
	 * @return struct Returns a struct for each CustomField updated
	 */ 
	public function CustomField_Reorder( $reorder ) {
		$args = array( 'key' => $this->key, 'method' => 'CustomField_Reorder' );
		$args['reorder'] = $reorder;

		$data = $this->makeCall( $args );
		
		return $data;
	}
			
			
	/**
	 * Delete a CustomField - this action cannot be undone
	 *
	 * 
	 *
	 * @param int fieldId The of the CustomField you want to delete - first_name, last_name and email_address cannot be deleted
	 * @return bool Returns true on success
	 */ 
	public function CustomField_Delete( $fieldId ) {
		$args = array( 'key' => $this->key, 'method' => 'CustomField_Delete' );
		$args['fieldId'] = $fieldId;

		$data = $this->makeCall( $args );
		
		return $data;
	}
			
			
	/**
	 * Create a new test contact list
	 *
	 * A Test Contact List is a special Private list that can be sent test campaigns
	 *
	 * @param string name The of your list
	 * @param struct optionalParameters A struct of optional parameters, see below for valid keys
	 * @return int id Returns the List ID of your new List
	 */ 
	public function List_Add_Test( $name, $optionalParameters = array() ) {
		$args = array( 'key' => $this->key, 'method' => 'List_Add_Test' );
		$args['name'] = $name;
		$args['optionalParameters'] = $optionalParameters;

		$data = $this->makeCall( $args );
		
		return $data;
	}
			
			
	/**
	 * Create a new internal contact list
	 *
	 * Contacts will not see this list if they edit their profile nor will they be able to subscribe to this list from the subscription center. If an internal list member unsubscribes (via link in a message), they will be Globally Unsubscribed from your database.
	 *
	 * @param string name The of your list
	 * @param struct optionalParameters A struct of optional parameters, see below for valid keys
	 * @return int id Returns the List ID of your new List
	 */ 
	public function List_Add_Internal( $name, $optionalParameters = array() ) {
		$args = array( 'key' => $this->key, 'method' => 'List_Add_Internal' );
		$args['name'] = $name;
		$args['optionalParameters'] = $optionalParameters;

		$data = $this->makeCall( $args );
		
		return $data;
	}
			
			
	/**
	 * Create a new private contact list
	 *
	 * Choose this option if your contacts must be 'authorized' before they can be on the list. Private lists are not publicly available to sign up for, but will be seen by recipients in the subscription center if they modify their contact information. Example: Alumni lists
	 * 
	 * 
	 * <structnotes optInMesage>
	 * Example optInMessage:
	 * 
	 * <!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN">
	 * <html>
	 * <head>
	 * <title>Message</title>
	 * </head>
	 * <body style="font-family: Times New Roman;font-size: 12px;">
	 * <p>Dear Customer,</p>
	 * <p>Thank you for subscribing to our list.  To get started, please <a href="{confirm_url}">click here</a> to confirm your subscription.</p>
	 * <p>Thank you,<br />Unit Test Team</p>
	 * </body>
	 * </html>
	 * </structnotes optInMesage>
	 *
	 * @param string name The of your list
	 * @param struct optionalParameters A struct of optional parameters, see below for valid keys
	 * @return int id Returns the List ID of your new List
	 */ 
	public function List_Add_Private( $name, $optionalParameters = array() ) {
		$args = array( 'key' => $this->key, 'method' => 'List_Add_Private' );
		$args['name'] = $name;
		$args['optionalParameters'] = $optionalParameters;

		$data = $this->makeCall( $args );
		
		return $data;
	}
			
			
	/**
	 * Create a new public contact list
	 *
	 * Choose this option if you want to allow anyone to sign up for this list via your website. Example: Newsletters
	 * 
	 * 
	 * <structnotes optInMesage>
	 * Example optInMessage:
	 * 
	 * <!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN">
	 * <html>
	 * <head>
	 * <title>Message</title>
	 * </head>
	 * <body style="font-family: Times New Roman;font-size: 12px;">
	 * <p>Dear Customer,</p>
	 * <p>Thank you for subscribing to our list.  To get started, please <a href="{confirm_url}">click here</a> to confirm your subscription.</p>
	 * <p>Thank you,<br />Unit Test Team</p>
	 * </body>
	 * </html>
	 * </structnotes optInMesage>
	 *
	 * @param string name The of your list
	 * @param struct optionalParameters A struct of optional parameters, see below for valid keys
	 * @return int id Returns the List ID of your new List
	 */ 
	public function List_Add_Public( $name, $optionalParameters = array() ) {
		$args = array( 'key' => $this->key, 'method' => 'List_Add_Public' );
		$args['name'] = $name;
		$args['optionalParameters'] = $optionalParameters;

		$data = $this->makeCall( $args );
		
		return $data;
	}
			
			
	/**
	 * Delete a list you created
	 *
	 * 
	 *
	 * @param int listId The List ID of the list you wish to delete
	 * @return bool Returns true on success
	 */ 
	public function List_Delete( $listId ) {
		$args = array( 'key' => $this->key, 'method' => 'List_Delete' );
		$args['listId'] = $listId;

		$data = $this->makeCall( $args );
		
		return $data;
	}
			
			
	/**
	 * Add an email address contact to an existing list
	 *
	 * 
	 *
	 * @param int listId The ID of the list you want to subscribe the email address to
	 * @param string email The address you are subscribing
	 * @param struct optionalParameters A struct of optional parameters, see below for valid keys
	 * @return bool True on success
	 */ 
	public function List_Subscribe( $listId, $email, $optionalParameters = array() ) {
		$args = array( 'key' => $this->key, 'method' => 'List_Subscribe' );
		$args['listId'] = $listId;
		$args['email'] = $email;
		$args['optionalParameters'] = $optionalParameters;

		$data = $this->makeCall( $args );
		
		return $data;
	}
			
			
	/**
	 * Remove an email address contact from an existing list
	 *
	 * 
	 *
	 * @param int listId The ID of the list you want to unsubscribe the email address from
	 * @param string email The address you are unsubscribing
	 * @param struct optionalParameters A struct of optional parameters, see below for valid keys
	 * @return bool True on success
	 */ 
	public function List_Unsubscribe( $listId, $email, $optionalParameters = array() ) {
		$args = array( 'key' => $this->key, 'method' => 'List_Unsubscribe' );
		$args['listId'] = $listId;
		$args['email'] = $email;
		$args['optionalParameters'] = $optionalParameters;

		$data = $this->makeCall( $args );
		
		return $data;
	}
			
			
	/**
	 * Remove multiple email address contacts from an existing list
	 *
	 * 
	 *
	 * @param int listId The ID of the list you want to unsubscribe the email address from
	 * @param array emails An array of email addresses you are unsubscribing
	 * @return int Returns the number of contacts removed
	 */ 
	public function List_Unsubscribe_Multiple( $listId, $emails ) {
		$args = array( 'key' => $this->key, 'method' => 'List_Unsubscribe_Multiple' );
		$args['listId'] = $listId;
		$args['emails'] = $emails;

		$data = $this->makeCall( $args );
		
		return $data;
	}
			
			
	/**
	 * Retrieve a list of contacts in a given list
	 *
	 * 
	 *
	 * @param int listId The ID of the list you retrieve contacts from
	 * @param struct optionalParameters A struct of optional parameters, see below for valid keys
	 * @return struct Struct of records each with a key of email and values of contactId, email, status, statusCode and listStatus
	 */ 
	public function List_Get_Contacts( $listId, $optionalParameters = array() ) {
		$args = array( 'key' => $this->key, 'method' => 'List_Get_Contacts' );
		$args['listId'] = $listId;
		$args['optionalParameters'] = $optionalParameters;

		$data = $this->makeCall( $args );
		
		return $data;
	}
			
			
	/**
	 * Get a listing of currently active lists
	 *
	 * 
	 *
	 * @param struct optionalParameters A struct of optional parameters, see below for valid keys
	 * @return struct Array of records with the key of listId and values of listId, name, description and type
	 */ 
	public function List_Get_Active_Lists( $optionalParameters = array() ) {
		$args = array( 'key' => $this->key, 'method' => 'List_Get_Active_Lists' );
		$args['optionalParameters'] = $optionalParameters;

		$data = $this->makeCall( $args );
		
		return $data;
	}
			
			
	/**
	 * Get information about a list, including name, type, easyCast, listOwner and optIn info (where applicable)
	 *
	 * 
	 *
	 * @param int listId The ID of the list you are getting info for
	 * @return struct Returns info about the requested list
	 */ 
	public function List_Get_Info( $listId ) {
		$args = array( 'key' => $this->key, 'method' => 'List_Get_Info' );
		$args['listId'] = $listId;

		$data = $this->makeCall( $args );
		
		return $data;
	}
			
			
	/**
	 * Import a collection of contacts into a given list
	 *
	 * <structnotes contacts>
	 * contacts is an array of structs, where each struct can contain:
	 *  - email => value
	 *  - customFieldToken => value
	 *  - customFieldId => value
	 * 
	 * Each contact_hash must have a valid email
	 * 
	 * Ex:
	 * contacts = [
	 * {'email':'test@contactology.com', 'first_name':'Test', 'last_name':'User'},
	 * {'email':'support@contactology.com', 1:'Support', 2:'User'}
	 * ];
	 * </structnotes contacts>
	 *
	 * @param int listId The ID of the list you are importing contacts into
	 * @param string source A short description of the of your contacts
	 * @param array contacts Array of contact_hash items, as explained above
	 * @param struct optionalParameters A struct of optional parameters, see below for valid keys
	 * @return struct Aggregate import results
	 */ 
	public function List_Import_Contacts( $listId, $source, $contacts, $optionalParameters = array() ) {
		$args = array( 'key' => $this->key, 'method' => 'List_Import_Contacts' );
		$args['listId'] = $listId;
		$args['source'] = $source;
		$args['contacts'] = $contacts;
		$args['optionalParameters'] = $optionalParameters;

		$data = $this->makeCall( $args );
		
		return $data;
	}
			
			
	/**
	 * Import a collection of contacts into a given list asyncrhonously
	 *
	 * This call is a companion to List_Import_Contacts, but it's designed to integrate with webapps - using this call, you can get very fast responses from the API so that your webapp can continue its user-flow without waiting for all of your contacts to be verified and imported.
	 * 
	 * We'll send the return values to use via a POST to your callbackUrl which will include the jobId, chunkNum and a data struct containing the results of the import following the same format as the return value of List_Import_Contacts
	 * 
	 * <structnotes contacts>
	 * contacts is an array of structs, where each struct can contain:
	 *  - email => value
	 *  - customFieldToken => value
	 *  - customFieldId => value
	 * 
	 * Each contact_hash must have a valid email
	 * 
	 * Ex:
	 * contacts = [
	 * {'email':'test@contactology.com', 'first_name':'Test', 'last_name':'User'},
	 * {'email':'support@contactology.com', 1:'Support', 2:'User'}
	 * ];
	 * </structnotes contacts>
	 *
	 * @param int listId The ID of the list you are importing contacts into
	 * @param string source A short description of the of your contacts
	 * @param array contacts Array of contact_hash items, as explained above
	 * @param string callbackUrl A URL endpoint for the results of the import to be POSTed to
	 * @param string jobId A Job ID used to match up the import in your webapp
	 * @param int chunkNum An Import Chunk number used to match up in your webapp - use this to keep track of what chunks have been processed, they may not be handled in order
	 * @param struct optionalParameters A struct of optional parameters, see below for valid keys
	 * @return bool 
	 */ 
	public function List_Import_Contacts_Delayed( $listId, $source, $contacts, $callbackUrl, $jobId, $chunkNum, $optionalParameters = array() ) {
		$args = array( 'key' => $this->key, 'method' => 'List_Import_Contacts_Delayed' );
		$args['listId'] = $listId;
		$args['source'] = $source;
		$args['contacts'] = $contacts;
		$args['callbackUrl'] = $callbackUrl;
		$args['jobId'] = $jobId;
		$args['chunkNum'] = $chunkNum;
		$args['optionalParameters'] = $optionalParameters;

		$data = $this->makeCall( $args );
		
		return $data;
	}
			
			
	/**
	 * Evaluate a collection of contacts
	 *
	 * This function works exactly like List_Import_Contacts, but will not actually insert or modify any contacts.  This function can be used to evaluate a large list of contacts to find problematic entries before performing an actual insert.
	 * 
	 * Batches of 50,000 contacts can be evaluated
	 * 
	 * <structnotes contacts>
	 * contacts is an array of structs, where each struct can contain:
	 *  - email => value
	 *  - customFieldToken => value
	 *  - customFieldId => value
	 * 
	 * Each contact_hash must have a valid email
	 * 
	 * Ex:
	 * contacts = [
	 * {'email':'test@contactology.com', 'first_name':'Test', 'last_name':'User'},
	 * {'email':'support@contactology.com', 1:'Support', 2:'User'}
	 * ];
	 * </structnotes contacts>
	 *
	 * @param array contacts Array of contact_hash items, as explained above
	 * @return struct Aggregate import results
	 */ 
	public function List_Evaluate_Contacts( $contacts ) {
		$args = array( 'key' => $this->key, 'method' => 'List_Evaluate_Contacts' );
		$args['contacts'] = $contacts;

		$data = $this->makeCall( $args );
		
		return $data;
	}
			
			
	/**
	 * Get the number of contacts in a given list
	 *
	 * 
	 *
	 * @param int listId The ID of the list you want the count from
	 * @param string status Count only contacts with a particular list status, valid values: subscribed, unsubscribed, bounced (defaults to subscribed)
	 * @return int Number of contacts in list
	 */ 
	public function List_Get_Count( $listId, $status = '' ) {
		$args = array( 'key' => $this->key, 'method' => 'List_Get_Count' );
		$args['listId'] = $listId;
		$args['status'] = $status;

		$data = $this->makeCall( $args );
		
		return $data;
	}
			
			
	/**
	 * Update an existing Test List
	 *
	 * Update Parameters can include:
	 *  - name: The name of your list
	 *  - description:  A short description for your list
	 *  - easycastName: A one word shortcut for EasyCast access, will create an EasyCast email address in the format NAME.list.YOURID@send.emailcampaigns.net
	 *  - listOwnerEmail: An email address that receives an email whenever a contact subscribes to this list, and can approve "EasyCast" messages
	 *
	 * @param int listId The of the list you want to modify
	 * @param struct updateParameters A struct of replacement values for your list - only specify items that you want to change
	 * @return bool Returns true on success
	 */ 
	public function List_Update_Test( $listId, $updateParameters ) {
		$args = array( 'key' => $this->key, 'method' => 'List_Update_Test' );
		$args['listId'] = $listId;
		$args['updateParameters'] = $updateParameters;

		$data = $this->makeCall( $args );
		
		return $data;
	}
			
			
	/**
	 * Update an existing Internal List
	 *
	 * Update Parameters can include:
	 *  - name: The name of your list
	 *  - description:  A short description for your list
	 *  - easycastName: A one word shortcut for EasyCast access, will create an EasyCast email address in the format NAME.list.YOURID@send.emailcampaigns.net
	 *  - listOwnerEmail: An email address that receives an email whenever a contact subscribes to this list, and can approve "EasyCast" messages
	 *
	 * @param int listId The of the list you want to modify
	 * @param struct updateParameters A struct of replacement values for your list - only specify items that you want to change
	 * @return bool Returns true on success
	 */ 
	public function List_Update_Internal( $listId, $updateParameters ) {
		$args = array( 'key' => $this->key, 'method' => 'List_Update_Internal' );
		$args['listId'] = $listId;
		$args['updateParameters'] = $updateParameters;

		$data = $this->makeCall( $args );
		
		return $data;
	}
			
			
	/**
	 * Update an existing Private List
	 *
	 * Update Parameters can include:
	 *  - string description A short description for your list
	 *  - string easycastName A one word shortcut for EasyCast access, will create an EasyCast email address in the format NAME.list.YOURID@send.emailcampaigns.net
	 *  - string listOwnerEmail An email address that receives an email whenever a contact subscribes to this list, and can approve "EasyCast" messages
	 *  - bool optIn Setting this to true means that the system will send contacts a confirmation email before sending them messages
	 *  - string optInFromEmail The email address that the optInMessage confirmation email will be sent from.  Required if optIn is true
	 *  - string optInFromEmailAlias The From Name that the optInMessage confirmation email will be sent from.
	 *  - string optInSubject The subject line of the optInMessage confirmation email.  Required if optIn is true
	 *  - string optInMessage The HTML email body for the confirmation email. MUST include the {confirm_url} token that will insert the link to the correct subscription confirmation page.  Required if optIn is true
	 * 
	 * Example optInMessage:
	 * 
	 * <!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN">
	 * <html>
	 * <head>
	 * <title>Message</title>
	 * </head>
	 * <body style="font-family: Times New Roman;font-size: 12px;">
	 * <p>Dear Customer,</p>
	 * <p>Thank you for subscribing to our list.  To get started, please <a href="{confirm_url}">click here</a> to confirm your subscription.</p>
	 * <p>Thank you,<br />Unit Test Team</p>
	 * </body>
	 * </html>
	 *
	 * @param int listId The of the list you want to modify
	 * @param struct updateParameters A struct of replacement values for your list - only specify items that you want to change
	 * @return bool Returns true on success
	 */ 
	public function List_Update_Private( $listId, $updateParameters ) {
		$args = array( 'key' => $this->key, 'method' => 'List_Update_Private' );
		$args['listId'] = $listId;
		$args['updateParameters'] = $updateParameters;

		$data = $this->makeCall( $args );
		
		return $data;
	}
			
			
	/**
	 * Update an existing Public List
	 *
	 * Update Parameters can include:
	 *  - string description A short description for your list
	 *  - string easycastName A one word shortcut for EasyCast access, will create an EasyCast email address in the format NAME.list.YOURID@send.emailcampaigns.net
	 *  - string listOwnerEmail An email address that receives an email whenever a contact subscribes to this list, and can approve "EasyCast" messages
	 *  - bool optIn Setting this to true means that the system will send contacts a confirmation email before sending them messages
	 *  - string optInFromEmail The email address that the optInMessage confirmation email will be sent from.  Required if optIn is true
	 *  - string optInFromEmailAlias The From Name that the optInMessage confirmation email will be sent from.
	 *  - string optInSubject The subject line of the optInMessage confirmation email.  Required if optIn is true
	 *  - string optInMessage The HTML email body for the confirmation email. MUST include the {confirm_url} token that will insert the link to the correct subscription confirmation page.  Required if optIn is true
	 * 
	 * Example optInMessage:
	 * 
	 * <!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN">
	 * <html>
	 * <head>
	 * <title>Message</title>
	 * </head>
	 * <body style="font-family: Times New Roman;font-size: 12px;">
	 * <p>Dear Customer,</p>
	 * <p>Thank you for subscribing to our list.  To get started, please <a href="{confirm_url}">click here</a> to confirm your subscription.</p>
	 * <p>Thank you,<br />Unit Test Team</p>
	 * </body>
	 * </html>
	 *
	 * @param int listId The of the list you want to modify
	 * @param struct updateParameters A struct of replacement values for your list - only specify items that you want to change
	 * @return bool Returns true on success
	 */ 
	public function List_Update_Public( $listId, $updateParameters ) {
		$args = array( 'key' => $this->key, 'method' => 'List_Update_Public' );
		$args['listId'] = $listId;
		$args['updateParameters'] = $updateParameters;

		$data = $this->makeCall( $args );
		
		return $data;
	}
			
			
	/**
	 * Create a new Group
	 *
	 * Contact groups are simple collections of contacts.  They do not have subscription information or appear in the subscription center like a list, but you can add and remove contacts from groups through imports and quick adds.  The purpose of contact groups is to provide a simple way of grouping your contacts together, without the added complexity of list subscriptions.  Because groups are so simple, the only information you need to create a group is a name.
	 *
	 * @param string name The of your group
	 * @return int id Returns the Group ID of your new Group
	 */ 
	public function Group_Create( $name ) {
		$args = array( 'key' => $this->key, 'method' => 'Group_Create' );
		$args['name'] = $name;

		$data = $this->makeCall( $args );
		
		return $data;
	}
			
			
	/**
	 * Update an existing group
	 *
	 * 
	 *
	 * @param int groupId The of the group you want to modify
	 * @param string name The new of your group
	 * @return bool Returns true on success
	 */ 
	public function Group_Update( $groupId, $name ) {
		$args = array( 'key' => $this->key, 'method' => 'Group_Update' );
		$args['groupId'] = $groupId;
		$args['name'] = $name;

		$data = $this->makeCall( $args );
		
		return $data;
	}
			
			
	/**
	 * Delete a group you created
	 *
	 * 
	 *
	 * @param int groupId The Group ID of the group you wish to delete
	 * @return bool Returns true on success
	 */ 
	public function Group_Delete( $groupId ) {
		$args = array( 'key' => $this->key, 'method' => 'Group_Delete' );
		$args['groupId'] = $groupId;

		$data = $this->makeCall( $args );
		
		return $data;
	}
			
			
	/**
	 * Add an email address contact to an existing group
	 *
	 * 
	 *
	 * @param int groupId The ID of the group you want to subscribe the email address to
	 * @param string email The address you are subscribing
	 * @return bool True on success
	 */ 
	public function Group_Add_Contact( $groupId, $email ) {
		$args = array( 'key' => $this->key, 'method' => 'Group_Add_Contact' );
		$args['groupId'] = $groupId;
		$args['email'] = $email;

		$data = $this->makeCall( $args );
		
		return $data;
	}
			
			
	/**
	 * Remove an email address contact from an existing group
	 *
	 * 
	 *
	 * @param int groupId The ID of the group you want to unsubscribe the email address to
	 * @param string email The address you are unsubscribing
	 * @return bool True on success
	 */ 
	public function Group_Remove_Contact( $groupId, $email ) {
		$args = array( 'key' => $this->key, 'method' => 'Group_Remove_Contact' );
		$args['groupId'] = $groupId;
		$args['email'] = $email;

		$data = $this->makeCall( $args );
		
		return $data;
	}
			
			
	/**
	 * Remove multiple email address contacts from an existing group
	 *
	 * 
	 *
	 * @param int groupId The ID of the group you want to remove the email address from
	 * @param array emails An array of email addresses you are removing from the group
	 * @return int Returns the number of contacts removed
	 */ 
	public function Group_Remove_Contacts_Multiple( $groupId, $emails ) {
		$args = array( 'key' => $this->key, 'method' => 'Group_Remove_Contacts_Multiple' );
		$args['groupId'] = $groupId;
		$args['emails'] = $emails;

		$data = $this->makeCall( $args );
		
		return $data;
	}
			
			
	/**
	 * Retrieve a list of contacts in a given group
	 *
	 * 
	 *
	 * @param int groupId The ID of the group you retrieve contacts from
	 * @param struct optionalParameters A struct of optional parameters, see below for valid keys
	 * @return struct Struct of records each with a key of the contact's email addressemail and values of contactId, email, status and statusCode
	 */ 
	public function Group_Get_Contacts( $groupId, $optionalParameters = array() ) {
		$args = array( 'key' => $this->key, 'method' => 'Group_Get_Contacts' );
		$args['groupId'] = $groupId;
		$args['optionalParameters'] = $optionalParameters;

		$data = $this->makeCall( $args );
		
		return $data;
	}
			
			
	/**
	 * Get a listing of currently active groups
	 *
	 * 
	 *
	 * @return struct Struct of records with the key of groupId and value of name
	 */ 
	public function Group_List(  ) {
		$args = array( 'key' => $this->key, 'method' => 'Group_List' );

		$data = $this->makeCall( $args );
		
		return $data;
	}
			
			
	/**
	 * Import a collection of contacts into a given group
	 *
	 * <structnotes contacts>
	 * contacts is an array of associative arrays, where each associative array can contain:
	 *  - email => value
	 *  - customFieldToken => value
	 *  - customFieldId => value
	 * 
	 * Each contact must have a valid email
	 * 
	 * Ex:
	 * contacts = [
	 * {'email':'test@contactology.com','first_name':'Test','last_name':'User'},
	 * {'email':'support@contactology.com',1:'Support',2:'User'}
	 * ];
	 * </structnotes contacts>
	 *
	 * @param int groupId The ID of the group you are importing contacts into
	 * @param string source A short description of the of your contacts
	 * @param array contacts Array of contact_hash items, as explained above
	 * @param struct optionalParameters A struct of optional parameters, see below for valid keys
	 * @return struct Aggregate import results
	 */ 
	public function Group_Import_Contacts( $groupId, $source, $contacts, $optionalParameters = array() ) {
		$args = array( 'key' => $this->key, 'method' => 'Group_Import_Contacts' );
		$args['groupId'] = $groupId;
		$args['source'] = $source;
		$args['contacts'] = $contacts;
		$args['optionalParameters'] = $optionalParameters;

		$data = $this->makeCall( $args );
		
		return $data;
	}
			
			
	/**
	 * Get the number of contacts in a given group
	 *
	 * 
	 *
	 * @param int groupId The ID of the group you want the count from
	 * @return int Number of contacts in group
	 */ 
	public function Group_Get_Count( $groupId ) {
		$args = array( 'key' => $this->key, 'method' => 'Group_Get_Count' );
		$args['groupId'] = $groupId;

		$data = $this->makeCall( $args );
		
		return $data;
	}
			
			
	/**
	 * Create a new SavedSearch
	 *
	 * 
	 *
	 * @param string name A for your saved search, must be unique
	 * @param array advancedConditions An array of AdvancedCondition items - see AdvancedCondition for more info
	 * @param struct optionalParameters A struct of optional parameters, see below for valid keys
	 * @return int Returns the searchId of your new search
	 */ 
	public function SavedSearch_Create( $name, $advancedConditions, $optionalParameters = array() ) {
		$args = array( 'key' => $this->key, 'method' => 'SavedSearch_Create' );
		$args['name'] = $name;
		$args['advancedConditions'] = $advancedConditions;
		$args['optionalParameters'] = $optionalParameters;

		$data = $this->makeCall( $args );
		
		return $data;
	}
			
			
	/**
	 * Delete a savedSearch you created
	 *
	 * 
	 *
	 * @param int searchId The SavedSearch ID of the savedSearch you wish to delete
	 * @return bool Returns true on success
	 */ 
	public function SavedSearch_Delete( $searchId ) {
		$args = array( 'key' => $this->key, 'method' => 'SavedSearch_Delete' );
		$args['searchId'] = $searchId;

		$data = $this->makeCall( $args );
		
		return $data;
	}
			
			
	/**
	 * Retrieve a list of contacts found by a given saved search
	 *
	 * 
	 *
	 * @param int searchId The ID of the savedSearch you retrieve contacts from
	 * @param struct optionalParameters A struct of optional parameters, see below for valid keys
	 * @return struct Struct of records each with a key of email and values of contactId, email, status and statusCode
	 */ 
	public function SavedSearch_Get_Contacts( $searchId, $optionalParameters = array() ) {
		$args = array( 'key' => $this->key, 'method' => 'SavedSearch_Get_Contacts' );
		$args['searchId'] = $searchId;
		$args['optionalParameters'] = $optionalParameters;

		$data = $this->makeCall( $args );
		
		return $data;
	}
			
			
	/**
	 * Get a listing of Saved Searches
	 *
	 * 
	 *
	 * @return struct Struct of records with the key of searchId and value of name
	 */ 
	public function SavedSearch_List(  ) {
		$args = array( 'key' => $this->key, 'method' => 'SavedSearch_List' );

		$data = $this->makeCall( $args );
		
		return $data;
	}
			
			
	/**
	 * Get the number of contacts in a given savedSearch
	 *
	 * 
	 *
	 * @param int searchId The ID of the savedSearch you want the count from
	 * @return int Number of contacts in savedSearch
	 */ 
	public function SavedSearch_Get_Count( $searchId ) {
		$args = array( 'key' => $this->key, 'method' => 'SavedSearch_Get_Count' );
		$args['searchId'] = $searchId;

		$data = $this->makeCall( $args );
		
		return $data;
	}
			
			
	/**
	 * Create a standard Contactology campaign
	 *
	 * 
	 * <structnotes recipients>
	 * Any combination of the above can be provided in recipients.
	 * Ex. 1:{'recipients':{'list':3,group:[1,2,3],'search':[2,3]}
	 * Ex. 2:{'recipients':{'list':[1,2],group:1,'search':[1,2]}
	 * Ex. 3:{'recipients':{'list':[1,2],group:[2,3],'search':1}
	 * Ex. 4:{'recipients':{'list':1}
	 * </structnotes recipients>
	 * 
	 * <structnotes content>
	 * At least one of html or text must be provided.  If both are provided, the message will be sent as a multipart message
	 * </structnotes content>
	 * 
	 * 
	 * 
	 * <structnotes exclusions>
	 * Any combination of the above can be provided in exclusions.
	 * Ex. 1:{'exclusions':{'list':3,group:[1,2,3],'search':[2,3]}
	 * Ex. 2:{'exclusions':{'list':[1,2],group:1,'search':[1,2]}
	 * Ex. 3:{'exclusions':{'list':[1,2],group:[2,3],'search':1}
	 * Ex. 4:{'exclusions':{'list':1}
	 * </structnotes exclusions>
	 *
	 * @param struct recipients A struct which specifies the for your Campaign - can include list, group and search
	 * @param string campaignName The name of this Campaign - not shown to recipients
	 * @param string subject The line of the Campaign
	 * @param string senderEmail The from email address of the Campaign
	 * @param string senderName The from name of the Campaign
	 * @param struct content A struct which specifies the of the Campaign email - can include html and text
	 * @param struct optionalParameters A struct of optional parameters, see below for valid keys
	 * @return int campaignId The ID for your new Campaign
	 */ 
	public function Campaign_Create_Standard( $recipients, $campaignName, $subject, $senderEmail, $senderName, $content, $optionalParameters = array() ) {
		$args = array( 'key' => $this->key, 'method' => 'Campaign_Create_Standard' );
		$args['recipients'] = $recipients;
		$args['campaignName'] = $campaignName;
		$args['subject'] = $subject;
		$args['senderEmail'] = $senderEmail;
		$args['senderName'] = $senderName;
		$args['content'] = $content;
		$args['optionalParameters'] = $optionalParameters;

		$data = $this->makeCall( $args );
		
		return $data;
	}
			
			
	/**
	 * Create a standard Contactology campaign
	 *
	 * 
	 * <structnotes recipients>
	 * Any combination of the above can be provided in recipients.
	 * Ex. 1:{'recipients':{'list':3,group:[1,2,3],'search':[2,3]}
	 * Ex. 2:{'recipients':{'list':[1,2],group:1,'search':[1,2]}
	 * Ex. 3:{'recipients':{'list':[1,2],group:[2,3],'search':1}
	 * Ex. 4:{'recipients':{'list':1}
	 * </structnotes recipients>
	 * 
	 * 
	 * <structnotes exclusions>
	 * Any combination of the above can be provided in exclusions.
	 * Ex. 1:{'exclusions':{'list':3,group:[1,2,3],'search':[2,3]}
	 * Ex. 2:{'exclusions':{'list':[1,2],group:1,'search':[1,2]}
	 * Ex. 3:{'exclusions':{'list':[1,2],group:[2,3],'search':1}
	 * Ex. 4:{'exclusions':{'list':1}
	 * </structnotes exclusions>
	 * 
	 * 
	 * <structnotes content>
	 * At least one of htmlUrl or textUrl must be provided.  If both are provided, the message will be sent as a multipart message
	 * </structnotes content>
	 *
	 * @param struct recipients A struct which specifies the for your Campaign - can include list, group and search
	 * @param string campaignName The name of this Campaign - not shown to recipients
	 * @param string subject The line of the Campaign
	 * @param string senderEmail The from email address of the Campaign
	 * @param string senderName The from name of the Campaign
	 * @param struct content A struct which specifies the URLs that hold the of the Campaign email - can include htmlUrl and textUrl
	 * @param struct optionalParameters A struct of optional parameters, see below for valid keys
	 * @return int campaignId The ID for your new Campaign
	 */ 
	public function Campaign_Create_Standard_From_Url( $recipients, $campaignName, $subject, $senderEmail, $senderName, $content, $optionalParameters = array() ) {
		$args = array( 'key' => $this->key, 'method' => 'Campaign_Create_Standard_From_Url' );
		$args['recipients'] = $recipients;
		$args['campaignName'] = $campaignName;
		$args['subject'] = $subject;
		$args['senderEmail'] = $senderEmail;
		$args['senderName'] = $senderName;
		$args['content'] = $content;
		$args['optionalParameters'] = $optionalParameters;

		$data = $this->makeCall( $args );
		
		return $data;
	}
			
			
	/**
	 * Create a campaign to be sent to an ad hoc list of email addresses.  The campaign will send immediately, it is not necessary to call Campaign_Send
	 *
	 * There is a limit of 1000 contacts per call, however you can use Campaign_Add_Recipients to send more after your first batch is completed.
	 * 
	 * 
	 * <structnotes contacts>
	 * contacts is an array of structs, where each struct can contain:
	 *  - email => value
	 *  - customFieldToken => value
	 *  - customFieldId => value
	 * 
	 * Each contact must have a valid email
	 * 
	 * Ex:
	 * contacts = [
	 * {'email':'test@contactology.com', 'first_name':'Test', 'last_name':'User'},
	 * {'email':'support@contactology.com', 1:'Support', 2:'User'}
	 * ];
	 * </structnotes contacts>
	 * 
	 * <structnotes content>
	 * At least one of html or text must be provided.  If both are provided, the message will be sent as a multipart message
	 * </structnotes content>
	 *
	 * @param array contacts an array of contact items
	 * @param string campaignName The name of this Campaign - not shown to recipients
	 * @param string subject The line of the Campaign
	 * @param string senderEmail The from email address of the Campaign
	 * @param string senderName The from name of the Campaign
	 * @param struct content A struct which specifies the of the Campaign email - can include html and text
	 * @param struct optionalParameters A struct of optional parameters, see below for valid keys
	 * @return struct Returns a struct containing info about the new campaign
	 */ 
	public function Campaign_Create_Ad_Hoc( $contacts, $campaignName, $subject, $senderEmail, $senderName, $content, $optionalParameters = array() ) {
		$args = array( 'key' => $this->key, 'method' => 'Campaign_Create_Ad_Hoc' );
		$args['contacts'] = $contacts;
		$args['campaignName'] = $campaignName;
		$args['subject'] = $subject;
		$args['senderEmail'] = $senderEmail;
		$args['senderName'] = $senderName;
		$args['content'] = $content;
		$args['optionalParameters'] = $optionalParameters;

		$data = $this->makeCall( $args );
		
		return $data;
	}
			
			
	/**
	 * Create an Triggered campaign that sends every time someone subscribes to a given List
	 *
	 * Note:  Triggered campaigns won't send until they are activated
	 * 
	 * timeType and timeValue work together to define when your Triggered Campaign fires, ex:
	 * timeValue = 10, timeType = minutes means that your Triggered Campaign will send 10 minutes after the person subscribes
	 * timeValue = 0, timeType = minutes means that your Triggered Campaign will send immediately after the person subscribes
	 * timeValue = 1, timeType = months means that your Triggered Campaign will send 1 month after the person subscribes
	 * 
	 * Advanced Conditions can help control who will receive your Triggered Campaign, some example uses are limiting your Triggered campaign to users:
	 *  - with a specific domain name in their email address
	 *  - who sign up in a specific state
	 *  - many more (See the AdvancedCondition API calls)
	 *
	 * @param int listId The ID of the list that subscriptions to will trigger
	 * @param string timeType The type of time interval for your Triggered Campaign - timeValue and go together to define the timing rule for your Triggered Campaign. Valid values: minutes, hours, days, weeks, months
	 * @param int timeValue A number between 0 and 60 inclusive - and timeType go together to define the timing rule for your Triggered Campaign
	 * @param string campaignName The name of this Campaign - not shown to recipients
	 * @param string subject The line of the Campaign
	 * @param string senderEmail The from email address of the Campaign
	 * @param string senderName The from name of the Campaign
	 * @param struct content A struct which specifies the of the Campaign email - can include html and text
	 * @param array advancedConditions An array of AdvancedCondition items that govern automation behavior
	 * @param struct optionalParameters A struct of optional parameters, see below for valid keys
	 * @return int campaignId The ID of your new Campaign
	 */ 
	public function Campaign_Create_Triggered_On_List_Subscription( $listId, $timeType, $timeValue, $campaignName, $subject, $senderEmail, $senderName, $content, $advancedConditions = array(), $optionalParameters = array() ) {
		$args = array( 'key' => $this->key, 'method' => 'Campaign_Create_Triggered_On_List_Subscription' );
		$args['listId'] = $listId;
		$args['timeType'] = $timeType;
		$args['timeValue'] = $timeValue;
		$args['campaignName'] = $campaignName;
		$args['subject'] = $subject;
		$args['senderEmail'] = $senderEmail;
		$args['senderName'] = $senderName;
		$args['content'] = $content;
		$args['advancedConditions'] = $advancedConditions;
		$args['optionalParameters'] = $optionalParameters;

		$data = $this->makeCall( $args );
		
		return $data;
	}
			
			
	/**
	 * Create an Triggered campaign that sends relative to a Date CustomField
	 *
	 * Ex: Send 10 days before the value of CustomField birthday
	 * 
	 * Note:  Triggered campaigns won't send until they are activated
	 * 
	 * timeType, timeValue, timeDirection and useCurrentYear work together to define when your Triggered Campaign fires, ex:
	 * timeValue = 4, timeType = days, timeDirection = before means that your Triggered Campaign will send 4 days before the value of the given CustomField
	 * timeValue = 1, timeType = months, timeDirection = after means that your Triggered Campaign will send 1 month after the value of the given CustomField
	 * 
	 * useCurrentYear can be used to ignore the Year value of the Date CustomField, set useCurrentYear to cause the Campaign to trigger each year on the Date CustomField's Day and Month
	 * 
	 * Advanced Conditions can help control who will receive your Triggered Campaign, some example uses are limiting your Triggered campaign to users:
	 *  - with a specific domain name in their email address
	 *  - who sign up in a specific state
	 *  - many more (See the AdvancedCondition API calls)
	 *
	 * @param int dateCustomFieldId The ID of the CustomField of type Date whose value you want to use as a trigger
	 * @param string timeType The type of time interval for your Triggered Campaign - timeValue and go together to define the timing rule for your Triggered Campaign. Valid values: days, weeks, months
	 * @param int timeValue A number between 0 and 60 inclusive - and timeType go together to define the timing rule for your Triggered Campaign
	 * @param string timeDirection The direction of the time interval for your Triggered Campaign - timeValue and timeType go together to define the timing rule for your Triggered Campaign. Valid values: before, after
	 * @param bool useCurrentYear Set to true to ignore the Year value in the specified Date CustomField and base the trigger off of the current year instead.
	 * @param int sendTime What hour of the day in the campaign should send in 24-hour format, give a number between 0 and 23 inclusive
	 * @param string campaignName The name of this Campaign - not shown to recipients
	 * @param string subject The line of the Campaign
	 * @param string senderEmail The from email address of the Campaign
	 * @param string senderName The from name of the Campaign
	 * @param struct content A struct which specifies the of the Campaign email - can include html and text
	 * @param array advancedConditions An array of AdvancedCondition items that govern automation behavior
	 * @param struct optionalParameters A struct of optional parameters, see below for valid keys
	 * @return int campaignId The ID of your new Campaign
	 */ 
	public function Campaign_Create_Triggered_On_Date_CustomField( $dateCustomFieldId, $timeType, $timeValue, $timeDirection, $useCurrentYear, $sendTime, $campaignName, $subject, $senderEmail, $senderName, $content, $advancedConditions = array(), $optionalParameters = array() ) {
		$args = array( 'key' => $this->key, 'method' => 'Campaign_Create_Triggered_On_Date_CustomField' );
		$args['dateCustomFieldId'] = $dateCustomFieldId;
		$args['timeType'] = $timeType;
		$args['timeValue'] = $timeValue;
		$args['timeDirection'] = $timeDirection;
		$args['useCurrentYear'] = $useCurrentYear;
		$args['sendTime'] = $sendTime;
		$args['campaignName'] = $campaignName;
		$args['subject'] = $subject;
		$args['senderEmail'] = $senderEmail;
		$args['senderName'] = $senderName;
		$args['content'] = $content;
		$args['advancedConditions'] = $advancedConditions;
		$args['optionalParameters'] = $optionalParameters;

		$data = $this->makeCall( $args );
		
		return $data;
	}
			
			
	/**
	 * Create a Recurring Campaign that sends regularly on a defined schedule
	 *
	 * <structnotes timeFrame>
	 * timeFrame should be a struct containing:
	 *  - string type - the type of timeFrame, valid values: daily, weekly, monthly.  Each has different required fields
	 *    daily: no additional fields required
	 *    weekly:
	 * 		- array days An array of days that that the Recurring Campaign should send on, valid values are: sun, mon, tues, wed, thurs, fri, sat OR sunday, monday, tuesday, wednesday, thursday, friday, saturday
	 *    monthly:
	 * 		- int dayOfMonth What day of the month you want the Recurring Campaign to send on (1-31)
	 * 
	 * timeFrame examples:
	 * {'type':'daily'}
	 * {'type':'weekly','days':['tues']}
	 * {'type':'weekly','days':['sunday','thursday']}
	 * {'type':'monthly','dayOfMonth':15}
	 * </structnotes timeFrame>
	 * 
	 * 
	 * <structnotes recipients>
	 * Any combination of the above can be provided in recipients.
	 * Ex. 1:{'recipients':{'list':3,group:[1,2,3],'search':[2,3]}
	 * Ex. 2:{'recipients':{'list':[1,2],group:1,'search':[1,2]}
	 * Ex. 3:{'recipients':{'list':[1,2],group:[2,3],'search':1}
	 * Ex. 4:{'recipients':{'list':1}
	 * </structnotes recipients>
	 * 
	 * <structnotes content>
	 * At least one of html or text must be provided.  If both are provided, the message will be sent as a multipart message
	 * </structnotes content>
	 * 
	 * 
	 * 
	 * <structnotes exclusions>
	 * Any combination of the above can be provided in exclusions.
	 * Ex. 1:{'exclusions':{'list':3,group:[1,2,3],'search':[2,3]}
	 * Ex. 2:{'exclusions':{'list':[1,2],group:1,'search':[1,2]}
	 * Ex. 3:{'exclusions':{'list':[1,2],group:[2,3],'search':1}
	 * Ex. 4:{'exclusions':{'list':1}
	 * </structnotes exclusions>
	 *
	 * @param struct timeFrame A definition of how often the Recurring Campaign should send (see notes above)
	 * @param int sendHour What hour of the day the Recurring Campaign should be sent, in 24 hour format (0-23)
	 * @param int sendMinute What minute of the day the Recurring Campaign should be sent (0-59)
	 * @param string sendTimezone What Timezone is intended for use with sendHour and sendMinute
	 * @param struct recipients A struct which specifies the for your Campaign - can include list, group and search
	 * @param string campaignName The name of this Campaign - not shown to recipients
	 * @param string subject The line of the Campaign
	 * @param string senderEmail The from email address of the Campaign
	 * @param string senderName The from name of the Campaign
	 * @param struct content A struct which specifies the of the Campaign email - can include html and text
	 * @param struct optionalParameters A struct of optional parameters, see below for valid keys
	 * @return int campaignId The ID for your new Campaign
	 */ 
	public function Campaign_Create_Recurring( $timeFrame, $sendHour, $sendMinute, $sendTimezone, $recipients, $campaignName, $subject, $senderEmail, $senderName, $content, $optionalParameters = array() ) {
		$args = array( 'key' => $this->key, 'method' => 'Campaign_Create_Recurring' );
		$args['timeFrame'] = $timeFrame;
		$args['sendHour'] = $sendHour;
		$args['sendMinute'] = $sendMinute;
		$args['sendTimezone'] = $sendTimezone;
		$args['recipients'] = $recipients;
		$args['campaignName'] = $campaignName;
		$args['subject'] = $subject;
		$args['senderEmail'] = $senderEmail;
		$args['senderName'] = $senderName;
		$args['content'] = $content;
		$args['optionalParameters'] = $optionalParameters;

		$data = $this->makeCall( $args );
		
		return $data;
	}
			
			
	/**
	 * Create a Recurring Campaign that sends regularly on a defined schedule
	 *
	 * <structnotes timeFrame>
	 * timeFrame should be a struct containing:
	 *  - string type - the type of timeFrame, valid values: daily, weekly, monthly.  Each has different required fields
	 *    daily: no additional fields required
	 *    weekly:
	 * 		- array days An array of days that that the Recurring Campaign should send on, valid values are: sun, mon, tues, wed, thurs, fri, sat OR sunday, monday, tuesday, wednesday, thursday, friday, saturday
	 *    monthly:
	 * 		- int dayOfMonth What day of the month you want the Recurring Campaign to send on (1-31)
	 * 
	 * timeFrame examples:
	 * {'type':'daily'}
	 * {'type':'weekly','days':['tues']}
	 * {'type':'weekly','days':['sunday','thursday']}
	 * {'type':'monthly','dayOfMonth':15}
	 * </structnotes timeFrame>
	 * 
	 * 
	 * <structnotes recipients>
	 * Any combination of the above can be provided in recipients.
	 * Ex. 1:{'recipients':{'list':3,group:[1,2,3],'search':[2,3]}
	 * Ex. 2:{'recipients':{'list':[1,2],group:1,'search':[1,2]}
	 * Ex. 3:{'recipients':{'list':[1,2],group:[2,3],'search':1}
	 * Ex. 4:{'recipients':{'list':1}
	 * </structnotes recipients>
	 * 
	 * 
	 * <structnotes content>
	 * At least one of htmlUrl or textUrl must be provided.  If both are provided, the message will be sent as a multipart message
	 * </structnotes content>
	 * 
	 * 
	 * <structnotes exclusions>
	 * Any combination of the above can be provided in exclusions.
	 * Ex. 1:{'exclusions':{'list':3,group:[1,2,3],'search':[2,3]}
	 * Ex. 2:{'exclusions':{'list':[1,2],group:1,'search':[1,2]}
	 * Ex. 3:{'exclusions':{'list':[1,2],group:[2,3],'search':1}
	 * Ex. 4:{'exclusions':{'list':1}
	 * </structnotes exclusions>
	 *
	 * @param struct timeFrame A definition of how often the Recurring Campaign should send (see notes above)
	 * @param int sendHour What hour of the day the Recurring Campaign should be sent, in 24 hour format (0-23)
	 * @param int sendMinute What minute of the day the Recurring Campaign should be sent (0-59)
	 * @param string sendTimezone What Timezone is intended for use with sendHour and sendMinute
	 * @param struct recipients A struct which specifies the for your Campaign - can include list, group and search
	 * @param string campaignName The name of this Campaign - not shown to recipients
	 * @param string subject The line of the Campaign
	 * @param string senderEmail The from email address of the Campaign
	 * @param string senderName The from name of the Campaign
	 * @param struct content A struct which specifies the of the Campaign email - can include html and text
	 * @param struct optionalParameters A struct of optional parameters, see below for valid keys
	 * @return int campaignId The ID for your new Campaign
	 */ 
	public function Campaign_Create_Recurring_From_Url( $timeFrame, $sendHour, $sendMinute, $sendTimezone, $recipients, $campaignName, $subject, $senderEmail, $senderName, $content, $optionalParameters = array() ) {
		$args = array( 'key' => $this->key, 'method' => 'Campaign_Create_Recurring_From_Url' );
		$args['timeFrame'] = $timeFrame;
		$args['sendHour'] = $sendHour;
		$args['sendMinute'] = $sendMinute;
		$args['sendTimezone'] = $sendTimezone;
		$args['recipients'] = $recipients;
		$args['campaignName'] = $campaignName;
		$args['subject'] = $subject;
		$args['senderEmail'] = $senderEmail;
		$args['senderName'] = $senderName;
		$args['content'] = $content;
		$args['optionalParameters'] = $optionalParameters;

		$data = $this->makeCall( $args );
		
		return $data;
	}
			
			
	/**
	 * Create a transactional Contactology campaign
	 *
	 * A transactional campaign is sent to one recipeient at a time for a specific purpose with
	 * replacement values for tokens specific to that message.  Ex: sending a user's authorization
	 * code following a signup action on your site.  Replacements will override the value of a
	 * custom field with the same name in the Campaign
	 * 
	 * If the content of your email included a string {activation_code}, your replacements could contain {'activation_code':'123'},
	 * and that value would be used only for this instance of the transactional campaign
	 * 
	 * <structnotes contacts>
	 * Contacts is a struct which can contain:
	 *  - email => value
	 *  - customFieldToken => value
	 *  - customFieldId => value
	 * 
	 * A contact must have an email
	 * </structnotes contacts>
	 * 
	 * <structnotes content>
	 * At least one of html or text must be provided.  If both are provided, the message will be sent as a multipart message
	 * </structnotes content>
	 *
	 * @param struct testContact An initial contact to receive a test copy of the transactional email
	 * @param struct testReplacements An initial set of test replacement values to be used for the testEmail
	 * @param string campaignName The name of this Campaign - not shown to recipients
	 * @param string subject The line of the Campaign
	 * @param string senderEmail The from email address of the Campaign
	 * @param string senderName The from name of the Campaign
	 * @param struct content A struct which specifies the of the Campaign email - can include html and text
	 * @param struct optionalParameters A struct of optional parameters, see below for valid keys
	 * @return int campaignId The ID for your new Campaign
	 */ 
	public function Campaign_Create_Transactional( $testContact, $testReplacements, $campaignName, $subject, $senderEmail, $senderName, $content, $optionalParameters = array() ) {
		$args = array( 'key' => $this->key, 'method' => 'Campaign_Create_Transactional' );
		$args['testContact'] = $testContact;
		$args['testReplacements'] = $testReplacements;
		$args['campaignName'] = $campaignName;
		$args['subject'] = $subject;
		$args['senderEmail'] = $senderEmail;
		$args['senderName'] = $senderName;
		$args['content'] = $content;
		$args['optionalParameters'] = $optionalParameters;

		$data = $this->makeCall( $args );
		
		return $data;
	}
			
			
	/**
	 * Update properties of an existing Campaign.  Only unsent campaigns in Draft status can be Updated
	 *
	 * 
	 *
	 * @param int campaignId The Campaign id. The campaign must be in draft mode - an exception will be thrown if not.
	 * @param struct optionalParameters A struct of optional parameters, see below for valid keys
	 * @return struct Returns a struct containing standard Info for the campaign
	 */ 
	public function Campaign_Update_Standard( $campaignId, $optionalParameters = array() ) {
		$args = array( 'key' => $this->key, 'method' => 'Campaign_Update_Standard' );
		$args['campaignId'] = $campaignId;
		$args['optionalParameters'] = $optionalParameters;

		$data = $this->makeCall( $args );
		
		return $data;
	}
			
			
	/**
	 * Update properties of an existing Triggered On List Subscription Campaign.  Only unactivated campaigns in Draft status can be Updated
	 *
	 * 
	 *
	 * @param int campaignId The Campaign id. The campaign must be in draft mode - an exception will be thrown if not.
	 * @param struct optionalParameters A struct of optional parameters, see below for valid keys
	 * @return struct Returns a struct containing standard Info for the campaign
	 */ 
	public function Campaign_Update_Triggered_On_List_Subscription( $campaignId, $optionalParameters = array() ) {
		$args = array( 'key' => $this->key, 'method' => 'Campaign_Update_Triggered_On_List_Subscription' );
		$args['campaignId'] = $campaignId;
		$args['optionalParameters'] = $optionalParameters;

		$data = $this->makeCall( $args );
		
		return $data;
	}
			
			
	/**
	 * Update properties of an existing Triggered On Date CustomField Campaign.  Only unactivated campaigns in Draft status can be Updated
	 *
	 * 
	 *
	 * @param int campaignId The Campaign id. The campaign must be in draft mode - an exception will be thrown if not.
	 * @param struct optionalParameters A struct of optional parameters, see below for valid keys
	 * @return struct Returns a struct containing standard Info for the campaign
	 */ 
	public function Campaign_Update_Triggered_On_Date_CustomField( $campaignId, $optionalParameters = array() ) {
		$args = array( 'key' => $this->key, 'method' => 'Campaign_Update_Triggered_On_Date_CustomField' );
		$args['campaignId'] = $campaignId;
		$args['optionalParameters'] = $optionalParameters;

		$data = $this->makeCall( $args );
		
		return $data;
	}
			
			
	/**
	 * Update properties of an existing Recurring Campaign.  Only unactivated campaigns in Draft status can be Updated
	 *
	 * 
	 *
	 * @param int campaignId The Campaign id. The campaign must be in draft mode - an exception will be thrown if not.
	 * @param struct optionalParameters A struct of optional parameters, see below for valid keys
	 * @return struct Returns a struct containing standard Info for the campaign
	 */ 
	public function Campaign_Update_Recurring( $campaignId, $optionalParameters = array() ) {
		$args = array( 'key' => $this->key, 'method' => 'Campaign_Update_Recurring' );
		$args['campaignId'] = $campaignId;
		$args['optionalParameters'] = $optionalParameters;

		$data = $this->makeCall( $args );
		
		return $data;
	}
			
			
	/**
	 * Refresh the content of a URL based Campaign in Draft status
	 *
	 * 
	 * <structnotes links>
	 *     - string url The actual URL of the link
	 *     - string linktext The text of the link
	 * </structnotes links>
	 *
	 * @param int campaignId The Campaign id. The campaign must be in draft mode - an exception will be thrown if not.
	 * @param struct optionalParameters A struct of optional parameters, see below for valid keys
	 * @return struct Returns a struct containing a preview of the campaign
	 */ 
	public function Campaign_Refresh_Url_Content( $campaignId, $optionalParameters = array() ) {
		$args = array( 'key' => $this->key, 'method' => 'Campaign_Refresh_Url_Content' );
		$args['campaignId'] = $campaignId;
		$args['optionalParameters'] = $optionalParameters;

		$data = $this->makeCall( $args );
		
		return $data;
	}
			
			
	/**
	 * Get Info for a specified Campaign
	 *
	 * 
	 *
	 * @param int campaignId The Campaign ID of the Campaign you want Info on
	 * @return struct 
	 */ 
	public function Campaign_Get_Info( $campaignId ) {
		$args = array( 'key' => $this->key, 'method' => 'Campaign_Get_Info' );
		$args['campaignId'] = $campaignId;

		$data = $this->makeCall( $args );
		
		return $data;
	}
			
			
	/**
	 * Find Info for a set of Campaigns
	 *
	 * If no searchParameters are defined, all Campaigns will be returned
	 * 
	 * Note: Campaign_Find will not return more than 1000 Campaigns - if you run a Campaign_Find that would return more than 1000 Campaigns, it will throw an error
	 *
	 * @param struct searchParameters A struct of search parameters, any combination of which can be used
	 * @return struct 
	 */ 
	public function Campaign_Find( $searchParameters = array() ) {
		$args = array( 'key' => $this->key, 'method' => 'Campaign_Find' );
		$args['searchParameters'] = $searchParameters;

		$data = $this->makeCall( $args );
		
		return $data;
	}
			
			
	/**
	 * Find count for a set of Campaigns
	 *
	 * If no searchParameters are defined, all Campaigns will be counted
	 *
	 * @param struct searchParameters A struct of search parameters, any combination of which can be used
	 * @return int Number of campaigns
	 */ 
	public function Campaign_Get_Count( $searchParameters = array() ) {
		$args = array( 'key' => $this->key, 'method' => 'Campaign_Get_Count' );
		$args['searchParameters'] = $searchParameters;

		$data = $this->makeCall( $args );
		
		return $data;
	}
			
			
	/**
	 * Get a preview of a Campaign
	 *
	 * <structnotes links>
	 *     - string url The actual URL of the link
	 *     - string linktext The text of the link
	 * </structnotes links>
	 *
	 * @param int campaignId The campaign you wish to preview
	 * @return struct Returns a struct containing a preview of the campaign
	 */ 
	public function Campaign_Preview( $campaignId ) {
		$args = array( 'key' => $this->key, 'method' => 'Campaign_Preview' );
		$args['campaignId'] = $campaignId;

		$data = $this->makeCall( $args );
		
		return $data;
	}
			
			
	/**
	 * Create a duplicate of a campaign
	 *
	 * 
	 *
	 * @param int campaignId The campaign you wish to copy
	 * @param struct optionalParameters A struct of optional parameters, see below for valid keys
	 * @return struct Returns Info about the newly created campaign
	 */ 
	public function Campaign_Copy( $campaignId, $optionalParameters = array() ) {
		$args = array( 'key' => $this->key, 'method' => 'Campaign_Copy' );
		$args['campaignId'] = $campaignId;
		$args['optionalParameters'] = $optionalParameters;

		$data = $this->makeCall( $args );
		
		return $data;
	}
			
			
	/**
	 * Send a test of your Campaign
	 *
	 * 
	 * At least one of testEmail or testListId must be provided
	 *
	 * @param int campaignId The campaign you wish to test
	 * @param struct optionalParameters A struct of optional parameters, see below for valid keys
	 */ 
	public function Campaign_Send_Test( $campaignId, $optionalParameters = array() ) {
		$args = array( 'key' => $this->key, 'method' => 'Campaign_Send_Test' );
		$args['campaignId'] = $campaignId;
		$args['optionalParameters'] = $optionalParameters;

		$data = $this->makeCall( $args );
		
		return $data;
	}
			
			
	/**
	 * Get a report on a Completed Campaign
	 *
	 * 
	 *
	 * @param int campaignId The campaign you wish to generate a report for
	 * @return struct Returns report data from the specified campaign
	 */ 
	public function Campaign_Report( $campaignId ) {
		$args = array( 'key' => $this->key, 'method' => 'Campaign_Report' );
		$args['campaignId'] = $campaignId;

		$data = $this->makeCall( $args );
		
		return $data;
	}
			
			
	/**
	 * Delete a specified Campaign
	 *
	 * WARNING:  Unlike the webapp, this call WILL NOT prompt for confirmation
	 *
	 * @param int campaignId The campaign you wish to delete
	 * @return bool Returns true on success
	 */ 
	public function Campaign_Delete( $campaignId ) {
		$args = array( 'key' => $this->key, 'method' => 'Campaign_Delete' );
		$args['campaignId'] = $campaignId;

		$data = $this->makeCall( $args );
		
		return $data;
	}
			
			
	/**
	 * Adds an adhoc grouping of contacts to a campaign
The recipients are added and will be sent in the next send cycle. This can be used to send transactional or ongoing type messages. In this
	 *
	 * case, recipients will be appended to the queue - meaning you can send the same contact the same message multiple times.
	 * 
	 * There is a limit of 1000 contacts per call, however you can use a loop to add more than 1000 contacts.
	 * 
	 * <structnotes contacts>
	 * contacts is an array of structs, where each struct can contain:
	 *  - email => value
	 *  - customFieldToken => value
	 *  - customFieldId => value
	 * 
	 * Each contact must have a valid email
	 * 
	 * Ex:
	 * contacts = [
	 * {'email':'test@contactology.com', 'first_name':'Test', 'last_name':'User'},
	 * {'email':'support@contactology.com', 1:'Support', 2:'User'}
	 * ];
	 * </structnotes contacts>
	 *
	 * @param integer campaignId a valid campaign id
	 * @param array contacts an array of contact items
	 * @param struct optionalParameters A struct of optional parameters, see below for valid keys
	 * @return struct Returns contact add results
	 */ 
	public function Campaign_Add_Recipients( $campaignId, $contacts, $optionalParameters = array() ) {
		$args = array( 'key' => $this->key, 'method' => 'Campaign_Add_Recipients' );
		$args['campaignId'] = $campaignId;
		$args['contacts'] = $contacts;
		$args['optionalParameters'] = $optionalParameters;

		$data = $this->makeCall( $args );
		
		return $data;
	}
			
			
	/**
	 * Send a Campaign currently in Draft status
	 *
	 * 
	 *
	 * @param int campaignId The campaign you wish to send
	 * @return struct Returns boolean success and an array of issues
	 */ 
	public function Campaign_Send( $campaignId ) {
		$args = array( 'key' => $this->key, 'method' => 'Campaign_Send' );
		$args['campaignId'] = $campaignId;

		$data = $this->makeCall( $args );
		
		return $data;
	}
			
			
	/**
	 * Send a new message in a transactional Campaign
	 *
	 * A transactional campaign is sent to one recipient at a time for a specific purpose with
	 * replacement values for tokens specific to that message.  Ex: sending a user's authorization
	 * code following a signup action on your site.  Replacements will override the value of a
	 * custom field with the same name in the Campaign
	 * 
	 * If the content of your email included a string {activation_code}, your replacements could contain {'activation_code':'123'},
	 * and that value would be used only for this instance of the transactional campaign
	 * 
	 * <structnotes contact>
	 * contact is a struct, which can contain:
	 *  - email => value
	 *  - customFieldToken => value
	 *  - customFieldId => value
	 * 
	 * A contact must have a valid email
	 * 
	 * Ex:
	 * contact = {'email':'test@contactology.com', 'first_name':'Test', 'last_name':'User'}
	 * contact = {'email':'support@contactology.com', 1:'Support', 2:'User'}
	 * </structnotes contact>
	 *
	 * @param int campaignId The campaign you wish to send
	 * @param struct contact The you wish to send this transactional campaign to
	 * @param string source A short description of the of your contact
	 * @param struct replacements Token replacement values to be swapped in the message body
	 * @return mixed Returns true on success, returns a struct on failure
	 */ 
	public function Campaign_Send_Transactional( $campaignId, $contact, $source, $replacements ) {
		$args = array( 'key' => $this->key, 'method' => 'Campaign_Send_Transactional' );
		$args['campaignId'] = $campaignId;
		$args['contact'] = $contact;
		$args['source'] = $source;
		$args['replacements'] = $replacements;

		$data = $this->makeCall( $args );
		
		return $data;
	}
			
			
	/**
	 * Send a new message in a transactional Campaign
	 *
	 * A transactional campaign is sent to one recipient at a time for a specific purpose with
	 * replacement values for tokens specific to that message.  Ex: sending a user's authorization
	 * code following a signup action on your site.  Replacements will override the value of a
	 * custom field with the same name in the Campaign
	 * 
	 * If the content of your email included a string {activation_code}, your replacements could contain {'activation_code':'123'},
	 * and that value would be used only for this instance of the transactional campaign
	 * 
	 * This function allows you to send transactional messages in batches of up to 1000 contacts, providing a replacement struct for each contact
	 * 
	 * If this call fails and returns an error struct, no messages will be sent.  The List_Evaluate_Contacts call can help you find potential problems
	 * in your list prior to using this call.  This behavior can be bypassed with the continueOnError optionalParameter.
	 * 
	 * If continueOnError is specified, the number actually sent will be in the "successes" key of the return struct
	 * 
	 * <structnotes contacts>
	 * Each contact is a struct, which can contain:
	 *  - email => value
	 *  - customFieldToken => value
	 *  - customFieldId => value
	 * 
	 * A contact must have a valid email
	 * 
	 * Ex:
	 * contact = {'email':'test@contactology.com', 'first_name': 'Test', 'last_name':'User'}
	 * contact = {'email':'support@contactology.com', 1:'Support', 2:'User'}
	 * </structnotes contacts>
	 *
	 * @param int campaignId The campaign you wish to send
	 * @param array contacts An array of structs containing the you wish to send this transactional campaign to
	 * @param string source A short description of the of your contact
	 * @param array replacements An array of token replacement values to be swapped in the message body
	 * @param struct optionalParameters A struct of optional parameters, see below for valid keys
	 * @return mixed Returns true on success, returns a struct on failure
	 */ 
	public function Campaign_Send_Transactional_Multiple( $campaignId, $contacts, $source, $replacements, $optionalParameters = array() ) {
		$args = array( 'key' => $this->key, 'method' => 'Campaign_Send_Transactional_Multiple' );
		$args['campaignId'] = $campaignId;
		$args['contacts'] = $contacts;
		$args['source'] = $source;
		$args['replacements'] = $replacements;
		$args['optionalParameters'] = $optionalParameters;

		$data = $this->makeCall( $args );
		
		return $data;
	}
			
			
	/**
	 * Schedule a Campaign to send at a specified date and time
	 *
	 * <structnotes time>
	 *  - A specified date/time in the format: YYYY-MM-DD
	 *  - A relative time string including, but not limited to:
	 *    * 'now'
	 *    * 'today'
	 *    * 'tomorrow'
	 *    * 'first day of January 2010' (example)
	 *    * 'last day of March 2010' (example)
	 *    * 'Monday this week' (example)
	 *    * 'Tuesday next week' (example)
	 * 
	 * The timeString must specify a time in the future.  No time travelling to send emails is allowed
	 * </structnotes time>
	 *
	 * @param int campaignId The campaign you wish to schedule
	 * @param string time a timeString in UTC timezone
	 * @return struct Returns boolean success and an array of issues
	 */ 
	public function Campaign_Schedule( $campaignId, $time ) {
		$args = array( 'key' => $this->key, 'method' => 'Campaign_Schedule' );
		$args['campaignId'] = $campaignId;
		$args['time'] = $time;

		$data = $this->makeCall( $args );
		
		return $data;
	}
			
			
	/**
	 * Clear a Scheduled Campaign's scheduled time and return the Campaign to Draft mode
	 *
	 * 
	 *
	 * @param int campaignId The campaign you wish to schedule
	 * @return struct 
	 */ 
	public function Campaign_Schedule_Cancel( $campaignId ) {
		$args = array( 'key' => $this->key, 'method' => 'Campaign_Schedule_Cancel' );
		$args['campaignId'] = $campaignId;

		$data = $this->makeCall( $args );
		
		return $data;
	}
			
			
	/**
	 * Resend the Campaign to Contacts who soft-bounced
	 *
	 * 
	 *
	 * @param int campaignId The campaign you wish to schedule
	 * @return bool Returns true on success
	 */ 
	public function Campaign_Resend_Bounces( $campaignId ) {
		$args = array( 'key' => $this->key, 'method' => 'Campaign_Resend_Bounces' );
		$args['campaignId'] = $campaignId;

		$data = $this->makeCall( $args );
		
		return $data;
	}
			
			
	/**
	 * Get a list of all Contacts who have the Delivered status for the specified Campaign
	 *
	 * 
	 * A timeString can be any of:
	 *  - A specified date/time in the format: YYYY-MM-DD
	 *  - A relative time string including, but not limited to:
	 *    * "now"
	 *    * "today"
	 *    * "tomorrow"
	 *    * "first day of January 2010" (example)
	 *    * "last day of March 2010" (example)
	 *    * "Monday this week" (example)
	 *    * "Tuesday next week" (example)
	 *
	 * @param int campaignId The campaign you wish to find delivered contacts for
	 * @param struct optionalParameters A struct of optional parameters, see below for valid keys
	 * @return struct Returns a struct containing all found contacts
	 */ 
	public function Campaign_Get_Delivered_Contacts( $campaignId, $optionalParameters = array() ) {
		$args = array( 'key' => $this->key, 'method' => 'Campaign_Get_Delivered_Contacts' );
		$args['campaignId'] = $campaignId;
		$args['optionalParameters'] = $optionalParameters;

		$data = $this->makeCall( $args );
		
		return $data;
	}
			
			
	/**
	 * Get a list of all Contacts who Hard Bounced for the specified Campaign
	 *
	 * 
	 * A timeString can be any of:
	 *  - A specified date/time in the format: YYYY-MM-DD
	 *  - A relative time string including, but not limited to:
	 *    * "now"
	 *    * "today"
	 *    * "tomorrow"
	 *    * "first day of January 2010" (example)
	 *    * "last day of March 2010" (example)
	 *    * "Monday this week" (example)
	 *    * "Tuesday next week" (example)
	 *
	 * @param int campaignId The campaign you wish to find hard bounced contacts for
	 * @param struct optionalParameters A struct of optional parameters, see below for valid keys
	 * @return struct Returns a struct containing all found contacts
	 */ 
	public function Campaign_Get_Hard_Bounced_Contacts( $campaignId, $optionalParameters = array() ) {
		$args = array( 'key' => $this->key, 'method' => 'Campaign_Get_Hard_Bounced_Contacts' );
		$args['campaignId'] = $campaignId;
		$args['optionalParameters'] = $optionalParameters;

		$data = $this->makeCall( $args );
		
		return $data;
	}
			
			
	/**
	 * Get a list of all Contacts who Opened the specified Campaign
	 *
	 * Only works if the Campaign had trackOpens set to true
	 * 
	 * 
	 * A timeString can be any of:
	 *  - A specified date/time in the format: YYYY-MM-DD
	 *  - A relative time string including, but not limited to:
	 *    * "now"
	 *    * "today"
	 *    * "tomorrow"
	 *    * "first day of January 2010" (example)
	 *    * "last day of March 2010" (example)
	 *    * "Monday this week" (example)
	 *    * "Tuesday next week" (example)
	 *
	 * @param int campaignId The campaign you wish to find contacts who opened said campaign
	 * @param struct optionalParameters A struct of optional parameters, see below for valid keys
	 * @return struct Returns a struct containing all found contacts
	 */ 
	public function Campaign_Get_Opened_Contacts( $campaignId, $optionalParameters = array() ) {
		$args = array( 'key' => $this->key, 'method' => 'Campaign_Get_Opened_Contacts' );
		$args['campaignId'] = $campaignId;
		$args['optionalParameters'] = $optionalParameters;

		$data = $this->makeCall( $args );
		
		return $data;
	}
			
			
	/**
	 * Get a list of all Contacts who Clicked Thru for the specified Campaign
	 *
	 * Only works for Campaigns who have trackClickThruHTML and/or trackClickThruText set to true
	 * 
	 * 
	 * A timeString can be any of:
	 *  - A specified date/time in the format: YYYY-MM-DD
	 *  - A relative time string including, but not limited to:
	 *    * "now"
	 *    * "today"
	 *    * "tomorrow"
	 *    * "first day of January 2010" (example)
	 *    * "last day of March 2010" (example)
	 *    * "Monday this week" (example)
	 *    * "Tuesday next week" (example)
	 *
	 * @param int campaignId The campaign you wish to find clicked thru contacts for
	 * @param struct optionalParameters A struct of optional parameters, see below for valid keys
	 * @return struct Returns a struct containing all found contacts
	 */ 
	public function Campaign_Get_ClickThru_Contacts( $campaignId, $optionalParameters = array() ) {
		$args = array( 'key' => $this->key, 'method' => 'Campaign_Get_ClickThru_Contacts' );
		$args['campaignId'] = $campaignId;
		$args['optionalParameters'] = $optionalParameters;

		$data = $this->makeCall( $args );
		
		return $data;
	}
			
			
	/**
	 * Get a list of all Contacts who Replied To the specified Campaign
	 *
	 * Only works if the Campaign had trackReplies set to true
	 * 
	 * 
	 * A timeString can be any of:
	 *  - A specified date/time in the format: YYYY-MM-DD
	 *  - A relative time string including, but not limited to:
	 *    * "now"
	 *    * "today"
	 *    * "tomorrow"
	 *    * "first day of January 2010" (example)
	 *    * "last day of March 2010" (example)
	 *    * "Monday this week" (example)
	 *    * "Tuesday next week" (example)
	 *
	 * @param int campaignId The campaign you wish to find contacts who replied to said campaign
	 * @param struct optionalParameters A struct of optional parameters, see below for valid keys
	 * @return struct Returns a struct containing all found contacts
	 */ 
	public function Campaign_Get_Replied_Contacts( $campaignId, $optionalParameters = array() ) {
		$args = array( 'key' => $this->key, 'method' => 'Campaign_Get_Replied_Contacts' );
		$args['campaignId'] = $campaignId;
		$args['optionalParameters'] = $optionalParameters;

		$data = $this->makeCall( $args );
		
		return $data;
	}
			
			
	/**
	 * Get a list of all Contacts who Unsubscribed from the specified Campaign
	 *
	 * 
	 * A timeString can be any of:
	 *  - A specified date/time in the format: YYYY-MM-DD
	 *  - A relative time string including, but not limited to:
	 *    * "now"
	 *    * "today"
	 *    * "tomorrow"
	 *    * "first day of January 2010" (example)
	 *    * "last day of March 2010" (example)
	 *    * "Monday this week" (example)
	 *    * "Tuesday next week" (example)
	 *
	 * @param int campaignId The campaign you wish to find contacts who unsubscribed from said campaign
	 * @param struct optionalParameters A struct of optional parameters, see below for valid keys
	 * @return struct Returns a struct containing all found contacts
	 */ 
	public function Campaign_Get_Unsubscribed_Contacts( $campaignId, $optionalParameters = array() ) {
		$args = array( 'key' => $this->key, 'method' => 'Campaign_Get_Unsubscribed_Contacts' );
		$args['campaignId'] = $campaignId;
		$args['optionalParameters'] = $optionalParameters;

		$data = $this->makeCall( $args );
		
		return $data;
	}
			
			
	/**
	 * Get a list of all Contacts who Subscribed from the specified Campaign
	 *
	 * Only works if the Campaign had trackOpens set to true
	 * 
	 * 
	 * A timeString can be any of:
	 *  - A specified date/time in the format: YYYY-MM-DD
	 *  - A relative time string including, but not limited to:
	 *    * "now"
	 *    * "today"
	 *    * "tomorrow"
	 *    * "first day of January 2010" (example)
	 *    * "last day of March 2010" (example)
	 *    * "Monday this week" (example)
	 *    * "Tuesday next week" (example)
	 *
	 * @param int campaignId The campaign you wish to find contacts who subscribed from said campaign
	 * @param struct optionalParameters A struct of optional parameters, see below for valid keys
	 * @return struct Returns a struct containing all found contacts
	 */ 
	public function Campaign_Get_Subscribed_Contacts( $campaignId, $optionalParameters = array() ) {
		$args = array( 'key' => $this->key, 'method' => 'Campaign_Get_Subscribed_Contacts' );
		$args['campaignId'] = $campaignId;
		$args['optionalParameters'] = $optionalParameters;

		$data = $this->makeCall( $args );
		
		return $data;
	}
			
			
	/**
	 * Get a list of all Contacts who Forwarded the specified Campaign
	 *
	 * 
	 * A timeString can be any of:
	 *  - A specified date/time in the format: YYYY-MM-DD
	 *  - A relative time string including, but not limited to:
	 *    * "now"
	 *    * "today"
	 *    * "tomorrow"
	 *    * "first day of January 2010" (example)
	 *    * "last day of March 2010" (example)
	 *    * "Monday this week" (example)
	 *    * "Tuesday next week" (example)
	 *
	 * @param int campaignId The campaign you wish to find contacts who forwarded said campaign
	 * @param struct optionalParameters A struct of optional parameters, see below for valid keys
	 * @return struct Returns a struct containing all found contacts
	 */ 
	public function Campaign_Get_Forwarded_Contacts( $campaignId, $optionalParameters = array() ) {
		$args = array( 'key' => $this->key, 'method' => 'Campaign_Get_Forwarded_Contacts' );
		$args['campaignId'] = $campaignId;
		$args['optionalParameters'] = $optionalParameters;

		$data = $this->makeCall( $args );
		
		return $data;
	}
			
			
	/**
	 * Get a list of URLs in a given campaign, for use with the Campaign_Get_ClickThru_Contacts call
	 *
	 * 
	 *
	 * @param int campaignId The campaign you wish to find contacts who forwarded said campaign
	 * @return struct 
	 */ 
	public function Campaign_Get_Urls( $campaignId ) {
		$args = array( 'key' => $this->key, 'method' => 'Campaign_Get_Urls' );
		$args['campaignId'] = $campaignId;

		$data = $this->makeCall( $args );
		
		return $data;
	}
			
			
	/**
	 * Activate an Triggered Campaign
	 *
	 * 
	 *
	 * @param int campaignId The campaign you wish to activate
	 * @return struct 
	 */ 
	public function Campaign_Activate_Triggered( $campaignId ) {
		$args = array( 'key' => $this->key, 'method' => 'Campaign_Activate_Triggered' );
		$args['campaignId'] = $campaignId;

		$data = $this->makeCall( $args );
		
		return $data;
	}
			
			
	/**
	 * Activate a Recurring Campaign
	 *
	 * 
	 *
	 * @param int campaignId The campaign you wish to activate
	 * @return struct 
	 */ 
	public function Campaign_Activate_Recurring( $campaignId ) {
		$args = array( 'key' => $this->key, 'method' => 'Campaign_Activate_Recurring' );
		$args['campaignId'] = $campaignId;

		$data = $this->makeCall( $args );
		
		return $data;
	}
			
			
	/**
	 * Deactivate an active Triggered campaign
	 *
	 * 
	 *
	 * @param int campaignId The campaign you wish to deactivate
	 * @return bool Returns true on success
	 */ 
	public function Campaign_Deactivate_Triggered( $campaignId ) {
		$args = array( 'key' => $this->key, 'method' => 'Campaign_Deactivate_Triggered' );
		$args['campaignId'] = $campaignId;

		$data = $this->makeCall( $args );
		
		return $data;
	}
			
			
	/**
	 * Deactivate an active recurring campaign
	 *
	 * 
	 *
	 * @param int campaignId The campaign you wish to deactivate
	 * @return bool Returns true on success
	 */ 
	public function Campaign_Deactivate_Recurring( $campaignId ) {
		$args = array( 'key' => $this->key, 'method' => 'Campaign_Deactivate_Recurring' );
		$args['campaignId'] = $campaignId;

		$data = $this->makeCall( $args );
		
		return $data;
	}
			
			
	/**
	 * Create a multivariate split Contactology campaign - used to help determine the most effective configuration for your campaigns.
	 *
	 * An MV Split campaign is a more advanced version of the commonly-used A/B Split testing strategy.  An A/B Split test can be done using the Split campaign type - but with MV Splits, you aren't limited to just two testing scenarios.
	 * 
	 * Campaign_Create_Split works almost identically to Campaign_Create_Standard - the same parameters apply and the base values of the campaign become your 'A' split.  Additional splits are created using the splitParts parameter and specifying override values for the 'A' split.  It is possible to have a split be perfectly identical to the 'A' split - this can be used to test what time of day is best to send your campaign at (see: Campaign_Schedule_Split_Parts), or you can override as many or as few properties as you want.
	 * 
	 * Each element in the splitParts property will cause a new split to be created, and they will be labelled alphabetically, with the 'root' split being 'A'.
	 * 
	 * Each split will receive a percentage of your recipients as specified in the optionalParameter splitPercent.  The remainder can be sent using Campaign_Schedule_Split_Remainder or Campaign_Send_Split_Remainder.  Ex: splitPercent 5 with 3 splits = 15% of your contacts used in testing, 85% used in sending remainder
	 * 
	 * 
	 * <structnotes recipients>
	 * Any combination of the above can be provided in recipients.
	 * Ex. 1:{'recipients':{'list':3,group:[1,2,3],'search':[2,3]}
	 * Ex. 2:{'recipients':{'list':[1,2],group:1,'search':[1,2]}
	 * Ex. 3:{'recipients':{'list':[1,2],group:[2,3],'search':1}
	 * Ex. 4:{'recipients':{'list':1}
	 * </structnotes recipients>
	 * 
	 * 
	 * <structnotes content>
	 * At least one of html or text must be provided.  If both are provided, the message will be sent as a multipart message
	 * </structnotes content>
	 *
	 * @param struct recipients A struct which specifies the for your Campaign - can include list, group and search
	 * @param string campaignName The name of this Campaign - not shown to recipients
	 * @param string subject The line of the Campaign
	 * @param string senderEmail The from email address of the Campaign
	 * @param string senderName The from name of the Campaign
	 * @param struct content A struct which specifies the of the Campaign email - can include html and text
	 * @param array splitParts An array of structs containing override values for each split - each struct will cause a new split to be created
	 * @param struct optionalParameters A struct of optional parameters, see below for valid keys
	 * @return int campaignId The ID for your new Campaign
	 */ 
	public function Campaign_Create_Split( $recipients, $campaignName, $subject, $senderEmail, $senderName, $content, $splitParts, $optionalParameters = array() ) {
		$args = array( 'key' => $this->key, 'method' => 'Campaign_Create_Split' );
		$args['recipients'] = $recipients;
		$args['campaignName'] = $campaignName;
		$args['subject'] = $subject;
		$args['senderEmail'] = $senderEmail;
		$args['senderName'] = $senderName;
		$args['content'] = $content;
		$args['splitParts'] = $splitParts;
		$args['optionalParameters'] = $optionalParameters;

		$data = $this->makeCall( $args );
		
		return $data;
	}
			
			
	/**
	 * Update properties of an existing Campaign.  Only unsent campaigns in Draft status can be Updated
	 *
	 * 
	 *
	 * @param int campaignId The Campaign id. The campaign must be in draft mode - an exception will be thrown if not.
	 * @param array partIds An array of partIds, each element being a single letter A-Z
	 * @param struct optionalParameters A struct of optional parameters, see below for valid keys
	 * @return struct Returns a struct of structs, a split ID with
	 */ 
	public function Campaign_Update_Split_Parts( $campaignId, $partIds, $optionalParameters ) {
		$args = array( 'key' => $this->key, 'method' => 'Campaign_Update_Split_Parts' );
		$args['campaignId'] = $campaignId;
		$args['partIds'] = $partIds;
		$args['optionalParameters'] = $optionalParameters;

		$data = $this->makeCall( $args );
		
		return $data;
	}
			
			
	/**
	 * Get the alphabetic split IDs for the parts of a split campaign
	 *
	 * 
	 *
	 * @param int campaignId The Campaign ID. The campaign must be a split campaign
	 * @return array An array of split part IDs
	 */ 
	public function Campaign_Get_Split_Part_Ids( $campaignId ) {
		$args = array( 'key' => $this->key, 'method' => 'Campaign_Get_Split_Part_Ids' );
		$args['campaignId'] = $campaignId;

		$data = $this->makeCall( $args );
		
		return $data;
	}
			
			
	/**
	 * Get the Campaign_Report for the split winner/remainder, sent with Campaign_Send_Split_Remainder, Campaign_Schedule_Split_Remainder or Campaign_Schedule_Split_Winner
	 *
	 * 
	 *
	 * @param int campaignId The Campaign ID. The campaign must be a split campaign
	 * @return struct See Campaign_Report
	 */ 
	public function Campaign_Get_Split_Winner_Report( $campaignId ) {
		$args = array( 'key' => $this->key, 'method' => 'Campaign_Get_Split_Winner_Report' );
		$args['campaignId'] = $campaignId;

		$data = $this->makeCall( $args );
		
		return $data;
	}
			
			
	/**
	 * Get information for specific splitParts for your MV Split Campaign
	 *
	 * 
	 *
	 * @param int campaignId The Campaign ID. The campaign must be a split campaign
	 * @param array partIds An array of partIds, each element being a single letter A-Z
	 * @return struct Report info
	 */ 
	public function Campaign_Get_Split_Parts_Info( $campaignId, $partIds ) {
		$args = array( 'key' => $this->key, 'method' => 'Campaign_Get_Split_Parts_Info' );
		$args['campaignId'] = $campaignId;
		$args['partIds'] = $partIds;

		$data = $this->makeCall( $args );
		
		return $data;
	}
			
			
	/**
	 * Schedule specified split parts to send at the given time.  Schedules all specified parts for the same time
	 *
	 * Note:  Once you send or schedule one part of a split campaign, no part of that split campaign can be modified
	 * 
	 * <structnotes time>
	 *  - A specified date/time in the format: YYYY-MM-DD
	 *  - A relative time string including, but not limited to:
	 *    * 'now'
	 *    * 'today'
	 *    * 'tomorrow'
	 *    * 'first day of January 2010' (example)
	 *    * 'last day of March 2010' (example)
	 *    * 'Monday this week' (example)
	 *    * 'Tuesday next week' (example)
	 * 
	 * The timeString must specify a time in the future.  No time travelling to send emails is allowed
	 * </structnotes time>
	 *
	 * @param int campaignId The Campaign ID. The campaign must be a split campaign in draft mode
	 * @param array partIds An array of alphabetic split part IDs
	 * @param string time a timeString in UTC timezone
	 * @return struct Returns boolean success and an array of issues
	 */ 
	public function Campaign_Schedule_Split_Parts( $campaignId, $partIds, $time ) {
		$args = array( 'key' => $this->key, 'method' => 'Campaign_Schedule_Split_Parts' );
		$args['campaignId'] = $campaignId;
		$args['partIds'] = $partIds;
		$args['time'] = $time;

		$data = $this->makeCall( $args );
		
		return $data;
	}
			
			
	/**
	 * Clear a Scheduled Campaign's scheduled time and return the Campaign to Draft mode
	 *
	 * 
	 *
	 * @param int campaignId The campaign you wish to schedule
	 * @param array partIds An array of alphabetic split part IDs
	 * @return bool True on success
	 */ 
	public function Campaign_Schedule_Cancel_Split_Parts( $campaignId, $partIds ) {
		$args = array( 'key' => $this->key, 'method' => 'Campaign_Schedule_Cancel_Split_Parts' );
		$args['campaignId'] = $campaignId;
		$args['partIds'] = $partIds;

		$data = $this->makeCall( $args );
		
		return $data;
	}
			
			
	/**
	 * Send specified split parts immediately
	 *
	 * Note:  Once you send or schedule one part of a split campaign, no part of that split campaign can be modified
	 *
	 * @param int campaignId The Campaign ID. The campaign must be a split campaign in draft mode
	 * @param array partIds An array of alphabetic split part IDs
	 * @return struct Returns boolean success and an array of issues
	 */ 
	public function Campaign_Send_Split_Parts( $campaignId, $partIds ) {
		$args = array( 'key' => $this->key, 'method' => 'Campaign_Send_Split_Parts' );
		$args['campaignId'] = $campaignId;
		$args['partIds'] = $partIds;

		$data = $this->makeCall( $args );
		
		return $data;
	}
			
			
	/**
	 * Send a test message for each of the specified split partIds
	 *
	 * 
	 * At least one of testEmail or testListId must be provided
	 *
	 * @param int campaignId The campaign you wish to test
	 * @param array partIds An array of alphabetic split part IDs
	 * @param struct optionalParameters A struct of optional parameters, see below for valid keys
	 * @return bool True on success
	 */ 
	public function Campaign_Send_Split_Test( $campaignId, $partIds, $optionalParameters = array() ) {
		$args = array( 'key' => $this->key, 'method' => 'Campaign_Send_Split_Test' );
		$args['campaignId'] = $campaignId;
		$args['partIds'] = $partIds;
		$args['optionalParameters'] = $optionalParameters;

		$data = $this->makeCall( $args );
		
		return $data;
	}
			
			
	/**
	 * Schedule the 'winning' split manually - the part specified will receive the remaining unsent recipients that were not allocated to splits
	 *
	 * Campaign_Get_Split_Comparison has data to help you decide which split performed has performed the best so far
	 * 
	 * <structnotes time>
	 *  - A specified date/time in the format: YYYY-MM-DD
	 *  - A relative time string including, but not limited to:
	 *    * 'now'
	 *    * 'today'
	 *    * 'tomorrow'
	 *    * 'first day of January 2010' (example)
	 *    * 'last day of March 2010' (example)
	 *    * 'Monday this week' (example)
	 *    * 'Tuesday next week' (example)
	 * 
	 * The timeString must specify a time in the future.  No time travelling to send emails is allowed
	 * </structnotes time>
	 *
	 * @param int campaignId The split campaign for which you wish to schedule the remainder
	 * @param string partId The of the split part you want the declare as the 'winner' and allocate remaining recipients to
	 * @param string time a timeString in UTC timezone
	 * @return struct Returns boolean success and an array of issues
	 */ 
	public function Campaign_Schedule_Split_Remainder( $campaignId, $partId, $time ) {
		$args = array( 'key' => $this->key, 'method' => 'Campaign_Schedule_Split_Remainder' );
		$args['campaignId'] = $campaignId;
		$args['partId'] = $partId;
		$args['time'] = $time;

		$data = $this->makeCall( $args );
		
		return $data;
	}
			
			
	/**
	 * Clear a Scheduled Split Remainder's scheduled time
	 *
	 * 
	 *
	 * @param int campaignId The campaign you wish to schedule
	 * @return bool True on success
	 */ 
	public function Campaign_Schedule_Cancel_Split_Remainder( $campaignId ) {
		$args = array( 'key' => $this->key, 'method' => 'Campaign_Schedule_Cancel_Split_Remainder' );
		$args['campaignId'] = $campaignId;

		$data = $this->makeCall( $args );
		
		return $data;
	}
			
			
	/**
	 * Send the 'winning' split manually - the part specified will receive the remaining unsent recipients that were not allocated to splits
	 *
	 * 
	 *
	 * @param int campaignId The split campaign for which you wish to send the remainder
	 * @param string partId The of the split part you want the declare as the 'winner' and allocate remaining recipients to
	 * @return struct Returns boolean success and an array of issues
	 */ 
	public function Campaign_Send_Split_Remainder( $campaignId, $partId ) {
		$args = array( 'key' => $this->key, 'method' => 'Campaign_Send_Split_Remainder' );
		$args['campaignId'] = $campaignId;
		$args['partId'] = $partId;

		$data = $this->makeCall( $args );
		
		return $data;
	}
			
			
	/**
	 * Schedule the Winner for your split campaign.  When the time comes, it will evaluate the winCriteria and send the remainder to the winning split.
	 *
	 * It is recommended that your split go out no sooner than 1 day after your splits go out.  If your splits have not all completed sending when the scheduled Winner triggers, no Winner will be chosen or sent.
	 * 
	 * You can send or schedule a split remainder after a Winner is scheduled to be determined, doing so will override the scheduled Winner.
	 * 
	 * <structnotes time>
	 *  - A specified date/time in the format: YYYY-MM-DD
	 *  - A relative time string including, but not limited to:
	 *    * 'now'
	 *    * 'today'
	 *    * 'tomorrow'
	 *    * 'first day of January 2010' (example)
	 *    * 'last day of March 2010' (example)
	 *    * 'Monday this week' (example)
	 *    * 'Tuesday next week' (example)
	 * 
	 * The timeString must specify a time in the future.  No time travelling to send emails is allowed
	 * </structnotes time>
	 * 
	 * <structnotes winCriteria>
	 * The following values are valid for winCriteria:
	 * - TotalOpened
	 * - TotalClickedThru
	 * - TotalReplied
	 * - TotalForwarded
	 * </structnotes winCriteria>
	 *
	 * @param int campaignId The Campaign ID. The campaign must be a split campaign. The splits must be complete at the time specified for a winner to be scheduled
	 * @param string time A timeString in the UTC timezone
	 * @param array winCriteria An array of criteria to be evaluated in the order specified (these are the same criteria returned in Campaign_Get_Split_Comparison)
	 * @return bool True on success
	 */ 
	public function Campaign_Schedule_Split_Winner( $campaignId, $time, $winCriteria ) {
		$args = array( 'key' => $this->key, 'method' => 'Campaign_Schedule_Split_Winner' );
		$args['campaignId'] = $campaignId;
		$args['time'] = $time;
		$args['winCriteria'] = $winCriteria;

		$data = $this->makeCall( $args );
		
		return $data;
	}
			
			
	/**
	 * Cancel a previously scheduled Winner evaluation
	 *
	 * 
	 *
	 * @param int campaignId The Campaign ID. The campaign must be a split campaign with a currently scheduled winner.
	 * @return bool True on success
	 */ 
	public function Campaign_Schedule_Cancel_Split_Winner( $campaignId ) {
		$args = array( 'key' => $this->key, 'method' => 'Campaign_Schedule_Cancel_Split_Winner' );
		$args['campaignId'] = $campaignId;

		$data = $this->makeCall( $args );
		
		return $data;
	}
			
			
	/**
	 * Get the Split Comparison report for a split campaign
	 *
	 * This data compares the performance of all splits across many factors:  Clickthrus, Opens, Bounces, Complaints, Forwards and more.  Returns a struct of structs, with the key being the data point being compared and the value as a struct containing the performance of each split
	 * 
	 * This function should not be called until your split sends are complete and your recipients have had time to read your split campaign
	 *
	 * @param int campaignId The campaign you wish to test
	 * @return struct Returns a struct of structs, a breakdown of how each split performed against each metric - with a 'Sole Winner', 'Sole Loser', 'Tie', 'Tied Winner' or 'Tied Loser' status for each campaign on each metric.  Use this data to determine the winner of your split campaign
	 */ 
	public function Campaign_Get_Split_Comparison( $campaignId ) {
		$args = array( 'key' => $this->key, 'method' => 'Campaign_Get_Split_Comparison' );
		$args['campaignId'] = $campaignId;

		$data = $this->makeCall( $args );
		
		return $data;
	}
			
			
	/**
	 * List current HostedAttachments
	 *
	 * Returns a struct with the filename as the key and an array of all campaigns 
	 * Example:
	 * {"test.gif":[1,2,3],"test.doc":[]}
	 *
	 * @return struct Returns a struct with the filename as the key and a list of all campaigns that have the HostedAttachment added as the value
	 */ 
	public function HostedAttachment_List(  ) {
		$args = array( 'key' => $this->key, 'method' => 'HostedAttachment_List' );

		$data = $this->makeCall( $args );
		
		return $data;
	}
			
			
	/**
	 * Add a HostedAttachment to the server
	 *
	 * <structnotes attachment>
	 * attachment should be the contents of the attachment, this is what will be stored in the attachment when it is written
	 * 
	 * Valid attachment types are:
	 *  - gif
	 *  - jpg
	 *  - png
	 *  - bmp
	 *  - docx
	 *  - pptx
	 *  - xlsx
	 *  - doc
	 *  - xls
	 *  - ppt
	 *  - pdf
	 *  - rtf
	 *  - odt
	 *  - ods
	 *  - odp
	 *  - txt
	 *  - html
	 *  - csv
	 *  - mp3
	 *  - avi
	 *  - mpg
	 *  - mov
	 *  - wma
	 *  - wmv
	 *  - zip
	 *  - gz
	 *  - bz2
	 * </structnotes attachment>
	 *
	 * @param string filename name The and extension for your attachment
	 * @param string attachment The body of the attachment
	 * @return string Returns the URL of the HostedAttachment for use in your Campaigns
	 */ 
	public function HostedAttachment_Add( $filename, $attachment ) {
		$args = array( 'key' => $this->key, 'method' => 'HostedAttachment_Add' );
		$args['filename'] = $filename;
		$args['attachment'] = $attachment;

		$data = $this->makeCall( $args );
		
		return $data;
	}
			
			
	/**
	 * Add a HostedAttachment to a Campaign
	 *
	 * 
	 *
	 * @param string filename The of your existing attachment
	 * @param int campaignId The ID of the Campaign in Draft Mode you want to add the attachment to
	 * @return bool Returns true on success
	 */ 
	public function HostedAttachment_Add_To_Campaign( $filename, $campaignId ) {
		$args = array( 'key' => $this->key, 'method' => 'HostedAttachment_Add_To_Campaign' );
		$args['filename'] = $filename;
		$args['campaignId'] = $campaignId;

		$data = $this->makeCall( $args );
		
		return $data;
	}
			
			
	/**
	 * Remove an existing HostedAttachment from a Campaign without deleting the HostedAttachment from the server
	 *
	 * 
	 *
	 * @param string filename The of the existing HostedAttachment
	 * @param int campaignId The ID of the Campaign you want to remove the attachment from
	 * @return bool Returns true on success
	 */ 
	public function HostedAttachment_Remove_From_Campaign( $filename, $campaignId ) {
		$args = array( 'key' => $this->key, 'method' => 'HostedAttachment_Remove_From_Campaign' );
		$args['filename'] = $filename;
		$args['campaignId'] = $campaignId;

		$data = $this->makeCall( $args );
		
		return $data;
	}
			
			
	/**
	 * Delete a HostedAttachment from the server and remove it from all Campaigns
	 *
	 * 
	 *
	 * @param string filename The of the existing attachment
	 * @return bool Returns true on success
	 */ 
	public function HostedAttachment_Delete( $filename ) {
		$args = array( 'key' => $this->key, 'method' => 'HostedAttachment_Delete' );
		$args['filename'] = $filename;

		$data = $this->makeCall( $args );
		
		return $data;
	}
			
			
	/**
	 * Get the total number of sent emails for your account
	 *
	 * 
	 *
	 * @param struct optionalParameters 
	 * @return int 
	 */ 
	public function Account_Get_Send_Count( $optionalParameters = array() ) {
		$args = array( 'key' => $this->key, 'method' => 'Account_Get_Send_Count' );
		$args['optionalParameters'] = $optionalParameters;

		$data = $this->makeCall( $args );
		
		return $data;
	}
			
			
	/**
	 * Get the quota, used and free space for your account
	 *
	 * 
	 *
	 * @return struct Returns a struct containing your quota, used and free space
	 */ 
	public function Account_Get_Hosted_Content_Info(  ) {
		$args = array( 'key' => $this->key, 'method' => 'Account_Get_Hosted_Content_Info' );

		$data = $this->makeCall( $args );
		
		return $data;
	}
			
			
	/**
	 * Retrieve the number of block sends remaining in a Block Send Quota account
	 *
	 * 
	 *
	 * @return int The number of sends remaining
	 */ 
	public function Account_Get_Block_Sends_Remaining(  ) {
		$args = array( 'key' => $this->key, 'method' => 'Account_Get_Block_Sends_Remaining' );

		$data = $this->makeCall( $args );
		
		return $data;
	}
			
			
	/**
	 * Confirm that an AdvancedCondition for use in SavedSearch or Campaign is valid
	 *
	 * Ex:
	 * {'type':'customField','token':'first_name','condition':'contains','value':'steve'}
	 * {'type':'addedOn','after':'2010-01-11','before':'2010-06-16','selectedAreas':['appAddEditContact','appSendTest']}
	 * {'type':'addedOn','before':'2010-06-16'}
	 * {'type':'contactStatus','condition':'isNot','value':'globallyUnsubscribed'}
	 * {'type':'listStatus','condition':'unsubscribedFrom','listId':1}
	 *
	 * @param AdvancedCondition condition A struct following a format defined in AdvancedCondition_List_Conditions
	 * @return bool 
	 */ 
	public function AdvancedCondition_Check_Condition( $condition ) {
		$args = array( 'key' => $this->key, 'method' => 'AdvancedCondition_Check_Condition' );
		$args['condition'] = $condition;

		$data = $this->makeCall( $args );
		
		return $data;
	}
			
			
	/**
	 * Returns a list of all of your available AdvancedConditions for use in
Campaign and SavedSearch functions
	 *
	 * 
	 *
	 * @return array Returns a list of valid AdvancedConditions
	 */ 
	public function AdvancedCondition_List_Conditions(  ) {
		$args = array( 'key' => $this->key, 'method' => 'AdvancedCondition_List_Conditions' );

		$data = $this->makeCall( $args );
		
		return $data;
	}
			
			
	/**
	 * Get Selected Area info for use with Automated Campaigns
	 *
	 * 
	 *
	 * @return array Returns a list of valid SelectedAreas for your account for use with Automated Campaigns
	 */ 
	public function AdvancedCondition_Get_SelectedAreas(  ) {
		$args = array( 'key' => $this->key, 'method' => 'AdvancedCondition_Get_SelectedAreas' );

		$data = $this->makeCall( $args );
		
		return $data;
	}
			
			
	/**
	 * Get a Contactology Message Quality Score for an email
	 *
	 * 
	 *
	 * @param string fromEmail The email address your email will be sent from
	 * @param string subject The of your email
	 * @param string html The HTML body of your message
	 * @param string text The plain version of your message
	 * @return struct A struct containing your score and some detailed information on any issues encountered (see notes above)
	 */ 
	public function Util_Get_MQS( $fromEmail, $subject, $html, $text ) {
		$args = array( 'key' => $this->key, 'method' => 'Util_Get_MQS' );
		$args['fromEmail'] = $fromEmail;
		$args['subject'] = $subject;
		$args['html'] = $html;
		$args['text'] = $text;

		$data = $this->makeCall( $args );
		
		return $data;
	}
			
			
	/**
	 * Template_List returns a struct of your templates available for use in the new Template Controller
	 *
	 * 
	 *
	 * @return struct A struct where the key is the template_name and the value is the template_id (Note: This is reversed from most API calls)
	 */ 
	public function Template_List(  ) {
		$args = array( 'key' => $this->key, 'method' => 'Template_List' );

		$data = $this->makeCall( $args );
		
		return $data;
	}
			
			
	/**
	 * Add a new Template for use with the new Template Controller
	 *
	 * Please see the Template Controller documentation for instructions on making your templates configurable
	 * 
	 * This function is used by the "Save As" functionality in the Template Controller
	 *
	 * @param string name The of your new template
	 * @param string html The HTML content of your new template
	 * @return int The templateId of your new template
	 */ 
	public function Template_Add( $name, $html ) {
		$args = array( 'key' => $this->key, 'method' => 'Template_Add' );
		$args['name'] = $name;
		$args['html'] = $html;

		$data = $this->makeCall( $args );
		
		return $data;
	}
			
			
	/**
	 * Get a list of tokens available for use in the new Template Controller
	 *
	 * This function is used by the new Template Controller
	 *
	 * @return struct Struct of tokens
	 */ 
	public function Template_Get_Tokens(  ) {
		$args = array( 'key' => $this->key, 'method' => 'Template_Get_Tokens' );

		$data = $this->makeCall( $args );
		
		return $data;
	}
			
			
	/**
	 * Transfer content from one template to another.  This will only work if the templates have been set up
  in advance to use the same IDs for corresponding containers.  See the Template Controller documentation
	 *
	 *   for reference
	 * 
	 * This function is used by the new Template Controller
	 *
	 * @param int templateId The template ID that you're moving to
	 * @param string content The full HTML of the old template
	 * @return string The full HTML content of the new template, with content transferred from the old template where possible
	 */ 
	public function Template_Transfer_Content( $templateId, $content ) {
		$args = array( 'key' => $this->key, 'method' => 'Template_Transfer_Content' );
		$args['templateId'] = $templateId;
		$args['content'] = $content;

		$data = $this->makeCall( $args );
		
		return $data;
	}
			
			
	/**
	 * Get the HTML of a template by ID
	 *
	 * This function is used by the new Template Controller
	 *
	 * @param int templateId The ID of the template of which you want the content
	 * @return string The full HTML content of the specified template
	 */ 
	public function Template_Get_Content( $templateId ) {
		$args = array( 'key' => $this->key, 'method' => 'Template_Get_Content' );
		$args['templateId'] = $templateId;

		$data = $this->makeCall( $args );
		
		return $data;
	}
	
	
	protected function makeCall( $args ) {
		$ch = curl_init( $this->url );
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, true); 
		curl_setopt($ch, CURLOPT_POSTFIELDS, http_build_query( $args, null, "&" ) );
		curl_setopt($ch, CURLOPT_POST, true); 
		curl_setopt($ch, CURLOPT_USERAGENT, "Contactology PHP Wrapper {$this->version}" );
		$json = curl_exec( $ch );
		
		$data = json_decode( $json, true );
		
		if ( $data === false ) {
			throw new Exception( "Data could not be retrieved from API" );
		}
		if ( isset( $data['result'] ) && $data['result'] == "error" ) {
			throw new Exception( "API Error: {$data['message']}", $data['code'] );
		}

		return $data;
	}
}
