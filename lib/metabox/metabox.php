<?php
/**
 * Include and setup custom metaboxes and fields.
 *
 * @category YourThemeOrPlugin
 * @package  Metaboxes
 * @license  http://www.opensource.org/licenses/gpl-license.php GPL v2.0 (or later)
 * @link     https://github.com/webdevstudios/Custom-Metaboxes-and-Fields-for-WordPress
 */

add_filter( 'cmb_meta_boxes', 'rb_metaboxes' );
/**
 * Define the metabox and field configurations.
 *
 * @param  array $meta_boxes
 * @return array
 */
function rb_metaboxes( array $meta_boxes ) {

	// Start with an underscore to hide fields from custom fields list
	$prefix = 'rb_';

	/**
	 * Sample metabox to demonstrate each field type included
	 */
	$meta_boxes['coupons_metabox'] = array(
		'id'         => 'coupons_metabox',
		'title'      => __( 'Coupon information', 'rb' ),
		'pages'      => array( 'coupons', ), // Post type
		'context'    => 'normal',
		'priority'   => 'high',
		'show_names' => true, // Show field names on the left
		'fields'     => array(
			array(
				'name' => __( 'Coupon code', 'rb' ),
				'desc' => __( '<p>Add your coupon code right here, and it will be styled appropriately for users.</p>', 'rb' ),
				'id'   => $prefix . 'coupon_code',
				'type' => 'text_medium',
			),
			array(
				'name' => __( 'Link to product on Amazon', 'cmb' ),
				'desc' => __( 'e.g. http://amazon.com/link-to-your-product', 'rb' ),
				'id'   => $prefix . 'amazon_url',
				'type' => 'text_url',
				'protocols' => array('http', 'https', 'ftp', 'ftps', 'mailto', 'news', 'irc', 'gopher', 'nntp', 'feed', 'telnet'), // Array of allowed protocols
				// 'repeatable' => true,
			),
		),
	);

	return $meta_boxes;
}

add_action( 'init', 'cmb_initialize_cmb_meta_boxes', 9999 );
/**
 * Initialize the metabox class.
 */
function cmb_initialize_cmb_meta_boxes() {

	if ( ! class_exists( 'cmb_Meta_Box' ) )
		require_once 'init.php';

}
