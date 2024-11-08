<?php
    if(!defined('PAGELOAD')) { http_response_code(404); echo 'Failed to locate resource...'; exit(); }

    class Menu {

        protected static function addToStructure($dir, &$structure) {
            $items = scandir($dir);

            foreach ($items as $item) {
                if ($item === '.' || $item === '..') continue;

                $path = $dir . DIRECTORY_SEPARATOR . $item;
                if (is_dir($path)) {
                    $structure[$item] = [];
                    self::addToStructure($path, $structure[$item]);
                } elseif (pathinfo($item, PATHINFO_EXTENSION) === 'php') {
                    $structure[] = str_replace('.php', '', $item);
                }
            }
        }

        protected static function reorderArray($array, $order) {
            $orderedArray = array();
            $remainingKeys = array_keys($array);

            foreach ($order as $key) {
                if (array_key_exists($key, $array)) {
                    $orderedArray[$key] = $array[$key];
                    $remainingKeys = array_diff($remainingKeys, array($key));
                }
            }

            foreach ($remainingKeys as $key) {
                $orderedArray[$key] = $array[$key];
            }

            return $orderedArray;
        }

        public static function getMenuList() {
            $baseDir = 'pages/';
            $structure = [];

            self::addToStructure($baseDir, $structure);

            $order = array("news", "account", "community", "guides", "systems", "shop");
            $structure = self::reorderArray($structure, $order);

            return $structure;
        }

        public static function findMenuParent($value) {
            $array = self::getMenuList();
            foreach ($array as $key => $subArray) {
                if (in_array($value, $subArray)) {
                    return $key;
                }
            }
            return null;
        }
    }

?>
