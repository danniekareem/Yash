<?php

//This enables $_SESSION everywhere
session_start();

define('ROOT', 'http://localhost/yash/public');
define('ASSETS', 'http://localhost/yash/public/assets');

define('DBNAME', 'yash_db');
define('DBHOST', 'localhost');
define('DBUSER', 'root');
define('DBPASS', '');
define('DBDRIVER', 'mysql');



// invoked whe the class is not found 
spl_autoload_register(function ($class_name) {

    require "../private/models/" . $class_name . ".php";
});
