<?php

require 'vendor/autoload.php';

use Monolog\Logger;
use Monolog\Handler\StreamHandler;
use Monolog\Processor\UidProcessor;

// Create a logger instance
$logger = new Logger('global_logger');

// Add a unique identifier processor (optional)
$logger->pushProcessor(new UidProcessor());

//IN PRODUCTION naka OFF yung DEBUG

// Set up the file handler

$env_path = str_replace(basename(__DIR__),"",realpath(__DIR__)); ;
$fileHandler = new StreamHandler($env_path.'/logs/app.log', Logger::DEBUG);
$logger->pushHandler($fileHandler);

// You can add more handlers if needed, e.g., for Slack, console, etc.

// Return the logger instance
return $logger;

?>