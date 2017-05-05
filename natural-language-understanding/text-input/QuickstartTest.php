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

require_once(dirname(__FILE__) . '/NluApiSample.php');
     
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
		echo ' - args[4]: your_text_input'.$ln;
		echo $ln.$ln;
		exit(0);       
	} else {
		$url = $_SERVER["argv"][1];
		$appKey = $_SERVER["argv"][2];
		$appSecret = $_SERVER["argv"][3];
		$inputText = $_SERVER["argv"][4];  
	}
  
	$nluApi = new NluApiSample;
	$nluApi->setLocalization($url);
	$nluApi->setAuthorization($appKey, $appSecret);

	echo $ln.'---------- Test NLU API, api=seg ----------'.$ln;
	echo $ln.'Result:'.$ln.$ln;
	echo $nluApi->getRecognitionResult($nluApi->API_NAME_SEG, $inputText);
	echo $ln;

	echo $ln.'---------- Test NLU API, api=nli ----------'.$ln;
	echo $ln.'Result:'.$ln.$ln;
	echo $nluApi->getRecognitionResult($nluApi->API_NAME_NLI, $inputText);
	echo $ln;

	echo $ln.$ln;
   
} else if (isset($_GET["url"])) {

	// This may not work, depending on your system environment.

	$url = $_GET["url"];
	$appKey = $_GET["appkey"];
	$appSecret = $_GET["appsecret"];
	$inputText = $_GET["inputtext"];

	// You should change '/usr/bin/php' to your PHP binary path.
	$result = shell_exec("/usr/bin/php ./QuickstartTest.php " .$url." ".$appKey." ".$appSecret." ".$inputText);
	echo str_replace($ln, '<br>', $result);

} else {

	echo 'Oops...';

}

?>