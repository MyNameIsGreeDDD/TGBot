<?php
return [
    '~/start~' => [Bot\Controllers\MainController::class, 'start'],
    '~/help~' => [Bot\Controllers\MainController::class, 'help'],
    '~/usd~' => [Bot\Controllers\MainController::class, 'usd'],
    '~/trackUSD~' => [Bot\Controllers\MainController::class, 'trackUsd'],
    '~/myUSD~' => [Bot\Controllers\MainController::class, 'myUsd'],
    '~unsubscribe~' => [Bot\Controllers\MainController::class, 'unsubscribe']
];