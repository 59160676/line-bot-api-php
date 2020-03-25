<?php

$API_URL = 'https://api.line.me/v2/bot/message/reply';
$ACCESS_TOKEN = '0mGnf2wYx0WJ6GVMr1QC8EYbf/31gD7Edf020MGtWk6oq8GMbe0ag6+5BwHSj561UQomGmnMjxkQqW3FabLlltEQlD/sFTxHVqAIDv6CnwPOuxJMUUkuaNwXgjr5CoS6MVYesYwqelS+Rf773chnEgdB04t89/1O/w1cDnyilFU='; // Access Token ค่าที่เราสร้างขึ้น
$POST_HEADER = array('Content-Type: application/json', 'Authorization: Bearer ' . $ACCESS_TOKEN);

$request = file_get_contents('php://input');   // Get request content
$request_array = json_decode($request, true);   // Decode JSON to Array

if ( sizeof($request_array['events']) > 0 )
{

 foreach ($request_array['events'] as $event)
 {
  $reply_message = '';
  $reply_token = $event['replyToken'];

  if ( $event['type'] == 'message' ) 
  {
   
   if( $event['message']['type'] == 'text' )
   {
		$text = $event['message']['text'];
		
		if(($text == "COVID-19")||($text == "อยากทราบยอด COVID-19 ครับ")){
			$Infect = 27;
			$Dead = 4;
			$Get_well = 57;
			$reply_message = '
					  "รายงานสถานการณ์ ยอดผู้ติดเชื้อไวรัสโคโรนา 2019 (COVID-19) ในประเทศไทย" 
					   ผู้ป่วยสะสม จำนวน '.$Infect.' ราย
					   ผู้เสียชีวิต จำนวน '.$Dead.' ราย
					   รักษาหาย จำนวน '.$Get_well.' ราย
					   ผู้รายงานข้อมูล: นายพัสสน อุ้ยวงค์ศา 59160676ขณะนี้อุณหภูมิที่ ';
		}
		else if(($text== "ข้อมูลส่วนตัวของผู้พัฒนาระบบ")){
			$reply_message = 'ชื่อนายพัสสน อุ้ยวงค์ศา อายุ 22ปี น้ำหนัก 75kg. สูง 182cm. ขนาดรองเท้าเบอร์ 11 US';
		}
		else
		{
			$reply_message = 'ระบบได้รับข้อความ ('.$text.') ของคุณแล้ว';
    		}
   
   }
   else
    $reply_message = 'ระบบได้รับ '.ucfirst($event['message']['type']).' ของคุณแล้ว';
  
  }
  else
   $reply_message = 'ระบบได้รับ Event '.ucfirst($event['type']).' ของคุณแล้ว';
 
  if( strlen($reply_message) > 0 )
  {
   //$reply_message = iconv("tis-620","utf-8",$reply_message);
   $data = [
    'replyToken' => $reply_token,
    'messages' => [['type' => 'text', 'text' => $reply_message]]
   ];
   $post_body = json_encode($data, JSON_UNESCAPED_UNICODE);

   $send_result = send_reply_message($API_URL, $POST_HEADER, $post_body);
   echo "Result: ".$send_result."\r\n";
  }
 }
}

echo "OK";

function send_reply_message($url, $post_header, $post_body)
{
 $ch = curl_init($url);
 curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "POST");
 curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
 curl_setopt($ch, CURLOPT_HTTPHEADER, $post_header);
 curl_setopt($ch, CURLOPT_POSTFIELDS, $post_body);
 curl_setopt($ch, CURLOPT_FOLLOWLOCATION, 1);
 $result = curl_exec($ch);
 curl_close($ch);

 return $result;
}

?>
