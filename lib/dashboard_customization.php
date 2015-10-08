<?php 

/**
 * Customize the admin menu for non-admin users
 * @since 1.0.0
 * @author Jon Schroeder
 *
 * Removes, by default, everything that a typical user wouldn't need to use
 *
*/
function rbc_edit_admin_menu() {
    
    global $current_user;
    $user_roles = $current_user->roles;
    $user_role = array_shift($user_roles);

    if ( $user_role == 'webmaster' ) {

        // Core WordPress menu items
        remove_menu_page( 'options-general.php' ); // wordpress settings and subitems (Settings > * )
        remove_menu_page( 'tools.php' ); // tools and subitems (Tools > * )
        // remove_menu_page( 'edit.php' ); // remove the blog (Posts)
        remove_menu_page( 'branding' );
        remove_menu_page( 'users.php' );
        remove_submenu_page( 'themes.php', 'themes.php' ); // themes subitem (Appearance > Themes)
        remove_submenu_page( 'themes.php', 'customize.php' ); // customize subitem (Appearance > Header)
        remove_submenu_page( 'themes.php', 'custom-header' ); // header subitem (Appearance > Header)
        remove_submenu_page( 'themes.php', 'custom-background' ); // background subitem (Appearance > Background)
        remove_submenu_page( 'users.php', 'profile.php' );

        // Gravity forms
        remove_submenu_page( 'gf_edit_forms', 'gf_help' ); // gravity forms help menu (Forms > Help)
        remove_submenu_page( 'gf_edit_forms', 'gf_update' ); // gravity forms updates (Forms > Update)
        remove_submenu_page( 'gf_edit_forms', 'gf_edit_forms' ); // gravity forms updates (Forms > Edit Forms)
        remove_submenu_page( 'gf_edit_forms', 'gf_new_form' ); // gravity forms updates (Forms > New Form)
        remove_submenu_page( 'gf_edit_forms', 'gf_settings' ); // gravity forms updates (Forms > Settings)
        remove_submenu_page( 'gf_edit_forms', 'gf_update' ); // gravity forms updates (Forms > Update)

        // Other plugins
        remove_menu_page( 'gadash_settings' ); // google analytics dashboard settings and subitems
        remove_menu_page( 'edit.php?post_type=acf' ); // Advanced Custom Fields settings
        remove_menu_page( 'edit.php?post_type=acf-field-group' ); // Advanced Custom Fields field group
        remove_menu_page( 'sucuriscan' ); // sucuri
        remove_menu_page( 'aiowpsec' ); // All In One Security & Firewall
        
        // Genesis framework settings
        remove_menu_page( 'genesis' ); // genesis settings

        // For use echoing the top-level menu items for testing
        // global $menu;
        // echo '<pre>';
        // print_r( $menu );
        // echo '</pre>';

    }
}
add_action( 'admin_menu', 'rbc_edit_admin_menu', 9999 );

/**
 *Add full access to Gravity Forms for webmasters
 *
 */
function rbc_add_gf_cap()
{
    $role = get_role( 'webmaster' );
    $role->add_cap( 'gform_full_access' );
}
add_action( 'admin_init', 'rbc_add_gf_cap' );

/**
 * Customize Menu Order
 * @since 1.0.0
 *
 * @param array $menu_ord. Current order.
 * @return array $menu_ord. New order.
 *
 * Customizes the menu order. Items not included within this order are likely to appear at the bottom of the list (top level menu items and spacers only).
 * If an item on this list is not present on the site itself, it's simply skipped.
 *
 */
function rbc_custom_menu_order( $menu_ord ) {
    if ( !$menu_ord ) return true;
    return array(
        'index.php', // this represents the dashboard link
        'genesis',
        'separator',
        'edit.php?post_type=page', //the page tab
        'edit.php', //the posts tab
        'edit.php?post_type=testimonials',
        'edit.php?post_type=faqs',
        'edit.php?post_type=partners',
        'edit.php?post_type=members',
        'separator1',
        'edit.php?post_type=envira',
        'edit.php?post_type=soliloquy',
        'optin-monster-api-settings',
        'upload.php', // the media manager
        'gf_edit_forms',
        'edit-comments.php', // the comments tab
        'separator2',
        'themes.php',
        'profile.php',
        'wpseo_dashboard',
        'wp-menu-separator',
    );
}
add_filter( 'custom_menu_order', 'rbc_custom_menu_order' );
add_filter( 'menu_order', 'rbc_custom_menu_order' );

//* Remove links from the sidebar
function rbc_remove_links() {
     remove_menu_page( 'link-manager.php' );
}
add_action( 'admin_menu', 'rbc_remove_links' );

/**
 * Remove dashboard panels
 * @since 1.0.0
 *
 * Removes the standard dashboard widgets for all users, including the administrator role on the site
 * (no one really misses these widgets)
 *
*/
function rbc_remove_dashboard_widgets() {
    global $wp_meta_boxes;
    unset($wp_meta_boxes['dashboard']['side']['core']['dashboard_quick_press']);
    unset($wp_meta_boxes['dashboard']['normal']['core']['dashboard_incoming_links']);
    unset($wp_meta_boxes['dashboard']['normal']['core']['dashboard_right_now']);
    unset($wp_meta_boxes['dashboard']['normal']['core']['dashboard_plugins']);
    unset($wp_meta_boxes['dashboard']['normal']['core']['dashboard_recent_drafts']);
    unset($wp_meta_boxes['dashboard']['normal']['core']['dashboard_recent_comments']);
    unset($wp_meta_boxes['dashboard']['normal']['core']['dashboard_activity']);
    unset($wp_meta_boxes['dashboard']['side']['core']['dashboard_primary']);
    unset($wp_meta_boxes['dashboard']['side']['core']['dashboard_secondary']);
    
    remove_action( 'welcome_panel', 'wp_welcome_panel' );
    
    // the commented code below will display the full array so that you can see what other things should need to be removed
    /* 
    echo '<pre>';
    print_r($wp_meta_boxes);
    echo '</pre>';
    */
}
add_action( 'wp_dashboard_setup', 'rbc_remove_dashboard_widgets' );

/**
 * Remove admin toolbar links
 * @since 1.0.0
 *
 * Removes links added both by core and by a number of plugins. They're helpful to me,
 * but not necessarily helpful to a 'webmaster' role user.
 *
*/
function rbc_remove_toolbar_links() {
  // this function removes admin bar links for all users EXCEPT for user 1
    global $wp_admin_bar;
    global $current_user;
    $user_roles = $current_user->roles;
    $user_role = array_shift($user_roles);

    if ( $user_role == 'webmaster' ) {
        $wp_admin_bar->remove_menu('themes'); // subitem themes
        // $wp_admin_bar->remove_menu('customize'); // subitem customize
        $wp_admin_bar->remove_menu('widgets'); // subitem widgets
        $wp_admin_bar->remove_menu('background'); // subitem background
        $wp_admin_bar->remove_menu('ddw-gravityforms-toolbar'); // Gravity Forms Extended Toolbar
        // $wp_admin_bar->remove_menu('w3tc'); // Total Cache
        $wp_admin_bar->remove_menu('comments'); // Comment count
        $wp_admin_bar->remove_menu('wpseo-menu'); // Wordpress SEO Menu
        $wp_admin_bar->remove_menu('ghooks'); // Visual Hooks Guide
        $wp_admin_bar->remove_menu('new-media'); // Add new subitem – media
        $wp_admin_bar->remove_menu('new-link'); // Add new subitem – link
        $wp_admin_bar->remove_menu('new-user'); // Add new subitem – user
        $wp_admin_bar->remove_menu('ddw-genesis-admin-bar'); // Genesis menu
        $wp_admin_bar->remove_menu('plugin-toggle'); // Plugin Toggle plugin
        $wp_admin_bar->remove_menu('custom-header'); // Plugin Toggle plugin
    }
    
    // the code below should echo the admin bar boxes, so that you can see how to remove others
    // echo '<pre>';
    // print_r($wp_admin_bar);
    // echo '</pre>';
}
add_action( 'wp_before_admin_bar_render', 'rbc_remove_toolbar_links' );

/**
 * Add custom login logo
 * @since 1.0.0
 *
 * Replaces the WordPress logo with the Red Blue Concepts one, packaged with this plugin
 *
*/
function rbc_custom_login_logo() {
    echo '<style type="text/css"> h1 {  background-image:url(' . '/wp-content/plugins/core-functionality/img/logo_admin.png)  !important; } h1 a { background:none !important;} </style>';
}
add_action('login_head',  'rbc_custom_login_logo');


function rbc_custom_dashboard_widgets() {
    global $wp_meta_boxes;
    $title = 'Having trouble with something?';
    wp_add_dashboard_widget('custom_help_widget', $title, 'rbc_custom_dashboard_help');
}
function rbc_custom_dashboard_help() {
    $blog_title = get_bloginfo();
    echo '<p>Welcome to the ' . $blog_title . ' website!</p>';
    echo "<p>If you're needing help with something, you can <a target='_blank' href='/use-site'>watch how-to videos right here</a> or contact <a target='_blank' href='http://redblue.us'>Red Blue Concepts</a> directly if you're still having trouble.";
}
add_action('wp_dashboard_setup', 'rbc_custom_dashboard_widgets');


/**
 * Remove the gray dotted dashboard placeholder
 * @since 1.0.0
 *
*/
function rbc_remove_gray_line() {
    $styles = '.metabox-holder .postbox-container .empty-container { border:none; }';
    echo '<style>' . $styles . '</style>';
}
add_action('wp_before_admin_bar_render', 'rbc_remove_gray_line', 10);

/**
 * Remove WP logo & menu
 * @since 1.0.0
 *
 * Remove the Wordpress logo from the admin menu bar.
 *
 */
function rbc_remove_wp_logo_from_admin_bar() {
        global $wp_admin_bar;
        $wp_admin_bar->remove_menu('wp-logo');
}
add_action('wp_before_admin_bar_render', 'rbc_remove_wp_logo_from_admin_bar', 0);

/**
 * Remove home icon
 * @since 1.0.0
 *
 * Remove the Wordpress logo from the admin menu bar.
 *
 */
function rbc_remove_home_icon() {
    $content = '#wpadminbar #wp-admin-bar-site-name>.ab-item:before { content: ""; } #wpadminbar .quicklinks>ul>li>a { padding: 0 7px 0 7px; text-align:center; }';
    echo '<style>' . $content . '</style>';
}
add_action('wp_before_admin_bar_render', 'rbc_remove_home_icon', 0);

/**
 * Customize the admin footer area
 * @since 1.0.0
 *
 * Customize the footer admin to display my company name
 * @param string $defaulttext
 * @return string $footertext, replacing the default completely
 * 
 */
function rbc_custom_footer_admin ( $defaulttext ) {
        $footertext = 'Site by <a target="_blank" href="http://redblue.us">Red Blue Concepts</a>';
        return $footertext;
}
add_filter('admin_footer_text', 'rbc_custom_footer_admin');