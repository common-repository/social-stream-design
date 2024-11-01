<?php
/**
 * Class file for all actions of stream
 *
 * @version 1.0
 * @package WP Social Stream Designer
 */

/**
 * Class for all actions of stream.
 */
class WpSocialStream {
	/**
	 * Class for all actions of stream.
	 */
	public function __construct() {

		add_action( 'admin_menu', array( $this, 'ssd_add_menu' ) );
		add_action( 'admin_init', array( $this, 'ssd_save_admin_template' ), 10 );
		add_action( 'admin_init', array( $this, 'ssd_delete_admin_template' ), 11 );
		add_action( 'admin_init', array( $this, 'ssd_duplicate_admin_template' ), 12 );
		add_action( 'admin_enqueue_scripts', array( $this, 'ssd_admin_script_style' ), 10 );
		add_action( 'wp_enqueue_scripts', array( $this, 'ssd_front_end_script_style' ), 10 );
		add_filter( 'set-screen-option', array( $this, 'ssd_set_option' ), 10, 3 );
		add_action( 'plugins_loaded', array( $this, 'ssd_load_language_files' ), 10 );
		add_action( 'admin_head', array( $this, 'ssd_read_all_feed_for_refresh' ), 10 );
		add_action( 'wp_head', array( $this, 'ssd_read_all_feed_for_refresh' ), 10 );
		add_action( 'wp_ajax_nopriv_ssd_action_check_for_refresh_feed', array( $this, 'ssd_refresh_feeds' ), 10 );
		add_action( 'wp_ajax_ssd_action_check_for_refresh_feed', array( $this, 'ssd_refresh_feeds' ), 10 );
		add_action( 'wp_ajax_ssd_update_feed_status', array( $this, 'ssd_update_status_feeds' ), 10 );
		add_action( 'wp_ajax_ssd_update_feed_live_status', array( $this, 'ssd_update_live_status_feeds' ), 10 );
		add_action( 'wp_ajax_ssd_set_order', array( $this, 'ssd_set_order' ), 10 );
		add_action( 'wp_ajax_ssd_corner_icon', array( $this, 'ssd_corner_icon' ), 10 );
		add_action( 'wp_ajax_ssd_update_sticky_on', array( $this, 'ssd_update_sticky_on' ), 10 );
		add_action( 'wp_ajax_ssd_reset_layout_settings', array( &$this, 'ssd_reset_layout_settings' ), 10 );
		add_shortcode( 'social_stream_feeds', array( $this, 'ssd_feeds_shortcode' ) );
	}

	/**
	 * Argument for Kses.
	 *
	 * @since    1.0.0
	 * @return  array
	 */
	public static function args_kses() {
		$args_kses = array(
			'div'    => array(
				'class'  => true,
				'id'     => true,
				'style'  => true,
				'script' => true,
				'adid'   => true,
				'bdid'   => true,
				'btype'  => true,
				'adview' => true,
				'bid'    => true,
			),
			'script' => array(
				'type'    => true,
				'charset' => true,
			),
			'style'  => array(
				'type' => true,
			),
			'iframe' => array(
				'class'        => true,
				'src'          => true,
				'style'        => true,
				'marginwidth'  => true,
				'marginheight' => true,
				'scrolling'    => true,
				'frameborder'  => true,
			),
			'img'    => array(
				'class' => true,
				'src'   => true,
			),
			'a'      => array(
				'href'   => true,
				'adid'   => true,
				'bdid'   => true,
				'btype'  => true,
				'adview' => true,
				'bid'    => true,
				'class'  => true,
			),
			'ul'     => array(
				'class' => true,
				'id'    => true,
				'style' => true,
			),
			'li'     => array(
				'class' => true,
				'id'    => true,
				'style' => true,
			),
			'p'      => array(
				'class' => true,
				'id'    => true,
				'style' => true,
			),
			'i'      => array(
				'class' => true,
				'style' => true,
			),
			'h1'     => array(
				'class' => true,
				'style' => true,
			),
			'h4'     => array(
				'class' => true,
				'style' => true,
			),
			'span'   => array(
				'class' => true,
				'style' => true,
			),
		);
		return $args_kses;
	}
	/**
	 * Display layout
	 *
	 * @param string $atts shortcode id.
	 * @return string
	 */
	public function ssd_feeds_shortcode( $atts ) {
		$layout_object = new WpSSDLayoutsActions();
		$layout_object->init();
		$shortcode_id           = $atts['id'];
		$layout_object->id      = $shortcode_id;
		$layout_settings        = $layout_object->ssd_get_layout_detail();
		$shortcode_name         = ( isset( $layout_settings->shortcode_name ) && '' !== $layout_settings->shortcode_name ) ? $layout_settings->shortcode_name : '';
		$social_stream_settings = ( isset( $layout_settings->social_stream_settings ) && '' !== $layout_settings->social_stream_settings ) ? maybe_unserialize( $layout_settings->social_stream_settings ) : '';
		if ( isset( $_POST['nonce'] ) ) {
			$nonce = sanitize_text_field( wp_unslash( $_POST['nonce'] ) );
			if ( ! wp_verify_nonce( $nonce, 'ajax-nonce' ) ) {
				wp_send_json_error( array( 'ssd_shortcodes' => 'Nonce error' ) );
				die();
			}
		}
		if ( ! $layout_settings ) {
			return '<b style="color:#ff0000">' . esc_html__( 'Error', 'social-stream-design' ) . ' : </b>' . esc_html__( 'Social Stream Feeds shortcode not found. Please cross check your Social Stream Feeds ID.', 'social-stream-design' );
		}
		// Get feed ids to get feed posts.
		$feed_ids = $social_stream_settings['feed_ids'];
		$feed_ids = array_values( $feed_ids );
		$feed_ids = implode( ',', $feed_ids );
		// Get pagination options.
		$no_of_posts_per_page  = ( isset( $social_stream_settings['ssd_no_of_posts_per_page'] ) && $social_stream_settings['ssd_no_of_posts_per_page'] > 1 ) ? $social_stream_settings['ssd_no_of_posts_per_page'] : 6;
		$total_number_of_posts = ( isset( $social_stream_settings['ssd_no_of_posts'] ) && $social_stream_settings['ssd_no_of_posts'] >= 0 ) ? $social_stream_settings['ssd_no_of_posts'] : '-1';
		$cur_page              = ( isset( $_REQUEST['cur_page'] ) && sanitize_text_field( wp_unslash( $_REQUEST['cur_page'] ) ) > 0 ) ? sanitize_text_field( wp_unslash( $_REQUEST['cur_page'] ) ) : 1;
		$ssd_pagination_type   = 'no_pagination';
		$ssd_design_layout     = isset( $social_stream_settings['ssd_design_layout'] ) ? $social_stream_settings['ssd_design_layout'] : '';
		$layout                = isset( $social_stream_settings['ssd_layout'] ) ? $social_stream_settings['ssd_layout'] : 'listing';
		$order                 = isset( $social_stream_settings['ssd_display_order'] ) ? $social_stream_settings['ssd_display_order'] : 0;
		$order_by              = isset( $social_stream_settings['ssd_order_by'] ) ? $social_stream_settings['ssd_order_by'] : 'date';
		$ssd_grid_style        = isset( $social_stream_settings['ssd_grid_style'] ) ? $social_stream_settings['ssd_grid_style'] : 'masonry';
		if ( 'listing' !== $layout ) {
			$ssd_grid_style = '';
		}
		$ssd_stream_title                = isset( $social_stream_settings['ssd_stream_title'] ) ? $social_stream_settings['ssd_stream_title'] : '';
		$ssd_stream_subtitle             = isset( $social_stream_settings['ssd_stream_subtitle'] ) ? $social_stream_settings['ssd_stream_subtitle'] : '';
		$ssd_heading_alignment           = isset( $social_stream_settings['ssd_heading_alignment'] ) ? $social_stream_settings['ssd_heading_alignment'] : 'center';
		$ssd_display_feed_without_media0 = (int) isset( $social_stream_settings['ssd_display_feed_without_media'] ) ? $social_stream_settings['ssd_display_feed_without_media'] : 1;
		$ssd_justified_grid_height       = isset( $social_stream_settings['ssd_justified_grid_height'] ) ? $social_stream_settings['ssd_justified_grid_height'] : '200';
		$ssd_column_type                 = isset( $social_stream_settings['ssd_column_type'] ) ? $social_stream_settings['ssd_column_type'] : 3;
		$ssd_column_type_laptop          = isset( $social_stream_settings['ssd_column_type_laptop'] ) ? $social_stream_settings['ssd_column_type_laptop'] : 3;
		$ssd_column_type_rotated_tablet  = isset( $social_stream_settings['ssd_column_type_rotated_tablet'] ) ? $social_stream_settings['ssd_column_type_rotated_tablet'] : 3;
		$ssd_column_type_tablet          = isset( $social_stream_settings['ssd_column_type_tablet'] ) ? $social_stream_settings['ssd_column_type_tablet'] : 2;
		$ssd_column_type_rotated_mobile  = isset( $social_stream_settings['ssd_column_type_rotated_mobile'] ) ? $social_stream_settings['ssd_column_type_rotated_mobile'] : 1;
		$ssd_column_type_mobile          = isset( $social_stream_settings['ssd_column_type_mobile'] ) ? $social_stream_settings['ssd_column_type_mobile'] : 1;
		$ssd_column_type_vertical        = isset( $social_stream_settings['ssd_column_type_vertical'] ) ? $social_stream_settings['ssd_column_type_vertical'] : 3;
		$ssd_row_type                    = isset( $social_stream_settings['ssd_row_type'] ) ? $social_stream_settings['ssd_row_type'] : 1;
		$ssd_display_search              = isset( $social_stream_settings['ssd_display_search'] ) ? $social_stream_settings['ssd_display_search'] : 0;
		$ssd_display_feed_without_media  = (int) $ssd_display_feed_without_media0;
		$feed_object                     = new WpSSDFeedsActions();
		$feed_object->init();
		$feed_live_status = array();
		$all_feeds        = $feed_object->ssd_get_all_feeds();
		if ( is_array( $all_feeds ) && ! empty( $all_feeds ) ) {
			foreach ( $all_feeds as $single_feed ) {
				if ( in_array( $single_feed->id, $social_stream_settings['feed_ids'], true ) ) {
					$feeds_settings       = maybe_unserialize( $single_feed->feeds_settings );
					$all_feed_status_live = isset( $feeds_settings['feed_status_live'] ) ? $feeds_settings['feed_status_live'] : 'Live';
					if ( 'Live' === $all_feed_status_live ) {
						$feed_live_status[ $single_feed->id ] = isset( $feeds_settings['feed_status_live'] ) ? $feeds_settings['feed_status_live'] : 'Live';
					}
				}
			}
		}
		$total_feed_count = '';

		if ( 'listing' === $layout ) {
			$ssd_pagination_type = isset( $social_stream_settings['ssd_pagination_type'] ) ? $social_stream_settings['ssd_pagination_type'] : 'no_pagination';
		}

		// Set pagination options.
		$feeds_object = new WpSSDFrontFeeds();
		$feeds_object->init();
		$feeds_object->feed_id                        = $feed_ids;
		$feeds_object->ssd_display_order              = $order;
		$feeds_object->ssd_order_by                   = $order_by;
		$feeds_object->ssd_search                     = '';
		$feeds_object->ssd_display_feed_without_media = $ssd_display_feed_without_media;

		if ( 1 == $ssd_display_feed_without_media ) {
			$ssd_total_stream = $feeds_object->ssd_total_stream();
		} else {
			$ssd_total_stream = $feeds_object->ssd_total_stream_without_image();
		}
		if ( $ssd_total_stream > $total_number_of_posts ) {
			$total_feeds = $total_number_of_posts;
		} else {
			$total_feeds = $ssd_total_stream;
		}

		if ( -1 == $total_number_of_posts ) {
			$total_feeds = $ssd_total_stream;
		} else {
			$feeds_object->max_number_of_posts = $total_number_of_posts;
		}

		if ( 'no_pagination' === $ssd_pagination_type ) {
			$feeds_object->posts_per_page = (int) $total_number_of_posts;
			if ( -1 == $total_number_of_posts ) {
				$feeds_object->posts_per_page = 1000;
			}
		} else {
			$feeds_object->posts_per_page = (int) $no_of_posts_per_page;
		}

		$feeds_object->cur_page          = (int) ( $cur_page );
		$pages_to_display_for_pagination = apply_filters( 'pages_to_display_for_pagination', 7, 10 );
		$pagination                      = '0';
		// Set pagination to yes.
		if ( ( 'load_more_btn' === $ssd_pagination_type ) && $total_feeds > $no_of_posts_per_page && ( 'listing' === $layout ) ) {
			$pagination = '1';
		}

		// Set pagination to 1000 to display all linked pages in pagination instead of 7.
		if ( 'load_more_btn' === $ssd_pagination_type ) {
			$pages_to_display_for_pagination = apply_filters( 'pages_to_display_for_pagination', 1000, 10 );
		}
		// Get all feeds.
		$feed_title_array = array();
		$social_stream    = $feeds_object->ssd_get_stream();
		if ( is_array( $social_stream ) && ! empty( $social_stream ) ) {
			foreach ( $social_stream as $feeds ) {
				$feed_title_data[] = $feeds->feed_type;
			}
			if ( is_array( $feed_title_data ) && ! empty( $feed_title_data ) ) {
				$feed_title_array = array_unique( $feed_title_data );
			}
		}
		ob_start();
		include WPSOCIALSTREAMDESIGNER_DIR . 'css/layout-dynamic-style.php';
		?>
			<div class="ssdsos-wrp ssd-content" data-id="<?php echo intval( $shortcode_id ); ?>" id="social_stream_<?php echo '_' . intval( $shortcode_id ); ?>">
			<?php if ( ! empty( $ssd_stream_title ) || ! empty( $ssd_stream_subtitle ) ) { ?>
				<div class="sstrm-hed-wrp" id="social_stream_header_<?php echo '_' . intval( $shortcode_id ); ?>">
					<?php if ( ! empty( $ssd_stream_title ) ) { ?>
						<div class="ssd-steam-title-wrap">
							<h1><?php echo esc_html( $ssd_stream_title ); ?></h1>
						</div>
					<?php } ?>
					<?php if ( ! empty( $ssd_stream_subtitle ) ) { ?>
						<div class="ssd-steam-sub-title-wrap">
							<h4><?php echo esc_html( $ssd_stream_subtitle ); ?></h4>
						</div>
					<?php } ?>
				</div>

				<?php
			}
				$style = '';
			?>
					<div <?php echo esc_attr( $style ); ?> class="ssd-wrapper-inner 
						<?php
						echo 'ssd_' . esc_attr( $ssd_pagination_type );
						?>
					">
						<div id="ssd_social_stream_<?php echo esc_attr( $layout ); ?>"
							class="ssd-cnt-prnt  ssd-row-padding slides 
							<?php
							echo esc_attr( 'ssd_social_stream_' . $layout );
							?>
							ssd_social_stream_<?php echo esc_attr( $ssd_grid_style ); ?>" >
								<?php
								if ( is_array( $social_stream ) && ! empty( $social_stream ) ) {
									foreach ( $social_stream as $feeds ) {
										foreach ( $feed_live_status as $key => $val ) {
											$c_feed_id = (int) $feeds->feed_id;
											if ( $key == $c_feed_id ) {
												$image = isset( $feeds->post_image ) ? $feeds->post_image : '';
												$video = isset( $feeds->post_video ) ? $feeds->post_video : '';
												if ( 1 == $ssd_display_feed_without_media ) {
													include 'include/social-stream-layout.php';
												} else {
													if ( '' !== $image || '' !== $video ) {
														include 'include/social-stream-layout.php';
													}
												}
											}
										}
									}
								}
								?>
						</div>
						<?php
						// Display Pagination.
						if ( $no_of_posts_per_page < $ssd_total_stream ) {
							if ( is_array( $social_stream ) && ! empty( $social_stream ) && ( $total_number_of_posts > $no_of_posts_per_page || -1 == $total_number_of_posts ) ) {
								echo '<div class="ssd_pagination_wrap">';
								if ( 1 == $pagination ) {
									$cur_page_for_pagination = 0;
									if ( $cur_page > $pages_to_display_for_pagination ) {
										$cur_page_for_pagination = floor( $cur_page / $pages_to_display_for_pagination );
									}
									$total_pages_for_pagination = ceil( $total_feeds / $no_of_posts_per_page );
									$out_pagination             = Wp_Social_Stream_Main::ssd_get_feed_pagination( $social_stream_settings, $total_pages_for_pagination, $cur_page, $cur_page_for_pagination, $pages_to_display_for_pagination );
									echo wp_kses( $out_pagination, self::args_kses() );
								}
								if ( ( 'load_more_btn' === $ssd_pagination_type ) && 'listing' === $layout ) {
									$ssd_pagination_layout = ( isset( $social_stream_settings['ssd_load_more_layout'] ) && '' !== $social_stream_settings['ssd_load_more_layout'] ) ? $social_stream_settings['ssd_load_more_layout'] : 'template-1';
									?>
									<input type="hidden" name="ssd_total_posts" id="ssd_total_posts" value="<?php echo esc_attr( $total_number_of_posts ); ?>">
									<button class="ssd_social_stream_load_more_btn <?php echo esc_attr( $ssd_pagination_layout ); ?> ssd-button ssd-black ssd-margin-bottom"><?php esc_html_e( 'Load More', 'social-stream-design' ); ?></button>
									<?php
								}
								echo '</div>';
							}
						}
						?>
					</div>
				</div>
			<?php
			$output_string = ob_get_contents();
			ob_end_clean();
			return $output_string;
	}

	/**
	 * Create menus at admin side
	 *
	 * @return void
	 */
	public function ssd_add_menu() {
		$hook = add_menu_page( esc_html( 'WP Social Stream Designer' ), esc_html( 'WP Social Stream Designer' ), 'manage_options', 'social-stream-designer-layouts', '', 'dashicons-layout' );
		add_action( "load-$hook", array( $this, 'ssd_add_option' ) );
		add_submenu_page( 'social-stream-designer-layouts', esc_html__( 'Social Stream Layouts', 'social-stream-design' ), esc_html__( 'Layouts', 'social-stream-design' ), 'manage_options', 'social-stream-designer-layouts', array( $this, 'ssd_get_layout_function' ) );
		add_submenu_page( 'social-stream-designer-layouts', esc_html__( 'Add Social Stream Layouts', 'social-stream-design' ), esc_html__( 'Add New Layout', 'social-stream-design' ), 'manage_options', 'social-stream-designer-add-layouts', array( $this, 'ssd_add_layout_function' ) );
		$feed_hook = add_submenu_page( 'social-stream-designer-layouts', esc_html__( 'Social Feeds', 'social-stream-design' ), esc_html__( 'Social Feeds', 'social-stream-design' ), 'manage_options', 'social-stream-designer-social-feed-layouts', array( $this, 'ssd_get_social_feed_function' ) );
		add_action( "load-$feed_hook", array( $this, 'ssd_feed_add_option' ) );
		add_submenu_page( 'social-stream-designer-layouts', esc_html__( 'Add Social Feed', 'social-stream-design' ), esc_html__( 'Add Social Feed', 'social-stream-design' ), 'manage_options', 'social-stream-designer-social-feed', array( $this, 'ssd_add_social_feed_function' ) );
		add_submenu_page( 'social-stream-designer-layouts', esc_html__( 'Social Authentication', 'social-stream-design' ), esc_html__( 'Social Authentication', 'social-stream-design' ), 'manage_options', 'social-stream-designer-settings', array( $this, 'ssd_settings_function' ) );
	}
	/**
	 * Language text domain.
	 *
	 * @return void
	 */
	public function ssd_load_language_files() {
		load_plugin_textdomain( 'social-stream-design', false, dirname( plugin_basename( __FILE__ ) ) . '/languages' );
	}
	/**
	 * Set item per page
	 *
	 * @param string $status status.
	 * @param string $option option.
	 * @param string $value value.
	 * @return string
	 */
	public function ssd_set_option( $status, $option, $value ) {
		if ( 'ssd_items_per_page' === $option || 'ssd_feeds_per_page' === $option ) {
			return $value;
		}

		return $status;
	}
	/**
	 * Add item per page
	 *
	 * @return void
	 */
	public function ssd_add_option() {
		$option = 'per_page';
		$args   = array(
			'label'   => 'Number of items per page',
			'default' => 10,
			'option'  => 'ssd_items_per_page',
		);
		add_screen_option( $option, $args );
	}
	/**
	 * Add Feed per page
	 *
	 * @return void
	 */
	public function ssd_feed_add_option() {

		$option = 'per_page';
		$args   = array(
			'label'   => 'Number of items per page',
			'default' => 10,
			'option'  => 'ssd_feeds_per_page',
		);
		add_screen_option( $option, $args );
	}
	/**
	 * Display all created layouts at admin side
	 *
	 * @return void
	 */
	public function ssd_get_layout_function() {
		$shortcode_list_table = new Shortcode_List_Table_WP();
		$shortcode_list_table->prepare_items();
		if ( isset( $_POST['nonce'] ) ) {
			$nonce = sanitize_text_field( wp_unslash( $_POST['nonce'] ) );
			if ( ! wp_verify_nonce( $nonce, 'layout-nonce' ) ) {
				wp_send_json_error( array( 'status' => 'Nonce error' ) );
				die();
			}
		}
		?>
		<div class="wrap">
			<div>
				<h1 class="wp-heading-inline"><?php esc_html_e( 'Social Stream Layouts', 'social-stream-design' ); ?></h1>
				<a href="<?php echo esc_url( admin_url( 'admin.php?page=social-stream-designer-add-layouts' ) ); ?>" class="page-title-action"><?php esc_html_e( 'Add New Social Stream Layout', 'social-stream-design' ); ?></a>
				<?php
				if ( isset( $_REQUEST['delete'] ) && 'true' == $_REQUEST['delete'] ) {
					?>
				<div class="updated notice">
					<p>
					<?php
						esc_html_e( 'Layout has been deleted successfully.', 'social-stream-design' );
					?>
					</p>
				</div>
					<?php
				} elseif ( isset( $_REQUEST['delete'] ) && 'false' == $_REQUEST['delete'] ) {
					?>
				<div class="error notice">
					<p>
					<?php
						esc_html_e( 'Error to delete layout.', 'social-stream-design' );
					?>
					</p>
				</div>
					<?php
				}
				?>
			</div>
			<form id="shortcode-filter" method="get">
				<?php
				$page = isset( $_REQUEST['page'] ) ? sanitize_text_field( wp_unslash( $_REQUEST['page'] ) ) : '';
				?>
				<input type="hidden" name="page" value="<?php echo esc_attr( $page ); ?>" />
				<?php $shortcode_list_table->display(); ?>
			</form>
		</div>
		<?php
	}
	/**
	 * Display all feeds at admin side
	 *
	 * @return void
	 */
	public function ssd_get_social_feed_function() {
		$feed_list_table = new Feed_List_Table_WP();
		$feed_list_table->prepare_items();
		if ( isset( $_POST['nonce'] ) ) {
			$nonce = sanitize_text_field( wp_unslash( $_POST['nonce'] ) );
			if ( ! wp_verify_nonce( $nonce, 'feed-list-table-nonce' ) ) {
				wp_send_json_error( array( 'status' => 'Nonce error' ) );
				die();
			}
		}
		?>
		<div class="wrap">
			<div>
				<h1 class="wp-heading-inline"><?php esc_html_e( 'Social Feeds', 'social-stream-design' ); ?></h1>
				<a href="<?php echo esc_url( admin_url( 'admin.php?page=social-stream-designer-social-feed' ) ); ?>" class="page-title-action"><?php esc_html_e( 'Add New Social Feed', 'social-stream-design' ); ?></a>
			</div>
			<?php
			if ( isset( $_REQUEST['delete'] ) && 'true' == $_REQUEST['delete'] ) {
				global $wpdb;
				$feed_posts_table = $wpdb->prefix . 'ssd_feed_posts';
				$feed_id          = ( isset( $_GET['id'] ) && '' !== $_GET['id'] ) ? intval( $_GET['id'] ) : '';
				$wpdb->delete( $feed_posts_table, array( 'feed_id' => $feed_id ), array( '%d' ) );
				?>
				<div class="updated notice">
					<p>
					<?php
					esc_html_e( 'Feed has been deleted successfully.', 'social-stream-design' );
					?>
					</p>
				</div>
				<?php
			} elseif ( isset( $_REQUEST['delete'] ) && 'false' == $_REQUEST['delete'] ) {
				?>
				<div class="error notice">
					<p>
				<?php
				esc_html_e( 'Error to delete feed.', 'social-stream-design' );
				?>
					</p>
				</div>
				<?php
			}
			?>
			<form id="shortcode-filter" method="get">
				<?php $page = isset( $_REQUEST['page'] ) ? sanitize_text_field( wp_unslash( $_REQUEST['page'] ) ) : ''; ?>
				<input type="hidden" name="page" value="<?php echo esc_attr( $page ); ?>" />
				<?php $feed_list_table->display(); ?>
			</form>
		</div>
		<?php
	}
	/**
	 * Include file for social feeds tab
	 *
	 * @return void
	 */
	public function ssd_add_social_feed_function() {
		include_once 'admin/assets/social-stream-add-social-feed.php';
	}
	/**
	 * Include file for social layouts tab
	 *
	 * @return void
	 */
	public function ssd_add_layout_function() {
		include_once 'admin/assets/social-stream-add-layout.php';
	}
	/**
	 * Include file for social settings tab
	 *
	 * @return void
	 */
	public function ssd_settings_function() {
		include_once 'admin/assets/social-stream-settings-form.php';
	}
	/**
	 * Add scripts and styles at admin side
	 *
	 * @return void
	 */
	public function ssd_admin_script_style() {
		if ( isset( $_GET['page'] ) && ( 'social-stream-designer-layouts' === $_GET['page'] || 'social-stream-designer-add-layouts' === $_GET['page'] || 'social-stream-designer-social-feed-layouts' === $_GET['page'] || 'social-stream-designer-social-feed' === $_GET['page'] || 'social-stream-designer-settings' === $_GET['page'] ) ) {
			wp_enqueue_style( 'ssd_admin_style', WPSOCIALSTREAMDESIGNER_URL . '/admin/css/admin-style.css', array(), '1.0', false );
			wp_enqueue_style( 'ssd_fontawesome', WPSOCIALSTREAMDESIGNER_URL . '/css/fontawesome-all.min.css', array(), '6.5.1', false );
			wp_enqueue_style( 'ssd_admin_select2', WPSOCIALSTREAMDESIGNER_URL . '/admin/css/select2.min.css', array(), '1.0', false );
			wp_enqueue_style( 'wp-jquery-ui-dialog' );
			wp_enqueue_style( 'wp-color-picker' );
			wp_enqueue_script( 'ssd_admin_select2', WPSOCIALSTREAMDESIGNER_URL . '/admin/js/select2.min.js', array( 'jquery' ), '1.0', true );
			wp_enqueue_media();
			wp_enqueue_script( 'jquery' );
			wp_enqueue_script( 'jquery-ui-core' );
			wp_enqueue_script( 'jquery-ui-dialog' );
			wp_enqueue_script( 'jquery-ui-sortable' );
			wp_enqueue_script( 'wp-color-picker' );
			wp_enqueue_script( 'ssd_wpcolorpicker_alpha', WPSOCIALSTREAMDESIGNER_URL . '/admin/js/wp-color-picker-alpha.js', array( 'wp-color-picker' ), '1.0', true );
			$colorpicker_l10n = array(
				'clear'            => __( 'Clear', 'social-stream-design' ),
				'clearAriaLabel'   => __( 'Clear color', 'social-stream-design' ),
				'defaultString'    => __( 'Default', 'social-stream-design' ),
				'defaultAriaLabel' => __( 'Select default color', 'social-stream-design' ),
				'pick'             => __( 'Select Color', 'social-stream-design' ),
				'defaultLabel'     => __( 'Color value', 'social-stream-design' ),
			);
			wp_localize_script( 'ssd_wpcolorpicker_alpha', 'wpColorPickerL10n', $colorpicker_l10n );
			wp_enqueue_script( 'ssd_admin_script', WPSOCIALSTREAMDESIGNER_URL . '/admin/js/admin-script.js', array( 'jquery' ), '1.0', false );
			wp_localize_script(
				'ssd_admin_script',
				'ssd_ajax_var',
				array(
					'ssd_nonce' => wp_create_nonce( 'ssd_nonce_ajax' ),
				)
			);
			wp_enqueue_script( 'ssd_modernizr_custom', WPSOCIALSTREAMDESIGNER_URL . '/js/modernizr.custom.js', array( 'jquery' ), '1.0', false );
			wp_enqueue_script( 'jquery-masonry' );
			wp_enqueue_script( 'ssd_classie', WPSOCIALSTREAMDESIGNER_URL . '/js/classie.min.js', array( 'jquery' ), '1.0', false );
			wp_enqueue_script( 'ssd_anim_on_scroll', WPSOCIALSTREAMDESIGNER_URL . '/js/AnimOnScroll.js', array( 'jquery', 'jquery-masonry' ), '1.0', false );
			wp_localize_script(
				'ssd_admin_script',
				'ssdadminObj',
				array(
					'reset_data'           => esc_html__( 'Do you want to reset data?', 'social-stream-design' ),
					'select_social_stream' => esc_html__( 'Please select social stream.', 'social-stream-design' ),
					'validation_error'     => esc_html__( 'Please enter numeric value. Fill the red border input correct and try again.', 'social-stream-design' ),
					'delete_confirm'       => esc_html__( 'Are you sure to delete this item?', 'social-stream-design' ),
					'preview_title'        => esc_html__( 'Your Social Stream Layout Preview', 'social-stream-design' ),
					'ssd_plugin_path'      => WPSOCIALSTREAMDESIGNER_URL,
				)
			);
		}
	}
	/**
	 * Save feeds at admin side
	 *
	 * @return void
	 */
	public function ssd_save_admin_template() {
		if ( current_user_can( 'manage_options' ) ) {
			if ( isset( $_POST['ssd'] ) ) {
				if ( isset( $_POST['ssd_nonce'] ) && wp_verify_nonce( sanitize_text_field( wp_unslash( $_POST['ssd_nonce'] ) ), 'social-stream-designer_meta_box_nonce' ) ) {
					$ssd_layout_actions = new WpSSDLayoutsActions();
					$ssd_layout_actions->init();
					$ssd_layout_actions->ssd_add_layout();
				}
			} elseif ( isset( $_POST['feed_stream_nonce'] ) ) {
				if ( isset( $_POST['feed_stream_nonce'] ) && wp_verify_nonce( sanitize_text_field( wp_unslash( $_POST['feed_stream_nonce'] ) ), 'social-stream-designer_meta_box_nonce' ) ) {
					$ssd_feeds_actions = new WpSSDFeedsActions();
					$ssd_feeds_actions->init();
					$ssd_feeds_actions->ssd_add_feed();
				}
			} elseif ( isset( $_POST['social-stream-designer-settings'] ) ) {
				$error_array      = array();
				$update_auth_name = '';
				if ( isset( $_POST['social-stream-designer-settings'] ) && wp_verify_nonce( sanitize_text_field( wp_unslash( $_POST['social-stream-designer-settings'] ) ), 'social-stream-designer_meta_box_nonce' ) ) {
					foreach ( $_POST as $key => $value ) {
						if ( 'facebook_stream' === $key ) {
							$update_auth_name      = 'Facebook';
							$facebook_id           = $value['facebook_id'];
							$facebook_secret       = $value['facebook_secret'];
							$facebook_access_token = $value['facebook_access_token'];
							$url                   = 'https://graph.facebook.com/oauth/access_token?client_id=' . $facebook_id . '&client_secret=' . $facebook_secret . '&grant_type=fb_exchange_token&fb_exchange_token=' . $facebook_access_token;
							$get_message           = Wp_Social_Stream_Main::ssd_get_data_from_remote_url( $url );
							$get_php_error         = Wp_Social_Stream_Main::ssd_get_last_error();
							$response              = json_decode( $get_message, true );
							if ( isset( $response['error']['message'] ) && ! empty( $response['error']['message'] ) ) {
								$error_array[] = $response['error']['message'] . '!!!! ' . esc_html__( 'Please try again for', 'social-stream-design' ) . esc_html( ' Facebook stream' );
							} else {
								if ( ! $response ) {
									$error_array[] = 'Facebook Stream';
								}
							}
						} elseif ( 'instagram_stream' === $key ) {
							$update_auth_name = 'Instagram';
							$access_token     = $value['access_token'];

							$url           = 'https://graph.instagram.com/me/media?fields=id,caption&access_token=' . $access_token;
							$get_message   = Wp_Social_Stream_Main::ssd_get_data_from_remote_url( $url );
							$response      = json_decode( $get_message, true );
							$get_php_error = Wp_Social_Stream_Main::ssd_get_last_error();
							if ( isset( $response['meta']['error_message'] ) && ! empty( $response['meta']['error_message'] ) ) {
								$error_array[] = $response['meta']['error_message'] . '!!!! ' . esc_html__( 'Please try again for', 'social-stream-design' ) . esc_html( ' instagram stream' );
							} else {
								if ( ! $response ) {
									$error_array[] = 'Instagram Stream';
								}
							}
						} elseif ( 'twitter_stream' === $key ) {
							$update_auth_name          = 'Twitter';
							$oauth_access_token        = $value['access_token'];
							$oauth_access_token_secret = $value['access_token_secret'];
							$consumer_key              = $value['consumer_key'];
							$consumer_secret           = $value['consumer_secret'];
							$settings                  = array(
								'oauth_access_token' => $oauth_access_token,
								'oauth_access_token_secret' => $oauth_access_token_secret,
								'consumer_key'       => $consumer_key,
								'consumer_secret'    => $consumer_secret,
							);
							$url                       = 'https://api.twitter.com/1.1/account/verify_credentials.json';
							$request_method            = 'GET';
							$getfield                  = '?count=20';
							$twitter                   = new TwitterAPIExchangeWP( $settings );
							$result                    = $twitter->setGetfield( $getfield )->buildOauth( $url, $request_method )->performRequest();
							$json                      = json_decode( $result, true );
							$get_php_error             = Wp_Social_Stream_Main::ssd_get_last_error();
							if ( isset( $json['errors']['0']['message'] ) && ! empty( $json['errors']['0']['message'] ) ) {
								$error_array[] = $json['errors']['0']['message'] . '!!!! ' . esc_html__( 'Please try again for', 'social-stream-design' ) . esc_html( ' twitter stream' );
							} elseif ( is_array( $get_php_error ) ) {
								if ( $get_php_error ) {
									$error_array[] = $get_php_error['message'] . '!!!! ' . esc_html__( 'Please try again for', 'social-stream-design' ) . esc_html( ' twitter stream' );
								} else {
									$error_array[] = 'Twitter stream';
								}
							} else {
								if ( isset( $json->errors ) ) {
									$error_array[] = 'Twitter Stream';
								}
							}
						}
						$error_array_string = maybe_serialize( $error_array );
						if ( false == get_option( 'auth_error_array' ) ) {
							add_option( 'auth_error_array', $error_array_string );
						} else {
							update_option( 'auth_error_array', $error_array_string );
						}
						$_POST['error'] = $error_array;
						if ( isset( $update_auth_name ) && '' !== $update_auth_name ) {
							$_POST['update_auth_name'] = $update_auth_name;
						}
						update_option( $key, $value );
					}
				}
			}
		}
	}
	/**
	 * Delete layout
	 *
	 * @return void
	 */
	public function ssd_delete_admin_template() {
		global $wpdb;
		if ( current_user_can( 'manage_options' ) ) {

			if ( isset( $_GET['action'] ) && 'delete' === $_GET['action'] ) {
				$id   = isset( $_REQUEST['id'] ) ? intval( $_REQUEST['id'] ) : '';
				$page = isset( $_REQUEST['page'] ) ? sanitize_text_field( wp_unslash( $_REQUEST['page'] ) ) : '';
				if ( 'social-stream-designer-social-feed-layouts' === $page || 'social-stream-designer-layouts' === $page ) {
					if ( isset( $_POST['nonce'] ) ) {
						$nonce = sanitize_text_field( wp_unslash( $_POST['nonce'] ) );
						if ( ! wp_verify_nonce( $nonce, 'delete-admin-template-nonce' ) ) {
							wp_send_json_error( array( 'status' => 'Nonce error' ) );
							die();
						}
					}
				}
				if ( 'social-stream-designer-social-feed-layouts' === $page ) {
					// $result = $wpdb->query( $wpdb->prepare( "DELETE FROM $wpdb->prefix" . 'ssd_feeds WHERE id= %d', $id ) );
					// wp_safe_redirect( add_query_arg( 'id', $id, admin_url( 'admin.php?page=social-stream-designer-social-feed-layouts&delete=true' ) ) );
					// exit();
				} elseif ( 'social-stream-designer-layouts' === $page ) {
					// $result = $wpdb->query( $wpdb->prepare( "DELETE FROM $wpdb->prefix" . 'ssd_shortcodes WHERE ID= %d', $id ) );
					// wp_safe_redirect( add_query_arg( 'id', $id, admin_url( 'admin.php?page=social-stream-designer-layouts&delete=true' ) ) );
					// exit();
				}
			}
		}
	}

	/**
	 * Duplicate layout
	 *
	 * @return void
	 */
	public function ssd_duplicate_admin_template() {
		if ( current_user_can( 'manage_options' ) ) {

			if ( isset( $_GET['action'] ) && 'duplicate' === $_GET['action'] ) {
				global $wpdb;
				$id        = isset( $_GET['id'] ) ? intval( $_GET['id'] ) : '0';
				$page      = isset( $_GET['page'] ) ? sanitize_text_field( wp_unslash( $_GET['page'] ) ) : '';
				$tablename = $wpdb->prefix . 'ssd_shortcodes';
				if ( 'social-stream-designer-layouts' === $page && $id > 0 ) {
					if ( isset( $_POST['nonce'] ) ) {
						$nonce = sanitize_text_field( wp_unslash( $_POST['nonce'] ) );
						if ( ! wp_verify_nonce( $nonce, 'duplicate-admin-template-nonce' ) ) {
							wp_send_json_error( array( 'status' => 'Nonce error' ) );
							die();
						}
					}
					$source_records = $wpdb->get_row( $wpdb->prepare( "select * from $wpdb->prefix" . 'ssd_shortcodes where ID = %d', $id ) );
					if ( $source_records ) {
						$source_shortcode_name                            = $source_records->shortcode_name . ' - Copy';
						$source_social_stream_name                        = $source_records->social_stream_name;
						$source_social_stream_settings                    = maybe_unserialize( $source_records->social_stream_settings );
						$source_social_stream_settings['ssd_stream_page'] = '';
						$source_social_stream_name                        = $source_social_stream_name . ' - Copy';
						$wpdb->insert(
							$tablename,
							array(
								'shortcode_name'         => sanitize_text_field( $source_shortcode_name ),
								'social_stream_name'     => sanitize_text_field( $source_social_stream_name ),
								'social_stream_settings' => maybe_serialize( $source_social_stream_settings ),
							),
							array(
								'%s',
								'%s',
								'%s',
							)
						);
						$insert_id = $wpdb->insert_id;
						if ( $insert_id > 0 ) {
							$redirect_link = admin_url( 'admin.php?page=add_shortcode&action=edit&id=' . $insert_id );
							wp_safe_redirect( add_query_arg( 'duplicate', 'true', admin_url( 'admin.php?page=social-stream-designer-add-layouts&action=edit&id=' . $insert_id ) ) );
							exit();
						}
					}
				}
			}
		}
	}
	/**
	 * Enqueue script and style at front side
	 *
	 * @return void
	 */
	public function ssd_front_end_script_style() {
		wp_enqueue_style( 'effects', WPSOCIALSTREAMDESIGNER_URL . '/css/effects.css', array(), '1.0', false );
		wp_enqueue_style( 'wp-jquery-ui-dialog' );
		wp_enqueue_style( 'ssd_fontawesome', WPSOCIALSTREAMDESIGNER_URL . '/css/fontawesome-all.min.css', array(), '6.5.1', false );
		wp_enqueue_style( 'stream-font-family', 'https://fonts.googleapis.com/css?family=Roboto:400,400i,500,700', array(), '1.0', false );
		wp_enqueue_style( 'stream', WPSOCIALSTREAMDESIGNER_URL . '/css/style.css', array(), '1.0', false );
		wp_enqueue_style( 'slickslider', WPSOCIALSTREAMDESIGNER_URL . '/css/slick.min.css', array(), '1.0', false );
		wp_enqueue_script( 'slickslider', WPSOCIALSTREAMDESIGNER_URL . '/js/slick.min.js', array( 'jquery' ), '1.0', false );
		wp_enqueue_script( 'jquery' );
		wp_enqueue_script( 'isotop', WPSOCIALSTREAMDESIGNER_URL . '/js/isotope.pkgd.min.js', array( 'jquery' ), '1.0', false );
		wp_enqueue_script( 'ssd_modernizr_custom', WPSOCIALSTREAMDESIGNER_URL . '/js/modernizr.custom.js', array( 'jquery' ), '1.0', false );
		wp_enqueue_script( 'imagesloaded', WPSOCIALSTREAMDESIGNER_URL . '/js/imagesloaded.pkgd.min.js', array( 'jquery' ), '1.0', false );
		wp_enqueue_script( 'jquery-masonry' );
		wp_enqueue_script( 'jquery-ui-dialog' );
		wp_enqueue_script( 'ssd_classie', WPSOCIALSTREAMDESIGNER_URL . '/js/classie.min.js', array( 'jquery' ), '1.0', false );
		wp_enqueue_script( 'anim-on-scroll', WPSOCIALSTREAMDESIGNER_URL . '/js/AnimOnScroll.js', array( 'jquery', 'jquery-masonry' ), '1.0', false );
		wp_enqueue_script( 'script', WPSOCIALSTREAMDESIGNER_URL . '/js/script.js', array( 'jquery' ), '1.0', false );
		wp_localize_script( 'script', 'ajax_object', array( 'ajax_url' => admin_url( 'admin-ajax.php' ) ) );
	}
	/**
	 * Ajax function to update feed on status change
	 *
	 * @return void
	 */
	public function ssd_update_status_feeds() {
		$ssd_nonce = isset( $_POST['ssd_nonce'] ) ? sanitize_text_field( wp_unslash( $_POST['ssd_nonce'] ) ) : '';
		if ( wp_verify_nonce( $ssd_nonce, 'ssd_nonce_ajax' ) ) {
			if ( isset( $_POST['action'] ) && 'ssd_update_feed_status' === $_POST['action'] ) {
				global $wpdb;
				$feed_id                       = isset( $_POST['feed_id'] ) ? intval( $_POST['feed_id'] ) : '';
				$feed_status                   = isset( $_POST['feed_status'] ) ? sanitize_text_field( wp_unslash( $_POST['feed_status'] ) ) : '';
				$feed_table_name               = $wpdb->prefix . 'ssd_feeds';
				$read_feed                     = $wpdb->get_row( $wpdb->prepare( "select * from $wpdb->prefix" . 'ssd_feeds where id = %d', $feed_id ) );
				$feeds_settings                = maybe_unserialize( $read_feed->feeds_settings );
				$feeds_settings['feed_status'] = $feed_status;
				$updated_feed_settings         = maybe_serialize( $feeds_settings );
				$wpdb->update(
					$wpdb->prefix . 'ssd_feeds',
					array(
						'feeds_settings' => $updated_feed_settings,
					),
					array( 'id' => $feed_id ),
					array(
						'%s',
					),
					array( '%d' )
				);
			}
		}
		wp_die();
	}
	/**
	 * Ajax function to update feed on live status change
	 *
	 * @return void
	 */
	public function ssd_update_live_status_feeds() {
		$ssd_nonce = isset( $_POST['ssd_nonce'] ) ? sanitize_text_field( wp_unslash( $_POST['ssd_nonce'] ) ) : '';
		if ( wp_verify_nonce( $ssd_nonce, 'ssd_nonce_ajax' ) ) {
			if ( isset( $_POST['action'] ) && 'ssd_update_feed_live_status' === $_POST['action'] ) {
				global $wpdb;
				$feed_id                            = isset( $_POST['feed_id'] ) ? intval( $_POST['feed_id'] ) : '';
				$feed_live_status                   = isset( $_POST['feed_status_live'] ) ? sanitize_text_field( wp_unslash( $_POST['feed_status_live'] ) ) : '';
				$feed_table_name                    = $wpdb->prefix . 'ssd_feeds';
				$read_feed                          = $wpdb->get_row( $wpdb->prepare( "select feeds_settings from $wpdb->prefix" . 'ssd_feeds where id = %d', $feed_id ) );
				$feeds_settings                     = maybe_unserialize( $read_feed->feeds_settings );
				$feeds_settings['feed_status_live'] = $feed_live_status;
				$updated_feed_settings              = maybe_serialize( $feeds_settings );
				$wpdb->update(
					$feed_table_name,
					array(
						'feeds_settings' => $updated_feed_settings,
					),
					array( 'id' => $feed_id ),
					array(
						'%s',
					),
					array( '%d' )
				);
			}
		}
		wp_die();
	}
	/**
	 * Get all feeds
	 *
	 * @return void
	 */
	public function ssd_read_all_feed_for_refresh() {
		global $wpdb;
		$get_results = $wpdb->get_results( "SELECT * FROM $wpdb->prefix" . 'ssd_feeds' );
		foreach ( $get_results as $response ) {
			$feeds_settings        = maybe_unserialize( $response->feeds_settings );
			$feeds_type            = $feeds_settings['feed'];
			$feed_type_txt         = explode( '-', $feeds_type );
			$feed_type_text        = $feed_type_txt[0];
			$feed_numbers          = ( isset( $feeds_settings['refresh_feed_on_number'] ) ) ? $feeds_settings['refresh_feed_on_number'] : '';
			$feeds_refresh_dd      = ( isset( $feeds_settings['refresh_feed_on_dd'] ) ) ? $feeds_settings['refresh_feed_on_dd'] : '';
			$last_refresh_time     = ( isset( $response->refresh_feed_date ) ) ? $response->refresh_feed_date : '';
			$updated_referesh_time = gmdate( 'Y-m-d H:i:s', strtotime( '+' . $feed_numbers . $feeds_refresh_dd, strtotime( $last_refresh_time ) ) );
			$current_time          = gmdate( 'Y-m-d H:i:s' );
			if ( $current_time >= $updated_referesh_time ) {
				?>
				<script>
				jQuery(document).ready(function(){
					var ajaxurl = '<?php echo esc_url( admin_url( 'admin-ajax.php' ) ); ?>';
					var ajaxData = {
						'action': 'ssd_action_check_for_refresh_feed',
						'feed_id': '<?php echo intval( $response->id ); ?>',
						'feeds_type': '<?php echo esc_html( $feed_type_text ); ?>',
						'ssd_nonce' : '<?php echo esc_attr( wp_create_nonce( 'ssd_nonce_ajax' ) ); ?>'
					}
					jQuery.post(ajaxurl, ajaxData, function(response){

					});
				});
				</script>
				<?php
			}
		}
	}
	/**
	 * Ajax function to refresh feeds
	 *
	 * @return void
	 */
	public function ssd_refresh_feeds() {
		$ssd_nonce = isset( $_POST['ssd_nonce'] ) ? sanitize_text_field( wp_unslash( $_POST['ssd_nonce'] ) ) : '';
		if ( wp_verify_nonce( $ssd_nonce, 'ssd_nonce_ajax' ) ) {
			if ( isset( $_POST['action'] ) ) {
				global $wpdb;
				$feed_id          = ( isset( $_POST['feed_id'] ) && '' !== $_POST['feed_id'] ) ? intval( $_POST['feed_id'] ) : '';
				$feed_type        = ( isset( $_POST['feeds_type'] ) && '' !== $_POST['feeds_type'] ) ? sanitize_text_field( wp_unslash( $_POST['feeds_type'] ) ) : '';
				$feed_posts_table = $wpdb->prefix . 'ssd_feed_posts';
				$table_name       = $wpdb->prefix . 'ssd_feeds';
				$wpdb->update(
					$table_name,
					array(
						'refresh_feed_date' => gmdate( 'Y-m-d H:i:s' ),
					),
					array( 'id' => $feed_id ),
					array(
						'%s',
					),
					array( '%d' )
				);
				$ssd_feeds_actions = new WpSSDFeedsActions();
				$ssd_feeds_actions->init();
				$ssd_feeds_actions->feed_id   = $feed_id;
				$ssd_feeds_actions->feed_type = $feed_type;
				$ssd_feeds_actions->ssd_replace_feed_posts();
			}
		}
		wp_die();
	}
	/**
	 * Function to save order in database
	 *
	 * @return void
	 */
	public function ssd_set_order() {
		$ssd_nonce = isset( $_POST['ssd_nonce'] ) ? sanitize_text_field( wp_unslash( $_POST['ssd_nonce'] ) ) : '';
		if ( wp_verify_nonce( $ssd_nonce, 'ssd_nonce_ajax' ) ) {
			if ( ! empty( $_REQUEST['order'] ) ) {
				$ssd_social_order = ( isset( $_REQUEST['order'] ) ) ? sanitize_text_field( wp_unslash( $_REQUEST['order'] ) ) : '';
				$shortcode_id     = ( isset( $_REQUEST['shortcode_id'] ) ) ? intval( $_REQUEST['shortcode_id'] ) : 0;
				update_option( 'ssd_social_order_' . $shortcode_id, $ssd_social_order );
			}
			wp_die();
		}
	}
	/**
	 * Ajax function if display social icon.
	 *
	 * @return void
	 */
	public function ssd_corner_icon() {
			$ssd_nonce = isset( $_POST['ssd_nonce'] ) ? sanitize_text_field( wp_unslash( $_POST['ssd_nonce'] ) ) : '';
		if ( wp_verify_nonce( $ssd_nonce, 'ssd_nonce_ajax' ) ) {
			if ( ! empty( $_REQUEST['value'] ) ) {
				$ssd_corner_icon = sanitize_text_field( wp_unslash( $_REQUEST['value'] ) );
				if ( 'true' == $ssd_corner_icon ) {
					echo '<option value="left" selected>' . esc_html__( 'Left', 'social-stream-design' ) . '</option><option value="right">' . esc_html__( 'Right', 'social-stream-design' ) . '</option>';
				} else {
					echo '<option value="left" selected>' . esc_html__( 'Left', 'social-stream-design' ) . '</option><option value="right">' . esc_html__( 'Right', 'social-stream-design' ) . '</option><option value="center">' . esc_html__( 'Center', 'social-stream-design' ) . '</option>';
				}
			}
		}
		wp_die();
	}
	/**
	 * Ajax function if display author on.
	 *
	 * @return void
	 */
	public function ssd_update_sticky_on() {
		$ssd_nonce = isset( $_POST['ssd_nonce'] ) ? sanitize_text_field( wp_unslash( $_POST['ssd_nonce'] ) ) : '';
		if ( wp_verify_nonce( $ssd_nonce, 'ssd_nonce_ajax' ) ) {
			if ( ! empty( $_REQUEST['author'] ) ) {
				$ssd_display_author = sanitize_text_field( wp_unslash( $_REQUEST['author'] ) );
				if ( 'true' == $ssd_display_author ) {
					echo '<option value="author">' . esc_html__( 'Author', 'social-stream-design' ) . '</option><option value="media" selected>' . esc_html__( 'Media', 'social-stream-design' ) . '</option>';
				} else {
					echo '<option value="author" disabled>' . esc_html__( 'Author', 'social-stream-design' ) . '</option><option value="media" selected>' . esc_html__( 'Media', 'social-stream-design' ) . '</option>';
				}
			}
		}
		wp_die();
	}
	/**
	 * Reset layout to default.
	 *
	 * @return void
	 */
	public function ssd_reset_layout_settings() {
		$ssd_nonce = isset( $_POST['ssd_nonce'] ) ? sanitize_text_field( wp_unslash( $_POST['ssd_nonce'] ) ) : '';
		if ( wp_verify_nonce( $ssd_nonce, 'ssd_nonce_ajax' ) ) {
			if ( current_user_can( 'manage_options' ) ) {
				if ( isset( $_POST['layout_id'] ) && '' !== $_POST['layout_id'] && '0' !== $_POST['layout_id'] ) {
					global $wpdb;
					$shortcode_table      = $wpdb->prefix . 'ssd_shortcodes';
					$result_settings      = $wpdb->get_var( $wpdb->prepare( "SELECT social_stream_settings FROM $wpdb->prefix" . 'ssd_shortcodes WHERE ID = %d', intval( $_POST['layout_id'] ) ) );
					$settings             = maybe_unserialize( $result_settings );
					$feed_ids             = $settings['feed_ids'];
					$ssd_stream_page      = $settings['ssd_stream_page'];
					$ssd_card_extra_class = $settings['ssd_card_extra_class'];
					$ssd_settings         = array(
						'feed_ids'                       => $feed_ids,
						'ssd_stream_page'                => $ssd_stream_page,
						'ssd_card_extra_class'           => '',
						'ssd_design_layout'              => 'layout-1',
						'ssd_order_by'                   => 'rand',
						'ssd_display_order'              => '0',
						'ssd_display_meta'               => '1',
						'ssd_custom_css'                 => '',
						'ssd_layout'                     => 'listing',
						'ssd_grid_style'                 => 'masonry',
						'ssd_column_type'                => '3',
						'ssd_column_type_laptop'         => '3',
						'ssd_column_type_rotated_tablet' => '2',
						'ssd_column_type_tablet'         => '2',
						'ssd_column_type_rotated_mobile' => '1',
						'ssd_column_type_mobile'         => '1',
						'ssd_column_type_vertical'       => '3',
						'ssd_stream_title'               => '',
						'ssd_heading_color'              => '',
						'ssd_stream_subtitle'            => '',
						'ssd_subheading_color'           => '',
						'ssd_heading_alignment'          => 'center',
						'ssd_display_share_with'         => '0',
						'ssd_theme_color'                => '#f93d66',
						'ssd_overlay_bg_color'           => '#ffffff',
						'ssd_overlay_padding_top'        => '0',
						'ssd_overlay_padding_bottom'     => '0',
						'ssd_overlay_padding_left'       => '0',
						'ssd_overlay_padding_right'      => '0',
						'ssd_card_box_hoffset'           => '0',
						'ssd_card_box_voffset'           => '0',
						'ssd_card_box_blur'              => '4',
						'ssd_card_box_spread'            => '0',
						'ssd_card_box_shadow'            => '#cccccc',
						'ssd_display_social_icon'        => '1',
						'ssd_social_share_type'          => 'text',
						'ssd_display_image'              => '0',
						'ssd_image_layout'               => 'ssd_image_layout_1',
						'ssd_display_corner_icon'        => '0',
						'ssd_display_sticky'             => '1',
						'ssd_display_sticky_on'          => 'media',
						'ssd_icon_border_radius'         => '0',
						'ssd_icon_border_radius_type'    => '%',
						'ssd_text_border_radius'         => '0',
						'ssd_text_border_radius_type'    => 'px',
						'ssd_icon_alignment'             => 'left',
						'ssd_icon_position'              => 'top',
						'ssd_icon_color'                 => '',
						'ssd_icon_bg_color'              => '',
						'ssd_display_title'              => '1',
						'ssd_title_font_size'            => '16',
						'ssd_title_color'                => '#333333',
						'ssd_title_hover_color'          => '#f93d66',
						'ssd_display_content'            => '1',
						'ssd_content_limit'              => '50',
						'ssd_content_font_size'          => '14',
						'ssd_content_color'              => '#333333',
						// 'ssd_content_hover_color'        => '#f93d66',
						'ssd_display_feed_without_media' => '1',
						'ssd_display_default_image'      => '0',
						'ssd_default_image_id'           => '',
						'ssd_default_image_src'          => '',
						'ssd_display_author_box'         => '1',
						'ssd_author_border_radius'       => '0',
						'ssd_author_border_radius_type'  => 'px',
						'ssd_author_bg_color'            => '',
						'ssd_author_title_color'         => '#f93d66',
						'ssd_author_title_hover_color'   => '#333333',
						'ssd_author_meta_color'          => '#666666',
						'ssd_view_user_name'             => '1',
						'ssd_view_date'                  => '1',
						'ssd_count_meta_color'           => '#666666',
						'ssd_count_bg_color'             => '',
						'ssd_count_meta_hover_color'     => '#f93d66',
						'ssd_count_border_top_width'     => '1',
						'ssd_count_border_top_type'      => 'solid',
						'ssd_count_border_top_color'     => '#e5e5e5',
						'ssd_count_border_bottom_width'  => '0',
						'ssd_count_border_bottom_type'   => 'solid',
						'ssd_count_border_bottom_color'  => '',
						'ssd_count_border_right_width'   => '0',
						'ssd_count_border_right_type'    => 'solid',
						'ssd_count_border_right_color'   => '',
						'ssd_count_border_left_width'    => '0',
						'ssd_count_border_left_type'     => 'solid',
						'ssd_count_border_left_color'    => '#e5e5e5',
						'ssd_count_padding_top'          => '10',
						'ssd_count_padding_bottom'       => '10',
						'ssd_count_margin_top'           => '0',
						'ssd_count_margin_bottom'        => '10',
						'ssd_user_follower_count'        => '1',
						'ssd_user_friend_count'          => '1',
						'ssd_retweet_count'              => '1',
						'ssd_reply_link'                 => '1',
						'ssd_favorite_count'             => '1',
						'ssd_view_count'                 => '1',
						'ssd_like_count'                 => '1',
						'ssd_pin_count'                  => '1',
						'ssd_dislike_count'              => '1',
						'ssd_comment_count'              => '1',
						'ssd_pagination_type'            => 'no_pagination',
						'ssd_pagination_layout'          => 'template-1',
						'ssd_load_more_layout'           => 'template-1',
						'ssd_load_more_effect'           => 'eff-fadein',
						'ssd_no_of_posts_per_page'       => '9',
						'ssd_no_of_posts'                => '20',
					);
					$wpdb->update(
						$shortcode_table,
						array( 'social_stream_settings' => maybe_serialize( $ssd_settings ) ),
						array( 'ID' => intval( $_POST['layout_id'] ) ),
						array( '%s' ),
						array( '%d' )
					);
					echo 'success';
				}
			}
		}
		wp_die();
	}
}
new WpSocialStream();
