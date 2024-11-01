<?php
	/**
	 * Style file
	 *
	 * @version 1.0
	 * @package WP Social Stream Designer
	 */

	$rand          = '_' . $shortcode_id;
	$layout_object = new WpSSDLayoutsActions();
	$layout_object->init();
	$layout_object->id             = $shortcode_id;
	$layout_settings               = $layout_object->ssd_get_layout_detail();
	$social_stream_settings        = maybe_unserialize( $layout_settings->social_stream_settings );
	$ssd_share_type                = isset( $social_stream_settings['ssd_social_share_type'] ) ? $social_stream_settings['ssd_social_share_type'] : 'text';
	$ssd_design_layout             = isset( $social_stream_settings['ssd_design_layout'] ) ? $social_stream_settings['ssd_design_layout'] : '';
	$ssd_custom_css                = isset( $social_stream_settings['ssd_custom_css'] ) ? $social_stream_settings['ssd_custom_css'] : '';
	$ssd_theme_color               = isset( $social_stream_settings['ssd_theme_color'] ) ? $social_stream_settings['ssd_theme_color'] : '';
	$ssd_overlay_bg_color          = isset( $social_stream_settings['ssd_overlay_bg_color'] ) ? $social_stream_settings['ssd_overlay_bg_color'] : '';
	$ssd_heading_color             = isset( $social_stream_settings['ssd_heading_color'] ) ? $social_stream_settings['ssd_heading_color'] : '';
	$ssd_heading_alignment         = isset( $social_stream_settings['ssd_heading_alignment'] ) ? $social_stream_settings['ssd_heading_alignment'] : 'center';
	$ssd_subheading_color          = isset( $social_stream_settings['ssd_subheading_color'] ) ? $social_stream_settings['ssd_subheading_color'] : '';
	$ssd_overlay_padding_top       = isset( $social_stream_settings['ssd_overlay_padding_top'] ) && '' !== $social_stream_settings['ssd_overlay_padding_top'] ? $social_stream_settings['ssd_overlay_padding_top'] : '10';
	$ssd_overlay_padding_bottom    = isset( $social_stream_settings['ssd_overlay_padding_bottom'] ) && '' !== $social_stream_settings['ssd_overlay_padding_bottom'] ? $social_stream_settings['ssd_overlay_padding_bottom'] : '10';
	$ssd_overlay_padding_left      = isset( $social_stream_settings['ssd_overlay_padding_left'] ) && '' !== $social_stream_settings['ssd_overlay_padding_left'] ? $social_stream_settings['ssd_overlay_padding_left'] : '15';
	$ssd_overlay_padding_right     = isset( $social_stream_settings['ssd_overlay_padding_right'] ) && '' !== $social_stream_settings['ssd_overlay_padding_right'] ? $social_stream_settings['ssd_overlay_padding_right'] : '15';
	$ssd_card_box_hoffset          = isset( $social_stream_settings['ssd_card_box_hoffset'] ) ? $social_stream_settings['ssd_card_box_hoffset'] : '0';
	$ssd_card_box_voffset          = isset( $social_stream_settings['ssd_card_box_voffset'] ) ? $social_stream_settings['ssd_card_box_voffset'] : '0';
	$ssd_card_box_blur             = isset( $social_stream_settings['ssd_card_box_blur'] ) ? $social_stream_settings['ssd_card_box_blur'] : '0';
	$ssd_card_box_spread           = isset( $social_stream_settings['ssd_card_box_spread'] ) ? $social_stream_settings['ssd_card_box_spread'] : '0';
	$ssd_card_box_shadow           = isset( $social_stream_settings['ssd_card_box_shadow'] ) ? $social_stream_settings['ssd_card_box_shadow'] : '';
	$ssd_text_border_radius        = isset( $social_stream_settings['ssd_text_border_radius'] ) ? $social_stream_settings['ssd_text_border_radius'] : '';
	$ssd_text_border_radius_type   = isset( $social_stream_settings['ssd_text_border_radius_type'] ) ? $social_stream_settings['ssd_text_border_radius_type'] : '';
	$ssd_icon_alignment            = isset( $social_stream_settings['ssd_icon_alignment'] ) ? $social_stream_settings['ssd_icon_alignment'] : '';
	$ssd_icon_border_radius        = isset( $social_stream_settings['ssd_icon_border_radius'] ) ? $social_stream_settings['ssd_icon_border_radius'] : '';
	$ssd_icon_border_radius_type   = isset( $social_stream_settings['ssd_icon_border_radius_type'] ) ? $social_stream_settings['ssd_icon_border_radius_type'] : '';
	$ssd_icon_color                = isset( $social_stream_settings['ssd_icon_color'] ) ? $social_stream_settings['ssd_icon_color'] : '';
	$ssd_icon_bg_color             = isset( $social_stream_settings['ssd_icon_bg_color'] ) ? $social_stream_settings['ssd_icon_bg_color'] : '';
	$ssd_title_font_size           = isset( $social_stream_settings['ssd_title_font_size'] ) ? $social_stream_settings['ssd_title_font_size'] : '';
	$ssd_title_color               = isset( $social_stream_settings['ssd_title_color'] ) ? $social_stream_settings['ssd_title_color'] : '';
	$ssd_title_hover_color         = isset( $social_stream_settings['ssd_title_hover_color'] ) ? $social_stream_settings['ssd_title_hover_color'] : '';
	$ssd_content_font_size         = isset( $social_stream_settings['ssd_content_font_size'] ) ? $social_stream_settings['ssd_content_font_size'] : '';
	$ssd_content_color             = isset( $social_stream_settings['ssd_content_color'] ) ? $social_stream_settings['ssd_content_color'] : '';
	// $ssd_content_hover_color       = isset( $social_stream_settings['ssd_content_hover_color'] ) ? $social_stream_settings['ssd_content_hover_color'] : '';
	$ssd_author_border_radius      = isset( $social_stream_settings['ssd_author_border_radius'] ) ? $social_stream_settings['ssd_author_border_radius'] : '';
	$ssd_author_border_radius_type = isset( $social_stream_settings['ssd_author_border_radius_type'] ) ? $social_stream_settings['ssd_author_border_radius_type'] : '';
	$ssd_author_title_color        = isset( $social_stream_settings['ssd_author_title_color'] ) ? $social_stream_settings['ssd_author_title_color'] : '';
	$ssd_author_bg_color           = isset( $social_stream_settings['ssd_author_bg_color'] ) ? $social_stream_settings['ssd_author_bg_color'] : '';
	$ssd_author_title_hover_color  = isset( $social_stream_settings['ssd_author_title_hover_color'] ) ? $social_stream_settings['ssd_author_title_hover_color'] : '';
	$ssd_author_meta_color         = isset( $social_stream_settings['ssd_author_meta_color'] ) ? $social_stream_settings['ssd_author_meta_color'] : '';
	$ssd_count_meta_color          = isset( $social_stream_settings['ssd_count_meta_color'] ) ? $social_stream_settings['ssd_count_meta_color'] : '';
	$ssd_count_padding_top         = isset( $social_stream_settings['ssd_count_padding_top'] ) ? $social_stream_settings['ssd_count_padding_top'] : '10';
	$ssd_count_padding_bottom      = isset( $social_stream_settings['ssd_count_padding_bottom'] ) ? $social_stream_settings['ssd_count_padding_bottom'] : '0';
	$ssd_count_margin_top          = isset( $social_stream_settings['ssd_count_margin_top'] ) ? $social_stream_settings['ssd_count_margin_top'] : '0';
	$ssd_count_margin_bottom       = isset( $social_stream_settings['ssd_count_margin_bottom'] ) ? $social_stream_settings['ssd_count_margin_bottom'] : '0';
	$ssd_count_bg_color            = isset( $social_stream_settings['ssd_count_bg_color'] ) ? $social_stream_settings['ssd_count_bg_color'] : '';
	$ssd_count_meta_hover_color    = isset( $social_stream_settings['ssd_count_meta_hover_color'] ) ? $social_stream_settings['ssd_count_meta_hover_color'] : '';
	$ssd_count_border_top_width    = isset( $social_stream_settings['ssd_count_border_top_width'] ) ? $social_stream_settings['ssd_count_border_top_width'] : '';
	$ssd_count_border_top_type     = isset( $social_stream_settings['ssd_count_border_top_type'] ) ? $social_stream_settings['ssd_count_border_top_type'] : '';
	$ssd_count_border_top_color    = isset( $social_stream_settings['ssd_count_border_top_color'] ) ? $social_stream_settings['ssd_count_border_top_color'] : '';
	$ssd_count_border_bottom_width = isset( $social_stream_settings['ssd_count_border_bottom_width'] ) ? $social_stream_settings['ssd_count_border_bottom_width'] : '';
	$ssd_count_border_bottom_type  = isset( $social_stream_settings['ssd_count_border_bottom_type'] ) ? $social_stream_settings['ssd_count_border_bottom_type'] : '';
	$ssd_count_border_bottom_color = isset( $social_stream_settings['ssd_count_border_bottom_color'] ) ? $social_stream_settings['ssd_count_border_bottom_color'] : '';
	$ssd_count_border_right_type   = isset( $social_stream_settings['ssd_count_border_right_type'] ) ? $social_stream_settings['ssd_count_border_right_type'] : '';
	$ssd_count_border_right_color  = isset( $social_stream_settings['ssd_count_border_right_color'] ) ? $social_stream_settings['ssd_count_border_right_color'] : '';
	$ssd_count_border_right_width  = isset( $social_stream_settings['ssd_count_border_right_width'] ) ? $social_stream_settings['ssd_count_border_right_width'] : '';
	$ssd_count_border_left_type    = isset( $social_stream_settings['ssd_count_border_left_type'] ) ? $social_stream_settings['ssd_count_border_left_type'] : '';
	$ssd_count_border_left_color   = isset( $social_stream_settings['ssd_count_border_left_color'] ) ? $social_stream_settings['ssd_count_border_left_color'] : '';
	$ssd_count_border_left_width   = isset( $social_stream_settings['ssd_count_border_left_width'] ) ? $social_stream_settings['ssd_count_border_left_width'] : '';
	$ssd_heading_bg_color          = isset( $social_stream_settings['ssd_heading_bg_color'] ) ? $social_stream_settings['ssd_heading_bg_color'] : '';
	$ssd_column_type          	   = isset( $social_stream_settings['ssd_column_type'] ) ? $social_stream_settings['ssd_column_type'] : '';
	$ssd_column_type_laptop        = isset( $social_stream_settings['ssd_column_type_laptop'] ) ? $social_stream_settings['ssd_column_type_laptop'] : '';
	$ssd_column_type_rotated_tablet = isset( $social_stream_settings['ssd_column_type_rotated_tablet'] ) ? $social_stream_settings['ssd_column_type_rotated_tablet'] : '';
	$ssd_column_type_tablet        = isset( $social_stream_settings['ssd_column_type_tablet'] ) ? $social_stream_settings['ssd_column_type_tablet'] : '';
	$ssd_column_type_rotated_mobile = isset( $social_stream_settings['ssd_column_type_rotated_mobile'] ) ? $social_stream_settings['ssd_column_type_rotated_mobile'] : '';
	$ssd_column_type_mobile        = isset( $social_stream_settings['ssd_column_type_mobile'] ) ? $social_stream_settings['ssd_column_type_mobile'] : '';

?>
<style>
	<?php
	echo wp_unslash( $ssd_custom_css );
	if ( '' !== $ssd_heading_color ) {
		?>
		#social_stream_header_<?php echo esc_attr( $rand ); ?> .ssd-steam-title-wrap h1 {
			color:<?php echo esc_attr( $ssd_heading_color ); ?>;
		}
		<?php
	}
	if ( '' !== $ssd_subheading_color ) {
		?>
		#social_stream_header_<?php echo esc_attr( $rand ); ?> .ssd-steam-sub-title-wrap h4 {
			color:<?php echo esc_attr( $ssd_subheading_color ); ?>;
		}
		<?php
	}
	if ( '' !== $ssd_heading_alignment ) {
		?>
		#social_stream_header_<?php echo esc_attr( $rand ); ?> .ssd-steam-title-wrap,
		#social_stream_header_<?php echo esc_attr( $rand ); ?> .ssd-steam-sub-title-wrap {
			text-align:<?php echo esc_attr( $ssd_heading_alignment ); ?>;
		}
		<?php
	}
	if ( '' !== $ssd_heading_bg_color ) {
		?>
		#social_stream_header_<?php echo esc_attr( $rand ); ?> {
			background:<?php echo esc_attr( $ssd_heading_bg_color ); ?>;
			<?php if ( '' !== $ssd_overlay_padding_bottom && $ssd_overlay_padding_bottom >= 0 ) { ?>
				padding-bottom:<?php echo esc_attr( $ssd_overlay_padding_bottom ); ?>px;
										<?php
			}
			if ( '' !== $ssd_overlay_padding_top && $ssd_overlay_padding_top >= 0 ) {
				?>
				padding-top:<?php echo esc_attr( $ssd_overlay_padding_top ); ?>px;
				<?php
			}
			if ( '' !== $ssd_overlay_padding_left && $ssd_overlay_padding_left >= 0 ) {
				?>
				padding-left:<?php echo esc_attr( $ssd_overlay_padding_left ); ?>px;
				<?php
			}
			if ( '' !== $ssd_overlay_padding_right && $ssd_overlay_padding_right >= 0 ) {
				?>
				padding-right:<?php echo esc_attr( $ssd_overlay_padding_right ); ?>px;
				<?php
			}
			?>
		}
		<?php
	}
	if ( '' !== $ssd_theme_color ) {
		?>
		#social_stream_<?php echo esc_attr( $rand ); ?> .ssd_lmp_products_loading .ssd_lmp_rotate {
			color: <?php echo esc_attr( $ssd_theme_color ); ?> !important;
		}
		#social_stream_<?php echo esc_attr( $rand ); ?> button {
			color: #fff;background-color: <?php echo esc_attr( $ssd_theme_color ); ?>;
		}
		#social_stream_<?php echo esc_attr( $rand ); ?> .ssd_paged .ssd-pgn-bar.template-1 a.ssd-page-active,.ssd_paged .ssd-pgn-bar.template-2 a,.ssd_paged .ssd-pgn-bar.template-3 a,.ssd_paged .ssd-pgn-bar.template-4 a,.ssd_paged .ssd-pgn-bar.template-5 a,.ssd_paged .ssd-pgn-bar.template-6 a,.ssd_load_more_btn button.template-3{
			color: <?php echo esc_attr( $ssd_theme_color ); ?> !important;
		}
		#social_stream_<?php echo esc_attr( $rand ); ?> .ssd_paged .ssd-pgn-bar.template-4 a,.ssd_paged .ssd-pgn-bar.template-5 a,.ssd_paged .ssd-pgn-bar.template-6 a,.ssd_load_more_btn button.template-3:before,.ssd_load_more_btn button.template-3:after{
			border-color: <?php echo esc_attr( $ssd_theme_color ); ?> !important;
		}
		#social_stream_<?php echo esc_attr( $rand ); ?> .ssd_paged .ssd-pgn-bar.template-1 a,.ssd_paged .ssd-pgn-bar.template-2 a.ssd-page-active,.ssd_paged .ssd-pgn-bar.template-3 a.ssd-page-active,.ssd_paged .ssd-pgn-bar.template-4 a.ssd-page-active,.ssd_paged .ssd-pgn-bar.template-5 a.ssd-page-active{
			color: #fff !important;background-color: <?php echo esc_attr( $ssd_theme_color ); ?> !important;
		}
		#social_stream_<?php echo esc_attr( $rand ); ?> .ssd_paged .ssd-pgn-bar.template-1 a.ssd-page-active,.ssd_load_more_btn button.template-3{
			background-color: transparent !important;
		}
		.ssd-dialog-popup-cover .flex-direction-nav a::before,#social_stream_<?php echo esc_attr( $rand ); ?> .flex-direction-nav a::before {
			color: <?php echo esc_attr( $ssd_theme_color ); ?> !important;
		}
		#social_stream_<?php echo esc_attr( $rand ); ?> .ssd-feed-filter li.filter-all a:hover{
			background-color: #7d7d7d !important;
		}
		#social_stream_<?php echo esc_attr( $rand ); ?> .ssd-feed-filter li.filter-all a.ssd_active-feed:hover{
			background-color: #7d7d7d !important;
		}
		#social_stream_<?php echo esc_attr( $rand ); ?> .ssd-feed-filter li.filter-all a.ssd_active-feed{
			background-color: <?php echo esc_attr( $ssd_theme_color ); ?> !important;
		}
		.ssdss_tmln:before,.ssdss_tmln .ssd-col-item .ssd-card:before{
			background-color: <?php echo esc_attr( $ssd_theme_color ); ?> !important;
		} 
		<?php
	}
	?>
	#social_stream_<?php echo esc_attr( $rand ); ?>.ssdsos-wrp { 
	<?php
	if ( '' !== $ssd_overlay_padding_bottom && $ssd_overlay_padding_bottom >= 0 ) {
		?>
		padding-bottom:<?php echo esc_attr( $ssd_overlay_padding_bottom ); ?>px;
		<?php
	}
	if ( '' !== $ssd_overlay_padding_top && $ssd_overlay_padding_top >= 0 ) {
		?>
		padding-top:<?php echo esc_attr( $ssd_overlay_padding_top ); ?>px;
		<?php
	}
	if ( '' !== $ssd_overlay_padding_left && $ssd_overlay_padding_left >= 0 ) {
		?>
		padding-left:<?php echo esc_attr( $ssd_overlay_padding_left ); ?>px;
		<?php
	}
	if ( '' !== $ssd_overlay_padding_right && $ssd_overlay_padding_right >= 0 ) {
		?>
		padding-right:<?php echo esc_attr( $ssd_overlay_padding_right ); ?>px;
		<?php
	}
	if ( '' !== $ssd_overlay_bg_color ) {
		?>
		background: <?php echo esc_attr( $ssd_overlay_bg_color ); ?>;
		<?php
	}
	?>
	    display: inline-block;
		max-width: 100%;
	}
	#social_stream_<?php echo esc_attr( $rand ); ?> .ssd-card {
		overflow: hidden;
		<?php
		if ( '' !== $ssd_card_box_shadow ) {
			?>
			margin-top:8px;margin-bottom:8px;box-shadow: <?php echo esc_attr( $ssd_card_box_hoffset ); ?>px <?php echo esc_attr( $ssd_card_box_voffset ); ?>px <?php echo esc_attr( $ssd_card_box_blur ); ?>px <?php echo esc_attr( $ssd_card_box_spread ); ?>px <?php echo esc_attr( $ssd_card_box_shadow ); ?>;
								<?php
		}
		?>
	}

	/* Style for title */
	.ssd-dialog-popup-cover .ssd-post-title h4,.ssd-dialog-popup-cover .ssd-post-title a,#social_stream_<?php echo esc_attr( $rand ); ?> .ssd-post-title h4,#social_stream_<?php echo esc_attr( $rand ); ?> .ssd-post-title a {
		<?php
		if ( $ssd_title_font_size > 0 ) {
			?>
			font-size:<?php echo esc_attr( $ssd_title_font_size ) . 'px'; ?>;
								<?php
		}
		if ( '' !== $ssd_title_color ) {
			?>
			color:<?php echo esc_attr( $ssd_title_color ); ?>;
								<?php
		}
		?>
	}
	#social_stream_<?php echo esc_attr( $rand ); ?> .ssd-post-title a:hover h4,#social_stream_<?php echo esc_attr( $rand ); ?> .ssd-post-title a:hover { 
								<?php
								if ( '' !== $ssd_title_hover_color ) {
									?>
			color:<?php echo esc_attr( $ssd_title_hover_color ); ?>;
									<?php
								}
								?>
	}
	/* Style for content */
	.ssd-action-row .ssd-photo-count {
		<?php
		if ( '' !== $ssd_content_color ) {
			?>
			color:<?php echo esc_attr( $ssd_content_color ); ?>;
							<?php
		}
		?>
	}
	#social_stream_<?php echo esc_attr( $rand ); ?> .ssd-content-wrap p {
		<?php
		if ( $ssd_content_font_size > 0 ) {
			?>
			font-size:<?php echo esc_attr( $ssd_content_font_size ) . 'px'; ?>;
							<?php
		}
		if ( '' !== $ssd_content_color ) {
			?>
			color:<?php echo esc_attr( $ssd_content_color ); ?>;
							<?php
		}
		?>
		padding-top:10px;
		padding-bottom:10px;
		margin-top:0;				
		margin-bottom:0;
									
	}
	#social_stream_<?php echo esc_attr( $rand ); ?> .ssd-post-media.ssd_has_media,
	#social_stream_<?php echo esc_attr( $rand ); ?> .ssd-post-media.ssd_no_media .display_on_media { 
			margin-top:0;
			margin-bottom:20px;
										
	}
	
	/* Style for author section */
	#social_stream_<?php echo esc_attr( $rand ); ?> .ssd-author-detail { 			
			margin-top:0;
			margin-bottom:20px;
			padding-top:0;
			padding-bottom:0;
	}
	.ssd-dialog-popup-cover .ssd-author-detail .ssd-author-image,
	.ssd-dialog-popup-cover .ssd-author-detail .ssd-author-image img,
	#social_stream_<?php echo esc_attr( $rand ); ?> .ssd-author-detail .ssd-author-image,
	#social_stream_<?php echo esc_attr( $rand ); ?> .ssd-author-detail .ssd-author-image img { 
								<?php
								if ( '' !== $ssd_author_border_radius && '' !== $ssd_author_border_radius_type ) {
									?>
			border-radius:<?php echo esc_attr( $ssd_author_border_radius . $ssd_author_border_radius_type ); ?>;
										<?php
								}
								?>
	}
	.ssd-dialog-popup-cover .ssd-author-detail .ssd-author-name a.ssd-display-name,
	#social_stream_<?php echo esc_attr( $rand ); ?> .ssd-author-detail .ssd-author-name a.ssd-display-name { 
								<?php
								if ( '' !== $ssd_author_title_color ) {
									?>
			color:<?php echo esc_attr( $ssd_author_title_color ); ?>;
									<?php
								}
								?>
	}
	.ssd-dialog-popup-cover .ssd-author-detail,
	#social_stream_<?php echo esc_attr( $rand ); ?> .ssd-author-detail { 
								<?php
								if ( '' !== $ssd_author_bg_color ) {
									?>
			background-color:<?php echo esc_attr( $ssd_author_bg_color ); ?>;
											<?php
								}
								?>
	}
	.ssd-dialog-popup-cover .ssd-author-detail .ssd-author-name a.ssd-display-name:hover,
	#social_stream_<?php echo esc_attr( $rand ); ?> .ssd-author-detail .ssd-author-name a.ssd-display-name:hover {
								<?php
								if ( '' !== $ssd_author_title_hover_color ) {
									?>
			color:<?php echo esc_attr( $ssd_author_title_hover_color ); ?>;
									<?php
								}
								?>
	}
	.ssd-dialog-popup-cover .ssd-author-detail .ssd-author-name a.ssd-user-name,
	.ssd-dialog-popup-cover .ssd-posted-date,
	#social_stream_<?php echo esc_attr( $rand ); ?> .ssd-author-detail .ssd-author-name a.ssd-user-name,
	#social_stream_<?php echo esc_attr( $rand ); ?> .ssd-posted-date { 
								<?php
								if ( '' !== $ssd_author_meta_color ) {
									?>
			color:<?php echo esc_attr( $ssd_author_meta_color ); ?>;
									<?php
								}
								?>
	}
	#social_stream_<?php echo esc_attr( $rand ); ?> .ssd-card.ssd_center.ssd_bottom a.ssd-share-label::after { 
								<?php
								if ( '' !== $ssd_icon_bg_color ) {
									?>
			border-bottom-color:<?php echo esc_attr( $ssd_icon_bg_color ); ?>;
												<?php
								}
								?>
	}
	#social_stream_<?php echo esc_attr( $rand ); ?> .ssd-card.ssd_center.ssd_top a.ssd-share-label::after { 
								<?php
								if ( '' !== $ssd_icon_bg_color ) {
									?>
			border-top-color:<?php echo esc_attr( $ssd_icon_bg_color ); ?>;
											<?php
								}
								?>
	}
	#social_stream_<?php echo esc_attr( $rand ); ?> .ssdso-icn:not(.ssd_display_corner_icon) .ssd-share-label { 
								<?php
								if ( '' !== $ssd_icon_bg_color ) {
									?>
			background-color:<?php echo esc_attr( $ssd_icon_bg_color ); ?>;
											<?php
								}
								?>
	}
	#social_stream_<?php echo esc_attr( $rand ); ?> .ssd-card.ssd_left .ssdso-icn.ssd_display_corner_icon::after { 
								<?php
								if ( '' !== $ssd_icon_bg_color ) {
									?>
			border-left-color:<?php echo esc_attr( $ssd_icon_bg_color ); ?>;
											<?php
								}
								?>
	}
	#social_stream_<?php echo esc_attr( $rand ); ?> .ssd-card.ssd_right .ssdso-icn.ssd_display_corner_icon::after { 
								<?php
								if ( '' !== $ssd_icon_bg_color ) {
									?>
			border-right-color:<?php echo esc_attr( $ssd_icon_bg_color ); ?>;
											<?php
								}
								?>
	}
	#social_stream_<?php echo esc_attr( $rand ); ?> .ssdso-icn .ssd-share-label { 
								<?php
								if ( '' !== $ssd_icon_color ) {
									?>
			color:<?php echo esc_attr( $ssd_icon_color ); ?>;
									<?php
								}
								if ( 'icon' === $ssd_share_type ) {
									if ( '' !== $ssd_icon_border_radius && '' !== $ssd_icon_border_radius_type ) {
										?>
				border-radius:<?php echo esc_attr( $ssd_icon_border_radius . $ssd_icon_border_radius_type ); ?>;
											<?php
									}
								}
								?>
	}
	#social_stream_<?php echo esc_attr( $rand ); ?> .ssdso-icn i { 
								<?php
								if ( '' !== $ssd_icon_border_radius && '' !== $ssd_icon_border_radius_type ) {
									?>
			border-radius:<?php echo esc_attr( $ssd_icon_border_radius . $ssd_icon_border_radius_type ); ?>;
										<?php
								}
								?>
	}
	#social_stream_<?php echo esc_attr( $rand ); ?> .ssdso-icn.ssd_text .ssd-share-label,
	#social_stream_<?php echo esc_attr( $rand ); ?> .ssdso-icn.ssd_icon_text .ssd-share-label { 
								<?php
								if ( '' !== $ssd_text_border_radius && '' !== $ssd_text_border_radius_type ) {
									?>
			border-radius:<?php echo esc_attr( $ssd_text_border_radius . $ssd_text_border_radius_type ); ?>;
										<?php
								}
								?>

	}
	#social_stream_<?php echo esc_attr( $rand ); ?> .ssd-action-row { 
								<?php
								if ( '' !== $ssd_count_bg_color ) {
									?>
			background-color:<?php echo esc_attr( $ssd_count_bg_color ); ?>;
											<?php
								}
								if ( '' !== $ssd_count_padding_top ) {
									?>
			padding-top:<?php echo esc_attr( $ssd_count_padding_top ); ?>px;
										<?php
								}
								if ( '' !== $ssd_count_padding_bottom ) {
									?>
			padding-bottom:<?php echo esc_attr( $ssd_count_padding_bottom ); ?>px;
										<?php
								}
								if ( '' !== $ssd_count_margin_top ) {
									?>
			margin-top:<?php echo esc_attr( $ssd_count_margin_top ); ?>px;
									<?php
								}
								if ( '' !== $ssd_count_margin_bottom ) {
									?>
			margin-bottom:<?php echo esc_attr( $ssd_count_margin_bottom ); ?>px;
										<?php
								}
								if ( '' !== $ssd_count_border_top_width ) {
									?>
			border-top-width:<?php echo esc_attr( $ssd_count_border_top_width ); ?>px;
											<?php
								}
								if ( '' !== $ssd_count_border_top_type ) {
									?>
			border-top-style:<?php echo esc_attr( $ssd_count_border_top_type ); ?>;
											<?php
								}
								if ( '' !== $ssd_count_border_top_color ) {
									?>
			border-top-color:<?php echo esc_attr( $ssd_count_border_top_color ); ?>;
											<?php
								}
								if ( '' !== $ssd_count_border_bottom_width ) {
									?>
			border-bottom-width:<?php echo esc_attr( $ssd_count_border_bottom_width ); ?>px;
												<?php
								}
								if ( '' !== $ssd_count_border_bottom_type ) {
									?>
			border-bottom-style:<?php echo esc_attr( $ssd_count_border_bottom_type ); ?>;
												<?php
								}
								if ( '' !== $ssd_count_border_bottom_color ) {
									?>
			border-bottom-color:<?php echo esc_attr( $ssd_count_border_bottom_color ); ?>;
												<?php
								}

								if ( '' !== $ssd_count_border_right_width ) {
									?>
			border-right-width:<?php echo esc_attr( $ssd_count_border_right_width ); ?>px;
											<?php
								}
								if ( '' !== $ssd_count_border_right_type ) {
									?>
			border-right-style:<?php echo esc_attr( $ssd_count_border_right_type ); ?>;
											<?php
								}
								if ( '' !== $ssd_count_border_right_color ) {
									?>
			border-right-color:<?php echo esc_attr( $ssd_count_border_right_color ); ?>;
											<?php
								}

								if ( '' !== $ssd_count_border_left_width ) {
									?>
			border-left-width:<?php echo esc_attr( $ssd_count_border_left_width ); ?>px;
											<?php
								}
								if ( '' !== $ssd_count_border_left_type ) {
									?>
			border-left-style:<?php echo esc_attr( $ssd_count_border_left_type ); ?>;
											<?php
								}
								if ( '' !== $ssd_count_border_left_color ) {
									?>
			border-left-color:<?php echo esc_attr( $ssd_count_border_left_color ); ?>;
											<?php
								}

								?>
	}
	.ssd-dialog-popup-cover .ssd-share-button i,
	.ssd-dialog-popup-cover .ssd-content-meta-wrap,
	.ssd-dialog-popup-cover .ssd-content-meta-wrap i,
	#social_stream_<?php echo esc_attr( $rand ); ?> .ssd-action-row i,
	#social_stream_<?php echo esc_attr( $rand ); ?> .ssd-share-button i,
	#social_stream_<?php echo esc_attr( $rand ); ?> .ssd-action-row .ssd-counts { 
								<?php
								if ( '' !== $ssd_count_meta_color ) {
									?>
			color:<?php echo esc_attr( $ssd_count_meta_color ); ?>;
									<?php
								}
								?>
	}
	.ssd-dialog-popup-cover .ssd-share-button i,
	.ssd-dialog-popup-cover .ssd-content-meta-wrap,
	.ssd-dialog-popup-cover .ssd-content-meta-wrap i,
	#social_stream_<?php echo esc_attr( $rand ); ?> .ssd-share-button i,
	#social_stream_<?php echo esc_attr( $rand ); ?> .ssd-action-row .ssd-counts { 
								<?php
								if ( '' !== $ssd_count_meta_color ) {
									?>
			color:<?php echo esc_attr( $ssd_count_meta_color ); ?>;
									<?php
								}
								?>
		<?php
		if ( $ssd_content_font_size > 0 ) {
			?>
			font-size:<?php echo esc_attr( $ssd_content_font_size ) . 'px'; ?>;
								<?php
		}
		?>
	}
	.ssd-dialog-popup-cover .ssd-cell-row { 
	<?php
	if ( '' !== $ssd_count_bg_color ) {
		?>
			background-color:<?php echo esc_attr( $ssd_count_bg_color ); ?>;
										<?php
	}
	if ( '' !== $ssd_count_padding_top ) {
		?>
			padding-top:<?php echo esc_attr( $ssd_count_padding_top ); ?>px;
									<?php
	}
	?>
		padding-bottom:10px;
	}
	.ssd-dialog-popup-cover .ssd-content-meta-wrap:hover,.ssd-dialog-popup-cover .ssd-content-meta-wrap:hover i,#social_stream_<?php echo esc_attr( $rand ); ?> .ssd-action-row a:hover i,#social_stream_<?php echo esc_attr( $rand ); ?> .ssd-action-row a:hover .ssd-counts{
								<?php
								if ( '' !== $ssd_count_meta_hover_color ) {
									?>
			color:<?php echo esc_attr( $ssd_count_meta_hover_color ); ?>;
									<?php
								}
								?>
	}
</style>
