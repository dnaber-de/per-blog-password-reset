<?php

/**
 * Plugin Name: Per Blog Password Reset
 * Description: Multisite Plugin! Allways using the current blog URL when reset the password. Standard behaviour is to lead to the network root blog URL.
 * Plugin URI:  https://github.com/dnaber-de/per-blog-password-reset
 * Version:     2012.11.21
 * Author:      David Naber
 * Author URI:  http://dnaber.de/
 * License:     MIT
 * License URI: http://www.opensource.org/licenses/mit-license.php
 */

/**
The MIT License (MIT)

Copyright (c) 2013 David Naber

Permission is hereby granted, free of charge, to any person obtaining a copy
of this software and associated documentation files (the "Software"), to deal
in the Software without restriction, including without limitation the rights
to use, copy, modify, merge, publish, distribute, sublicense, and/or sell
copies of the Software, and to permit persons to whom the Software is
furnished to do so, subject to the following conditions:

The above copyright notice and this permission notice shall be included in
all copies or substantial portions of the Software.

THE SOFTWARE IS PROVIDED "AS IS", WITHOUT WARRANTY OF ANY KIND, EXPRESS OR
IMPLIED, INCLUDING BUT NOT LIMITED TO THE WARRANTIES OF MERCHANTABILITY,
FITNESS FOR A PARTICULAR PURPOSE AND NONINFRINGEMENT. IN NO EVENT SHALL THE
AUTHORS OR COPYRIGHT HOLDERS BE LIABLE FOR ANY CLAIM, DAMAGES OR OTHER
LIABILITY, WHETHER IN AN ACTION OF CONTRACT, TORT OR OTHERWISE, ARISING FROM,
OUT OF OR IN CONNECTION WITH THE SOFTWARE OR THE USE OR OTHER DEALINGS IN
THE SOFTWARE.
*/

namespace Per_Blog_Password_Reset;

if ( ! function_exists( 'add_filter' ) || ! function_exists( 'is_multisite' ) )
	return;

if ( ! \is_multisite() )
	return;

add_filter( 'lostpassword_url', __NAMESPACE__ . '\get_lost_password_url', 10, 2 );
add_filter( 'retrieve_password_message', __NAMESPACE__ . '\get_password_mail_content', 10, 2 );

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
/**
 * filter the passord message
 *
 * @wp-hook retrieve_password_message
 * @param string $message
 * @param string $key
 * @return string
 */
function get_password_mail_content( $message, $key ) {

	$message = str_replace(
		\network_site_url( '/' ),
		\home_url( '/' ),
		$message
	);

	return $message;
}
