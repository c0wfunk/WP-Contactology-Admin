/**
 * WP Contactology Admin Javascript
 *
 *
 * @name    wp-contactology-admin
 * @author  Jeremy Ferguson
 * @see     https://github.com/c0wfunk/WP-Contactology-Admin
 */
jQuery(document).ready(function(){

	/**
	 * Show loading graphic div
	 */
	function showLoading() {
		jQuery("#loading").show();
	}
	/**
	 * Hide loading graphic div
	 */
	function hideLoading() {
		jQuery("#loading").hide();
	}	
	
	/**
	 * Handle new client submit
	 */
	jQuery('#wp_contactology_new_client_submit').click( function() {
		//show loading graphic to start
		showLoading();
		//gather variables from form values
		var wpcntlgy_admin_client_name = jQuery('#wp_contactology_admin_client_name').val();
		var wpcntlgy_admin_client_email = jQuery('#wp_contactology_admin_email').val();
		var wpcntlgy_admin_client_username = jQuery('#wp_contactology_admin_username').val();
		var wpcntlgy_admin_client_password = jQuery('#wp_contactology_admin_password').val();

		//organize data for wp ajax request
		var data = {
			'action': 'wp_contactology_admin',
			'type': 'client_setup',
			'client_name': wpcntlgy_admin_client_name,
			'client_email': wpcntlgy_admin_client_email,
			'client_username': wpcntlgy_admin_client_username,
			'client_password': wpcntlgy_admin_client_password
		}
				
		//send data to admin-ajax.php
		jQuery.post(
			ajaxurl, //global variable that points to admin-ajax.php
			data, 
			function(response)  {
				//hide loading graphic
				hideLoading();
				//show response
				jQuery('#wp_contactology_client_setup_results').html(response.message);

			}, "json"
		);
		//override default click behavior
		return false;
	}); //end click function
	
	/**
	 * Handle account activate submit
	 */
	jQuery('#wp_contactology_activate_account_submit').click( function() {
		
		showLoading();
		
		//gather variables
		var wpcntlgy_admin_client_id = jQuery('#wp_contactology_admin_client_to_activate').val();
		
		//organize data for wp ajax request
		var data = {
			'action': 'wp_contactology_admin',
			'type': 'activate_account',
			'client_id': wpcntlgy_admin_client_id
		}
				
		//send data to admin-ajax.php
		jQuery.post(
			ajaxurl, //global variable that points to admin-ajax.php
			data, 
			function(response)  {
				//hide loading graphic
				hideLoading();
				//show response
				jQuery('#wp_contactology_activate_account_results').html(response.message);

			}, "json"
		);
		//override default click behavior
		return false;
	}); //end click function
			
	/**
	 * Handle trigger copy submit
	 * TODO: Fix loading graphic
	 */
	jQuery('#wp_contactology_copy_trigger_submit').click( function() {
		
		showLoading();
		//get info to copy
		var wpcntlgy_admin_folder_id = jQuery('#wp_contactology_admin_campaigns').val();
		var wpcntlgy_admin_folder_name = jQuery('#wp_contactology_admin_campaigns option:selected').html();
		jQuery( '#wp_contactology_current_client_checklist div'	).each(function() {
			//var strings to hold current group and checkbox 
			var wpcntlgy_current_group = jQuery(this);
			var wpcntlgy_current_checkbox = wpcntlgy_current_group.find('input:checkbox');
			//only if the box is checked
			if( jQuery( wpcntlgy_current_checkbox ).is( ":checked" ) )  {
				
				//get current selected client info
				var wpcntlgy_current_checkbox_value = wpcntlgy_current_checkbox.val();
				var wpcntlgy_client_id = wpcntlgy_current_group.find( 'input:hidden' ).val();
				//get selected list
				var wpcntlgy_admin_list = wpcntlgy_current_group.find( 'option:selected' ).val();
				
				
				//organize data for wp ajax request
				var data = {
					'action': 'wp_contactology_admin',
					'type': 'copy_trigger',
					'folder_id': wpcntlgy_admin_folder_id,
					'folder_name': wpcntlgy_admin_folder_name,
					'client_apikey': wpcntlgy_current_checkbox_value,
					'client_id': wpcntlgy_client_id,
					'list': wpcntlgy_admin_list
				}
			
				//send data to admin-ajax.php
				jQuery.post(
					ajaxurl, //global variable that points to admin-ajax.php
					data, 
					function(response)  {
						//TODO - show fail on fail, only show success when successful!
						if (response.message.indexOf("API Error:") >= 0) {
							wpcntlgy_current_group.find( '.fail' ).show().after(response.message); 
						}
						else {
							//jQuery(wp_contactology_copy_trigger_results).text( response.message );
							wpcntlgy_current_group.find( '.success' ).show().after(response.message); 
						}
					}, "json"
				);
			} //end checkbox if
		}); //end each loop
		
		hideLoading();
		return false;
	}); //end click function
		
	/**
	 * Handle checkbox clicks and adds list dropdown
	 * TODO: Make it work only the first time checkbox is selected
	 */
	jQuery('#wp_contactology_current_client_checklist input:checkbox').click( function() {
		//only if the box is checked
		if( jQuery(this).is( ":checked" ) ) {
			var checked_box = jQuery(this);
			//get api key from checked option
			var api_key = checked_box.val();
			
			//organize data for wp ajax request
			var data = {
				'action': 'wp_contactology_admin',
				'type': 'get_lists',
				'api_key': api_key
			}
					
			//send data to admin-ajax.php
			jQuery.post(
				ajaxurl, //global variable that points to admin-ajax.php
				data, 
				function(response)  {
					hideLoading();
					//add dropdown after checked box
					checked_box.parent().append( response.message );
				}, "json"
			);
			
		} //end checkbox if
		
	}); 
	
}); // end document ready function
