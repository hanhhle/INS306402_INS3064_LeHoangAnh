<?php
class Database {
    private static $instance = null;
    private $pdo;

    private function __construct() {
        $config = require __DIR__ . '/../config/database.php';
        $dsn = "mysql:host={$config['host']};dbname={$config['dbname']};charset={$config['charset']}";
        try {
            $this->pdo = new PDO($dsn, $config['username'], $config['password'], [
                PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
                PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC
            ]);
        } catch (PDOException $e) {
            error_log($e->getMessage()); // Log lỗi nội bộ [cite: 602]
            die("Lỗi kết nối CSDL. Vui lòng thử lại sau."); // Giấu lỗi thực tế với người dùng [cite: 603, 604]
        }
    }

    public static function getInstance() {
        if (self::$instance === null) { self::$instance = new Database(); }
        return self::$instance;
    }

    // Hàm tiện ích để chạy lệnh SQL
    public function query($sql, $params = []) {
        $stmt = $this->pdo->prepare($sql);
        $stmt->execute($params);
        return $stmt;
    }
    
    public function fetchAll($sql, $params = []) {
        return $this->query($sql, $params)->fetchAll();
    }

    public function fetch($sql, $params = []) {
        return $this->query($sql, $params)->fetch();
    }
}
?>