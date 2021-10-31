# cloudmonitor-telegram

Simple script to send CloudMonitor alarms to Telegram from Alibaba Cloud via HTTP Callback.

## Host on your own server:
- Clone
- `composer install`
- Copy `.env.example` to `.env`
- Enter your Telegram bot API key (`TG_API_KEY`) and chat ID (`TG_CHAT_ID`) into `.env` file
- Add `http://your-domain.com/cloudmonitor-telegram/threshold_alarm.php` into the _HTTP Callback_ field in the threshold alarm rule.
- Add `http://your-domain.com/cloudmonitor-telegram/event_alarm.php` into the _URL callback_ field in the event alarm rule. Do note that, HTTPS is not supported in this callback URL.

## Host on Alibaba Cloud Function Compute
- Clone
- `composer install`
- Create a [Function](https://fc.console.aliyun.com/) on Alibaba Cloud
  - Runtime: php7.2
  - Triggers: http
  - Memory: 128 MB
- Configure Environment Variables in your Function Properties (use Key Value for easy editing):
  | Key | Value |
  | --- | --- |
  | SIGNATURE | `<random string>` (use a [generator](https://www.gigacalculator.com/randomizers/random-alphanumeric-generator.php)) |
  | TG_API_KEY | `<your Telegram Bot API key>` |
  | TG_CHAT_ID | `<your Telegram Chat ID>` |
- Zip and upload the code (including vendor folder) to the Function
- Get your Function's HTTP URL from the Triggers tab
- Go to Cloud Monitor and create a new Alert Contact:
  - Name: Telegram
  - Webhook: `<your function http url>/?signature=<your random string>`
- Set your Cloud Monitor alerts to send notification to _Telegram_ contact.