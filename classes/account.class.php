<?php
    if(!defined('PAGELOAD')) { http_response_code(404); echo 'Failed to locate resource...'; exit(); }

    class Account extends Database {
    
        private static function isConnected(): bool {
            return self::$connection !== null;
        }

        public static function isLogged(): bool {
            if (isset($_SESSION['accountid']) && isset($_SESSION['password'])) {
                // Use self:: para acessar propriedades e métodos estáticos
                if (!self::isConnected()) {
                    return false;
                }
                
                $query = "SELECT * FROM accounts WHERE id = :id AND password = :password";
                $stmt = self::$connection->prepare($query);
                $stmt->execute([
                    'id' => $_SESSION['accountid'],
                    'password' => $_SESSION['password']
                ]);
                if ($stmt->rowCount() > 0) {
                    return true;
                } else {
                    unset($_SESSION['accountid'], $_SESSION['password']);
                }
            }
            return false;
        }
    
        public static function doLogin(string $user, string $pass): bool {
            if (!self::isConnected()) {
                throw new Exception('Not connected to the database.');
            }
    
            $hashedPass = sha1($pass);
    
            $query = "SELECT * FROM accounts WHERE name = :name AND password = :password";
            $stmt = self::$connection->prepare($query);
            $stmt->execute([
                'name' => $user,
                'password' => $hashedPass
            ]);
    
            if ($stmt->rowCount() > 0) {
                $account = $stmt->fetch(PDO::FETCH_ASSOC);
                $_SESSION['accountid'] = $account['id'];
                $_SESSION['password'] = $hashedPass;
                return true;
            } else {
                return false;
            }
        }

        public static function doLogout(): bool {
            unset($_SESSION['accountid'], $_SESSION['password']);
            return true;
        }
    
        public static function createAccount(string $user, string $pass): bool {
            if (!self::isConnected()) {
                throw new Exception('Not connected to the database.');
            }
    
            $hashedPass = sha1($pass);
            
            if (self::userExists($user)) {
                return false;
            }
    
            $query = "INSERT INTO accounts (name, password) VALUES (:name, :password)";
            $stmt = self::$connection->prepare($query);
    
            try {
                $stmt->execute([
                    'name' => $user,
                    'password' => $hashedPass
                ]);
                return true;
            } catch (PDOException $e) {
                return false;
            }
        }
    
        private static function userExists(string $user): bool {
            if (!self::isConnected()) {
                throw new Exception('Not connected to the database.');
            }
    
            $query = "SELECT * FROM accounts WHERE name = :name";
            $stmt = self::$connection->prepare($query);
            $stmt->execute(['name' => $user]);
    
            return $stmt->rowCount() > 0;
        }

        public static function createCharacter(string $name, string $sex): bool {
            if (!self::isConnected()) {
                throw new Exception('Not connected to the database.');
            }
        
            if (!self::isLogged()) {
                return false;
            }
        
            $accountId = self::getSelfId();
        
            $defaultValues = [
                'world_id' => 0,
                'group_id' => 1,
                'level' => 1,
                'vocation' => 0,
                'health' => 150,
                'healthmax' => 150,
                'experience' => 0,
                'lookbody' => 68,
                'lookfeet' => 76,
                'lookhead' => 78,
                'looklegs' => 39,
                'looktype' => $sex === 0 ? 136 : 128,
                'lookaddons' => 0,
                'lookmount' => 0,
                'maglevel' => 0,
                'mana' => 0,
                'manamax' => 0,
                'manaspent' => 0,
                'soul' => 100,
                'town_id' => 22,
                'posx' => 31941,
                'posy' => 32424,
                'posz' => 7,
                'conditions' => 0x30,
                'cap' => 400,
                'sex' => $sex,
                'lastlogin' => 0,
                'lastip' => 0,
                'account_id' => $accountId,
                'name' => $name
            ];

            $columns = implode(", ", array_keys($defaultValues));
            $placeholders = ":" . implode(", :", array_keys($defaultValues));
            $query = "INSERT INTO players ($columns) VALUES ($placeholders)";
            $stmt = self::$connection->prepare($query);

            try {
                $stmt->execute($defaultValues);
                return true;
            } catch (PDOException $e) {
                return false;
            }
        }

        // Método para obter o ID da conta atual
        public static function getSelfId(): int {
            if (isset($_SESSION['accountid'])) {
                return $_SESSION['accountid'];
            }
        }
    }