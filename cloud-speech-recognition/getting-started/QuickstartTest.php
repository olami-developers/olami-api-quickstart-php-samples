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

require_once(dirname(__FILE__) . '/SpeechApiSample.php');
     
$ln = "\n";

$url = '';
$appKey = '';
$appSecret = '';
$inputText = '';

if (isset($_SERVER["argc"])) {

	if ($_SERVER["argc"] <= 1) {  
		echo $ln.$ln;
		echo '[Error] Missing args! Usage:'.$ln;
		echo ' - args[1]: api_url'.$ln;
		echo ' - args[2]: your_app_key'.$ln;
		echo ' - args[3]: your_app_secret'.$ln;
		echo ' - args[4]: your_audio_file'.$ln;
		echo ' - args[5]: compress_flag=[0|1]'.$ln;
		echo $ln.$ln;
		exit(0);       
	} else {
		$url = $_SERVER["argv"][1];
		$appKey = $_SERVER["argv"][2];
		$appSecret = $_SERVER["argv"][3];
		$filePath = $_SERVER["argv"][4];  
		$compressed = ($_SERVER["argv"][5] == "1"); 
	}
  
	$result = '';

	$seepchApi = new SpeechApiSample;
	$seepchApi->setLocalization($url);
	$seepchApi->setAuthorization($appKey, $appSecret);

	echo $ln.'---------- Test Speech Recognition API, seq=nli,seg ----------'.$ln;
	// Start sending audio file for recognition
	echo $ln.'Send audio file...'.$ln;
	$result = $seepchApi->sendAudioFile($seepchApi->API_NAME_ASR, 'nli,seg', true, $filePath, $compressed);
	echo $ln.$ln.'Result:'.$ln.$ln;
	$result_array = json_decode($result, true);
	var_dump($result_array);
  
	// Try to get result until the end of the recognition is complete
	if (strtolower($result_array['status']) == 'ok') {
		sleep(1);   
		echo $ln.'----- Get Recognition Result -----'.$ln;
		while (true) {
			$result = $seepchApi->getRecognitionResult($seepchApi->API_NAME_ASR, 'nli,seg'); 
			echo $ln.$ln.'Result:'.$ln.$ln;
			$result_array = json_decode($result, true);
			var_dump($result_array);
			// Try to get result until the end of the recognition is complete
			if (!strtolower($result_array['status']['final'])) {
				echo $ln.'*** The recognition is not yet complete. ***'.$ln;
				if (strtolower($result_array['status']) == 'error') break;
				sleep(2);      
			} else {
				break;
			}
		} 
	}

	echo $ln.$ln;
   
} else {

	echo 'Oops...';

}

?>