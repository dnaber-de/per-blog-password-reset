<?php

/**
 * Plugin Name: per site password reset
 * Description: Multisite Plugin! Allways using the current blog url when reset the password. Standard behaviour is to lead to the network root url.
 * Plugin URI:
 * Version:     2012.11.20
 * Author:      David Naber
 * Author URI:  http://dnaber.de/
 * License:     MIT
 * License URI: http://www.opensource.org/licenses/mit-license.php
 */

namespace Per_Site_Password_Reset;

if ( ! function_exists( 'add_filter' ) || ! function_exists( 'is_multisite' ) )
	return;

if ( ! \is_multisite() )
	return;


add_filter( 'lostpassword_url', __NAMESPACE__ . '\get_lost_password_url', 10, 2 );
/**
 * the url to the lost-password dialog
 *
 * @wp-hook lostpassword_url
 * @param string $url
 * @param string $redirect (Optional)
 * @return string
 */
function get_lost_password_url( $url, $redirect ) {

	$parts = parse_url( $url );
	$part_template = array(
		'scheme' => '',
		'host'   => '',
		'path'   => '',
		'query'  => ''
	);

	$parts  = wp_parse_args( $parts, $part_template );

	$path = $parts[ 'path' ];
	if ( ! empty( $parts[ 'query' ] ) )
		$path .= '?' . $parts[ 'query' ];

	$lp_url = home_url( $path );

	return $lp_url;
}
