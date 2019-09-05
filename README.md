# cloudmonitor-telegram

Simple script to send CloudMonitor alarms to Telegram from Alibaba Cloud via HTTP Callback.

- Clone
- `composer install`
- Copy `.env.example` to `.env`
- Enter your Telegram bot API key (`TG_API_KEY`) and chat ID (`TG_CHAT_ID`) into `.env` file
- Add `http://your-ip.com/cloudmonitor-telegram/threshold_alarm.php` into the _HTTP Callback_ field in the threshold alarm rule.
- Add `http://your-ip.com/cloudmonitor-telegram/event_alarm.php` into the _URL callback_ field in the event alarm rule. Do note that, HTTPS is not supported in this callback URL.