<?php

use Infobip\Configuration;
use Infobip\Api\SmsApi;
use Infobip\Model\SmsDestination;
use Infobip\Model\SmsTextualMessage;
use Infobip\Model\SmsAdvancedTextualRequest;
use PSpell\Config;

require __DIR__ . "/vendor/autoload.php";

$number = $_POST["number"];
$message = $_POST["message"];


$apiURL = "ggjlgr.api.infobip.com";
$apikey = "40b8ef48ba444167f56cf8138f2145c2-f72fec9d-4aa2-4ebe-a02f-6ee1934edabe";


$configuration = new Configuration(host: $apiURL, apiKey: $apikey);
$api = new SmsApi(config: $configuration);

$destination = new SmsDestination(to: $number);

$theMessage = new SmsTextualMessage(
    destinations: [$destination],
    text: $message,
    from: "guideco"

);

$request = new SmsAdvancedTextualRequest(messages: [$theMessage]);
$response = $api->sendSmsMessage($request);

echo "SMS MESSAGE SENT";



?>