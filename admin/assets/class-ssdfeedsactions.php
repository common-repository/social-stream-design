<?php
/**
 * Class for all feed actions
 *
 * @version 1.0
 * @package WP Social Stream Designer
 */

/**
 * Feed Action Class.
 *
 * @package    WP Social Stream Designer
 * @subpackage WP Social Stream Designer/admin/assets
 * @author     Solwin Infotech <support@solwininfotech.com>
 */
class WpSSDFeedsActions {
	/**
	 * Feed ID
	 *
	 * @var string
	 */
	public $feed_id = 0;
	/**
	 * Feed Type
	 *
	 * @var string
	 */
	public $feed_type = '';
		/**
		 * Feed Type
		 *
		 * @var string
		 */
	public $moderate_feeds = '';
	/**
	 * Feed Table name
	 *
	 * @var string
	 */
	public $feeds_table = '';
	/**
	 * Feed posts yabel
	 *
	 * @var string
	 */
	public $feed_posts_table = '';
	/**
	 * Initialize Setup
	 *
	 * @since  1.0.0
	 * @return void
	 */
	public function init() {
		global $wpdb;
		$feed_table             = $wpdb->prefix . 'ssd_feeds';
		$feed_posts_table       = $wpdb->prefix . 'ssd_feed_posts';
		$this->feeds_table      = $feed_table;
		$this->feed_posts_table = $feed_posts_table;
		$this->feed_type        = '';
		if ( isset( $_POST['feed_stream_nonce'] ) ) {
			if ( isset( $_POST['feed_stream_nonce'] ) && wp_verify_nonce( sanitize_text_field( wp_unslash( $_POST['feed_stream_nonce'] ) ), 'social-stream-designer_meta_box_nonce' ) ) {
				if ( isset( $_REQUEST['action'] ) && 'edit' === $_REQUEST['action'] && isset( $_REQUEST['id'] ) && '' != $_REQUEST['id'] ) {
					$this->feed_id = sanitize_text_field( wp_unslash( $_REQUEST['id'] ) );
				}
			}
		} else {
			$this->feed_id = 0;
		}
		$this->ssd_get_feed_detail();
	}

	/**
	 * Update moderate status of feeds
	 *
	 * @since  1.0.0
	 */
	public function ssd_update_moderate_status() {
		global $wpdb;
											$feed_id        = $this->feed_id;
											$moderate_feeds = $this->moderate_feeds;
		if ( '1' == $moderate_feeds ) {
			$moderate_feeds = 'yes';
		}
											$feed_posts_table = $this->feed_posts_table;
											$wpdb->update(
												$feed_posts_table,
												array(
													'moderate' => $moderate_feeds,
												),
												array(
													'feed_id' => $feed_id,
												),
												array(
													'%s',
												)
											);
	}
	/**
	 * Replace feed posts.
	 *
	 * @since  1.0.0
	 * @return string
	 */
	public function ssd_replace_feed_posts() {
		global $wpdb;

		$feeds_added = $this->ssd_add_feed_posts();
		return $feeds_added;
	}
	/**
	 * Delete feed posts by feed id.
	 *
	 * @since  1.0.0
	 * @return void
	 */
	public function ssd_delete_feed_posts() {
		global $wpdb;
		$feed_id          = $this->feed_id;
		$feed_posts_table = $this->feed_posts_table;
		$wpdb->delete( $feed_posts_table, array( 'feed_id' => $feed_id ), array( '%d' ) );
	}
	/**
	 * Add feed posts by feed id.
	 *
	 * @since  1.0.0
	 * @return boolean
	 */
	public function ssd_add_feed_posts() {
		global $wpdb;
		$feed_id                = $this->feed_id;
		$feed_posts_table       = $this->feed_posts_table;
		$feed_type              = $this->feed_type;
				$moderate_feeds = $this->moderate_feeds;
		if ( '' !== $feed_type ) {
			$feed_type = $this->ssd_get_feed_typename_from_feed_type();
			require_once WPSOCIALSTREAMDESIGNER_DIR . 'admin/assets/feeds/class-ssdadmin' . $feed_type . 'feeds.php';
			$classname = 'SSDAdmin' . $feed_type . 'Feeds';

			if ( class_exists( $classname ) ) {
				$feed_posts_obj          = new $classname();
				$feed_posts_obj->feed_id = $feed_id;
				$feed_posts_obj->init();
			}

			$arr_return   = array();
			$add_function = 'ssd_get_' . $feed_type . '_stream_from_feed';
			if ( isset( $feed_posts_obj ) ) {
				$feeds = $feed_posts_obj->$add_function();
				if ( is_wp_error( $feeds ) ) {
					$_POST['error_ssd'] = $feeds;
				}
				$feeds = $feed_posts_obj->post_feeds;
			}
			if ( isset( $feeds ) && ! empty( $feeds ) ) {
				$this->ssd_delete_feed_posts();
				$upload_file_path = WP_CONTENT_DIR . '/uploads/social-stream/';
				$i                = 0;
				foreach ( $feeds as $feed ) {
					$post_user_image_url = $feed['post_user_image'];
					$post_user_image_url = strtok( $post_user_image_url, '?' );
					if ( '' !== $post_user_image_url ) {
						$path_info       = pathinfo( $post_user_image_url );
						$file_extentions = '';
						if ( isset( $path_info['extension'] ) ) {
							$file_extentions = $path_info['extension'];
						}
						if ( '' !== $file_extentions ) {
							$file_extentions = wp_parse_url( $file_extentions );
							if ( is_array( $file_extentions ) ) {
								$file_extention = $file_extentions['path'];
							}
							$file_extention = '.' . $file_extention;
						} else {
							$file_extention = '.jpg';
						}
						$file_name   = md5( $post_user_image_url );
						$upload_file = $upload_file_path . $file_name . '_profile' . $file_extention;
					}
					if ( 'image' === $feed['type_in'] ) {
						$post_image_url = $feed['post_image'];
						$post_image_url = strtok( $post_image_url, '?' );
						if ( '' !== $post_image_url ) {
							$path_info = pathinfo( $post_image_url );
							if ( array_key_exists( 'extension', $path_info ) ) {
								$file_extention = $path_info['extension'];
							}
							if ( '' !== $file_extention ) {
								$file_extentions = wp_parse_url( $file_extention );
								if ( is_array( $file_extentions ) ) {
									$file_extention = $file_extentions['path'];
								}
								$file_extention = '.' . $file_extention;
							} else {
								$file_extention = '.jpg';
							}
							$file_name   = md5( $post_image_url );
							$upload_file = $upload_file_path . $file_name . '_image' . $file_extention;
						}
					}
					if ( 'video' === $feed['type_in'] ) {
						$post_video_url = $feed['post_video'];
						$post_video_url = strtok( $post_video_url, '?' );
						if ( '' !== $post_video_url ) {
							$path_info = pathinfo( $post_video_url );
							if ( isset( $path_info['extension'] ) ) {
								$file_extention = $path_info['extension'];
								if ( '' !== $file_extention ) {
									$file_extentions = wp_parse_url( $file_extention );
									if ( is_array( $file_extentions ) ) {
										$file_extention = $file_extentions['path'];
									}
									$file_extention = '.' . $file_extention;
								}
								$file_name   = md5( $post_video_url );
								$upload_file = $upload_file_path . $file_name . '_image' . $file_extention;
							}
						}
					}
					$post_subattachments = isset( $feed['subattachments'] ) ? $feed['subattachments'] : '';
					$insert = $wpdb->insert(
						$feed_posts_table,
						array(
							'feed_id'                   => sanitize_text_field( $feed['feed_id'] ),
							'unique_id'                 => sanitize_text_field( $feed['unique_id'] ),
							'feed_type'                 => sanitize_text_field( $feed['feed_type'] ),
							'post_title'                => sanitize_text_field( $feed['post_title'] ),
							'post_description'          => sanitize_textarea_field( $feed['post_description'] ),
							'post_date'                 => isset( $feed['post_date'] ) ? sanitize_text_field( $feed['post_date'] ) : '',
							'post_link'                 => esc_url_raw( $feed['post_link'] ),
							'type_in'                   => sanitize_text_field( $feed['type_in'] ),
							'post_image'                => $feed['post_image'],
							'post_video'                => $feed['post_video'],
							'post_subattachments'		=> $post_subattachments,
							'post_retweet_count'        => intval( $feed['post_retweet_count'] ),
							'post_comment_count'        => intval( $feed['post_comment_count'] ),
							'post_favorite_count'       => intval( $feed['post_favorite_count'] ),
							'post_plays_count'          => intval( $feed['post_plays_count'] ),
							'post_likes_count'          => intval( $feed['post_likes_count'] ),
							'post_dislikes_count'       => intval( $feed['post_dislikes_count'] ),
							'post_language'             => sanitize_text_field( $feed['post_language'] ),
							'post_user_name'            => sanitize_text_field( $feed['post_user_name'] ),
							'post_user_screen_name'     => sanitize_text_field( $feed['post_user_screen_name'] ),
							'post_user_description'     => sanitize_text_field( $feed['post_user_description'] ),
							'post_user_link'            => esc_url_raw( $feed['post_user_link'] ),
							'post_user_image'           => sanitize_text_field( $feed['post_user_image'] ),
							'post_user_followers_count' => intval( $feed['post_user_followers_count'] ),
							'post_user_friends_count'   => intval( $feed['post_user_friends_count'] ),
							'reply_to_link'             => esc_url_raw( $feed['reply_to_link'] ),
							'retweet_link'              => esc_url_raw( $feed['retweet_link'] ),
							'favourite_link'            => esc_url_raw( $feed['favourite_link'] ),
							'like_link'                 => esc_url_raw( $feed['like_link'] ),
							'play_link'                 => esc_url_raw( $feed['play_link'] ),
							'moderate'                  => $moderate_feeds,
						),
						array(
							'%d',
							'%s',
							'%s',
							'%s',
							'%s',
							'%s',
							'%s',
							'%s',
							'%s',
							'%s',
							'%s',
							'%d',
							'%d',
							'%d',
							'%d',
							'%d',
							'%d',
							'%s',
							'%s',
							'%s',
							'%s',
							'%s',
							'%s',
							'%d',
							'%d',
							'%s',
							'%s',
							'%s',
							'%s',
							'%s',
							'%s',
						)	
					);
					if ( $insert ) {
						$i++;
					}
				}
				$_POST['feed_add_count'] = $i;
			}
		}
		return true;
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
		$result_feeds = $wpdb->get_row( $wpdb->prepare( "select feeds_settings from {$wpdb->prefix}ssd_feeds where id = %d", $feed_id ) );
		if ( $result_feeds ) {
			$feeds_settings       = $result_feeds->feeds_settings;
			$feeds_settings       = maybe_unserialize( $feeds_settings );
			$this->feed_type      = $feeds_settings['feed'];
			$this->moderate_feeds = isset( $feeds_settings['moderate_feeds'] ) ? '1' : '';
			return $feeds_settings;
		} else {
			return '';
		}
	}
	/**
	 * Get all feeds.
	 *
	 * @since  1.0.0
	 * @return array
	 */
	public function ssd_get_all_feeds() {
		global $wpdb;
		$feed_table   = $this->feeds_table;
		$result_feeds = $wpdb->get_results( "SELECT * FROM {$wpdb->prefix}ssd_feeds" );
		if ( $result_feeds ) {
			return $result_feeds;
		} else {
			return '';
		}
	}
	/**
	 * Add feed.
	 *
	 * @since  1.0.0
	 * @return void
	 */
	public function ssd_add_feed() {
		global $wpdb;
		if ( isset( $_POST['feed_stream_nonce'] ) && wp_verify_nonce( sanitize_text_field( wp_unslash( $_POST['feed_stream_nonce'] ) ), 'social-stream-designer_meta_box_nonce' ) ) {
			if ( isset( $_POST['feed_stream'] ) && is_array( $_POST['feed_stream'] ) && ! empty( $_POST['feed_stream'] ) ) {
				$feeds_settings = array_map( 'sanitize_text_field', wp_unslash( $_POST['feed_stream'] ) );
			}
		}
		$feeds_settings = maybe_serialize( $feeds_settings );
		if ( isset( $_POST['ssd_edit_action'] ) && 'edit' === $_POST['ssd_edit_action'] && isset( $_POST['ssd_edit_id'] ) ) {
			$feed_table     = $this->feeds_table;
			$result_feeds   = $wpdb->get_results( $wpdb->prepare( "select feeds_settings from {$wpdb->prefix}ssd_feeds where id = %d", sanitize_text_field( wp_unslash( $_POST['ssd_edit_id'] ) ) ) );
			$old_feed_array = maybe_unserialize( $result_feeds[0]->feeds_settings );
			$new_feed_array = array_map( 'sanitize_text_field', wp_unslash( $_POST['feed_stream'] ) );
			$old_moderate   = '';
			$new_moderate   = '';
			/* old array get from database */
			foreach ( $old_feed_array as $key => $value ) {
				if ( 'moderate_feeds' == $key ) {
						$old_moderate = $value;
				} else {
					$first_value[] = $value;
				}
			}
			/* Updated feeds array */
			foreach ( $new_feed_array as $key => $value ) {
				if ( 'moderate_feeds' == $key ) {
					$new_moderate = $value;
				} else {
					$s_value[] = $value;
				}
			}
			/* get difference of both array */
			$result_diff_feed = array_diff_assoc( $first_value, $s_value );

			$this->feed_id = sanitize_text_field( wp_unslash( $_POST['ssd_edit_id'] ) );
			if ( count( $result_diff_feed ) > 0 ) {
				$wpdb->update(
					$this->feeds_table,
					array(
						'feeds_settings'    => $feeds_settings,
						'refresh_feed_date' => gmdate( 'Y-m-d H:i:s' ),
					),
					array( 'id' => $this->feed_id ),
					array(
						'%s',
						'%s',
					),
					array( '%d' )
				);
			} else {
				$wpdb->update(
					$this->feeds_table,
					array(
						'feeds_settings' => $feeds_settings,
					),
					array( 'id' => $this->feed_id ),
					array(
						'%s',
					),
					array( '%d' )
				);
			}
		} else {
			$wpdb->insert(
				$this->feeds_table,
				array(
					'feeds_settings'    => $feeds_settings,
					'refresh_feed_date' => gmdate( 'Y-m-d H:i:s' ),
				),
				array(
					'%s',
					'%s',
				)
			);
			$this->feed_id = $wpdb->insert_id;
		}
		if ( isset( $_POST['feed_stream']['feed'] ) ) {
			$this->feed_type = sanitize_text_field( wp_unslash( $_POST['feed_stream']['feed'] ) );
		} else {
			$this->feed_type = '';
		}
		if ( isset( $_POST['feed_stream']['moderate_feeds'] ) && '1' == $_POST['feed_stream']['moderate_feeds'] ) {
			$this->moderate_feeds = '1';
		} else {
			$this->moderate_feeds = '';
		}
		$old_moderate = '';
		$new_moderate = '';
		if ( $old_moderate != $new_moderate ) {
			$this->ssd_update_moderate_status();
		}
		if ( $this->feed_id > 0 ) {
			if ( isset( $_POST['ssd_edit_action'] ) && 'edit' === $_POST['ssd_edit_action'] ) {
				if ( count( $result_diff_feed ) > 0 ) {
					$feeds_added = $this->ssd_replace_feed_posts();
				} else {
					$feeds_added = true;
				}
			} else {
				$feeds_added = $this->ssd_replace_feed_posts();
			}
			if ( $feeds_added ) {
				$update = 'added';
				if ( isset( $_POST['ssd_edit_action'] ) && 'edit' === $_POST['ssd_edit_action'] ) {
					$update = 'updated';
				}

				if ( isset( $_POST['error_ssd'] ) && is_array( $_POST['error_ssd'] ) ) {
					$errors_string  = '';
					$errors_feeds_a = array_map( 'sanitize_text_field', wp_unslash( $_POST['error_ssd'] ) );
					foreach ( $errors_feeds_a as $errors_feeds ) {
						if ( null !== $errors_feeds && isset( $errors_feeds['ssd-feeds-errors'][0] ) ) {
							$errors_string .= $errors_feeds['ssd-feeds-errors'][0] . ',';
						}
					}
					$ssderror  = rtrim( $errors_string, ',' );
					$newstring = base64_encode( $ssderror );
					wp_safe_redirect( admin_url( "admin.php?page=social-stream-designer-social-feed&action=edit&id=$this->feed_id&ssd_error=" . $newstring . "&update=$update" ) );
					exit();
				} else {
					if ( isset( $_POST['feed_add_count'] ) ) {
						$count = sanitize_text_field( wp_unslash( $_POST['feed_add_count'] ) );
					} else {
						$count = '';
					}
					wp_safe_redirect( admin_url( "admin.php?page=social-stream-designer-social-feed&action=edit&id=$this->feed_id&add_feed=" . $count . "&update=$update&error=yes" ) );
				}
				exit();
			} else {
				wp_safe_redirect( admin_url( "admin.php?page=social-stream-designer-social-feed&action=edit&id=$this->feed_id&update=false" ) );
				exit();
			}
		}
	}
	/**
	 * Get feed type from feed type in database.
	 *
	 * @since  1.0.0
	 * @return array
	 */
	public function ssd_get_feed_typename_from_feed_type() {
		$feed_type     = $this->feed_type;
		$feed_type_txt = explode( '-', $feed_type );
		return $feed_type_txt[0];
	}
}
