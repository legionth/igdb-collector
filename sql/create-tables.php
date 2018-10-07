<?php

require __DIR__ . '/../vendor/autoload.php';

$loop = \React\EventLoop\Factory::create();
$factory = new \React\MySQL\Factory($loop);

$loop->run();