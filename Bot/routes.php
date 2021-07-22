<?php
return [
    '~/start~' => [Bot\Controllers\CommandController::class, 'start'],
    '~/help~' => [Bot\Controllers\CommandController::class, 'help'],
    '~/usd~' => [Bot\Controllers\CommandController::class, 'usd'],
    '~/trackUSD~' => [Bot\Controllers\CommandController::class, 'trackUsd'],
    '~/myUSD~' => [Bot\Controllers\CommandController::class, 'myUsd'],
    '~/unsubscribe~' => [Bot\Controllers\CommandController::class, 'unsubscribe']
];