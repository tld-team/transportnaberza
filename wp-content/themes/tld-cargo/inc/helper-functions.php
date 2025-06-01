<?php

/**
 * Print array
 */
function dd($array, $array2 = null ): void {
	if ($array2 == null) {
		echo '<pre>';
		print_r($array);
		echo '</pre>';
	} else {
		echo '<div class="flex-container" style="display: flex"> <div class="column" style="background-color:#f5f5f5;"><pre>';
		print_r($array);
		echo '</pre></div> <pre class="column" style="background-color:#f5eded;"><pre>';
		print_r($array2);
		echo '</pre></div> </div>';
	}
}

/**
 * Log function
 */
if ( ! function_exists( 'intellrocket_log' ) ) {
	function tld_log( $entry, $mode = 'a', $file = 'tld_log' ) {
		// Get WordPress uploads directory.
		$upload_dir = wp_upload_dir();

		$upload_dir = $upload_dir['basedir'];
		$upload_dir = dirname(__FILE__);
		// If the entry is array, json_encode.
		if ( is_array( $entry ) ) {
			$entry = json_encode( $entry );
		}
		// Write the log file.
		$file  = $upload_dir . '/' . $file . '.log';
		$file  = fopen( $file, $mode );
		$bytes = fwrite( $file, current_time( 'mysql' ) . "::" . $entry . "\n" );
		fclose( $file );
		return $bytes;
	}
}
