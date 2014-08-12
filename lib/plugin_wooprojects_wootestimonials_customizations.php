<?php

/**
 * Remove meta boxes from WooProjects
 * @since 1.0.0
 *
*/
function rbc_remove_projects_meta_boxes() {
	remove_meta_box(' project-images', 'project', 'side' ); // remove the projects gallery from the WooProjects plugin
}
add_action( 'add_meta_boxes', 'rbc_remove_projects_meta_boxes' );

/**
 * Remove meta boxes from WooTestimonials
 * @since 1.0.0
 *
*/
function rbc_remove_testimonials_meta_boxes() {
	remove_meta_box( 'pageparentdiv', 'testimonial', 'side' );
	remove_meta_box( 'testimonial-categorydiv', 'testimonial', 'side'); // remove testimonial categories metabox from WooTestimonials
	remove_meta_box( 'authordiv', 'testimonial', 'normal' ); // remove the author metabox from WooTestimonials 
	remove_meta_box( 'wpseo_meta', 'testimonial', 'normal' );
}
add_action( 'add_meta_boxes', 'rbc_remove_testimonials_meta_boxes', 999 );

/**
 * Filter out Envira from showing on the testimonials custom post type
 * @since 1.0.0
 *
 * @param array $post_types Default post types to skip.
 * @return array $post_types Amended post types to skip.
 */
function rbc_envira_skip_testimonials_custom_post_type( $post_types ) {
    // Add your custom post type here.
    $post_types[] = 'testimonial';
    return $post_types;
}
add_filter( 'envira_gallery_skipped_posttypes', 'rbc_envira_skip_testimonials_custom_post_type' );