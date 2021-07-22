<?php
include(__DIR__ . '../vendor/autoload.php');

unset($argv[0]);

$className = '\\Bot\\Cli\\' . array_shift($argv);

$params = [];
foreach ($argv as $argument) {
    preg_match('/^-(.+)=(.+)$/', $argument, $matches);
    if (!empty($matches)) {
        $paramName = $matches[1];
        $paramValue = $matches[2];

        $params[$paramName] = $paramValue;
    }
}

$class = new $className($params);
$class->getUsdSubscribedUsers();