<?php

/**
 * language_model_openai class
 *
 */
class language_model_openai implements language_model_interface {

	private $api_key;
	public $api_url;
	public $api_model;
	public $stream;

	public function __construct() {
		global $settings;

		// Get the API settings
		$this->api_key = $settings->get('language_model', 'api_key', '');
		$this->api_url = $settings->get('language_model', 'api_url', '');
		$this->api_model = $settings->get('language_model', 'api_model', 'o4-mini');
	}

	// Callback function to handle streaming data
	private function stream_callback($ch, $data) {
		// Process the data here (e.g., echo or write to a file)
		echo $data; // Example: output the data as it is received
		flush(); // Make sure the output is sent immediately
		ob_flush();
		return strlen($data); // Return the length of the data processed
	}

	public function get_models() : array {

		// Set the default endpoint
		//if (empty($endpoint)) {
		//	$endpoint = '/api/generate';
		//}

		// Set default empty string
		$response = '';

		// Set the url
		if (empty($this->api_url)) {
			$this->api_url = 'http://127.0.0.1:11434';
		}

		// Set the api url endpoint
		$api_url = $this->api_url . '/api/tags';

		// Initialize curl session
		$ch = curl_init();

		// Set curl options
		curl_setopt($ch, CURLOPT_URL, $api_url);
		if (!empty($json_data)) {
			curl_setopt($ch, CURLOPT_POST, true);
			curl_setopt($ch, CURLOPT_POSTFIELDS, $json_data);
		}
		curl_setopt($ch, CURLOPT_HTTPHEADER, array(
			"Content-Type: application/json"
		));

		// Set timeouts
		curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 3);
		curl_setopt($ch, CURLOPT_TIMEOUT, 5);

		// Enable verbose output for debugging
		curl_setopt($ch, CURLOPT_VERBOSE, true);
		//$verbose_Log = fopen("curl_verbose_log.txt", "w");
		//curl_setopt($ch, CURLOPT_STDERR, $verbose_Log);

		curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);

		// Execute the request Note: The response will be empty if stream is true
		$response = curl_exec($ch);

		// Check for errors
		if (curl_errno($ch)) {
			$response = [];
			$response['error']['error_message'] = curl_error($ch)."\n";
			$response['error']['error_code'] = curl_errno($ch)."\n";
			return $response;
		}

		// Output debugging info
		//echo "Debug Info:\n";
		//print_r($debug_info);

		// Output raw response
		//echo "\nRaw Response:\n" . $response . "\n";
		//exit;

		// Decode and display JSON response if valid
		if (json_last_error() === JSON_ERROR_NONE) {
				$decoded_response = json_decode($response, true);
				if (!empty($decoded_response['models'])) {
					$response = $decoded_response['models'];
				}
		}

		// Check for JSON error
		if (json_last_error() !== JSON_ERROR_NONE) {
			$response['error'] = "JSON Decode Error: " . json_last_error_msg() . "\n";
		}

		// Close curl session
		unset($ch);

		// Close verbose log file
		//fclose($verbose_Log);

		return $response;
	}

	public function request(string $model, array $content) {

		// Set default empty string
		$response = '';

		// Set to stream or not to stream
		$stream = $this->stream;

		// Turn off output buffering
		if ($stream) {
			while (ob_get_level() > 0) {
				ob_end_flush();
			}
			ob_implicit_flush(true);
		}

		// Prepare the request
		$data['model'] = $this->api_model;
		//$data['messages'][0]['role'] = 'developer';
		//$data['messages'][0]['content'] = '';
		$data['messages'][0]['role'] = 'user';
		$data['messages'][0]['content'] = $content['prompt'];
		$data['stream'] = $stream;

		/*
		curl "https://api.openai.com/v1/chat/completions" \
			-H "Content-Type: application/json" \
			-H "Authorization: Bearer $OPENAI_API_KEY" \
			-d '{
				"model": "gpt-4.1",
				"messages": [
					{
						"role": "developer",
						"content": "Talk like a pirate."
					},
					{
						"role": "user",
						"content": "Are semicolons optional in JavaScript?"
					}
				]
			}'
		*/

		// Convert data to JSON
		$json_data = json_encode($data);

		// Initialize curl session
		$ch = curl_init();

		// Set the url
		if (empty($this->api_url)) {
			$this->api_url = 'https://api.openai.com/v1/chat/completions';
		}

		// Set curl options
		curl_setopt($ch, CURLOPT_URL, $this->api_url);
		curl_setopt($ch, CURLOPT_POST, true);
		curl_setopt($ch, CURLOPT_POSTFIELDS, $json_data);

		// Set the request headers
		curl_setopt($ch, CURLOPT_HTTPHEADER, array(
			'Authorization: Bearer '.$this->api_key,
			'Content-Type: application/json'
		));

		// Set timeouts
		curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 5);
		curl_setopt($ch, CURLOPT_TIMEOUT, 300);

		// Enable verbose output for debugging
		curl_setopt($ch, CURLOPT_VERBOSE, true);
		//$verbose_Log = fopen("curl_verbose_log.txt", "w");
		//curl_setopt($ch, CURLOPT_STDERR, $verbose_Log);

		// Stream the response
		if ($stream) {
			curl_setopt($ch, CURLOPT_RETURNTRANSFER, false);
			curl_setopt($ch, CURLOPT_WRITEFUNCTION, array($this, 'stream_callback'));
			//curl_setopt($ch, CURLOPT_WRITEFUNCTION, function ($ch, $data) {
			//	echo $data;
			//	flush();
			//	ob_flush();
			//});
		} else {
			curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
		}

		// Execute the request Note: The response will be empty if stream is true
		$response = curl_exec($ch);

		// Debugging information
		$debug_info = array(
			"HTTP Code" => curl_getinfo($ch, CURLINFO_HTTP_CODE),
			"Total Time" => curl_getinfo($ch, CURLINFO_TOTAL_TIME),
			"Connect Time" => curl_getinfo($ch, CURLINFO_CONNECT_TIME),
			"Effective URL" => curl_getinfo($ch, CURLINFO_EFFECTIVE_URL),
			"Content Type" => curl_getinfo($ch, CURLINFO_CONTENT_TYPE)
		);

		// Response Stream: false
		if (!$stream) {
			// Check for errors
			if (curl_errno($ch)) {
				echo "error ". curl_error($ch)."\n";
				echo "error code ".curl_errno($ch)."\n";
				return false;
			}

			// Output debugging info
			//echo "Debug Info:\n";
			//print_r($debug_info);

			// Output raw response
			//echo "\nRaw Response:\n" . $response . "\n";

			// Decode and display JSON response if valid
			if (json_last_error() === JSON_ERROR_NONE) {
				$decoded_response = json_decode($response, true);

				if (!empty($decoded_response['response'])) {
					$response = $decoded_response['response'];
				}
			}

			// Check for JSON error
			if (json_last_error() !== JSON_ERROR_NONE) {
				$response = "JSON Decode Error: " . json_last_error_msg() . "\n";
			}

			// Return response message content only
			if (!empty($decoded_response['choices'][0]['message']['content'])) {
				return $decoded_response['choices'][0]['message']['content'];
			}
		}

		// Close curl session
		unset($ch);

		//close verbose log file
		//fclose($verbose_Log);

		/*
		// show the result when there is an error
		if ($http_code == 200) {
			$response_array = json_decode($response, true);
			return urldecode($response_array['data']['translations'][0]['translatedText']);
		}
		else {
			echo "error ".$error."\n";
			echo "http_code ".$http_code."\n";
			if (strlen($response) < 500) {
				view_array(json_decode($response, true));
			}
			exit;
		}
		*/

		return $response;
	}

}
