<?php
    if(!defined('PAGELOAD')) { http_response_code(404); echo 'Failed to locate resource...'; exit(); }

    class Player extends Database {
        
        public static function getPlayerByName(string $name) {
            $conn = self::getConnection();
            if (!$conn) {
                throw new Exception('Not connected to the database.');
            }

            $query = "SELECT * FROM players WHERE name = :name";
            $stmt = $conn->prepare($query);
            $stmt->execute(['name' => $name]);

            return $stmt->fetch(PDO::FETCH_ASSOC);
        }
        
        public static function getPlayersByAccountID(int $accId) {
            $conn = self::getConnection();
            if (!$conn) {
                throw new Exception('Not connected to the database.');
            }

            $query = "SELECT * FROM players WHERE `account_id` = :accid";
            $stmt = $conn->prepare($query);
            $stmt->execute(['accid' => $accId]);

            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        }

        public static function getPlayersOrderedBy(string $column, int $start = 0, int $limit = 100) {
            $conn = self::getConnection();
            if (!$conn) {
                throw new Exception('Not connected to the database.');
            }
        
            $valid_columns = ['name', 'level'];
            if (!in_array($column, $valid_columns)) {
                throw new InvalidArgumentException('Invalid column name.');
            }
        
            if ($start < 0 || $limit <= 0) {
                throw new InvalidArgumentException('Invalid start or limit value.');
            }
        
            $query = "SELECT * FROM players WHERE `group_id` < 2 ORDER BY $column DESC LIMIT :start, :limit";
            $stmt = $conn->prepare($query);
        
            $stmt->bindValue(':start', $start, PDO::PARAM_INT);
            $stmt->bindValue(':limit', $limit, PDO::PARAM_INT);

            $stmt->execute();
        
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        }

        public static function getPlayersOnline(int $start = 0, int $limit = 100) {
            $conn = self::getConnection();
            if (!$conn) {
                throw new Exception('Not connected to the database.');
            }
        
            if ($start < 0 || $limit <= 0) {
                throw new InvalidArgumentException('Invalid start or limit value.');
            }
        
            $query = "SELECT * FROM players WHERE `online` > 0 ORDER BY `level` DESC LIMIT :start, :limit";
            $stmt = $conn->prepare($query);
        
            $stmt->bindValue(':start', $start, PDO::PARAM_INT);
            $stmt->bindValue(':limit', $limit, PDO::PARAM_INT);
            
            $stmt->execute();
        
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        }

        public static function countOnlinePlayers(): int {
            $conn = self::getConnection();
            if (!$conn) {
                throw new Exception('Not connected to the database.');
            }

            $query = "SELECT COUNT(*) FROM players WHERE online = 1";
            $stmt = $conn->prepare($query);
            $stmt->execute();

            return (int) $stmt->fetchColumn();
        }
    }
?>
