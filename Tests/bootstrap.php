<?php

if (!file_exists($autoload_file = __DIR__.'/../vendor/autoload.php')) {
    throw new Exception('You must run composer install.');
}

require_once $autoload_file;