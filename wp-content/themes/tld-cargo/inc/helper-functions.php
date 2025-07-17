<?php

/**
 * Read JSON file and return data
 */
function tld_read_json($json_file_path) {
	if (!file_exists($json_file_path)) {
		error_log("JSON file not found at path: $json_file_path");
		return;
	}

	$json_content = file_get_contents($json_file_path);
	$data = json_decode($json_content, true);

	if (json_last_error() !== JSON_ERROR_NONE || !is_array($data)) {
		error_log("Invalid JSON format in file: $json_file_path");
		return;
	}

	return $data;
}