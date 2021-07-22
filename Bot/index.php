<?php
include('../vendor/autoload.php');

$telegramApi = new \Bot\Api\TelegramApi();

while (true) {
    sleep(2);
    $updates = $telegramApi->getUpdates();
    $update = end($updates);


    $routes = require __DIR__ . '/routes.php';

    if (empty($update)) {
        continue;
    }
    $route = '/' . $update->message->text;
    $isRouteFound = false;
    foreach ($routes as $pattern => $controllerAndAction) {
        preg_match($pattern, $route, $matches);
        if (!empty($matches)) {
            $isRouteFound = true;
            break;
        }
    }
    if (!$isRouteFound) {
        $telegramApi->sendMessage($update->message->chat->id, 'Я не знаю такую команду :( ' . PHP_EOL . 'Вызовите /help, чтобы посмотреть список доступных команд');
    }

    unset($matches[0]);

    $controllerName = $controllerAndAction[0];
    $actionName = $actionName = str_replace('/', '', $controllerAndAction[1],);

    $controller = new $controllerName();
    $controller->$actionName($update);

}