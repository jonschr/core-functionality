<?php
class TDWUR_Yoast {
	public $parent;

	function __construct( $parent ) {
		$this->parent = $parent;

		add_filter( 'redux/options/webmaster_user_role_config/sections', array( $this, 'settings_section' ) );
		
		add_action( 'plugins_loaded', array( $this, 'wpseo_admin_init' ), 16 );
		add_action( 'admin_menu', array( $this, 'admin_menu' ), 999 );
	}

	function is_active() {
		return ( function_exists( 'wpseo_init' ) );
	}

	function settings_section( $sections ) {
		if ( !$this->is_active() ) return $sections;

		$sections[] = array(
			'icon'      => 'wp-menu-image tools',
			'title'     => __('Yoast SEO', 'webmaster-user-role'),
			'fields'    => array(
				array(
					'id'        => 'webmaster_yoast_metabox_settings',
					'type'      => 'checkbox',
					'title'     => __('Yoast SEO Capabilities', 'redux-framework-demo'),
					'subtitle'  => __('Webmaster users can', 'redux-framework-demo'),

					'options'   => array(
						'yoast_post_metabox' => 'Edit SEO values on individual posts/pages',
						'yoast_settings' => 'Use Yoast Settings Menu',
					),
					
					'default'   => array(
						'yoast_post_metabox' => '0',
						'yoast_settings' => '0',
					)
				),
			)
		);

		return $sections;
	}

	function wpseo_admin_init() {
		if ( !$this->is_active() ) return;
		if ( !TD_WebmasterUserRole::current_user_is_webmaster() ) return;
		if ( empty( $GLOBALS['wpseo_metabox'] ) ) return;

		global $webmaster_user_role_config;
		if ( empty ( $webmaster_user_role_config['webmaster_yoast_metabox_settings']['yoast_post_metabox'] ) ) {
			remove_action( 'add_meta_boxes', array( $GLOBALS['wpseo_metabox'], 'add_meta_box' ) );
			remove_action( 'admin_enqueue_scripts', array( $GLOBALS['wpseo_metabox'], 'enqueue' ) );
			remove_action( 'wp_insert_post', array( $GLOBALS['wpseo_metabox'], 'save_postdata' ) );
			remove_action( 'edit_attachment', array( $GLOBALS['wpseo_metabox'], 'save_postdata' ) );
			remove_action( 'add_attachment', array( $GLOBALS['wpseo_metabox'], 'save_postdata' ) );
			remove_action( 'admin_init', array( $GLOBALS['wpseo_metabox'], 'setup_page_analysis' ) );
			remove_action( 'admin_init', array( $GLOBALS['wpseo_metabox'], 'translate_meta_boxes' ) );
		}	
	}

	function admin_menu() {
		if ( !TD_WebmasterUserRole::current_user_is_webmaster() ) return;

		global $webmaster_user_role_config;
		if ( empty ( $webmaster_user_role_config['webmaster_yoast_metabox_settings']['yoast_settings'] ) ) {
			remove_menu_page( 'wpseo_dashboard' );
		}
	}
}