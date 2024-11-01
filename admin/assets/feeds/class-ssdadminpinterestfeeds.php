<?php
/**
 * Pinterest Social media class file get all social feed
 *
 * @version 1.0
 * @package WP Social Stream Designer
 */

/**
 * Get Pinterest stream data
 *
 * @package    WP Social Stream Designer
 * @subpackage WP Social Stream Designer/admin/assets/feeds
 * @author     Solwin Infotech <support@solwininfotech.com>
 */
class SSDAdminpinterestFeeds {
	/**
	 * Table name
	 *
	 * @var string
	 */
	public $feed_posts_table = '';
	/**
	 * Feed Object
	 *
	 * @var string
	 */
	public $feeds_object = '';
	/**
	 * Feed Details
	 *
	 * @var string
	 */
	public $feed_detail = '';
	/**
	 * Feed ID
	 *
	 * @var int
	 */
	public $feed_id = '';
	/**
	 * Post Feeds
	 *
	 * @var array
	 */
	public $post_feeds = array();
	/**
	 * Count Feed Limit
	 *
	 * @var int
	 */
	public $ssd_feed_limit_count = '0';
	/**
	 * Feed Type
	 *
	 * @var string
	 */
	public $feed_type = '';
	/**
	 * Initial Setup
	 *
	 * @since  1.0.0
	 * @return void
	 */
	public function init() {
		global $wpdb;
		$this->feed_posts_table = $wpdb->prefix . 'ssd_feed_posts';
		if ( isset( $_POST['feed_stream_nonce'] ) ) {
			if ( isset( $_POST['feed_stream_nonce'] ) && wp_verify_nonce( sanitize_text_field( wp_unslash( $_POST['feed_stream_nonce'] ) ), 'social-stream-designer_meta_box_nonce' ) ) {
				if ( isset( $_REQUEST['action'] ) && 'edit' === $_REQUEST['action'] && isset( $_REQUEST['id'] ) && '' != $_REQUEST['id'] ) {
					$this->feed_id = sanitize_text_field( wp_unslash( $_REQUEST['id'] ) );
				}
			}
		}
		$feed_detail       = $this->ssd_get_feed_detail();
		$this->feed_detail = $feed_detail;
	}
	/**
	 * Get feed type from feed id.
	 *
	 * @since  1.0.0
	 * @return array
	 */
	public function ssd_get_feed_detail() {
		global $wpdb;
		$feed_id      = $this->feed_id;
		$result_feeds = $wpdb->get_row( $wpdb->prepare( "select feeds_settings from $wpdb->prefix" . 'ssd_feeds where id = %d', $feed_id ) );
		if ( $result_feeds ) {
			$feeds_settings  = $result_feeds->feeds_settings;
			$feeds_settings  = maybe_unserialize( $feeds_settings );
			$this->feed_type = $feeds_settings['feed'];
			return $feeds_settings;
		} else {
			return '';
		}
	}
	/**
	 * Get all feeds
	 *
	 * @since  1.0.0
	 * @return string
	 */
	public function ssd_get_pinterest_stream_from_feed() {
		$feed_detail      = $this->feed_detail;
		$feed_limit       = $feed_detail['feed_limit'];
		$username         = $feed_detail['ssd_feed_type_pinterest_userid'] ? $feed_detail['ssd_feed_type_pinterest_userid'] : '';
		$board_name       = $feed_detail['ssd_feed_type_pinterest_boardname'] ? $feed_detail['ssd_feed_type_pinterest_boardname'] : '';
		$pinterest_stream = get_option( 'pinterest_stream' );
		if ( '' == $username || '' == $board_name ) {
			return new WP_Error( 'ssd-feeds-errors', esc_html__( 'Please enter User ID and Board Name', 'social-stream-design' ) );
		}
		$url       = 'https://api.pinterest.com/v3/pidgets/boards/' . $username . '/' . $board_name . '/pins/';
		$read_feed = Wp_Social_Stream_Main::ssd_get_data_from_remote_url( $url );

		$return        = json_decode( $read_feed );
		$get_php_error = Wp_Social_Stream_Main::ssd_get_last_error();
		if ( $get_php_error ) {
			$message = $get_php_error['message'] . '!!!! ' . esc_html__( 'Please try again', 'social-stream-design' );
			return new WP_Error( 'ssd-feeds-errors', $message );
		}
		if ( ! $return ) {
			return 'Please Re-enter Pinterest "User ID" and "Board Name"';
		}
		$pinterest_feeds = array();
		$pins_post       = '';
		if ( isset( $return->data->pins ) ) {
			$pins_post = $return->data->pins;
		}
		if ( ! empty( $pins_post ) ) {
			foreach ( $return->data->pins as $pins ) {
				foreach ( $pins->images as $image ) {
					$feeds['post_image'] = $image->url;
				}
				$ssd_str    = $pins->pinner->image_small_url;
				$img_string = explode( '30x30_RS', $ssd_str );
				if ( isset( $img_string[0] ) && isset( $img_string[1] ) ) {
					$profile_img = $img_string[0] . '60x60_RS' . $img_string[1];
				} else {
					$profile_img = $img_string[0];
				}
				$feeds['feed_id']                   = $this->feed_id;
				$feeds['unique_id']                 = $pins->id;
				$feeds['feed_type']                 = 'pinterest';
				$feeds['post_video']                = '';
				$feeds['post_title']                = '';
				$feeds['post_description']          = $pins->description;
				$feeds['post_date']                 = '';
				$feeds['post_link']                 = 'https://in.pinterest.com/pin/' . $pins->id;
				$feeds['post_comment_count']        = '0';
				$feeds['post_plays_count']          = '0';
				$feeds['post_likes_count']          = $pins->pinner->pin_count;
				$feeds['post_dislikes_count']       = '0';
				$feeds['post_language']             = '';
				$feeds['post_user_name']            = $pins->pinner->full_name;
				$feeds['post_user_screen_name']     = $pins->pinner->full_name;
				$feeds['post_user_description']     = $pins->pinner->about;
				$feeds['post_user_link']            = $pins->pinner->profile_url;
				$feeds['post_user_image']           = $profile_img;
				$feeds['post_user_followers_count'] = $pins->pinner->follower_count;
				$feeds['post_user_friends_count']   = $pins->pinner->follower_count;
				$feeds['post_retweet_count']        = $pins->repin_count;
				$feeds['post_favorite_count']       = '';
				$feeds['reply_to_link']             = '';
				$feeds['retweet_link']              = '';
				$feeds['favourite_link']            = '';
				$feeds['like_link']                 = '';
				$feeds['play_link']                 = '';
				$feeds['type_in']                   = 'image';
				$this->ssd_feed_limit_count         = $this->ssd_feed_limit_count + 1;
				if ( $this->ssd_feed_limit_count <= $feed_limit ) {
					$this->post_feeds[] = $feeds;
				}
			}
		}
	}
}
