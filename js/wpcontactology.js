jQuery(document).ready(function(){

	function showLoading() {
		jQuery("#loading").show();
	}
	
	function hideLoading() {
		jQuery("#loading").hide();
	}	
			
	//click function that downloads file and initiates wp ajax handler
	jQuery('#wp_contactology_submit').click( function() {
	
		showLoading();
		
		//gather variables
		var wpcntlgy_folder_id = jQuery('#wp_contactology_folder').val();
		var wpcntlgy_folder_name = jQuery('#wp_contactology_folder option:selected').html();
		var wpcntlgy_client = jQuery('#wp_contactology_client').val();
		var wpcntlgy_list = jQuery('#wp_contactology_list').val();
		
				
		//organize data for wp ajax request
		var data = {
			'action': 'wp_contactology',
			'type': 'copy_folder',
			'folder_id': wpcntlgy_folder_id,
			'folder_name': wpcntlgy_folder_name,
			'client': wpcntlgy_client,
			'list': wpcntlgy_list
		}
				
		//send data to admin-ajax.php
		jQuery.post(
			ajaxurl, //global variable that points to admin-ajax.php
			data, 
			function(response)  {
				hideLoading();
				jQuery('#wp_contactology_results').html(response.message);
				//alert('The server responded: ' + response.message);

			}, "json"
		);
		
		return false;
	}); //end click function
	
	//Watches clients dropdown and then populates and enables folders dropdown
	jQuery('#wp_contactology_client').change( function() {
	
		showLoading();
		
		//gather variables
		var wpcntlgy_folder_id = jQuery('#wp_contactology_folder').val();
		var wpcntlgy_folder_name = jQuery('#wp_contactology_folder').attr('name');
		var wpcntlgy_client = jQuery('#wp_contactology_client').val();
		
				
		//organize data for wp ajax request
		var data = {
			'action': 'wp_contactology',
			'type': 'get_lists',
			'folder_id': wpcntlgy_folder_id,
			'client': wpcntlgy_client
		}
				
		//send data to admin-ajax.php
		jQuery.post(
			ajaxurl, //global variable that points to admin-ajax.php
			data, 
			function(response)  {
				
				//enable the mailing list dropdown and populate with results
				jQuery('#wp_contactology_list').prop('disabled', false).html(response.message);
				hideLoading();

			}, "json"
		);
		
		return false;
	}); //end click function

}); // end document ready function
