<?php
require_once __DIR__ . '/../models/BorrowModel.php';
require_once __DIR__ . '/../models/BookModel.php';

class BorrowController {
    private $borrowModel;
    private $bookModel;

    public function __construct() {
        $this->borrowModel = new BorrowModel();
        $this->bookModel = new BookModel();
    }

    public function index() {
        $borrows = $this->borrowModel->getAll();
        $books = $this->bookModel->getAllBooks(); // Để hiện danh sách chọn sách trong form
        require __DIR__ . '/../views/borrows/index.php';
    }

    public function store() {
        $data = [
            'book_id' => $_POST['book_id'],
            'borrower_name' => trim($_POST['borrower_name']),
            'borrow_date' => date('Y-m-d')
        ];
        $this->borrowModel->create($data);
        header("Location: index.php?controller=borrow&action=index");
        exit;
    }
}