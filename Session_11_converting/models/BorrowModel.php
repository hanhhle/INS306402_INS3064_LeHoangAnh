<?php
require_once __DIR__ . '/../config/database.php';

class BorrowModel {
    private $pdo;

    public function __construct() {
        $this->pdo = getConnection();
    }

    // Lấy danh sách tất cả các lượt mượn
    public function getAll() {
        $sql = "SELECT b.*, s.title FROM borrowings b 
                JOIN books s ON b.book_id = s.id 
                ORDER BY b.id DESC";
        return $this->pdo->query($sql)->fetchAll(PDO::FETCH_ASSOC);
    }

    // Ghi nhận một lượt mượn mới
    public function create($data) {
        $stmt = $this->pdo->prepare("INSERT INTO borrowings (book_id, borrower_name, borrow_date) VALUES (?, ?, ?)");
        return $stmt->execute([$data['book_id'], $data['borrower_name'], $data['borrow_date']]);
    }
}