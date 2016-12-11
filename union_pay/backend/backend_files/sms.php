<?php
//##########################################################################
// ITEXMO SEND SMS API - CURL-LESS METHOD
// Visit www.itexmo.com/developers.php for more info about this API
//##########################################################################
function itexmo($number,$message,$apicode){
  $url = 'https://www.itexmo.com/php_api/api.php';
  $itexmo = array('1' => $number, '2' => $message, '3' => "TENTE512314_XQVUB");
  $param = array(
      'http' => array(
          'header'  => "Content-type: application/x-www-form-urlencoded\r\n",
          'method'  => 'POST',
          'content' => http_build_query($itexmo),
      ),
  );
  $context  = stream_context_create($param);
  return file_get_contents($url, false, $context);
}
//##########################################################################

	// $result = itexmo("09269809425","I love you ;)","TENTE512314_XQVUB");
	// if ($result == ""){
	// echo "iTexMo: No response from server!!! <br>
	// Please check the METHOD used (CURL or CURL-LESS). If you are using CURL then try CURL-LESS and vice versa.
	// Please <a href=\"https://www.itexmo.com/contactus.php\">CONTACT US</a> for help. ";
	// }else if ($result == 0){
	// echo "Message Sent!";
	// }
	// else{
	// echo "Error Num ". $result . " was encountered!";
	// }
?>
