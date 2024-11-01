<?php
/**
 * File to Stream Layout file
 *
 * @version 1.0
 * @package WP Social Stream Designer
 */

global $wpdb;
$feed_type_stream     = '';
$ssd_link             = $feeds->post_link;
$post_status          = $feeds->post_status;
$unique_id            = $feeds->unique_id;
$image                = isset( $feeds->post_image ) ? $feeds->post_image : '';
$video                = isset( $feeds->post_video ) ? $feeds->post_video : '';
$url                  = $feeds->post_link;
$ssd_title            = $feeds->post_title;
$message              = $feeds->post_description;
$message              = rtrim( $message );
$message              = ltrim( $message );
$feed_type            = $feeds->feed_type;
$type_in              = $feeds->type_in;
$user_image           = $feeds->post_user_image;
$user_link            = $feeds->post_user_link;
$user_name            = $feeds->post_user_name;
$user_screen_name     = $feeds->post_user_screen_name;
$user_screen_name     = ( isset( $user_screen_name ) && '' !== $user_screen_name ) ? '@' . $user_screen_name : '';
$user_followers_count = '' !== $feeds->post_user_followers_count ? $feeds->post_user_followers_count : 0;
$user_friends_count   = '' !== $feeds->post_user_friends_count ? $feeds->post_user_friends_count : 0;
$comments_count       = '' !== $feeds->post_comment_count ? $feeds->post_comment_count : 0;
$favorite_count       = '' !== $feeds->post_favorite_count ? $feeds->post_favorite_count : 0;
$likes_count          = '' !== $feeds->post_likes_count ? $feeds->post_likes_count : 0;
$dislike_count        = '' !== $feeds->post_dislikes_count ? $feeds->post_dislikes_count : 0;
$plays_count          = '' !== $feeds->post_plays_count ? $feeds->post_plays_count : 0;
$retweet_count        = '' !== $feeds->post_retweet_count ? $feeds->post_retweet_count : 0;
$retweet_link         = $feeds->retweet_link;
$favourite_link       = $feeds->favourite_link;
$like_link            = $feeds->like_link;
$play_link            = $feeds->play_link;
$reply_to_link        = $feeds->reply_to_link;
$favourite_link       = $feeds->favourite_link;
$like_link            = $feeds->like_link;
$plays_link           = $feeds->play_link;
$date_time            = $feeds->post_date;
$subattachments			= $feeds->post_subattachments;
$date_time            = human_time_diff( strtotime( $date_time ), current_time( 'timestamp' ) ) . ' ago';
if ( null == $feeds->post_date || '0000-00-00 00:00:00' == $feeds->post_date ) {
	$date_time = '';
}
$show_view_date                 = isset( $social_stream_settings['ssd_view_date'] ) ? $social_stream_settings['ssd_view_date'] : '';
$ssd_content_limit              = isset( $social_stream_settings['ssd_content_limit'] ) ? $social_stream_settings['ssd_content_limit'] : '';
$ssd_design_layout              = isset( $social_stream_settings['ssd_design_layout'] ) ? $social_stream_settings['ssd_design_layout'] : 'layout-1';
$ssd_extra_class                = isset( $social_stream_settings['ssd_card_extra_class'] ) ? $social_stream_settings['ssd_card_extra_class'] : '';
$ssd_social_share_type          = isset( $social_stream_settings['ssd_social_share_type'] ) ? $social_stream_settings['ssd_social_share_type'] : 'text';
$ssd_display_image              = isset( $social_stream_settings['ssd_display_image'] ) ? $social_stream_settings['ssd_display_image'] : '';
$ssd_display_corner_icon        = isset( $social_stream_settings['ssd_display_corner_icon'] ) ? $social_stream_settings['ssd_display_corner_icon'] : '0';
$icon_position                  = isset( $social_stream_settings['ssd_icon_position'] ) ? $social_stream_settings['ssd_icon_position'] : 'top';
$icon_alignment                 = isset( $social_stream_settings['ssd_icon_alignment'] ) ? $social_stream_settings['ssd_icon_alignment'] : 'left';
$ssd_display_sticky             = isset( $social_stream_settings['ssd_display_sticky'] ) ? $social_stream_settings['ssd_display_sticky'] : '1';
$ssd_display_sticky_on          = isset( $social_stream_settings['ssd_display_sticky_on'] ) ? $social_stream_settings['ssd_display_sticky_on'] : 'media';
$ssd_display_content            = isset( $social_stream_settings['ssd_display_content'] ) ? $social_stream_settings['ssd_display_content'] : '1';
$ssd_display_title              = isset( $social_stream_settings['ssd_display_title'] ) ? $social_stream_settings['ssd_display_title'] : '1';
$ssd_display_title_link         = isset( $social_stream_settings['ssd_display_title_link'] ) ? $social_stream_settings['ssd_display_title_link'] : '1';
$ssd_display_social_icon        = isset( $social_stream_settings['ssd_display_social_icon'] ) ? $social_stream_settings['ssd_display_social_icon'] : '1';
$view_share_with                = isset( $social_stream_settings['ssd_display_share_with'] ) ? $social_stream_settings['ssd_display_share_with'] : '';
$ssd_layout                     = isset( $social_stream_settings['ssd_layout'] ) ? $social_stream_settings['ssd_layout'] : '';
$ssd_display_author_box         = isset( $social_stream_settings['ssd_display_author_box'] ) ? $social_stream_settings['ssd_display_author_box'] : '0';
$ssd_display_feed_without_media = isset( $social_stream_settings['ssd_display_feed_without_media'] ) ? $social_stream_settings['ssd_display_feed_without_media'] : 1;
$ssd_display_default_image      = isset( $social_stream_settings['ssd_display_default_image'] ) && '' !== $social_stream_settings['ssd_display_default_image'] ? $social_stream_settings['ssd_display_default_image'] : 0;
$ssd_default_image_src          = isset( $social_stream_settings['ssd_default_image_src'] ) ? $social_stream_settings['ssd_default_image_src'] : '';
$ssd_display_corner_icon        = (int) $ssd_display_corner_icon;
$ssd_display_social_icon        = (int) $ssd_display_social_icon;
$ssd_display_sticky             = (int) $ssd_display_sticky;
$ssd_display_author_box         = (int) $ssd_display_author_box;
$ssd_display_content            = (int) $ssd_display_content;
$ssd_display_title_link         = (int) $ssd_display_title_link;
$ssd_display_title              = (int) $ssd_display_title;
$show_view_date                 = (int) $show_view_date;
$ssd_display_image              = (int) $ssd_display_image;
$result_feeds                   = $wpdb->get_results( $wpdb->prepare( "select feeds_settings from $wpdb->prefix" . 'ssd_feeds where id = %d', $feeds->feed_id ) );
$feed_setting                   = maybe_unserialize( $result_feeds[0]->feeds_settings );
if ( 'pinterest' !== $feed_type && 'instagram' !== $feed_type ) {
	$feed_type_stream = $feed_setting[ 'feed_type_' . $feed_type ];
}
$ssd_social_order = get_option( 'ssd_social_order_' . $shortcode_id );
if ( '' == $ssd_social_order ) {
	$ssd_social_order = 'share-label,media,title,content,author,count,';
}
$ssd_social_order = explode( ',', $ssd_social_order );
// array_pop( $ssd_social_order );
$column_type                    = $social_stream_settings['ssd_column_type'];
$ssd_column_type_laptop         = isset( $social_stream_settings['ssd_column_type_laptop'] ) ? $social_stream_settings['ssd_column_type_laptop'] : 3;
$ssd_column_type_rotated_tablet = isset( $social_stream_settings['ssd_column_type_rotated_tablet'] ) ? $social_stream_settings['ssd_column_type_rotated_tablet'] : 3;
$ssd_column_type_tablet         = isset( $social_stream_settings['ssd_column_type_tablet'] ) ? $social_stream_settings['ssd_column_type_tablet'] : 2;
$ssd_column_type_rotated_mobile = isset( $social_stream_settings['ssd_column_type_rotated_mobile'] ) ? $social_stream_settings['ssd_column_type_rotated_mobile'] : 1;
$ssd_column_type_mobile         = isset( $social_stream_settings['ssd_column_type_mobile'] ) ? $social_stream_settings['ssd_column_type_mobile'] : 1;
$ssd_display_meta               = isset( $social_stream_settings['ssd_display_meta'] ) ? $social_stream_settings['ssd_display_meta'] : 1;
$ssd_display_meta               = (int) $ssd_display_meta;
$display_image_class            = '';
$display_on_media_class         = '';
$display_corner_icon_class      = '';
$display_on_author_class        = '';

if ( 'icon' === $ssd_social_share_type && 1 == $ssd_display_image ) {
	$display_image_class = 'ssddimg';
}
if ( 'icon' === $ssd_social_share_type && 1 == $ssd_display_corner_icon && 1 !== $ssd_display_image ) {
	$display_corner_icon_class = 'ssd_display_corner_icon';
}
if ( 1 == $ssd_display_sticky ) {
	if ( 'media' === $ssd_display_sticky_on ) {
		$display_on_media_class = 'display_on_media';
	}
	if ( 'author' === $ssd_display_sticky_on ) {
		$display_on_author_class = 'display_on_author';
	}
}
$ssd_icon_position_alignment = ' ssd_' . $icon_position . ' ssd_' . $icon_alignment;
$social_cover_class          = 'ssd_' . $ssd_social_share_type . ' ' . $feed_type . ' ' . $display_image_class . ' ' . $display_corner_icon_class . ' ' . $display_on_media_class . ' ' . $display_on_author_class;

if ( '' !== $image || '' !== $video || ( 1 == $ssd_display_sticky && 'media' === $ssd_display_sticky_on ) || '' != $ssd_default_image_src ) {
	$card_class = 'ssd_not_card_media';
	if ( '' !== $image || '' !== $video || '' !== $ssd_default_image_src ) {
		$card_class = 'ssd_has_card_media';
	}
} else {
	$card_class = 'ssd_not_card_media';
}
if ( 0 == $ssd_display_sticky && 'icon_text' === $ssd_social_share_type && 'right' === $icon_alignment ) {
	$ssd_icon_position_alignment = '';
}
?>
<div class="ssd-col-item ssd-container animate ssd-col filter-<?php echo esc_attr( $feed_type ); ?> <?php
echo esc_attr( 'l' . $column_type . ' lp' . $ssd_column_type_laptop . ' rt' . $ssd_column_type_rotated_tablet . ' t' . $ssd_column_type_tablet . ' rm' . $ssd_column_type_rotated_mobile . ' m' . $ssd_column_type_mobile . ' ' );
?>
	<?php echo esc_attr( $ssd_design_layout . ' ' . $ssd_extra_class . ' ssd-' . $ssd_social_share_type ); ?>" >
	<div class="ssd-card <?php echo esc_attr( $feed_type . ' ' . $ssd_icon_position_alignment . ' ' . $card_class . ' ' . $display_corner_icon_class ); ?>">
		<?php
		if ( 'icon' === $ssd_social_share_type && 'top' === $icon_position && 1 == $ssd_display_corner_icon && 1 == $ssd_display_sticky && 1 == $ssd_display_social_icon ) {
			Wp_Social_Stream_Main::ssd_social_share_labels( $social_cover_class, $social_stream_settings, $feed_type );
		}
		if ( 'icon' === $ssd_social_share_type && 'top' === $icon_position && 0 == $ssd_display_corner_icon && 0 == $ssd_display_sticky && 1 == $ssd_display_social_icon ) {
			Wp_Social_Stream_Main::ssd_social_share_labels( $social_cover_class, $social_stream_settings, $feed_type );
		}

			$ssd_social_order_c = count( $ssd_social_order );
		for ( $i = 0; $i < $ssd_social_order_c; $i++ ) {
			if ( 'title' === $ssd_social_order[ $i ] ) {
				if ( 1 == $ssd_display_title && '' !== $ssd_title ) {
					?>
						<div class="ssd-post-title">  
						<?php
						if ( 1 == $ssd_display_title_link ) {
							?>
								<a target="_blank" href="<?php echo esc_url( $ssd_link ); ?>"><?php } ?>
								<h4><?php echo esc_html( wp_strip_all_tags( $ssd_title ) ); ?></h4>
								<?php
								if ( 1 == $ssd_display_title_link ) {
									?>
								</a><?php } ?>
						</div>
						<?php
				}
			}
			if ( 'content' === $ssd_social_order[ $i ] ) {
				if ( '' != $message && 1 == $ssd_display_content ) {
					$ssd_content = '';
					if ( '' == $ssd_content_limit ) {
						$ssd_content = $message;
					} elseif ( $ssd_content_limit > 0 ) {
						$ssd_content = wp_trim_words( $message, $ssd_content_limit, '...' );
					}
					?>
						<div class="ssd-panel ssd-content-wrap">
						<?php
						if ( 'foursquare' !== $feed_type ) {
							?>
								<?php if ( ! empty( $ssd_content ) ) { ?>
										<p><?php echo esc_html( $ssd_content ); ?></p>
									<?php } ?>
							<?php
						} else {
							?>
									<p><a href="<?php echo esc_url( $ssd_link ); ?>" target="_blank" ><?php echo esc_html( $ssd_content ); ?></a></p>
								<?php
						}
						?>
						</div>
						<?php
				}
			}
			if ( 'social-share' === $ssd_social_order[ $i ] ) {
				if ( 1 == $ssd_display_social_icon && 'icon' !== $ssd_social_share_type && 1 !== $ssd_display_corner_icon && 1 !== $ssd_display_sticky ) {
					Wp_Social_Stream_Main::ssd_social_share_labels( $social_cover_class, $social_stream_settings, $feed_type );
				}
			}
			if ( 'media' === $ssd_social_order[ $i ] ) {
				if ( '' != $image || '' !== $video || ( 1 == $ssd_display_sticky && 'media' === $ssd_display_sticky_on ) || '' !== $ssd_default_image_src ) {
					if ( '' !== $image || '' !== $video || '' !== $ssd_default_image_src ) {
						$media_class = 'ssd_has_media';
					} else {
						$media_class = 'ssd_no_media';
					}
					?>
							<div class="ssd-post-media <?php echo esc_attr( $media_class ); ?>">
						<?php
						Wp_Social_Stream_Main::ssd_media_type( $type_in, $feed_type, $feed_type_stream, $ssd_link, $image, $video, $ssd_display_default_image, $ssd_default_image_src, $ssd_design_layout, $ssd_title, $subattachments );
						if ( 1 == $ssd_display_sticky && 'media' === $ssd_display_sticky_on && 1 == $ssd_display_social_icon ) {
							Wp_Social_Stream_Main::ssd_social_share_labels( $social_cover_class, $social_stream_settings, $feed_type );
						}
						?>
							</div>
						<?php
				}
				if ( ( 'layout-2' === $ssd_design_layout && 1 == $ssd_display_sticky ) ) {
					if ( 1 == $ssd_display_author_box ) {
						Wp_Social_Stream_Main::ssd_author_data( $social_cover_class, $social_stream_settings, $feeds );
					}
				}
			}
			if ( isset( $ssd_social_order[ $i ] ) && 'author' === $ssd_social_order[ $i ] && ( 'layout-1' === $ssd_design_layout ) ) {
				if ( 1 == $ssd_display_author_box ) {
					Wp_Social_Stream_Main::ssd_author_data( $social_cover_class, $social_stream_settings, $feeds );
				}
			}
			if ( isset( $ssd_social_order[ $i ] ) && 'count' === $ssd_social_order[ $i ] && 1 == $ssd_display_meta ) {
				Wp_Social_Stream_Main::ssd_social_share_count( $social_stream_settings, $feeds );
			}
		}
		if ( 'icon' === $ssd_social_share_type && 1 == $ssd_display_social_icon && 1 == $ssd_display_corner_icon && 'top' !== $icon_position ) {
			Wp_Social_Stream_Main::ssd_social_share_labels( $social_cover_class, $social_stream_settings, $feed_type );
		}
		if ( 'icon' === $ssd_social_share_type && 'top' !== $icon_position && 1 == $ssd_display_corner_icon && 0 == $ssd_display_sticky && 1 == $ssd_display_social_icon ) {
			Wp_Social_Stream_Main::ssd_social_share_labels( $social_cover_class, $social_stream_settings, $feed_type );
		}
		if ( 'icon' === $ssd_social_share_type && 'top' !== $icon_position && 0 == $ssd_display_corner_icon && 0 == $ssd_display_sticky && 1 == $ssd_display_social_icon ) {
			Wp_Social_Stream_Main::ssd_social_share_labels( $social_cover_class, $social_stream_settings, $feed_type );
		}
		?>
			</div>
</div>
