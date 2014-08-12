<?php 

/**
 * Remove meta boxes
 * @since 1.0.0
 * 
 * Remove meta boxes from around the site for easier usage by clients
 *
*/
function rbc_remove_meta_boxes() {
	// Everything
	remove_meta_box( 'genesis_inpost_scripts_box', '', 'normal' ); // remove the scripts box from everywhere (not for client use, typically not used)
	remove_meta_box( 'postcustom', '', 'normal' ); // remove custom files from everywhere (typically not used)
	remove_meta_box( 'revisionsdiv', '', 'normal' ); // remove custom files from everywhere (typically not used)

	// Pages
	remove_meta_box( 'postimagediv', 'page', 'side' ); // remove the featured image from pages (typically not used)
	remove_meta_box( 'postexcerpt', 'page', 'normal' ); // remove the post excerpt from pages (typically not used)
	remove_meta_box( 'authordiv', 'page', 'normal' ); // remove the author meta box from pages (typically not used)
	remove_meta_box( 'commentstatusdiv', 'page', 'normal' ); // remove the comment status from pages (typically not used)
	remove_meta_box( 'commentsdiv', 'page', 'normal' ); // remove the comments meta box from pages (typically not used)

	// Posts
	remove_meta_box( 'tagsdiv-post_tag', 'post', 'side' ); // remove tags from posts (typically not used)
}
add_action( 'add_meta_boxes', 'rbc_remove_meta_boxes' );


/**
 * Filter out Envira from showing on a custom post type.
 *
 * @since 1.0.0
 *
 * @param array $post_types Default post types to skip.
 * @return array $post_types Amended post types to skip.
 */
function rbc_envira_skip_custom_post_type( $post_types ) {
    // Add your custom post type here.
    $post_types[] = 'post';
    return $post_types;
}
// add_filter( 'envira_gallery_skipped_posttypes', 'rbc_envira_skip_custom_post_type' );
