<?php
require __DIR__ . '/vendor/autoload.php';
require 'TelegramMessage.php';

use RingCentral\Psr7\Response;

/*
To enable the initializer feature (https://help.aliyun.com/document_detail/89029.html)
please implement the initializer function as below：
function initializer($context) {
    echo 'initializing' . PHP_EOL;
}
*/

function handler($request, $context): Response{
    $body = $request->getBody()->getContents();
    $queries    = $request->getQueryParams();
    // $method     = $request->getMethod();
    $headers = $request->getHeaders();
    // $path       = $request->getAttribute('path');
    // $requestURI = $request->getAttribute('requestURI');
    // $clientIP   = $request->getAttribute('clientIP');
    $data = null;
    $logger = $GLOBALS['fcLogger'];
    $prefix = getenv('PREFIX') ?: '`[CM]` ';
    $logger->info(json_encode([$headers, $body]));

    // Signature verification
    if (isset($queries['signature']) && getenv('SIGNATURE') !== $queries['signature']) {
        return new Response(200, [], 'OK');
    }

    // Threshold alarm
    if (strpos($headers['Content-Type'][0], 'application/x-www-form-urlencoded') !== false)
    {
        parse_str($body, $data);
        $required_fields = ['alertName', 'alertState', 'curValue', 'instanceName', 'metricName'];

        if (validate_request($data, $required_fields)) {
            $message = "{$prefix}*{$data['alertName']} {$data['metricName']}* for `{$data['instanceName']}` is `{$data['alertState']}`. Value: {$data['curValue']}";
        }
    }
    // Event alarm
    elseif (strpos($headers['Content-Type'][0], 'application/json') !== false) {
        $data = json_decode($body, true);
        $required_fields = ['product', 'level', 'instanceName', 'name'];

        if (validate_request($data, $required_fields)) {
            $message = "{$prefix}*{$data['content']['instanceIds'][0]}* - `{$data['name']}`\n{$data['content']['description']}";
        }
    } else {
        $message = "{$prefix}Invalid request _content-type_.";
    }

    // Send message to Telegram
    send_message($message);

    return new Response(200, [], 'OK');
}

function validate_request($data, $required_fields)
{
    foreach ($required_fields as $field)
    {
        if (!isset($data[$field])) {
            send_message("{$prefix}*ERROR*: Required field {$field} is missing.");

            return false;
        }
    }

    return true;
}

function send_message($message)
{
    TelegramMessage::send(getenv('TG_API_KEY'), getenv('TG_CHAT_ID'), $message, false);
}
