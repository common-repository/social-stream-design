<?php
/**
 * Flicker Social media class file get all social feed
 *
 * @version 1.0
 * @package WP Social Stream Designer
 */

/**
 * Get Flickr stream data
 *
 * @package    WP Social Stream Designer
 * @subpackage WP Social Stream Designer/admin/assets/feeds
 * @author     Solwin Infotech <support@solwininfotech.com>
 */
class SSDAdmintiktokFeeds {

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
	 * Feed Id
	 *
	 * @var int
	 */
	public $feed_id = '';

	/**
	 * Max Cursor
	 *
	 * @var int
	 */
	public $max_cursor = 0;

	/**
	 * Min Cursor
	 *
	 * @var int
	 */
	public $min_cursor = 0;

	/**
	 * Flicker Feed
	 *
	 * @var array
	 */
	public $tiktok_feeds = array();

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
	 * Initial set up
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
	 * Get all Feeds.
	 *
	 * @since  1.0.0
	 * @return array
	 */
	public function ssd_get_tiktok_stream_from_feed() {

		$feed_detail      = $this->feed_detail;
		$feed_type_tiktok = $feed_detail['feed_type_tiktok'];
		$feed_limit       = $feed_detail['feed_limit'];
		$tiktok_hashtag   = $feed_detail['tiktok_hashtag'];
		$tiktok_username  = $feed_detail['tiktok_username'];
		if ( 'hashtag' == $feed_type_tiktok && '' == $tiktok_hashtag ) {
			return new WP_Error( 'ssd-feeds-errors', esc_html__( 'Please enter', 'social-stream-design' ) . esc_html( ' Hashtag' ) );
		}
		if ( 'username' == $feed_type_tiktok && '' == $tiktok_username ) {
			return new WP_Error( 'ssd-feeds-errors', esc_html__( 'Please enter', 'social-stream-design' ) . esc_html( ' Username' ) );
		}
		if ( 'hashtag' === $feed_type_tiktok ) {
			if ( $feed_limit < 100 ) {
				$per_page = $feed_limit;
			} else {
				$per_page = 100;
			}
			$url             = 'https://www.tiktok.com/node/share/tag/' . $tiktok_hashtag;
			$get_feeds       = Wp_Social_Stream_Main::ssd_get_data_from_remote_url( $url );
			$hashtag_profile = json_decode( $get_feeds );
			$min_cursor      = $this->min_cursor;
			$max_cursor      = $this->max_cursor;
			if ( isset( $hashtag_profile->body->challengeData->challengeId ) ) {
				$profile_id = $hashtag_profile->body->challengeData->challengeId;
				$url        = 'https://www.tiktok.com/node/video/feed/?id=' . $profile_id . '&count=' . $per_page . '&maxCursor=' . $max_cursor . '&minCursor=' . $min_cursor . '&type=3';
				$get_feeds  = Wp_Social_Stream_Main::ssd_get_data_from_remote_url( $url );
				$get_feeds  = json_decode( $get_feeds );
				if ( $get_feeds ) {
					if ( isset( $get_feeds->body->itemListData ) && ! empty( $get_feeds->body->itemListData ) ) {
						$tiktok_items     = $get_feeds->body->itemListData;
						$this->min_cursor = $get_feeds->body->minCursor;
						$this->max_cursor = $get_feeds->body->maxCursor;

						foreach ( $tiktok_items as $tiktok_item ) {
							$feeds['feed_id']                   = $this->feed_id;
							$feeds['unique_id']                 = isset( $tiktok_item->itemInfos->id ) ? $tiktok_item->itemInfos->id : '';
							$feeds['post_title']                = isset( $tiktok_item->itemInfos->text ) ? $tiktok_item->itemInfos->text : '';
							$feeds['post_description']          = isset( $tiktok_item->itemInfos->text ) ? $tiktok_item->itemInfos->text : '';
							$feeds['post_video']                = isset( $tiktok_item->itemInfos->video->urls[0] ) ? $tiktok_item->itemInfos->video->urls[0] : '';
							$feeds['post_favorite_count']       = isset( $tiktok_item->itemInfos->diggCount ) ? $tiktok_item->itemInfos->diggCount : 0;
							$feeds['type_in']                   = 'video';
							$feeds['post_likes_count']          = '';
							$feeds['post_dislikes_count']       = '';
							$feeds['post_comment_count']        = isset( $tiktok_item->itemInfos->commentCount ) ? $tiktok_item->itemInfos->commentCount : 0;
							$feeds['post_plays_count']          = isset( $tiktok_item->itemInfos->playCount ) ? $tiktok_item->itemInfos->playCount : 0;
							$feeds['post_retweet_count']        = isset( $tiktok_item->itemInfos->shareCount ) ? $tiktok_item->itemInfos->shareCount : 0;
							$feeds['post_user_name']            = isset( $tiktok_item->authorInfos->uniqueId ) ? $tiktok_item->authorInfos->uniqueId : '';
							$feeds['post_user_screen_name']     = isset( $tiktok_item->authorInfos->nickName ) ? $tiktok_item->authorInfos->nickName : '';
							$feeds['post_user_description']     = isset( $tiktok_item->authorInfos->signature ) ? $tiktok_item->authorInfos->signature : '';
							$feeds['post_user_image']           = isset( $tiktok_item->authorInfos->covers ) ? $tiktok_item->authorInfos->covers[0] : '';
							$feeds['feed_type']                 = 'tiktok';
							$feeds['post_date']                 = gmdate( 'Y-m-d h:i:s', $tiktok_item->itemInfos->createTime );
							$feeds['post_link']                 = 'https://www.tiktok.com/@' . $tiktok_item->authorInfos->uniqueId . '/video/' . $tiktok_item->itemInfos->id;
							$feeds['post_user_link']            = 'https://www.tiktok.com/@' . $tiktok_item->authorInfos->uniqueId;
							$feeds['post_image']                = isset( $tiktok_item->itemInfos->covers ) ? $tiktok_item->itemInfos->covers[0] : '';
							$feeds['post_language']             = '';
							$feeds['post_user_location']        = '';
							$feeds['post_user_friends_count']   = 0;
							$feeds['reply_to_link']             = '';
							$feeds['retweet_link']              = '';
							$feeds['favourite_link']            = '';
							$feeds['play_link']                 = '';
							$feeds['like_link']                 = '';
							$feeds['post_user_followers_count'] = isset( $tiktok_item->authorStats->followerCount ) ? $tiktok_item->authorStats->followerCount : 0;

							$this->ssd_feed_limit_count = $this->ssd_feed_limit_count + 1;
							if ( $this->ssd_feed_limit_count <= $feed_limit ) {
								$this->post_feeds[] = $feeds;
							}
						}
					}
				} else {
					return new WP_Error( 'ssd-feeds-errors', esc_html__( 'Error in getting videos of hashtag.', 'social-stream-design' ) );
				}
			} else {
				return new WP_Error( 'ssd-feeds-errors', esc_html__( 'Error in getting profile data of hashtag.', 'social-stream-design' ) );
			}
			if ( $this->ssd_feed_limit_count < $feed_limit ) {
					$this->ssd_get_tiktok_stream_from_feed();
			}
		} elseif ( 'username' === $feed_type_tiktok ) {
			if ( $feed_limit < 100 ) {
				$per_page = $feed_limit;
			} else {
				$per_page = 100;
			}
			$url             = 'https://www.tiktok.com/node/share/user/@' . $tiktok_username;
			$get_feeds       = Wp_Social_Stream_Main::ssd_get_data_from_remote_url( $url );
			$hashtag_profile = json_decode( $get_feeds );
			$min_cursor      = $this->min_cursor;
			$max_cursor      = $this->max_cursor;
			if ( isset( $hashtag_profile->body->userData->userId ) ) {
				$profile_id = $hashtag_profile->body->userData->userId;
				$url        = 'https://www.tiktok.com/node/video/feed/?id=' . $profile_id . '&count=500&maxCursor=' . $max_cursor . '&minCursor=' . $min_cursor . '&type=1';
				$get_feeds  = Wp_Social_Stream_Main::ssd_get_data_from_remote_url( $url );
				$get_feeds  = json_decode( $get_feeds );
				if ( $get_feeds ) {
					if ( isset( $get_feeds->body->itemListData ) && ! empty( $get_feeds->body->itemListData ) ) {
						$tiktok_items     = $get_feeds->body->itemListData;
						$this->min_cursor = $get_feeds->body->minCursor;
						$this->max_cursor = $get_feeds->body->maxCursor;

						foreach ( $tiktok_items as $tiktok_item ) {
							$feeds['feed_id']                   = $this->feed_id;
							$feeds['unique_id']                 = isset( $tiktok_item->itemInfos->id ) ? $tiktok_item->itemInfos->id : '';
							$feeds['post_title']                = isset( $tiktok_item->itemInfos->text ) ? $tiktok_item->itemInfos->text : '';
							$feeds['post_description']          = isset( $tiktok_item->itemInfos->text ) ? $tiktok_item->itemInfos->text : '';
							$feeds['post_video']                = isset( $tiktok_item->itemInfos->video->urls[0] ) ? $tiktok_item->itemInfos->video->urls[0] : '';
							$feeds['post_favorite_count']       = isset( $tiktok_item->itemInfos->diggCount ) ? $tiktok_item->itemInfos->diggCount : 0;
							$feeds['type_in']                   = 'video';
							$feeds['post_likes_count']          = '';
							$feeds['post_dislikes_count']       = '';
							$feeds['post_comment_count']        = isset( $tiktok_item->itemInfos->commentCount ) ? $tiktok_item->itemInfos->commentCount : 0;
							$feeds['post_plays_count']          = isset( $tiktok_item->itemInfos->playCount ) ? $tiktok_item->itemInfos->playCount : 0;
							$feeds['post_retweet_count']        = isset( $tiktok_item->itemInfos->shareCount ) ? $tiktok_item->itemInfos->shareCount : 0;
							$feeds['post_user_name']            = isset( $tiktok_item->authorInfos->uniqueId ) ? $tiktok_item->authorInfos->uniqueId : '';
							$feeds['post_user_screen_name']     = isset( $tiktok_item->authorInfos->nickName ) ? $tiktok_item->authorInfos->nickName : '';
							$feeds['post_user_description']     = isset( $tiktok_item->authorInfos->signature ) ? $tiktok_item->authorInfos->signature : '';
							$feeds['post_user_image']           = isset( $tiktok_item->authorInfos->covers ) ? $tiktok_item->authorInfos->covers[0] : '';
							$feeds['feed_type']                 = 'tiktok';
							$feeds['post_date']                 = gmdate( 'Y-m-d h:i:s', $tiktok_item->itemInfos->createTime );
							$feeds['post_link']                 = 'https://www.tiktok.com/@' . $tiktok_item->authorInfos->uniqueId . '/video/' . $tiktok_item->itemInfos->id;
							$feeds['post_user_link']            = 'https://www.tiktok.com/@' . $tiktok_item->authorInfos->uniqueId;
							$feeds['post_image']                = isset( $tiktok_item->itemInfos->covers ) ? $tiktok_item->itemInfos->covers[0] : '';
							$feeds['post_language']             = '';
							$feeds['post_user_location']        = '';
							$feeds['post_user_friends_count']   = 0;
							$feeds['reply_to_link']             = '';
							$feeds['retweet_link']              = '';
							$feeds['favourite_link']            = '';
							$feeds['play_link']                 = '';
							$feeds['like_link']                 = '';
							$feeds['post_user_followers_count'] = isset( $tiktok_item->authorStats->followerCount ) ? $tiktok_item->authorStats->followerCount : 0;

							$this->ssd_feed_limit_count = $this->ssd_feed_limit_count + 1;
							if ( $this->ssd_feed_limit_count <= $feed_limit ) {
								$this->post_feeds[] = $feeds;
							}
						}
					}
				} else {
					return new WP_Error( 'ssd-feeds-errors', esc_html__( 'Error in getting videos of user.', 'social-stream-design' ) );
				}
			} else {
				return new WP_Error( 'ssd-feeds-errors', esc_html__( 'Error in getting profile data of User.', 'social-stream-design' ) );
			}
		}
	}

}
