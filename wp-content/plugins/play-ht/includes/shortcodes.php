<?php

if ( ! defined( 'WPINC' ) ) {
	// Exit if accessed directly.
	die();
}

if ( ! function_exists( 'get_playht_player' ) ) :
	function get_playht_player( $atts ) {

		$default_atts = [
			'width'                => '100%',
			'height'               => '90px', // Iframe size, Player size will not change.
			'alignment'            => 'center',
			'title'                => __( 'Audio', 'play-ht' ),
			'message'              => __( 'Hello', 'play-ht' ),
			'download_text_button' => __( 'Download', 'play-ht' ),
		];

		$atts = shortcode_atts( $default_atts, $atts, 'playht_player' );

		if ( isset( $_GET['debug'] ) && 'true' == $_GET['debug'] ) {
			print_r( $atts );
		}

		return playht_player( $atts );
	}
endif;

add_shortcode( 'playht_player', 'get_playht_player' );


function playht_listen_button_shortcode() {
	$config['post_id'] = get_the_ID();
	return playht_listen_button( $config );
}

add_shortcode( 'playht_listen_button', 'playht_listen_button_shortcode' );
