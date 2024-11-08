<?php
    if(!defined('PAGELOAD')) { http_response_code(404); echo 'Failed to locate resource...'; exit(); }

    class Config {
        protected $config;
        protected $configFile = 'config/config.ini';

        public function __construct() {
            $this->config = $this->loadConfig();
        }

        protected function loadConfig() {
            $config = parse_ini_file($this->configFile, true);
            if ($config === false) { die('Não foi possível ler o arquivo de configuração.'); }
            return $config;            
        }

        public function getConfigValue($cfgName) {
            $keys = explode('.', $cfgName);
            $value = $this->config;
            foreach ($keys as $key) {
                if (isset($value[$key])) {
                    $value = $value[$key];
                } else {
                    return null;
                }
            }
            return $value;
        }

        public function setConfigValue($cfgName, $newValue) {
            $keys = explode('.', $cfgName);
            $arrayRef = &$this->config;
            foreach ($keys as $key) {
                if (isset($arrayRef[$key])) {
                    $arrayRef = &$arrayRef[$key];
                } else {
                    return false;
                }
            }
            $arrayRef = $newValue;
            return $this->saveConfig();
        }
    
        protected function saveConfig() {
            $content = '';
            foreach ($this->config as $section => $values) {
                $content .= "[$section]\n";
                foreach ($values as $key => $value) {
                    $content .= "$key = \"$value\"\n";
                }
            }
    
            if (file_put_contents($this->configFile, $content) === false) {
                return false;
            }
            return true;
        }
        
    }