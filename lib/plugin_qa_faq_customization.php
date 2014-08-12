<?php

/**
 * Remove meta boxes from QA FAQs
 * @since 1.0.0
 *
*/
function rbc_remove_qafaq_meta_boxes() {
	remove_meta_box( 'wpseo_meta', 'qa_faqs', 'normal' );
}
add_action( 'add_meta_boxes', 'rbc_remove_qafaq_meta_boxes', 9999 );


/**
 * Filter out Envira from showing on a custom post type.
 *
 * @since 1.0.0
 *
 * @param array $post_types Default post types to skip.
 * @return array $post_types Amended post types to skip.
 */
function rbc_qa_envira_skip_custom_post_type( $post_types ) {
    // Add your custom post type here.
    $post_types[] = 'qa_faqs';
    return $post_types;
}
add_filter( 'envira_gallery_skipped_posttypes', 'rbc_qa_envira_skip_custom_post_type' );
