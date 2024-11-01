<?php
/**
 * Twitter Stream Form Page
 *
 * @version 1.0
 * @package WP Social Stream Designer
 */

$twitter_stream      = get_option( 'twitter_stream' );
$consumer_key        = '';
$consumer_secret     = '';
$access_token_secret = '';
$access_token        = '';
if ( isset( $twitter_stream['consumer_key'] ) ) {
	$consumer_key = $twitter_stream['consumer_key'];
}
if ( isset( $twitter_stream['consumer_secret'] ) ) {
	$consumer_secret = $twitter_stream['consumer_secret'];
}
if ( isset( $twitter_stream['access_token_secret'] ) ) {
	$access_token_secret = $twitter_stream['access_token_secret'];
}
if ( isset( $twitter_stream['access_token'] ) ) {
	$access_token = $twitter_stream['access_token'];
}
?>
<div class="inside">
	<h2>
	<?php
	echo esc_html( 'Twitter Stream ' );
	esc_html_e( 'Settings', 'social-stream-design' );
	?>
	</h2>
	<table class="form-table">
		<tbody>
			<tr>
				<td><?php echo esc_html( 'Twitter Consumer Key' ); ?></td>
				<td>
					<input type="text" id="consumer_key" required='required' class="consumer_key" name="twitter_stream[consumer_key]" value="<?php echo esc_attr( $consumer_key ); ?>">
					<p class="description" id="twitter-id-description"><?php esc_html_e( "To get your own 'Consumer Key','Consumer Secret Key','Access Token' and 'Access Token Secret Key' you can follow these", 'social-stream-design' ); ?> <a href="https://socialstreamdesigner.solwininfotech.com/docs/how-to-get-twitter-access-keys-and-consumer-keys/" target="_blank"><?php esc_html_e( 'Click here', 'social-stream-design' ); ?></a></p>
				</td>
			</tr>
			<tr>
				<td><?php echo esc_html( 'Twitter Consumer Secret Key' ); ?></td>
				<td>
					<input type="text" id="consumer_secret" required='required' class="consumer_secret" name="twitter_stream[consumer_secret]" value="<?php echo esc_attr( $consumer_secret ); ?>">
				</td>
			</tr>
			<tr>
				<td><?php echo esc_html( 'Twitter Access Token' ); ?></td>
				<td>
					<input type="text" id="access_token" required='required' class="access_token" name="twitter_stream[access_token]" value="<?php echo esc_attr( $access_token ); ?>">
				</td>
			</tr>
			<tr>
				<td><?php echo esc_html( 'Twitter Access Token Secret Key' ); ?></td>
				<td>
					<input type="text" id="access_token_secret" required='required' class="access_token_secret" name="twitter_stream[access_token_secret]" value="<?php echo esc_attr( $access_token_secret ); ?>">
				</td>
			</tr>
			<tr>
				<td><?php submit_button(); ?></td>
			</tr>
		</tbody>
	</table>
</div>
