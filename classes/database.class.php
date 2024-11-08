<?php
    if(!defined('PAGELOAD')) { http_response_code(404); echo 'Failed to locate resource...'; exit(); }

    class Database extends Config {
        public bool $isConnected = false;
        protected static $connection;
    
        public function __construct() {
            parent::__construct();
            $this->connect();
        }
    
        protected function connect() {
            $host = $this->getConfigValue('database.host');
            $port = $this->getConfigValue('database.port');
            $username = $this->getConfigValue('database.username');
            $password = $this->getConfigValue('database.password');
            $dbname = $this->getConfigValue('database.dbname');
            $dsn = "mysql:host=$host;port=$port;dbname=$dbname";
            try {
                $this->isConnected = true;
                self::$connection = new PDO($dsn, $username, $password);
                self::$connection->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
            } catch (PDOException $e) {
                $this->isConnected = false;
            }
        }

        public function isInstalled(): bool {
            if ($this->getConfigValue('general.installed') == "yes") { return true;}
            return false;
        }

        public function setInstalled(): void {
            $this->setConfigValue('general.installed', "yes");
        }

        public function unsetInstalled(): void {
            $this->setConfigValue('general.installed', "no");
        }

        public function tableExists(string $tableName): bool {
            if (!$this->isConnected) {
                throw new Exception('Not connected to the database.');
            }
            
            $query = "SHOW TABLES LIKE :tableName";
            $stmt = self::$connection->prepare($query);
            $stmt->execute(['tableName' => $tableName]);
            
            return $stmt->rowCount() > 0;
        }

        public function checkTables($tables): bool {
            foreach ($tables as $table) {
                if (!$this->tableExists($table)) { return false; }
            }        
            return true;
        }

        public function createDefaultTables(): bool {
            if (!$this->isConnected) {
                throw new Exception('Not connected to the database.');
            }
        
            $tables = [
                'zdsite_news' => "
                    CREATE TABLE IF NOT EXISTS zdsite_news (
                        id VARCHAR(255) NOT NULL UNIQUE,
                        title VARCHAR(255),
                        content TEXT,
                        writer VARCHAR(255),
                        time VARCHAR(255),
                        PRIMARY KEY (id)
                    )
                ",
                'zdsite_donationhistory' => "
                    CREATE TABLE IF NOT EXISTS zdsite_donationhistory (
                        id VARCHAR(255) NOT NULL UNIQUE,
                        donor_account VARCHAR(255),
                        amount DECIMAL(10, 2),
                        time VARCHAR(255),
                        PRIMARY KEY (id)
                    )
                ",
                'zdsite_shop' => "
                    CREATE TABLE IF NOT EXISTS zdsite_shop (
                        id VARCHAR(255) NOT NULL UNIQUE,
                        item_name VARCHAR(255),
                        price DECIMAL(10, 2),
                        stock INT,
                        item_id INT DEFAULT 0,
                        item_count INT DEFAULT 0,
                        service_name VARCHAR(255) DEFAULT NULL,
                        description VARCHAR(255),
                        PRIMARY KEY (id)
                    )
                ",
                'zdsite_shophistory' => "
                    CREATE TABLE IF NOT EXISTS zdsite_shophistory (
                        id VARCHAR(255) NOT NULL UNIQUE,
                        item_id INT,
                        quantity INT,
                        time VARCHAR(255),
                        PRIMARY KEY (id)
                    )
                "
            ];
        
            try {
                foreach ($tables as $tableName => $createQuery) {
                    if (!$this->tableExists($tableName)) {
                        $stmt = self::$connection->prepare($createQuery);
                        $stmt->execute();
                    }
                }
                return true;
            } catch (Exception $e) {
                return false;
            }
        }        

        public static function getConnection(): ?PDO {
            return self::$connection;
        }
        
    }