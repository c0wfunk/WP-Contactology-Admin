<?php
/*
Description: Adds Campaign Post Type to WP Contactology
Version: 0.1
License:

  Copyright 2012  (me@home.com)

  This program is free software; you can redistribute it and/or modify
  it under the terms of the GNU General Public License, version 2, as 
  published by the Free Software Foundation.

  This program is distributed in the hope that it will be useful,
  but WITHOUT ANY WARRANTY; without even the implied warranty of
  MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
  GNU General Public License for more details.

  You should have received a copy of the GNU General Public License
  along with this program; if not, write to the Free Software
  Foundation, Inc., 51 Franklin St, Fifth Floor, Boston, MA  02110-1301  USA
  
*/

class WP_Contactology_Post_Type {

	/*--------------------------------------------*
	 * Constants
	 *--------------------------------------------*/
	const name = 'WP Contactology Post Types';
	const slug = 'wpcontactology-post-types';
	
	/**
	 * Constructor
	 */
	function __construct() {
		//register an activation hook for the plugin
		register_activation_hook( __FILE__, array( &$this, 'install_plugin' ) );

		//run init function
		add_action( 'init', array( &$this, 'plugin_init' ) );
	}
  
	/**
	 * Runs when the plugin is activated
	 * TODO: Why isn't rewrite flush working? Not happening at the right time? - change where require happens.
	 */  
	function install_plugin() {
		//add post type and taxonomy
		$this->plugin_init();
		//to fix permalinks
		flush_rewrite_rules();
		
		// do not generate any output here
	}
  
	/**
	 * Runs when the plugin is initialized
	 */
	function plugin_init() {
		
		$this->register_wpc_campaign();
		$this->register_taxonomy_campaign_categories();
		
	}
	
	function register_wpc_campaign() {

		$labels = array( 
			'name' => _x( 'Campaigns', 'campaign' ),
			'singular_name' => _x( 'Campaign', 'campaign' ),
			'add_new' => _x( 'Add New', 'campaign' ),
			'add_new_item' => _x( 'Add New Campaign', 'campaign' ),
			'edit_item' => _x( 'Edit Campaign', 'campaign' ),
			'new_item' => _x( 'New Campaign', 'campaign' ),
			'view_item' => _x( 'View Campaign', 'campaign' ),
			'search_items' => _x( 'Search Campaigns', 'campaign' ),
			'not_found' => _x( 'No campaigns found', 'campaign' ),
			'not_found_in_trash' => _x( 'No campaigns found in Trash', 'campaign' ),
			'parent_item_colon' => _x( 'Parent Campaign:', 'campaign' ),
			'menu_name' => _x( 'Campaigns', 'campaign' ),
		);

		$args = array( 
			'labels' => $labels,
			'hierarchical' => false,
			'description' => 'Campaigns',
			'supports' => array( 'title', 'editor', 'custom-fields'),
			'taxonomies' => array( 'Campaign Categories' ),
			'public' => true,
			'show_ui' => true,
			'show_in_menu' => true,
			'menu_position' => 20,
			
			'show_in_nav_menus' => true,
			'publicly_queryable' => true,
			'exclude_from_search' => false,
			'has_archive' => true,
			'query_var' => true,
			'can_export' => true,
			'rewrite' => true,
			'capability_type' => 'post'
		);

		register_post_type( 'campaign', $args );
	}
	
	
	function register_taxonomy_campaign_categories() {

		$labels = array( 
			'name' => _x( 'Campaign Categories', 'campaign_categories' ),
			'singular_name' => _x( 'Campaign Category', 'campaign_categories' ),
			'search_items' => _x( 'Search Campaign Categories', 'campaign_categories' ),
			'popular_items' => _x( 'Popular Campaign Categories', 'campaign_categories' ),
			'all_items' => _x( 'All Campaign Categories', 'campaign_categories' ),
			'parent_item' => _x( 'Parent Campaign Category', 'campaign_categories' ),
			'parent_item_colon' => _x( 'Parent Campaign Category:', 'campaign_categories' ),
			'edit_item' => _x( 'Edit Campaign Category', 'campaign_categories' ),
			'update_item' => _x( 'Update Campaign Category', 'campaign_categories' ),
			'add_new_item' => _x( 'Add New Campaign Category', 'campaign_categories' ),
			'new_item_name' => _x( 'New Campaign Category', 'campaign_categories' ),
			'separate_items_with_commas' => _x( 'Separate series categories with commas', 'campaign_categories' ),
			'add_or_remove_items' => _x( 'Add or remove series categories', 'campaign_categories' ),
			'choose_from_most_used' => _x( 'Choose from the most used series categories', 'campaign_categories' ),
			'menu_name' => _x( 'Campaign Categories', 'campaign_categories' ),
		);

		$args = array( 
			'labels' => $labels,
			'public' => true,
			'show_in_nav_menus' => true,
			'show_ui' => true,
			'show_tagcloud' => true,
			'hierarchical' => true,

			'rewrite' => true,
			'query_var' => true
		);

		register_taxonomy( 'campaign_categories', array('campaign'), $args );
	}
	
	
  
	
  
} // end class

$WP_Contactology_Post_Type = new WP_Contactology_Post_Type();






