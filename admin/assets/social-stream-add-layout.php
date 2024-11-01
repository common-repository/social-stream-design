<?php
/**
 * File for Add new layout
 *
 * @version 1.0
 * @package WP Social Stream Designer
 */

$page_title  = esc_html__( 'Add New Layout', 'social-stream-design' );
$edit_action = '';
$edit_id     = '';

$ssd_title                      = '';
$ssd_feed_ids                   = '';
$ssd_design_layout              = 'layout-1';
$ssd_page                       = 0;
$ssd_order_by                   = 'rand';
$ssd_display_order              = 0;
$ssd_display_private_stream     = 0;
$ssd_hide_desktop_stream        = 0;
$ssd_hide_mobile_stream         = 0;
$ssd_display_meta               = 1;
$ssd_display_search             = 0;
$ssd_layout                     = 'listing';
$ssd_grid_style                 = 'masonry';
$ssd_justified_grid_height      = '200';
$ssd_column_type                = 3;
$ssd_column_type_laptop         = 3;
$ssd_column_type_rotated_tablet = 3;
$ssd_column_type_tablet         = 2;
$ssd_column_type_rotated_mobile = 1;
$ssd_column_type_mobile         = 1;
$ssd_heading_alignment          = 'center';
$ssd_column_type_vertical       = 3;
$ssd_row_type                   = 1;
$ssd_display_share_with         = 0;
$ssd_theme_color                = '#f93d66';
$ssd_overlay_bg_color           = '';
$ssd_subheading_color           = '';
$ssd_heading_color              = '';
$ssd_design                     = 'style-1';
$ssd_display_feed_without_media = 1;
$ssd_display_default_image      = 0;
$ssd_pagination_type            = 'no_pagination';
$ssd_pagination_layout          = 'template-1';
$ssd_load_more_layout           = 'template-1';
$ssd_load_more_effect           = 'eff-fadein';
$ssd_no_of_posts_per_page       = 9;
$ssd_no_of_posts                = 50;
$ssd_view_user_name             = 1;
$ssd_date                       = 1;
$ssd_view_count                 = 1;
$ssd_user_follower_count        = 1;
$ssd_user_friend_count          = 1;
$ssd_retweet_count              = 1;
$ssd_favorite_count             = 1;
$ssd_like_count                 = 1;
$ssd_pin_count                  = 1;
$ssd_dislike_count              = 1;
$ssd_reply_link                 = 1;
$ssd_comment_count              = 1;
$ssd_share_count                = 1;
$ssd_social_share_type          = 'text';
$ssd_display_image              = 0;
$ssd_image_layout               = 'ssd_image_layout_1';
$ssd_display_corner_icon        = 0;
$ssd_display_sticky_on          = 'media';
$ssd_display_sticky             = 1;
$ssd_icon_alignment             = 'left';
$ssd_icon_position              = 'top';
$ssd_icon_color                 = '';
$ssd_icon_bg_color              = '';
$ssd_icon_border_radius_type    = 'px';
$ssd_text_border_radius_type    = 'px';
$ssd_display_title              = 1;
$ssd_display_title_link         = 1;
$ssd_title_color                = '#333333';
$ssd_title_hover_color          = '#f93d66';
$ssd_content_color              = '#333333';
// $ssd_content_hover_color        = '#f93d66';
$ssd_author_border_radius_type = 'px';
$ssd_author_title_color        = '#f93d66';
$ssd_author_bg_color           = '';
$ssd_author_title_hover_color  = '#333333';
$ssd_author_meta_color         = '#666666';
$ssd_display_content           = 1;
$ssd_count_meta_color          = '#666666';
$ssd_count_padding_top         = '10';
$ssd_count_padding_bottom      = '0';
$ssd_count_margin_top          = '0';
$ssd_count_margin_bottom       = '10';
$ssd_count_bg_color            = '';
$ssd_count_meta_hover_color    = '#f93d66';
$ssd_count_border_top_type     = 'solid';
$ssd_count_border_top_color    = '#cccccc';
$ssd_count_border_bottom_type  = 'solid';
$ssd_count_border_bottom_color = '';
$ssd_count_border_right_type   = 'solid';
$ssd_count_border_right_color  = '';
$ssd_count_border_left_type    = 'solid';
$ssd_count_border_left_color   = '';
$ssd_extra_class               = '';
$ssd_display_author_box        = '1';
$ssd_display_social_icon       = '1';
$ssd_content_limit             = '';
$ssd_display_title_post_break  = 'break-all';
$ssd_display_title_post_size   = '1';


// Initialize feed object.
$feed_object = new WpSSDFeedsActions();
$feed_object->init();
$all_feeds          = $feed_object->ssd_get_all_feeds();
$ssd_layout_actions = new WpSSDLayoutsActions();
$ssd_layout_actions->init();
if ( isset( $_POST['nonce'] ) ) {
	$nonce = sanitize_text_field( wp_unslash( $_POST['nonce'] ) );
	if ( ! wp_verify_nonce( $nonce, 'ssd-layout-nonce' ) ) {
		wp_send_json_error( array( 'ssd_layout_action' => 'Nonce error' ) );
		die();
	}
}
if ( isset( $_REQUEST['action'] ) && 'edit' === $_REQUEST['action'] ) {
	$edit_action                    = 'edit';
	$edit_id                        = isset( $_REQUEST['id'] ) ? intval( $_REQUEST['id'] ) : '';
	$ssd_layout_actions->id         = $edit_id;
	$page_title                     = esc_html__( 'Edit Layout', 'social-stream-design' );
	$social_settings                = ( isset( $ssd_layout_actions ) && ! empty( $ssd_layout_actions ) ) ? $ssd_layout_actions->ssd_get_layout_detail() : '';
	$ssd_title                      = ( isset( $social_settings->shortcode_name ) && '' !== $social_settings->shortcode_name ) ? $social_settings->shortcode_name : '';
	$ssd_settings                   = ( isset( $social_settings->social_stream_settings ) ) ? maybe_unserialize( $social_settings->social_stream_settings ) : '';
	$ssd_feed_ids                   = ( isset( $ssd_settings['feed_ids'] ) && '' !== $ssd_settings['feed_ids'] ) ? $ssd_settings['feed_ids'] : '';
	$ssd_feed_ids                   = ( '' !== $ssd_feed_ids ) ? array_values( $ssd_feed_ids ) : '';
	$ssd_order_by                   = isset( $ssd_settings['ssd_order_by'] ) ? $ssd_settings['ssd_order_by'] : $ssd_order_by;
	$ssd_overlay_bg_color           = isset( $ssd_settings['ssd_overlay_bg_color'] ) ? $ssd_settings['ssd_overlay_bg_color'] : $ssd_overlay_bg_color;
	$ssd_heading_color              = isset( $ssd_settings['ssd_heading_color'] ) ? $ssd_settings['ssd_heading_color'] : $ssd_heading_color;
	$ssd_subheading_color           = isset( $ssd_settings['ssd_subheading_color'] ) ? $ssd_settings['ssd_subheading_color'] : $ssd_subheading_color;
	$ssd_theme_color                = isset( $ssd_settings['ssd_theme_color'] ) ? $ssd_settings['ssd_theme_color'] : $ssd_theme_color;
	$ssd_page                       = isset( $ssd_settings['ssd_stream_page'] ) ? $ssd_settings['ssd_stream_page'] : $ssd_page;
	$ssd_layout                     = isset( $ssd_settings['ssd_layout'] ) ? $ssd_settings['ssd_layout'] : $ssd_layout;
	$ssd_grid_style                 = isset( $ssd_settings['ssd_grid_style'] ) ? $ssd_settings['ssd_grid_style'] : $ssd_grid_style;
	$ssd_justified_grid_height      = isset( $ssd_settings['ssd_justified_grid_height'] ) ? $ssd_settings['ssd_justified_grid_height'] : $ssd_justified_grid_height;
	$ssd_column_type                = isset( $ssd_settings['ssd_column_type'] ) ? $ssd_settings['ssd_column_type'] : $ssd_column_type;
	$ssd_column_type_laptop         = isset( $ssd_settings['ssd_column_type_laptop'] ) ? $ssd_settings['ssd_column_type_laptop'] : $ssd_column_type_laptop;
	$ssd_column_type_rotated_tablet = isset( $ssd_settings['ssd_column_type_rotated_tablet'] ) ? $ssd_settings['ssd_column_type_rotated_tablet'] : $ssd_column_type_rotated_tablet;
	$ssd_column_type_tablet         = isset( $ssd_settings['ssd_column_type_tablet'] ) ? $ssd_settings['ssd_column_type_tablet'] : $ssd_column_type_tablet;
	$ssd_column_type_rotated_mobile = isset( $ssd_settings['ssd_column_type_rotated_mobile'] ) ? $ssd_settings['ssd_column_type_rotated_mobile'] : $ssd_column_type_rotated_mobile;
	$ssd_column_type_mobile         = isset( $ssd_settings['ssd_column_type_mobile'] ) ? $ssd_settings['ssd_column_type_mobile'] : $ssd_column_type_mobile;
	$ssd_column_type_vertical       = isset( $ssd_settings['ssd_column_type_vertical'] ) ? $ssd_settings['ssd_column_type_vertical'] : $ssd_column_type_vertical;
	$ssd_row_type                   = isset( $ssd_settings['ssd_row_type'] ) ? $ssd_settings['ssd_row_type'] : $ssd_row_type;
	$ssd_heading_alignment          = isset( $ssd_settings['ssd_heading_alignment'] ) ? $ssd_settings['ssd_heading_alignment'] : $ssd_heading_alignment;
	$ssd_display_share_with         = isset( $ssd_settings['ssd_display_share_with'] ) ? $ssd_settings['ssd_display_share_with'] : $ssd_display_share_with;
	$ssd_display_share_with         = (int) $ssd_display_share_with;
	$ssd_display_default_image      = isset( $ssd_settings['ssd_display_default_image'] ) ? $ssd_settings['ssd_display_default_image'] : $ssd_display_default_image;
	$ssd_display_default_image      = (int) $ssd_display_default_image;
	$ssd_display_feed_without_media = isset( $ssd_settings['ssd_display_feed_without_media'] ) ? $ssd_settings['ssd_display_feed_without_media'] : $ssd_display_feed_without_media;
	$ssd_display_feed_without_media = (int) $ssd_display_feed_without_media;
	$ssd_display_order              = isset( $ssd_settings['ssd_display_order'] ) ? $ssd_settings['ssd_display_order'] : $ssd_display_order;
	$ssd_display_order              = (int) $ssd_display_order;
	$ssd_display_private_stream     = isset( $ssd_settings['ssd_display_private_stream'] ) ? $ssd_settings['ssd_display_private_stream'] : $ssd_display_private_stream;
	$ssd_display_private_stream     = (int) $ssd_display_private_stream;
	$ssd_hide_desktop_stream        = isset( $ssd_settings['ssd_hide_desktop_stream'] ) ? $ssd_settings['ssd_hide_desktop_stream'] : 0;
	$ssd_hide_desktop_stream        = (int) $ssd_hide_desktop_stream;
	$ssd_hide_mobile_stream         = isset( $ssd_settings['ssd_hide_mobile_stream'] ) ? $ssd_settings['ssd_hide_mobile_stream'] : 1;
	$ssd_hide_mobile_stream         = (int) $ssd_hide_mobile_stream;
	$ssd_display_meta               = isset( $ssd_settings['ssd_display_meta'] ) ? $ssd_settings['ssd_display_meta'] : $ssd_display_meta;
	$ssd_display_meta               = (int) $ssd_display_meta;
	$ssd_design                     = isset( $ssd_settings['design'] ) ? $ssd_settings['design'] : $ssd_design;
	$ssd_pagination_type            = isset( $ssd_settings['ssd_pagination_type'] ) ? $ssd_settings['ssd_pagination_type'] : $ssd_pagination_type;
	$ssd_pagination_layout          = isset( $ssd_settings['ssd_pagination_layout'] ) ? $ssd_settings['ssd_pagination_layout'] : $ssd_pagination_layout;
	$ssd_load_more_layout           = isset( $ssd_settings['ssd_load_more_layout'] ) ? $ssd_settings['ssd_load_more_layout'] : $ssd_load_more_layout;
	$ssd_load_more_effect           = isset( $ssd_settings['ssd_load_more_effect'] ) ? $ssd_settings['ssd_load_more_effect'] : $ssd_load_more_effect;
	$ssd_no_of_posts_per_page       = isset( $ssd_settings['ssd_no_of_posts_per_page'] ) ? $ssd_settings['ssd_no_of_posts_per_page'] : $ssd_no_of_posts_per_page;
	$ssd_no_of_posts                = isset( $ssd_settings['ssd_no_of_posts'] ) ? $ssd_settings['ssd_no_of_posts'] : $ssd_no_of_posts;
	$ssd_view_user_name             = isset( $ssd_settings['ssd_view_user_name'] ) ? $ssd_settings['ssd_view_user_name'] : $ssd_view_user_name;
	$ssd_view_user_name             = (int) $ssd_view_user_name;
	$ssd_date                       = isset( $ssd_settings['ssd_view_date'] ) ? $ssd_settings['ssd_view_date'] : $ssd_date;
	$ssd_date                       = (int) $ssd_date;
	$ssd_view_count                 = isset( $ssd_settings['ssd_view_count'] ) ? $ssd_settings['ssd_view_count'] : $ssd_view_count;
	$ssd_view_count                 = (int) $ssd_view_count;
	$ssd_user_follower_count        = isset( $ssd_settings['ssd_user_follower_count'] ) ? $ssd_settings['ssd_user_follower_count'] : $ssd_user_follower_count;
	$ssd_user_follower_count        = (int) $ssd_user_follower_count;
	$ssd_user_friend_count          = isset( $ssd_settings['ssd_user_friend_count'] ) ? $ssd_settings['ssd_user_friend_count'] : $ssd_user_friend_count;
	$ssd_user_friend_count          = (int) $ssd_user_friend_count;
	$ssd_retweet_count              = isset( $ssd_settings['ssd_retweet_count'] ) ? $ssd_settings['ssd_retweet_count'] : $ssd_retweet_count;
	$ssd_retweet_count              = (int) $ssd_retweet_count;
	$ssd_reply_link                 = isset( $ssd_settings['ssd_reply_link'] ) ? $ssd_settings['ssd_reply_link'] : $ssd_reply_link;
	$ssd_reply_link                 = (int) $ssd_reply_link;
	$ssd_favorite_count             = isset( $ssd_settings['ssd_favorite_count'] ) ? $ssd_settings['ssd_favorite_count'] : $ssd_favorite_count;
	$ssd_favorite_count             = (int) $ssd_favorite_count;
	$ssd_like_count                 = isset( $ssd_settings['ssd_like_count'] ) ? $ssd_settings['ssd_like_count'] : $ssd_like_count;
	$ssd_like_count                 = (int) $ssd_like_count;
	$ssd_pin_count                  = isset( $ssd_settings['ssd_pin_count'] ) ? $ssd_settings['ssd_pin_count'] : $ssd_pin_count;
	$ssd_pin_count                  = (int) $ssd_pin_count;
	$ssd_dislike_count              = isset( $ssd_settings['ssd_dislike_count'] ) ? $ssd_settings['ssd_dislike_count'] : $ssd_dislike_count;
	$ssd_dislike_count              = (int) $ssd_dislike_count;
	$ssd_comment_count              = isset( $ssd_settings['ssd_comment_count'] ) ? $ssd_settings['ssd_comment_count'] : $ssd_comment_count;
	$ssd_comment_count              = (int) $ssd_comment_count;
	$ssd_share_count                = isset( $ssd_settings['ssd_share_count'] ) ? $ssd_settings['ssd_share_count'] : $ssd_share_count;
	$ssd_share_count                = (int) $ssd_share_count;
	$ssd_social_share_type          = isset( $ssd_settings['ssd_social_share_type'] ) ? $ssd_settings['ssd_social_share_type'] : $ssd_social_share_type;
	$ssd_display_social_icon        = isset( $ssd_settings['ssd_display_social_icon'] ) ? $ssd_settings['ssd_display_social_icon'] : $ssd_display_social_icon;
	$ssd_display_social_icon        = (int) $ssd_display_social_icon;
	$ssd_design_layout              = ( isset( $ssd_settings['ssd_design_layout'] ) && '' !== $ssd_settings['ssd_design_layout'] ) ? $ssd_settings['ssd_design_layout'] : $ssd_design_layout;
	$ssd_display_image              = isset( $ssd_settings['ssd_display_image'] ) ? $ssd_settings['ssd_display_image'] : $ssd_display_image;
	$ssd_display_image              = (int) $ssd_display_image;
	$ssd_image_layout               = isset( $ssd_settings['ssd_image_layout'] ) ? $ssd_settings['ssd_image_layout'] : $ssd_image_layout;
	$ssd_icon_alignment             = isset( $ssd_settings['ssd_icon_alignment'] ) ? $ssd_settings['ssd_icon_alignment'] : $ssd_icon_alignment;
	$ssd_icon_position              = isset( $ssd_settings['ssd_icon_position'] ) ? $ssd_settings['ssd_icon_position'] : $ssd_icon_position;
	$ssd_display_corner_icon        = isset( $ssd_settings['ssd_display_corner_icon'] ) ? $ssd_settings['ssd_display_corner_icon'] : $ssd_display_corner_icon;
	$ssd_display_corner_icon        = (int) $ssd_display_corner_icon;
	$ssd_display_sticky             = isset( $ssd_settings['ssd_display_sticky'] ) ? $ssd_settings['ssd_display_sticky'] : $ssd_display_sticky;
	$ssd_display_sticky             = (int) $ssd_display_sticky;
	$ssd_display_sticky_on          = isset( $ssd_settings['ssd_display_sticky_on'] ) ? $ssd_settings['ssd_display_sticky_on'] : $ssd_display_sticky_on;
	$ssd_icon_color                 = isset( $ssd_settings['ssd_icon_color'] ) ? $ssd_settings['ssd_icon_color'] : $ssd_icon_color;
	$ssd_icon_bg_color              = isset( $ssd_settings['ssd_icon_bg_color'] ) ? $ssd_settings['ssd_icon_bg_color'] : $ssd_icon_bg_color;
	$ssd_icon_border_radius_type    = isset( $ssd_settings['ssd_icon_border_radius_type'] ) ? $ssd_settings['ssd_icon_border_radius_type'] : $ssd_icon_border_radius_type;
	$ssd_text_border_radius_type    = isset( $ssd_settings['ssd_text_border_radius_type'] ) ? $ssd_settings['ssd_text_border_radius_type'] : $ssd_text_border_radius_type;
	$ssd_display_title              = isset( $ssd_settings['ssd_display_title'] ) ? $ssd_settings['ssd_display_title'] : $ssd_display_title;
	$ssd_display_title              = (int) $ssd_display_title;
	$ssd_display_title_link         = isset( $ssd_settings['ssd_display_title_link'] ) ? $ssd_settings['ssd_display_title_link'] : $ssd_display_title_link;
	$ssd_display_title_link         = (int) $ssd_display_title_link;
	$ssd_title_color                = isset( $ssd_settings['ssd_title_color'] ) ? $ssd_settings['ssd_title_color'] : $ssd_title_color;
	$ssd_title_hover_color          = isset( $ssd_settings['ssd_title_hover_color'] ) ? $ssd_settings['ssd_title_hover_color'] : $ssd_title_hover_color;
	$ssd_display_content            = isset( $ssd_settings['ssd_display_content'] ) ? $ssd_settings['ssd_display_content'] : $ssd_display_content;
	$ssd_display_content            = (int) $ssd_display_content;
	$ssd_content_color              = isset( $ssd_settings['ssd_content_color'] ) ? $ssd_settings['ssd_content_color'] : $ssd_content_color;
	// $ssd_content_hover_color        = isset( $ssd_settings['ssd_content_hover_color'] ) ? $ssd_settings['ssd_content_hover_color'] : $ssd_content_hover_color;
	$ssd_display_author_box        = isset( $ssd_settings['ssd_display_author_box'] ) ? $ssd_settings['ssd_display_author_box'] : $ssd_display_author_box;
	$ssd_display_author_box        = (int) $ssd_display_author_box;
	$ssd_author_border_radius_type = isset( $ssd_settings['ssd_author_border_radius_type'] ) ? $ssd_settings['ssd_author_border_radius_type'] : $ssd_author_border_radius_type;
	$ssd_author_title_color        = isset( $ssd_settings['ssd_author_title_color'] ) ? $ssd_settings['ssd_author_title_color'] : $ssd_author_title_color;
	$ssd_author_bg_color           = isset( $ssd_settings['ssd_author_bg_color'] ) ? $ssd_settings['ssd_author_bg_color'] : $ssd_author_bg_color;
	$ssd_author_title_hover_color  = isset( $ssd_settings['ssd_author_title_hover_color'] ) ? $ssd_settings['ssd_author_title_hover_color'] : $ssd_author_title_hover_color;
	$ssd_author_meta_color         = isset( $ssd_settings['ssd_author_meta_color'] ) ? $ssd_settings['ssd_author_meta_color'] : $ssd_author_meta_color;
	$ssd_count_meta_color          = isset( $ssd_settings['ssd_count_meta_color'] ) ? $ssd_settings['ssd_count_meta_color'] : $ssd_count_meta_color;
	$ssd_count_bg_color            = isset( $ssd_settings['ssd_count_bg_color'] ) ? $ssd_settings['ssd_count_bg_color'] : $ssd_count_bg_color;
	$ssd_count_meta_hover_color    = isset( $ssd_settings['ssd_count_meta_hover_color'] ) ? $ssd_settings['ssd_count_meta_hover_color'] : $ssd_count_meta_hover_color;
	$ssd_count_border_top_type     = isset( $ssd_settings['ssd_count_border_top_type'] ) ? $ssd_settings['ssd_count_border_top_type'] : $ssd_count_border_top_type;
	$ssd_count_border_top_color    = isset( $ssd_settings['ssd_count_border_top_color'] ) ? $ssd_settings['ssd_count_border_top_color'] : $ssd_count_border_top_color;
	$ssd_count_border_bottom_type  = isset( $ssd_settings['ssd_count_border_bottom_type'] ) ? $ssd_settings['ssd_count_border_bottom_type'] : $ssd_count_border_bottom_type;
	$ssd_count_border_bottom_color = isset( $ssd_settings['ssd_count_border_bottom_color'] ) ? $ssd_settings['ssd_count_border_bottom_color'] : $ssd_count_border_bottom_color;
	$ssd_count_padding_top         = isset( $ssd_settings['ssd_count_padding_top'] ) && '' !== $ssd_settings['ssd_count_padding_top'] ? $ssd_settings['ssd_count_padding_top'] : $ssd_count_padding_top;
	$ssd_count_padding_bottom      = isset( $ssd_settings['ssd_count_padding_bottom'] ) && '' !== $ssd_settings['ssd_count_padding_bottom'] ? $ssd_settings['ssd_count_padding_bottom'] : $ssd_count_padding_bottom;
	$ssd_count_margin_top          = isset( $ssd_settings['ssd_count_margin_top'] ) && '' !== $ssd_settings['ssd_count_margin_top'] ? $ssd_settings['ssd_count_margin_top'] : $ssd_count_margin_top;
	$ssd_count_margin_bottom       = isset( $ssd_settings['ssd_count_margin_bottom'] ) && '' !== $ssd_settings['ssd_count_margin_bottom'] ? $ssd_settings['ssd_count_margin_bottom'] : $ssd_count_margin_bottom;
	$ssd_extra_class               = isset( $ssd_settings['ssd_card_extra_class'] ) ? $ssd_settings['ssd_card_extra_class'] : '';
	$ssd_count_border_right_type   = isset( $ssd_settings['ssd_count_border_right_type'] ) ? $ssd_settings['ssd_count_border_right_type'] : $ssd_count_border_right_type;
	$ssd_count_border_right_color  = isset( $ssd_settings['ssd_count_border_right_color'] ) ? $ssd_settings['ssd_count_border_right_color'] : $ssd_count_border_right_color;
	$ssd_count_border_left_type    = isset( $ssd_settings['ssd_count_border_left_type'] ) ? $ssd_settings['ssd_count_border_left_type'] : $ssd_count_border_left_type;
	$ssd_count_border_left_color   = isset( $ssd_settings['ssd_count_border_left_color'] ) ? $ssd_settings['ssd_count_border_left_color'] : $ssd_count_border_left_color;
}
?>
<div class="wrap"> <!-- wrap start -->
	<?php
	settings_errors();
	if ( isset( $_REQUEST['update'] ) && 'added' === $_REQUEST['update'] ) {
		?>
		<div class="updated notice">
			<p>
				<?php
				if ( $ssd_page ) {
					echo esc_html_e( 'Layout has been added successfully.', 'social-stream-design' ) . '&nbsp;';
					?>
					<a href="<?php echo esc_url( get_the_permalink( $ssd_page ) ); ?>"
						target="_blank"><?php echo esc_html_e( 'View Layout', 'social-stream-design' ); ?></a>
					<?php
				} else {
					echo esc_html_e( 'Layout has been added successfully.', 'social-stream-design' );
				}
				?>
			</p>
		</div>
		<?php
	} elseif ( isset( $_REQUEST['update'] ) && 'updated' === $_REQUEST['update'] ) {
		?>
		<div class="updated notice">
			<p>
				<?php
				if ( $ssd_page ) {
					echo esc_html_e( 'Layout has been updated successfully.', 'social-stream-design' ) . '&nbsp;';
					?>
					<a href="<?php echo esc_url( get_the_permalink( $ssd_page ) ); ?>"
						target="_blank"><?php echo esc_html_e( 'View Layout', 'social-stream-design' ); ?></a>
					<?php
				} else {
					echo esc_html_e( 'Layout has been updated successfully.', 'social-stream-design' );
				}
				?>
			</p>
		</div>
		<?php
	} elseif ( isset( $_REQUEST['update'] ) && 'false' == $_REQUEST['update'] ) {
		?>
		<div class="error notice">
			<p>
				<?php
				esc_html_e( 'Error to update layout.', 'social-stream-design' );
				?>
			</p>
		</div>
		<?php
	} elseif ( isset( $_REQUEST['duplicate'] ) && 'true' == $_REQUEST['duplicate'] ) {
		?>
		<div class="updated notice">
			<p>
				<?php
				esc_html_e( 'Layout has been duplicated successfully.', 'social-stream-design' );
				?>
			</p>
		</div>
		<?php
	} elseif ( isset( $_REQUEST['reset'] ) && '1' == $_REQUEST['reset'] ) {
		?>
		<div class="updated notice">
			<p>
				<?php
				esc_html_e( 'Layout has been reset successfully.', 'social-stream-design' );
				?>
			</p>
		</div>
		<?php
	}
	?>
	<div class="ssd_splash-screen"></div>
	<div class="ssd-screen ssd_layouts">
		<!-- ssd-screen start -->
		<form method="post" class="ssd-form-class">
			<div class="ssd-scod-div">
				<div class="pull-left">
					<p class="ssd-page-title"><?php echo esc_html( $page_title ); ?></p>
				</div>
				<div class="pull-right">
					<?php
					if ( isset( $_REQUEST['action'] ) && 'edit' === $_REQUEST['action'] ) {
						$shortcode_id = isset( $_REQUEST['id'] ) ? intval( $_REQUEST['id'] ) : '';
						?>
						<input type="text" readonly="" onclick="this.select()" class="copy_shortcode" title="Copy Shortcode"
							value='[social_stream_feeds id="<?php echo intval( $shortcode_id ); ?>"]'>
						<?php
					}
					?>
				</div>
			</div>
			<?php wp_nonce_field( 'social-stream-designer_meta_box_nonce', 'ssd_nonce' ); ?>
			<input type="hidden" id="ssd_section" class="ssd_section" name="ssd_section" value="ssd_generalsettings">
			<input type="hidden" id="ssd_edit_action" name="ssd_edit_action"
				value="<?php echo esc_attr( $edit_action ); ?>">
			<input type="hidden" id="ssd_edit_id" name="ssd_edit_id" value="<?php echo intval( $edit_id ); ?>">
			<div class="stm-hdr ssdstm-ly-hdr">
				<h3><?php esc_html_e( 'Social Stream Layout Settings', 'social-stream-design' ); ?></h3>
				<p class="submit">
					<a id="ssd-btn-sw-prv" disabled="disabled"
						title="<?php esc_html_e( 'Show Preview', 'social-stream-design' ); ?>"
						class="button show_preview button-primary" href="#">
						<span><?php esc_html_e( 'Preview', 'social-stream-design' ); ?></span>
					</a>
					<?php
					if ( isset( $_GET['action'] ) && ( 'edit' === $_GET['action'] ) && isset( $_GET['id'] ) && ( '' != $_GET['id'] ) && ( '0' != $_GET['id'] ) ) {
						?>
						<input name="ssd_reset_layout" id="ssd_reset_layout" class="button button-primary"
							value="<?php esc_html_e( 'Reset', 'social-stream-design' ); ?>" type="reset"
							data-id="<?php echo intval( $_GET['id'] ); ?>">
						<?php
					}
					?>
					<input name="submit" id="submit" class="button button-primary"
						value="<?php esc_html_e( 'Save', 'social-stream-design' ); ?>" type="submit">
				</p>
			</div>
			<div class="ssd-preview-box" id="ssd-preview-box"></div>
			<div class="ssds-cntr">
				<div class="ssds-mn-set">
					<ul class="ssd_stream-setting-handle">
						<li data-show="ssd_generalsettings" class="ssd_generalsettings layout_tabs">
							<i
								class="fas fa-cog"></i><span><?php esc_html_e( 'General Settings', 'social-stream-design' ); ?></span>
						</li>
						<li data-show="selectcardsettings" class="selectcardsettings layout_tabs">
							<i
								class="far fa-address-card"></i><span><?php esc_html_e( 'Select Card Layout', 'social-stream-design' ); ?></span>
						</li>
						<li data-show="layoutsettings" class="layoutsettings layout_tabs">
							<i
								class="fas fa-th-large"></i><span><?php esc_html_e( 'Layout Settings', 'social-stream-design' ); ?></span>
						</li>
						<li data-show="cardsettings" class="cardsettings layout_tabs">
							<i
								class="far fa-address-card"></i><span><?php esc_html_e( 'Card Settings', 'social-stream-design' ); ?></span>
						</li>
						<li data-show="sharelabelsettings" class="sharelabelsettings">
							<i
								class="far fa-share-square"></i><span><?php esc_html_e( 'Share Icon Settings', 'social-stream-design' ); ?></span>
						</li>
						<li data-show="titlesettings" class="titlesettings">
							<i
								class="fas fa-text-width"></i><span><?php esc_html_e( 'Title Settings', 'social-stream-design' ); ?></span>
						</li>
						<li data-show="contentsettings" class="contentsettings">
							<i
								class="far fa-file-alt"></i><span><?php esc_html_e( 'Content Settings', 'social-stream-design' ); ?></span>
						</li>
						<li data-show="mediasettings" class="mediasettings">
							<i
								class="far fa-images"></i><span><?php esc_html_e( 'Media Settings', 'social-stream-design' ); ?></span>
						</li>
						<li data-show="authorsettings" class="authorsettings">
							<i
								class="far fa-user"></i><span><?php esc_html_e( 'Author Content Settings', 'social-stream-design' ); ?></span>
						</li>
						<li data-show="ssd_countsettings" class="ssd_countsettings">
							<i
								class="far fa-comments"></i><span><?php esc_html_e( 'Count Bar Settings', 'social-stream-design' ); ?></span>
						</li>
						<li data-show="paginationsettings" class="paginationsettings">
							<i
								class="fas fa-sort-numeric-down"></i><span><?php esc_html_e( 'Pagination Settings', 'social-stream-design' ); ?></span>
						</li>
						<li data-show="ssd_popupsettings" class="ssd_popupsettings">
							<i
								class="far fa-window-restore"></i><span><?php esc_html_e( 'Popup Settings', 'social-stream-design' ); ?></span>
						</li>
					</ul>
				</div>
				<div id="ssd_generalsettings" class="ssds-set-box">
					<div class="inside">
						<table class="form-table">
							<tbody>
								<tr>
									<td><?php echo esc_html( 'Social Stream' ); ?></td>
									<td>
										<?php
										$feeds_array = array();
										if ( is_array( $all_feeds ) && ! empty( $all_feeds ) ) {
											foreach ( $all_feeds as $single_feed ) {
												$feeds_settings = maybe_unserialize( $single_feed->feeds_settings );
												if ( isset( $feeds_settings['feed_status'] ) && 'Active' == $feeds_settings['feed_status'] ) {
													$feeds_array[ $single_feed->id ] =
														array(
															$single_feed->id => $feeds_settings['feed_name'],
															'feed_type' => $feeds_settings['feed'],
														);
												}
											}
										}
										$no_feeds_message = '';
										$all_feeds        = '';
										$feed_object      = new WpSSDFeedsActions();
										$feed_object->init();
										$all_feeds = $feed_object->ssd_get_all_feeds();
										if ( '' == $all_feeds ) {
											$no_feeds_message = "You haven't added any feeds yet. Please add feeds first";
										}

										if ( count( $feeds_array ) <= 0 ) {
											$no_feeds_message = 'You have not any Active feeds. Please Active feeds to display here';
										}

										if ( '' !== $no_feeds_message ) {
											?>
											<p style="margin-top: 0px;margin-bottom: 8px;"><span
													class="ssd-warning description"><?php echo esc_html( $no_feeds_message ); ?></span>
											</p>
											<?php
										}
										?>
										<select multiple="multiple" id="ssdssstrm" class="layout ssd_stream_select"
											name="ssd[feed_ids][]" required="">
											<?php
											foreach ( $feeds_array as $feed_array ) {
												foreach ( $feed_array as $key => $value ) {
													if ( 'feed_type' != $key && '' != $value ) {
														?>
														<option
															data-feed_type="<?php echo esc_attr( $feeds_array[ $key ]['feed_type'] ); ?>"
															value="<?php echo esc_html( $key ); ?>" 
																			  <?php
																				if ( is_array( $ssd_feed_ids ) ) {
																					if ( in_array( $key, $ssd_feed_ids ) ) {
																						echo 'selected="selected"';
																					}
																				}
																				?>
																 >
															<?php echo esc_html( $value ); ?>
														</option>
														<?php
													}
												}
											}
											?>
										</select>
										<p class="description">
											<?php esc_html_e( 'Select the feeds which you have created in feeds tab.', 'social-stream-design' ); ?>
										</p>
									</td>
								</tr>
								<tr>
									<td><?php esc_html_e( 'Title', 'social-stream-design' ); ?></td>
									<td>
										<input type="text" name="ssd_title"
											value="<?php echo esc_attr( $ssd_title ); ?>">
										<p class="description">
											<?php esc_html_e( 'Enter the layout title. This will not display on frontend it is only for the identification on layout listing page.', 'social-stream-design' ); ?>
										</p>
									</td>
								</tr>
								<tr>
									<td><?php esc_html_e( 'Select Page for Stream', 'social-stream-design' ); ?></td>
									<td>
										<select class="stream_page ssd_stream_select" name="ssd[ssd_stream_page]">
											<option value="">
												<?php esc_html_e( 'Select Page', 'social-stream-design' ); ?></option>
											<?php
											$pages_s = get_pages();
											foreach ( $pages_s as $page_s ) {
												?>
												<option value="<?php echo intval( $page_s->ID ); ?>" <?php echo ( $ssd_page == $page_s->ID ) ? ' selected="selected"' : ''; ?>>
													<?php echo esc_attr( $page_s->post_title ); ?></option>
												<?php
											}
											?>
										</select>
										<p class="description">
											<?php esc_html_e( 'Select the page for display layout', 'social-stream-design' ); ?>
										</p>
										<p><span
												class="ssd-warning description"><?php esc_html_e( 'Caution', 'social-stream-design' ); ?>:</span>&nbsp;&nbsp;<span
												class="description"><?php esc_html_e( 'You are about to select the page for your layout, you will lost your page content. There is no undo. Think about it!', 'social-stream-design' ); ?></span>
										</p>
									</td>
								</tr>
								<tr>
									<td><?php esc_html_e( 'Order By', 'social-stream-design' ); ?></td>
									<td>
										<div class="ssd-col-xs-4">
											<select id="ssd_social_stream_order_by" class="ssd_stream_select"
												name="ssd[ssd_order_by]">
												<option value="rand" <?php echo ( 'rand' === $ssd_order_by ) ? ' selected="selected"' : ''; ?>><?php echo esc_html( 'Random' ); ?>
												</option>
												<option value="date" <?php echo ( 'date' === $ssd_order_by ) ? ' selected="selected"' : ''; ?>><?php echo esc_html( 'Date' ); ?>
												</option>
												<option value="social-media" <?php echo ( 'social-media' === $ssd_order_by ) ? ' selected="selected"' : ''; ?>>
													<?php echo esc_html( 'Social Media' ); ?></option>
											</select>
										</div>
										<p class="description">
											<?php esc_html_e( 'Select parameter to sort feeds. If you select "Social media" option then all the same social media feeds are display together.', 'social-stream-design' ); ?>
										</p>
									</td>
								</tr>
								<tr class="ssd_order_by_tr">
									<td><?php esc_html_e( 'Order', 'social-stream-design' ); ?></td>
									<td>
										<div class="radio-group">
											<input type="radio" id="display_order_yes" class="display_order yes"
												name="ssd[ssd_display_order]" value="1" <?php echo ( 1 == $ssd_display_order ) ? ' checked="checked"' : ''; ?>><label
												for="display_order_yes"><?php echo esc_html( 'Ascending' ); ?></label>
											<input type="radio" id="display_order_no" class="display_order no"
												name="ssd[ssd_display_order]" value="0" <?php echo ( 0 == $ssd_display_order ) ? ' checked="checked"' : ''; ?>><label
												for="display_order_no"><?php echo esc_html( 'Descending' ); ?></label>
										</div>
										<p class="description">
											<?php esc_html_e( 'Ascending order from lowest to highest values ( 1,2,3; a,b,c) or Descending order from highest to lowest values (3, 2, 1; c, b, a).', 'social-stream-design' ); ?>
										</p>
									</td>
								</tr>
								<tr class="disable_li">
									<td><i
											class="fa fa-lock"></i>&nbsp;<?php esc_html_e( 'Private Stream', 'social-stream-design' ); ?>
									</td>
									<td>
										<div class="radio-group">
											<input type="radio" id="display_private_stream_yes"
												class="display_order yes" name="ssd[ssd_display_private_stream]"
												value="1" <?php echo ( 1 == $ssd_display_private_stream ) ? ' checked="checked"' : ''; ?>><label
												for="display_private_stream_yes"><?php esc_html_e( 'Yes', 'social-stream-design' ); ?></label>
											<input type="radio" id="display_private_stream_no" class="display_order no"
												name="ssd[ssd_display_private_stream]" value="0" <?php echo ( 0 == $ssd_display_private_stream ) ? ' checked="checked"' : ''; ?>><label
												for="display_private_stream_no"><?php esc_html_e( 'No', 'social-stream-design' ); ?></label>
										</div>
										<p class="description">
											<?php esc_html_e( 'Show only for logged in users.', 'social-stream-design' ); ?>
										</p>
									</td>
								</tr>
								<tr class="disable_li">
									<td><i
											class="fa fa-lock"></i>&nbsp;<?php esc_html_e( 'Hide Stream on a Desktop', 'social-stream-design' ); ?>
									</td>
									<td>
										<div class="radio-group">
											<input type="radio" id="hide_desktop_stream_yes" class="display_order yes"
												name="ssd[ssd_hide_desktop_stream]" value="1" <?php echo ( 1 == $ssd_hide_desktop_stream ) ? ' checked="checked"' : ''; ?>><label
												for="hide_desktop_stream_yes"><?php esc_html_e( 'Yes', 'social-stream-design' ); ?></label>
											<input type="radio" id="hide_desktop_stream_no" class="display_order no"
												name="ssd[ssd_hide_desktop_stream]" value="0" <?php echo ( 0 == $ssd_hide_desktop_stream ) ? ' checked="checked"' : ''; ?>><label
												for="hide_desktop_stream_no"><?php esc_html_e( 'No', 'social-stream-design' ); ?></label>
										</div>
										<p class="description">
											<b><?php esc_html_e( 'Note', 'social-stream-design' ); ?></b> :
											<?php esc_html_e( 'If you want to create mobiles specific stream only.', 'social-stream-design' ); ?>
										</p>
									</td>
								</tr>
								<tr class="disable_li">
									<td><i
											class="fa fa-lock"></i>&nbsp;<?php esc_html_e( 'Hide Stream on a Mobile', 'social-stream-design' ); ?>
									</td>
									<td>
										<div class="radio-group">
											<input type="radio" id="hide_mobile_stream_yes" class="display_order yes"
												name="ssd[ssd_hide_mobile_stream]" value="1" <?php echo ( 1 == $ssd_hide_mobile_stream ) ? ' checked="checked"' : ''; ?>><label
												for="hide_mobile_stream_yes"><?php esc_html_e( 'Yes', 'social-stream-design' ); ?></label>
											<input type="radio" id="hide_mobile_stream_no" class="display_order no"
												name="ssd[ssd_hide_mobile_stream]" value="0" <?php echo ( 0 == $ssd_hide_mobile_stream ) ? ' checked="checked"' : ''; ?>><label
												for="hide_mobile_stream_no"><?php esc_html_e( 'No', 'social-stream-design' ); ?></label>
										</div>
										<p class="description">
											<b><?php esc_html_e( 'Note', 'social-stream-design' ); ?></b> :
											<?php esc_html_e( 'If you want to show stream content only on desktop.', 'social-stream-design' ); ?>
										</p>
									</td>
								</tr>
								<tr>
									<td><?php esc_html_e( 'Display Stream Meta', 'social-stream-design' ); ?></td>
									<td>
										<div class="radio-group">
											<input type="radio" id="display_meta_yes" class="ssd_display_post_meta yes"
												name="ssd[ssd_display_meta]" value="1" <?php echo ( 1 == $ssd_display_meta ) ? ' checked="checked"' : ''; ?>><label
												for="display_meta_yes"><?php esc_html_e( 'Yes', 'social-stream-design' ); ?></label>
											<input type="radio" id="ssd_display_meta_no"
												class="ssd_display_post_meta no" name="ssd[ssd_display_meta]" value="0"
												<?php echo ( 0 == $ssd_display_meta ) ? ' checked="checked"' : ''; ?>><label
												for="ssd_display_meta_no"><?php esc_html_e( 'No', 'social-stream-design' ); ?></label>
										</div>
										<p class="description">
											<b><?php esc_html_e( 'Note', 'social-stream-design' ); ?></b> :
											<?php esc_html_e( 'Display comments, like, dislikes, views, tweets in each post.', 'social-stream-design' ); ?>
										</p>
									</td>
								</tr>
								<tr>
									<td><?php esc_html_e( 'Custom CSS', 'social-stream-design' ); ?></td>
									<td>
										<textarea
											placeholder="<?php echo esc_html( '.class_name{ color:#ffffff }' ); ?>"
											name="ssd[ssd_custom_css]"><?php echo isset( $ssd_settings['ssd_custom_css'] ) ? esc_html( $ssd_settings['ssd_custom_css'] ) : ''; ?></textarea>
									</td>
								</tr>
							</tbody>
						</table>
					</div>
				</div>
				<div id="selectcardsettings" class="ssds-set-box">
					<div class="inside">
						<table class="form-table">
							<tbody>
								<tr>
									<td>
										<div id="ssd_item_data">
											<?php
											for ( $i = 1; $i <= 5; $i++ ) {
												$class        = 'layout-' . $i;
												$disble_class = '';
												if ( $i > 2 ) {
													$disble_class = 'disable_div_div';
												}
												?>
												<div data-id="<?php echo intval( $i ); ?>" class="ssd_item-template layout-<?php echo intval( $i ); ?>">
													<label class="ssd_item-content 
													<?php
													if ( $class === $ssd_design_layout ) {
														echo esc_attr( 'selected' );
													}
													echo esc_attr( $disble_class );
													?>
													" for="ssd_design_layout_<?php echo intval( $i ); ?>">
														<img
															src="<?php echo esc_url( WPSOCIALSTREAMDESIGNER_URL ) . '/images/style-' . intval( $i ) . '.png'; ?>">
														<?php if ( $i < 3 ) { ?>
															<input id="ssd_design_layout_<?php echo intval( $i ); ?>"
																type="radio" name="ssd[ssd_design_layout]" <?php checked( $ssd_design_layout, $class ); ?>
																value="layout-<?php echo intval( $i ); ?>">
															<?php
														}
														if ( $i > 2 ) {
															?>
															<span class="disable_div_l"><i class="fa fa-lock"></i></span>
														<?php } ?>
													</label>
												</div>
												<?php
											}
											?>
										</div>
										<p class="description">
											<?php esc_html_e( 'Select the layout which is more suitable as per your requirement.', 'social-stream-design' ); ?>
										</p>
									</td>
									<td>
										<?php
										$id_ss = ( isset( $_GET['id'] ) && '' !== $_GET['id'] ) ? intval( $_GET['id'] ) : '0';

										$ssd_social_order_e       = get_option( 'ssd_social_order_' . intval( $id_ss ) );
										$ssd_orignal_social       = 'social-share,media,title,content,author,count,';
										$ssd_orignal_social_array = explode( ',', $ssd_orignal_social );

										if ( '' == $ssd_social_order_e ) {
											$ssd_social_order_e = $ssd_orignal_social;
										}
										$ssd_social_order_e   = explode( ',', $ssd_social_order_e );
										$proper_ordered_array = $ssd_social_order_e;
										$proper_ordered_array = array_filter( $proper_ordered_array );
										$proper_ordered_array = array_unique( $proper_ordered_array );
										foreach ( $proper_ordered_array as $proper_ordered_val ) {
											$ssd_social_order[] = $proper_ordered_val;
										}
										?>
										<h4 class="text-center">
											<?php esc_html_e( 'Drag and Drop Builder', 'social-stream-design' ); ?></h4>
										<div
											class="ssd-col-item ssd-container ssd-col <?php echo esc_attr( $ssd_design_layout . ' ' . $ssd_extra_class . ' ssd-' . $ssd_social_share_type ); ?>">
											<div class="ssd-card facebook ssd-drag-drop-layout">
												<ul id="sortable" data-shortcode-id="<?php echo intval( $id_ss ); ?>">
													<?php
													$ssd_social_order_c = count( $ssd_social_order );
													for ( $i = 0; $i < $ssd_social_order_c; $i++ ) {
														if ( 'title' === $ssd_social_order[ $i ] ) {
															?>
															<li class="" data-order="title">
																<input type="hidden" value="title" name="ssd-drag-drop-layout[]">
																<h3><?php esc_html_e( '41 landing page optimization best practices', 'social-stream-design' ); ?>
																</h3>
															</li>
															<?php
														}
														if ( 'content' === $ssd_social_order[ $i ] ) {
															?>
															<li class="" data-order="content">
																<input type="hidden" value="content" name="ssd-drag-drop-layout[]">
																<p style="margin:0">
																	<?php echo esc_html( 'At ornare ullamcorper potenti pulvinar wisi. Nibh faucibus nec duis elit eleifend accumsan libero sociis metus id feugiat. Quis interdum senectus. Luctus etiam consequat adipiscing lobortis nec. Massa wisi cras.' ); ?>
																</p>
															</li>
															<?php
														}
														if ( 'social-share' === $ssd_social_order[ $i ] ) {
															?>
															<li class=" ssd-social-share-sortable" data-order="social-share">
																<input type="hidden" value="social-share" name="ssd-drag-drop-layout[]">
																<div class="facebook-cover"><i
																		class="fab fa-facebook-f"></i><?php esc_html_e( 'Facebook', 'social-stream-design' ); ?>
																</div>
															</li>
															<?php
														}
														if ( 'media' === $ssd_social_order[ $i ] ) {
															?>
															<li class=" ssd-media-sortable" data-order="media">
																<input type="hidden" value="media" name="ssd-drag-drop-layout[]">
																<img src="<?php echo esc_url( WPSOCIALSTREAMDESIGNER_URL ) . '/images/layout-media.png'; ?>"
																	alt="<?php esc_html_e( 'Sample Image', 'social-stream-design' ); ?>">
															</li>
															<?php
														}
														if ( 'author' === $ssd_social_order[ $i ] ) {
															?>
															<li class=" ssd-author-sortable" data-order="author">
																<input type="hidden" value="author" name="ssd-drag-drop-layout[]">
																<div class="ssd-author-detail">
																	<div class='ssd-author-image'></div>
																	<div class="ssd-author-name">
																		<a
																			href=''><?php esc_html_e( 'Solwin Infotech', 'social-stream-design' ); ?></a>
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
															<li class="" data-order="count">
																<input type="hidden" value="count" name="ssd-drag-drop-layout[]">
																<div class="ssd-action-row">
																	<a href="">
																		<i class='fas fa-eye'></i>
																		<span class='ssd-counts'>17</span>
																	</a>
																	<a href="">
																		<i class='fas fa-comments'></i>
																		<span class='ssd-counts'>46</span>
																	</a>
																	<a href="">
																		<i class='fas fa-heart'></i>
																		<span class='ssd-counts'>46</span>
																	</a>
																	<a href="">
																		<i class='far fa-thumbs-up'></i>
																		<span class='ssd-counts'>5</span>
																	</a>
																</div>
															</li>
															<?php
														}
													}
													?>
												</ul>
											</div>
										</div>
										<p class="description">
											<?php esc_html_e( 'You can drag and drop element up and down. In layout 2 and layout 3 you can not change the position of author box.', 'social-stream-design' ); ?>
										</p>
									</td>
								</tr>
							</tbody>
						</table>
					</div>
				</div>

				<div id="layoutsettings" class="ssds-set-box">
					<div class="inside">
						<table class="form-table">
							<tbody>
								<tr>
									<td><?php esc_html_e( 'Layout Type', 'social-stream-design' ); ?></td>
									<td>
										<div class="ssd-col-xs-5">
											<select id="ssdsstmly" class="layout ssd_stream_select"
												name="ssd[ssd_layout]">
												<option value="listing" selected="selected">
													<?php echo esc_html( 'Listing' ); ?></option>
												<option value="" disabled><i
														class="fa fa-lock"></i>&nbsp;<?php echo esc_html( 'Slider' ); ?>
												</option>
												<option value="" disabled><i
														class="fa fa-lock"></i>&nbsp;<?php echo esc_html( 'Timeline' ); ?>
												</option>
											</select>
										</div>
									</td>
								</tr>
								<tr class="ssd_display_columns">
									<td>
										<?php
										esc_html_e( 'Number of Feeds in One Row', 'social-stream-design' );
										echo esc_html( ' (Desktop)' );
										?>
									</td>
									<td>
										<div class="ssd-col-xs-2">
											<select id="column_type" class="ssd_stream_select column_type"
												name="ssd[ssd_column_type]">
												<option value="1" <?php echo ( '1' == $ssd_column_type ) ? ' selected="selected"' : ''; ?>>1</option>
												<option value="2" <?php echo ( '2' == $ssd_column_type ) ? ' selected="selected"' : ''; ?>>2</option>
												<option value="3" <?php echo ( '3' == $ssd_column_type ) ? ' selected="selected"' : ''; ?>>3</option>
												<option value="4" <?php echo ( '4' == $ssd_column_type ) ? ' selected="selected"' : ''; ?>>4</option>
											</select>
										</div>
										<p class="description">
											<?php
											esc_html_e( 'Enter the number of feeds to display in one row', 'social-stream-design' );
											echo esc_html( ' (Screen Size 1200px and above)' );
											?>
										</p>
									</td>
								</tr>
								<tr class="ssd_display_columns">
									<td>
										<?php
										esc_html_e( 'Number of Feeds in One Row', 'social-stream-design' );
										echo esc_html( ' (Laptop)' );
										?>
									</td>
									<td>
										<div class="ssd-col-xs-2">
											<select id="column_type_laptop" class="ssd_stream_select column_type_laptop"
												name="ssd[ssd_column_type_laptop]">
												<option value="1" <?php echo ( '1' == $ssd_column_type_laptop ) ? ' selected="selected"' : ''; ?>>1</option>
												<option value="2" <?php echo ( '2' == $ssd_column_type_laptop ) ? ' selected="selected"' : ''; ?>>2</option>
												<option value="3" <?php echo ( '3' == $ssd_column_type_laptop ) ? ' selected="selected"' : ''; ?>>3</option>
												<option value="4" <?php echo ( '4' == $ssd_column_type_laptop ) ? ' selected="selected"' : ''; ?>>4</option>
											</select>
										</div>
										<p class="description">
											<?php
											esc_html_e( 'Enter the number of feeds to display in one row', 'social-stream-design' );
											echo esc_html( ' (Minimum Screen Size 1024px and Maximum Screen Size 1200px)' );
											?>
										</p>
									</td>
								</tr>
								<tr class="ssd_display_columns">
									<td>
										<?php
										esc_html_e( 'Number of Feeds in One Row', 'social-stream-design' );
										echo esc_html( ' (Rotated Tablet)' );
										?>
									</td>
									<td>
										<div class="ssd-col-xs-2">
											<select id="column_type_rotated_tablet"
												class="ssd_stream_select column_type_rotated_tablet"
												name="ssd[ssd_column_type_rotated_tablet]">
												<option value="1" <?php echo ( '1' == $ssd_column_type_rotated_tablet ) ? ' selected="selected"' : ''; ?>>1</option>
												<option value="2" <?php echo ( '2' == $ssd_column_type_rotated_tablet ) ? ' selected="selected"' : ''; ?>>2</option>
												<option value="3" <?php echo ( '3' == $ssd_column_type_rotated_tablet ) ? ' selected="selected"' : ''; ?>>3</option>
												<option value="4" <?php echo ( '4' == $ssd_column_type_rotated_tablet ) ? ' selected="selected"' : ''; ?>>4</option>
											</select>
										</div>
										<p class="description">
											<?php
											esc_html_e( 'Enter the number of feeds to display in one row', 'social-stream-design' );
											echo esc_html( ' (Minimum Screen Size 767px and Maximum Screen Size 1024px)' );
											?>
										</p>
									</td>
								</tr>
								<tr class="ssd_display_columns">
									<td>
										<?php
										esc_html_e( 'Number of Feeds in One Row', 'social-stream-design' );
										echo esc_html( ' (Tablet)' );
										?>
									</td>
									<td>
										<div class="ssd-col-xs-2">
											<select id="column_type_tablet" class="ssd_stream_select column_type_tablet"
												name="ssd[ssd_column_type_tablet]">
												<option value="1" <?php echo ( '1' == $ssd_column_type_tablet ) ? ' selected="selected"' : ''; ?>>1</option>
												<option value="2" <?php echo ( '2' == $ssd_column_type_tablet ) ? ' selected="selected"' : ''; ?>>2</option>
												<option value="3" <?php echo ( '3' == $ssd_column_type_tablet ) ? ' selected="selected"' : ''; ?>>3</option>
												<option value="4" <?php echo ( '4' == $ssd_column_type_tablet ) ? ' selected="selected"' : ''; ?>>4</option>
											</select>
										</div>
										<p class="description">
											<?php
											esc_html_e( 'Enter the number of feeds to display in one row', 'social-stream-design' );
											echo esc_html( ' (Minimum Screen Size 480px and Maximum Screen Size 767px)' );
											?>
										</p>
									</td>
								</tr>
								<tr class="ssd_display_columns">
									<td>
										<?php
										esc_html_e( 'Number of Feeds in One Row', 'social-stream-design' );
										echo esc_html( ' (Rotated Mobile)' );
										?>
									</td>
									<td>
										<div class="ssd-col-xs-2">
											<select id="column_type_rotated_mobile"
												class="ssd_stream_select column_type_rotated_mobile"
												name="ssd[ssd_column_type_rotated_mobile]">
												<option value="1" <?php echo ( '1' == $ssd_column_type_rotated_mobile ) ? ' selected="selected"' : ''; ?>>1</option>
												<option value="2" <?php echo ( '2' == $ssd_column_type_rotated_mobile ) ? ' selected="selected"' : ''; ?>>2</option>
											</select>
										</div>
										<p class="description">
											<?php
											esc_html_e( 'Enter the number of feeds to display in one row', 'social-stream-design' );
											echo esc_html( ' (Minimum Screen Size 380px and Maximum Screen Size 480px)' );
											?>
										</p>
									</td>
								</tr>
								<tr class="ssd_display_columns">
									<td>
										<?php
										esc_html_e( 'Number of Feeds in One Row', 'social-stream-design' );
										echo esc_html( ' (Mobile)' );
										?>
									</td>
									<td>
										<div class="ssd-col-xs-2">
											<select id="column_type_mobile" class="ssd_stream_select column_type_mobile"
												name="ssd[ssd_column_type_mobile]">
												<option value="1" <?php echo ( '1' == $ssd_column_type_mobile ) ? ' selected="selected"' : ''; ?>>1</option>
												<option value="2" <?php echo ( '2' == $ssd_column_type_mobile ) ? ' selected="selected"' : ''; ?>>2</option>
											</select>
										</div>
										<p class="description">
											<?php
											esc_html_e( 'Enter the number of feeds to display in one row', 'social-stream-design' );
											echo esc_html( ' (Screen Size 380px and below)' );
											?>
										</p>
									</td>
								</tr>
								<tr class="ssd_display_share_icon_tr">
									<td><?php esc_html_e( 'Display Share Icon', 'social-stream-design' ); ?></td>
									<td>
										<div class="radio-group">
											<input type="radio" id="display_share_with_yes"
												class="display_share_with yes" name="ssd[ssd_display_share_with]"
												value="1" <?php echo ( 1 == $ssd_display_share_with ) ? ' checked="checked"' : ''; ?>><label
												for="display_share_with_yes"><?php esc_html_e( 'Yes', 'social-stream-design' ); ?></label>
											<input type="radio" id="ssd_display_share_with_no"
												class="display_share_with no" name="ssd[ssd_display_share_with]"
												value="0" <?php echo ( 0 == $ssd_display_share_with ) ? ' checked="checked"' : ''; ?>><label
												for="ssd_display_share_with_no"><?php esc_html_e( 'No', 'social-stream-design' ); ?></label>
										</div>
										<p class="description">
											<?php esc_html_e( 'Show/Hide share icon. With this you can share you feeds on multiple social media.', 'social-stream-design' ); ?>
										</p>
									</td>
								</tr>
								<tr class="disable_li">
									<td><i
											class="fa fa-lock"></i>&nbsp;<?php esc_html_e( 'Display Filter', 'social-stream-design' ); ?>
									</td>
									<td>
										<div class="radio-group">
											<input type="radio" id="ssd_display_filter_yes"
												class="ssd_display_filter yes" name="" value=""><label
												for="ssd_display_filter_yes"><?php esc_html_e( 'Yes', 'social-stream-design' ); ?></label>
											<input type="radio" id="ssd_display_filter_no" class="ssd_display_filter no"
												name="" value="" checked="checked"><label
												for="ssd_display_filter_no"><?php esc_html_e( 'No', 'social-stream-design' ); ?></label>
										</div>
										<p class="description">
											<?php esc_html_e( 'Show/Hide filter on frontend', 'social-stream-design' ); ?>
										</p>
									</td>
								</tr>
								<tr class="disable_li">
									<td><i
											class="fa fa-lock"></i>&nbsp;<?php esc_html_e( 'Display Search', 'social-stream-design' ); ?>
									</td>
									<td>
										<div class="radio-group">
											<input type="radio" id="display_search_yes" class="display_search yes"
												name="ssd[ssd_display_search]" value="1" <?php echo ( 1 == $ssd_display_search ) ? ' checked="checked"' : ''; ?>><label
												for="display_search_yes"><?php esc_html_e( 'Yes', 'social-stream-design' ); ?></label>
											<input type="radio" id="display_search_no" class="display_search no"
												name="ssd[ssd_display_search]" value="0" <?php echo ( 0 == $ssd_display_search ) ? ' checked="checked"' : ''; ?>><label
												for="display_search_no"><?php esc_html_e( 'No', 'social-stream-design' ); ?></label>
										</div>
										<p class="description">
											<b><?php esc_html_e( 'Note', 'social-stream-design' ); ?></b> :
											<?php esc_html_e( 'Available only for listing layouts.', 'social-stream-design' ); ?>
										</p>
									</td>
								</tr>
								<tr class="disable_li">
									<td><i
									class="fa fa-lock"></i>&nbsp;<?php esc_html_e( 'Theme Color', 'social-stream-design' ); ?></td>
									<td>
										<input type="text" id="ssd_theme_color"
											class="ssd_theme_color ssd_cpa-color-picker" name="ssd[ssd_theme_color]"
											value="<?php echo esc_attr( $ssd_theme_color ); ?>">
									</td>
								</tr>
								<tr class="ssd_overlay_bg_color_main">
									<td><?php esc_html_e( 'Background color', 'social-stream-design' ); ?></td>
									<td>
										<input type="text" id="ssd_overlay_bg_color"
											class="ssd_overlay_bg_color ssd_cpa-color-picker"
											name="ssd[ssd_overlay_bg_color]"
											value="<?php echo esc_attr( $ssd_overlay_bg_color ); ?>">
									</td>
								</tr>
								<tr>
									<td class="ssd_label_fit_content"><?php echo esc_html( 'Padding' ); ?></td>
									<td>
										<div class="ssd-col-xs-2">
											<div class="ssd_border_cover">
												<label><?php echo esc_html( 'Top' ); ?>&nbsp;(px)<br /></label>
												<input type="number" class="ssd_number_field" min="0" step="1"
													name="ssd[ssd_overlay_padding_top]"
													value="<?php echo isset( $ssd_settings['ssd_overlay_padding_top'] ) ? esc_attr( $ssd_settings['ssd_overlay_padding_top'] ) : '10'; ?>" />
											</div>
										</div>
										<div class="ssd-col-xs-2">
											<div class="ssd_border_cover">
												<label><?php echo esc_html( 'Bottom' ); ?>&nbsp;(px)<br /></label>
												<input type="number" class="ssd_number_field" min="0" step="1"
													name="ssd[ssd_overlay_padding_bottom]"
													value="<?php echo isset( $ssd_settings['ssd_overlay_padding_bottom'] ) ? esc_attr( $ssd_settings['ssd_overlay_padding_bottom'] ) : '10'; ?>" />
											</div>
										</div>
										<div class="ssd-col-xs-2">
											<div class="ssd_border_cover">
												<label><?php echo esc_html( 'Left' ); ?>&nbsp;(px)<br /></label>
												<input type="number" min="0" step="1" class="ssd_number_field"
													name="ssd[ssd_overlay_padding_left]"
													value="<?php echo isset( $ssd_settings['ssd_overlay_padding_left'] ) ? esc_attr( $ssd_settings['ssd_overlay_padding_left'] ) : '10'; ?>" />
											</div>
										</div>
										<div class="ssd-col-xs-2">
											<div class="ssd_border_cover">
												<label><?php echo esc_html( 'Right' ); ?>&nbsp;(px)<br /></label>
												<input type="number" min="0" step="1" class="ssd_number_field"
													name="ssd[ssd_overlay_padding_right]"
													value="<?php echo isset( $ssd_settings['ssd_overlay_padding_right'] ) ? esc_attr( $ssd_settings['ssd_overlay_padding_right'] ) : '10'; ?>" />
											</div>
										</div>
									</td>
								</tr>
								<tr>
									<td><?php esc_html_e( 'Stream Heading', 'social-stream-design' ); ?></td>
									<td>
										<input type="text" name="ssd[ssd_stream_title]"
											value="<?php echo isset( $ssd_settings['ssd_stream_title'] ) ? esc_attr( $ssd_settings['ssd_stream_title'] ) : ''; ?>">
										<p class="description">
											<?php esc_html_e( 'Enter the Stream title. This will display on frontend.If Leave empty to not show.', 'social-stream-design' ); ?>
										</p>
									</td>
								</tr>
								<tr class="ssd_heading_color_main">
									<td><?php esc_html_e( 'Heading color', 'social-stream-design' ); ?></td>
									<td>
										<input type="text" id="ssd_heading_color"
											class="ssd_heading_color ssd_cpa-color-picker" name="ssd[ssd_heading_color]"
											value="<?php echo esc_attr( $ssd_heading_color ); ?>">
									</td>
								</tr>
								<tr>
									<td><?php esc_html_e( 'Stream Sub-Heading', 'social-stream-design' ); ?></td>
									<td>
										<input type="text" name="ssd[ssd_stream_subtitle]"
											value="<?php echo isset( $ssd_settings['ssd_stream_subtitle'] ) ? esc_attr( $ssd_settings['ssd_stream_subtitle'] ) : ''; ?>">
										<p class="description">
											<?php esc_html_e( 'Enter the Stream sub-title. This will display on frontend.If Leave empty to not show.', 'social-stream-design' ); ?>
										</p>
									</td>
								</tr>
								<tr class="ssd_subheading_color_main">
									<td><?php esc_html_e( 'Sub-Heading color', 'social-stream-design' ); ?></td>
									<td>
										<input type="text" id="ssd_subheading_color"
											class="ssd_subheading_color ssd_cpa-color-picker"
											name="ssd[ssd_subheading_color]"
											value="<?php echo esc_attr( $ssd_subheading_color ); ?>">
									</td>
								</tr>
								<tr>
									<td><?php esc_html_e( 'Headings alignment', 'social-stream-design' ); ?></td>
									<td>
										<div class="ssd-col-xs-2">
											<select id="ssd_heading_alignment"
												class="ssd_stream_select ssd_heading_alignment"
												name="ssd[ssd_heading_alignment]">
												<option value="left" <?php echo ( 'left' === $ssd_heading_alignment ) ? ' selected="selected"' : ''; ?>><?php echo esc_html( 'Left' ); ?>
												</option>
												<option value="center" <?php echo ( 'center' === $ssd_heading_alignment ) ? ' selected="selected"' : ''; ?>>
													<?php echo esc_html( 'Center' ); ?></option>
												<option value="right" <?php echo ( 'right' === $ssd_heading_alignment ) ? ' selected="selected"' : ''; ?>><?php echo esc_html( 'Right' ); ?>
												</option>
											</select>
										</div>
									</td>
								</tr>
								<tr class="disable_li">
									<td><i
											class="fa fa-lock"></i>&nbsp;<?php esc_html_e( 'Heading Background color', 'social-stream-design' ); ?>
									</td>
									<td>
										<input disabled type="text" id="ssd_heading_bg_color"
											class="ssd_heading_bg_color ssd_cpa-color-picker" name="" value="">
									</td>
								</tr>
							</tbody>
						</table>
					</div>
				</div>
				<div id="cardsettings" class="ssds-set-box">
					<div class="inside">
						<table class="form-table">
							<tbody>
								<tr class="disable_li">
									<td class="ssd_label_fit_content"><i
											class="fa fa-lock"></i>&nbsp;<?php esc_html_e( 'Card Border', 'social-stream-design' ); ?>
									</td>
									<td>
										<div class="ssd_border_cover">
											<label><?php echo esc_html( 'Top' ); ?>&nbsp;(px)<br /></label>
											<div class="ssd-col-xs-3">
												<input type="number" class="ssd_number_field" min="0" step="1" name=""
													value="0" />
											</div>
											<div class="ssd-col-xs-3">
												<select class="ssd_stream_select" id="ssd_card_border_top_type" name="">
													<option value=""><?php echo esc_html( 'Solid' ); ?></option>
													<option value=""><?php echo esc_html( 'Dotted' ); ?></option>
													<option value=""><?php echo esc_html( 'Dash' ); ?></option>
													<option value=""><?php echo esc_html( 'Double' ); ?></option>
												</select>
											</div>
											<div class="ssd-col-xs-4">
												<input name="" value="" type="text" class="ssd_cpa-color-picker">
											</div>
										</div>
										<div class="ssd_border_cover">
											<label><?php echo esc_html( 'Bottom' ); ?>&nbsp;(px)<br /></label>
											<div class="ssd-col-xs-3">
												<input type="number" class="ssd_number_field" min="0" step="1" name=""
													value="0" />
											</div>
											<div class="ssd-col-xs-3">
												<select class="ssd_stream_select" name="">
													<option value=""><?php echo esc_html( 'Solid' ); ?></option>
													<option value=""><?php echo esc_html( 'Dotted' ); ?></option>
													<option value=""><?php echo esc_html( 'Dash' ); ?></option>
													<option value=""><?php echo esc_html( 'Double' ); ?></option>
												</select>
											</div>
											<div class="ssd-col-xs-4">
												<input name="" value="" type="text" class="ssd_cpa-color-picker">
											</div>
										</div>
										<div class="ssd_border_cover">
											<label><?php echo esc_html( 'Left' ); ?>&nbsp;(px)<br /></label>
											<div class="ssd-col-xs-3">
												<input type="number" class="ssd_number_field" min="0" step="1" name=""
													value="0" />
											</div>
											<div class="ssd-col-xs-3">
												<select class="ssd_stream_select" name="">
													<option value=""><?php echo esc_html( 'Solid' ); ?></option>
													<option value=""><?php echo esc_html( 'Dotted' ); ?></option>
													<option value=""><?php echo esc_html( 'Dash' ); ?></option>
													<option value=""><?php echo esc_html( 'Double' ); ?></option>
												</select>
											</div>
											<div class="ssd-col-xs-4">
												<input name="" value="" type="text" class="ssd_cpa-color-picker">
											</div>
										</div>
										<div class="ssd_border_cover">
											<label><?php echo esc_html( 'Right' ); ?>&nbsp;(px)<br /></label>
											<div class="ssd-col-xs-3">
												<input type="number" class="ssd_number_field" min="0" step="1" name=""
													value="0" />
											</div>
											<div class="ssd-col-xs-3">
												<select class="ssd_stream_select" name="">
													<option value=""><?php echo esc_html( 'Solid' ); ?></option>
													<option value=""><?php echo esc_html( 'Dotted' ); ?></option>
													<option value=""><?php echo esc_html( 'Dash' ); ?></option>
													<option value=""><?php echo esc_html( 'Double' ); ?></option>
												</select>
											</div>
											<div class="ssd-col-xs-4">
												<input name="" value="" type="text" class="ssd_cpa-color-picker">
											</div>
										</div>
									</td>
								</tr>
								<tr class="disable_li">
									<td><i
											class="fa fa-lock"></i>&nbsp;<?php esc_html_e( 'Card Border Radius (px)', 'social-stream-design' ); ?>
									</td>
									<td>
										<div class="ssd-col-xs-4">
											<div class="ssd_border_cover">
												<input type="number" class="ssd_number_field" max="25" min="0" step="1"
													name="" value="0" />
											</div>
										</div>
									</td>
								</tr>
								<tr class="disable_li">
									<td><i
											class="fa fa-lock"></i>&nbsp;<?php esc_html_e( 'Card Background Color', 'social-stream-design' ); ?>
									</td>
									<td>
										<input name="" value="" type="text" class="ssd_cpa-color-picker">
									</td>
								</tr>
								<tr class="ssd-layout-4">
									<td><i
											class="fa fa-lock"></i>&nbsp;<?php esc_html_e( 'Card Overlay Background Color', 'social-stream-design' ); ?>
									</td>
									<td>
										<input name="" value="" type="text" data-alpha="true"
											class="ssd_cpa-color-picker">
									</td>
								</tr>
								<tr class="disable_li">
									<td class="ssd_label_fit_content"><i
											class="fa fa-lock"></i>&nbsp;<?php esc_html_e( 'Card Box Shadow', 'social-stream-design' ); ?>
									</td>
									<td>
										<div class="ssd-col-xs-2">
											<div class="ssd_border_cover">
												<label><?php echo esc_html( 'H-offset' ); ?>&nbsp;(px)<br /></label>
												<input type="number" class="ssd_number_field" step="1" min='-10'
													max="10" name="" value="0" />
											</div>
										</div>
										<div class="ssd-col-xs-2">
											<div class="ssd_border_cover">
												<label><?php echo esc_html( 'V-offset' ); ?>&nbsp;(px)<br /></label>
												<input type="number" class="ssd_number_field" step="1" min='-10'
													max="10" name="" value="0" />
											</div>
										</div>
										<div class="ssd-col-xs-2">
											<div class="ssd_border_cover">
												<label><?php echo esc_html( 'Blur' ); ?>&nbsp;(px)<br /></label>
												<input type="number" class="ssd_number_field" step="1" min='-10'
													max="10" name="" value="0" />
											</div>
										</div>
										<div class="ssd-col-xs-2">
											<div class="ssd_border_cover">
												<label><?php echo esc_html( 'Spread' ); ?>&nbsp;(px)<br /></label>
												<input type="number" class="ssd_number_field" step="1" min='-10'
													max="10" name="" value="0" />
											</div>
										</div>
										<div class="ssd-col-xs-6">
											<div class="ssd_card_box_shadow_main">
												<label><?php echo esc_html( 'Color' ); ?>&nbsp;(px)<br /></label>
												<input name="" value="" type="text" class="ssd_cpa-color-picker">
											</div>
										</div>
									</td>
								</tr>
								<tr class="">
									<td><?php esc_html_e( 'Card Custom Class', 'social-stream-design' ); ?></td>
									<td>
										<input type="text" name="ssd[ssd_card_extra_class]"
											value="<?php echo isset( $ssd_settings['ssd_card_extra_class'] ) ? esc_attr( $ssd_settings['ssd_card_extra_class'] ) : ''; ?>" />
										<p class="description">
											<?php esc_html_e( 'Enter the custom class name here, which will apply to the card and this class use for custom css which you will add from General Setting -> Custom CSS.', 'social-stream-design' ); ?>
										</p>
									</td>
								</tr>
							</tbody>
						</table>
					</div>
				</div>
				<div id="sharelabelsettings" class="ssds-set-box">
					<div class="inside">
						<table class="form-table">
							<tbody>
								<tr>
									<td><?php esc_html_e( 'Display Social Icon', 'social-stream-design' ); ?></td>
									<td>
										<div class="radio-group">
											<input type="radio" id="ssd_display_social_icon_yes"
												class="ssd_display_social_icon yes" name="ssd[ssd_display_social_icon]"
												value="1" <?php echo ( 1 == $ssd_display_social_icon ) ? ' checked="checked"' : ''; ?>><label
												for="ssd_display_social_icon_yes"><?php esc_html_e( 'Yes', 'social-stream-design' ); ?></label>
											<input type="radio" id="display_social_icon_no"
												class="ssd_display_social_icon no" name="ssd[ssd_display_social_icon]"
												value="0" <?php echo ( 0 == $ssd_display_social_icon ) ? ' checked="checked"' : ''; ?>><label
												for="display_social_icon_no"><?php esc_html_e( 'No', 'social-stream-design' ); ?></label>
										</div>
									</td>
								</tr>
								<tr class="ssd_social_row">
									<td><?php esc_html_e( 'Social Share Type', 'social-stream-design' ); ?></td>
									<td>
										<div class="ssd-col-xs-5">
											<select id="ssd_social_share_type" class="ssd_stream_select"
												name="ssd[ssd_social_share_type]">
												<option value="icon" <?php echo ( 'icon' === $ssd_social_share_type ) ? ' selected="selected"' : ''; ?>><?php echo esc_html( 'Icon' ); ?>
												</option>
												<option value="text" <?php echo ( 'text' === $ssd_social_share_type ) ? ' selected="selected"' : ''; ?>><?php echo esc_html( 'Text' ); ?>
												</option>
												<option value="icon_text" <?php echo ( 'icon_text' === $ssd_social_share_type ) ? ' selected="selected"' : ''; ?>>
													<?php
													esc_html_e( 'Icon', 'social-stream-design' );
													echo ' + ';
													esc_html_e( 'Text', 'social-stream-design' );
													?>
												</option>
											</select>
										</div>
									</td>
								</tr>
								<tr class="ssd_social_row ssd_show_image_for_icon_tr">
									<td><?php esc_html_e( 'Social Share Icon Style', 'social-stream-design' ); ?></td>
									<td>
										<div class="radio-group">
											<input type="radio" id="ssddimg_no" class="ssddimg no"
												name="ssd[ssd_display_image]" value="0" <?php echo ( 0 == $ssd_display_image ) ? ' checked="checked"' : ''; ?>><label
												for="ssddimg_no"><?php esc_html_e( 'Default', 'social-stream-design' ); ?></label>
											<input type="radio" id="ssddimg_yes" class="ssddimg yes"
												name="ssd[ssd_display_image]" value="1" <?php echo ( 1 == $ssd_display_image ) ? ' checked="checked"' : ''; ?>><label
												for="ssddimg_yes"><?php esc_html_e( 'Custom', 'social-stream-design' ); ?></label>
										</div>
									</td>
								</tr>
								<tr class="ssd_social_row ssd_image_layout_tr">
									<td><?php esc_html_e( 'Select Icon Layout', 'social-stream-design' ); ?></td>
									<td>
										<?php
										for ( $i = 1; $i <= 4; $i++ ) {
											?>
											<div class="ssdily_pst ssd_image_layout_<?php echo intval( $i ); ?>">
												<label>
													<input type="radio" name="ssd[ssd_image_layout]"
														class="ssd_image_layout_<?php echo intval( $i ); ?>"
														value="ssd_image_layout_<?php echo intval( $i ); ?>" <?php echo ( "ssd_image_layout_$i" === $ssd_image_layout ) ? ' checked="checked"' : ''; ?>>
													<span
														class="<?php echo ( "ssd_image_layout_$i" === $ssd_image_layout ) ? 'selected' : ''; ?>"></span>
												</label>
											</div>
											<?php
										}
										?>
									</td>
								</tr>
								<tr class="ssd_social_row ssd_show_corner_tr">
									<td><?php esc_html_e( 'Display Corner Icon', 'social-stream-design' ); ?></td>
									<td>
										<div class="radio-group">
											<input type="radio" id="ssd_display_corner_icon_yes"
												class="ssd_display_corner_icon yes" name="ssd[ssd_display_corner_icon]"
												value="1" <?php echo ( 1 == $ssd_display_corner_icon ) ? ' checked="checked"' : ''; ?>><label
												for="ssd_display_corner_icon_yes"><?php esc_html_e( 'Yes', 'social-stream-design' ); ?></label>
											<input type="radio" id="ssd_display_corner_icon_no"
												class="ssd_display_corner_icon no" name="ssd[ssd_display_corner_icon]"
												value="0" <?php echo ( 0 == $ssd_display_corner_icon ) ? ' checked="checked"' : ''; ?>><label
												for="ssd_display_corner_icon_no"><?php esc_html_e( 'No', 'social-stream-design' ); ?></label>
										</div>
										<p class="description">
											<?php esc_html_e( 'Display social icon on top, bottom, left, right corner.', 'social-stream-design' ); ?>
										</p>
									</td>
								</tr>
								<tr class="ssd_social_row ssd_show_sticky_icon_tr">
									<td><?php esc_html_e( 'Display Sticky', 'social-stream-design' ); ?></td>
									<td>
										<div class="radio-group">
											<input type="radio" id="ssd_display_sticky_yes"
												class="ssd_display_sticky yes" name="ssd[ssd_display_sticky]" value="1"
												<?php echo ( 1 == $ssd_display_sticky ) ? ' checked="checked"' : ''; ?>><label
												for="ssd_display_sticky_yes"><?php esc_html_e( 'Yes', 'social-stream-design' ); ?></label>
											<input type="radio" id="ssd_display_sticky_no" class="ssd_display_sticky no"
												name="ssd[ssd_display_sticky]" value="0" <?php echo ( 0 == $ssd_display_sticky ) ? ' checked="checked"' : ''; ?>><label
												for="ssd_display_sticky_no"><?php esc_html_e( 'No', 'social-stream-design' ); ?></label>
										</div>
										<p class="description">
											<?php esc_html_e( 'Display social icon sticky on media or author', 'social-stream-design' ); ?>
										</p>
									</td>
								</tr>

								<tr class="ssd_social_row ssd_show_sticky_icon_on_tr">
									<td><?php esc_html_e( 'Display Sticky on', 'social-stream-design' ); ?></td>
									<td>
										<div class="ssd-col-xs-4">
											<select id="ssd_display_sticky_on" class="ssd_stream_select"
												name="ssd[ssd_display_sticky_on]">
												<option value="media" <?php echo ( 'media' === $ssd_display_sticky_on ) ? ' selected="selected"' : ''; ?>>
													<?php esc_html_e( 'Media', 'social-stream-design' ); ?></option>
												<?php
												if ( 'layout-2' === $ssd_design_layout ) {
													?>
													<option value="author" <?php echo ( 'author' === $ssd_display_sticky_on ) ? ' selected="selected"' : ''; ?>>
														<?php esc_html_e( 'Author', 'social-stream-design' ); ?></option>
													<?php
												} else {
													?>
													<option value="author" disabled <?php echo ( 'author' === $ssd_display_sticky_on ) ? ' selected="selected"' : ''; ?>><?php esc_html_e( 'Author', 'social-stream-design' ); ?></option>
													<?php
												}
												?>
											</select>
										</div>
									</td>
								</tr>
								<tr class="ssd_social_row ssd_icon_border_radius_tr">
									<td><?php esc_html_e( 'Icon Border Radius', 'social-stream-design' ); ?></td>
									<td>
										<div class="ssd-col-xs-4">
											<div class="ssd_border_cover">
												<input type="number" min="0" class="ssd_number_field" step="1"
													name="ssd[ssd_icon_border_radius]"
													value="<?php echo isset( $ssd_settings['ssd_icon_border_radius'] ) && '' !== $ssd_settings['ssd_icon_border_radius'] ? esc_attr( $ssd_settings['ssd_icon_border_radius'] ) : '0'; ?>" />
											</div>
										</div>
										<div class="ssd-col-xs-4">
											<div class="ssd_border_cover">
												<select id="ssd_icon_border_radius_type" class="ssd_stream_select"
													name="ssd[ssd_icon_border_radius_type]">
													<option value="px" <?php echo ( 'px' === $ssd_icon_border_radius_type ) ? ' selected="selected"' : ''; ?>><?php echo 'px'; ?></option>
													<option value="%" <?php echo ( '%' === $ssd_icon_border_radius_type ) ? ' selected="selected"' : ''; ?>><?php echo 'percentage'; ?>
													</option>
												</select>
											</div>
										</div>
									</td>
								</tr>
								<tr class="ssd_social_row ssd_text_border_radius_tr">
									<td><?php esc_html_e( 'Text Border Radius', 'social-stream-design' ); ?></td>
									<td>
										<div class="ssd-col-xs-2">
											<div class="ssd_border_cover">
												<input type="number" min="0" class="ssd_number_field" step="1"
													name="ssd[ssd_text_border_radius]"
													value="<?php echo isset( $ssd_settings['ssd_text_border_radius'] ) && '' !== $ssd_settings['ssd_text_border_radius'] ? esc_attr( $ssd_settings['ssd_text_border_radius'] ) : '0'; ?>" />
											</div>
										</div>
										<div class="ssd-col-xs-3">
											<div class="ssd_border_cover">
												<select id="ssd_text_border_radius_type" class="ssd_stream_select"
													name="ssd[ssd_text_border_radius_type]">
													<option value="px" <?php echo ( 'px' === $ssd_text_border_radius_type ) ? ' selected="selected"' : ''; ?>><?php echo 'px'; ?></option>
													<option value="%" <?php echo ( '%' === $ssd_text_border_radius_type ) ? ' selected="selected"' : ''; ?>><?php echo 'percentage'; ?>
													</option>
												</select>
											</div>
										</div>
									</td>
								</tr>
								<tr class="ssd_social_row ssd_icon_text_alignment_tr">
									<td><?php esc_html_e( 'Alignment', 'social-stream-design' ); ?></td>
									<td>
										<div class="ssd-col-xs-3">
											<select id="ssd_icon_alignment" class="ssd_stream_select"
												name="ssd[ssd_icon_alignment]">
												<option value="left" <?php echo ( 'left' === $ssd_icon_alignment ) ? ' selected="selected"' : ''; ?>><?php echo esc_html( 'Left' ); ?>
												</option>
												<option value="right" <?php echo ( 'right' === $ssd_icon_alignment ) ? ' selected="selected"' : ''; ?>><?php echo esc_html( 'Right' ); ?>
												</option>
												<?php
												if ( 0 == $ssd_display_corner_icon ) {
													?>
													<option value="center" <?php echo ( 'center' === $ssd_icon_alignment ) ? ' selected="selected"' : ''; ?>><?php echo esc_html( 'Center' ); ?>
													</option>
													<?php
												}
												?>
											</select>
										</div>
										<p class="description">
											<?php esc_html_e( 'Select the alignment of social share icon', 'social-stream-design' ); ?>
										</p>
									</td>
								</tr>
								<tr class="ssd_social_row ssd_icon_text_position_tr">
									<td><?php esc_html_e( 'Position', 'social-stream-design' ); ?></td>
									<td>
										<div class="ssd-col-xs-3">
											<select id="ssd_icon_position" class="ssd_stream_select"
												name="ssd[ssd_icon_position]">
												<option value="top" <?php echo ( 'top' === $ssd_icon_position ) ? ' selected="selected"' : ''; ?>><?php echo esc_html( 'Top' ); ?>
												</option>
												<option value="bottom" <?php echo ( 'bottom' === $ssd_icon_position ) ? ' selected="selected"' : ''; ?>><?php echo esc_html( 'Bottom' ); ?>
												</option>
											</select>
										</div>
										<p class="description">
											<?php esc_html_e( 'Select the position of social share icon', 'social-stream-design' ); ?>
										</p>
									</td>
								</tr>
								<tr class="ssd_social_row ssd_icon_text_color_tr">
									<td><?php esc_html_e( 'Color', 'social-stream-design' ); ?></td>
									<td>
										<input name="ssd[ssd_icon_color]"
											value="<?php echo esc_attr( $ssd_icon_color ); ?>" type="text"
											class="ssd_cpa-color-picker">
									</td>
								</tr>
								<tr class="ssd_social_row ssd_icon_text_bg_color_tr">
									<td><?php esc_html_e( 'Background Color', 'social-stream-design' ); ?></td>
									<td>
										<input name="ssd[ssd_icon_bg_color]"
											value="<?php echo esc_attr( $ssd_icon_bg_color ); ?>" type="text"
											class="ssd_cpa-color-picker">
									</td>
								</tr>
							</tbody>
						</table>
					</div>
				</div>
				<div id="titlesettings" class="ssds-set-box">
					<div class="inside">
						<table class="form-table">
							<tbody>
								<tr class="disable_li">
									<td><i
											class="fa fa-lock"></i>&nbsp;<?php esc_html_e( 'Display Title', 'social-stream-design' ); ?>
									</td>
									<td>
										
										<div class="radio-group">
											<input type="radio" id="ssd_display_title_yes" class="ssd_display_title yes"
												name="ssd[ssd_display_title]" value="1" <?php echo ( 1 == $ssd_display_title ) ? ' checked="checked"' : ''; ?>><label
												for="ssd_display_title_yes"><?php esc_html_e( 'Yes', 'social-stream-design' ); ?></label>
											<input type="radio" id="display_title_no" class="ssd_display_title no"
												name="ssd[ssd_display_title]" value="0" <?php echo ( 0 == $ssd_display_title ) ? ' checked="checked"' : ''; ?>><label
												for="display_title_no"><?php esc_html_e( 'No', 'social-stream-design' ); ?></label>
										</div>
									</td>
								</tr>

								</tr class=>
								<?php echo esc_attr( isset( $ssd_settings['ssd_title_display_number_lines'] ) ); ?>

								<tr class="ssd_title_settings_tr disable_li">
									<td><i
											class="fa fa-lock"></i> <?php esc_html_e( 'Post Title Maximum Line', 'social-stream-designer' ); ?></td>
									<td>
										
										<div class="radio-group">
											<input type="radio" id="ssd_display_title_post_size_yes" class="ssd_display_title_post_size yes" name="ssd[ssd_display_title_post_size]" value="1" <?php echo ( 1 == $ssd_display_title_post_size ) ? ' checked="checked"' : ''; ?>><label for="ssd_display_title_post_size_yes"><?php esc_html_e( 'Yes', 'social-stream-designer' ); ?></label>
											<input type="radio" id="ssd_display_title_post_size_no" class="ssd_display_title_post_size no" name="ssd[ssd_display_title_post_size]" value="0" <?php echo ( 0 == $ssd_display_title_post_size ) ? ' checked="checked"' : ''; ?> ><label for="ssd_display_title_post_size_no"><?php esc_html_e( 'No', 'social-stream-designer' ); ?></label>
										</div>
									</td>
								</tr>

								<tr class="ssd_title_settings_tr cd-additional-options disable_li">
									<td >
									<i
											class="fa fa-lock"></i> 
									<?php
									esc_html_e( 'Display Maximum Number Of Lines', 'social-stream-designer' );

									?>
									</td>
									<td >
									<?php
									$ssd_title_setting = isset( $ssd_settings['ssd_title_display_number_lines'] ) ? esc_attr( $ssd_settings['ssd_title_display_number_lines'] ) : '4';
									?>
									
										<div class="ssd-col-xs-6  ">
											<input type="number" min="0" step="1" class disabled="ssd_number_fields " name="ssd[ssd_title_display_number_lines]" value="<?php echo isset( $ssd_settings['ssd_title_display_number_lines'] ) ? esc_attr( $ssd_settings['ssd_title_display_number_lines'] ) : '1'; ?>" />
										</div>
									</td>
								</tr>


								<tr class="ssd_title_settings_tr disable_li">
									<td><i
											class="fa fa-lock"></i> <?php esc_html_e( 'Post Title Break Words', 'social-stream-designer' ); ?></td>
									<td>

										<div class="radio-group">

											<input type="radio" id="ssd_display_title_post_default"
												class="ssd_display_title_post_break yes"
												name="ssd[ssd_display_title_post_break]" value="default" <?php echo ( 'default' == $ssd_display_title_post_break ) ? ' checked="checked"' : ''; ?>><label
												for="ssd_display_title_post_default"><?php esc_html_e( 'Default', 'social-stream-designer' ); ?></label>

											<input type="radio" id="ssd_display_title_post_break-all"
												class="ssd_display_title_post_break yes"
												name="ssd[ssd_display_title_post_break]" value="break-all" <?php echo ( 'break-all' == $ssd_display_title_post_break ) ? ' checked="checked"' : ''; ?>><label
												for="ssd_display_title_post_break-all"><?php esc_html_e( 'Break-All', 'social-stream-designer' ); ?></label>

											<input type="radio" id="ssd_display_title_post_break-word"
												class="ssd_display_title_post_break yes"
												name="ssd[ssd_display_title_post_break]" value="break-word" <?php echo ( 'break-word' == $ssd_display_title_post_break ) ? ' checked="checked"' : ''; ?>><label
												for="ssd_display_title_post_break-word"><?php esc_html_e( 'Break-Words', 'social-stream-designer' ); ?></label>


										</div>
									</td>
								</tr>

								<tr class="ssd_title_settings_tr disable_li">
									<td><i
											class="fa fa-lock"></i>&nbsp;<?php esc_html_e( 'Display Title Link', 'social-stream-design' ); ?>
									</td>
									<td>
										<div class="radio-group">
											<input type="radio" id="ssd_display_title_link_yes"
												class="ssd_display_title_link yes" name="ssd[ssd_display_title_link]"
												value="1" <?php echo ( 1 == $ssd_display_title_link ) ? ' checked="checked"' : ''; ?>><label
												for="ssd_display_title_link_yes"><?php esc_html_e( 'Yes', 'social-stream-design' ); ?></label>
											<input type="radio" id="display_title_link_no"
												class="ssd_display_title_link no" name="ssd[ssd_display_title_link]"
												value="0" <?php echo ( 0 == $ssd_display_title_link ) ? ' checked="checked"' : ''; ?>><label
												for="display_title_link_no"><?php esc_html_e( 'No', 'social-stream-design' ); ?></label>
										</div>
									</td>
								</tr>
								<tr class="ssd_title_settings_tr disable_li">
									<td><i class="fa fa-lock"></i>&nbsp;
										<?php
										esc_html_e( 'Title Font Size', 'social-stream-design' );
										echo ' (px)';
										?>
									</td>
									<td>
										<div class="ssd-col-xs-6">
											<input disabled type="number" min="0" step="1" class="ssd_number_field"
												name="ssd[ssd_title_font_size]"
												value="<?php echo isset( $ssd_settings['ssd_title_font_size'] ) ? esc_attr( $ssd_settings['ssd_title_font_size'] ) : '16'; ?>" />
										</div>
									</td>
								</tr>
								<tr class="ssd_title_settings_tr disable_li">
									<td><i
											class="fa fa-lock"></i>&nbsp;<?php esc_html_e( 'Title Color', 'social-stream-design' ); ?>
									</td>
									<td>
										<input name="ssd[ssd_title_color]"
											value="<?php echo esc_attr( $ssd_title_color ); ?>" type="text"
											class="ssd_cpa-color-picker">
									</td>
								</tr>
								<tr class="ssd_title_settings_tr ssd_title_hover_settings_tr disable_li">
									<td><i
											class="fa fa-lock"></i>&nbsp;<?php esc_html_e( 'Title Hover Color', 'social-stream-design' ); ?>
									</td>
									<td>
										<input name="ssd[ssd_title_hover_color]"
											value="<?php echo esc_attr( $ssd_title_hover_color ); ?>" type="text"
											class="ssd_cpa-color-picker">
									</td>
								</tr>
								<tr class="ssd_title_settings_tr disable_li">
									<td><i
											class="fa fa-lock"></i>&nbsp;<?php esc_html_e( 'Title Background Color', 'social-stream-design' ); ?>
									</td>
									<td>
										<input name="" value="" type="text" class="ssd_cpa-color-picker">
									</td>
								</tr>
								<tr class="ssd_title_settings_tr disable_li">
									<td class="ssd_label_fit_content"><i
											class="fa fa-lock"></i>&nbsp;<?php esc_html_e( 'Title Padding', 'social-stream-design' ); ?>
									</td>
									<td>
										<div class="ssd-col-xs-2">
											<div class="ssd_border_cover">
												<label><?php echo esc_html( 'Top' ); ?>&nbsp;(px)<br /></label>
												<input type="number" class="ssd_number_field" min="0" step="1" name=""
													value="20" />
											</div>
										</div>
										<div class="ssd-col-xs-2">
											<div class="ssd_border_cover">
												<label><?php echo esc_html( 'Bottom' ); ?>&nbsp;(px)<br /></label>
												<input type="number" class="ssd_number_field" min="0" step="1" name=""
													value="20" />
											</div>
										</div>
									</td>
								</tr>
								<tr class="ssd_title_settings_tr disable_li">
									<td class="ssd_label_fit_content"><i
											class="fa fa-lock"></i>&nbsp;<?php echo esc_html( 'Title Margin' ); ?></td>
									<td>
										<div class="ssd-col-xs-2">
											<div class="ssd_border_cover">
												<label><?php echo esc_html( 'Top' ); ?>&nbsp;(px)<br /></label>
												<input type="number" class="ssd_number_field" min="0" step="1" name=""
													value="0" />
											</div>
										</div>
										<div class="ssd-col-xs-2">
											<div class="ssd_border_cover">
												<label><?php echo esc_html( 'Bottom' ); ?>&nbsp;(px)<br /></label>
												<input type="number" class="ssd_number_field" min="0" step="1" name=""
													value="0" />
											</div>
										</div>
									</td>
								</tr>
								<tr class="ssd_title_settings_tr disable_li">
									<td><i
											class="fa fa-lock"></i>&nbsp;<?php esc_html_e( 'Title Font Weight', 'social-stream-design' ); ?>
									</td>
									<td>
										<div class="ssd-col-xs-4">
											<select disabled id="ssd_title_font_weight" class="ssd_stream_select"
												name="">
												<option value=""><?php echo '100'; ?></option>
												<option value=""><?php echo '200'; ?></option>
												<option value=""><?php echo '300'; ?></option>
												<option value=""><?php echo '400'; ?></option>
												<option value=""><?php echo '500'; ?></option>
												<option value=""><?php echo '600'; ?></option>
												<option value=""><?php echo '700'; ?></option>
												<option value=""><?php echo '800'; ?></option>
												<option value=""><?php echo '900'; ?></option>
												<option value=""><?php echo esc_html( 'Normal' ); ?></option>
												<option value=""><?php echo esc_html( 'Bold' ); ?></option>
											</select>
										</div>
									</td>
								</tr>
							</tbody>
						</table>
					</div>
				</div>
				<div id="contentsettings" class="ssds-set-box">
					<div class="inside">
						<table class="form-table">
							<tbody>
								<tr>
									<td><?php esc_html_e( 'Display Content', 'social-stream-design' ); ?></td>
									<td>
										<div class="radio-group">
											<input type="radio" id="ssd_display_content_yes"
												class="ssd_display_content yes" name="ssd[ssd_display_content]"
												value="1" <?php echo ( 1 == $ssd_display_content ) ? ' checked="checked"' : ''; ?>><label
												for="ssd_display_content_yes"><?php esc_html_e( 'Yes', 'social-stream-design' ); ?></label>
											<input type="radio" id="display_content_no" class="ssd_display_content no"
												name="ssd[ssd_display_content]" value="0" <?php echo ( 0 == $ssd_display_content ) ? ' checked="checked"' : ''; ?>><label
												for="display_content_no"><?php esc_html_e( 'No', 'social-stream-design' ); ?></label>
										</div>
									</td>
								</tr>

								<tr class="ssd_content_settings_tr">
									<td><?php esc_html_e( 'Content Word Limit', 'social-stream-design' ); ?></td>
									<td>
										<div class="ssd-col-xs-6">
											<input type="number" min="0" step="1" name="ssd[ssd_content_limit]"
												value="<?php echo isset( $ssd_settings['ssd_content_limit'] ) && '' !== $ssd_settings['ssd_content_limit'] ? esc_attr( $ssd_settings['ssd_content_limit'] ) : esc_attr( $ssd_content_limit ); ?>" />&nbsp;
										</div>
										<p class="description">
											<?php esc_html_e( 'Enter the number of words to display in content. Leave blank if you want to display whole content.', 'social-stream-design' ); ?>
										</p>
									</td>
								</tr>
								<tr class="ssd_content_settings_tr">
									<td>
										<?php
										esc_html_e( 'Content Font Size', 'social-stream-design' );
										echo ' (px)';
										?>
									</td>
									<td>
										<div class="ssd-col-xs-6">
											<input type="number" min="0" max="50" step="1" class="ssd_number_field"
												name="ssd[ssd_content_font_size]"
												value="<?php echo isset( $ssd_settings['ssd_content_font_size'] ) ? esc_attr( $ssd_settings['ssd_content_font_size'] ) : '14'; ?>" />
										</div>
									</td>
								</tr>
								<tr class="ssd_content_settings_tr">
									<td><?php esc_html_e( 'Content Color', 'social-stream-design' ); ?></td>
									<td>
										<input name="ssd[ssd_content_color]"
											value="<?php echo esc_attr( $ssd_content_color ); ?>" type="text"
											class="ssd_cpa-color-picker">
									</td>
								</tr>
								<!-- <tr class="ssd_content_settings_tr">
									<td><?php // esc_html_e( 'Content Link Color', 'social-stream-design' );. ?></td>
									<td>
										<input name="ssd[ssd_content_hover_color]" value="<?php // echo esc_attr( $ssd_content_hover_color );. ?>" type="text" class="ssd_cpa-color-picker">
									</td>
								</tr> -->
								<tr class="ssd_content_settings_tr disable_li">
									<td><i
											class="fa fa-lock"></i>&nbsp;<?php esc_html_e( 'Content Background Color', 'social-stream-design' ); ?>
									</td>
									<td>
										<input name="" value="" type="text" class="ssd_cpa-color-picker">
									</td>
								</tr>
								<tr class="ssd_content_settings_tr disable_li">
									<td class="ssd_label_fit_content"><i
											class="fa fa-lock"></i>&nbsp;<?php echo esc_html( 'Content Padding' ); ?>
									</td>
									<td>
										<div class="ssd-col-xs-2">
											<div class="ssd_border_cover">
												<label><?php echo esc_html( 'Top' ); ?>&nbsp;(px)<br /></label>
												<input type="number" class="ssd_number_field" min="0" step="1" name=""
													value="0" />
											</div>
										</div>
										<div class="ssd-col-xs-2">
											<div class="ssd_border_cover">
												<label><?php echo esc_html( 'Bottom' ); ?>&nbsp;(px)<br /></label>
												<input type="number" class="ssd_number_field" min="0" step="1" name=""
													value="0" />
											</div>
										</div>
									</td>
								</tr>
								<tr class="ssd_content_settings_tr disable_li">
									<td class="ssd_label_fit_content"><i
											class="fa fa-lock"></i>&nbsp;<?php echo esc_html( 'Content Margin' ); ?>
									</td>
									<td>
										<div class="ssd-col-xs-2">
											<div class="ssd_border_cover">
												<label><?php echo esc_html( 'Top' ); ?>&nbsp;(px)<br /></label>
												<input type="number" class="ssd_number_field" min="0" step="1" name=""
													value="0" />
											</div>
										</div>
										<div class="ssd-col-xs-2">
											<div class="ssd_border_cover">
												<label><?php echo esc_html( 'Bottom' ); ?>&nbsp;(px)<br /></label>
												<input type="number" class="ssd_number_field" min="0" step="1" name=""
													value="15" />
											</div>
										</div>
									</td>
								</tr>
							</tbody>
						</table>
					</div>
				</div>
				<div id="mediasettings" class="ssds-set-box">
					<div class="inside">
						<table class="form-table">
							<tbody>
								<tr>
									<td><?php esc_html_e( 'Display Feeds Without Media', 'social-stream-design' ); ?>
									</td>
									<td>
										<div class="radio-group">
											<input type="radio" id="ssd_display_feed_without_media_yes"
												class="ssd_display_feed_without_media yes"
												name="ssd[ssd_display_feed_without_media]" value="1" <?php echo ( 1 == $ssd_display_feed_without_media ) ? ' checked="checked"' : ''; ?>><label
												for="ssd_display_feed_without_media_yes"><?php esc_html_e( 'Yes', 'social-stream-design' ); ?></label>
											<input type="radio" id="ssd_display_feed_without_media_no"
												class="ssd_display_feed_without_media no"
												name="ssd[ssd_display_feed_without_media]" value="0" <?php echo ( 0 == $ssd_display_feed_without_media ) ? ' checked="checked"' : ''; ?>><label
												for="ssd_display_feed_without_media_no"><?php esc_html_e( 'No', 'social-stream-design' ); ?></label>
										</div>
										<p class="description">
											<?php esc_html_e( 'Show/Hide feeds which do not have the media.', 'social-stream-design' ); ?>
										</p>
									</td>
								</tr>
								<tr class="ssd-feeds-without-media-tr">
									<td><?php esc_html_e( 'Display Default Image', 'social-stream-design' ); ?></td>
									<td>
										<div class="radio-group">
											<input type="radio" id="display_default_image_yes"
												class="ssd_display_default_image yes"
												name="ssd[ssd_display_default_image]" value="1" <?php echo ( 1 == $ssd_display_default_image ) ? ' checked="checked"' : ''; ?>><label
												for="display_default_image_yes"><?php esc_html_e( 'Yes', 'social-stream-design' ); ?></label>
											<input type="radio" id="ssd_display_default_image_no"
												class="ssd_display_default_image no"
												name="ssd[ssd_display_default_image]" value="0" <?php echo ( 0 == $ssd_display_default_image ) ? ' checked="checked"' : ''; ?>><label
												for="ssd_display_default_image_no"><?php esc_html_e( 'No', 'social-stream-design' ); ?></label>
										</div>
										<p class="description">
											<?php esc_html_e( 'Enable default image for the feeds which does not have media', 'social-stream-design' ); ?>
										</p>
									</td>
								</tr>
								<tr class="ssd-default-image-tr">
									<td><?php esc_html_e( 'Upload Default Image', 'social-stream-design' ); ?></td>
									<td>
										<span class="ssd-default-image-holder">
											<?php
											if ( isset( $ssd_settings['ssd_default_image_src'] ) && '' !== $ssd_settings['ssd_default_image_src'] ) {
												echo '<img src="' . esc_url( $ssd_settings['ssd_default_image_src'] ) . '"/>';
											}
											?>
										</span>
										<?php
										if ( isset( $ssd_settings['ssd_default_image_src'] ) && '' !== $ssd_settings['ssd_default_image_src'] ) {
											?>
											<input class="button ssd-remove-image-button"
												value="<?php esc_html_e( 'Remove Image', 'social-stream-design' ); ?>"
												type="button">
											<?php
										} else {
											?>
											<input class="button ssd-upload-image-button"
												value="<?php esc_html_e( 'Upload Image', 'social-stream-design' ); ?>"
												type="button">
											<?php
										}
										?>
										<input name="ssd[ssd_default_image_id]" id="ssd_default_image_id"
											value="<?php echo isset( $ssd_settings['ssd_default_image_id'] ) ? esc_attr( $ssd_settings['ssd_default_image_id'] ) : ''; ?>"
											type="hidden">
										<input name="ssd[ssd_default_image_src]" id="ssd_default_image_src"
											value="<?php echo isset( $ssd_settings['ssd_default_image_src'] ) ? esc_url( $ssd_settings['ssd_default_image_src'] ) : ''; ?>"
											type="hidden">
										<p class="description">
											<?php esc_html_e( 'Upload default image', 'social-stream-design' ); ?></p>
									</td>
								</tr>
								<tr class="disable_li">
									<td class="ssd_label_fit_content"><i
											class="fa fa-lock"></i>&nbsp;<?php esc_html_e( 'Margin', 'social-stream-design' ); ?>
									</td>
									<td>
										<div class="ssd-col-xs-2">
											<div class="ssd_border_cover">
												<label><?php echo esc_html( 'Top' ); ?>&nbsp;(px)<br /></label>
												<input disabled type="number" class="ssd_number_field" min="0" step="1"
													name="" value="0" />
											</div>
										</div>
										<div class="ssd-col-xs-2 ssd-media-margin-bottom">
											<div class="ssd_border_cover">
												<label><?php echo esc_html( 'Bottom' ); ?>&nbsp;(px)<br /></label>
												<input disabled type="number" class="ssd_number_field" min="0" step="1"
													name="" value="20" />
											</div>
										</div>
									</td>
								</tr>
								<tr class="disable_li">
									<td class="ssd_label_fit_content"><i
											class="fa fa-lock"></i>&nbsp;<?php esc_html_e( 'Display Gallery Slider Arrows', 'social-stream-design' ); ?>
									</td>
									<td>
										<div class="radio-group">
											<input type="radio" id="display_media_slider_nav_yes"
												class="display_media_slider_nav yes" name="" value="1"><label
												for="display_media_slider_nav_yes"><?php esc_html_e( 'Yes', 'social-stream-design' ); ?></label>
											<input type="radio" id="display_media_slider_nav_no"
												class="display_media_slider_nav no" name="" value="0"
												checked="checked"><label
												for="display_media_slider_nav_no"><?php esc_html_e( 'No', 'social-stream-design' ); ?></label>
										</div>
										<p class="description">
											<?php esc_html_e( 'Show/Hide Slider navigation icons for media gallery. This option is only work for Instagram feeds.', 'social-stream-design' ); ?>
										</p>
									</td>
								</tr>
								<tr class="disable_li">
									<td class="ssd_label_fit_content"><i
											class="fa fa-lock"></i>&nbsp;<?php esc_html_e( 'Gallery Slider Autoplay', 'social-stream-design' ); ?>
									</td>
									<td>
										<div class="radio-group">
											<input type="radio" id="ssd_display_media_autoplay_yes"
												class="ssd_display_media_autoplay yes" name="" value="1"><label
												for="ssd_display_media_autoplay_yes"><?php esc_html_e( 'Yes', 'social-stream-design' ); ?></label>
											<input type="radio" id="display_media_autoplay_no"
												class="ssd_display_media_autoplay no" name="" value="0"
												checked="checked"><label
												for="display_media_autoplay_no"><?php esc_html_e( 'No', 'social-stream-design' ); ?></label>
										</div>
										<p class="description">
											<?php esc_html_e( 'Show/Hide slider autoplay. This option is only work for Instagram feeds.', 'social-stream-design' ); ?>
										</p>
									</td>
								</tr>
								<tr class="disable_li">
									<td class="ssd_label_fit_content"><i
											class="fa fa-lock"></i>&nbsp;<?php esc_html_e( 'Enter Gallery slider autoplay intervals', 'social-stream-design' ); ?>
										(ms)</td>
									<td>
										<input disabled type="number" class="ssd_number_field" min="0" max="1000"
											step="1" name="" value="1000" />
										<p class="description">
											<?php esc_html_e( 'Enter autoplay interval time for gallery slider in ms. This option is only work for Instagram feeds.', 'social-stream-design' ); ?>
										</p>
									</td>
								</tr>
								<tr class="disable_li">
									<td class="ssd_label_fit_content"><i
											class="fa fa-lock"></i>&nbsp;<?php esc_html_e( 'Gallery Slider Speed', 'social-stream-design' ); ?>
										(ms)</td>
									<td>
										<input disabled type="number" class="ssd_number_field" min="0" max="1000"
											step="1" name="" value="1000" />
										<p class="description">
											<?php esc_html_e( 'Enter gallery slider speed in ms. This option is only work for Instagram feeds.', 'social-stream-design' ); ?>
										</p>
									</td>
								</tr>
							</tbody>
						</table>
					</div>
				</div>
				<div id="authorsettings" class="ssds-set-box">
					<div class="inside">
						<table class="form-table">
							<tbody>
								<tr>
									<td><?php esc_html_e( 'Display Author Box', 'social-stream-design' ); ?></td>
									<td>
										<div class="radio-group">
											<input type="radio" id="ssd_display_author_box_yes"
												class="ssd_display_author_box yes" name="ssd[ssd_display_author_box]"
												value="1" <?php echo ( 1 == $ssd_display_author_box ) ? ' checked="checked"' : ''; ?>><label
												for="ssd_display_author_box_yes"><?php esc_html_e( 'Yes', 'social-stream-design' ); ?></label>
											<input type="radio" id="display_author_box_no"
												class="ssd_display_author_box no" name="ssd[ssd_display_author_box]"
												value="0" <?php echo ( 0 == $ssd_display_author_box ) ? ' checked="checked"' : ''; ?>><label
												for="display_author_box_no"><?php esc_html_e( 'No', 'social-stream-design' ); ?></label>
										</div>
									</td>
								</tr>
								<tr class="ssd_author_row">
									<td><?php esc_html_e( 'Author Image Border Radius', 'social-stream-design' ); ?>
									</td>
									<td>
										<div class="ssd-col-xs-2">
											<div class="ssd_border_cover">
												<input type="number" class="ssd_number_field" min="0" step="1"
													name="ssd[ssd_author_border_radius]"
													value="<?php echo isset( $ssd_settings['ssd_author_border_radius'] ) && '' !== $ssd_settings['ssd_author_border_radius'] ? esc_attr( $ssd_settings['ssd_author_border_radius'] ) : '0'; ?>" />
											</div>
										</div>
										<div class="ssd-col-xs-5">
											<div class="ssd_border_cover">
												<select id="ssd_author_border_radius_type" class="ssd_stream_select"
													name="ssd[ssd_author_border_radius_type]">
													<option value="px" <?php echo ( 'px' === $ssd_author_border_radius_type ) ? ' selected="selected"' : ''; ?>><?php echo 'px'; ?></option>
													<option value="%" <?php echo ( '%' === $ssd_author_border_radius_type ) ? ' selected="selected"' : ''; ?>><?php echo 'percentage'; ?>
													</option>
												</select>
											</div>
										</div>
									</td>
								</tr>
								<tr class="ssd_author_row disable_li">
									<td><i
											class="fa fa-lock"></i>&nbsp;<?php esc_html_e( 'Author Box Background Color', 'social-stream-design' ); ?>
									</td>
									<td>
										<input name="" value="" type="text" class="ssd_cpa-color-picker">
									</td>
								</tr>
								<tr class="ssd_author_row">
									<td><?php esc_html_e( 'Author Box Title Color', 'social-stream-design' ); ?></td>
									<td>
										<input name="ssd[ssd_author_title_color]"
											value="<?php echo esc_attr( $ssd_author_title_color ); ?>" type="text"
											class="ssd_cpa-color-picker">
									</td>
								</tr>
								<tr class="ssd_author_row">
									<td><?php esc_html_e( 'Author Box Hover Title Color', 'social-stream-design' ); ?>
									</td>
									<td>
										<input name="ssd[ssd_author_title_hover_color]"
											value="<?php echo esc_attr( $ssd_author_title_hover_color ); ?>" type="text"
											class="ssd_cpa-color-picker">
									</td>
								</tr>
								<tr class="ssd_author_row">
									<td><?php esc_html_e( 'Author Box Meta Color', 'social-stream-design' ); ?></td>
									<td>
										<input name="ssd[ssd_author_meta_color]"
											value="<?php echo esc_attr( $ssd_author_meta_color ); ?>" type="text"
											class="ssd_cpa-color-picker">
									</td>
								</tr>
								<tr class="ssd_author_row disable_li">
									<td class="ssd_label_fit_content"><i
											class="fa fa-lock"></i>&nbsp;<?php esc_html_e( 'Author Box Padding', 'social-stream-design' ); ?>
									</td>
									<td>
										<div class="ssd-col-xs-2">
											<div class="ssd_border_cover">
												<label><?php echo esc_html( 'Top' ); ?>&nbsp;(px)<br /></label>
												<input type="number" class="ssd_number_field" min="0" step="1" name=""
													value="0" />
											</div>
										</div>
										<div class="ssd-col-xs-2">
											<div class="ssd_border_cover">
												<label><?php echo esc_html( 'Bottom' ); ?>&nbsp;(px)<br /></label>
												<input type="number" class="ssd_number_field" min="0" step="1" name=""
													value="0" />
											</div>
										</div>
									</td>
								</tr>
								<tr class="ssd_author_row disable_li">
									<td class="ssd_label_fit_content"><i
											class="fa fa-lock"></i>&nbsp;<?php esc_html_e( 'Author Box Margin', 'social-stream-design' ); ?>
									</td>
									<td>
										<div class="ssd-col-xs-2">
											<div class="ssd_border_cover">
												<label><?php echo esc_html( 'Top' ); ?>&nbsp;(px)<br /></label>
												<input type="number" class="ssd_number_field" min="0" step="1" name=""
													value="0" />
											</div>
										</div>
										<div class="ssd-col-xs-2 ssd-author-margin-bottom">
											<div class="ssd_border_cover">
												<label><?php echo esc_html( 'Bottom' ); ?>&nbsp;(px)<br /></label>
												<input type="number" class="ssd_number_field" min="-40" step="1" name=""
													value="20" />
											</div>
										</div>
									</td>
								</tr>
								<tr class="ssd_author_row">
									<td><?php esc_html_e( 'Display Author Screen Name', 'social-stream-design' ); ?>
									</td>
									<td>
										<div class="radio-group">
											<input type="radio" id="ssd_view_user_name_yes" class="view_user_name yes"
												name="ssd[ssd_view_user_name]" value="1" <?php echo ( 1 == $ssd_view_user_name ) ? ' checked="checked"' : ''; ?>><label
												for="ssd_view_user_name_yes"><?php esc_html_e( 'Yes', 'social-stream-design' ); ?></label>
											<input type="radio" id="view_user_name_no" class="view_user_name no"
												name="ssd[ssd_view_user_name]" value="0" <?php echo ( 0 == $ssd_view_user_name ) ? ' checked="checked"' : ''; ?>><label
												for="view_user_name_no"><?php esc_html_e( 'No', 'social-stream-design' ); ?></label>
										</div>
									</td>
								</tr>
								<tr class="ssd_author_row">
									<td><?php esc_html_e( 'Display Time', 'social-stream-design' ); ?></td>
									<td>
										<div class="radio-group">
											<input type="radio" id="ssd_view_date_yes" class="yes"
												name="ssd[ssd_view_date]" value="1" <?php echo ( 1 == $ssd_date ) ? ' checked="checked"' : ''; ?>><label
												for="ssd_view_date_yes"><?php esc_html_e( 'Yes', 'social-stream-design' ); ?></label>
											<input type="radio" id="view_date_no" class="no" name="ssd[ssd_view_date]"
												value="0" <?php echo ( 0 == $ssd_date ) ? ' checked="checked"' : ''; ?>><label
												for="view_date_no"><?php esc_html_e( 'No', 'social-stream-design' ); ?></label>
										</div>
									</td>
								</tr>
							</tbody>
						</table>
					</div>
				</div>
				<div id="ssd_countsettings" class="ssds-set-box">
					<div class="inside">
						<table class="form-table">
							<tbody>
								<tr class="ssd_count_meta_color_main">
									<td><?php esc_html_e( 'Color', 'social-stream-design' ); ?></td>
									<td>
										<input name="ssd[ssd_count_meta_color]"
											value="<?php echo esc_attr( $ssd_count_meta_color ); ?>" type="text"
											class="ssd_cpa-color-picker">
									</td>
								</tr>
								<tr class="disable_li">
									<td><i
											class="fa fa-lock"></i>&nbsp;<?php esc_html_e( 'Background Color', 'social-stream-design' ); ?>
									</td>
									<td>
										<input name="" value="" type="text" class="ssd_cpa-color-picker">
									</td>
								</tr>
								<tr class="disable_li">
									<td><i
											class="fa fa-lock"></i>&nbsp;<?php esc_html_e( 'Hover Color', 'social-stream-design' ); ?>
									</td>
									<td>
										<input name="" value="" type="text" class="ssd_cpa-color-picker">
									</td>
								</tr>
								<tr class="disable_li">
									<td class="ssd_label_fit_content"><i
											class="fa fa-lock"></i>&nbsp;<?php echo esc_html( 'Border' ); ?></td>
									<td>
										<div class="ssd_border_cover">
											<label><?php echo esc_html( 'Top' ); ?>&nbsp;(px)<br /></label>
											<div class="ssd-col-xs-2">
												<input type="number" class="ssd_number_field" min="0" step="1" name=""
													value="1" />
											</div>
											<div class="ssd-col-xs-2">
												<select class="ssd_stream_select" name="">
													<option value=""><?php echo esc_html( 'Solid' ); ?></option>
													<option value=""><?php echo esc_html( 'Dotted' ); ?></option>
													<option value=""><?php echo esc_html( 'Dash' ); ?></option>
													<option value=""><?php echo esc_html( 'Double' ); ?></option>
												</select>
											</div>
											<div class="ssd-col-xs-4">
												<input name="" value="" type="text" class="ssd_cpa-color-picker">
											</div>
										</div>
										<div class="ssd_border_cover">
											<label><?php echo esc_html( 'Bottom' ); ?>&nbsp;(px)<br /></label>
											<div class="ssd-col-xs-2">
												<input type="number" class="ssd_number_field" min="0" step="1" name=""
													value="0" />
											</div>
											<div class="ssd-col-xs-2">
												<select class="ssd_stream_select" name="">
													<option value=""><?php echo esc_html( 'Solid' ); ?></option>
													<option value=""><?php echo esc_html( 'Dotted' ); ?></option>
													<option value=""><?php echo esc_html( 'Dash' ); ?></option>
													<option value=""><?php echo esc_html( 'Double' ); ?></option>
												</select>
											</div>
											<div class="ssd-col-xs-4">
												<input name="" value="" type="text" class="ssd_cpa-color-picker">
											</div>
										</div>

										<div class="ssd_border_cover">
											<label><?php echo esc_html( 'Right' ); ?>&nbsp;(px)<br /></label>
											<div class="ssd-col-xs-2">
												<input type="number" class="ssd_number_field" min="0" step="1" name=""
													value="0" />
											</div>
											<div class="ssd-col-xs-2">
												<select class="ssd_stream_select" name="">
													<option value=""><?php echo esc_html( 'Solid' ); ?></option>
													<option value=""><?php echo esc_html( 'Dotted' ); ?></option>
													<option value=""><?php echo esc_html( 'Dash' ); ?></option>
													<option value=""><?php echo esc_html( 'Double' ); ?></option>
												</select>
											</div>
											<div class="ssd-col-xs-4">
												<input name="" value="" type="text" class="ssd_cpa-color-picker">
											</div>
										</div>

										<div class="ssd_border_cover">
											<label><?php echo esc_html( 'Left' ); ?>&nbsp;(px)<br /></label>
											<div class="ssd-col-xs-2">
												<input type="number" class="ssd_number_field" min="0" step="1" name=""
													value="0" />
											</div>
											<div class="ssd-col-xs-2">
												<select class="ssd_stream_select" name="">
													<option value=""><?php echo esc_html( 'Solid' ); ?></option>
													<option value=""><?php echo esc_html( 'Dotted' ); ?></option>
													<option value=""><?php echo esc_html( 'Dash' ); ?></option>
													<option value=""><?php echo esc_html( 'Double' ); ?></option>
												</select>
											</div>
											<div class="ssd-col-xs-4">
												<input name="" value="" type="text" class="ssd_cpa-color-picker">
											</div>
										</div>

									</td>
								</tr>
								<tr class="disable_li">
									<td class="ssd_label_fit_content"><i
											class="fa fa-lock"></i>&nbsp;<?php esc_html_e( 'Bar Padding', 'social-stream-design' ); ?>
									</td>
									<td>
										<div class="ssd-col-xs-2">
											<div class="ssd_border_cover">
												<label><?php echo esc_html( 'Top' ); ?>&nbsp;(px)<br /></label>
												<input type="number" class="ssd_number_field" min="0" step="1" name=""
													value="0" />
											</div>
										</div>
										<div class="ssd-col-xs-2">
											<div class="ssd_border_cover">
												<label><?php echo esc_html( 'Bottom' ); ?>&nbsp;(px)<br /></label>
												<input type="number" class="ssd_number_field" min="0" step="1" name=""
													value="0" />
											</div>
										</div>
									</td>
								</tr>
								<tr class="disable_li">
									<td class="ssd_label_fit_content"><i
											class="fa fa-lock"></i>&nbsp;<?php esc_html_e( 'Bar Margin', 'social-stream-design' ); ?>
									</td>
									<td>
										<div class="ssd-col-xs-2">
											<div class="ssd_border_cover">
												<label><?php echo esc_html( 'Top' ); ?>&nbsp;(px)<br /></label>
												<input type="number" class="ssd_number_field" min="0" step="1"
													name="ssd[ssd_count_margin_top]"
													value="<?php echo esc_attr( $ssd_count_margin_top ); ?>" />
											</div>
										</div>
										<div class="ssd-col-xs-2">
											<div class="ssd_border_cover">
												<label><?php echo esc_html( 'Bottom' ); ?>&nbsp;(px)<br /></label>
												<input type="number" class="ssd_number_field" min="0" step="1"
													name="ssd[ssd_count_margin_bottom]"
													value="<?php echo esc_attr( $ssd_count_margin_bottom ); ?>" />
											</div>
										</div>
									</td>
								</tr>
								<tr class="ssd_user_follower_count_tr">
									<td><?php esc_html_e( 'Display User Followers', 'social-stream-design' ); ?></td>
									<td>
										<div class="radio-group">
											<input type="radio" id="ssd_user_follower_count_yes"
												class="user_follower_count yes" name="ssd[ssd_user_follower_count]"
												value="1" <?php echo ( 1 == $ssd_user_follower_count ) ? ' checked="checked"' : ''; ?>><label
												for="ssd_user_follower_count_yes"><?php esc_html_e( 'Yes', 'social-stream-design' ); ?></label>
											<input type="radio" id="ssd_user_follower_count_no"
												class="user_follower_count no" name="ssd[ssd_user_follower_count]"
												value="0" <?php echo ( 0 == $ssd_user_follower_count ) ? ' checked="checked"' : ''; ?>><label
												for="ssd_user_follower_count_no"><?php esc_html_e( 'No', 'social-stream-design' ); ?></label>
										</div>
									</td>
								</tr>
								<tr class="ssd_user_friend_count_tr">
									<td><?php esc_html_e( 'Display User Friends', 'social-stream-design' ); ?></td>
									<td>
										<div class="radio-group">
											<input type="radio" id="ssd_user_friend_count_yes"
												class="user_friend_count yes" name="ssd[ssd_user_friend_count]"
												value="1" <?php echo ( 1 == $ssd_user_friend_count ) ? ' checked="checked"' : ''; ?>><label
												for="ssd_user_friend_count_yes"><?php esc_html_e( 'Yes', 'social-stream-design' ); ?></label>
											<input type="radio" id="ssd_user_friend_count_no"
												class="user_friend_count no" name="ssd[ssd_user_friend_count]" value="0"
												<?php echo ( 0 == $ssd_user_friend_count ) ? ' checked="checked"' : ''; ?>><label
												for="ssd_user_friend_count_no"><?php esc_html_e( 'No', 'social-stream-design' ); ?></label>
										</div>
									</td>
								</tr>
								<tr class="ssd_retweet_count_tr">
									<td><?php esc_html_e( 'Display Retweet Count', 'social-stream-design' ); ?></td>
									<td>
										<div class="radio-group">
											<input type="radio" id="ssd_retweet_count_yes" class="retweet_count yes"
												name="ssd[ssd_retweet_count]" value="1" <?php echo ( 1 == $ssd_retweet_count ) ? ' checked="checked"' : ''; ?>><label
												for="ssd_retweet_count_yes"><?php esc_html_e( 'Yes', 'social-stream-design' ); ?></label>
											<input type="radio" id="ssd_retweet_count_no" class="retweet_count no"
												name="ssd[ssd_retweet_count]" value="0" <?php echo ( 0 == $ssd_retweet_count ) ? ' checked="checked"' : ''; ?>><label
												for="ssd_retweet_count_no"><?php esc_html_e( 'No', 'social-stream-design' ); ?></label>
										</div>
									</td>
								</tr>
								<tr class="ssd_reply_count_tr">
									<td><?php esc_html_e( 'Display Reply Count', 'social-stream-design' ); ?></td>
									<td>
										<div class="radio-group">
											<input type="radio" id="ssd_reply_count_yes" class="reply_count yes"
												name="ssd[ssd_reply_link]" value="1" <?php echo ( 1 == $ssd_reply_link ) ? ' checked="checked"' : ''; ?>><label
												for="ssd_reply_count_yes"><?php esc_html_e( 'Yes', 'social-stream-design' ); ?></label>
											<input type="radio" id="ssd_reply_count_no" class="reply_count no"
												name="ssd[ssd_reply_link]" value="0" <?php echo ( 0 == $ssd_reply_link ) ? ' checked="checked"' : ''; ?>><label
												for="ssd_reply_count_no"><?php esc_html_e( 'No', 'social-stream-design' ); ?></label>
										</div>
									</td>
								</tr>
								<tr class="ssd_favorite_count_tr">
									<td><?php esc_html_e( 'Display Favorite Count', 'social-stream-design' ); ?></td>
									<td>
										<div class="radio-group">
											<input type="radio" id="ssd_favorite_count_yes" class="favorite_count yes"
												name="ssd[ssd_favorite_count]" value="1" <?php echo ( 1 == $ssd_favorite_count ) ? ' checked="checked"' : ''; ?>><label
												for="ssd_favorite_count_yes"><?php esc_html_e( 'Yes', 'social-stream-design' ); ?></label>
											<input type="radio" id="ssd_favorite_count_no" class="favorite_count no"
												name="ssd[ssd_favorite_count]" value="0" <?php echo ( 0 == $ssd_favorite_count ) ? ' checked="checked"' : ''; ?>><label
												for="ssd_favorite_count_no"><?php esc_html_e( 'No', 'social-stream-design' ); ?></label>
										</div>
									</td>
								</tr>
								<tr class="ssd_view_count_tr">
									<td><?php esc_html_e( 'Display Views Count', 'social-stream-design' ); ?></td>
									<td>
										<div class="radio-group">
											<input type="radio" id="ssd_view_count_yes" class="view_count yes"
												name="ssd[ssd_view_count]" value="1" <?php echo ( 1 == $ssd_view_count ) ? ' checked="checked"' : ''; ?>><label
												for="ssd_view_count_yes"><?php esc_html_e( 'Yes', 'social-stream-design' ); ?></label>
											<input type="radio" id="ssd_view_count_no" class="view_count no"
												name="ssd[ssd_view_count]" value="0" <?php echo ( 0 == $ssd_view_count ) ? ' checked="checked"' : ''; ?>><label
												for="ssd_view_count_no"><?php esc_html_e( 'No', 'social-stream-design' ); ?></label>
										</div>
									</td>
								</tr>
								<tr class="ssd_like_count_tr">
									<td><?php esc_html_e( 'Display Likes Count', 'social-stream-design' ); ?></td>
									<td>
										<div class="radio-group">
											<input type="radio" id="ssd_like_count_yes" class="like_count yes"
												name="ssd[ssd_like_count]" value="1" <?php echo ( 1 == $ssd_like_count ) ? ' checked="checked"' : ''; ?>><label
												for="ssd_like_count_yes"><?php esc_html_e( 'Yes', 'social-stream-design' ); ?></label>
											<input type="radio" id="ssd_like_count_no" class="ssd_like_count_no no"
												name="ssd[ssd_like_count]" value="0" <?php echo ( 0 == $ssd_like_count ) ? ' checked="checked"' : ''; ?>><label
												for="ssd_like_count_no"><?php esc_html_e( 'No', 'social-stream-design' ); ?></label>
										</div>
									</td>
								</tr>
								<tr class="ssd_pin_count_tr">
									<td><?php esc_html_e( 'Display Pin Count', 'social-stream-design' ); ?></td>
									<td>
										<div class="radio-group">
											<input type="radio" id="pin_count_yes" class="pin_count yes"
												name="ssd[ssd_pin_count]" value="1" <?php echo ( 1 == $ssd_pin_count ) ? ' checked="checked"' : ''; ?>><label
												for="pin_count_yes"><?php esc_html_e( 'Yes', 'social-stream-design' ); ?></label>
											<input type="radio" id="pin_count_no" class="pin_count_no no"
												name="ssd[ssd_pin_count]" value="0" <?php echo ( 0 == $ssd_pin_count ) ? ' checked="checked"' : ''; ?>><label
												for="pin_count_no"><?php esc_html_e( 'No', 'social-stream-design' ); ?></label>
										</div>
									</td>
								</tr>
								<tr class="ssd_dislike_count_tr">
									<td><?php esc_html_e( 'Display Dislikes Count', 'social-stream-design' ); ?></td>
									<td>
										<div class="radio-group">
											<input type="radio" id="ssd_dislike_count_yes" class="dislike_count yes"
												name="ssd[ssd_dislike_count]" value="1" <?php echo ( 1 == $ssd_dislike_count ) ? ' checked="checked"' : ''; ?>><label
												for="ssd_dislike_count_yes"><?php esc_html_e( 'Yes', 'social-stream-design' ); ?></label>
											<input type="radio" id="ssd_dislike_count_no" class="dislike_count no"
												name="ssd[ssd_dislike_count]" value="0" <?php echo ( 0 == $ssd_dislike_count ) ? ' checked="checked"' : ''; ?>><label
												for="ssd_dislike_count_no"><?php esc_html_e( 'No', 'social-stream-design' ); ?></label>
										</div>
									</td>
								</tr>
								<tr class="ssd_comment_count_tr">
									<td><?php esc_html_e( 'Display Comments Count', 'social-stream-design' ); ?></td>
									<td>
										<div class="radio-group">
											<input type="radio" id="ssd_comment_count_yes" class="comment_count yes"
												name="ssd[ssd_comment_count]" value="1" <?php echo ( 1 == $ssd_comment_count ) ? ' checked="checked"' : ''; ?>><label
												for="ssd_comment_count_yes"><?php esc_html_e( 'Yes', 'social-stream-design' ); ?></label>
											<input type="radio" id="ssd_comment_count_no" class="comment_count no"
												name="ssd[ssd_comment_count]" value="0" <?php echo ( 0 == $ssd_comment_count ) ? ' checked="checked"' : ''; ?>><label
												for="ssd_comment_count_no"><?php esc_html_e( 'No', 'social-stream-design' ); ?></label>
										</div>
									</td>
								</tr>
								<tr class="ssd_share_count_tr">
									<td><?php esc_html_e( 'Display Share Count', 'social-stream-design' ); ?></td>
									<td>
										<div class="radio-group">
											<input type="radio" id="ssd_share_count_yes" class="share_count yes"
												name="ssd[ssd_share_count]" value="1" <?php echo ( 1 == $ssd_share_count ) ? ' checked="checked"' : ''; ?>><label
												for="ssd_share_count_yes"><?php esc_html_e( 'Yes', 'social-stream-design' ); ?></label>
											<input type="radio" id="ssd_share_count_no" class="share_count no"
												name="ssd[ssd_share_count]" value="0" <?php echo ( 0 == $ssd_share_count ) ? ' checked="checked"' : ''; ?>><label
												for="ssd_share_count_no"><?php esc_html_e( 'No', 'social-stream-design' ); ?></label>
										</div>
									</td>
								</tr>
							</tbody>
						</table>
					</div>
				</div>
				<div id="paginationsettings" class="ssds-set-box">
					<div class="inside">
						<table class="form-table">
							<tbody>
								<tr class="ssd_social_stream_pagination_tr">
									<td><?php esc_html_e( 'Pagination Type', 'social-stream-design' ); ?></td>
									<td>
										<div class="ssd-col-xs-5">
											<select id="ssd_pagination_type"
												class="ssd_pagination_type ssd_stream_select"
												name="ssd[ssd_pagination_type]">
												<option value="no_pagination" <?php echo ( 'no_pagination' === $ssd_pagination_type ) ? ' selected="selected"' : ''; ?>>
													<?php esc_html_e( 'No Pagination', 'social-stream-design' ); ?>
												</option>
												<option value="" disabled>
													<?php esc_html_e( 'Paged', 'social-stream-design' ); ?></option>
												<option value="load_more_btn" <?php echo ( 'load_more_btn' === $ssd_pagination_type ) ? ' selected="selected"' : ''; ?>>
													<?php esc_html_e( 'Load More Button', 'social-stream-design' ); ?>
												</option>
												<option value="" disabled>
													<?php esc_html_e( 'Load On Page Scroll', 'social-stream-design' ); ?>
												</option>
											</select>
										</div>
										<p class="description">
											<?php esc_html_e( 'Select pagination type', 'social-stream-design' ); ?></p>
									</td>
								</tr>
								<tr class="ssd_social_stream_pagination_tr ssd_load_more_template_tr disable_li">
									<td><i
											class="fa fa-lock"></i>&nbsp;<?php esc_html_e( 'Load More Button Layout', 'social-stream-design' ); ?>
									</td>
									<td>
										<div class="ssd-col-xs-4">
											<select id="ssd_load_more_layout"
												class="ssd_load_more_layout ssd_stream_select"
												name="ssd[ssd_load_more_layout]">
												<option value="template-1" <?php echo ( 'template-1' === $ssd_load_more_layout ) ? ' selected="selected"' : ''; ?>>
													<?php esc_html_e( 'Template', 'social-stream-design' ); ?>&nbsp;1
												</option>
												<option value="" disabled>
													<?php esc_html_e( 'Template', 'social-stream-design' ); ?>&nbsp;2
												</option>
												<option value="" disabled>
													<?php esc_html_e( 'Template', 'social-stream-design' ); ?>&nbsp;3
												</option>
											</select>
										</div>
										<p class="description">
											<?php esc_html_e( 'Select load more button layout', 'social-stream-design' ); ?>
										</p>
									</td>
								</tr>
								<tr class="ssd_social_stream_pagination_tr ssd_load_more_template_preview disable_li">
									<td></td>
									<td>
										<div class="ssd_load_more_btn">
											<button
												class="<?php echo esc_attr( $ssd_load_more_layout ); ?> ssd-button ssd-black ssd-margin-bottom"
												disabled=""><?php esc_html_e( 'Load More', 'social-stream-design' ); ?></button>
										</div>
									</td>
								</tr>
								<tr class="ssd_social_stream_pagination_tr ssd_load_more_effect_tr disable_li">
									<td><i
											class="fa fa-lock"></i>&nbsp;<?php esc_html_e( 'Load More Effect', 'social-stream-design' ); ?>
									</td>
									<td>
										<select id="ssd_load_more_effect" class="ssd_load_more_effect ssd_stream_select"
											name="">
											<option value="eff-fadein"><?php echo esc_html( 'FadeIn' ); ?></option>
											<option value="eff-move-up"><?php echo esc_html( 'Move Up' ); ?></option>
											<option value="eff-scale-up"><?php echo esc_html( 'Scale Up' ); ?></option>
											<option value="eff-fall-perspective">
												<?php echo esc_html( 'Fall Perspective' ); ?></option>
											<option value="eff-fly"><?php echo esc_html( 'Fly' ); ?></option>
											<option value="eff-flip"><?php echo esc_html( 'Flip' ); ?></option>
											<option value="eff-helix"><?php echo esc_html( 'Helix' ); ?></option>
											<option value="eff-popup"><?php echo esc_html( 'PopUp' ); ?></option>
										</select>
										<p class="description">
											<?php esc_html_e( 'Select load more effect', 'social-stream-design' ); ?>
										</p>
									</td>
								</tr>
								<tr class="ssd_social_stream_pagination_tr ssd_post_per_page_tr">
									<td><?php esc_html_e( 'Number Of Posts To Display Per Page', 'social-stream-design' ); ?>
									</td>
									<td>
										<div class="ssd-col-xs-2">
											<input type="number" step="1" min="0" id="no_of_posts_per_page"
												class="no_of_posts_per_page ssd_number_field"
												name="ssd[ssd_no_of_posts_per_page]"
												value="<?php echo esc_attr( $ssd_no_of_posts_per_page ); ?>">
										</div>
										<p class="description">
											<b><?php esc_html_e( 'Note', 'social-stream-design' ); ?></b> :
											<?php esc_html_e( 'Select number of feeds you want to display in single page', 'social-stream-design' ); ?>
										</p>
									</td>
								</tr>
								<tr class="ssd_search_post_per_page_tr">
									<td><?php esc_html_e( 'Maximum Posts to Display', 'social-stream-design' ); ?></td>
									<td>
										<div class="ssd-col-xs-2">
											<input type="number" step="1" min="-1" id="ssd_no_of_posts"
												class="ssd_no_of_posts ssd_number_field" name="ssd[ssd_no_of_posts]"
												value="<?php echo esc_attr( $ssd_no_of_posts ); ?>">
										</div>
										<p class="description">
											<b><?php esc_html_e( 'Note', 'social-stream-design' ); ?></b> :
											<?php esc_html_e( "Select '-1' if you want to display all the feeds.", 'social-stream-design' ); ?>
										</p>
									</td>
								</tr>
							</tbody>
						</table>
					</div>
				</div>
				<div id="ssd_popupsettings" class="ssds-set-box">
					<div class="inside">
						<table class="form-table">
							<tbody>
								<tr class="disable_li">
									<td><i
											class="fa fa-lock"></i>&nbsp;<?php esc_html_e( 'Popup open on content click', 'social-stream-design' ); ?>
									</td>
									<td>
										<div class="radio-group">
											<input type="radio" id="ssd_popup_view_on_content_click_yes"
												class="ssd_view_on_content_click yes" name="" value=""><label
												for="ssd_popup_view_on_content_click_yes"><?php esc_html_e( 'Yes', 'social-stream-design' ); ?></label>
											<input type="radio" id="ssd_popup_view_on_content_click_no"
												class="ssd_view_on_content_click no" name="" value="0"
												checked="checked"><label
												for="ssd_popup_view_on_content_click_no"><?php esc_html_e( 'No', 'social-stream-design' ); ?></label>
										</div>
										<p class="description">
											<?php esc_html_e( 'Show/Hide popup on click of content', 'social-stream-design' ); ?>
										</p>
									</td>
								</tr>
							</tbody>
						</table>
					</div>
				</div>
			</div>
		</form>
		<div id="ssd-advertisement-popup">
			<div class="ssd-advertisement-cover">
				<a class="ssd-advertisement-link" target="_blank"
					href="<?php echo esc_url( 'https://codecanyon.net/item/social-stream-design/26344658?irgwc=1&clickid=UtXV9eXjuxyJW5zwUx0Mo3QzUki2HBxlq3kkwU0&iradid=275988&irpid=1195590&iradtype=ONLINE_TRACKING_LINK&irmptype=mediapartner&mp_value1=&utm_campaign=af_impact_radius_1195590&utm_medium=affiliate&utm_source=impact_radius' ); ?>">
					<img
						src="<?php echo esc_url( WPSOCIALSTREAMDESIGNER_URL ) . '/images/Social-stream-designer-wordpress-plugin.jpg'; ?>" />
				</a>
			</div>
		</div>
	</div><!-- ssd-screen end -->
</div><!-- wrap end-->
