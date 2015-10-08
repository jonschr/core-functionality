<?php

// Add all Gravity Forms capabilities (that way, we can remove them in a more fine-grained way later)
add_filter( 'td-webmaster-user-roleoption_cap_gravityforms_edit_forms', '__return_true' );

/**
 *
 * @author Tyler Digital
 * @link http://wordpress.org/support/plugin/webmaster-user-role
 * @since 1.0.0
 *
 * This entire plugin, essentially, has been added inside my core functionality plugin
 * simply because I have slightly different defaults that I'd like to stick to. This lets
 * me customize to my heart's content without worrying about upgrades.
 *
*/
if ( !class_exists( 'TD_WebmasterUserRole' ) ) {
	class TD_WebmasterUserRole {

		/*--------------------------------------------*
	 * Constants
	 *--------------------------------------------*/

		const name = 'Webmaster User Role';

		const slug = 'td-webmaster-user-role';

		private $default_options = array(
			'role_display_name' => 'Webmaster',
			// 'cap_gravityforms_view_entries' => 1,
			// 'cap_gravityforms_edit_forms' => 0,
		);

		/*--------------------------------------------*
	 * Constructor
	 *--------------------------------------------*/

		/**
		 * Initializes the plugin by setting localization, filters, and administration functions.
		 */
		function __construct() {

			// load_plugin_textdomain( 'td-webmaster-user-role', false, dirname( plugin_basename( __FILE__ ) ) . '/lang' );

			add_action( 'wpmu_new_blog', array( $this, 'add_role_to_blog' ) );
			add_action( 'updated_'.self::slug.'_option', array( $this, 'updated_option' ), 10, 3 );
			add_action( 'deleted_'.self::slug.'_option', array( $this, 'deleted_option' ) );
			// add_action( 'admin_menu', array( &$this, 'admin_menu' ), 999 );
			add_action( 'admin_init', array( &$this, 'create_role_if_missing' ), 10 );
			// add_action( 'admin_init', array( &$this, 'cleanup_dashboard_widgets' ), 20 );

			/* Load Modules */
			// include_once( dirname( RBC_DIR ). '/includes/module-itsec.php' );
			// new TDWUR_Itsec( $this );
			// include_once( dirname( __FILE__ ). '/includes/module-yoast.php' );
			// new TDWUR_Yoast( $this );

		} // end constructor

		function activate( $network_wide ) {
			$capabilities = $this->capabilities();
			add_role( 'webmaster', $this->get_option( 'role_display_name' ), $capabilities );
		}
		function deactivate( $network_wide ) {			
			remove_role( 'webmaster' );
		}

	/*--------------------------------------------*
	 * Core Functions
	 *---------------------------------------------*/

		public static function current_user_is_webmaster() {
			if ( is_super_admin() ) return false;
			return current_user_can( 'webmaster' );
		}

		function capabilities() {
			$admin_role = get_role( 'administrator' );
			$capabilities = $admin_role->capabilities;
			unset( $capabilities['level_10'] );
			unset( $capabilities['update_core'] );
			unset( $capabilities['install_plugins'] );
			unset( $capabilities['activate_plugins'] );
			unset( $capabilities['update_plugins'] );
			unset( $capabilities['edit_plugins'] );
			unset( $capabilities['delete_plugins'] );
			unset( $capabilities['install_themes'] );
			unset( $capabilities['update_themes'] );
			unset( $capabilities['switch_themes'] );
			unset( $capabilities['edit_themes'] );
			unset( $capabilities['delete_themes'] );
			unset( $capabilities['list_users'] );
			unset( $capabilities['create_users'] );
			unset( $capabilities['add_users'] );
			unset( $capabilities['edit_users'] );
			unset( $capabilities['delete_users'] );
			unset( $capabilities['remove_users'] );
			unset( $capabilities['promote_users'] );

			/* Add TablePress Capabilities */
			$capabilities['tablepress_list_tables'] = 1;
			$capabilities['tablepress_add_tables'] = 1;
			$capabilities['tablepress_edit_tables'] = 1;
			$capabilities['tablepress_import_tables'] = 1;
			$capabilities['tablepress_export_tables'] = 1;
			$capabilities['tablepress_access_about_screen'] = 1;
			$capabilities['tablepress_access_options_screen'] = 0;

			/* Add WooCommerce Capabilities */
			$woo_caps = $this->get_woocommerce_capabilities();
			foreach ( $woo_caps as $woo_cap_key => $woo_cap_array ) {
				foreach ($woo_cap_array as $key => $woo_cap) {
					$capabilities[$woo_cap] = 1;
				}
			}
			// $capabilities['manage_woocommerce'] = 0;

			$capabilities['editor'] = 1; // Needed for 3rd party plugins that check explicitly for the "editor" role (looking at you NextGen Gallery)

			$capabilities = apply_filters( 'td_webmaster_capabilities', $capabilities );
			return $capabilities;
		}

		public function get_woocommerce_capabilities() {
			$capabilities = array();

			$capabilities['core'] = array(
				'manage_woocommerce',
				'view_woocommerce_reports'
			);

			$capability_types = array( 'product', 'shop_order', 'shop_coupon' );

			foreach ( $capability_types as $capability_type ) {

				$capabilities[ $capability_type ] = array(
					// Post type
					"edit_{$capability_type}",
					"read_{$capability_type}",
					"delete_{$capability_type}",
					"edit_{$capability_type}s",
					"edit_others_{$capability_type}s",
					"publish_{$capability_type}s",
					"read_private_{$capability_type}s",
					"delete_{$capability_type}s",
					"delete_private_{$capability_type}s",
					"delete_published_{$capability_type}s",
					"delete_others_{$capability_type}s",
					"edit_private_{$capability_type}s",
					"edit_published_{$capability_type}s",

					// Terms
					"manage_{$capability_type}_terms",
					"edit_{$capability_type}_terms",
					"delete_{$capability_type}_terms",
					"assign_{$capability_type}_terms"
				);
			}

			return $capabilities;
		}

		function create_role_if_missing() {
			$wp_roles = new WP_Roles();
			if ( $wp_roles->is_role( 'webmaster' ) ) return;

			$this->deactivate( false );
			$this->activate( false );
		}

		function add_role_to_blog( $blog_id ) {
			switch_to_blog( $blog_id );
			$capabilities = $this->capabilities();
			add_role( 'webmaster', 'Webmaster', $capabilities );
			restore_current_blog();
		}

		function updated_option( $option, $oldvalue, $newValue ) {
			if ( $option=='role_display_name' || strpos( 'cap_', $option )!==false ) {
				$this->deactivate( false );
				$this->activate( false );
			}
		}

		function deleted_option( $option ) {
			if ( $option=='role_display_name' || strpos( 'cap_', $option )!==false ) {
				$this->deactivate( false );
				$this->activate( false );
			}
		}

		function get_option( $option ) {
			// Allow plugins to short-circuit options.
			$pre = apply_filters( 'pre_'.self::slug.'_option_' . $option, false );
			if ( false !== $pre )
				return $pre;

			$option = trim( $option );
			if ( empty( $option ) )
				return false;

			$saved_options = get_option( self::slug.'_options' );

			if ( isset( $saved_options[$option] ) ) {
				$value = $saved_options[$option];
			} else {
				$saved_options = ( empty( $saved_options ) ) ? array() : $saved_options;
				$saved_options = array_merge( $this->default_options, $saved_options );
				$value = $saved_options[$option];
			}

			return apply_filters( self::slug.'option_' . $option, $value );
		}

		function update_option( $option, $newValue ) {
			$option = trim( $option );
			if ( empty( $option ) )
				return false;

			if ( is_object( $newvalue ) )
				$newvalue = clone $newvalue;

			$oldvalue = $this->get_option( $option );
			$newvalue = apply_filters( 'pre_update_'.self::slug.'_option_' . $option, $newvalue, $oldvalue );

			// If the new and old values are the same, no need to update.
			if ( $newvalue === $oldvalue )
				return false;

			$_newvalue = $newvalue;
			$newvalue = maybe_serialize( $newvalue );

			do_action( 'update_'.self::slug.'_option', $option, $oldvalue, $_newvalue );

			$options = get_option( self::slug.'_options' );
			if ( empty( $options ) ) $options = array( $option => $newValue );
			else $options[$option] = $newValue;
			update_option( self::slug.'_options', $options );

			do_action( "update_".self::slug."_option_{$option}", $oldvalue, $_newvalue );
			do_action( 'updated_'.self::slug.'_option', $option, $oldvalue, $_newvalue );

			return true;
		}

		function delete_option( $option ) {
			do_action( 'delete_'.self::slug.'_option', $option );
			$options = get_option( self::slug.'_options' );
			if ( !isset( $options[$option] ) ) return false;
			unset( $options[$option] );

			$result = update_option( self::slug.'_options', $options );

			if ( $result ) {
				do_action( "delete_".self::slug."_option_$option", $option );
				do_action( 'deleted_'.self::slug.'_option', $option );
				return true;
			}
			return false;
		}

	/*--------------------------------------------*
	 * Private Functions
	 *---------------------------------------------*/

		function _blogs() {
			global $wpdb;
			$blogs = $wpdb->get_col( $wpdb->prepare( "
			SELECT blog_id
			FROM {$wpdb->blogs}
			WHERE site_id = %d
			AND blog_id <> %d
			AND spam = '0'
			AND deleted = '0'
			AND archived = '0'
			ORDER BY registered DESC
			LIMIT %d, 5
		", $wpdb->siteid, $wpdb->blogid, $offset ) );

			return $blogs;
		}

	} // end class
} // end class_exists()
if ( !isset( $td_webmaster_user_role ) ) $td_webmaster_user_role = new TD_WebmasterUserRole();
register_activation_hook( __FILE__, array( $td_webmaster_user_role, 'activate' ) );
register_deactivation_hook( __FILE__, array( $td_webmaster_user_role, 'deactivate' ) );