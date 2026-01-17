<?php

//This enables $_SESSION everywhere
session_start();

define('ROOT', 'http://localhost/Yash/public');
define('ASSETS', 'http://localhost/Yash/public/assets');

define('DBNAME', 'yash_db');
define('DBHOST', '127.0.0.1');
define('DBUSER', 'root');
define('DBPASS', '');
define('DBDRIVER', 'mysql');



// invoked whe the class is not found 
spl_autoload_register(function ($class_name) {
    $file = __DIR__ . "/../models/$class_name.php"; // Adjust path if needed
    if (file_exists($file)) {
        require_once $file;
    } else {
        error_log("Autoload error: Class file not found: $file");
    }
});
