<?php

require_once 'vendor/autoload.php';
require_once 'routes/api.php';
require_once 'routes/web.php';

use Framework\Classes\Request;
use Framework\Classes\Route;

$request = Request::make();
$response = Route::run($request);
$response->send();