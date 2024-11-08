<?php
    if(!defined('PAGELOAD')) { http_response_code(404); echo 'Failed to locate resource...'; exit(); }

    include('themes/'.$config->getConfigValue('general.theme').'/layout.php');