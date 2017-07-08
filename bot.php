<?php
$to ="Ua0d33be595428c3820949904acaf1235";
$access_token = 'xNe1sRhyIk+OZiSWRC60QGzRqMKM+XAz5C4do+zXopx4Iq2jyhD5YPxz7vyZF9dj4FEqXprmZVUO6OcySz0POeqoytHBVU9SYFEil6d9R8fo0hVVXAL+OdqajbcsEAyHR9zTbv+9rzRyqokoUTDN4gdB04t89/1O/w1cDnyilFU=';

// Get POST body content
$content = file_get_contents('php://input');
// Parse JSON
$events = json_decode($content, true);
// Validate parsed JSON data
if (!is_null($events['events'])) {
	// Loop through each event
	foreach ($events['events'] as $event) {
		$userId = $event['source']['userId'];
		$groupId = $event['source']['groupId'];
		$roomId = $event['source']['roomId'];
		$replyToken = $event['replyToken'];
		$text = $event['message']['text'];
		$type = $event['type'];
		
		$url = "http://110.76.155.50/jrsura/apiline.php?type=$type&userId=$userId&groupId=$groupId&roomId=$roomId&replyToken=$replyToken&text=$text";
		//$file = file_get_contents($url);

		// Reply only when message sent is in 'text' format
		
		if ($event['type'] == 'message' && $event['message']['type'] == 'text') {					
			switch (substr($text,0,3)) 
			{
			case "it:":
				$text2="ยินดีต้อนรับ";
				break;
			default:
				$text2="ไม่เข้าใจคำถาม (".$text.")".":".$userId.":".$groupId.":".$roomId.":".$replyToken.":".$text.":".$type;
			}
			
			replyline($access_token,$replyToken,$url);
		}
	}
}
echo "OK";


function replyline($access_token,$replyToken,$message) {
			// Build message to reply back
			//echo $replyToken;
			//echo $message;
			//echo $userId;
			//echo $access_token;
			$messages = array(
				'type' => 'text',
				'text' => $message
			);
			// Make a POST Request to Messaging API to reply to sender
			$url = 'https://api.line.me/v2/bot/message/reply';
			$data = array(
				'replyToken' => $replyToken,
				'messages' => array($messages)
			);
			$post = json_encode($data);
			$headers = array('Content-Type: application/json', 'Authorization: Bearer ' . $access_token);
			$ch = curl_init($url);
			curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "POST");
			curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
			curl_setopt($ch, CURLOPT_POSTFIELDS, $post);
			curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
			curl_setopt($ch, CURLOPT_FOLLOWLOCATION, 1);
			curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
			$result = curl_exec($ch);
			curl_close($ch);
			echo $result . "\r\n";
	
}

function pushline($access_token,$to,$message) {
			$messages = array(
				'type' => 'text',
				'text' => $message
			);
			// Make a POST Request to Messaging API to push to sender
			$url = 'https://api.line.me/v2/bot/message/push';
			$data = array(
				'to' => $to,
				'messages' => array($messages)
			);
			$post = json_encode($data);
			$headers = array('Content-Type: application/json', 'Authorization: Bearer ' . $access_token);
			$ch = curl_init($url);
			curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "POST");
			curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
			curl_setopt($ch, CURLOPT_POSTFIELDS, $post);
			curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
			curl_setopt($ch, CURLOPT_FOLLOWLOCATION, 1);
			curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
			$result = curl_exec($ch);
			curl_close($ch);
			echo $result . "\r\n";
	
}

?>

