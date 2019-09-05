<?php
require __DIR__ . '/vendor/autoload.php';
require 'TelegramMessage.php';

if ($_SERVER['REQUEST_METHOD'] !== 'POST')
	return;

$dotenv = Dotenv\Dotenv::create(__DIR__);
$dotenv->load();
$data = json_decode(file_get_contents('php://input'), true);
file_put_contents('log.txt', file_get_contents('php://input'));

// Required fields
$required = ['product', 'level', 'instanceName', 'name'];
foreach ($required as $req)
{
	if (!isset($data[$req]))
		throw new Exception("Required field {$req} is missing.");
}

$message = "`[Aliyun]` *{$data['product']} {$data['instanceName']}* - `{$data['name']}`";
TelegramMessage::send(getenv('TG_API_KEY'), getenv('TG_CHAT_ID'), $message, false);

echo "OK";

?>