<?php
require __DIR__ . '/vendor/autoload.php';
require 'TelegramMessage.php';

if ($_SERVER['REQUEST_METHOD'] !== 'POST')
	return;

$dotenv = Dotenv\Dotenv::create(__DIR__);
$dotenv->load();
file_put_contents('log.txt', $_POST);

// Required fields
$required = ['alertName', 'alertState', 'curValue', 'instanceName', 'metricName'];
foreach ($required as $req)
{
	if (!isset($_POST[$req]))
		throw new Exception("Required field {$req} is missing.");
}

$message = "`[Aliyun]` *{$_POST['metricName']}* for `{$_POST['instanceName']}` is `{$_POST['alertState']}`. Value: {$_POST['curValue']}";
TelegramMessage::send(getenv('TG_API_KEY'), getenv('TG_CHAT_ID'), $message, false);

echo "OK";

?>