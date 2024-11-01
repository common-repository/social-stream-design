<?php
/**
 * New Social Feed
 *
 * @version 1.0
 * @package WP Social Stream Designer
 */

global $wpdb;
$page_title                        = esc_html__( 'Add New Social Feed', 'social-stream-design' );
$edit_action                       = '';
$edit_id                           = '';
$feed_name                         = '';
$feed_type                         = '';
$feed_type_twitter                 = '';
$feed_type_facebook                = '';
$feed_type_instagram               = '';
$moderate_feeds                    = 0;
$feed_type_soundcloud_username     = '';
$feed_type_soundcloud_playlist     = '';
$refresh_feed_on_dd                = 'days';
$refresh_feed_on_number            = '1';
$feed_limit                        = 100;
$feed_status                       = 'Active';
$twitter_search_keyword            = '';
$insta_location_id                 = '';
$insta_tag_keyword                 = '';
$insta_username                    = '';
$twitter_username                  = '';
$twitter_user_listname             = '';
$fb_page_id                        = '';
$fb_album_id                       = '';
$ssd_feed_type_pinterest_userid    = '';
$ssd_feed_type_pinterest_boardname = '';
$feed_type_tiktok                  = '';
$tiktok_hashtag                    = '';
$tiktok_username                   = '';
$ssd_feeds_actions                 = new WpSSDFeedsActions();
$ssd_feeds_actions->init();
if ( isset( $_POST['nonce'] ) ) {
	$nonce = sanitize_text_field( wp_unslash( $_POST['nonce'] ) );
	if ( ! wp_verify_nonce( $nonce, 'social-stream' ) ) {
		wp_send_json_error( array( 'stream' => 'Nonce error' ) );
		die();
	}
}
$ssd_action = isset( $_REQUEST['action'] ) ? sanitize_text_field( wp_unslash( $_REQUEST['action'] ) ) : '';
if ( 'edit' === $ssd_action ) {
	$ssd_feeds_actions->feed_id = isset( $_REQUEST['id'] ) ? intval( $_REQUEST['id'] ) : '';

	$edit_action = 'edit';
	$page_title  = esc_html__( 'Edit Social Feed', 'social-stream-design' );

	$feed_stream = $ssd_feeds_actions->ssd_get_feed_detail();

	if ( isset( $feed_stream ) && is_array( $feed_stream ) ) {
		$feed_type                     = isset( $feed_stream['feed'] ) ? $feed_stream['feed'] : '';
		$feed_type_twitter             = isset( $feed_stream['feed_type_twitter'] ) ? $feed_stream['feed_type_twitter'] : '';
		$feed_type_facebook            = isset( $feed_stream['feed_type_facebook'] ) ? $feed_stream['feed_type_facebook'] : '';
		$feed_type_instagram           = isset( $feed_stream['feed_type_instagram'] ) ? $feed_stream['feed_type_instagram'] : '';
		$feed_name                     = isset( $feed_stream['feed_name'] ) ? $feed_stream['feed_name'] : '';
		$refresh_feed_on_number        = isset( $feed_stream['refresh_feed_on_number'] ) ? $feed_stream['refresh_feed_on_number'] : '1';
		$feed_limit                    = isset( $feed_stream['feed_limit'] ) ? $feed_stream['feed_limit'] : $feed_limit;
		$refresh_feed_on_dd            = isset( $feed_stream['refresh_feed_on_dd'] ) ? $feed_stream['refresh_feed_on_dd'] : 'days';
		$insta_location_id             = isset( $feed_stream['insta_location_id'] ) ? $feed_stream['insta_location_id'] : '';
		$insta_tag_keyword             = isset( $feed_stream['insta_tag_keyword'] ) ? $feed_stream['insta_tag_keyword'] : '';
		$twitter_search_keyword        = isset( $feed_stream['twitter_search_keyword'] ) ? $feed_stream['twitter_search_keyword'] : '';
		$feed_status                   = isset( $feed_stream['feed_status'] ) ? $feed_stream['feed_status'] : 'Active';
		$feed_type_soundcloud_username = isset( $feed_stream['feed_type_soundcloud_username'] ) ? $feed_stream['feed_type_soundcloud_username'] : '';
		$feed_type_soundcloud_playlist = isset( $feed_stream['feed_type_soundcloud_playlist'] ) ? $feed_stream['feed_type_soundcloud_playlist'] : '';
		$twitter_username              = isset( $feed_stream['twitter_username'] ) ? $feed_stream['twitter_username'] : '';
		$twitter_user_listname         = isset( $feed_stream['twitter_user_listname'] ) ? $feed_stream['twitter_user_listname'] : '';
		$fb_page_id                    = isset( $feed_stream['fb_page_id'] ) ? $feed_stream['fb_page_id'] : '';
		$fb_album_id                   = isset( $feed_stream['fb_album_id'] ) ? $feed_stream['fb_album_id'] : '';
		$insta_username                = isset( $feed_stream['insta_username'] ) ? $feed_stream['insta_username'] : '';

		$ssd_feed_type_pinterest_userid    = isset( $feed_stream['ssd_feed_type_pinterest_userid'] ) ? $feed_stream['ssd_feed_type_pinterest_userid'] : '';
		$ssd_feed_type_pinterest_boardname = isset( $feed_stream['ssd_feed_type_pinterest_boardname'] ) ? $feed_stream['ssd_feed_type_pinterest_boardname'] : '';

		$feed_type_tiktok = isset( $feed_stream['feed_type_tiktok'] ) ? $feed_stream['feed_type_tiktok'] : '';
		$tiktok_hashtag   = isset( $feed_stream['tiktok_hashtag'] ) ? $feed_stream['tiktok_hashtag'] : '';
		$tiktok_username  = isset( $feed_stream['tiktok_username'] ) ? $feed_stream['tiktok_username'] : '';
	}
}
?>
<div class="wrap ssd-add-social-feed-wrap"> <!-- wrap start -->
	<h1 class="ssd-add-feed-div"><?php echo esc_html( $page_title ); ?></h1>
	<?php
	if ( isset( $_GET['ssd_error'] ) ) {
		?>
			<div class="error notice">
				<p><?php echo esc_html( base64_decode( sanitize_text_field( wp_unslash( $_GET['ssd_error'] ) ) ) ); ?></p>
			</div>
		<?php
	}
	if ( isset( $_REQUEST['update'] ) && 'added' === $_REQUEST['update'] ) {
		if ( isset( $_GET['add_feed'] ) && '' !== sanitize_text_field( wp_unslash( $_GET['add_feed'] ) ) ) {
			$count = sanitize_text_field( wp_unslash( $_GET['add_feed'] ) );
		} else {
			$count = 0;
		}
		if ( ! isset( $_GET['ssd_error'] ) ) {
			?>
		<div class="updated notice">
			<p>
			<?php
			esc_html_e( 'Feed has been updated successfully.', 'social-stream-design' );
			echo ' ( ' . esc_html( $count ) . ' ';
			esc_html_e( 'feeds added', 'social-stream-design' );
			echo ' )';
			?>
			</p>
		</div>
			<?php
		}
	} elseif ( isset( $_REQUEST['update'] ) && 'updated' === $_REQUEST['update'] ) {
		if ( isset( $_GET['add_feed'] ) && '' !== sanitize_text_field( wp_unslash( $_GET['add_feed'] ) ) ) {
			$count = sanitize_text_field( wp_unslash( $_GET['add_feed'] ) );
		} else {
			$count = 0;
		}
		if ( ! isset( $_GET['ssd_error'] ) ) {
			?>
		<div class="updated notice">
			<p>
			<?php
			esc_html_e( 'Feed has been updated successfully.', 'social-stream-design' );
			echo ' ( ' . esc_html( $count ) . ' ';
			esc_html_e( 'feeds added', 'social-stream-design' );
			echo ' )';
			?>
			</p>
		</div>
			<?php
		}
	} elseif ( isset( $_REQUEST['update'] ) && 'false' == $_REQUEST['update'] ) {
		?>
		<div class="error notice">
			<p><?php esc_html_e( 'Error to update feed.', 'social-stream-design' ); ?></p>
		</div>
		<?php
	}
	?>
	<div class="ssd-screen"><!-- ssd-screen start -->
		<form method="post" action="" class="ssdf-set-bx">
			<?php wp_nonce_field( 'social-stream-designer_meta_box_nonce', 'feed_stream_nonce' ); ?>
			<input type="hidden" id="ssd_edit_action"  name="ssd_edit_action" value="<?php echo esc_attr( $edit_action ); ?>">
			<input type="hidden" id="ssd_edit_id"  name="ssd_edit_id" value="<?php echo intval( $ssd_feeds_actions->feed_id ); ?>">
			<div class="stm-hdr" >
				<h3><?php esc_html_e( 'Social Feed Settings', 'social-stream-design' ); ?></h3>
				<p class="submit">
					<input name="submit" id="submit" class="button button-primary ssd_feed_setting_btn" value="<?php esc_html_e( 'Save Changes', 'social-stream-design' ); ?>" type="submit">
				</p>
			</div>
			<div class="ssds-cntr">
				<div class="">
					<div class="inside">
						<ul class="form-table">
							<li>
								<div class="ssd-left"><?php esc_html_e( 'Add Feed Name', 'social-stream-design' ); ?></div>
								<div class="ssd-right">
									<input required="" type="text" name="feed_stream[feed_name]" value="<?php echo esc_attr( $feed_name ); ?>" />
									<p class="description"><b><?php esc_html_e( 'Note', 'social-stream-design' ); ?></b>: 
										<?php
										esc_html_e( 'Enter', 'social-stream-design' );
										echo esc_html( '  feed name' );
										?>
									</p>
								</div>
							</li>
							<li>
								<div class="ssd-left"><?php esc_html_e( 'Select Stream For Feed', 'social-stream-design' ); ?></div>
								<div class="ssd-right">
									<select id="ssd_feed" onchange="ssd_get_feed_type()" name="feed_stream[feed]" class="ssd_feed_select">
										<?php
										foreach ( Wp_Social_Stream_Main::ssd_get_social_stream_options() as $key => $value ) :
											echo '<option value="' . esc_attr( $key ) . '" ' . ( $feed_type === $key ? 'selected="selected"' : '' ) . '>' . esc_html( $value ) . '</option>';
											endforeach;
										?>
									</select>
									<p class="description"><?php esc_html_e( 'Select social stream to create feed', 'social-stream-design' ); ?></p>
								</div>
							</li>

							<!-- Below code is use for Twitter -->
							<li class="ssd_feed_type ssd_twitter-stream">
								<div class="ssd-left"><?php esc_html_e( 'Select Feed Type', 'social-stream-design' ); ?></div>
								<div class="ssd-right">
									<select id="ssd_feed_type_twitter" onchange="ssd_get_feed_type()" name="feed_stream[feed_type_twitter]" class="ssd_feed_select">
										<option <?php selected( 'home_timeline', $feed_type_twitter ); ?> value="home_timeline"><?php echo esc_html( 'Home timeline' ); ?></option>
										<option <?php selected( 'user_feed', $feed_type_twitter ); ?> value="user_feed"><?php echo esc_html( 'User feed' ); ?></option>
										<option <?php selected( 'users_like', $feed_type_twitter ); ?> value="users_like"><?php echo esc_html( "User's likes" ); ?></option>
										<option <?php selected( 'tweets_by_search', $feed_type_twitter ); ?> value="tweets_by_search"><?php echo esc_html( 'Tweets by search' ); ?></option>
										<option <?php selected( 'user_list', $feed_type_twitter ); ?> value="user_list"><?php echo esc_html( 'User List' ); ?></option>
									</select>
									<p class="description"><?php esc_html_e( 'Select social stream feed type to get the feeds', 'social-stream-design' ); ?></p>
								</div>
							</li>
							<li class="ssd_feed_type ssd_twitter-stream ssd_feed_type_search">
								<div class="ssd-left">
								<?php
								esc_html_e( 'Enter', 'social-stream-design' );
								echo esc_html( ' Search Keyword' );
								?>
								</div>
								<div class="ssd-right">
									<input type="text" name="feed_stream[twitter_search_keyword]" value="<?php echo esc_attr( $twitter_search_keyword ); ?>" />
									<p class="description"><b><?php esc_html_e( 'Note', 'social-stream-design' ); ?></b> : 
										<?php
										esc_html_e( 'Enter', 'social-stream-design' );
										echo esc_html( ' Search Keyword' );
										?>
									</p>
								</div>
							</li>
							<li class="ssd_feed_type ssd_twitter-stream ssd_feed_type_user">
								<div class="ssd-left">
								<?php
								esc_html_e( 'Enter', 'social-stream-design' );
								echo esc_html( ' Username' );
								?>
								</div>
								<div class="ssd-right">
									<input type="text" name="feed_stream[twitter_username]" value="<?php echo esc_attr( $twitter_username ); ?>" />
									<p class="ssd_user_feed_text description"><b><?php esc_html_e( 'Note', 'social-stream-design' ); ?></b> : <?php esc_html_e( 'Enter Username of any public Twitter account.', 'social-stream-design' ); ?></p>
									<p class="ssd_user_list_text description"><b><?php esc_html_e( 'Note', 'social-stream-design' ); ?></b> : <?php esc_html_e( 'If your twitter page URL is structured like this:', 'social-stream-design' ); ?> https://twitter.com/SolwinInfotech <?php esc_html_e( 'then the Username is', 'social-stream-design' ); ?> "SolwinInfotech".</p>
									<p class="ssd_user_like_text description"><b><?php esc_html_e( 'Note', 'social-stream-design' ); ?></b> : <?php esc_html_e( 'If your twitter page URL is structured like this:', 'social-stream-design' ); ?> https://twitter.com/SolwinInfotech <?php esc_html_e( 'then the Username is', 'social-stream-design' ); ?> "SolwinInfotech".</p>
								</div>
							</li>
							<li class="ssd_feed_type ssd_twitter-stream ssd_feed_type_user_list">
								<div class="ssd-left">
								<?php
								esc_html_e( 'Enter', 'social-stream-design' );
								echo esc_html( ' List ID' );
								?>
								</div>
								<div class="ssd-right">
									<input type="text" name="feed_stream[twitter_user_listname]" value="<?php echo esc_attr( $twitter_user_listname ); ?>" />
									<p class="description"><b><?php esc_html_e( 'Note', 'social-stream-design' ); ?></b> : <?php esc_html_e( 'If your List URL is structured like this', 'social-stream-design' ); ?>: https://twitter.com/i/lists/123654123654123 <?php esc_html_e( 'then the List ID is actually the number at the end, so in this case', 'social-stream-design' ); ?> 123654123654123.</p>
								</div>
							</li>
							<li class="ssd_feed_type ssd_facebook-stream">
								<div class="ssd-left"><?php esc_html_e( 'Select Feed Type', 'social-stream-design' ); ?></div>
								<div class="ssd-right">
									<select name="feed_stream[feed_type_facebook]" onchange="ssd_get_feed_type()" class="ssd_feed_select">
										<option <?php selected( 'page', $feed_type_facebook ); ?> value="page"><?php echo esc_html( 'Page' ); ?></option>
										<option <?php selected( 'album', $feed_type_facebook ); ?> value="album"><?php echo esc_html( 'Album' ); ?></option>
									</select>
									<p class="description"><?php esc_html_e( 'Select social stream feed type to get the feeds', 'social-stream-design' ); ?></p>
								</div>
							</li>
							<li class="ssd_feed_type ssd_facebook-stream ssd_feed_type_fb_page">
								<div class="ssd-left">
								<?php
								esc_html_e( 'Enter', 'social-stream-design' );
								echo esc_html( ' Page ID' );
								?>
								</div>
								<div class="ssd-right">
									<input type="text" name="feed_stream[fb_page_id]" value="<?php echo esc_attr( $fb_page_id ); ?>" />
									<p class="description"><b><?php esc_html_e( 'Note', 'social-stream-design' ); ?></b> : <?php esc_html_e( 'If your page URL is structured like this', 'social-stream-design' ); ?>: https://www.facebook.com/pages/your_page_name/123654123654123 <?php esc_html_e( 'then the Page ID is actually the number at the end, so in this case', 'social-stream-design' ); ?> 123654123654123.</p>
								</div>
							</li>
							<li class="ssd_feed_type ssd_facebook-stream ssd_feed_type_fb_album">
								<div class="ssd-left">
								<?php
								esc_html_e( 'Enter', 'social-stream-design' );
								echo esc_html( ' Album ID' );
								?>
								</div>
								<div class="ssd-right">
									<input type="text" name="feed_stream[fb_album_id]" value="<?php echo esc_attr( $fb_album_id ); ?>" />
									<p class="description"><b><?php esc_html_e( 'Note', 'social-stream-design' ); ?></b> : <?php esc_html_e( 'You can see here to get album ID.', 'social-stream-design' ); ?> <a href='https://socialstreamdesigner.solwininfotech.com/docs/how-to-get-facebook-album-id/' target='_blank'><?php esc_html_e( 'Click here', 'social-stream-design' ); ?></a></p>
								</div>
							</li>
							<!-- Below code is use for Pinterest -->
							<li class="ssd_feed_type ssd_pinterest-stream ssd_feed_type_pinterest_userid ssd_inner_field">
								<div class="ssd-left">
								<?php
								esc_html_e( 'Enter', 'social-stream-design' );
								echo esc_html( ' User ID' );
								?>
								</div>
								<div class="ssd-right">
									<input type="text" name="feed_stream[ssd_feed_type_pinterest_userid]" value="<?php echo esc_attr( $ssd_feed_type_pinterest_userid ); ?>" />
									<p class="description"><b><?php esc_html_e( 'Note', 'social-stream-design' ); ?></b> : <?php esc_html_e( 'If your profile page URL is structured like this', 'social-stream-design' ); ?>: https://in.pinterest.com/user1235 <?php esc_html_e( 'then the User ID is actually the number at the end, so in this case', 'social-stream-design' ); ?> user1235.</p>
								</div>
							</li>
							<li class="ssd_feed_type ssd_pinterest-stream ssd_feed_type_pinterest_boardname ssd_inner_field">
								<div class="ssd-left">
								<?php
								esc_html_e( 'Enter', 'social-stream-design' );
								echo esc_html( ' Board Name' );
								?>
								</div>
								<div class="ssd-right">
									<input type="text" name="feed_stream[ssd_feed_type_pinterest_boardname]" value="<?php echo esc_attr( $ssd_feed_type_pinterest_boardname ); ?>" />
									<p class="description"><b><?php esc_html_e( 'Note', 'social-stream-design' ); ?></b> : 
										<?php
										esc_html_e( 'Enter', 'social-stream-design' );
										echo esc_html( ' pinterest board name' );
										?>
									</p>
								</div>
							</li>						
							<!-- Below code is use for Instagram -->
							<li class="ssd_feed_type ssd_instagram-stream">
								<div class="ssd-left">
								<?php
								esc_html_e( 'Select', 'social-stream-design' );
								echo esc_html( ' Feed Type' );
								?>
								</div>
								<div class="ssd-right">
									<select name="feed_stream[feed_type_instagram]" onchange="ssd_get_feed_type()" class="ssd_feed_select">
										<option <?php selected( 'user_feed', $feed_type_instagram ); ?> value="user_feed"><?php echo esc_html( 'User feed' ); ?></option>
										<option <?php selected( 'hashtag', $feed_type_instagram ); ?> value="hashtag"><?php echo esc_html( 'Hashtag' ); ?></option>
									</select>
									<p class="description"><?php esc_html_e( 'Select social stream feed type to get the feeds', 'social-stream-design' ); ?></p>
								</div>
							</li>
							<li class="ssd_feed_type ssd_instagram-stream ssd_feed_type_insta_user">
								<div class="ssd-left">
								<?php
								esc_html_e( 'Enter', 'social-stream-design' );
								echo esc_html( ' Username' );
								?>
								</div>
								<div class="ssd-right">
									<input type="text" name="feed_stream[insta_username]" value="<?php echo esc_attr( $insta_username ); ?>" />
									<p class="description"><b><?php esc_html_e( 'Note', 'social-stream-design' ); ?></b> : 
																				<?php
																				esc_html_e( 'Enter', 'social-stream-design' );
																				echo esc_html( ' Instagram username' );
																				?>
									</p>
								</div>
							</li>
							<li class="ssd_feed_type ssd_instagram-stream ssd_feed_type_location">
								<div class="ssd-left">
								<?php
								esc_html_e( 'Enter', 'social-stream-design' );
								echo esc_html( ' Location ID' );
								?>
								</div>
								<div class="ssd-right">
									<input type="text" name="feed_stream[insta_location_id]" value="<?php echo esc_attr( $insta_location_id ); ?>" />
									<p class="description"><b><?php esc_html_e( 'Note', 'social-stream-design' ); ?></b> : 
																				<?php
																				esc_html_e( 'Enter', 'social-stream-design' );
																				echo esc_html( ' location ID' );
																				?>
									</p>
								</div>
							</li>
							<li class="ssd_feed_type ssd_instagram-stream ssd_feed_type_tag">
								<div class="ssd-left">
								<?php
								esc_html_e( 'Enter', 'social-stream-design' );
								echo esc_html( ' Tag Keyword' );
								?>
								</div>
								<div class="ssd-right">
									<input type="text" name="feed_stream[insta_tag_keyword]" value="<?php echo esc_attr( $insta_tag_keyword ); ?>" />
									<p class="description"><b><?php esc_html_e( 'Note', 'social-stream-design' ); ?></b> : 
																				<?php
																				esc_html_e( 'Enter', 'social-stream-design' );
																				echo esc_html( ' keyword for tag' );
																				?>
									</p>
								</div>
							</li>
							<!-- Below code is use for Tiktok -->
							<li class="ssd_feed_type ssd_tiktok-stream">
								<div class="ssd-left"><?php esc_html_e( 'Get Tiktok videos from', 'social-stream-design' ); ?></div>
								<div class="ssd-right">
									<select id="ssd_feed_type_tiktok" onchange="ssd_get_feed_type()" name="feed_stream[feed_type_tiktok]" class="ssd_feed_select">
										<option <?php selected( 'hashtag', $feed_type_tiktok ); ?> value="hashtag"><?php echo esc_html( 'Hashtag' ); ?></option>
										<option <?php selected( 'username', $feed_type_tiktok ); ?> value="username"><?php echo esc_html( 'User feed' ); ?></option>
									</select>
									<p class="description"><?php esc_html_e( 'Select social stream feed type to get the feeds', 'social-stream-design' ); ?></p>
								</div>
							</li>
							<li class="ssd_feed_type ssd_tiktok-stream ssd_feed_type_tiktok_hashtag">
								<div class="ssd-left"><?php esc_html_e( 'Enter hashtag keyword', 'social-stream-design' ); ?></div>
								<div class="ssd-right">
									<input type="text" name="feed_stream[tiktok_hashtag]" value="<?php echo esc_attr( $tiktok_hashtag ); ?>" />
									<p class="description"><b><?php esc_html_e( 'Note', 'social-stream-design' ); ?></b> : <?php esc_html_e( 'Enter hashtag Keyword without # tag.', 'social-stream-design' ); ?></p>
								</div>
							</li>
							<li class="ssd_feed_type ssd_tiktok-stream ssd_feed_type_tiktok_user">
								<div class="ssd-left"><?php esc_html_e( 'Enter Username', 'social-stream-design' ); ?></div>
								<div class="ssd-right">
									<input type="text" name="feed_stream[tiktok_username]" value="<?php echo esc_attr( $tiktok_username ); ?>" />
									<p class="description"><b><?php esc_html_e( 'Note', 'social-stream-design' ); ?></b> : <?php esc_html_e( 'Enter username without @ tag.', 'social-stream-design' ); ?></p>
								</div>
							</li>
							<li>
								<div class="ssd-left"><?php esc_html_e( 'Feed Limit', 'social-stream-design' ); ?></div>
								<div class="ssd-right">
									<input required='' type="number" min="0" max="500" name="feed_stream[feed_limit]" value="<?php echo esc_attr( $feed_limit ); ?>"/>
									<p class="description"><?php esc_html_e( 'Feed limit control the number of feeds to fetch from the API.', 'social-stream-design' ); ?></p>
								</div>
							</li>
							<li>
								<div class="ssd-left"><?php esc_html_e( 'Refresh Feeds On', 'social-stream-design' ); ?></div>
								<div class="ssd-right feed_refresh">
									<?php esc_html_e( 'Every', 'social-stream-design' ); ?>&nbsp;&nbsp;&nbsp;
									<input type="number" min="1" max="999" name="feed_stream[refresh_feed_on_number]" value="<?php echo esc_attr( $refresh_feed_on_number ); ?>" style="max-width: 115px !important;margin-right: 10px;" />
									<select style="min-width: 82px;max-width: 100px;" name="feed_stream[refresh_feed_on_dd]" class="ssd_feed_select">
										<option <?php selected( 'minutes', $refresh_feed_on_dd ); ?> value="minutes"><?php esc_html_e( 'Minutes', 'social-stream-design' ); ?></option>
										<option <?php selected( 'hours', $refresh_feed_on_dd ); ?> value="hours"><?php esc_html_e( 'Hours', 'social-stream-design' ); ?></option>
										<option <?php selected( 'days', $refresh_feed_on_dd ); ?> value="days"><?php esc_html_e( 'Days', 'social-stream-design' ); ?></option>
										<option <?php selected( 'weeks', $refresh_feed_on_dd ); ?> value="weeks"><?php esc_html_e( 'Weeks', 'social-stream-design' ); ?></option>
									</select>
																		<p class="description"><?php esc_html_e( 'Note : It is recommended to refresh tiktok feeds every 1 day', 'social-stream-design' ); ?></p>
								</div>
							</li>
							<li class="disable_li">
								<div class="ssd-left"><i class="fa fa-lock"></i>&nbsp;<?php esc_html_e( 'Moderate Feeds?', 'social-stream-design' ); ?></div>
								<div class="ssd-right  ssds-set-box">
									<div class="radio-group">
										<input type="radio" class="yes" id="moderate_feeds_yes" name="" value="" ><label for="moderate_feeds_yes"><?php esc_html_e( 'Yes', 'social-stream-design' ); ?></label><input class="no" type="radio" id="moderate_feeds_no" name="" value="" checked="checked" ><label for="moderate_feeds_no"><?php esc_html_e( 'No', 'social-stream-design' ); ?></label>
									</div>
								</div>
							</li>						
							<input type="hidden" name="feed_stream[feed_status]" value="<?php echo esc_attr( $feed_status ); ?>" />
						</ul>
					</div>
				</div>
			</div>
		</form>
	</div><!-- ssd-screen end -->
</div> <!-- wrap end-->
