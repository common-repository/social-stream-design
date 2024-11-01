<?php
/**
 * File to display social stream settings
 *
 * @version 1.0
 * @package WP Social Stream Designer
 */

if ( isset( $_POST['nonce'] ) ) {
	$nonce = sanitize_text_field( wp_unslash( $_POST['nonce'] ) );
	if ( ! wp_verify_nonce( $nonce, 'social-stream' ) ) {
		wp_send_json_error( array( 'stream' => 'Nonce error' ) );
		die();
	}
}
if ( isset( $_POST['update_auth_name'] ) ) {
	$_POST['ssd_section'] = 'stream' . strtolower( sanitize_text_field( wp_unslash( $_POST['update_auth_name'] ) ) );
}
$ssd_section = isset( $_POST['ssd_section'] ) ? sanitize_text_field( wp_unslash( $_POST['ssd_section'] ) ) : 'streamtwitter'; ?>
<div class="wrap ssd-div"> <!-- wrap start -->
	<h1 class="ssd-add-feed-div"><?php esc_html_e( 'Social Authentication Settings', 'social-stream-design' ); ?></h1>
	<?php
	settings_errors();
	$update_auth_name = '';

	if ( false !== get_option( 'auth_error_array' ) ) {
		$auth_error_array = maybe_unserialize( get_option( 'auth_error_array' ) );
	}
	if ( isset( $auth_error_array ) && is_array( $auth_error_array ) && count( $auth_error_array ) > 0 ) {
		?>
		<div class="error notice">
			<p><?php esc_html_e( 'Invalid Authentication! Error in following stream settings.', 'social-stream-design' ); ?></p>
			<?php
			foreach ( $auth_error_array as $errors_list ) {
				?>
				<p><?php echo '- ' . esc_html( $errors_list ); ?></p>
				<?php
			}
			?>
		</div>
		<?php
	} else {
		if ( $_POST ) {
			if ( isset( $_POST['update_auth_name'] ) ) {
				$update_auth_name = sanitize_text_field( wp_unslash( $_POST['update_auth_name'] ) );
			}
			?>
			<div class="updated notice">
				<p>
					<?php
					echo esc_attr( $update_auth_name ) . esc_html__( ' Authentication updated.', 'social-stream-design' );
					?>
				</p>
			</div>
			<?php
		}
	}
	?>
	<div class="ssd-screen ssd_feeds"><!-- ssd-screen start -->
		<input type="hidden" id="ssd_section" class="ssd_section" name="ssd_section" value="streamtwitter">
		<div class="stm-hdr stream-authentication-screen" >
			<h3><?php esc_html_e( 'Social Authentication Settings', 'social-stream-design' ); ?></h3>
		</div>
		<div class="ssds-cntr" >
			<div class="ssds-mn-set">
				<ul class="ssd_stream-setting-handle">
					<li data-show="streamtwitter" class="streamtwitter 
					<?php
					if ( 'streamtwitter' === $ssd_section ) {
						echo 'ssd_stream-active-tab'; }
					?>
					">
						<i class="fab fa-x-twitter"></i><span><?php echo esc_html( 'Twitter' ); ?></span>
					</li>
					<li data-show="streamfacebook" class="streamfacebook 
					<?php
					if ( 'streamfacebook' === $ssd_section ) {
						echo 'ssd_stream-active-tab'; }
					?>
					">
						<i class="fab fa-facebook-f"></i><span><?php echo esc_html( 'Facebook' ); ?></span>
					</li>
					<li data-show="streamyoutube" class="streamyoutube 
					<?php
					if ( 'streamyoutube' === $ssd_section ) {
						echo 'ssd_stream-active-tab'; }
					?>
					">
						<i class="fab fa-youtube"></i><span><?php echo esc_html( 'YouTube' ); ?>&nbsp;<i class="fa fa-lock"></i></span>
					</li>
					<li data-show="streamvimeo" class="streamvimeo 
					<?php
					if ( 'streamvimeo' === $ssd_section ) {
						echo 'ssd_stream-active-tab'; }
					?>
					">
						<i class="fab fa-vimeo-v" ></i><span><?php echo esc_html( 'Vimeo' ); ?>&nbsp;<i class="fa fa-lock"></i></span>
					</li>
					<li data-show="streamflickr" class="streamflickr 
					<?php
					if ( 'streamflickr' === $ssd_section ) {
						echo 'ssd_stream-active-tab'; }
					?>
					">
						<i class="fab fa-flickr"></i><span><?php echo esc_html( 'Flickr' ); ?>&nbsp;<i class="fa fa-lock"></i></span>
					</li>
					<li data-show="streaminstagram" class="streaminstagram 
					<?php
					if ( 'streaminstagram' === $ssd_section ) {
						echo 'ssd_stream-active-tab'; }
					?>
					">
						<i class="fab fa-instagram"></i><span><?php echo esc_html( 'Instagram' ); ?></span>
					</li>
					<li data-show="streamtumblr" class="streamtumblr 
					<?php
					if ( 'streamtumblr' === $ssd_section ) {
						echo 'ssd_stream-active-tab'; }
					?>
					">
						<i class="fab fa-tumblr"></i><span><?php echo esc_html( 'Tumblr' ); ?>&nbsp;<i class="fa fa-lock"></i></span>
					</li>
					<li data-show="streamdribbble" class="streamdribbble 
					<?php
					if ( 'streamdribbble' === $ssd_section ) {
						echo 'ssd_stream-active-tab'; }
					?>
					">
						<i class="fab fa-dribbble"></i><span><?php echo esc_html( 'Dribbble' ); ?>&nbsp;<i class="fa fa-lock"></i></span>
					</li>
					<li data-show="streamfoursquare" class="streamfoursquare 
					<?php
					if ( 'streamfoursquare' === $ssd_section ) {
						echo 'ssd_stream-active-tab'; }
					?>
					">
						<i class="fab fa-foursquare"></i><span><?php echo esc_html( 'Foursquare' ); ?>&nbsp;<i class="fa fa-lock"></i></span>
					</li>
					<li data-show="streamsoundcloud" class="streamsoundcloud 
					<?php
					if ( 'streamsoundcloud' === $ssd_section ) {
						echo 'ssd_stream-active-tab'; }
					?>
					">
						<i class="fab fa-soundcloud"></i><span><?php echo esc_html( 'SoundCloud' ); ?>&nbsp;<i class="fa fa-lock"></i></span>
					</li>
				</ul>
			</div>
			<!-- Twitter -->
			<form method="post" action="">
				<?php wp_nonce_field( 'social-stream-designer_meta_box_nonce', 'social-stream-designer-settings' ); ?>
				<div id="streamtwitter" class="ssds-set-box  <?php echo ( 'streamtwitter' === $ssd_section ) ? 'ssd_active_tab' : ''; ?>">
					<?php require 'template/twitter-template.php'; ?>
				</div>
			</form>
			<!-- facebook -->
			<form method="post" action="">
				<?php wp_nonce_field( 'social-stream-designer_meta_box_nonce', 'social-stream-designer-settings' ); ?>
				<div id="streamfacebook" class="ssds-set-box  <?php echo ( 'streamfacebook' === $ssd_section ) ? 'ssd_active_tab' : ''; ?>">
					<?php require 'template/facebook-template.php'; ?>
				</div>
			</form>
			<!-- youtube -->
			<form method="post" action="">
				<?php wp_nonce_field( 'social-stream-designer_meta_box_nonce', 'social-stream-designer-settings' ); ?>
				<div id="streamyoutube" class="ssds-set-box  <?php echo ( 'streamyoutube' === $ssd_section ) ? 'ssd_active_tab' : ''; ?>">
					<div class="ssd-advertisement-cover">
							<a class="ssd-advertisement-link" target="_blank" href="<?php echo esc_url( 'https://codecanyon.net/item/wp-social-stream-designer/26344658?irgwc=1&clickid=UtXV9eXjuxyJW5zwUx0Mo3QzUki2HBxlq3kkwU0&iradid=275988&irpid=1195590&iradtype=ONLINE_TRACKING_LINK&irmptype=mediapartner&mp_value1=&utm_campaign=af_impact_radius_1195590&utm_medium=affiliate&utm_source=impact_radius' ); ?>">
									<img src="<?php echo esc_url( WPSOCIALSTREAMDESIGNER_URL ) . '/images/Social-stream-designer-wordpress-plugin.jpg'; ?>" />
							</a>
					</div>
				</div>
			</form>
			<!-- Vimeo -->
			<form method="post" action="">
				<?php wp_nonce_field( 'social-stream-designer_meta_box_nonce', 'social-stream-designer-settings' ); ?>
				<div id="streamvimeo" class="ssds-set-box  <?php echo ( 'streamvimeo' === $ssd_section ) ? 'ssd_active_tab' : ''; ?>">
					<div class="ssd-advertisement-cover">
							<a class="ssd-advertisement-link" target="_blank" href="<?php echo esc_url( 'https://codecanyon.net/item/wp-social-stream-designer/26344658?irgwc=1&clickid=UtXV9eXjuxyJW5zwUx0Mo3QzUki2HBxlq3kkwU0&iradid=275988&irpid=1195590&iradtype=ONLINE_TRACKING_LINK&irmptype=mediapartner&mp_value1=&utm_campaign=af_impact_radius_1195590&utm_medium=affiliate&utm_source=impact_radius' ); ?>">
									<img src="<?php echo esc_url( WPSOCIALSTREAMDESIGNER_URL ) . '/images/Social-stream-designer-wordpress-plugin.jpg'; ?>" />
							</a>
					</div>
				</div>
			</form>
			<!-- Flickr -->
			<form method="post" action="">
				<?php wp_nonce_field( 'social-stream-designer_meta_box_nonce', 'social-stream-designer-settings' ); ?>
				<div id="streamflickr" class="ssds-set-box  <?php echo ( 'streamflickr' === $ssd_section ) ? 'ssd_active_tab' : ''; ?>">
					<div class="ssd-advertisement-cover">
						<a class="ssd-advertisement-link" target="_blank" href="<?php echo esc_url( 'https://codecanyon.net/item/wp-social-stream-designer/26344658?irgwc=1&clickid=UtXV9eXjuxyJW5zwUx0Mo3QzUki2HBxlq3kkwU0&iradid=275988&irpid=1195590&iradtype=ONLINE_TRACKING_LINK&irmptype=mediapartner&mp_value1=&utm_campaign=af_impact_radius_1195590&utm_medium=affiliate&utm_source=impact_radius' ); ?>">
								<img src="<?php echo esc_url( WPSOCIALSTREAMDESIGNER_URL ) . '/images/Social-stream-designer-wordpress-plugin.jpg'; ?>" />
						</a>
					</div>
				</div>
			</form>
			<!-- Instagram -->
			<form method="post" action="">
				<?php wp_nonce_field( 'social-stream-designer_meta_box_nonce', 'social-stream-designer-settings' ); ?>
				<div id="streaminstagram" class="ssds-set-box  <?php echo ( 'streaminstagram' === $ssd_section ) ? 'ssd_active_tab' : ''; ?>">
					<?php require 'template/instagram-template.php'; ?>
				</div>
			</form>
			<!-- Tumblr -->
			<form method="post" action="">
				<?php wp_nonce_field( 'social-stream-designer_meta_box_nonce', 'social-stream-designer-settings' ); ?>
				<div id="streamtumblr" class="ssds-set-box  <?php echo ( 'streamtumblr' === $ssd_section ) ? 'ssd_active_tab' : ''; ?>">
					<div class="ssd-advertisement-cover">
						<a class="ssd-advertisement-link" target="_blank" href="<?php echo esc_url( 'https://codecanyon.net/item/wp-social-stream-designer/26344658?irgwc=1&clickid=UtXV9eXjuxyJW5zwUx0Mo3QzUki2HBxlq3kkwU0&iradid=275988&irpid=1195590&iradtype=ONLINE_TRACKING_LINK&irmptype=mediapartner&mp_value1=&utm_campaign=af_impact_radius_1195590&utm_medium=affiliate&utm_source=impact_radius' ); ?>">
								<img src="<?php echo esc_url( WPSOCIALSTREAMDESIGNER_URL ) . '/images/Social-stream-designer-wordpress-plugin.jpg'; ?>" />
						</a>
					</div>
				</div>
			</form>
			<!-- Dribbble -->
			<form method="post" action="">
				<?php wp_nonce_field( 'social-stream-designer_meta_box_nonce', 'social-stream-designer-settings' ); ?>
				<div id="streamdribbble" class="ssds-set-box  <?php echo ( 'streamdribbble' === $ssd_section ) ? 'ssd_active_tab' : ''; ?>">
					<div class="ssd-advertisement-cover">
						<a class="ssd-advertisement-link" target="_blank" href="<?php echo esc_url( 'https://codecanyon.net/item/wp-social-stream-designer/26344658?irgwc=1&clickid=UtXV9eXjuxyJW5zwUx0Mo3QzUki2HBxlq3kkwU0&iradid=275988&irpid=1195590&iradtype=ONLINE_TRACKING_LINK&irmptype=mediapartner&mp_value1=&utm_campaign=af_impact_radius_1195590&utm_medium=affiliate&utm_source=impact_radius' ); ?>">
								<img src="<?php echo esc_url( WPSOCIALSTREAMDESIGNER_URL ) . '/images/Social-stream-designer-wordpress-plugin.jpg'; ?>" />
						</a>
					</div>
				</div>
			</form>
			<!-- Foursquare -->
			<form method="post" action="">
				<?php wp_nonce_field( 'social-stream-designer_meta_box_nonce', 'social-stream-designer-settings' ); ?>
				<div id="streamfoursquare" class="ssds-set-box  <?php echo ( 'streamfoursquare' === $ssd_section ) ? 'ssd_active_tab' : ''; ?>">
					<div class="ssd-advertisement-cover">
						<a class="ssd-advertisement-link" target="_blank" href="<?php echo esc_url( 'https://codecanyon.net/item/wp-social-stream-designer/26344658?irgwc=1&clickid=UtXV9eXjuxyJW5zwUx0Mo3QzUki2HBxlq3kkwU0&iradid=275988&irpid=1195590&iradtype=ONLINE_TRACKING_LINK&irmptype=mediapartner&mp_value1=&utm_campaign=af_impact_radius_1195590&utm_medium=affiliate&utm_source=impact_radius' ); ?>">
								<img src="<?php echo esc_url( WPSOCIALSTREAMDESIGNER_URL ) . '/images/Social-stream-designer-wordpress-plugin.jpg'; ?>" />
						</a>
					</div>
				</div>
			</form>
			<form method="post" action="">
				<?php wp_nonce_field( 'social-stream-designer_meta_box_nonce', 'social-stream-designer-settings' ); ?>
				<div id="streamsoundcloud" class="ssds-set-box  <?php echo ( 'streamsoundcloud' === $ssd_section ) ? 'ssd_active_tab' : ''; ?>">
					<div class="ssd-advertisement-cover">
						<a class="ssd-advertisement-link" target="_blank" href="<?php echo esc_url( 'https://codecanyon.net/item/wp-social-stream-designer/26344658?irgwc=1&clickid=UtXV9eXjuxyJW5zwUx0Mo3QzUki2HBxlq3kkwU0&iradid=275988&irpid=1195590&iradtype=ONLINE_TRACKING_LINK&irmptype=mediapartner&mp_value1=&utm_campaign=af_impact_radius_1195590&utm_medium=affiliate&utm_source=impact_radius' ); ?>">
								<img src="<?php echo esc_url( WPSOCIALSTREAMDESIGNER_URL ) . '/images/Social-stream-designer-wordpress-plugin.jpg'; ?>" />
						</a>
					</div>
				</div>
			</form>
		</div>
	</div><!-- ssd-screen end -->
</div> <!-- wrap end-->
