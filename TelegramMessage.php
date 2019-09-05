<?php

use GuzzleHttp\Client;
use GuzzleHttp\Psr7;
use GuzzleHttp\Exception\ClientException;

class TelegramMessage
{
	static function send($apiKey, $chatId, $message, $silent = false)
	{
		$client = new Client(['base_uri' => 'https://api.telegram.org/bot'.$apiKey.'/']);
		$data = [
			'chat_id' => $chatId,
			'text' => $message,
			'parse_mode' => 'Markdown',
			'disable_notification' => $silent,
		];

		$res = $client->post('sendMessage', ['json' => $data]);
		// $resData = json_decode($res->getBody()->getContents());

		return;
	}

}
