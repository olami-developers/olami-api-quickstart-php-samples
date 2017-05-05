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

class NluApiSample
{
	public $API_NAME_SEG = 'seg';
	public $API_NAME_NLI = 'nli';

	private $apiBaseUrl = '';
	private $appKey = '';
	private $appSecret = '';
	private $apiName = '';
	private $language = '';

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
	 * Get the NLU recognition result for your input text.
	 *
	 * @param apiName the API name for 'api=xxx' HTTP parameter.
	 * @param inputText the text you want to recognize.
	 */
	public function getRecognitionResult ($apiName, $input) {
		$this->apiName = $apiName;
		$signMsg = $this->preSignMsg();
		$url = $this->preRequestUrl($input, $signMsg);
		$response = $this->httpGet($url);
		return $response;
	}

	/**
	 * Prepare message to generate an MD5 digest.
	 */
	private function preSignMsg () {
		$timestamp = time();
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
	 * Request NLU service by HTTP POST
	 */
	private function preRequestUrl ($input, $signMsg) {
		$timestamp = time();
		$url = '';
		$url .= $this->apiBaseUrl .'?';
		$url .= 'appkey='. $this->appKey;
		$url .= '&api=';
		$url .= $this->apiName;
		$url .= '&timestamp='. $timestamp;
		$url .= '&sign='. $signMsg;
		$url .= '&rq=';
    
		if ($this->apiName == $this->API_NAME_SEG) {
			$url .= $input;
		} else if ($this->apiName == $this->API_NAME_NLI) {
			$url .= '{"data":{"input_type":1,"text":"'. $input .'"},"data_type":"stt"}';
		}
    
		return $url;
	}

	/**
	 * Send HTTP request by cURL
	 */
	private function httpGet($url) {  
		$ch = curl_init();
		curl_setopt($ch, CURLOPT_URL, $url);
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
		curl_setopt($ch, CURLOPT_ENCODING, 'UTF-8');
		curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 0);
		curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 0);     

		$output = curl_exec($ch);
			if($output === false) {
				echo " [Error Number] : ". curl_errno($ch);
				echo " [Error String] : ". curl_error($ch);
			}
		curl_close($ch);
		return $output;
	}
}

?>