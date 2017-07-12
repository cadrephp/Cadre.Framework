<?php
declare(strict_types=1);

date_default_timezone_set('UTC');

$path = dirname(__DIR__);

require $path . '/config/_env.php';
require $path . '/vendor/autoload.php';

$project = new Cadre\Framework\Project($path, $_ENV['CADRE_ENV']);
