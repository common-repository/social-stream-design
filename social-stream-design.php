<?php
/**
 * Plugin Name: WP Social Stream Designer
 * Plugin URI: https://www.solwininfotech.com/product/wordpress-plugins/social-stream-design/
 * Description: To create and design social streams in more pretty, attractive and colorful way.
 * Author: Solwin Infotech
 * Author URI: https://www.solwininfotech.com/
 * Copyright: Solwin Infotech
 * Version: 1.3
 * Requires at least: 5.4
 * Tested up to: 6.6.1
 * License: GPLv2 or later
 *
 * @package WP Social Stream Designer
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}
define( 'WPSOCIALSTREAMDESIGNER_DIR', plugin_dir_path( __FILE__ ) );
define( 'WPSOCIALSTREAMDESIGNER_URL', plugins_url( '', __FILE__ ) );
register_activation_hook( __FILE__, 'wpssdesigner_shortcodes_tables' );
add_action( 'wp_head', 'wpssdesigner_shortcodes_tables' );
add_action( 'admin_head', 'wpssdesigner_shortcodes_tables' );
if ( ! class_exists( 'WP_List_Table' ) ) {
	require_once ABSPATH . 'wp-admin/includes/class-wp-list-table.php';
}
/**
 * Create table 'ssd_shortcodes' when plugin activated
 *
 * @global object $wpdb
 */
function wpssdesigner_shortcodes_tables() {
	include_once ABSPATH . 'wp-admin/includes/upgrade.php';
	global $wpdb;
	$plugin_data     = get_plugin_data( plugins_url( 'social-stream-design.php', __FILE__ ) . '/social-stream-design.php', $markup = true, $translate = true );
	$current_version = $plugin_data['Version'];
	if ( ! empty( $wpdb->charset ) ) {
		$charset_collate = "DEFAULT CHARACTER SET $wpdb->charset";
	}
	if ( ! empty( $wpdb->collate ) ) {
			$charset_collate .= " COLLATE $wpdb->collate";
	}
	$shortcode_table_name = $wpdb->prefix . 'ssd_shortcodes';
	if ( $wpdb->get_var( $wpdb->prepare( 'SHOW TABLES LIKE %s', $shortcode_table_name ) ) != $shortcode_table_name ) {
		$sql = "CREATE TABLE $shortcode_table_name (ID int(9) NOT NULL AUTO_INCREMENT,shortcode_name tinytext NOT NULL,social_stream_name text NOT NULL,social_stream_settings text NOT NULL,UNIQUE KEY ID (ID)) $charset_collate;";
		dbDelta( $sql );
	}
	$feeds_table_name = $wpdb->prefix . 'ssd_feeds';
	if ( $wpdb->get_var( "SHOW TABLES LIKE '" . $wpdb->prefix . 'ssd_feeds' . "'" ) != $feeds_table_name ) {
		$sql = "CREATE TABLE $feeds_table_name (id int(9) NOT NULL AUTO_INCREMENT,feeds_settings text NOT NULL,refresh_feed_date DATETIME NOT NULL,PRIMARY KEY (id)) $charset_collate;";
		dbDelta( $sql );
	}
	$feeds_post_table_name = $wpdb->prefix . 'ssd_feed_posts';
	if ( $wpdb->get_var( "SHOW TABLES LIKE '" . $wpdb->prefix . 'ssd_feed_posts' . "'" ) != $feeds_post_table_name ) {
		$sql = "CREATE TABLE $feeds_post_table_name (feed_id int(11) NOT NULL,feed_type varchar(80) NULL,unique_id varchar(200) NULL,post_title text NULL,post_description blob,post_date datetime NULL,post_link varchar(200) NULL,type_in varchar(200) NULL,post_image varchar(1000) NULL,post_video varchar(10000) NULL,post_retweet_count int(11) NULL,post_comment_count int(11) NULL,post_favorite_count int(11) NULL,post_plays_count int(11) NULL,post_likes_count int(11) NULL,post_dislikes_count int(11) NULL,reply_to_link varchar(255) NULL,retweet_link varchar(255) NULL,favourite_link varchar(255) NULL,like_link varchar(255) NULL,play_link varchar(255) NULL,post_language varchar(30) NULL,post_user_name varchar(200) NULL,post_user_screen_name varchar(200) NULL,post_user_location varchar(200) NULL,post_user_description text NULL,post_user_link varchar(200) NULL,post_user_image varchar(200) NULL,post_user_followers_count int(11) NULL,post_user_friends_count int(11) NULL,post_refresh_date datetime) $charset_collate;";
		dbDelta( $sql );
	}
	$column_exists = $wpdb->get_results("DESCRIBE $feeds_post_table_name post_image");
    if (!empty($column_exists)) {
        $column_type = $column_exists[0]->Type;
        if ('varchar(10000)' != $column_type) {
            $sql = "ALTER TABLE $feeds_post_table_name MODIFY COLUMN post_image LONGTEXT";
            $wpdb->query($sql);
        }
    }
	$column_exists = $wpdb->get_results("DESCRIBE $feeds_post_table_name post_video");
    if (!empty($column_exists)) {
        $column_type = $column_exists[0]->Type;
        if ('varchar(10000)' != $column_type) {
            $sql = "ALTER TABLE $feeds_post_table_name MODIFY COLUMN post_video LONGTEXT";
            $wpdb->query($sql);
        }
    }
	$upload_dir = WP_CONTENT_DIR . '/uploads/social-stream/';
	if ( ! is_dir( $upload_dir ) ) {
		mkdir( $upload_dir, 0777 );
	}
	if ( $wpdb->get_var( 'SHOW COLUMNS FROM ' . $feeds_post_table_name . " LIKE 'post_status'" ) != 'post_status' ) {
		$sql = "ALTER TABLE $feeds_post_table_name ADD `post_status` VARCHAR(3) NULL AFTER `post_refresh_date`, ADD `moderate` VARCHAR(3) NULL AFTER `post_status`";
		$wpdb->query( $sql );
		$sql = "ALTER TABLE $feeds_post_table_name CHANGE `post_description` `post_description` BLOB NULL DEFAULT NULL";
		$wpdb->query( $sql );
	}
	if ( $wpdb->get_var( "SHOW COLUMNS FROM " . $feeds_post_table_name . " LIKE 'post_subattachments'" ) != 'post_subattachments' ) {
		$sql = "ALTER TABLE $feeds_post_table_name ADD `post_subattachments` LONGTEXT NULL DEFAULT NULL AFTER `post_video`";
		$wpdb->query( $sql );
	}
	update_option( 'ssd_version', $current_version );
}

require_once WPSOCIALSTREAMDESIGNER_DIR . 'admin/assets/feeds/TwitterAPIExchange.php';
require_once 'admin/assets/class-shortcode-list-table.php';
require_once 'admin/assets/class-feed-list-table.php';
require_once 'admin/assets/class-ssdfeedsactions.php';
require_once 'admin/assets/class-ssdlayoutsactions.php';
require_once 'include/feeds/class-ssdfrontfeeds.php';
require_once 'include/class-social-stream-main.php';
require_once 'class-socialstream.php';
