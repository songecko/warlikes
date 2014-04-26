<?php

require_once __DIR__.'/vendor/autoload.php';

use Symfony\Component\HttpFoundation\Request;

$request = Request::createFromGlobals();
$gecky = new Gecky\Framework(false);
$gecky->handle($request)->send();