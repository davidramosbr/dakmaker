<?php
    if(!defined('PAGELOAD')) { http_response_code(404); echo 'Failed to locate resource...'; exit(); }

    class Functions extends Config { 
        
        protected static function getMonsterCaptchaList() {
            $monsterList = [];
            $monsterList['cyclops'] = array(22, md5("cyclops"));
            $monsterList['necromancer'] = array(9, md5("necromancer"));
            $monsterList['dragon'] = array(34, md5("dragon"));
            $monsterList['kongra'] = array(116, md5("kongra"));
            $monsterList['behemoth'] = array(55, md5("behemoth"));
            $monsterList['tarantula'] = array(219, md5("tarantula"));
            $monsterList['yeti'] = array(110, md5("yeti"));

            return $monsterList;
        }

        public static function sortCaptchaMonster() {
            $monsterList = self::getMonsterCaptchaList();
            $randomKey = array_rand($monsterList);
            $monster = $monsterList[$randomKey];
            $id = $monster[0];
            $code = $monster[1];
        
            return array($id, $code);
        }

        public static function getCaptchaMonsterByCode($code) {
            $monsterList = self::getMonsterCaptchaList();
        
            $codeToNameMap = array_flip(array_map(function($monster) {
                return $monster[1];
            }, $monsterList));
        
            if (isset($codeToNameMap[$code])) {
                return $codeToNameMap[$code];
            }
            return null;
        }
        
        

        public static function getCacheData() {
            $cacheDir = 'cache';
            $cacheFile = $cacheDir . DIRECTORY_SEPARATOR . 'serverdata.cache.json';
        
            if (file_exists($cacheFile)) {
                $cachedJson = file_get_contents($cacheFile);
                $data = json_decode($cachedJson);
        
                if (json_last_error() === JSON_ERROR_NONE && isset($data->cached_time)) {
                    $cachedTime = $data->cached_time;
                    $currentTime = time();
        
                    if (($currentTime - $cachedTime) <= 30) {
                        return $cachedJson;
                    }
                }
            }
        
            $json = self::getServerData();
            return $json;
        }
        

        public static function getServerData() {
            $connection = @fsockopen('localhost', 7171);
        
            if ($connection) {
                $requestPacket = chr(6).chr(0).chr(255).chr(255).'info';
                fwrite($connection, $requestPacket);
    
                $response = '';
                while (!feof($connection)) {
                    $response .= fread($connection, 1024);
                }
    
                fclose($connection);
                $response = trim($response);
    
                try {
                    $xml = new SimpleXMLElement($response);
                    $json = json_encode($xml);

                    $data = json_decode($json, true);
                    $data['cached_time'] = time();

                    $json = json_encode($data);
                    
                    $cacheDir = 'cache';
                    $cacheFile = $cacheDir . DIRECTORY_SEPARATOR . 'serverdata.cache.json';
        
                    if (!is_dir($cacheDir)) {
                        mkdir($cacheDir, 0755, true);
                    }
        
                    file_put_contents($cacheFile, $json);
                    return $json;
                } catch (Exception $e) {
                    return false;
                }
            }
        
            return false;
        }
    }