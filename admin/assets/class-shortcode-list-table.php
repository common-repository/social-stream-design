<?php
/**
 * Class for Shorcode list table
 *
 * @version 1.0
 * @package WP Social Stream Designer
 */

/**
 * File to display all layouts
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}
// WP_List_Table is not loaded automatically so we need to load it in our application.
if ( ! class_exists( 'WP_List_Table' ) ) {
	require_once ABSPATH . 'wp-admin/includes/class-wp-list-table.php';
}

/**
 * Create a new table class that will extend the WP_List_Table
 */
class Shortcode_List_Table_WP extends WP_List_Table {
	/**
	 * Initial set up
	 */
	public function __construct() {
		parent::__construct(
			array(
				'singular' => 'shortcode',
				'plural'   => 'shortcodes',
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
			'cb'        => '<input type="checkbox" />',		
			'shortcode_name'     => esc_html__( 'Social Stream Layout Name', 'social-stream-design' ),
			'social_stream_name' => esc_html__( 'Social Stream Name', 'social-stream-design' ),
			'template_name'      => esc_html__( 'Template Name', 'social-stream-design' ),
			'shortcode'          => esc_html__( 'Shortcode', 'social-stream-design' ),
		);
		return $columns;
	}

	function get_bulk_actions() {
		$actions = array(
			'delete'    => 'Delete'
		);
		return $actions;
		}

	
	/**
	 * Get a list of sortable columns. The format is:
	 * 'internal-name' => 'orderby'
	 * or
	 * 'internal-name' => array( 'orderby', true )
	 *
	 * @return array An associative array containing all the columns that should be sortable.
	 */
	protected function get_sortable_columns() {
		$sortable_columns = array();
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
		switch ( $column_name ) {
			case 'shortcode_name':
				return sprintf( '<a href="?page=social-stream-designer-add-layouts&action=%s&id=%s">%s</a>', 'edit', $item['ID'], $item[ $column_name ] );
			case 'social_stream_name':
				return ucwords( str_replace( '-', ' ', $item[ $column_name ] ) );
			case 'template_name':
				$col_val = maybe_unserialize( $item['social_stream_settings'] );
				return $col_val['ssd_design_layout'];
			case 'shortcode':
				$shortcode = "[social_stream_feeds id=\"{$item['ID']}\"]";
				return "<input type='text' readonly value='" . $shortcode . "'>";
			default:
		}
	}
	/**
	 * Shortcode name
	 *
	 * @param object $item A singular item (one full row's worth of data).
	 * @return string
	 */
	protected function column_shortcode_name( $item ) {
		global $wpdb;
		if ( isset( $_POST['nonce'] ) ) {
			$nonce = sanitize_text_field( wp_unslash( $_POST['nonce'] ) );
			if ( ! wp_verify_nonce( $nonce, 'column-shortcode-nonce' ) ) {
				wp_send_json_error( array( 'status' => 'Nonce error' ) );
				die();
			}
		}
		$id             = $item['ID'];
		$source_records = $wpdb->get_row( $wpdb->prepare( "select social_stream_settings from $wpdb->prefix" . 'ssd_shortcodes where ID = %d', $id ) );
		if ( $source_records ) {
			$source_social_stream_settings = maybe_unserialize( $source_records->social_stream_settings );
			$page_id                       = $source_social_stream_settings['ssd_stream_page'];
		}
				$page_r = isset( $_REQUEST['page'] ) ? sanitize_text_field( wp_unslash( $_REQUEST['page'] ) ) : '';
		$actions        = array(
			'edit'      => '<a href="?page=social-stream-designer-add-layouts&action=edit&id=' . $item['ID'] . '">' . esc_html__( 'Edit', 'social-stream-design' ) . '</a>',
			'duplicate' => '<a class="duplicate_shortcode" href="?page=' . $page_r . '&action=duplicate&id=' . $item['ID'] . '">' . esc_html__( 'Duplicate', 'social-stream-design' ) . '</a>',
			'delete'    => '<a onclick="return ssd_delele()" href="?page=' . $page_r . '&action=delete&id=' . $item['ID'] . '">' . esc_html__( 'Delete', 'social-stream-design' ) . '</a>',
		);
		if ( $page_id > 0 ) {
			$actions['view'] = '<a href="' . get_permalink( $page_id ) . '">' . esc_html__( 'View', 'social-stream-design' ) . '</a>';
		}
		// Return the title contents.
		return sprintf(
			'<a href="?page=social-stream-designer-add-layouts&action=%1$s&id=%2$s"><b>%3$s</b></a><span style="color:silver"></span>%4$s',
			/* $1%s */ 'edit',
			/* $2%s */ $item['ID'],
			/* $3%s */ $item['shortcode_name'],
			/* $4%s */ $this->row_actions( $actions )
		);
	}

	/**
	 * Get value for checkbox column.
	 *
	 * @param object $item A singular item (one full row's worth of data).
	 * @return string Text to be placed inside the column <td>.
	 */
	 function column_cb( $item ) {
		return sprintf( '<input type="checkbox" name="id[]" value="%s" />', $item['ID']  );

	}  

	public function process_bulk_action() {
		global $wpdb;
		$table_name = $wpdb->prefix.'ssd_shortcodes';

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
				$wpdb->query( "DELETE FROM $table_name WHERE ID IN($ids)" );
				
			}
		}
	}

	/**
	 * Get shortcode_name column value.
	 *
	 * @param object $item A singular item (one full row's worth of data).
	 * @return string Text to be placed inside the column <td>.
	 */
	protected function column_title( $item ) {
		if ( isset( $_POST['nonce'] ) ) {
			$nonce = sanitize_text_field( wp_unslash( $_POST['nonce'] ) );
			if ( ! wp_verify_nonce( $nonce, 'column-title-nonce' ) ) {
				wp_send_json_error( array( 'status' => 'Nonce error' ) );
				die();
			}
		}
		$page = isset( $_REQUEST['page'] ) ? sanitize_text_field( wp_unslash( $_REQUEST['page'] ) ) : '';
		/* Build edit row action. */
		$edit_query_args = array(
			'page'      => $page,
			'action'    => 'edit',
			'shortcode' => $item['ID'],
		);
		$actions['edit'] = sprintf(
			'<a href="%1$s">%2$s</a>',
			esc_url( wp_nonce_url( add_query_arg( $edit_query_args, 'admin.php' ), 'editshortcode_' . $item['ID'] ) ),
			esc_html__( 'Edit', 'social-stream-design' )
		);
		/* Build delete row action. */
		$delete_query_args = array(
			'page'      => $page,
			'action'    => 'delete',
			'shortcode' => $item['ID'],
		);
		$actions['delete'] = sprintf(
			'<a href="%1$s">%2$s</a>',
			esc_url( wp_nonce_url( add_query_arg( $delete_query_args, 'admin.php' ), 'deleteshortcode_' . $item['ID'] ) ),
			esc_html__( 'Delete', 'social-stream-design' )
		);
		/* Return the shortcode_name contents. */
		return sprintf(
			'%1$s <span style="color:silver;">(id:%2$s)</span>%3$s',
			$item['shortcode_name'],
			$item['ID'],
			$this->row_actions( $actions )
		);
	}
	/**
	 * Prepares the list of items for displaying.
	 *
	 * @global wpdb $wpdb
	 * @uses $this->_column_headers
	 * @uses $this->items
	 * @uses $this->get_columns()
	 * @uses $this->get_sortable_columns()
	 * @uses $this->get_pagenum()
	 * @uses $this->set_pagination_args()
	 * @return void
	 */
	public function prepare_items() {
		global $wpdb;
		if ( isset( $_POST['nonce'] ) ) {
			$nonce = sanitize_text_field( wp_unslash( $_POST['nonce'] ) );
			if ( ! wp_verify_nonce( $nonce, 'prepare-item-nonce' ) ) {
				wp_send_json_error( array( 'status' => 'Nonce error' ) );
				die();
			}
		}
		$orderby_query = '';
		$orderby       = ( isset( $_REQUEST['orderby'] ) && '' !== sanitize_text_field( wp_unslash( $_REQUEST['orderby'] ) ) ) ? sanitize_text_field( wp_unslash( $_REQUEST['orderby'] ) ) : 'shortcode_name';
		$order         = ( isset( $_REQUEST['order'] ) && '' !== sanitize_text_field( wp_unslash( $_REQUEST['order'] ) ) ) ? sanitize_text_field( wp_unslash( $_REQUEST['order'] ) ) : 'asc';
		if ( isset( $orderby ) && '' !== $orderby && isset( $order ) && '' !== $order ) {
			$orderby_query .= " ORDER BY $orderby $order";
		}
		$per_page              = get_user_meta( get_current_user_id(), 'ssd_items_per_page', true );
		$per_page              = '' !== $per_page ? $per_page : 10;
		$columns               = $this->get_columns();
		$hidden                = array();
		$sortable              = $this->get_sortable_columns();
		$this->_column_headers = array( $columns, $hidden, $sortable );
		$this->process_bulk_action();
		$data = $wpdb->get_results( $wpdb->prepare( "SELECT * FROM {$wpdb->prefix}ssd_shortcodes %1s", $orderby_query ), ARRAY_A );
		self::usort( $data, 'usort_reorder' );
		$current_page = $this->get_pagenum();
		$total_items  = count( $data );
		$data         = array_slice( $data, ( ( $current_page - 1 ) * $per_page ), $per_page );
		$this->items  = $data;
		$this->set_pagination_args(
			array(
				'total_items' => $total_items, /* WE have to calculate the total number of items. */
				'per_page'    => $per_page, /* WE have to determine how many items to show on a page. */
				'total_pages' => ceil( $total_items / $per_page ),   /* WE have to calculate the total number of pages. */
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
	protected function usort_reorder( $a, $b ) {
		if ( isset( $_POST['nonce'] ) ) {
			$nonce = sanitize_text_field( wp_unslash( $_POST['nonce'] ) );
			if ( ! wp_verify_nonce( $nonce, 'ushort-reorder-nonce' ) ) {
				wp_send_json_error( array( 'status' => 'Nonce error' ) );
				die();
			}
		}
		/**
		* If no sort, default to shortcode_name.
		*/
		$orderby = ! empty( $_REQUEST['orderby'] ) ? sanitize_text_field( wp_unslash( $_REQUEST['orderby'] ) ) : 'shortcode_name';
		/**
		 * WPCS: Input var ok.
		 * If no order, default to asc.
		 */
		$order = ! empty( $_REQUEST['order'] ) ? sanitize_text_field( wp_unslash( $_REQUEST['order'] ) ) : 'asc'; // WPCS: Input var ok.
		/**
		 * Determine sort order.
		 */
		$result = strcmp( $a[ $orderby ], $b[ $orderby ] );
		return ( 'asc' === $order ) ? $result : - $result;
	}

}
