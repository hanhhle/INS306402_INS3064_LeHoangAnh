<?php
// controllers/BookController.php
require_once __DIR__ . '/../models/BookModel.php';

class BookController {
    private $model;

    public function __construct() {
        $this->model = new BookModel();
    }

    // Hiển thị trang chính (có cả form và danh sách sách)
    public function index($message = '') {
        $action = $_GET['action'] ?? 'list';
        $id = isset($_GET['id']) ? (int)$_GET['id'] : 0;
        
        $book = null;
        if ($action === 'edit' && $id > 0) {
            $book = $this->model->getBookById($id);
        }

        $books = $this->model->getAllBooks();
        
        // Gọi View ra để hiển thị và truyền biến vào
        require __DIR__ . '/../views/books/index.php';
    }

    // Xử lý thêm hoặc cập nhật sách
    public function save() {
        $id = isset($_GET['id']) ? (int)$_GET['id'] : 0;
        
        $data = [
            'isbn' => trim($_POST['isbn'] ?? ''),
            'title' => trim($_POST['title'] ?? ''),
            'author' => trim($_POST['author'] ?? ''),
            'publisher' => trim($_POST['publisher'] ?? ''),
            'year' => !empty($_POST['publication_year']) ? (int)$_POST['publication_year'] : null,
            'copies' => (int)($_POST['available_copies'] ?? 1)
        ];

        if (empty($data['isbn']) || empty($data['title']) || empty($data['author']) || $data['copies'] < 0) {
            $this->index('<div class="alert alert-danger">Vui lòng điền đầy đủ thông tin bắt buộc.</div>');
            return;
        }

        try {
            if (isset($_POST['update']) && $id > 0) {
                $this->model->updateBook($id, $data);
            } else {
                $this->model->createBook($data);
            }
            header("Location: index.php?action=list&msg=success");
            exit;
        } catch(PDOException $e) {
            if ($e->getCode() == 23000) {
                $this->index('<div class="alert alert-danger">Lỗi: Mã ISBN này đã tồn tại.</div>');
            } else {
                $this->index('<div class="alert alert-danger">Lỗi: ' . $e->getMessage() . '</div>');
            }
        }
    }

    // Xử lý xóa
    public function delete() {
        $id = isset($_GET['id']) ? (int)$_GET['id'] : 0;
        if ($id > 0) {
            try {
                $this->model->deleteBook($id);
                header("Location: index.php?action=list&msg=deleted");
                exit;
            } catch(PDOException $e) {
                $this->index('<div class="alert alert-danger">Không thể xóa: ' . $e->getMessage() . '</div>');
            }
        }
    }
}