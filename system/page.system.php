<?php
    if(!defined('PAGELOAD')) { http_response_code(404); echo 'Failed to locate resource...'; exit(); }

    $cache_serverdata = json_decode(Functions::getCacheData(), true);
    if (!$cache_serverdata && Player::countOnlinePlayers() == 0) {
        $players_online = "Offline Server";
    } else {
        $players_online = $cache_serverdata['players']['@attributes']['online'].' Players Online';
    }

    $wPageManager = [];
    $wPageManager['topic'] = str_replace('-', ' ', $topic);
    $wPageManager['menu'] = Menu::findMenuParent($wPageManager['topic']);
    $wPageManager['folder'] = 'pages/'.$wPageManager['menu'];
    $wPageManager['file'] = $wPageManager['folder'].'/'.$wPageManager['topic'].'.php';

    try {
        if (!is_dir($wPageManager['folder'])) { throw new Exception('Diretório não encontrado.'); }
        if (!file_exists($wPageManager['file'])) { throw new Exception('Arquivo não encontrado.'); }
        include($wPageManager['file']);
    } catch (Exception $e) {
        http_response_code(404); include('globals/404.php'); exit();
    }
    