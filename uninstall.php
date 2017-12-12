<?php
	//if uninstall not called from WordPress exit
	if (!defined('WP_UNINSTALL_PLUGIN'))
		exit();
	
    // delete table
    global $wpdb;
    $table_name = $wpdb->prefix . 'slideshow';
    $sql = "DROP TABLE IF EXISTS $table_name;";
    $wpdb->query($sql);

	// delete option from options table
	delete_option('tkslideshow_version');
?>