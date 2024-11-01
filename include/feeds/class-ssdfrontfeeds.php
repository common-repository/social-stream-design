<?php
/**
 * File to Social stream
 *
 * @version 1.0
 * @package WP Social Stream Designer
 */

/**
 * Get Twitter stream data
 *
 * @return array
 */
class WpSSDFrontFeeds {

	/**
	 * Feed Stream
	 *
	 * @var string
	 */
	public $feeds_stream;

	/**
	 * Feed id
	 *
	 * @var string
	 */
	public $feed_id = '';

	/**
	 * Feed post table
	 *
	 * @var string
	 */
	public $feed_posts_table = '';

	/**
	 * Current Page
	 *
	 * @var string
	 */
	public $cur_page = 1;

	/**
	 * Post per page
	 *
	 * @var string
	 */
	public $posts_per_page = 6;

	/**
	 * Max number of posts
	 *
	 * @var string
	 */
	public $max_number_of_posts = '1000';

	/**
	 * Pagination
	 *
	 * @var string
	 */
	public $pagination = '0';
	/**
	 * Display Order
	 *
	 * @var string
	 */
	public $ssd_display_order = '';
	/**
	 * Order By
	 *
	 * @var string
	 */
	public $ssd_order_by = '';
	/**
	 * Search
	 *
	 * @var string
	 */
	public $ssd_search = '';
	/**
	 * Search
	 *
	 * @var string
	 */
	public $ssd_display_feed_without_media = '';

	/**
	 * Initialize .
	 *
	 * @since    1.0.0
	 */
	public function init() {
		global $wpdb;
		$feed_posts_table       = $wpdb->prefix . 'ssd_feed_posts';
		$this->feed_posts_table = $feed_posts_table;
	}

	/**
	 * Get Stream
	 *
	 * @since  1.0.0
	 * @return string
	 */
	public function ssd_get_stream() {
		global $wpdb;
		$feed_posts_table               = $wpdb->prefix . 'ssd_feed_posts';
		$feed_id                        = $this->feed_id;
		$cur_page                       = $this->cur_page;
		$posts_per_page                 = $this->posts_per_page;
		$max_number_of_posts            = $this->max_number_of_posts;
		$ssd_order                      = $this->ssd_display_order;
		$ssd_order_by                   = $this->ssd_order_by;
		$ssd_search                     = $this->ssd_search;
		$ssd_order                      = (int) $ssd_order;
		$ssd_display_feed_without_media = $this->ssd_display_feed_without_media;
		$ssd_display_feed_without_media = (int) $ssd_display_feed_without_media;
		if ( 1 == $ssd_order ) {
			$order = 'ASC';
		} else {
			$order = 'DESC';
		}
		if ( 'date' === $ssd_order_by ) {
			$order_by = 'ORDER BY post_date';
		} elseif ( 'social-media' === $ssd_order_by ) {
			$order_by = 'ORDER BY feed_type';
		} else {
			$order_by = 'ORDER BY rand()';
		}
		if ( ! empty( $feed_id ) ) {
			if ( '' !== $ssd_search ) {
				if ( 1 == $ssd_display_feed_without_media ) {
					if ( is_user_logged_in() && current_user_can( 'manage_options' ) ) {
						$query = $wpdb->prepare( "SELECT  * FROM {$wpdb->prefix}ssd_feed_posts WHERE feed_id IN ( %d ) AND (post_title LIKE %s OR post_description LIKE %s) %1s %1s", $feed_id, '%' . $ssd_search . '%', '%' . $ssd_search . '%', $order_by, $order );
					} elseif ( is_user_logged_in() && ! current_user_can( 'manage_options' ) ) {
						$query = $wpdb->prepare( "SELECT  * FROM {$wpdb->prefix}ssd_feed_posts WHERE feed_id IN ( %d ) AND (post_title LIKE %s OR post_description LIKE %s) AND (( moderate = '1' AND lower(post_status) = 'yes'))  %1s %1s", $feed_id, '%' . $ssd_search . '%', '%' . $ssd_search . '%', $order_by, $order );
					} else {
						$query = $wpdb->prepare( "SELECT  * FROM {$wpdb->prefix}ssd_feed_posts WHERE feed_id IN ( %d ) AND (post_title LIKE %s OR post_description LIKE %s) AND (( moderate = '1' AND lower(post_status) = 'yes') OR (moderate IS NULL or moderate ='')) %1s %1s", $feed_id, '%' . $ssd_search . '%', '%' . $ssd_search . '%', $order_by, $order );
					}
				} else {
					if ( is_user_logged_in() && current_user_can( 'manage_options' ) ) {
						$query = $wpdb->prepare( "SELECT * FROM {$wpdb->prefix}ssd_feed_posts WHERE feed_id IN ( %d ) AND ((type_in = 'video' AND post_video != '') OR (type_in = 'image' AND post_image != '')) AND (post_title LIKE %s OR post_description LIKE %s) %1s %1s", $feed_id, '%' . $ssd_search . '%', '%' . $ssd_search . '%', $order_by, $order );
					} elseif ( is_user_logged_in() && ! current_user_can( 'manage_options' ) ) {
						$query = $wpdb->prepare( "SELECT * FROM {$wpdb->prefix}ssd_feed_posts WHERE feed_id IN ( %d ) AND ((type_in = 'video' AND post_video != '') OR (type_in = 'image' AND post_image != '')) AND (post_title LIKE %s OR post_description LIKE %s) AND (( moderate = '1' AND lower(post_status) = 'yes'))  %1s %1s", $feed_id, '%' . $ssd_search . '%', '%' . $ssd_search . '%', $order_by, $order );
					} else {
						$query = $wpdb->prepare( "SELECT * FROM {$wpdb->prefix}ssd_feed_posts WHERE feed_id IN ( %d ) AND ((type_in = 'video' AND post_video != '') OR (type_in = 'image' AND post_image != '')) AND (post_title LIKE %s OR post_description LIKE %s) AND (( moderate = '1' AND lower(post_status) = 'yes') OR (moderate IS NULL or moderate =''))  %1s %1s", $feed_id, '%' . $ssd_search . '%', '%' . $ssd_search . '%', $order_by, $order, );
					}
				}
			} else {
				if ( 1 == $ssd_display_feed_without_media ) {
					if ( is_user_logged_in() && current_user_can( 'manage_options' ) ) {
						$query = $wpdb->prepare( "select * from {$wpdb->prefix}ssd_feed_posts where feed_id in ( %d ) %1s %1s", $feed_id, $order_by, $order );
					} elseif ( is_user_logged_in() && ! current_user_can( 'manage_options' ) ) {
						$query = $wpdb->prepare( "select * from {$wpdb->prefix}ssd_feed_posts where feed_id in ( %d ) AND (( moderate = '1' AND lower(post_status) = 'yes')) %1s %1s", $feed_id, $order_by, $order );
					} else {
						$query = $wpdb->prepare( "select * from {$wpdb->prefix}ssd_feed_posts where feed_id in ( %d ) AND (( moderate = '1' AND lower(post_status) = 'yes') OR (moderate IS NULL or moderate ='')) %1s %1s", $feed_id, $order_by, $order );
					}
				} else {
					if ( is_user_logged_in() && current_user_can( 'manage_options' ) ) {
						$query = $wpdb->prepare( "select * from {$wpdb->prefix}ssd_feed_posts where feed_id in ( %d ) AND ((type_in = 'video' AND post_video != '') OR (type_in = 'image' AND post_image != '')) %1s %1s", $feed_id, $order_by, $order );
					} if ( is_user_logged_in() && ! current_user_can( 'manage_options' ) ) {
						$query = $wpdb->prepare( "select * from {$wpdb->prefix}ssd_feed_posts where feed_id in ( %d ) AND ((type_in = 'video' AND post_video != '') OR (type_in = 'image' AND post_image != '')) AND (( moderate = '1' AND lower(post_status) = 'yes')) %1s %1s", $feed_id, $order_by, $order );
					} else {
						$query = $wpdb->prepare( "select * from {$wpdb->prefix}ssd_feed_posts where feed_id in ( %d ) AND ((type_in = 'video' AND post_video != '') OR (type_in = 'image' AND post_image != '')) AND (( moderate = '1' AND lower(post_status) = 'yes') OR (moderate IS NULL or moderate ='')) %1s %1s", $feed_id, $order_by, $order );
					}
				}
			}
			$limit_start = 0;
			if ( '1' !== $this->pagination ) {
				$limit_start = ( $cur_page - 1 ) * ( $posts_per_page );
			}
			$limit_end = $limit_start + $posts_per_page;
			if ( $limit_end > $max_number_of_posts ) {
				$posts_per_page = $posts_per_page - ( $limit_end - $max_number_of_posts );
			}
			$query       .= " limit $limit_start,$posts_per_page";
			$feeds_stream = $wpdb->get_results( $query );
			if ( $feeds_stream ) {
				return $feeds_stream;
			} else {
				return esc_html__( 'No feeds found', 'social-stream-design' );
			}
		}
	}

	/**
	 * Total Stream
	 *
	 * @since  1.0.0
	 * @return string
	 */
	public function ssd_total_stream() {
		global $wpdb;
		$feed_posts_table = $wpdb->prefix . 'ssd_feed_posts';
		$feed_id          = $this->feed_id;
		if ( is_user_logged_in() ) {
			$feeds_stream = $wpdb->prepare( 'select count(*) from %1s where feed_id in ( %s	 )', $feed_posts_table, $feed_id );

		} else {
			$feeds_stream = $wpdb->prepare( 'select count(*) from %1s where feed_id in ( %s ) AND (( moderate = "1" AND lower(post_status) = "yes") OR (moderate IS NULL or moderate ="")) ', $feed_posts_table, $feed_id );
		}

		if ( $feeds_stream ) {
			return $feeds_stream;

		} else {
			return '0';
		}
	}

	/**
	 * With out image
	 *
	 * @since  1.0.0
	 * @return string
	 */
	public function ssd_total_stream_without_image() {
		global $wpdb;
		$feed_posts_table = $wpdb->prefix . 'ssd_feed_posts';
		$feed_id          = $this->feed_id;
		if ( is_user_logged_in() ) {
			$query = 'select count(*) from ' . $feed_posts_table . ' where feed_id in ( ' . $feed_id . " ) and post_image !='' ";
		} else {
			$feeds_stream = $wpdb->get_var(
				$wpdb->prepare(
					"SELECT COUNT(*) FROM %s WHERE feed_id IN (%d) AND post_image != '' AND ((moderate = '1' AND LOWER(post_status) = 'yes') OR (moderate IS NULL OR moderate = ''))",
					$feed_posts_table,
					$feed_id
				)
			);
		}
		// = $wpdb->get_var( $query );
		if ( isset( $feeds_stream ) ) {
			return $feeds_stream;
		} else {
			return '0';
		}
	}

}
