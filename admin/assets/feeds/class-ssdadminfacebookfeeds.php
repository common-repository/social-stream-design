<?php
/**
 * Facebook Social media class file get all social feed
 *
 * @version 1.0
 * @package WP Social Stream Designer
 */

/**
 * Get Facebook stream data
 *
 * @package    WP Social Stream Designer
 * @subpackage WP Social Stream Designer/admin/assets/feeds
 * @author     Solwin Infotech <support@solwininfotech.com>
 */
class SSDAdminfacebookFeeds {
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
	 * Feed id
	 *
	 * @var int
	 */
	public $feed_id = '';
	/**
	 * Next Page
	 *
	 * @var string
	 */
	public $next_page = '';
	/**
	 * Post Feeds
	 *
	 * @var array
	 */
	public $post_feeds = array();
	/**
	 * Second next page
	 *
	 * @var string
	 */
	public $second_next_page = '';
	/**
	 * Count Feed limit
	 *
	 * @var int
	 */
	public $ssd_feed_limit_count = '0';
	/**
	 * Feed Type
	 *
	 * @var int
	 */
	public $feed_type = '';
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
	 *  Refresh Facebook Access Token.
	 *
	 * @param string $access_token fb access token.
	 * @param string $facebook_id facebook id.
	 * @param string $facebook_secret facebook secret key.
	 * @return string
	 */
	public function ssd_get_facebook_refresh_token_url( $access_token, $facebook_id, $facebook_secret ) {
		return 'https://graph.facebook.com/oauth/access_token?client_id=' . $facebook_id . '&client_secret=' . $facebook_secret . '&grant_type=fb_exchange_token&fb_exchange_token=' . $access_token;
	}
	/**
	 *  Get All Facebook Feeds.
	 *
	 * @since  1.0.0
	 * @return string
	 */
	public function ssd_get_facebook_stream_from_feed() {
		$feed_detail        = $this->feed_detail;
		$feed_type_facebook = $feed_detail['feed_type_facebook'];
		$feed_limit         = $feed_detail['feed_limit'];
		$facebook_stream    = get_option( 'facebook_stream' );
		$app_id             = $facebook_stream['facebook_id'];
		$app_secret         = $facebook_stream['facebook_secret'];
		$fb_accesstoken     = $facebook_stream['facebook_access_token'];
		$access_token       = $app_id . '|' . $app_secret;
		if ( '' == $app_id || '' == $app_secret || '' == $fb_accesstoken ) {
			return new WP_Error( 'ssd-feeds-errors', esc_html__( 'Authentication Missing. Please Check Authentication Setting', 'social-stream-design' ) );
		}
		$get_facebook_feeds = $this->ssd_get_facebook_refresh_token_url( $fb_accesstoken, $app_id, $app_secret );
		if ( 'page' === $feed_type_facebook ) {

			$fb_page_id = $feed_detail['fb_page_id'];
			if ( '' == $fb_page_id ) {
				return new WP_Error( 'ssd-feeds-errors', esc_html__( 'Please enter', 'social-stream-design' ) . esc_html( ' Page ID' ) );
			}
			$url       = 'https://graph.facebook.com/v20.0/' . $fb_page_id . '/posts?fields=id,created_time,full_picture,attachments{media,subattachments},story,status_type,message,from,likes.summary(true),comments.summary(true),permalink_url,sharedposts&access_token=' . $fb_accesstoken;
			$get_feeds = Wp_Social_Stream_Main::ssd_get_data_from_remote_url( $url );

			$posts = json_decode( $get_feeds, true );
			if ( isset( $posts['error'] ) ) {
				$message = $posts['error']['message'];
				return new WP_Error( 'ssd-feeds-errors', $message );
			}

			$nextpage = isset( $posts['paging']['next'] ) ? $posts['paging']['next'] : '';
			if ( '' !== $this->next_page ) {
				$url       = $this->next_page;
				$get_feeds = Wp_Social_Stream_Main::ssd_get_data_from_remote_url( $url );
				$posts     = json_decode( $get_feeds, true );
				$nextpage  = isset( $posts['paging']['next'] ) ? $posts['paging']['next'] : '';
			}

			$get_php_error = Wp_Social_Stream_Main::ssd_get_last_error();
			if ( $get_php_error ) {
				$message = $get_php_error['message'] . '!!!! ' . esc_html__( 'Please try again', 'social-stream-design' );
				return new WP_Error( 'ssd-feeds-errors', $message );
			}

			if ( ! $posts ) {
				return 'Please Re-enter "Facebook ID, Facebook secret and Facebook page ID"';
			}

			$facebook_feeds = array();
			$post_data      = isset( $posts['data'] ) ? $posts['data'] : array();
			if ( ! empty( $post_data ) ) {
				foreach ( $post_data as $post ) {
					if ( isset( $post['attachments']['data'][0]['media']['image']['src'] ) ) {
						$post_img_src = $post['attachments']['data'][0]['media']['image']['src'];
					} else {
						$post_img_src = '';
					}
					if ( isset( $post['message'] ) ) {
						$fb_message = $post['message'];
					} else {
						$fb_message = '';
					}
					if ( 'added_video' === $post['status_type'] ) {
						if ( isset( $post['attachments']['data'][0]['media']['source'] ) ) {
							$post_img_src = $post['attachments']['data'][0]['media']['source'];
						} else {
							$post_img_src = '';
						}
					}
					$subattachments = array();
					if ( isset( $post['attachments']['data'][0]['subattachments'] ) ) {
						$subattachments_data = $post['attachments']['data'][0]['subattachments']['data'];
						foreach( $subattachments_data as $subattachments_idex => $subattachments_value){
							$subattachments[$subattachments_idex]['src'] = $subattachments_value['media']['image']['src'];
							$subattachments[$subattachments_idex]['type'] = $subattachments_value['type'];
							$subattachments[$subattachments_idex]['url'] = isset( $subattachments_value['media']['source'] ) ? $subattachments_value['media']['source'] : '';
						}
					}
					$time_ar                        = explode( 'T', $post['created_time'] );
					$ids                            = explode( '_', $post['id'] );
					$feeds['unique_id']             = $ids[1];
					$feeds['post_title']            = '';
					$feeds['post_description']      = Wp_Social_Stream_Main::ssd_make_links( $fb_message );
					$feeds['post_date']             = $time_ar[0];
					$feeds['post_link']             = $post['permalink_url'];
					$feeds['post_image']            = isset( $post['full_picture'] ) ? $post['full_picture'] : '';
					$feeds['url']                   = $post_img_src;
					$feeds['post_user_name']        = $post['from']['name'];
					$feeds['post_user_screen_name'] = $post['from']['name'];
					$feeds['post_user_link']        = 'https://www.facebook.com/' . $post['id'];
					$feeds['post_favorite_count']   = '';
					if ( isset( $post['comments']['summary']['total_count'] ) ) {
						$feeds['post_comment_count'] = Wp_Social_Stream_Main::ssd_formatwithsuffix( $post['comments']['summary']['total_count'] );
					} else {
						$feeds['post_comment_count'] = '';
					}
					$feeds['post_plays_count'] = '';
					if ( isset( $post['likes']['summary']['total_count'] ) ) {
						$feeds['post_likes_count'] = Wp_Social_Stream_Main::ssd_formatwithsuffix( $post['likes']['summary']['total_count'] );
					} else {
						$feeds['post_likes_count'] = '';
					}
					$feeds['post_dislikes_count']       = '';
					$feeds['message_2']                 = isset( $post['story'] ) ? Wp_Social_Stream_Main::ssd_make_links( $post['story'] ) : '';
					$feeds['feed_id']                   = $this->feed_id;
					$feeds['reply_to_link']             = '';
					$feeds['retweet_link']              = '';
					$feeds['post_retweet_count']        = '';
					$feeds['favourite_link']            = '';
					$feeds['like_link']                 = $post['permalink_url'];
					$feeds['play_link']                 = '';
					$feeds['posttype']                  = '';
					$feeds['carousel_media']            = '';
					$feeds['post_language']             = '';
					$feeds['post_user_location']        = '';
					$feeds['post_user_description']     = '';
					$feeds['post_user_image']           = '';
					$feeds['post_user_followers_count'] = '';
					$feeds['post_user_friends_count']   = '';
					$feeds['feed_type']                 = 'facebook';
					$feeds['type_in']                   = 'added_video' === $post['status_type'] ? 'video' : 'image';
					$feeds['post_video']                = 'added_video' === $post['status_type'] ? $post_img_src : '';
					$feeds['subattachments'] = maybe_serialize($subattachments);
					$this->ssd_feed_limit_count         = $this->ssd_feed_limit_count + 1;
					if ( $this->ssd_feed_limit_count <= $feed_limit ) {
						$this->post_feeds[] = $feeds;
					}
				}
			}

			if ( isset( $nextpage ) && '' !== $nextpage ) {
				$this->next_page = $nextpage;
				$this->ssd_get_facebook_stream_from_feed();
			}
		} else {
			$album_id = $feed_detail['fb_album_id'];
			if ( '' == $album_id ) {
				return new WP_Error( 'ssd-feeds-errors', esc_html__( 'Please enter', 'social-stream-design' ) . esc_html( ' Album ID' ) );
			}

			$url = 'https://graph.facebook.com/v20.0/' . $album_id . '/photos?fields=likes.summary(true),comments.summary(true),shares,id,created_time,from,message,name,object_id,picture,full_picture,attachments{media,subattachments},source,link,type&access_token=' . $fb_accesstoken;
			$get_feeds      = Wp_Social_Stream_Main::ssd_get_data_from_remote_url( $url );
			$posts          = json_decode( $get_feeds, true );
			$secondnextpage = isset( $posts['paging']['next'] ) ? $posts['paging']['next'] : '';
			if ( isset( $posts['error'] ) ) {
				$message = $posts['error']['message'];
				return new WP_Error( 'ssd-feeds-errors', $message );
			}
			if ( '' !== $this->second_next_page ) {
				$url            = $this->second_next_page;
				$get_feeds      = Wp_Social_Stream_Main::ssd_get_data_from_remote_url( $url );
				$posts          = json_decode( $get_feeds, true );
				$secondnextpage = isset( $posts['paging']['next'] ) ? $posts['paging']['next'] : '';
			}
			$get_php_error = Wp_Social_Stream_Main::ssd_get_last_error();
			if ( $get_php_error ) {
				$message = $get_php_error['message'] . '!!!! ' . esc_html__( 'Please try again', 'social-stream-design' );
				return new WP_Error( 'ssd-feeds-errors', $message );
			}
			$i         = 0;
			$post_data = $posts['data'];
			if ( ! empty( $post_data ) ) {
				foreach ( $post_data as $post ) {
					$time_ar                        = explode( 'T', $post['created_time'] );
					$ids                            = explode( '_', $post['id'] );
					$feeds['unique_id']             = $ids[0];
					$feeds['post_title']            = '';
					$feeds['post_description']      = Wp_Social_Stream_Main::ssd_make_links( $post['from']['name'] );
					$feeds['post_date']             = $time_ar[0];
					$feeds['post_link']             = isset( $post['link'] ) ? $post['link'] : '';
					$feeds['post_image']            = isset( $post['full_picture'] ) ? $post['full_picture'] : $post['source'];
					$feeds['url']                   = $post['source'];
					$feeds['post_user_name']        = $post['from']['name'];
					$feeds['post_user_screen_name'] = $post['from']['name'];
					$feeds['post_user_link']        = isset( $post['link'] ) ? $post['link'] : '';
					$feeds['post_favorite_count']   = '';
					$feeds['post_plays_count']      = '';
					if ( isset( $post['comments']['summary']['total_count'] ) ) {
						$feeds['post_comment_count'] = Wp_Social_Stream_Main::ssd_formatwithsuffix( $post['comments']['summary']['total_count'] );
					} else {
						$feeds['post_comment_count'] = '';
					}
					$feeds['post_plays_count'] = '';
					if ( isset( $post['likes']['summary']['total_count'] ) ) {
						$feeds['post_likes_count'] = Wp_Social_Stream_Main::ssd_formatwithsuffix( $post['likes']['summary']['total_count'] );
					} else {
						$feeds['post_likes_count'] = '';
					}
					$feeds['post_dislikes_count']       = '';
					$feeds['message_2']                 = isset( $post['story'] ) ? Wp_Social_Stream_Main::ssd_make_links( $post['story'] ) : '';
					$feeds['feed_id']                   = $this->feed_id;
					$feeds['reply_to_link']             = '';
					$feeds['retweet_link']              = '';
					$feeds['post_retweet_count']        = '';
					$feeds['favourite_link']            = '';
					$feeds['like_link']                 = isset( $post['link'] ) ? $post['link'] : '';
					$feeds['play_link']                 = '';
					$feeds['posttype']                  = '';
					$feeds['carousel_media']            = '';
					$feeds['post_language']             = '';
					$feeds['post_user_location']        = '';
					$feeds['post_user_description']     = '';
					$feeds['post_user_image']           = '';
					$feeds['post_user_followers_count'] = '';
					$feeds['post_user_friends_count']   = '';
					$feeds['feed_type']                 = 'facebook';
					$feeds['type_in']                   = 'image';
					$feeds['post_video']                = '';
					$this->ssd_feed_limit_count         = $this->ssd_feed_limit_count + 1;
					if ( $this->ssd_feed_limit_count <= $feed_limit ) {
						$this->post_feeds[] = $feeds;
					}
				}
			}
			if ( $this->ssd_feed_limit_count <= $feed_limit ) {
				if ( isset( $secondnextpage ) && '' !== $secondnextpage ) {
					$this->second_next_page = $secondnextpage;
					$this->ssd_get_facebook_stream_from_feed();
				}
			}
		}
	}
}
