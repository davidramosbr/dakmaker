<?php
    if(!defined('PAGELOAD')) { http_response_code(404); echo 'Failed to locate resource...'; exit(); }

    $topic = !empty($_REQUEST['topic']) ? $_REQUEST['topic'] : 'latest-news';
    $subtopic = !empty($_REQUEST['subtopic']) ? $_REQUEST['subtopic'] : null;
    $action = !empty($_REQUEST['action']) ? $_REQUEST['action'] : null;
    $subaction = !empty($_REQUEST['subaction']) ? $_REQUEST['subaction'] : null;

    if ($topic == "script") {
        if (is_dir("scripts/$subtopic")) {
            include("scripts/$subtopic/index.php");
            exit;
        }
    }

    $site_name = $config->getConfigValue('general.servername');
    $site_title = $site_name.' | '.ucwords(str_replace('-', ' ', $topic));

    $cache_serverdata = json_decode(Functions::getCacheData(), true);

    if (!$cache_serverdata && Player::countOnlinePlayers() == 0) {
        $players_online = "Offline Server";
    } else {
        $players_online = $cache_serverdata['players']['@attributes']['online'].' Players Online';
    }

    $main_content = '';

