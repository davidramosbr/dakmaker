<?php
    define('PAGELOAD', true);

    ini_set('display_errors', 1);
    error_reporting(E_ALL);

    include('system/init.system.php');
    include('system/compat.system.php');
    include('system/install.system.php');
    include('system/page.system.php');
    include('system/payments.system.php');
    include('system/theme.system.php');
?>