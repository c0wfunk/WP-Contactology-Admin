<?php
/**
 * Include and setup custom metaboxes and fields.
 *
 * @category WP_Contactology
 * @package  Metaboxes
 * @license  http://www.opensource.org/licenses/gpl-license.php GPL v2.0 (or later)
 * @link     https://github.com/jaredatch/Custom-Metaboxes-and-Fields-for-WordPress
 */

add_filter( 'cmb_meta_boxes', 'wp_contactology_metaboxes' );
/**
 * Define the metabox and field configurations.
 *
 * @param  array $meta_boxes
 * @return array
 */
function wp_contactology_metaboxes( array $meta_boxes ) {

	// Start with an underscore to hide fields from custom fields list
	$prefix = '_wpcntctlgyadmin_';

	$meta_boxes[] = array(
		'id'         => 'campaign_metabox',
		'title'      => 'Campaign Options',
		'pages'      => array( 'campaign', ), // Post type
		'context'    => 'normal',
		'priority'   => 'high',
		'show_names' => true, // Show field names on the left
		'fields'     => array(
			array(
				'name' => 'Subject',
				'desc' => 'The subject line of the Campaign',
				'id'   => $prefix . 'subject',
				'type' => 'textarea_small',
			),
			array(
				'name'    => 'Time Type',
				'desc'    => 'The type of time interval for your Triggered Campaign - Time Value and Time Type go together to define the timing rule for your Triggered Campaign. ',
				'id'      => $prefix . 'timeType',
				'type'    => 'select',
				'options' => array(
					array( 'name' => 'minutes', 'value' => 'minutes' ),
					array( 'name' => 'hours', 'value' => 'hours' ),
					array( 'name' => 'days', 'value' => 'days' ),
					array( 'name' => 'weeks', 'value' => 'weeks' ),
					array( 'name' => 'months', 'value' => 'months' )
				)
			),
			array(
				'name' => 'Time Value',
				'desc' => 'A number between 0 and 60 inclusive',
				'id'   => $prefix . 'timeValue',
				'type' => 'text_small',
			)
		)
	);

	// Add other metaboxes as needed

	return $meta_boxes;
}

add_action( 'init', 'cmb_initialize_cmb_meta_boxes', 9999 );
/**
 * Initialize the metabox class.
 */
function cmb_initialize_cmb_meta_boxes() {

	if ( ! class_exists( 'cmb_Meta_Box' ) )
		require_once 'class.cmb_Meta_Box.php';

}