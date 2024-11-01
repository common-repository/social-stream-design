<?php
/**
 * Class for feed list table
 *
 * @version 1.0
 * @package WP Social Stream Designer
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}
/**
 * WP_List_Table is not loaded automatically so we need to load it in our application.
 */
if ( ! class_exists( 'WP_List_Table' ) ) {
	require_once ABSPATH . 'wp-admin/includes/class-wp-list-table.php';
}
/**
 * Create a new table class that will extend the WP_List_Table
 */
class Feed_List_Table_WP extends WP_List_Table {
	/**
	 * Inital Setup
	 */
	public function __construct() {
		parent::__construct(
			array(
				'singular' => 'feed',
				'plural'   => 'feeds',
				'ajax'     => false,
			)
		);
	}
	/**
	 * Get a list of columns. The format is:
	 *
	 * @see WP_List_Table::::single_row_columns()
	 * @return array An associative array containing column information.
	 */
	public function get_columns() {
		$columns = array(
			'cb'                => '<input type="checkbox" />',
			'feed_name'         => esc_html__( 'Feed Name', 'social-stream-designer' ),
			'feed'              => esc_html__( 'Stream', 'social-stream-designer' ),
			'feed_type'         => esc_html__( 'Feed Type', 'social-stream-designer' ),
			'last_refresh_time' => esc_html__( 'Last Refresh Time', 'social-stream-designer' ),
			'feed_refresh_time' => esc_html__( 'Feed Refresh Time', 'social-stream-designer' ),
			'live_status'       => esc_html__( 'Live Status', 'social-stream-designer' ),
			'status'            => esc_html__( 'Status', 'social-stream-designer' ),
		);
		return $columns;
	}


	function get_bulk_actions() {
		$actions = array(
			'delete'    => 'Delete'
		);
		return $actions;
		}


		function column_cb( $item ) {
			return sprintf( '<input type="checkbox" name="id[]" value="%s" />', $item['id']  );
	
		}  
	
		public function process_bulk_action() {
			global $wpdb;
			$table_name = $wpdb->prefix.'ssd_feeds'; 
	
			if ( 'delete' === $this->current_action() ) {
				if ( isset( $_REQUEST['id'] ) && is_array( $_REQUEST['id'] ) ) {
					$ids = isset( $_REQUEST['id'] ) ? array_map( 'sanitize_text_field', wp_unslash( $_REQUEST['id'] ) ) : array();
				} else {
					$ids = isset( $_REQUEST['id'] ) ? sanitize_text_field( wp_unslash( $_REQUEST['id'] ) ) : '';
				}
				if ( is_array( $ids ) ) {
					$ids = implode( ',', $ids );
				}
				if ( ! empty( $ids ) ) {
					$wpdb->query( "DELETE FROM $table_name WHERE id IN($ids)" );
					
				}
			}
		}
		protected function get_sortable_columns(){
			$sortable_columns = array(
				  'feed_name'  => array('feed_name', false),
				  'feed'  => array('feed', false),      
			);
			return $sortable_columns;
		 }
	
	

	/**
	 * Get default column value.
	 * For more detailed insight into how columns are handled, take a look at
	 * WP_List_Table::single_row_columns()
	 *
	 * @param object $item        A singular item (one full row's worth of data).
	 * @param string $column_name The name/slug of the column to be processed.
	 * @return string Text or HTML to be placed inside the column <td>.
	 */
	protected function column_default( $item, $column_name ) {
		global $wpdb;
		$feed_id                = $item['id'];
		$feed_post_count        = $wpdb->get_var( $wpdb->prepare( "SELECT COUNT(*) FROM $wpdb->prefix" . 'ssd_feed_posts WHERE feed_id = %d', $feed_id ) );
		$feeds_settings         = maybe_unserialize( $item['feeds_settings'] );
		$last_refresh_feed_time = ( isset( $item['refresh_feed_date'] ) && '' !== $item['refresh_feed_date'] ) ? $item['refresh_feed_date'] : '';
		$feed_refresh_time      = self::ssd_refresh_time_elapsed_string( $last_refresh_feed_time );
		$feed_status            = ( isset( $feeds_settings['feed_status'] ) && '' !== $feeds_settings['feed_status'] ) ? $feeds_settings['feed_status'] : '';
		$feed_status_live       = ( isset( $feeds_settings['feed_status_live'] ) && '' !== $feeds_settings['feed_status_live'] ) ? $feeds_settings['feed_status_live'] : 'Live';
		$feed_type              = $feeds_settings['feed'];
		$feed_type              = explode( '-', $feed_type );
		$feed_type              = $feed_type[0];
		$feed_stream_type       = ( isset( $feeds_settings[ 'feed_type_' . $feed_type ] ) && '' !== $feeds_settings[ 'feed_type_' . $feed_type ] ) ? $feeds_settings[ 'feed_type_' . $feed_type ] : '-';
		$feed_stream_type       = str_replace( '_', ' ', $feed_stream_type );
		$refresh_feed_on_number = ( isset( $feeds_settings['refresh_feed_on_number'] ) && '' !== $feeds_settings['refresh_feed_on_number'] ) ? $feeds_settings['refresh_feed_on_number'] : '';
		$refresh_feed_on_dd     = ( isset( $feeds_settings['refresh_feed_on_dd'] ) && '' !== $feeds_settings['refresh_feed_on_dd'] ) ? $feeds_settings['refresh_feed_on_dd'] : '';
		$checked_a              = '';
		$checked_ia             = '';
		$checked_live           = '';
		$checked_notlive        = '';
		if ( 'Active' === $feed_status ) {
			$checked_a = 'checked';
		} elseif ( 'Inactive' === $feed_status ) {
			$checked_ia = 'checked';
		}
		if ( 'Live' === $feed_status_live ) {
			$checked_live = 'checked';
		} elseif ( 'NotLive' === $feed_status_live ) {
			$checked_notlive = 'checked';
		}
		switch ( $column_name ) {
			case 'id':
				case 'feed_name':
					return $feeds_settings['feed_name'];
				case 'feed':
					return ucfirst( $feed_type ) . '(' . $feed_post_count . ')';
			case 'feed_type':
				return ucfirst( $feed_stream_type );
			case 'last_refresh_time':
				return $feed_refresh_time;
			case 'feed_refresh_time':
				return $refresh_feed_on_number . ' ' . $refresh_feed_on_dd;
			case 'live_status':
				return '<div class="radio-group feeds">
                    <input type="radio" id="ssd_feed_display_status_radio_active' . $item['id'] . '" class="ssd_feed_display_status_radio active feed_display_status_' . $item['id'] . '" name="feed_stream[feed_status_live][' . $item['id'] . ']" data-id="' . $item['id'] . '" ' . $checked_live . ' value="Live" ><label for="ssd_feed_display_status_radio_active' . $item['id'] . '">Yes</label>'
					. '<input type="radio" id="ssd_feed_display_status_radio_inactive' . $item['id'] . '" class="ssd_feed_display_status_radio inactive feed_display_status_' . $item['id'] . '" name="feed_stream[feed_status_live][' . $item['id'] . ']" data-id="' . $item['id'] . '" ' . $checked_notlive . ' value="NotLive"><label for="ssd_feed_display_status_radio_inactive' . $item['id'] . '">No</label>
                </div>';
			case 'status':
				return '<div class="radio-group feeds">
                            <input type="radio" id="ssd_feed_status_radio_active' . $item['id'] . '" class="ssd_feed_status_radio active feed_status_' . $item['id'] . '" name="feed_stream[feed_status][' . $item['id'] . ']" data-id="' . $item['id'] . '" ' . $checked_a . ' value="Active" ><label for="ssd_feed_status_radio_active' . $item['id'] . '">Active</label>'
					. '<input type="radio" id="ssd_feed_status_radio_inactive' . $item['id'] . '" class="ssd_feed_status_radio inactive feed_status_' . $item['id'] . '" name="feed_stream[feed_status][' . $item['id'] . ']" data-id="' . $item['id'] . '" ' . $checked_ia . ' value="Inactive"><label for="ssd_feed_status_radio_inactive' . $item['id'] . '">Inactive</label>
                        </div>';
			default:
		}
	}
	/**
	 * Get column Feed.
	 *
	 * @param string $item Feed item.
	 * @return string
	 */
	protected function column_feed_name( $item ) {
		if ( isset( $_POST['nonce'] ) ) {
			$nonce = sanitize_text_field( wp_unslash( $_POST['nonce'] ) );
			if ( ! wp_verify_nonce( $nonce, 'column-feed-nonce' ) ) {
				wp_send_json_error( array( 'status' => 'Nonce error' ) );
				die();
			}
		}
		$req_page       = isset( $_REQUEST['page'] ) ? sanitize_text_field( wp_unslash( $_REQUEST['page'] ) ) : '';
		$actions        = array(
			'edit'   => sprintf( '<a href="?page=social-stream-designer-social-feed&action=%s&id=%s">Edit</a>', 'edit', $item['id'] ),
			'delete' => sprintf( '<a onclick="return ssd_delele()" href="?page=%s&action=%s&id=%s">Delete</a>', $req_page, 'delete', $item['id'] ),
		);
		$feeds_settings = maybe_unserialize( $item['feeds_settings'] );
		/* Return the title contents. */
		return sprintf(
			'<a href="?page=social-stream-designer-social-feed&action=%1$s&id=%2$s"><b>%3$s</b></a><span style="color:silver"></span>%4$s',
			/* $1%s */ 'edit',
			/* $2%s */ $item['id'],
			/* $3%s */ $feeds_settings['feed_name'],
			/* $4%s */ $this->row_actions( $actions )
		);
	}
	/**
	 * Prepares the list of items for displaying.
	 *
	 * @global wpdb $wpdb
	 * @uses $this->_column_headers$this->_column_headers
	 * @uses $this->items
	 * @uses $this->get_columns()
	 * @uses $this->get_pagenum()
	 * @uses $this->set_pagination_args()
	 * @return void
	 */
	public function prepare_items() {
		global $wpdb;
		$per_page              = get_user_meta( get_current_user_id(), 'ssd_feeds_per_page', true );
		$per_page              = '' !== $per_page ? $per_page : 10;
		$columns               = $this->get_columns();
		$hidden                = array();
		$sortable = $this->get_sortable_columns();
		$this->_column_headers = array( $columns, $hidden, $sortable );
		$this->process_bulk_action();
		$data = $wpdb->get_results( "SELECT * FROM $wpdb->prefix" . 'ssd_feeds', ARRAY_A );
		usort( $data, array($this,'usort_reorder' ) );
		$current_page = $this->get_pagenum();
		$total_items  = count( $data );
		$data         = array_slice( $data, ( ( $current_page - 1 ) * $per_page ), $per_page );
		$this->items  = $data;
		$this->set_pagination_args(
			array(
				'total_items' => $total_items, /* WE have to calculate the total number of items. */
				'per_page'    => $per_page, /* WE have to determine how many items to show on a page. */
				'total_pages' => ceil( $total_items / $per_page ), /* WE have to calculate the total number of pages.*/
			)
		);
	}
	/**
	 * Callback to allow sorting of example data.
	 *
	 * @param string $a First value.
	 * @param string $b Second value.
	 * @return int
	 */
	/**
	 * Callback to allow sorting of example data.
	 *
	 * @param string $a First value.
	 * @param string $b Second value.
	 * @return int
	 */
	public function usort_reorder( $a, $b ) {
		if ( isset( $_POST['nonce'] ) ) {
			$nonce = sanitize_text_field( wp_unslash( $_POST['nonce'] ) );
			if ( ! wp_verify_nonce( $nonce, 'unsort-reorder-nonce' ) ) {
				wp_send_json_error( array( 'status' => 'Nonce error' ) );
				die();
			}
		}
		/**
		 * If no sort, default to shortcode_name.
		 */
		$orderby = ! empty( $_REQUEST['orderby'] ) ? sanitize_text_field( wp_unslash( $_REQUEST['orderby'] ) ) : 'id';
		/**
		 * WPCS: Input var ok.
		 * If no order, default to asc.
		 */
		$order = ! empty( $_REQUEST['order'] ) ? sanitize_text_field( wp_unslash( $_REQUEST['order'] ) ) : 'asc';
		if ( $orderby != 'id' ){
			$a_settings = unserialize($a['feeds_settings']);
			$b_settings = unserialize($b['feeds_settings']);
			// Normalize the case to lowercase for comparison
			$a_feed = strtolower($a_settings[$orderby]);
			$b_feed = strtolower($b_settings[$orderby]);

			// Compare the normalized 'feed' values
			$result = strcmp($a_feed, $b_feed);
		}else{	
			/**
			 * WPCS: Input var ok.
			 * Determine sort order.
			 */
			$result = strcmp( $a[ $orderby ], $b[ $orderby ] );
		}
		return ( 'asc' === $order ) ? $result : - $result;	
	}
	/**
	 * Function to get last refresh feed difference time.
	 *
	 * @param string $date_time First value.
	 * @param string $full Second value.
	 * @return string
	 */
	public function ssd_refresh_time_elapsed_string( $date_time, $full = false ) {
		$now    = new DateTime();
		$ago    = new DateTime( $date_time );
		$diff   = $now->diff( $ago );
		$string = array(
			'y' => 'year',
			'm' => 'month',
			'h' => 'hour',
			'i' => 'minute',
			's' => 'second',
		);
		foreach ( $string as $k => &$v ) {
			if ( $diff->$k ) {
				$v = $diff->$k . ' ' . $v . ( $diff->$k > 1 ? 's' : '' );
			} else {
				unset( $string[ $k ] );
			}
		}
		if ( ! $full ) {
			$string = array_slice( $string, 0, 1 );
		}
		return $string ? implode( ', ', $string ) . ' ' . esc_html__( 'ago', 'social-stream-design' ) : esc_html__( 'just now', 'social-stream-design' );
	}
}
