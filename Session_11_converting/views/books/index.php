<?php
// Xử lý thông báo (từ Controller truyền sang hoặc từ URL redirect về)
$displayMessage = $message; 
if (isset($_GET['msg']) && $_GET['msg'] === 'success') {
    $displayMessage = '<div class="alert alert-success alert-dismissible fade show">Lưu sách thành công!<button type="button" class="btn-close" data-bs-dismiss="alert"></button></div>';
} elseif (isset($_GET['msg']) && $_GET['msg'] === 'deleted') {
    $displayMessage = '<div class="alert alert-success alert-dismissible fade show">Xóa sách thành công!<button type="button" class="btn-close" data-bs-dismiss="alert"></button></div>';
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Library Book Dashboard (MVC)</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="bg-light">
<div class="container py-5">
    <h1 class="mb-4 text-center">📚 Library Book Management</h1>
    
    <?= $displayMessage ?>

    <div class="card mb-4 shadow-sm">
        <div class="card-header bg-primary text-white font-weight-bold">
            <?= $action === 'edit' ? 'Chỉnh sửa thông tin sách' : 'Thêm sách mới' ?>
        </div>
        <div class="card-body">
            <form method="POST" action="index.php?action=save<?= $action === 'edit' ? '&id=' . $id : '' ?>">
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
                <a href="index.php" class="btn btn-secondary">Hủy bỏ</a>
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
                                <a href="index.php?action=edit&id=<?= $b['id'] ?>" class="btn btn-sm btn-warning">Sửa</a>
                                <a href="index.php?action=delete&id=<?= $b['id'] ?>" 
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