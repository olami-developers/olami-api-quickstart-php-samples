<?php

/*
	Copyright 2017, VIA Technologies, Inc. & OLAMI Team.

	Licensed under the Apache License, Version 2.0 (the "License");
	you may not use this file except in compliance with the License.
	You may obtain a copy of the License at

	http://www.apache.org/licenses/LICENSE-2.0

	Unless required by applicable law or agreed to in writing, software
	distributed under the License is distributed on an "AS IS" BASIS,
	WITHOUT WARRANTIES OR CONDITIONS OF ANY KIND, either express or implied.
	See the License for the specific language governing permissions and
	limitations under the License.
*/

class SpeechApiSample
{
	public $API_NAME_ASR = 'asr';

	private $apiBaseUrl = '';
	private $appKey = '';
	private $appSecret = '';
	private $apiName = '';
	private $language = '';
  
	private $cookiePath = './mycookie';

	function __construct() {

	}

	/**
	 * Setup your authorization information to access OLAMI services.
	 *
	 * @param appKey the AppKey you got from OLAMI developer console.
	 * @param appSecret the AppSecret you from OLAMI developer console.
	 */
	public function setAuthorization ($appKey, $appSecret) {
		$this->appKey = $appKey;
		$this->appSecret = $appSecret;
	}

	/**
	 * Setup localization to select service area, this is related to different
	 * server URLs or languages, etc.
	 *
	 * @param apiBaseURL URL of the API service.
	 */
	public function setLocalization ($apiBaseURL) {
		$this->apiBaseUrl = $apiBaseURL;
	}

	/**
	 * Send an audio file to speech recognition service.
	 *
	 * @param apiName the API name for 'api=xxx' HTTP parameter.
	 * @param seqValue the value of 'seq' for 'seq=xxx' HTTP parameter.
	 * @param finished TRUE to finish upload or FALSE to continue upload.
	 * @param filePath the path of the audio file you want to upload.
	 * @param compressed TRUE if the audio file is a Speex audio.
	 */  
	public function sendAudioFile($apiName, $seqValue, $finished, $filePath, $compressed) {
		if (!file_exists($filePath)) return '{"status":"[ERROR] File not found!"}';
		$data = array();
		$data['sound'] = function_exists("curl_file_create") ? curl_file_create($filePath) : ('@'.$filePath);

		$this->apiName = $apiName;
		$signMsg = $this->preSignMsg();
		$url = $this->preRequestUrl($seqValue, $signMsg);
		$url.= "&compress=" . ($compressed ? "1" : "0");
		$url.= "&stop=" . ($finished ? "1" : "0");
		echo $url;
		
		// Send HTTP POST request to upload audio file by cURL
		$ch = curl_init();
		curl_setopt($ch, CURLOPT_URL, $url);
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
		curl_setopt($ch, CURLOPT_ENCODING, 'UTF-8');
		curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 0);
		curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 0);
		curl_setopt($ch, CURLOPT_POST, true); 
		curl_setopt($ch, CURLOPT_POSTFIELDS, $data);
		curl_setopt($ch, CURLOPT_COOKIEJAR, $this->cookiePath);
		$response = curl_exec($ch);
		if($response === false) {
			echo " [Error Number] : ". curl_errno($ch);
			echo " [Error String] : ". curl_error($ch);
		}
		curl_close($ch);
    
		return $response; 
	}

	/**
	 * Get the speech recognition result for the audio you sent.
	 *
	 * @param apiName the API name for 'api=xxx' HTTP parameter.
	 * @param seqValue the value of 'seq' for 'seq=xxx' HTTP parameter.
	 */
	public function getRecognitionResult ($apiName, $seqValue) {
		$this->apiName = $apiName;
		$signMsg = $this->preSignMsg();
		$url = $this->preRequestUrl($seqValue, $signMsg);
		$url.= "&stop=1";
		echo $url;
    
		// Send HTTP GET request to get speech recognition result by cURL
		$ch = curl_init();
		curl_setopt($ch, CURLOPT_URL, $url);
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
		curl_setopt($ch, CURLOPT_ENCODING, 'UTF-8');
		curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 0);
		curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 0);
		curl_setopt($ch, CURLOPT_COOKIEFILE, $this->cookiePath);
		$response = curl_exec($ch);
		if($response === false) {
			echo " [Error Number] : ". curl_errno($ch);
			echo " [Error String] : ". curl_error($ch);
		}
		curl_close($ch);

		return $response;
	}

	/**
	 * Prepare message to generate an MD5 digest.
	 */
	private function preSignMsg () {
		$timestamp = round(microtime(true) * 1000);
		$msg = '';
		$msg .= $this->appSecret;
		$msg .= 'api=';
		$msg .= $this->apiName;
		$msg .= 'appkey=';
		$msg .= $this->appKey;
		$msg .= 'timestamp=';
		$msg .= $timestamp;
		$msg .= $this->appSecret;

		// Generate MD5 digest.
		return md5($msg);
	}

	/**
	 * Generate and get a basic HTTP query string
	 */
	private function preRequestUrl ($seqValue, $signMsg) {
		$timestamp = round(microtime(true) * 1000);
		$url = '';
		$url .= $this->apiBaseUrl .'?_from=php';
		$url .= '&appkey='. $this->appKey;
		$url .= '&api=';
		$url .= $this->apiName;
		$url .= '&timestamp='. $timestamp;
		$url .= '&sign='. $signMsg;
		$url .= '&seq='. $seqValue;

		return $url;
	}
  
}

?>
