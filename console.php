<?php

require_once __DIR__.'/vendor/autoload.php';

/*use Symfony\Component\HttpFoundation\Request;

$request = Request::createFromGlobals();
$gecky = new Gecky\Framework(false);
$gecky->handle($request)->send();*/

use Urb\Console\Command\DatabaseCreateTablesCommand;
use Urb\Console\Command\DatabaseDropTablesCommand;
use Gecky\Console\Application;

$application = new Application();
$application->add(new DatabaseCreateTablesCommand());
$application->add(new DatabaseDropTablesCommand());
$application->run();