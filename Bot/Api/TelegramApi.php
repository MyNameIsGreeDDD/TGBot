<?php

namespace Bot\Api;

use GuzzleHttp\Client;

class TelegramApi
{
    private $updateId;

    private string $token = '1911236850:AAHjRfnJCAIij9JSWUe3jLx_PLvGOqjYJTI';


    public function query(string $method, array $params = [])
    {
        $url = 'https://api.telegram.org/bot';
        $url .= $this->token;
        $url .= '/' . $method;

        if (!empty($params)) {
            $url .= '?' . http_build_query($params);
        }
        $client = new Client (
            [
                'base_uri' => $url,
                'verify' => false
            ]
        );

        $result = $client->request('GET');

        return json_decode($result->getBody());
    }


    public function getUpdates()
    {
        $response = $this->query('getUpdates',
            [
                'offset' => $this->updateId + 1
            ]
        );

        if (!empty($response->result)) {
            $this->updateId = $response->result[count($response->result) - 1]->update_id;
        }

        return $response->result;

    }

    public function sendMessage($chatId, $text)
    {
        return $this->query('sendMessage',
            [
                'chat_id' => $chatId,
                'text' => $text
            ]
        );

    }


}