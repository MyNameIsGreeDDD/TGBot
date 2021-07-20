<?php

namespace Bot\Controllers;

use Bot\Api\CurrencyApi\CurrencyApi;
use Bot\Api\TelegramApi;
use Db;
use stdClass;


class MainController
{
    protected $tgApi;


    public function __construct()
    {
        $this->tgApi = new TelegramApi();
    }

    public function start($update): void
    {
        $this->tgApi->sendMessage($update->message->chat->id, 'Привет!Начинаем работу!Вызови /help,чтобы посмотреть список команд');
    }

    public function help($update): void
    {
        $this->tgApi->sendMessage($update->message->chat->id, '/usd' . PHP_EOL . '/help' . PHP_EOL . '/start' . PHP_EOL . '/saveCourse');
    }

    public function usd($update): void
    {
        $currencyApi = new CurrencyApi();
        $course = $currencyApi->getCurrency('USD');
        $chatId = $update->message->chat->id;
        $db = Db::getInstance();

        $user = $db->query('SELECT * FROM `users_currency_rate` WHERE chat_id = :chat_id;', [':chat_id' => $chatId], stdClass::class);

        if (empty($user)) {
            $db->query('INSERT INTO `users_currency_rate` (chat_id,course) values (:chat_id,:course)', [':chat_id' => $chatId, ':course' => $course], stdClass::class);
        } else {
            $db->query('UPDATE `users_currency_rate` SET course = :course WHERE chat_id = :chat_id;', [':chat_id' => $chatId, ':course' => $course], stdClass::class);
        }
        $this->tgApi->sendMessage($chatId, $course);
    }

    public function myUsd($update)
    {
        $chatId = $update->message->chat->id;

        $db = Db::getInstance();

        $usd = $db->query('SELECT course FROM `users_currency_rate` WHERE chat_id = :chat_id ;', [':chat_id' => $chatId], stdClass::class);
        $this->tgApi->sendMessage($chatId, $usd[0]->course);
    }

    public function trackUsd($update)
    {
        $chatId = $update->message->chat->id;

        $db = Db::getInstance();
        $date = date('d/m/Y', strtotime(' +1 day'));


        $user = $db->query('SELECT * FROM `subscription_users` WHERE chat_id = :chat_id;', [':chat_id' => $chatId], stdClass::class);

        if (empty($user)) {
            $db->query('INSERT INTO `subscription_users` (chat_id,notify_at) values (:chat_id,:notify_at);', ['chat_id' => $chatId, 'notify_at' => $date]);
        }

        $this->tgApi->sendMessage($chatId, 'Вы подписаны на рассылку,ожидайте');

    }

    public function unsubscribe($update)
    {
        $chatId = $update->message->chat->id;
        $db = Db::getInstance();
        $db->query('DELETE * FROM `subscription_users` WHERE chat_id = :chat_id;', ['chat_id' => $chatId]);
    }


}