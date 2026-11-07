<?php

session_start();

require __DIR__ . '/../vendor/autoload.php';
require __DIR__ . '/../config.php';
require __DIR__ . '/../Core/Crypto.php';
require __DIR__ . '/../Core/Database.php';
require __DIR__ . '/../Core/Flash.php';
require __DIR__ . '/../Core/Validacao.php';
require __DIR__ . '/../Core/functions.php';

$database = new Database();

require __DIR__ . '/../routes.php';
