<?php

define("BASE_URL", "http://localhost/phone-pay");
define("API_STATUS", "LIVE"); //LIVE OR UAT
define("MERCHANTIDLIVE", "#");
define("MERCHANTIDUAT", " ");  //Sandbox testing
define("SALTKEYLIVE", "#");
define("SALTKEYUAT", " ");
define("SALTINDEX", "1");
define("REDIRECTURL", "paymentstatus.php");
define("SUCCESSURL", "success.php");
define("FAILUREURL", "failure.php");
define("UATURLPAY", "https://api-preprod.phonepe.com/apis/pg-sandbox/pg/v1/pay");
define("LIVEURLPAY", "https://api.phonepe.com/apis/hermes/pg/v1/pay");
define("STATUSCHECKURL", "https://api-preprod.phonepe.com/apis/pg-sandbox/pg/v1/status/");
define("LIVESTATUSCHECKURL", "https://api.phonepe.com/apis/hermes/pg/v1/status/");
?>