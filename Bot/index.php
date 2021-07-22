<?php
include('../vendor/autoload.php');
include('Api/TelegramApi.php');
include('Api/CurrencyApi.php');
include('Service/Db.php');
include('Controllers/CommandController.php');


$telegramBot = new \Bot\Api\TelegramApi();

while (true) {
    sleep(2);
    $updates = $telegramBot->getUpdates();
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
    unset($matches[0]);

    $controllerName = $controllerAndAction[0];
    $actionName = $actionName = str_replace('/', '', $controllerAndAction[1],);

    $controller = new $controllerName();
    $controller->$actionName($update);

}