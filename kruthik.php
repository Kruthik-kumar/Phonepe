<?php


// Replace these with your actual PhonePe API credentials
$apiKey = 'f94f0bb9-bcfb-4077-adc0-3f8408a17bf7';
$merchantId = 'PGTESTPAYUAT115';
$keyIndex=1;



// Prepare the payment request data (you should customize this)
$paymentData = array(
    'merchantId' => $merchantId,
    'merchantTransactionId' => "MT7850590068128414",
    "merchantUserId"=>"MUID152",
    'amount' => 10*100, // Amount in paisa (10 INR)
    'redirectUrl'=>"C:\xampp\htdocs\myphp\paymentSuccess.php",
    'redirectMode'=>"POST",
    'callbackUrl'=>"C:\xampp\htdocs\myphp\paymentSuccess.php",
    "merchantOrderId"=> "YOUR_ORDER_ID",
   "mobileNumber"=>"9898923216",
   "message"=>"Order description",
   "email"=>"xyz@gmail.com",
   "shortName"=>"CUSTMER_Name",
   "paymentInstrument"=> array(    
    "type"=> "PAY_PAGE",
  )
);



$jsonencode = json_encode($paymentData);
 $payloadMain = base64_encode($jsonencode);




$payload = $payloadMain . "/pg/v1/pay" . $apiKey;
$sha256 = hash("sha256", $payload);
$final_x_header = $sha256 . '###' . $keyIndex;
$request = json_encode(array('request'=>$payloadMain));

$curl = curl_init();
curl_setopt_array($curl, [
  CURLOPT_URL => "https://api-preprod.phonepe.com/apis/pg-sandbox/pg/v1/pay",
  CURLOPT_RETURNTRANSFER => true,
  CURLOPT_ENCODING => "",
  CURLOPT_MAXREDIRS => 10,
  CURLOPT_TIMEOUT => 30,
  CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
  CURLOPT_CUSTOMREQUEST => "POST",
   CURLOPT_POSTFIELDS => $request,
  CURLOPT_HTTPHEADER => [
    "Content-Type: application/json",
     "X-VERIFY: " . $final_x_header,
     "accept: application/json"
  ],
]);
 
$response = curl_exec($curl);
$err = curl_error($curl);
 
curl_close($curl);
 
if ($err) {
  echo "cURL Error #:" . $err;
} else {
   $res = json_decode($response);
echo"<pre>";
print_r($res);

echo"</pre>"; 
if(isset($res->success) && $res->success=='1'){
$paymentCode=$res->code;
$paymentMsg=$res->message;
$payUrl=$res->data->instrumentResponse->redirectInfo->url;
 
header('Location:'.$payUrl) ;
}
}

?>
