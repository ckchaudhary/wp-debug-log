<?php
/**
 * Plugin Name: RB Debug Log
 * Description: Easy logging feature for development/debugging.
 * Version: 1.0.0
 * Author: ckchaudhary
 * Author URI: https://www.recycleb.in/u/chandan/
 * Licence: GPLv3
 *
 * @package RB Debug Log
 */

// Exit if accessed directly.
defined( 'ABSPATH' ) ? '' : exit();

if ( ! defined( 'LOGGING_ENABLED' ) ) {
	define( 'LOGGING_ENABLED', true );
}

/**
 * Easy logging feature for development/debugging.
 */
class RBDebugLog {
	/**
	 * Add a log
	 *
	 * @param string $text  text of the log.
	 * @param string $group name of the log groug. Optional.
	 * @return bool
	 */
	public static function log( $text, $group = '' ) {
		if ( ! \LOGGING_ENABLED ) {
			return false;
		}

		$target_file = self::get_log_file_path( $group );

		if ( is_array( $text ) || is_object( $text ) ) {
			$text = json_encode( $text );//phpcs:ignore
		}

		try {
			$current_time = date( 'Y-m-d', time() ) . ' ' . date( 'H:i:s', time() );//phpcs:ignore
			$text = ! empty( $text ) ? "\n" . $current_time . ' :: ' . $text . "\n" : '';
			if ( ! file_exists( $target_file ) ) {
				touch( $target_file );
			}
			$fh = fopen( $target_file, 'a' );
			fwrite( $fh, $text );
			fclose( $fh );

			return true;
		} catch ( \Exception $ex ) {
			return false;
		}
	}

	/**
	 * Clear log
	 *
	 * @param string $group name of the log groug. Optional.
	 * @return bool
	 */
	public static function clear_log( $group = '' ) {
		$target_file = self::get_log_file_path( $group );

		@ unlink( $target_file );
		return true;
	}

	/**
	 * Read a log.
	 *
	 * @param string $group     name of the log groug. Optional.
	 * @param string $separator what to use to separate lines. Optional. Default '<br>'.
	 * @return bool
	 */
	public static function read( $group = '', $separator = '<br>' ) {
		if ( ! LOGGING_ENABLED ) {
			return false;
		}

		$file_contents = '';

		$target_file = self::get_log_file_path( $group );
		try {
			$myfile = @ fopen( $target_file, 'r' );
			if ( $myfile ) {
				while ( ! feof( $myfile ) ) {
					$file_contents .= fgets( $myfile ) . $separator;
				}
			}
		} catch ( \Exception $ex ) {
			// Nothing.
		}

		return $file_contents;
	}

	/**
	 * Get log file path.
	 *
	 * @param string $group Name of the groupg.
	 * @return string path
	 */
	public static function get_log_file_path( $group = '' ) {
		$upload_dir = wp_upload_dir();
		return trailingslashit( $upload_dir['basedir'] ) . $group . '-debug.log';
	}
}
