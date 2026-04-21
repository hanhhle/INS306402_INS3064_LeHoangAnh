<?php
// models/BookModel.php
require_once __DIR__ . '/../config/database.php';

class BookModel {
    private $pdo;

    public function __construct() {
        $this->pdo = getConnection();
    }

    public function getAllBooks() {
        $stmt = $this->pdo->query("SELECT * FROM books ORDER BY id DESC");
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function getBookById($id) {
        $stmt = $this->pdo->prepare("SELECT * FROM books WHERE id = ?");
        $stmt->execute([$id]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    public function createBook($data) {
        $stmt = $this->pdo->prepare("INSERT INTO books (isbn, title, author, publisher, publication_year, available_copies) VALUES (?, ?, ?, ?, ?, ?)");
        $stmt->execute([$data['isbn'], $data['title'], $data['author'], $data['publisher'], $data['year'], $data['copies']]);
    }

    public function updateBook($id, $data) {
        $stmt = $this->pdo->prepare("UPDATE books SET isbn=?, title=?, author=?, publisher=?, publication_year=?, available_copies=? WHERE id=?");
        $stmt->execute([$data['isbn'], $data['title'], $data['author'], $data['publisher'], $data['year'], $data['copies'], $id]);
    }

    public function deleteBook($id) {
        $stmt = $this->pdo->prepare("DELETE FROM books WHERE id = ?");
        $stmt->execute([$id]);
    }
}