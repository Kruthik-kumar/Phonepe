<?php

// Replace these with your actual PhonePe API credentials
$apiKey = '0f453196-53d2-4b4f-aafa-72e67c2e5f9a';
$merchantId = 'M22ASDW00YXW2';
$keyIndex = 1;

// Function to generate a unique transaction ID
function generateUniqueTransactionId() {
    return 'MT' . uniqid(); // Generates a unique ID prefixed with 'MT'
}

// Function to generate a unique user ID
function generateUniqueUserId() {
    return 'MUI' . rand(1000, 9999); // Generates a random user ID
}

// Function to generate a unique mobile number (for testing purposes)
function generateUniqueMobileNumber() {
    return '9' . rand(100000000, 999999999); // Generates a random mobile number starting with '9'
}

// Prepare the payment request data (customize this)
$paymentData = array(
    'merchantId' => $merchantId,
    'merchantTransactionId' => generateUniqueTransactionId(), // Unique transaction ID
    "merchantUserId" => generateUniqueUserId(), // Unique user ID
    'amount' => 102,
    'redirectUrl' => "https://your-ngrok-url/myphp/paymentSuccess.php", // Use your ngrok URL
    'redirectMode' => "POST",
    'callbackUrl' => "https://your-ngrok-url/myphp/paymentSuccess.php", // Use your ngrok URL
    "merchantOrderId" => "1234",
    "mobileNumber" => generateUniqueMobileNumber(), // Unique mobile number
    "message" => "Order description",
    "email" => "xyz@gmail.com",
    "shortName" => "CUSTMER_Name",
    "paymentInstrument" => array(
        "type" => "PAY_PAGE",
    )
);

$jsonencode = json_encode($paymentData);
$payloadMain = base64_encode($jsonencode);
$payload = $payloadMain . "/pg/v1/pay" . $apiKey;
$sha256 = hash("sha256", $payload);
$final_x_header = $sha256 . '###' . $keyIndex;
$request = json_encode(array('request' => $payloadMain));

$url = 'https://api.phonepe.com/apis/hermes/pg/v1/pay';

$curl = curl_init();
curl_setopt_array($curl, [
    CURLOPT_URL => $url,
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
    echo "<pre>";
    print_r($res);
    echo "</pre>";

    if (isset($res->success) && $res->success == '1') {
        $payUrl = $res->data->instrumentResponse->redirectInfo->url;
        // Display the iframe with the payment URL
        echo '<iframe src="' . htmlspecialchars($payUrl) . '" width="100%" height="600px" frameborder="0"></iframe>';
    } else {
        // Handle the case where the payment request was not successful
        echo "Payment request failed: " . (isset($res->message) ? $res->message : "Unknown error");
    }
}

?>