<?php

namespace onlinetests;

error_reporting(E_ALL);
mb_internal_encoding("UTF-8");
session_start();
require_once('conf.php');
require_once('app/onlinetests/libs/Utils/Loader.php');
spl_autoload_register('onlinetests\Utils\Loader::loadClass');
RouterController::create()->run();
