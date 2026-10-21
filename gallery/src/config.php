<?php

/** 
 * Fitxer de configuració de l'aplicació.
 * */

$config = [];

$config["db"] = array();
$config["db"]["user"] = getenv('DB_USER') ?: 'gallery';
$config["db"]["password"] = getenv('DB_PASSWORD') ?: '1234';
$config["db"]["dbname"] = getenv('DB_NAME') ?: 'gallery';
$config["db"]["host"] = getenv('DB_HOST') ?: '127.0.0.1';
$config["db"]["port"] = getenv('DB_PORT') ?: '3306';
