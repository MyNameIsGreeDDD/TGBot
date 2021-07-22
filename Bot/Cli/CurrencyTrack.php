<?php

namespace Bot\Cli;

use Bot\Api\CurrencyApi;
use Bot\Api\TelegramApi;
use Bot\Service\CurrencyService;
use GuzzleHttp\Exception\GuzzleException;
use JetBrains\PhpStorm\Pure;


class CurrencyTrack
{
    protected TelegramApi $tgApi;

    protected CurrencyApi $currencyApi;

    protected CurrencyService $currencyService;

    public function __construct()
    {
        $this->tgApi = new  TelegramApi();
        $this->currencyApi = new CurrencyApi();
        $this->currencyService = new CurrencyService();
    }

    /**
     * @throws GuzzleException
     */
    public function getUsdSubscribedUsers(): void
    {
        $subscribes = $this->currencyService->findAll('subscription_users');

        $date = date('y.m.d', strtotime(' +1 day'));
        $currentDate = date('y.m.d');

        foreach ($subscribes as $subscribe) {

            if ($subscribe->notify_at === $currentDate) {

                $this->tgApi->sendMessage($subscribe->chat_id, 'Курс USD на сегоднешний день: ' . $this->currencyApi->getCurrency('USD'));

                $this->currencyService->dateUpdate($subscribe->chat_id, $date);
            }
        }
    }

}