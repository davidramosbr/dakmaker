<?php
    if(!defined('PAGELOAD')) { http_response_code(404); echo 'Failed to locate resource...'; exit(); }

    if (session_status() === PHP_SESSION_NONE) {
        session_start();
    }

    spl_autoload_register(function ($class) {
        $file = 'classes/'. strtolower($class).'.class.php';
        if (file_exists($file)) {
            require_once $file;
        }
    });

    $config = new Config();
    $SQL = new Database();
