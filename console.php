<?php

require_once __DIR__.'/vendor/autoload.php';

use Warlikes\Console\Command\DatabaseCreateTablesCommand;
use Warlikes\Console\Command\DatabaseDropTablesCommand;
use Gecky\Console\Application;

$application = new Application();
$application->add(new DatabaseCreateTablesCommand());
$application->add(new DatabaseDropTablesCommand());
$application->run();