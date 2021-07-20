<?php

namespace Bot\Cli;

use Bot\Api\CurrencyApi\CurrencyApi;
use Bot\Api\TelegramApi;
use Db;
use stdClass;

class CurrencyTrack
{
    protected TelegramApi $tgApi;

    protected CurrencyApi $currApi;

    public function __construct()
    {
        $this->tgApi = new  TelegramApi();
        $this->currApi = new CurrencyApi();
    }

    public function getUsd()
    {
        $usd = $this->currApi->getCurrency('USD');

        $db = Db::getInstance();

        $subscribes = $db->query('SELECT * FROM `subscription_users`;', [], stdClass::class);

        $date = date('d/m/Y', strtotime(' +1 day'));

        foreach ($subscribes as $subscribe) {
            $chatId = $subscribe['chat_id'];

            $this->tgApi->sendMessage($chatId, 'Курс USD на сегоднешний день: ' . $usd);

            $db->query('UPDATE `subscription_users` SET notify_at = :notify_at WHERE chat_id = :chat_id;', ['chat_id' => $chatId, 'notify_at' => $date], stdClass::class);
        }
    }

}