<?php

//This enables $_SESSION everywhere
session_start();

define('ROOT', 'https://yash.dawnmw.com');
define('ASSETS', 'https://yash.dawnmw.com/assets');

define('DBNAME', 'dawn_yash_db');
define('DBHOST', '127.0.0.1');
define('DBUSER', 'dawn_yash');
define('DBPASS', 'e6u2mz4NkNm2Ip2_');
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


