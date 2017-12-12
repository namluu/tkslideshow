<?php
/*
Plugin Name: TK Slideshow
Plugin URI: namluu.com
Description: Slideshow management
Version: 1.0
Author: Nam Luu
Author URI: namluu.com
Author Email: nam.luuduc@gmail.com
License:

  Copyright blah blah

*/

class TkSlideshow {

    protected $_tkslideshow_version = '1.0';

    /**
     * Initializes the plugin by setting localization, filters, and administration functions.
     */
    function __construct() {

        // Register hooks that are fired when the plugin is activated, deactivated, and uninstalled, respectively.
        register_activation_hook( __FILE__, array( $this, 'activate' ) );
        
        // MOVE uninstall feature to uninstall.php
        //register_uninstall_hook( __FILE__, array( $this, 'uninstall' ) );

        // Register hook executes just before WordPress determines which template page to load
        //add_action( 'template_redirect', array( $this, 'increase_counter_when_home_visited' ) );
        
        // Add extra submenu to the admin panel
        add_action( 'admin_menu', array( $this, 'create_menu_admin_panel' ) );
        
        // Handle POST request, admin_action_($action)
        //add_action( 'admin_action_tk_slideshow_action', array( $this, 'tk_slideshow_admin_action' ) );

        add_action( 'admin_post_tk_slideshow_new_action', array( $this, 'tk_slideshow_admin_new_action' ) );
        add_action( 'admin_post_tk_slideshow_edit_action', array( $this, 'tk_slideshow_admin_edit_action' ) );
        
    } // end constructor

    /**
     * Fired when the plugin is activated.
     * Init nam_hit_counter option in DB
     */
    public function activate( $network_wide ) { 
    
        // if the WordPress version is older than 2.6, deactivate this plugin
        // admin_action_ hook appearance 2.6 
        if ( version_compare( get_bloginfo( 'version' ), '2.6', '<' ) ) {
            deactivate_plugins( basename( __FILE__ ) );
        } else {
            global $wpdb;
            $table_name = $wpdb->prefix . 'slideshow';
            $charset_collate = $wpdb->get_charset_collate();
            $sql = "CREATE TABLE $table_name (
                id int(9) NOT NULL AUTO_INCREMENT,
                name varchar(255) NOT NULL,
                description text NULL,
                link_url varchar(255) NULL,
                link_image varchar(255) NOT NULL,
                is_active tinyint(1) NOT NULL default 1,
                ordering int(9) NOT NULL default 0,
                created_date timestamp DEFAULT CURRENT_TIMESTAMP NOT NULL,
                UNIQUE KEY id (id)
            ) $charset_collate;";
            require_once( ABSPATH . 'wp-admin/includes/upgrade.php' );
            dbDelta($sql);
            add_option( 'tkslideshow_version', $this->_tkslideshow_version );
        }
    } // end activate

    /**
     * Increase counter when home page visited
     */ 
    /*function increase_counter_when_home_visited() {
        if (is_home()) {
            $aData = get_option( 'nam_hit_counter' );
            if ( $aData['active'] ) {
                $aData['counter']++;
                $aData['time'] = current_time('mysql');
                update_option( 'nam_hit_counter', $aData );
            }
        }
    }*/

    /**
     * Add submenu into Admin's panel : Appearance > TK Slideshow
     */ 
    function create_menu_admin_panel() {

        // list slideshow
        add_submenu_page(
            'themes.php',
            'TK Slideshow',
            'TK Slideshow',
            'manage_options',
            'tk-slideshow',
            array($this, 'tk_slideshow_plugin_index')
        );

        // new slideshow
        add_submenu_page(
            null,
            'New TK Slideshow',
            'New TK Slideshow',
            'manage_options',
            'tk-slideshow-new',
            array($this, 'tk_slideshow_plugin_new')
        );

        // edit slideshow
        add_submenu_page(
            null,
            'Edit TK Slideshow',
            'Edit TK Slideshow',
            'manage_options',
            'tk-slideshow-edit',
            array($this, 'tk_slideshow_plugin_edit')
        );
    }
    
    /**
     * Create Plugin option page
     */ 
    function tk_slideshow_plugin_index() {
        if (!current_user_can( 'manage_options' )) {
            wp_die( __('You do not have sufficient permission to access this page.') );
        }
        // Add css only plugin option page
        //wp_enqueue_style( 'nam-hitcounter', plugins_url( 'css/admin.css', __FILE__ ) );
        global $wpdb;
        $table_name = $wpdb->prefix . 'slideshow';
        $slideshows = $wpdb->get_results( "SELECT * FROM $table_name", OBJECT );

        if (isset($_GET['action']))
        {
            $result = 0;
            if ($_GET['action'] == 'delete' && $id = $_GET['id']) {
                $result = $wpdb->delete($table_name, array('id' => $id));
            }
            if ($_GET['action'] == 'inactive' && $id = $_GET['id']) {
                $result = $wpdb->update($table_name, array('is_active'=>0), array('id' => $id));
            }
            if ($_GET['action'] == 'active' && $id = $_GET['id']) {
                $result = $wpdb->update($table_name, array('is_active'=>1), array('id' => $id));
            }
            if ($result) {
                wp_safe_redirect( admin_url( 'themes.php?page=tk-slideshow&success=1' ) );
            } else {
                wp_safe_redirect( admin_url( 'themes.php?page=tk-slideshow&error=1' ) );
            }
        }
        
        // admin form manage counter
        include 'views/admin.php';
    }

    function tk_slideshow_plugin_new() {
        if (!current_user_can( 'manage_options' )) {
            wp_die( __('You do not have sufficient permission to access this page.') );
        }
        wp_enqueue_style( 'tk-slideshow', plugins_url( 'css/admin.css', __FILE__ ) );
        
        include 'views/new.php';
    }

    function tk_slideshow_plugin_edit() {
        if (!current_user_can( 'manage_options' )) {
            wp_die( __('You do not have sufficient permission to access this page.') );
        }
        wp_enqueue_style( 'tk-slideshow', plugins_url( 'css/admin.css', __FILE__ ) );

        global $wpdb;
        $table_name = $wpdb->prefix . 'slideshow';
        if (isset($_GET['id']) && $id = $_GET['id'])
        {
            $row = $wpdb->get_row("SELECT * FROM $table_name WHERE id = " . $id);
        }
        
        include 'views/edit.php';
    }

    /**
     * Handle reset counter
     * Redirect to a normal page after a POST request to prevent duplicate when user refreshes the page
     */     
    /*function tk_slideshow_admin_action() {
        //verify post is not a revision
        
        if ( isset( $_POST['reset'] ) ) {
            $aData = get_option( 'nam_hit_counter' );
            $aData['counter'] = 0;
            $aData['time'] = null;
            update_option( 'nam_hit_counter', $aData );
        }
        
        if ( isset( $_POST['enable'] ) ) {
            $aData = get_option( 'nam_hit_counter' );
            $aData['active'] = true;
            update_option( 'nam_hit_counter', $aData );
        }
        
        if ( isset( $_POST['disable'] ) ) {
            $aData = get_option( 'nam_hit_counter' );
            $aData['active'] = false;
            update_option( 'nam_hit_counter', $aData );
        }
        
        wp_safe_redirect( add_query_arg( 'updated', 'true', wp_get_referer() ) );
        exit();
    }*/

    function tk_slideshow_admin_new_action()
    {
        status_header(200);
        if ( isset( $_POST['save'] ) ) {
            global $wpdb;
            $table_name = $wpdb->prefix . 'slideshow';
            if ( ! function_exists( 'wp_handle_upload' ) ) {
                require_once( ABSPATH . 'wp-admin/includes/file.php' );
            }

            $uploadedfile = $_FILES['link_image'];

            $upload_overrides = array( 'test_form' => false );
            $movefile = wp_handle_upload( $uploadedfile, $upload_overrides );

            if ( $movefile && !isset( $movefile['error'] ) ) 
            {
                $wpdb->insert($table_name, array(
                    'name' => $_POST['name'],
                    'description' => stripslashes_deep($_POST['description']),
                    'link_url' => $_POST['link_url'],
                    'link_image' => $movefile['url'],
                    'ordering' => $_POST['ordering']
                    ));
                if ($wpdb->insert_id) {
                    wp_safe_redirect( admin_url( 'themes.php?page=tk-slideshow&success=1' ) );
                } else {
                    wp_safe_redirect( admin_url( 'themes.php?page=tk-slideshow&error=1' ) );
                }
            } 
            else 
            {
                wp_safe_redirect( admin_url( 'themes.php?page=tk-slideshow&error=1' ) );
            }

            
        }
        exit();
    }

    function tk_slideshow_admin_edit_action()
    {
        status_header(200);
        if ( isset( $_POST['save'] ) ) {
            global $wpdb;
            $table_name = $wpdb->prefix . 'slideshow';
            if ( ! function_exists( 'wp_handle_upload' ) ) {
                require_once( ABSPATH . 'wp-admin/includes/file.php' );
            }

            $uploadedfile = $_FILES['link_image'];

            $upload_overrides = array( 'test_form' => false );
            $movefile = wp_handle_upload( $uploadedfile, $upload_overrides );

            $data = array(
                'name' => $_POST['name'],
                'description' => stripslashes_deep($_POST['description']),
                'link_url' => $_POST['link_url'],
                'ordering' => $_POST['ordering']
                );
            if ($movefile && !isset( $movefile['error'] )) {
                $data['link_image'] = $movefile['url'];
            }
            $result = $wpdb->update($table_name, $data, array('id' => $_POST['id']));
            if ($result) {
                wp_safe_redirect( admin_url( 'themes.php?page=tk-slideshow&success=1' ) );
            } else {
                wp_safe_redirect( admin_url( 'themes.php?page=tk-slideshow&error=1' ) );
            }
        }
        exit();
    }

} // end class

$plugin_name = new TkSlideshow();

function getTkSlideshow()
{
    global $wpdb;
    $table_name = $wpdb->prefix . 'slideshow';
    $slideshows = $wpdb->get_results( "
        SELECT * 
        FROM $table_name 
        WHERE is_active = 1 
        ORDER BY ordering
        ", OBJECT );
    return $slideshows;
}