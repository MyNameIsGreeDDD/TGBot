<?php

namespace Bot\Api\CurrencyApi;

class CurrencyApi
{
    public function getCurrency(string $currency)
    {
        $xml = simplexml_load_file('http://cbr.ru/scripts/XML_daily.asp');
        $currencies = [];
        foreach ($xml->xpath('//Valute') as $valute) {
            $currencies[(string)$valute->CharCode] = (float)str_replace(',', '.', $valute->Value);
        }
        return $currencies[$currency];
    }
}