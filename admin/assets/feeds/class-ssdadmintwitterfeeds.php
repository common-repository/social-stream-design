<?php
/**
 * Twitter Social media class file get all social feed
 *
 * @version 1.0
 * @package WP Social Stream Designer
 */

/**
 * Get Twitter stream data
 *
 * @package    WP Social Stream Designer
 * @subpackage WP Social Stream Designer/admin/assets/feeds
 * @author     Solwin Infotech <support@solwininfotech.com>
 */
class SSDAdmintwitterFeeds {
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
	 * Page
	 *
	 * @var int
	 */
	public $page = 1;
	/**
	 * Post Feeds
	 *
	 * @var array
	 */
	public $post_feeds = array();
	/**
	 * Feed Limit
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
	 * Get Feed Data
	 *
	 * @since  1.0.0
	 * @return array
	 */
	public function ssd_get_twitter_stream_from_feed() {
		global $arr_return;
		$feed_detail               = $this->feed_detail;
		$feed_type_twitter         = $feed_detail['feed_type_twitter'];
		$feed_limit                = $feed_detail['feed_limit'];
		$twitter_stream            = get_option( 'twitter_stream' );
		$oauth_access_token        = $twitter_stream['access_token'];
		$oauth_access_token_secret = $twitter_stream['access_token_secret'];
		$consumer_key              = $twitter_stream['consumer_key'];
		$consumer_secret           = $twitter_stream['consumer_secret'];
		if ( $feed_limit <= 100 ) {
			$count = $feed_limit;
		} else {
			$count = 100;
		}
		if ( '' == $oauth_access_token || '' == $oauth_access_token_secret || '' == $consumer_key || '' == $consumer_secret ) {
			return new WP_Error( 'ssd-feeds-errors', esc_html__( 'Please enter', 'social-stream-design' ) . esc_html( ' Search Keyword' ) );
		}
		if ( 'tweets_by_search' === $feed_type_twitter ) {
			$url            = 'https://api.twitter.com/1.1/search/tweets.json';
			$search_keyword = $feed_detail['twitter_search_keyword'];
			$request_method = 'GET';
			$page           = $this->page;
			$getfield       = '?q=' . $search_keyword . '&result_type=recent&count=' . $count;

			if ( '' == $search_keyword ) {
				return new WP_Error( 'ssd-feeds-errors', esc_html__( 'Please enter', 'social-stream-design' ) . esc_html( ' Search Keyword' ) );
			}
			$settings = array(
				'oauth_access_token'        => $oauth_access_token,
				'oauth_access_token_secret' => $oauth_access_token_secret,
				'consumer_key'              => $consumer_key,
				'consumer_secret'           => $consumer_secret,
				'oauth_nonce'               => time(),
				'oauth_timestamp'           => time(),
				'oauth_signature_method'    => 'HMAC-SHA1',
				'oauth_version'             => '1.0',
				'count'                     => $count,
				'page'                      => $page,
			);
			$twitter  = new TwitterAPIExchangeWP( $settings );
			$json     = $twitter->setGetfield( $getfield )->buildOauth( $url, $request_method )->performRequest();
			$json     = json_decode( $json, false );
			$json     = ( isset( $json->statuses ) && '' !== $json->statuses ) ? $json->statuses : '';
		} elseif ( 'user_feed' === $feed_type_twitter || 'home_timeline' === $feed_type_twitter ) {
			$username = $feed_detail['twitter_username'];
			if ( '' == $username ) {
				return new WP_Error( 'ssd-feeds-errors', esc_html__( 'Please enter', 'social-stream-design' ) . esc_html( ' Username' ) );
			}
			if ( 'user_feed' === $feed_type_twitter ) {
				$url = 'https://api.twitter.com/1.1/statuses/user_timeline.json';
			} else {
				$url = 'https://api.twitter.com/1.1/statuses/home_timeline.json';
			}
			$request_method = 'GET';
			$page           = $this->page;
			$getfield       = '?screen_name=' . $username . '&include_rts=1&tweet_mode=extended&count=' . $count;
			$settings       = array(
				'oauth_access_token'        => $oauth_access_token,
				'oauth_access_token_secret' => $oauth_access_token_secret,
				'consumer_key'              => $consumer_key,
				'consumer_secret'           => $consumer_secret,
				'oauth_nonce'               => time(),
				'oauth_timestamp'           => time(),
				'oauth_signature_method'    => 'HMAC-SHA1',
				'oauth_version'             => '1.0',
				'count'                     => $count,
				'page'                      => $page,
			);
			$twitter        = new TwitterAPIExchangeWP( $settings );
			$json           = $twitter->setGetfield( $getfield )->buildOauth( $url, $request_method )->performRequest();
			$json           = json_decode( $json, false );

			$get_php_error = Wp_Social_Stream_Main::ssd_get_last_error();
			if ( $get_php_error ) {
				$message = $get_php_error['message'] . '!!!! ' . esc_html__( 'Please try again', 'social-stream-design' );
				return new WP_Error( 'ssd-feeds-errors', $message );
			}
		} elseif ( 'user_list' === $feed_type_twitter ) {
			$username = $feed_detail['twitter_username'];
			$listname = $feed_detail['twitter_user_listname'];

			if ( '' == $username || '' == $listname ) {
				return new WP_Error( 'ssd-feeds-errors', esc_html__( 'Please enter', 'social-stream-design' ) . esc_html( ' Username or Listname' ) );
			}

			$url            = 'https://api.twitter.com/1.1/lists/statuses.json';
			$request_method = 'GET';
			$page           = $this->page;
			$getfield       = '?screen_name=' . $username . '&include_rts=1&include_entities=true&list_id=' . $listname . '&slug=' . $username . '&count=' . $count;

			$settings      = array(
				'oauth_access_token'        => $oauth_access_token,
				'oauth_access_token_secret' => $oauth_access_token_secret,
				'consumer_key'              => $consumer_key,
				'consumer_secret'           => $consumer_secret,
				'oauth_nonce'               => time(),
				'oauth_timestamp'           => time(),
				'oauth_signature_method'    => 'HMAC-SHA1',
				'oauth_version'             => '1.0',
				'count'                     => $count,
				'page'                      => $page,
			);
			$twitter       = new TwitterAPIExchangeWP( $settings );
			$json          = $twitter->setGetfield( $getfield )->buildOauth( $url, $request_method )->performRequest();
			$json          = json_decode( $json, false );
			$get_php_error = Wp_Social_Stream_Main::ssd_get_last_error();
			if ( $get_php_error ) {
				$message = $get_php_error['message'] . '!!!! ' . esc_html__( 'Please try again', 'social-stream-design' );
				return new WP_Error( 'ssd-feeds-errors', $message );
			}
		} elseif ( 'users_like' === $feed_type_twitter ) {
			$username = $feed_detail['twitter_username'];

			if ( '' == $username ) {
				return new WP_Error( 'ssd-feeds-errors', esc_html__( 'Please enter', 'social-stream-design' ) . esc_html( ' Username' ) );
			}

			$url            = 'https://api.twitter.com/1.1/favorites/list.json';
			$request_method = 'GET';
			$page           = $this->page;
			$getfield       = '?screen_name=' . $username . '&include_rts=1&tweet_mode=extended&count=' . $count;

			$settings = array(
				'oauth_access_token'        => $oauth_access_token,
				'oauth_access_token_secret' => $oauth_access_token_secret,
				'consumer_key'              => $consumer_key,
				'consumer_secret'           => $consumer_secret,
				'oauth_nonce'               => time(),
				'oauth_timestamp'           => time(),
				'oauth_signature_method'    => 'HMAC-SHA1',
				'oauth_version'             => '1.0',
				'count'                     => $count,
				'page'                      => $page,
			);
			$twitter  = new TwitterAPIExchangeWP( $settings );
			$json     = $twitter->setGetfield( $getfield )->buildOauth( $url, $request_method )->performRequest();
			$json     = json_decode( $json, false );

			$get_php_error = Wp_Social_Stream_Main::ssd_get_last_error();
			if ( $get_php_error ) {
				$message = $get_php_error['message'] . '!!!! ' . esc_html__( 'Please try again', 'social-stream-design' );
				return new WP_Error( 'ssd-feeds-errors', $message );
			}
		}
		if ( isset( $json ) && is_array( $json ) && ! empty( $json ) ) {
			foreach ( $json as $tweet ) {
				$media_type = '';
				$media      = '';
				if ( isset( $tweet->entities->media[0] ) ) {
					$media_type = $tweet->entities->media[0]->type;
					$media      = $tweet->entities->media[0]->media_url;
				} elseif ( isset( $tweet->extended_entities->media[0] ) ) {
					$media_type = $tweet->extended_entities->media[0]->type;
					$media      = $tweet->extended_entities->media[0]->media_url;
				}
				if ( 'photo' === $media_type || 'animated_gif' === $media_type ) {
					$feeds['type_in']    = 'image';
					$feeds['post_image'] = $media;
					$feeds['post_video'] = '';
				} elseif ( 'video' === $media_type ) {
					$feeds['type_in']    = 'video';
					$feeds['post_image'] = '';
					$feeds['post_video'] = $media;
				} else {
					$feeds['type_in']    = 'image';
					$feeds['post_image'] = '';
					$feeds['post_video'] = '';
				}
				$description = '';
				if ( isset( $tweet->text ) ) {
					$description = $tweet->text;
				} elseif ( isset( $tweet->full_text ) ) {
					$description = $tweet->full_text;
				}
				$profile_img            = $tweet->user->profile_image_url;
				$large_user_profile_img = str_replace( '_normal', '', $profile_img );

				$tweet_created_date                 = strtotime( $tweet->created_at );
				$feeds['feed_id']                   = $this->feed_id;
				$feeds['unique_id']                 = $tweet->id;
				$feeds['feed_type']                 = 'twitter';
				$feeds['post_title']                = '';
				$feeds['post_description']          = $description;
				$feeds['post_date']                 = gmdate( 'Y-m-d H:i:s', $tweet_created_date );
				$feeds['post_link']                 = 'https://twitter.com/' . $tweet->user->screen_name . '/status/' . $tweet->id;
				$feeds['post_language']             = $tweet->lang;
				$feeds['post_user_name']            = $tweet->user->name;
				$feeds['post_user_screen_name']     = $tweet->user->screen_name;
				$feeds['post_user_location']        = $tweet->user->location;
				$feeds['post_user_description']     = $tweet->user->description;
				$feeds['post_user_link']            = 'https://twitter.com/' . $tweet->user->screen_name;
				$feeds['post_user_image']           = $large_user_profile_img;
				$feeds['post_user_followers_count'] = $tweet->user->followers_count;
				$feeds['post_user_friends_count']   = $tweet->user->friends_count;
				$feeds['post_retweet_count']        = $tweet->retweet_count;
				$feeds['post_favorite_count']       = $tweet->favorite_count;
				$feeds['post_comment_count']        = '0';
				$feeds['post_plays_count']          = '0';
				$feeds['post_likes_count']          = '0';
				$feeds['post_dislikes_count']       = '0';
				$feeds['reply_to_link']             = 'https://twitter.com/intent/tweet?in_reply_to=' . $tweet->id;
				$feeds['retweet_link']              = 'https://twitter.com/intent/retweet?tweet_id=' . $tweet->id;
				$feeds['favourite_link']            = 'https://twitter.com/intent/favorite?tweet_id=' . $tweet->id;
				$feeds['like_link']                 = '';
				$feeds['play_link']                 = '';
				$this->ssd_feed_limit_count         = $this->ssd_feed_limit_count + 1;
				if ( $this->ssd_feed_limit_count <= $feed_limit ) {
					$this->post_feeds[] = $feeds;
				}
			}
		}
		if ( 'tweets_by_search' !== $feed_type_twitter ) {
			if ( $this->ssd_feed_limit_count <= $feed_limit ) {
				if ( isset( $json ) && is_array( $json ) && ! empty( $json ) ) {
					$this->page = $this->page + 1;
					$this->ssd_get_twitter_stream_from_feed();
				}
			}
		}
	}
}
