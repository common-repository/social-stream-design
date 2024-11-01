<?php
/**
 * Facebook Stream Form Page
 *
 * @version 1.0
 * @package WP Social Stream Designer
 */

$facebook_stream       = get_option( 'facebook_stream' );
$facebook_id           = isset( $facebook_stream['facebook_id'] ) ? $facebook_stream['facebook_id'] : '';
$facebook_secret       = isset( $facebook_stream['facebook_secret'] ) ? $facebook_stream['facebook_secret'] : '';
$facebook_access_token = isset( $facebook_stream['facebook_access_token'] ) ? $facebook_stream['facebook_access_token'] : '';
?>
<div class="inside">
	<h2>
	<?php
	echo esc_html( 'Facebook Stream ' );
	esc_html_e( 'Settings', 'social-stream-design' );
	?>
	</h2>
	<table class="form-table">
		<tbody>
			<tr>
				<td><?php echo esc_html( 'Facebook App ID' ); ?></td>
				<td>
					<input type="text" required='required' id="facebook_id" class="facebook_id" name="facebook_stream[facebook_id]" value="<?php echo esc_attr( $facebook_id ); ?>">
					<p class="description" id="facebook-id-description"><?php esc_html_e( "To get your own 'Facebook App ID' and get your own 'Consumer Secret Key', you can follow these", 'social-stream-design' ); ?> <a href="https://socialstreamdesigner.solwininfotech.com/docs/how-to-get-facebook-app-secret-keys-and-access-token/" target="_blank"><?php esc_html_e( 'Click here', 'social-stream-design' ); ?></a></p>
				</td>
			</tr>
			<tr>
				<td><?php echo esc_html( 'Consumer Secret Key' ); ?></td>
				<td><input type="text" required='required' id="facebook_secret" class="facebook_secret" name="facebook_stream[facebook_secret]" value="<?php echo esc_attr( $facebook_secret ); ?>"></td>
			</tr>
			<tr>
				<td><?php echo esc_html( 'Page Access Token' ); ?></td>
				<td>
					<input type="text" required='required' id="facebook_access_token" class="facebook_access_token" name="facebook_stream[facebook_access_token]" value="<?php echo esc_attr( $facebook_access_token ); ?>">
					<p class="description"><?php esc_html_e( "To get 'Page Access Token'", 'social-stream-design' ); ?> <a target="_blank" href="https://socialstreamdesigner.solwininfotech.com/docs/how-to-get-facebook-app-secret-keys-and-access-token/"><?php esc_html_e( 'Click here', 'social-stream-design' ); ?></a></p>
				</td>
			</tr>
			<tr><td><?php submit_button(); ?></td></tr>
		</tbody>
	</table>
</div>
