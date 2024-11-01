<?php
/**
 * Instagram Stream Form Page
 *
 * @version 1.0
 * @package WP Social Stream Designer
 */

$instagram_stream = get_option( 'instagram_stream' );
if ( $instagram_stream ) {
	$instagram_access_token = $instagram_stream['access_token'] ? $instagram_stream['access_token'] : '';
} else {
	$instagram_access_token = '';
}
?>
<div class="inside">
	<h2>
	<?php
	echo esc_html( 'Instagram Stream ' );
	esc_html_e( 'Settings', 'social-stream-design' );
	?>
	</h2>
	<table class="form-table">
		<tbody>
			<tr>
				<td><?php echo esc_html( 'Instagram Access Token' ); ?></td>
				<td>
					<input type="text" required='required' id="instagram_access_token" class="instagram_access_token" name="instagram_stream[access_token]" value="<?php echo esc_attr( $instagram_access_token ); ?>">
					<p class="description"><?php esc_html_e( "To get 'Instagram Access Token' you can follow these", 'social-stream-design' ); ?> <a href='https://socialstreamdesigner.solwininfotech.com/docs/how-to-generate-instagram-access-token/' target='_blank'><?php esc_html_e( 'Click here', 'social-stream-design' ); ?></a></p>
				</td>
			</tr>
			<tr>
				<td><?php submit_button(); ?></td>
			</tr>
		</tbody>
	</table>
</div>
