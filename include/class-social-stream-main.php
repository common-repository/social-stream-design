<?php
/**
 * Class file for all actions of stream
 *
 * @version 1.0
 * @package WP Social Stream Designer
 */

/**
 * Class for all common methods of socail stream.
 */
class Wp_Social_Stream_Main {
	/**
	 * Class Construct.
	 */
	public function __construct() {
		add_action( 'admin_footer', array( $this, 'ssd_loader_for_feeds' ) );
		add_action( 'admin_notices', array( $this, 'ssd_no_feeds_available_notice' ) );
		add_action( 'wp_ajax_ssd_update_drag_drop_builder', array( $this, 'ssd_update_drag_drop_builder' ) );
	}
	/**
	 * Create link for facebook story
	 *
	 * @param string $text text to put.
	 * @param string $class class to put in a tag.
	 * @param string $target target of link.
	 * @return html
	 */
	public static function ssd_make_links( $text, $class = '', $target = '_blank' ) {
		return preg_replace( '!((http\:\/\/|ftp\:\/\/|https\:\/\/)|www\.)([-a-zA-Zа-яА-Я0-9\~\!\@\#\$\%\^\&\*\(\)_\-\=\+\\\/\?\.\:\;\'\,]*)?!ism', '<a class="' . $class . '" href="//$3" target="' . $target . '">$1$3</a>', $text );
	}
	/**
	 * Get data from table by ID
	 *
	 * @param int $id id.
	 * @return array table row
	 */
	public function ssd_get_table_row( $id ) {
		global $wpdb;
		return $wpdb->get_row( $wpdb->prepare( "SELECT * FROM $wpdb->prefix" . 'ssd_shortcodes WHERE ID = %d', $id ) );
	}
	/**
	 * Set social stream options value
	 *
	 * @return array $options
	 */
	public static function ssd_get_social_stream_options() {
		$options          = array();
		$twitter_stream   = get_option( 'twitter_stream' );
		$facebook_stream  = get_option( 'facebook_stream' );
		$instagram_stream = get_option( 'instagram_stream' );
		if ( ! empty( $twitter_stream ) ) {
			$options['twitter-stream'] = 'Twitter';
		}
		if ( ! empty( $facebook_stream ) ) {
			$options['facebook-stream'] = 'Facebook';
		}
		$options['pinterest-stream'] = 'Pinterest';
		if ( ! empty( $instagram_stream ) ) {
			$options['instagram-stream'] = 'Instagram';
		}
				$options['tiktok-stream'] = 'Tiktok';
		return $options;
	}
	/**
	 * Converts a number into a short version, eg: 1000 -> 1k
	 *
	 * @param int $number number.
	 * @return string $number number
	 */
	public static function ssd_formatwithsuffix( $number ) {
		$suffixes     = array( '', 'k', 'm', 'g', 't' );
		$suffix_index = 0;
		$suffixes_ct  = count( $suffixes );
		while ( abs( $number ) >= 1000 && $suffix_index < $suffixes_ct ) {
			$suffix_index++;
			$number /= 1000;
		}
		return ( $number > 0 ? floor( $number * 10 ) / 10 : ceil( $number * 10 ) / 10 ) . $suffixes[ $suffix_index ];
	}
	/**
	 * Set Feed Pagination
	 *
	 * @param array $social_stream_settings social data array.
	 * @param int   $total_pages_for_pagination Total pages.
	 * @param int   $cur_page Current page.
	 * @param int   $cur_page_for_pagination Current page.
	 * @param int   $pages_to_display_for_pagination pages to display for pagination.
	 * @return Pagination
	 */
	public static function ssd_get_feed_pagination( $social_stream_settings, $total_pages_for_pagination, $cur_page, $cur_page_for_pagination = 1, $pages_to_display_for_pagination = 7 ) {
		ob_start();
		$ssd_pagination_layout = '';
		$ssd_pagination_type   = ( isset( $social_stream_settings['ssd_pagination_type'] ) && '' !== $social_stream_settings['ssd_pagination_type'] ) ? $social_stream_settings['ssd_pagination_type'] : 'no_pagination';
		if ( 'load_more_btn' === $ssd_pagination_type ) {
			$ssd_pagination_layout = ( isset( $social_stream_settings['ssd_load_more_layout'] ) && '' !== $social_stream_settings['ssd_load_more_layout'] ) ? $social_stream_settings['ssd_load_more_layout'] : 'template-1';
		}
		?>
		<div class="ssd-center ssd-row-padding ssd-block ssd-padding-32">
			<div class="ssd-pgn-bar <?php echo esc_attr( $ssd_pagination_layout ); ?>">
				<?php
				$pages_to_display_for_pagination = (int) $pages_to_display_for_pagination;
				if ( $pages_to_display_for_pagination > 0 ) {
					for ( $i = 1; $i <= $pages_to_display_for_pagination; $i++ ) {
						$page_id  = $pages_to_display_for_pagination * $cur_page_for_pagination + $i;
						$ex_class = '';
						if ( $cur_page == $page_id ) {
							$ex_class .= 'ssd-page-active';
						}
						$previous_page_id = $cur_page - 1;
						$next_page_id     = $cur_page + 1;
						if ( $cur_page_for_pagination > 0 && 1 == $i ) {
							$fisrt_previous_page_id = $pages_to_display_for_pagination * ( $cur_page_for_pagination - 1 ) + 1;
							?>
							<a class="ssd-bar-item ssd-button ssd-hover-black ssd-border ssd-margin-right-small" href="<?php echo esc_url( add_query_arg( 'cur_page', $fisrt_previous_page_id ) ); ?>"><<</a>
							<?php
						}
						if ( $previous_page_id > 0 && 1 == $i ) {
							?>
							<a class="ssd-bar-item ssd-button ssd-hover-black ssd-border ssd-margin-right-small" href="<?php echo esc_url( add_query_arg( 'cur_page', $previous_page_id ) ); ?>"><</a>
							<?php
						}
						if ( $page_id <= $total_pages_for_pagination ) {
							$cur_page2 = (int) $cur_page + 1;
							if ( $page_id == $cur_page2 ) {
								$ex_class .= 'next';
							}
							?>
							<a class="ssd-bar-item ssd-button ssd-hover-black ssd-border ssd-margin-right-small <?php echo esc_attr( $ex_class ); ?>" href="<?php echo esc_url( add_query_arg( 'cur_page', $page_id ) ); ?>" ><?php echo esc_attr( $page_id ); ?></a>
							<?php
						}
						if ( $next_page_id <= $total_pages_for_pagination && $i == $pages_to_display_for_pagination ) {
							?>
							<a class="ssd-bar-item ssd-button ssd-hover-black ssd-border ssd-margin-right-small" href="<?php echo esc_url( add_query_arg( 'cur_page', $next_page_id ) ); ?>">></a>
							<?php
						}
						$first_last_page_id = $pages_to_display_for_pagination * ( $cur_page_for_pagination + 1 ) + 1;
						if ( $first_last_page_id < $total_pages_for_pagination && $i == $pages_to_display_for_pagination ) {
							?>
							<a class="ssd-bar-item ssd-button ssd-hover-black ssd-border ssd-margin-right-small"  href="<?php echo esc_url( add_query_arg( 'cur_page', $first_last_page_id ) ); ?>">>></a>
							<?php
						}
					}
				}
				?>
			</div>
		</div>
		<?php
		$pagination_html = ob_get_clean();
		return $pagination_html;
	}
	/**
	 * Display share labels in layout
	 *
	 * @param string $social_cover_class cover class.
	 * @param string $social_stream_settings cover class.
	 * @param string $feed_type cover food type.
	 * @return void
	 */
	public static function ssd_social_share_labels( $social_cover_class, $social_stream_settings, $feed_type ) {
		$ssd_social_share_type = isset( $social_stream_settings['ssd_social_share_type'] ) ? $social_stream_settings['ssd_social_share_type'] : 'text';
		$ssd_display_image     = isset( $social_stream_settings['ssd_display_image'] ) ? $social_stream_settings['ssd_display_image'] : '';
		$ssd_icon_image_layout = isset( $social_stream_settings['ssd_image_layout'] ) ? $social_stream_settings['ssd_image_layout'] : '';
		if ( 'facebook' === $feed_type ) {
			$icon = 'fab fa-facebook-f';
		} elseif ( 'twitter' === $feed_type ) {
			$icon = 'fab fa-x-twitter';
		} elseif ( 'instagram' === $feed_type ) {
			$icon = 'fab fa-instagram';
		} elseif ( 'pinterest' === $feed_type ) {
			$icon = 'fab fa-pinterest';
		} elseif ( 'tiktok' === $feed_type ) {
			$icon = 'fab fa-tiktok';
		}
		?>
		<div class="ssdso-icn <?php echo esc_attr( $social_cover_class ); ?>">
			<?php
			if ( $feed_type ) {
				?>
				<div class="ssd-share-label">
					<?php
					if ( 'icon' === $ssd_social_share_type ) {
						if ( '1' !== $ssd_display_image ) {
							if ( isset( $icon ) ) {
								?>
								<i class="<?php echo esc_attr( $icon ); ?>"></i>
								<?php
							}
						} else {
							?>
							<span class="<?php echo ' ' . esc_attr( $ssd_icon_image_layout ); ?>"></span>
							<?php
						}
					}
					if ( 'icon_text' === $ssd_social_share_type ) {
						if ( isset( $icon ) ) {
							?>
							<i class="<?php echo esc_attr( $icon ); ?>"></i>
							<?php
						}
					}
					if ( 'icon' !== $ssd_social_share_type ) {
						?>
						<span><?php echo esc_html( $feed_type ); ?></span>
						<?php
					}
					?>
				</div>
				<?php
			}
			?>
		</div>
		<?php
	}
	/**
	 * Display author data in layout
	 *
	 * @param string $social_cover_class cover class.
	 * @param array  $social_stream_settings social settings array.
	 * @param object $feeds feeds.
	 * @return void
	 */
	public static function ssd_author_data( $social_cover_class, $social_stream_settings, $feeds ) {
		$upload_file  = '';
		$display_file = '';
		$feed_type    = $feeds->feed_type;
		$user_image   = $feeds->post_user_image;
		if ( '' !== $user_image ) {
			$upload_file_path    = WP_CONTENT_DIR . '/uploads/social-stream/';
			$display_file_path   = WP_CONTENT_URL . '/uploads/social-stream/';
			$post_user_image_url = strtok( $user_image, '?' );
			$path_info           = pathinfo( $post_user_image_url );
			$file_extention      = '';
			if ( isset( $path_info['extension'] ) ) {
				$file_extention = $path_info['extension'];
				if ( '' !== $file_extention ) {
					$file_extentions = wp_parse_url( $file_extention );
					if ( is_array( $file_extentions ) ) {
						$file_extention = $file_extentions['path'];
					}
					$file_extention = '.' . $file_extention;
				} else {
					$file_extention = '.jpg';
				}
			} else {
				$file_extention = '.jpg';
			}
			$file_name    = md5( $post_user_image_url );
			$upload_file  = $upload_file_path . $file_name . '_profile' . $file_extention;
			$display_file = $display_file_path . $file_name . '_profile' . $file_extention;
		}
		$user_link        = $feeds->post_user_link;
		$user_name        = $feeds->post_user_name;
		$user_screen_name = $feeds->post_user_screen_name;
		$user_screen_name = ( isset( $user_screen_name ) && '' !== $user_screen_name ) ? '@' . $user_screen_name : '';
		$date_time        = $feeds->post_date;
		$date_time        = human_time_diff( strtotime( $date_time ), current_time( 'timestamp' ) ) . ' ago';
		if ( null == $feeds->post_date || '0000-00-00 00:00:00' == $feeds->post_date ) {
			$date_time = '';
		}

		$ssd_design_layout     = isset( $social_stream_settings['ssd_design_layout'] ) ? $social_stream_settings['ssd_design_layout'] : 'layout-1';
		$ssd_display_sticky    = isset( $social_stream_settings['ssd_display_sticky'] ) ? $social_stream_settings['ssd_display_sticky'] : '1';
		$ssd_display_sticky_on = isset( $social_stream_settings['ssd_display_sticky_on'] ) ? $social_stream_settings['ssd_display_sticky_on'] : 'media';
		$show_view_user_name   = isset( $social_stream_settings['ssd_view_user_name'] ) ? $social_stream_settings['ssd_view_user_name'] : '';
		$show_view_user_name   = (int) $show_view_user_name;
		$show_view_date        = isset( $social_stream_settings['ssd_view_date'] ) ? $social_stream_settings['ssd_view_date'] : '';
		$show_view_date        = (int) $show_view_date;
		$ssd_layout            = isset( $social_stream_settings['ssd_layout'] ) ? $social_stream_settings['ssd_layout'] : '';
		?>
		<div class="ssd-author-detail">
			<div class='ssd-author-image'>
				<?php
				if ( file_exists( $upload_file ) ) {
					?>
					<img src='<?php echo esc_url( $display_file ); ?>' alt="<?php echo esc_attr( $user_name ); ?>"/>
					<?php
				} elseif ( isset( $user_image ) && '' !== $user_image ) {
					?>
					<img src='<?php echo esc_url( $user_image ); ?>' alt="<?php echo esc_attr( $user_name ); ?>"/>
					<?php
				} else {
					?>
					<img src='<?php echo esc_attr( WPSOCIALSTREAMDESIGNER_URL ) . '/images/author.png'; ?>' alt="<?php echo esc_attr( $user_name ); ?>"/>
					<?php
				}
				?>
			</div>
			<?php
			if ( 'author' === $ssd_display_sticky_on ) {
				self::ssd_social_share_labels( $social_cover_class, $social_stream_settings, $feed_type );
			}
			?>
			<div class="ssd-author-name">
				<div class="author-name-top"><a class="ssd-display-name" target="_blank" href='<?php echo esc_url( $user_link ); ?>'><?php echo esc_attr( $user_name ); ?></a></div>
				<?php if ( 1 == $show_view_user_name || 1 == $show_view_date ) { ?>
					<div class="author-name-bottom">
						<?php
						if ( '' != $user_screen_name && '1' == $show_view_user_name ) {
							?>
							<a class="ssd-user-name" target="_blank"  href='<?php echo esc_url( $user_link ); ?>'><?php echo esc_attr( $user_screen_name ); ?></a>
							<?php
						}
						if ( '' != $date_time && '1' == $show_view_date ) {
							?>
								<div class="ssd-posted-date">
									<?php echo ' - ' . esc_attr( $date_time ); ?>
								</div>
								<?php
						}
						?>
					</div>
				<?php } ?>
			</div>
		</div>
		<?php
	}
	/**
	 * Display share counts in layout
	 *
	 * @param array  $social_stream_settings stream settings array.
	 * @param object $feeds feeds.
	 * @return void
	 */
	public static function ssd_social_share_count( $social_stream_settings, $feeds ) {
		global $wpdb;
		$show_follower_count  = isset( $social_stream_settings['ssd_user_follower_count'] ) ? $social_stream_settings['ssd_user_follower_count'] : '';
		$show_friend_count    = isset( $social_stream_settings['ssd_user_friend_count'] ) ? $social_stream_settings['ssd_user_friend_count'] : '';
		$show_retweet_count   = isset( $social_stream_settings['ssd_retweet_count'] ) ? $social_stream_settings['ssd_retweet_count'] : '';
		$show_retweet_count   = (int) $show_retweet_count;
		$show_reply_count     = isset( $social_stream_settings['ssd_reply_link'] ) ? $social_stream_settings['ssd_reply_link'] : '';
		$show_reply_count     = (int) $show_reply_count;
		$show_view_count      = isset( $social_stream_settings['ssd_view_count'] ) ? $social_stream_settings['ssd_view_count'] : '';
		$show_view_count      = (int) $show_view_count;
		$show_favorite_count  = isset( $social_stream_settings['ssd_favorite_count'] ) ? $social_stream_settings['ssd_favorite_count'] : '';
		$show_favorite_count  = (int) $show_favorite_count;
		$show_like_count      = isset( $social_stream_settings['ssd_like_count'] ) ? $social_stream_settings['ssd_like_count'] : '';
		$show_like_count      = (int) $show_like_count;
		$show_pin_count       = isset( $social_stream_settings['ssd_pin_count'] ) ? $social_stream_settings['ssd_pin_count'] : '';
		$show_pin_count       = (int) $show_pin_count;
		$show_like_link       = isset( $social_stream_settings['ssd_like_link'] ) ? $social_stream_settings['ssd_like_link'] : '';
		$show_dislike_count   = isset( $social_stream_settings['ssd_dislike_count'] ) ? $social_stream_settings['ssd_dislike_count'] : '';
		$show_dislike_count   = (int) $show_dislike_count;
		$show_comment_count   = isset( $social_stream_settings['ssd_comment_count'] ) ? $social_stream_settings['ssd_comment_count'] : '';
		$show_comment_count   = (int) $show_comment_count;
		$show_comment_link    = isset( $social_stream_settings['ssd_comment_link'] ) ? $social_stream_settings['ssd_comment_link'] : '';
		$show_share_with      = isset( $social_stream_settings['ssd_display_share_with'] ) ? $social_stream_settings['ssd_display_share_with'] : '';
		$show_share_with      = (int) $show_share_with;
		$show_share_count     = isset( $social_stream_settings['ssd_share_count'] ) ? $social_stream_settings['ssd_share_count'] : '';
		$show_share_count     = (int) $show_share_count;
		$feed_type            = $feeds->feed_type;
		$user_followers_count = '' !== $feeds->post_user_followers_count ? $feeds->post_user_followers_count : 0;
		$user_friends_count   = '' !== $feeds->post_user_friends_count ? $feeds->post_user_friends_count : 0;
		$comments_count       = '' !== $feeds->post_comment_count ? $feeds->post_comment_count : 0;
		$favorite_count       = '' !== $feeds->post_favorite_count ? $feeds->post_favorite_count : 0;
		$likes_count          = '' !== $feeds->post_likes_count ? $feeds->post_likes_count : 0;
		$dislike_count        = '' !== $feeds->post_dislikes_count ? $feeds->post_dislikes_count : 0;
		$plays_count          = '' !== $feeds->post_plays_count ? $feeds->post_plays_count : 0;
		$retweet_count        = '' !== $feeds->post_retweet_count ? $feeds->post_retweet_count : 0;
		$reply_to_link        = $feeds->reply_to_link;
		$retweet_link         = $feeds->retweet_link;
		$favourite_link       = $feeds->favourite_link;
		$like_link            = $feeds->like_link;
		$plays_link           = $feeds->play_link;
		$post_link            = $feeds->post_link;
		$post_video           = $feeds->post_video;
		$unique_id            = $feeds->unique_id;
		if ( 'facebook' === $feed_type ) {
			$result_feeds     = $wpdb->get_results( $wpdb->prepare( "select feeds_settings from $wpdb->prefix" . 'ssd_feeds where id = %d', $feeds->feed_id ) );
			$feed_setting     = maybe_unserialize( $result_feeds[0]->feeds_settings );
			$feed_type_stream = $feed_setting[ 'feed_type_' . $feed_type ];
		}

		if ( 'twitter' === $feed_type ) {
			if ( 1 == $show_retweet_count || 1 == $show_favorite_count || 1 == $show_reply_count ) {
				echo '<div class="ssd-action-row">';
			}
			if ( 1 == $show_reply_count ) {
				?>
				<a target="_blank" title="<?php esc_html_e( 'Reply', 'social-stream-design' ); ?>" href="<?php echo esc_url( $reply_to_link ); ?>">
					<i class='fas fa-reply'></i>
				</a>
				<?php
			}
			if ( 1 == $show_retweet_count ) {
				?>
				<a target="_blank" title="<?php esc_html_e( 'Retweet', 'social-stream-design' ); ?>" href="<?php echo esc_url( $retweet_link ); ?>">
					<i class='fas fa-retweet'></i>
					<?php if ( '' !== $retweet_count ) { ?>
					<span class='ssd-counts'><?php echo esc_attr( $retweet_count ); ?></span>
					<?php } ?>
				</a>
				<?php
			}
			if ( 1 == $show_favorite_count ) {
				?>
				<a target="_blank" title="<?php esc_html_e( 'Favorite', 'social-stream-design' ); ?>" href="<?php echo esc_url( $favourite_link ); ?>">
					<i class='far fa-heart'></i>
					<?php if ( '' !== $favorite_count ) { ?>
					<span class='ssd-counts'><?php echo esc_attr( $favorite_count ); ?></span>
					<?php } ?>
				</a>
				<?php
			}
			if ( 1 == $show_retweet_count || 1 == $show_favorite_count || 1 == $show_reply_count ) {
				self::ssd_display_share_with_icon( $show_share_with, $post_link );
				echo '</div>';
			}
			if ( 1 !== $show_retweet_count && 1 !== $show_favorite_count && 1 !== $show_reply_count && 1 == $show_share_with ) {
				echo '<div class="ssd-action-row">';
				self::ssd_display_share_with_icon( $show_share_with, $post_link );
				echo '</div>';
			}
		} elseif ( 'tiktok' === $feed_type ) {
			if ( 1 == $show_favorite_count || 1 == $show_comment_count || 1 == $show_share_count || 1 == $plays_count ) {
				echo '<div class="ssd-action-row">';
			}
			if ( 1 == $show_favorite_count ) {
				?>
				<a target="_blank" title="<?php esc_html_e( 'Favorite', 'social-stream-design' ); ?>" href="<?php echo esc_url( $favourite_link ); ?>">
					<i class='fas fa-heart'></i>
				<?php if ( '' !== $favorite_count ) { ?>
					<span class='ssd-counts'><?php echo esc_attr( $favorite_count ); ?></span>
					<?php } ?>
				</a>
				<?php
			}
			if ( 1 == $show_comment_count ) {
				?>
				<a target="_blank" title="<?php esc_html_e( 'Comment', 'social-stream-design' ); ?>" href="<?php echo esc_url( $reply_to_link ); ?>">
					<i class="fas fa-comment"></i>
															<?php if ( '' != $comments_count ) { ?>
					<span class='ssd-counts'><?php echo esc_attr( $comments_count ); ?></span>
					<?php } ?>
				</a>
						<?php
			}
			if ( 1 == $show_share_count ) {
				?>
				<a target="_blank" title="<?php esc_html_e( 'Share', 'social-stream-design' ); ?>" href="<?php echo esc_url( $retweet_link ); ?>">
					<i class="fas fa-share"></i>
						<?php if ( '' !== $retweet_count ) { ?>
					<span class='ssd-counts'><?php echo esc_attr( $retweet_count ); ?></span>
					<?php } ?>
				</a>
						<?php
			}
			if ( 1 == $show_view_count ) {
				?>
				<a target="_blank" title="<?php esc_html_e( 'View', 'social-stream-design' ); ?>" href="<?php echo esc_url( $retweet_link ); ?>">
					<i class="fas fa-play"></i>
					<?php if ( '' !== $plays_count ) { ?>
					<span class='ssd-counts'><?php echo esc_attr( $plays_count ); ?></span>
					<?php } ?>
				</a>
					<?php
			}

			if ( 1 == $show_favorite_count || 1 == $show_comment_count || 1 == $show_share_count ) {
				self::ssd_display_share_with_icon( $show_share_with, $post_link );
				echo '</div>';
			}
			if ( 1 !== $show_favorite_count && 1 !== $show_comment_count && 1 !== $show_share_count && 1 == $show_share_with ) {
				echo '<div class="ssd-action-row">';
				self::ssd_display_share_with_icon( $show_share_with, $post_link );
				echo '</div>';
			}
		} elseif ( 'facebook' === $feed_type ) {
			if ( ( 'all_album' === $feed_type_stream ) ) {
				echo '<div class="ssd-action-row">';
				?>
				<span class="ssd-photo-count">
				<?php
				echo esc_attr( $favorite_count ) . ' ';
				esc_html_e( 'photos', 'social-stream-design' );
				?>
				<span>
				<?php
				if ( 1 == $show_share_with ) {
					self::ssd_display_share_with_icon( $show_share_with, $post_link );
				}

				echo '</div>';
			} else {
				if ( ( 1 == $show_comment_count && '' !== $comments_count ) || ( 1 == $show_like_count && '' !== $likes_count ) ) {
					echo '<div class="ssd-action-row">';
				}

				if ( 1 == $show_comment_count && '' !== $comments_count ) {
					?>
					<a target="_blank" href="<?php echo esc_url( $post_link ); ?>" title="<?php esc_html_e( 'Comments', 'social-stream-design' ); ?>" ><i class='fas fa-comments'></i><span class='ssd-counts'><?php echo esc_attr( $comments_count ); ?></span></a>
														<?php
				}
				if ( 1 == $show_like_count && '' !== $likes_count ) {
					?>
					<a target="_blank" href="<?php echo esc_url( $like_link ); ?>" title="<?php esc_html_e( 'Likes', 'social-stream-design' ); ?>" ><i class='far fa-thumbs-up'></i><span class='ssd-counts'><?php echo esc_attr( $likes_count ); ?></span></a>
														<?php
				}
				if ( ( 1 == $show_comment_count && '' !== $comments_count ) || ( 1 == $show_like_count && '' !== $likes_count ) ) {
					self::ssd_display_share_with_icon( $show_share_with, $post_link );
					echo '</div>';
				}
				if ( 1 !== $show_comment_count && 1 !== $show_like_count && 1 == $show_share_with ) {
					echo '<div class="ssd-action-row">';
					self::ssd_display_share_with_icon( $show_share_with, $post_link );
					echo '</div>';
				}
			}
		} elseif ( 'pinterest' === $feed_type ) {
			if ( 1 == $show_pin_count ) {
				echo '<div class="ssd-action-row">';
			}
			if ( 1 == $show_pin_count && '' !== $show_pin_count ) {
				?>
				<a target="_blank" target="_blank" href="https://www.pinterest.com/pin/<?php echo esc_attr( $unique_id ); ?>" title="<?php esc_html_e( 'Pins', 'social-stream-design' ); ?>" ><i class='fas fa-thumbtack'></i><span class='ssd-counts'><?php echo esc_attr( $likes_count ); ?></span></a>
				<?php
			}
			if ( 1 == $show_pin_count ) {
				self::ssd_display_share_with_icon( $show_share_with, $post_link );
				echo '</div>';
			}
			if ( 1 !== $show_pin_count && 1 == $show_share_with ) {
				echo '<div class="ssd-action-row">';
				self::ssd_display_share_with_icon( $show_share_with, $post_link );
				echo '</div>';
			}
		} elseif ( 'instagram' === $feed_type ) {
			$like_link    = $post_link;
			$comment_link = $post_link;
			if ( 1 == $show_comment_count || 1 == $show_like_count ) {
				echo '<div class="ssd-action-row">';
			}
			if ( 1 == $show_like_count ) {
				?>
				<a target="_blank" title="<?php esc_html_e( 'Likes', 'social-stream-design' ); ?>" href="<?php echo esc_url( $like_link ); ?>">
					<i class='fas fa-heart'></i>
					<?php if ( 1 == $show_like_count && '' !== $likes_count ) { ?>
						<span class='ssd-counts'><?php echo esc_html( $likes_count ); ?></span>
						<?php
					}
					?>
				</a>
				<?php
			}
			if ( 1 == $show_comment_count ) {
				?>
				<a target="_blank" href="<?php echo esc_url( $comment_link ); ?>" title="<?php esc_html_e( 'Comments', 'social-stream-design' ); ?>" >
					<i class='fas fa-comments'></i>
					<?php if ( 1 == $show_comment_count && '' !== $comments_count ) { ?>
						<span class='ssd-counts'><?php echo esc_html( $comments_count ); ?></span>
						<?php
					}
					?>
				</a>
				<?php
			}
			if ( 1 == $show_comment_count || 1 == $show_like_count ) {
				self::ssd_display_share_with_icon( $show_share_with, $post_link );
				echo '</div>';
			}
			if ( 1 !== $show_comment_count && 1 !== $show_like_count && 1 == $show_share_with ) {
				echo '<div class="ssd-action-row">';
				self::ssd_display_share_with_icon( $show_share_with, $post_link );
				echo '</div>';
			}
		}
	}
	/**
	 * Display loader on layout
	 *
	 * @return void
	 */
	public function ssd_loader_for_feeds() {
		?>
		<div style="display:none" class="ssd-loader-wrapper">
			<div class="ssd-loader-inner">
				<p><?php esc_html_e( 'This could take up to 2-5 minutes. We are fetching the posts from your feed. Please do not close this window until it\'s complete.', 'social-stream-design' ); ?></p>
				<i class="fas fa-spinner fa-spin"></i>
			</div>
		</div>
		<?php
	}
	/**
	 * Display share urls in layout
	 *
	 * @param string $link url.
	 * @return void
	 */
	public static function ssd_share_link( $link ) {
		?>
		<div class="ssd-share-link-wrapper">
			<ul class="ssd-share-link">
				<li><a class="email" href="mailto:?&subject=dfsdf&body=<?php echo esc_url( $link ); ?>" target="blank" title="<?php echo esc_html( 'Email' ); ?>"><i class="far fa-envelope"></i></a></li>
				<li><a class="linkedin" href="https://www.linkedin.com/shareArticle?mini=true&url=<?php echo esc_url( $link ); ?>" target="blank" title="<?php echo esc_html( 'Linkedin' ); ?>"><i class="fab fa-linkedin-in"></i></a></li>
				<li><a class="pinterest" href="http://pinterest.com/pin/create/button/?url=<?php echo esc_url( $link ); ?>" target="blank" title="<?php echo esc_html( 'Pinterst' ); ?>"><i class="fab fa-pinterest-p"></i></a></li>
				<li><a class="facebook" href="http://www.facebook.com/sharer.php?u=<?php echo esc_url( $link ); ?>" target="blank" title="<?php echo esc_html( 'Facebook' ); ?>"><i class="fab fa-facebook-f"></i></a></li>
				<li><a class="twitter" href="http://twitter.com/share?url=<?php echo esc_url( $link ); ?>" target="blank" title="<?php echo esc_html( 'Twitter' ); ?>"><i class="fab fa-x-twitter"></i></a></li>
				<li><a class="tumblr" href="https://www.tumblr.com/widgets/share/tool?url=<?php echo esc_url( $link ); ?>" target="blank" title="<?php echo esc_html( 'Tumblr' ); ?>"><i class="fab fa-tumblr"></i></a></li>
			</ul>
			<span class="ssd-close-button"><i class="far fa-times-circle"></i></span>
		</div>
		<?php
	}
	/**
	 * Display media based on type
	 *
	 * @param string $type_in media type.
	 * @param string $feed_type feed type.
	 * @param string $feed_type_stream feed type.
	 * @param string $link url.
	 * @param string $image image.
	 * @param string $video video.
	 * @param string $ssd_display_default_image default image.
	 * @param string $ssd_default_image_src default image source.
	 * @param string $ssd_design_layout default layout.
	 * @param string $ssd_title title.
	 * @param string $subattachments $subattachments.
	 * @return void
	 */
	public static function ssd_media_type( $type_in, $feed_type, $feed_type_stream, $link, $image, $video, $ssd_display_default_image, $ssd_default_image_src, $ssd_design_layout, $ssd_title, $subattachments ) {
		global $wpdb;
		$ssd_display_default_image = (int) $ssd_display_default_image;
		$ssd_default_image         = '';
		$post_type_slug            = array();
		$args                      = array(
			'public'   => true,
			'_builtin' => false,
		);
		$post_types                = get_post_types( $args, 'objects' );
		if ( isset( $post_types ) ) {
			if ( is_array( $post_types ) && ! empty( $post_types ) ) {
				foreach ( $post_types as $post_type ) {
					$post_type_slug[] = $post_type->name;
				}
			}
		}
		$upload_file = '';
		if ( 1 == $ssd_display_default_image && '' !== $ssd_default_image_src ) {
			$ssd_default_image = $ssd_default_image_src;
		}
		$subattachments_data = maybe_unserialize( $subattachments );
		if( empty( $subattachments_data ) ){
			if ( 'image' === $type_in ) {
				if ( '' !== $image ) {
					$post_image_url = strtok( $image, '?' );
					$path_info      = pathinfo( $post_image_url );
					$file_name      = md5( $post_image_url );
					$file_extention = isset( $path_info['extension'] ) ? $path_info['extension'] : '';
					if ( '' !== $file_extention ) {
						$file_extentions = wp_parse_url( $file_extention );
						if ( is_array( $file_extentions ) ) {
							$file_extention = $file_extentions['path'];
						}
						$file_extention = '.' . $file_extention;
					} else {
						$file_extention = '.jpg';
					}
					$upload_file_path  = WP_CONTENT_DIR . '/uploads/social-stream/';
					$display_file_path = WP_CONTENT_URL . '/uploads/social-stream/';
					$upload_file       = $upload_file_path . $file_name . '_image' . $file_extention;
					$display_file      = $display_file_path . $file_name . '_image' . $file_extention;
				}
				if ( file_exists( $upload_file ) ) {
					?>
					<a target="_blank" href="<?php echo esc_url( $link ); ?>"><img alt="<?php echo esc_attr( wp_strip_all_tags( $ssd_title ) ); ?>" src="<?php echo esc_url( $image ); ?>"></a>
					<?php
				} elseif ( '' !== $image ) {
					?>
					<a target="_blank" href="<?php echo esc_url( $link ); ?>"><img alt="<?php echo esc_attr( wp_strip_all_tags( $ssd_title ) ); ?>" src="<?php echo esc_url( $image ); ?>"></a>
					<?php
				} elseif ( '' !== $ssd_default_image ) {
					?>
					<a target="_blank" href="<?php echo esc_url( $link ); ?>"><img src="<?php echo esc_attr( wp_strip_all_tags( $ssd_default_image ) ); ?>"></a>
					<?php
				}
			} elseif ( 'video' === $type_in ) {
				if ( '' !== $video ) {
					?>
					<div class="ssd_video_play">
						<a target="_blank" href="<?php echo esc_url( $link ); ?>">
							<svg id="ssd_video_play" xmlns="http://www.w3.org/2000/svg" width="45" height="45" viewBox="0 0 45 45">
							<g id="Group_1" data-name="Group 1" transform="translate(-701 -412)">
							<path id="Fill-124" d="M-97.3-224.8a22.5,22.5,0,0,1-22.5-22.5,22.5,22.5,0,0,1,22.5-22.5,22.5,22.5,0,0,1,22.5,22.5,22.5,22.5,0,0,1-22.5,22.5Zm0-42.552A20.13,20.13,0,0,0-117.352-247.3,20.13,20.13,0,0,0-97.3-227.248,20.13,20.13,0,0,0-77.248-247.3,20.069,20.069,0,0,0-97.3-267.352Z" transform="translate(820.8 681.8)" fill="#fff"/>
							<path id="Fill-125" d="M-103-233.6v-24.7l21.2,12.4L-103-233.6Zm2.8-19.8v14.9l12.7-7.4-12.7-7.5Z" transform="translate(819.632 680.846)" fill="#fff"/>
							</g>
							</svg>					
						</a>
					</div>
					<?php
					if ( 'tiktok' === $feed_type ) {
						?>
						<a target="_blank" href="<?php echo esc_url( $link ); ?>"><img alt="<?php echo esc_html( wp_strip_all_tags( $ssd_title ) ); ?>" src="<?php echo esc_url( $image ); ?>"></a>
						<?php
					} else {
						?>
						<video controls><source src="<?php echo esc_url( $video ); ?>"></video>
						<?php
					}
				} else {
					if ( '' !== $image ) {
						?>
						<a target="_blank" href="<?php echo esc_url( $link ); ?>"><img alt="<?php echo esc_html( wp_strip_all_tags( $ssd_title ) ); ?>" src="<?php echo esc_url( $image ); ?>"></a>
						<?php
					} elseif ( '' !== $ssd_default_image ) {
						?>
						<a target="_blank" href="<?php echo esc_url( $link ); ?>"><img alt="<?php echo esc_html( wp_strip_all_tags( $ssd_title ) ); ?>" src="<?php echo esc_url( $ssd_default_image ); ?>"></a>
						<?php
					}
				}
			} elseif ( 'carousel' === $type_in || 'carousel_album' === $type_in ) {
				$images = explode( ',', $image );
				$videos = explode( ',', $video );
				?>
				<div class="ssd-carousel-media slickslider">
					<div class="slides">
						<?php
						$images_c = count( $images );
						for ( $i = 0; $i < $images_c; $i++ ) {
							if ( '' !== $images[ $i ] ) {
								?>
								<div><a target="_blank" href="<?php echo esc_url( $link ); ?>"><img alt="<?php echo esc_attr( wp_strip_all_tags( $ssd_title ) ); ?>" src="<?php echo esc_url( $images[ $i ] ); ?>"></a></div>
								<?php
							}
						}
						$videos_c = count( $videos );
						for ( $j = 0; $j < $videos_c; $j++ ) {
							if ( '' !== $videos[ $j ] ) {
								?>
								<div>
									<video controls>
										<source src="<?php echo esc_url( $videos[ $j ] ); ?>">
									</video>
								</div>
								<?php
							}
						}
						?>
					</div>
				</div>
				<?php
			} elseif ( 'post' === $type_in || ( ! empty( $post_type_slug ) && in_array( $type_in, $post_type_slug, true ) ) ) {
				if ( '' !== $image ) {
					?>
					<a target="_blank" href="<?php echo esc_url( $link ); ?>"><img alt="<?php echo esc_html( wp_strip_all_tags( $ssd_title ) ); ?>" src="<?php echo esc_url( $image ); ?>"></a>
														<?php
				} elseif ( '' !== $ssd_default_image ) {
					?>
					<a target="_blank" href="<?php echo esc_url( $link ); ?>"><img alt="<?php echo esc_html( wp_strip_all_tags( $ssd_title ) ); ?>" src="<?php echo esc_url( $ssd_default_image ); ?>"></a>
														<?php
				}
			} elseif ( 'feed' === $type_in ) {
				if ( '' !== $ssd_default_image ) {
					?>
					<a target="_blank" href="<?php echo esc_url( $link ); ?>"><img alt="<?php echo esc_html( wp_strip_all_tags( $ssd_title ) ); ?>" src="<?php echo esc_url( $ssd_default_image ); ?>"></a>
					<?php
				}
			} else {
				if ( '' !== $ssd_default_image ) {
					?>
					<a target="_blank" href="<?php echo esc_url( $link ); ?>"><img alt="<?php echo esc_html( wp_strip_all_tags( $ssd_title ) ); ?>" src="<?php echo esc_url( $ssd_default_image ); ?>"></a>
					<?php
				}
			}
		}else{ ?>
			<div class="ssd-carousel-media slickslider">
				<div class="slides_main_wrap">
					<div class="slides">	
						<?php
							foreach($subattachments_data as $subattachments){ ?>
								<?php
								if ( 'photo' == $subattachments['type']){
									?>
									<div class="ssd-single-slide-wrap"><a target="_blank" href="<?php echo esc_url( $link ); ?>"><img alt="<?php echo esc_attr( wp_strip_all_tags( $ssd_title ) ); ?>" src="<?php echo esc_url( $subattachments['src'] ); ?>"></a></div><!--- inner -->
									<?php
								} 
								else
								{ ?>
									<div class="ssd-single-slide-wrap">
									<video controls> <source src="<?php echo esc_url( $subattachments['url'] ); ?>">
									</video>
								</div> 

								
		
								<?php
								} ?>
						<?php } ?>
					</div><!--- slides -->
				</div><!--- slides_main_wrap --->
			</div><!--- slickslider --->
		<?php }
	}
	/**
	 * Display Share post link in layout
	 *
	 * @param int    $show_share_with if share display.
	 * @param string $post_link post link.
	 * @return void
	 */
	public static function ssd_display_share_with_icon( $show_share_with, $post_link ) {
		$show_share_with = (int) $show_share_with;
		if ( 1 == $show_share_with ) {
			?>
			<span class="ssd-share-button"><i class="fas fa-share-alt"></i></span>
			<?php
			self::ssd_share_link( $post_link );
		}
	}
	/**
	 * Display no feed available message
	 *
	 * @return void
	 */
	public function ssd_no_feeds_available_notice() {
		if ( isset( $_POST['nonce'] ) ) {
			$nonce = sanitize_text_field( wp_unslash( $_POST['nonce'] ) );
			if ( ! wp_verify_nonce( $nonce, 'ssd-function' ) ) {
				wp_send_json_error( array( 'ssd_function' => 'Nonce error' ) );
				die();
			}
		}
		if ( isset( $_GET['page'] ) && ( 'social-stream-designer-layouts' === $_GET['page'] || 'social-stream-designer-add-layouts' === $_GET['page'] ) ) {
			$all_feeds   = '';
			$feed_object = new WpSSDFeedsActions();
			$feed_object->init();
			$all_feeds = $feed_object->ssd_get_all_feeds();
			if ( '' == $all_feeds ) {
				?>
				<div class="notice notice-warning">
					<p>
					<?php esc_html_e( "You haven't created any feeds yet. Please create the feed before create the layout.", 'social-stream-design' ); ?>
						<a href="<?php echo esc_url( admin_url( 'admin.php?page=social-stream-designer-social-feed' ) ); ?>" ><?php esc_html_e( 'Create New Feed', 'social-stream-design' ); ?></a>
					</p>
				</div>
				<?php
			}
		}
	}
	/**
	 * Update drag and drop builder
	 *
	 * @return void
	 */
	public function ssd_update_drag_drop_builder() {
		if ( isset( $_POST['nonce'] ) ) {
			$nonce = sanitize_text_field( wp_unslash( $_POST['nonce'] ) );
			if ( ! wp_verify_nonce( $nonce, 'ssd-function' ) ) {
				wp_send_json_error( array( 'ssd_function' => 'Nonce error' ) );
				die();
			}
		}
		$layout = ( isset( $_POST['layout'] ) && '' !== $_POST['layout'] ) ? sanitize_text_field( wp_unslash( $_POST['layout'] ) ) : 'ssd_design_layout_1';
		if ( 'ssd_design_layout_1' === $layout ) {
			$ssd_social_order_e = 'media,title,content,author,count,';
		} elseif ( 'ssd_design_layout_2' === $layout ) {
			$ssd_social_order_e = 'media,title,content,count,';
		} elseif ( 'ssd_design_layout_3' === $layout ) {
			$ssd_social_order_e = 'media,title,content,count,';
		} elseif ( 'ssd_design_layout_5' === $layout ) {
			$ssd_social_order_e = 'title,content,author,count,social-share,';
		} else {
			$ssd_social_order_e = 'count,title,content,author,social-share,';
		}

		$ssd_social_order_e   = explode( ',', $ssd_social_order_e );
		$proper_ordered_array = $ssd_social_order_e;
		$proper_ordered_array = array_filter( $proper_ordered_array );
		$proper_ordered_array = array_unique( $proper_ordered_array );
		foreach ( $proper_ordered_array as $proper_ordered_val ) {
			$ssd_social_order[] = $proper_ordered_val;
		}
		$ssd_social_order_count = count( $ssd_social_order );
		for ( $i = 0; $i < $ssd_social_order_count; $i++ ) {
			if ( 'title' === $ssd_social_order[ $i ] ) {
				?>
				<li class="ui-sortable-handle" data-order="title">
					<input type="hidden" value="title" name="ssd-drag-drop-layout[]">
					<h3><?php esc_html_e( '41 landing page optimization best practices', 'social-stream-design' ); ?></h3>
				</li>
				<?php
			}
			if ( 'content' === $ssd_social_order[ $i ] ) {
				?>
				<li class="ui-sortable-handle" data-order="content">
					<input type="hidden" value="content" name="ssd-drag-drop-layout[]">
					<p style="margin:0"><?php echo esc_html( 'At ornare ullamcorper potenti pulvinar wisi. Nibh faucibus nec duis elit eleifend accumsan libero sociis metus id feugiat. Quis interdum senectus. Luctus etiam consequat adipiscing lobortis nec. Massa wisi cras.' ); ?></p>
				</li>
				<?php
			}
			if ( 'social-share' === $ssd_social_order[ $i ] ) {
				?>
				<li class="ssd-social-share-sortable ui-sortable-handle" data-order="social-share">
					<input type="hidden" value="social-share" name="ssd-drag-drop-layout[]">
				social-sharesocial-sharesocial-sharesocial-share
					<div class="facebook-cover"><i class="fab fa-facebook-f"></i><?php esc_html_e( 'Facebook', 'social-stream-design' ); ?></div>
				</li>
				<?php
			}
			if ( 'media' === $ssd_social_order[ $i ] ) {
				?>
				<li class="ssd-media-sortable ui-sortable-handle" data-order="media"><input type="hidden" value="media" name="ssd-drag-drop-layout[]"><img src="<?php echo esc_url( WPSOCIALSTREAMDESIGNER_URL ) . '/images/layout-media.png'; ?>" alt="<?php esc_html_e( 'Sample Image', 'social-stream-design' ); ?>"></li>
				<?php
			}
			if ( 'author' === $ssd_social_order[ $i ] ) {
				?>
				<li class="ssd-author-sortable ui-sortable-handle" data-order="author">
					<input type="hidden" value="author" name="ssd-drag-drop-layout[]">
					<div class="ssd-author-detail">
						<div class='ssd-author-image'></div>
						<div class="ssd-author-name">
							<a href=''><?php esc_html_e( 'Solwin Infotech', 'social-stream-design' ); ?></a>
							<a href=''>
							<?php
							esc_html_e( 'SolwinInfotech', 'social-stream-design' );
							echo ' - ';
							esc_html_e( '3h ago', 'social-stream-design' );
							?>
							</a>
						</div>
					</div>
				</li>
				<?php
			}
			if ( 'count' === $ssd_social_order[ $i ] ) {
				?>
				<li class="ui-sortable-handle" data-order="count">
					<input type="hidden" value="count" name="ssd-drag-drop-layout[]">
					<div class="ssd-action-row">
						<a href=""><i class='fas fa-eye'></i><span class='ssd-counts'>17</span></a>
						<a href=""><i class='fas fa-comments'></i><span class='ssd-counts'>7</span></a>
						<a href=""><i class='fas fa-heart'></i><span class='ssd-counts'>9</span></a>
						<a href=""><i class='far fa-thumbs-up'></i><span class='ssd-counts'>5</span></a>
					</div>
				</li>
				<?php
			}
		}
		exit;
	}
	/**
	 * Get data from url using curl or wp_remote_get
	 *
	 * @param string $url url.
	 * @return json $get_insta_user_feed feed data.
	 */
	public static function ssd_get_data_from_remote_url( $url ) {
		$get_insta_user_feed = '';
		if ( '' !== $url ) {
				$args     = array(
					'timeout' => 120,
				);
				$response = wp_remote_get( $url, $args );
				if ( is_wp_error( $response ) ) {
						return false;
				}
				$get_insta_user_feed = wp_remote_retrieve_body( $response );
		}
		return $get_insta_user_feed;
	}
	/**
	 * Get image from url and store in local
	 *
	 * @param string $upload_file upload file url.
	 * @param string $post_image_url image url.
	 */
	public static function ssd_put_media_file_in_local( $upload_file, $post_image_url ) {
		global $wp_filesystem;
		/* get image data from url. */
		$get_insta_user_feed = '';
		if ( '' !== $post_image_url ) {
				$args     = array(
					'timeout' => 120,
				);
				$response = wp_remote_get( $post_image_url, $args );
				if ( is_wp_error( $response ) ) {
						return false;
				}
				$get_insta_user_feed = wp_remote_retrieve_body( $response );
		}
		$image_data = $get_insta_user_feed;
		if ( empty( $wp_filesystem ) ) {
			require_once ABSPATH . '/wp-admin/includes/file.php';
			WP_Filesystem();
		}
		/* Save image on local system. */
		$url   = wp_nonce_url( admin_url( 'admin.php?page=social-stream-designer-social-feed' ), 'social-stream-design' );
		$creds = request_filesystem_credentials( $url, '', true, false, null );
		if ( false == $creds ) {
			esc_html_e( 'Credentials are required to save the file.', 'social-stream-design' );
			exit();
		}
		if ( ! WP_Filesystem( $creds ) ) {
			request_filesystem_credentials( $url, '', true, false, null );
		}
		$wp_filesystem->put_contents( $upload_file, $image_data, FS_CHMOD_FILE );
	}
	/**
	 * Check plugin last error
	 *
	 * @return array $error
	 */
	public static function ssd_get_last_error() {
		$error = wp_get_plugin_error( 'social-stream-design/social-stream-design.php' );
		return $error;
	}
}
new Wp_Social_Stream_Main();
