<?php
class TDWUR_Itsec {
	public $parent;

	function __construct( $parent ) {
		$this->parent = $parent;

		add_filter( 'redux/options/webmaster_user_role_config/sections', array( $this, 'settings_section' ) );
		
		add_action( 'plugins_loaded', array( $this, 'modify_itsec_globals' ) );
	}

	function is_active() {
		return ( class_exists( 'ITSEC_Core' ) );
	}

	function settings_section( $sections ) {
		if ( !$this->is_active() ) return $sections;

		$sections[] = array(
			'icon'      => 'el-icon-lock',
			'title'     => __('iThemes Security', 'webmaster-user-role'),
			'fields'    => array(
				array(
					'id'        => 'webmaster_itsec',
					'type'      => 'checkbox',
					'title'     => __('iThemes Security', 'redux-framework-demo'),
					'subtitle'  => __('Webmaster users can', 'redux-framework-demo'),

					'options'   => array(
						'itsec_settings' => 'Manage security settings',
					),
					
					'default'   => array(
						'itsec_settings' => '0',
					)
				),
			)
		);

		return $sections;
	}

	function modify_itsec_globals() {
		if ( !$this->is_active() ) return;
		if ( !TD_WebmasterUserRole::current_user_is_webmaster() ) return;

		global $webmaster_user_role_config;
		if ( !empty ( $webmaster_user_role_config['webmaster_itsec']['itsec_settings'] ) ) return;

		global $itsec_globals;
		$itsec_globals['plugin_access_lvl']	= 'administrator';

		/* Hide update/what's new nag (for some reason this isn't linked to the plugin_access_lvl) */
		$itsec_globals['data']['setup_completed'] = true;
	}
}