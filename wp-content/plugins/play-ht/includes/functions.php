<?php

use Wordpress\Play\Plugin;

if ( ! function_exists( 'wp_play' ) ) :
	/**
	 * Get plugin instance
	 *
	 * @return Plugin
	 */
	function wp_play() {
		return Plugin::get_instance();
	}
endif;

if ( ! function_exists( 'wppp_view' ) ) :
	/**
	 * Load view
	 *
	 * @param string  $view_name
	 * @param array   $args
	 * @param boolean $return
	 *
	 * @return void
	 */
	function wppp_view( $view_name, $args = null, $return = false ) {
		if ( $return ) {
			// start buffer
			ob_start();
		}

		wp_play()->load_view( $view_name, $args );

		if ( $return ) {
			// get buffer flush
			return ob_get_clean();
		}
	}
endif;

if ( ! function_exists( 'wppp_version' ) ) :
	/**
	 * Get plugin version
	 *
	 * @return string
	 */
	function wppp_version() {
		return wp_play()->version;
	}
endif;

if ( ! function_exists( 'wppp_db_version' ) ) :
	/**
	 * Get plugin version
	 *
	 * @return string
	 */
	function wppp_db_version() {
		return wp_play()->db_version;
	}
endif;

if ( ! function_exists( 'wppp_conversion_check' ) ) :
	/**
	 * Get plugin version
	 *
	 * @return boolean
	 */
	function wppp_conversion_check() {
		// if user not registered return false
		if ( false === get_option( 'wppp_blog_appId' ) || false === get_option( 'wppp_blog_userId' ) ) {
			return new WP_Error( 'play', __( 'You must login first to be able to use play.ht to add audio to your articles.', 'play-ht' ) );
		}

		// check quota end date
		$user_data = maybe_unserialize( get_option( 'wppp_play_user_data' ) );
		if ( ! empty( $user_data['package'] ) ) {
			if ( time() < $user_data['package']['pro_package_end'] ) {
				return new WP_Error( 'play', __( 'Your subscription is over, you need to renew your subscription.', 'play-ht' ) );
			}
		}
		//@todo check quota
		return 1;
	}
endif;

function playht_get_post_listens_count( $app_id, $article_id, $post_id ) {

	$url = 'https://a.play.ht/listens/article/?app_id=' . $app_id . '&created_at__gte=' . get_the_date( 'Y-m-d', $post_id ) . '&created_at__lte=' . date( 'Y-m-d' ) . '&article_id=' . $article_id;

	$response = wp_remote_get( $url, [ 'timeout' => 60 ] );

	if ( ! wp_remote_retrieve_response_code( $response ) ) {
		return 0;
	}

	if ( is_wp_error( $response ) ) {
		return 0;
	}

	$body = json_decode( wp_remote_retrieve_body( $response ) );
	return $body->hits->total;
}

function playht_get_post_listens_time( $app_id, $article_id, $post_id ) {

	$url = 'https://a.play.ht/listeningtime/article/?app_id=' . $app_id . '&created_at__gte=' . get_the_date( 'Y-m-d', $post_id ) . '&created_at__lte=' . date( 'Y-m-d' ) . '&article_id=' . $article_id;

	$response = wp_remote_get( $url, [ 'timeout' => 60 ] );

	if ( ! wp_remote_retrieve_response_code( $response ) ) {
		return 0;
	}

	if ( is_wp_error( $response ) ) {
		return 0;
	}

	$body        = json_decode( wp_remote_retrieve_body( $response ) );
	$minutes_tot = ( $body->hits->total * 10 ) / 60;
	return round( $minutes_tot, 2 );
}

function playht_player( array $config = [] ) {

	$post_id = get_the_ID();
	$app_id  = get_option( 'wppp_blog_appId' );

	$player_width  = $config['width'];
	$player_height = $config['height'];

	if ( playht_has_audio( $post_id ) ) {
		// Podcast stored data
		$podcast_data = maybe_unserialize( get_post_meta( $post_id, 'play_podcast_data', true ) );

		$article_url   = ! empty( $podcast_data['url'] ) ? $podcast_data['url'] : '';
		$article_voice = ! empty( $podcast_data['voice'] ) ? $podcast_data['voice'] : '';

		// {“title”:“Audio”,%20"message”:%20"hello”,%20"download_button_text”:%20"D”} phpcs:ignore
		$json_config = wp_json_encode( $config, true );

		return '
		<div
			id="playht-iframe-wrapper"
			style="display:flex; max-height:' . $player_height . '; justify-content:' . $config['alignment'] . '">
			<iframe
				allowfullscreen=""
				frameborder="0"
				scrolling="no"
				class="playht-iframe-player"
				data-appId="' . $app_id . '"
				article-url="' . $article_url . '"
				data-voice="' . $article_voice . '"
				src="https://play.ht/embed/?article_url=' . $article_url . '&voice=' . $article_voice . '&config=' . esc_attr( $json_config ) . '"
				width="' . $player_width . '"
				height="' . $player_height . '"
				style="max-height:' . $player_height . '">
			</iframe>
		</div>';
	}

	return false;
}


function playht_has_audio( $post_id = 0 ) {
	if ( ! $post_id ) {
		return false;
	}

	$podcast_data = maybe_unserialize( get_post_meta( $post_id, 'play_podcast_data', true ) );
	// 2: converted audio.
	return ( isset( $podcast_data['audio_status'] ) && 2 === $podcast_data['audio_status'] );
}

function playht_has_elementor_player( $post_id = 0 ) {
	if ( ! $post_id ) {
		return false;
	}

	if ( ! class_exists( 'Elementor\Plugin' ) ) {
		return false;
	}

	if ( ! Elementor\Plugin::instance()->db->is_built_with_elementor( $post_id ) ) {
		return false;
	}

	$elementor_data = get_post_meta( $post_id, '_elementor_data', true );

	if ( empty( $elementor_data ) ) {
		return false;
	}

	if ( false !== strpos( $elementor_data, '"widgetType":"playht"' ) ) {
		return true;
	}

	return false;
}


function playht_listen_button( $config = [] ) {
	ob_start();
	$podcast_data = maybe_unserialize( get_post_meta( $config['post_id'], 'play_podcast_data', true ) );

	$article_url   = $podcast_data['url'];
	$article_audio = $podcast_data['article_audio'];
	?>
	<div class="playHtListenArea" style="display:none;margin: 0;">
		<div id="playht-audioplayer-element" data-play-article="<?php echo $article_url; ?>" data-play-audio="<?php echo $article_audio; ?>">
		</div>
	</div>
	<?php

	return ob_get_clean();
}

function playht_has_listen_button() {
	$post_content = get_the_content( get_the_ID() );

	if ( has_shortcode( $post_content, 'playht_listen_button' ) ) {
		return 1;
	}

	return get_option( 'playht_Listenbutton_switch', '1' );
}
