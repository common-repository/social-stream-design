<?php
/**
 * Class for layout action
 *
 * @version 1.0
 * @package WP Social Stream Designer
 */

/**
 * Get Layout Data
 *
 * @package    WP Social Stream Designer
 * @subpackage WP Social Stream Designer/admin/assets
 * @author     Solwin Infotech <support@solwininfotech.com>
 */
class WpSSDLayoutsActions {
	/**
	 * Feed ID
	 *
	 * @var string
	 */
	public $feed_id = 0;
	/**
	 * ID
	 *
	 * @var string
	 */
	public $id = 0;
	/**
	 * Feed type
	 *
	 * @var string
	 */
	public $feed_type = '';
	/**
	 * Feed table
	 *
	 * @var string
	 */
	public $feeds_table = '';
	/**
	 * Feed post table
	 *
	 * @var string
	 */
	public $feed_posts_table = '';
	/**
	 * Shortcode table
	 *
	 * @var string
	 */
	public $shortcode_table = '';
	/**
	 * Search Data
	 *
	 * @var string
	 */
	public $search_data = '';
	/**
	 * Initialize
	 *
	 * @since  1.0.0
	 * @return void
	 */
	public function init() {
		global $wpdb;
		$feed_table             = $wpdb->prefix . 'ssd_feeds';
		$feed_posts_table       = $wpdb->prefix . 'ssd_feed_posts';
		$shortcode_table        = $wpdb->prefix . 'ssd_shortcodes';
		$this->feeds_table      = $feed_table;
		$this->feed_posts_table = $feed_posts_table;
		$this->shortcode_table  = $shortcode_table;
		$this->feed_type        = '';
		if ( isset( $_POST['nonce'] ) ) {
			$nonce = sanitize_text_field( wp_unslash( $_POST['nonce'] ) );
			if ( ! wp_verify_nonce( $nonce, 'ajax-nonce' ) ) {
				wp_send_json_error( array( 'ssd-edit-action' => 'Nonce error' ) );
				die();
			}
		}
		if ( isset( $_REQUEST['action'] ) && 'edit' === $_REQUEST['action'] && isset( $_REQUEST['id'] ) && '' != $_REQUEST['id'] ) {
			$this->id = sanitize_text_field( wp_unslash( $_REQUEST['id'] ) );
		}
	}
	/**
	 * Add/Update layout/shortcode.
	 *
	 * @since  1.0.0
	 * @return void
	 */
	public function ssd_add_layout() {
		global $wpdb;
		$shortcode_table = $this->shortcode_table;
		$id              = $this->id;
		$feed_table      = $wpdb->prefix . 'ssd_feeds';
		$ssd_data        = array();
		if ( isset( $_POST['nonce'] ) ) {
			$nonce = sanitize_text_field( wp_unslash( $_POST['nonce'] ) );
			if ( ! wp_verify_nonce( $nonce, 'ajax-nonce' ) ) {
				wp_send_json_error( array( 'ssd-edit-action' => 'Nonce error' ) );
				die();
			}
		}
		if ( isset( $_POST['ssd'] ) ) {
			$ssd_data = map_deep( wp_unslash( $_POST['ssd'] ), 'sanitize_text_field' );
		}
		$feed_ids = isset( $ssd_data['feed_ids'] ) ? map_deep( wp_unslash( $ssd_data['feed_ids'] ), 'sanitize_text_field' ) : array();
		if ( isset($id) && $id > 0 && isset($_POST['ssd-drag-drop-layout'])){
			update_option( 'ssd_social_order_' . $id, implode(',', $_POST['ssd-drag-drop-layout']) );
		}
		$social_feed_name = array();

		foreach ( $feed_ids as $feed_id ) {
			$result_feeds = $wpdb->get_row( $wpdb->prepare( "SELECT feeds_settings FROM $wpdb->prefix" . 'ssd_feeds where id = %d', $feed_id ) );
			if ( $result_feeds ) {
				$feeds_settings     = $result_feeds->feeds_settings;
				$feeds_settings     = maybe_unserialize( $feeds_settings );
				$feed_name          = $feeds_settings;
				$social_feed_name[] = $feed_name['feed_name'];
			}
		}
		$social_feed_name = implode( ', ', $social_feed_name );
		if ( isset( $_POST['ssd_edit_action'] ) && 'edit' === $_POST['ssd_edit_action'] ) {
			$ssd_title = isset( $_POST['ssd_title'] ) ? sanitize_text_field( wp_unslash( $_POST['ssd_title'] ) ) : '';
			$wpdb->update(
				$shortcode_table,
				array(
					'shortcode_name'         => $ssd_title,
					'social_stream_name'     => sanitize_text_field( $social_feed_name ),
					'social_stream_settings' => maybe_serialize( $ssd_data ),
				),
				array( 'ID' => $id ),
				array(
					'%s',
					'%s',
					'%s',
				),
				array( '%d' )
			);
			$ssd_id = $id;
		} else {
			$ssd_title = isset( $_POST['ssd_title'] ) ? sanitize_text_field( wp_unslash( $_POST['ssd_title'] ) ) : '';
			$wpdb->insert(
				$shortcode_table,
				array(
					'shortcode_name'         => $ssd_title,
					'social_stream_name'     => sanitize_text_field( $social_feed_name ),
					'social_stream_settings' => maybe_serialize( $ssd_data ),
				),
				array(
					'%s',
					'%s',
					'%s',
				)
			);
			$ssd_id = $wpdb->insert_id;
		}
		if ( $ssd_id > 0 ) {
			wp_update_post(
				array(
					'ID'           => $ssd_data['ssd_stream_page'],
					'post_content' => '[social_stream_feeds id="' . $ssd_id . '"]',
				)
			);
			$update = 'added';
			if ( isset( $_POST['nonce'] ) ) {
				$nonce = sanitize_text_field( wp_unslash( $_POST['nonce'] ) );
				if ( ! wp_verify_nonce( $nonce, 'ajax-nonce' ) ) {
					wp_send_json_error( array( 'ssd-edit-action' => 'Nonce error' ) );
					die();
				}
			}
			if ( isset( $_POST['ssd_edit_action'] ) && 'edit' === $_POST['ssd_edit_action'] ) {
				$update = 'updated';
			}
			wp_safe_redirect( add_query_arg( 'id', $ssd_id, admin_url( "admin.php?page=social-stream-designer-add-layouts&action=edit&update=$update" ) ) );
			exit();
		} else {
			wp_safe_redirect( add_query_arg( 'id', $ssd_id, admin_url( 'admin.php?page=social-stream-designer-add-layouts&action=edit&update=false' ) ) );
			exit();
		}
	}
	/**
	 * Get feed layout settings from layout.
	 *
	 * @since  1.0.0
	 * @return array
	 */
	public function ssd_get_layout_detail() {
		global $wpdb;
		$id              = $this->id;
		$shortcode_table = $this->shortcode_table;
		$result_layouts  = $wpdb->get_row( $wpdb->prepare( "SELECT * FROM {$wpdb->prefix}ssd_shortcodes WHERE ID = %d", $id ) );
		if ( $result_layouts ) {
			return $result_layouts;
		} else {
			return '';
		}
	}
}
