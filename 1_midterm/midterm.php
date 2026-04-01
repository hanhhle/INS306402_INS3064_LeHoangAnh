<?php
$host = 'localhost';
$dbname = 'library_db';
$username = 'root';
$password = '';

try {
    $pdo = new PDO("mysql:host=$host;dbname=$dbname;charset=utf8mb4", $username, $password);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch(PDOException $e) {
    die("Kết nối thất bại: " . $e->getMessage());
}

// Xử lý các hành động (action)
$action = $_GET['action'] ?? 'list';
$id = isset($_GET['id']) ? (int)$_GET['id'] : 0;
$message = '';

// Xử lý thông báo chuyển hướng khi thêm/cập nhật thành công
if (isset($_GET['msg']) && $_GET['msg'] === 'success') {
    $message = '<div class="alert alert-success alert-dismissible fade show">Lưu sách thành công!<button type="button" class="btn-close" data-bs-dismiss="alert"></button></div>';
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $isbn = trim($_POST['isbn'] ?? '');
    $title = trim($_POST['title'] ?? '');
    $author = trim($_POST['author'] ?? '');
    $publisher = trim($_POST['publisher'] ?? '');
    $year = !empty($_POST['publication_year']) ? (int)$_POST['publication_year'] : null;
    $copies = (int)($_POST['available_copies'] ?? 1);

    if (empty($isbn) || empty($title) || empty($author) || $copies < 0) {
        $message = '<div class="alert alert-danger">Vui lòng điền đầy đủ thông tin bắt buộc hoặc kiểm tra lại số lượng bản sao.</div>';
    } else {
        try {
            if (isset($_POST['update']) && $id > 0) {
                // Cập nhật bản ghi hiện có
                $stmt = $pdo->prepare("UPDATE books SET isbn=?, title=?, author=?, publisher=?, publication_year=?, available_copies=? WHERE id=?");
                $stmt->execute([$isbn, $title, $author, $publisher, $year, $copies, $id]);
            } else {
                // Thêm bản ghi mới
                $stmt = $pdo->prepare("INSERT INTO books (isbn, title, author, publisher, publication_year, available_copies) VALUES (?, ?, ?, ?, ?, ?)");
                $stmt->execute([$isbn, $title, $author, $publisher, $year, $copies]);
            }
            // Chuyển hướng để tránh gửi lại biểu mẫu (form resubmission)
            header("Location: midterm.php?msg=success");
            exit;
        } catch(PDOException $e) {
            // Kiểm tra trùng lặp mã ISBN (Mã lỗi 23000)
            if ($e->getCode() == 23000) {
                $message = '<div class="alert alert-danger">Lỗi: Mã ISBN này đã tồn tại trong hệ thống.</div>';
            } else {
                $message = '<div class="alert alert-danger">Lỗi: ' . $e->getMessage() . '</div>';
            }
        }
    }
}

// Xử lý xóa sách
if (isset($_GET['delete']) && $id > 0) {
    try {
        $stmt = $pdo->prepare("DELETE FROM books WHERE id = ?");
        $stmt->execute([$id]);
        $message = '<div class="alert alert-success alert-dismissible fade show">Xóa sách thành công!<button type="button" class="btn-close" data-bs-dismiss="alert"></button></div>';
    } catch(PDOException $e) {
        $message = '<div class="alert alert-danger">Không thể xóa: ' . $e->getMessage() . '</div>';
    }
}

// Lấy thông tin sách để chỉnh sửa
$book = null;
if ($action === 'edit' && $id > 0) {
    $stmt = $pdo->prepare("SELECT * FROM books WHERE id = ?");
    $stmt->execute([$id]);
    $book = $stmt->fetch(PDO::FETCH_ASSOC);
f}

// Liệt kê tất cả sách
$stmt = $pdo->query("SELECT * FROM books ORDER BY id DESC");
$books = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Library Book Dashboard</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="bg-light">
<div class="container py-5">
    <h1 class="mb-4 text-center">📚 Library Book Management</h1>
    
    <?= $message ?>

    <div class="card mb-4 shadow-sm">
        <div class="card-header bg-primary text-white font-weight-bold">
            <?= $action === 'edit' ? 'Chỉnh sửa thông tin sách' : 'Thêm sách mới' ?>
        </div>
        <div class="card-body">
            <form method="POST" action="midterm.php<?= $action === 'edit' ? '?action=edit&id=' . $id : '' ?>">
                <div class="row">
                    <div class="col-md-4 mb-3">
                        <label>ISBN <span class="text-danger">*</span></label>
                        <input type="text" name="isbn" class="form-control" value="<?= htmlspecialchars($book['isbn'] ?? '') ?>" required>
                    </div>
                    <div class="col-md-8 mb-3">
                        <label>Title <span class="text-danger">*</span></label>
                        <input type="text" name="title" class="form-control" value="<?= htmlspecialchars($book['title'] ?? '') ?>" required>
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-6 mb-3">
                        <label>Author <span class="text-danger">*</span></label>
                        <input type="text" name="author" class="form-control" value="<?= htmlspecialchars($book['author'] ?? '') ?>" required>
                    </div>
                    <div class="col-md-6 mb-3">
                        <label>Publisher</label>
                        <input type="text" name="publisher" class="form-control" value="<?= htmlspecialchars($book['publisher'] ?? '') ?>">
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-4 mb-3">
                        <label>Publication Year</label>
                        <input type="number" name="publication_year" class="form-control" value="<?= $book['publication_year'] ?? '' ?>">
                    </div>
                    <div class="col-md-4 mb-3">
                        <label>Available Copies <span class="text-danger">*</span></label>
                        <input type="number" name="available_copies" class="form-control" value="<?= $book['available_copies'] ?? 1 ?>" min="0" required>
                    </div>
                </div>
                <button type="submit" name="<?= $action === 'edit' ? 'update' : 'add' ?>" class="btn btn-primary">
                    <?= $action === 'edit' ? 'Cập nhật sách' : 'Thêm sách' ?>
                </button>
                <a href="midterm.php" class="btn btn-secondary">Hủy bỏ</a>
            </form>
        </div>
    </div>

    <div class="card shadow-sm">
        <div class="card-header bg-dark text-white font-weight-bold">Books Inventory</div>
        <div class="card-body table-responsive">
            <table class="table table-hover align-middle">
                <thead class="table-dark">
                    <tr>
                        <th>ID</th>
                        <th>ISBN</th>
                        <th>Title</th>
                        <th>Author</th>
                        <th>Publisher</th>
                        <th>Year</th>
                        <th>Copies</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if (empty($books)): ?>
                    <tr>
                        <td colspan="8" class="text-center py-4">Không tìm thấy sách nào trong thư viện.</td>
                    </tr>
                    <?php else: ?>
                        <?php foreach ($books as $b): ?>
                        <tr>
                            <td><?= $b['id'] ?></td>
                            <td><span class="badge bg-secondary"><?= htmlspecialchars($b['isbn']) ?></span></td>
                            <td class="fw-bold"><?= htmlspecialchars($b['title']) ?></td>
                            <td><?= htmlspecialchars($b['author']) ?></td>
                            <td><?= htmlspecialchars($b['publisher'] ?? 'N/A') ?></td>
                            <td><?= $b['publication_year'] ?? 'N/A' ?></td>
                            <td>
                                <?php if($b['available_copies'] == 0): ?>
                                    <span class="badge bg-danger">Hết hàng</span>
                                <?php else: ?>
                                    <span class="badge bg-success"><?= $b['available_copies'] ?></span>
                                <?php endif; ?>
                            </td>
                            <td>
                                <a href="midterm.php?action=edit&id=<?= $b['id'] ?>" class="btn btn-sm btn-warning">Sửa</a>
                                <a href="midterm.php?delete=1&id=<?= $b['id'] ?>" 
                                   onclick="return confirm('Bạn có chắc chắn muốn xóa cuốn sách này?')" 
                                   class="btn btn-sm btn-danger">Xóa</a>
                            </td>
                        </tr>
                        <?php endforeach; ?>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>