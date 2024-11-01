<?php
/**
 * Instagram Social media class file get all social feed
 *
 * @version 1.0
 * @package WP Social Stream Designer
 */

/**
 * Get Instagram stream data
 *
 * @package    WP Social Stream Designer
 * @subpackage WP Social Stream Designer/admin/assets/feeds
 * @author     Solwin Infotech <support@solwininfotech.com>
 */
class SSDAdmininstagramFeeds {
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
	 * Next URL
	 *
	 * @var string
	 */
	public $next_url = '';
	/**
	 * Post Feeds
	 *
	 * @var array
	 */
	public $post_feeds = array();
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
	 * Get feed Detail
	 *
	 * @since  1.0.0
	 * @return string
	 */
	public function ssd_get_instagram_stream_from_feed() {
		$feed_detail         = $this->feed_detail;
		$feed_type_instagram = $feed_detail['feed_type_instagram'];
		$feed_limit          = $feed_detail['feed_limit'];
		$instagram_stream    = get_option( 'instagram_stream' );
		$access_token        = $instagram_stream['access_token'];
		if ( '' == $access_token ) {
			return new WP_Error( 'ssd-feeds-errors', esc_html__( 'Authentication Missing. Please Check Authentication Setting', 'social-stream-design' ) );
		}
		$location_id     = isset( $feed_detail['insta_location_id'] ) ? $feed_detail['insta_location_id'] : '';
		$tag             = isset( $feed_detail['insta_tag_keyword'] ) ? $feed_detail['insta_tag_keyword'] : '';
		$username        = isset( $feed_detail['insta_username'] ) ? $feed_detail['insta_username'] : '';
		$posts           = ( isset( $posts ) && '' !== $posts ) ? $posts : array();
		$instagram_feeds = array();
		if ( $feed_limit <= 100 ) {
			$count = $feed_limit;
		} else {
			$count = 100;
		}
		$url = 'https://graph.instagram.com/me/media?fields=id,caption,media_type,children{media_url,thumbnail_url,media_type},media_url,permalink,thumbnail_url,timestamp,username&limit=' . $count . '&access_token=' . $access_token;

		if ( '' !== $this->next_url ) {
			$url = $this->next_url;
		}
		$get_insta_user_feed = Wp_Social_Stream_Main::ssd_get_data_from_remote_url( $url );

		$get_php_error = Wp_Social_Stream_Main::ssd_get_last_error();
		if ( $get_php_error ) {
			$message = $get_php_error['message'] . '!!!! ' . esc_html__( 'Please try again', 'social-stream-design' );
			return new WP_Error( 'ssd-feeds-errors', $message );
		}
		$get_insta_user_feed = json_decode( $get_insta_user_feed );

		if ( isset( $get_insta_user_feed->error->message ) ) {
			$message = $get_insta_user_feed->error->message . '!!!! ' . esc_html__( 'Please try again', 'social-stream-design' );
			return new WP_Error( 'ssd-feeds-errors', $message );
		}
		$posts = '';
		if ( isset( $get_insta_user_feed->data ) ) {
			$posts = $get_insta_user_feed->data;
		}
		if ( is_array( $posts ) && ! empty( $posts ) ) {
			foreach ( $posts as $post ) {
				$timestamp = strtotime($post->timestamp);
				$publsh_date = date('Y-m-d H:i:s', $timestamp);
				$post_description = ( isset( $post->caption ) && null !== $post->caption ) ? $post->caption : '';
				$post_user_name   = $post->username;
				if ( ! empty( $post_description ) && 'hashtag' === $feed_type_instagram && ! empty( $tag ) ) {
					if ( strpos( $post_description, '#' . $tag ) ) {
						$media_type = $post->media_type;
						if ( 'IMAGE' === $media_type ) {
							$feeds['post_image'] = $post->media_url;
							$feeds['post_video'] = '';
						}
						if ( 'VIDEO' === $media_type ) {
							$feeds['post_image'] = '';
							$feeds['post_video'] = $post->media_url;
						}
						if ( 'CAROUSEL_ALBUM' === $media_type ) {
							$carousel_media_image = array();
							$carousel_media_video = array();
							$feeds_carousel_media = $post->children->data;
							if ( ! empty( $feeds_carousel_media ) ) {
								foreach ( $feeds_carousel_media as $feed_carousel_media ) {
									$carousel_media_type = $feed_carousel_media->media_type;
									if ( 'IMAGE' === $carousel_media_type ) {
										$carousel_media_image[] = $feed_carousel_media->media_url;
									}
									if ( 'VIDEO' === $carousel_media_type ) {
										$carousel_media_video[] = $feed_carousel_media->media_url;
									}
								}
								$carousel_media_image = implode( ',', $carousel_media_image );
								$feeds['post_image']  = $carousel_media_image;
								$carousel_media_video = implode( ',', $carousel_media_video );
								$feeds['post_video']  = $carousel_media_video;
							}
						}
						$feeds['feed_id']          = $this->feed_id;
						$feeds['unique_id']        = $post->id;
						$feeds['post_comment_id']  = $post->id;
						$feeds['feed_type']        = 'instagram';
						$feeds['post_title']       = '';
						$feeds['post_description'] = ( isset( $post->caption ) && null !== $post->caption ) ? $post->caption : '';
						$feeds['url']              = $post->media_url;
						if ( 'CAROUSEL_ALBUM' === $media_type ) {
							$feeds['url'] = $carousel_media_image->media_url;
						}
						$feeds['post_user_name']            = $post->username;
						$feeds['post_link']                 = $post->permalink;
						$feeds['post_date']                 = $publsh_date ;
						$feeds['post_comment_count']        = '';
						$feeds['post_likes_count']          = '';
						$feeds['post_plays_count']          = '0';
						$feeds['post_dislikes_count']       = '0';
						$feeds['like_link']                 = '';
						$feeds['play_link']                 = '';
						$feeds['post_language']             = '';
						$feeds['post_user_screen_name']     = $post->username;
						$feeds['post_user_location']        = '';
						$feeds['post_user_description']     = '';
						$feeds['post_user_link']            = 'https://www.instagram.com/' . $post->username;
						$feeds['post_user_image']           = '';
						$feeds['post_user_followers_count'] = '';
						$feeds['post_user_friends_count']   = '';
						$feeds['post_retweet_count']        = '';
						$feeds['post_favorite_count']       = '';
						$feeds['reply_to_link']             = '';
						$feeds['retweet_link']              = '';
						$feeds['favourite_link']            = '';
						$feeds['type_in']                   = strtolower( $media_type );
						$this->ssd_feed_limit_count         = $this->ssd_feed_limit_count + 1;
						if ( $this->ssd_feed_limit_count <= $feed_limit ) {
							$this->post_feeds[] = $feeds;
						}
					}
				} elseif ( 'user_feed' === $feed_type_instagram && ! empty( $username ) ) {
					if ( $post_user_name !== $username && strpos( $post_description, '@' . $username ) ) {
						$media_type = $post->media_type;
						if ( 'IMAGE' === $media_type ) {
							$feeds['post_image'] = $post->media_url;
							$feeds['post_video'] = '';
						}
						if ( 'VIDEO' === $media_type ) {
							$feeds['post_image'] = '';
							$feeds['post_video'] = $post->media_url;
						}
						if ( 'CAROUSEL_ALBUM' === $media_type ) {
							$carousel_media_image = array();
							$carousel_media_video = array();
							$feeds_carousel_media = $post->children->data;

							if ( ! empty( $feeds_carousel_media ) ) {
								foreach ( $feeds_carousel_media as $feed_carousel_media ) {
									$carousel_media_type = $feed_carousel_media->media_type;
									if ( 'IMAGE' === $carousel_media_type ) {
										$carousel_media_image[] = $feed_carousel_media->media_url;
									}
									if ( 'VIDEO' === $carousel_media_type ) {
										$carousel_media_video[] = $feed_carousel_media->media_url;
									}
								}
								$carousel_media_image = implode( ',', $carousel_media_image );
								$feeds['post_image']  = $carousel_media_image;
								$carousel_media_video = implode( ',', $carousel_media_video );
								$feeds['post_video']  = $carousel_media_video;
							}
						}
						$feeds['feed_id']          = $this->feed_id;
						$feeds['unique_id']        = $post->id;
						$feeds['post_comment_id']  = $post->id;
						$feeds['feed_type']        = 'instagram';
						$feeds['post_title']       = '';
						$feeds['post_description'] = ( isset( $post->caption ) && null !== $post->caption ) ? $post->caption : '';
						$feeds['url']              = $post->media_url;
						if ( 'CAROUSEL_ALBUM' === $media_type ) {
							$feeds['url'] = $carousel_media_image->media_url;
						}
						$feeds['post_user_name']            = $post->username;
						$feeds['post_link']                 = $post->permalink;
						$feeds['post_date']                 = $publsh_date;
						$feeds['post_comment_count']        = '';
						$feeds['post_likes_count']          = '';
						$feeds['post_plays_count']          = '0';
						$feeds['post_dislikes_count']       = '0';
						$feeds['like_link']                 = '';
						$feeds['play_link']                 = '';
						$feeds['post_language']             = '';
						$feeds['post_user_screen_name']     = $post->username;
						$feeds['post_user_location']        = '';
						$feeds['post_user_description']     = '';
						$feeds['post_user_link']            = 'https://www.instagram.com/' . $post->username;
						$feeds['post_user_image']           = '';
						$feeds['post_user_followers_count'] = '';
						$feeds['post_user_friends_count']   = '';
						$feeds['post_retweet_count']        = '';
						$feeds['post_favorite_count']       = '';
						$feeds['reply_to_link']             = '';
						$feeds['retweet_link']              = '';
						$feeds['favourite_link']            = '';
						$feeds['type_in']                   = strtolower( $media_type );
						$this->ssd_feed_limit_count         = $this->ssd_feed_limit_count + 1;
						if ( $this->ssd_feed_limit_count <= $feed_limit ) {
							$this->post_feeds[] = $feeds;
						}
					} else {
						$media_type = $post->media_type;
						if ( 'IMAGE' === $media_type ) {
							$feeds['post_image'] = $post->media_url;
							$feeds['post_video'] = '';
						}
						if ( 'VIDEO' === $media_type ) {
							$feeds['post_image'] = '';
							$feeds['post_video'] = $post->media_url;
						}
						if ( 'CAROUSEL_ALBUM' === $media_type ) {
							$carousel_media_image = array();
							$carousel_media_video = array();
							$feeds_carousel_media = $post->children->data;
							if ( ! empty( $feeds_carousel_media ) ) {
								foreach ( $feeds_carousel_media as $feed_carousel_media ) {
									$carousel_media_type = $feed_carousel_media->media_type;
									if ( 'IMAGE' === $carousel_media_type ) {
										$carousel_media_image[] = $feed_carousel_media->media_url;
									}
									if ( 'VIDEO' === $carousel_media_type ) {
										$carousel_media_video[] = $feed_carousel_media->media_url;
									}
								}
								$carousel_media_image = implode( ',', $carousel_media_image );
								$feeds['post_image']  = $carousel_media_image;
								$carousel_media_video = implode( ',', $carousel_media_video );
								$feeds['post_video']  = $carousel_media_video;
							}
						}
						$feeds['feed_id']          = $this->feed_id;
						$feeds['unique_id']        = $post->id;
						$feeds['post_comment_id']  = $post->id;
						$feeds['feed_type']        = 'instagram';
						$feeds['post_title']       = '';
						$feeds['post_description'] = ( isset( $post->caption ) && null !== $post->caption ) ? $post->caption : '';
						$feeds['url']              = $post->media_url;
						if ('CAROUSEL_ALBUM' === $media_type && is_object($carousel_media_image) && property_exists($carousel_media_image, 'media_url')) {
							$feeds['url'] = $carousel_media_image->media_url;
						}
						$feeds['post_user_name']            = $post->username;
						$feeds['post_link']                 = $post->permalink;
						$feeds['post_date']                 = $publsh_date ;
						$feeds['post_comment_count']        = '';
						$feeds['post_likes_count']          = '';
						$feeds['post_plays_count']          = '0';
						$feeds['post_dislikes_count']       = '0';
						$feeds['like_link']                 = '';
						$feeds['play_link']                 = '';
						$feeds['post_language']             = '';
						$feeds['post_user_screen_name']     = $post->username;
						$feeds['post_user_location']        = '';
						$feeds['post_user_description']     = '';
						$feeds['post_user_link']            = 'https://www.instagram.com/' . $post->username;
						$feeds['post_user_image']           = '';
						$feeds['post_user_followers_count'] = '';
						$feeds['post_user_friends_count']   = '';
						$feeds['post_retweet_count']        = '';
						$feeds['post_favorite_count']       = '';
						$feeds['reply_to_link']             = '';
						$feeds['retweet_link']              = '';
						$feeds['favourite_link']            = '';
						$feeds['type_in']                   = strtolower( $media_type );
						$this->ssd_feed_limit_count         = $this->ssd_feed_limit_count + 1;
						if ( $this->ssd_feed_limit_count <= $feed_limit ) {
							$this->post_feeds[] = $feeds;
						}
					}
				}
			}
			$pagination = $get_insta_user_feed->paging;
			if ( $this->ssd_feed_limit_count <= $feed_limit ) {
				$next_url = ( isset( $pagination->cursors->after ) && '' !== $pagination->cursors->after ) ? $pagination->cursors->after : '';
				if ( '' !== $next_url ) {
					$this->next_url = $next_url;
					$this->ssd_get_instagram_stream_from_feed();
				}
			}
		}
	}
}
