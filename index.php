<?php
require __DIR__ . '/vendor/autoload.php';
require 'TelegramMessage.php';

$dotenv = Dotenv\Dotenv::create(__DIR__);
$dotenv->load();

file_put_contents('log.txt', file_get_contents('php://input'));

$alertData = $_POST; //file_get_contents('php://input');
$required = ['alertName', 'alertState', 'curValue', 'instanceName', 'metricName'];

// Required fields
foreach ($required as $reqKey => $req)
{
	if (!isset($alertData[$req]))
		throw new Exception("Required field {$req} is missing.");
}

$message = "`[Aliyun]` *{$alertData['metricName']}* for `{$alertData['instanceName']}` is `{$alertData['alertState']}`. Value: {$alertData['curValue']}";

TelegramMessage::send(getenv('TG_API_KEY'), getenv('TG_CHAT_ID'), $message, false);

echo "OK";

?>