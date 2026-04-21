<?php
// Xử lý thông báo chuyển hướng (msg)
$displayMessage = '';
if (isset($_GET['msg'])) {
    if ($_GET['msg'] === 'success') {
        $displayMessage = '<div class="alert alert-success">Ghi nhận lượt mượn thành công!</div>';
    } elseif ($_GET['msg'] === 'error') {
        $displayMessage = '<div class="alert alert-danger">Có lỗi xảy ra, vui lòng kiểm tra lại.</div>';
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Quản lý Mượn Sách</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="bg-light">

<nav class="navbar navbar-expand-lg navbar-dark bg-dark mb-4">
    <div class="container">
        <a class="navbar-brand" href="index.php">Library Admin</a>
        <div class="navbar-nav">
            <a class="nav-link" href="index.php?controller=book">Quản lý Sách</a>
            <a class="nav-link active" href="index.php?controller=borrow">Quản lý Mượn</a>
        </div>
    </div>
</nav>

<div class="container">
    <h2 class="mb-4">📋 Quản lý Mượn Sách</h2>

    <?= $displayMessage ?>

    <div class="card mb-5 shadow-sm">
        <div class="card-header bg-success text-white">Thêm Lượt Mượn Mới</div>
        <div class="card-body">
            <form method="POST" action="index.php?controller=borrow&action=store">
                <div class="row">
                    <div class="col-md-5 mb-3">
                        <label class="form-label">Chọn Sách <span class="text-danger">*</span></label>
                        <select name="book_id" class="form-select" required>
                            <option value="">-- Chọn cuốn sách --</option>
                            <?php foreach ($books as $b): ?>
                                <option value="<?= $b['id'] ?>">
                                    <?= htmlspecialchars($b['title']) ?> (ISBN: <?= $b['isbn'] ?>)
                                </option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                    <div class="col-md-5 mb-3">
                        <label class="form-label">Tên Người Mượn <span class="text-danger">*</span></label>
                        <input type="text" name="borrower_name" class="form-control" placeholder="Nhập tên sinh viên/độc giả" required>
                    </div>
                    <div class="col-md-2 mb-3 d-flex align-items-end">
                        <button type="submit" class="btn btn-success w-100">Ghi nhận</button>
                    </div>
                </div>
            </form>
        </div>
    </div>

    <div class="card shadow-sm">
        <div class="card-header bg-dark text-white">Lịch Sử Mượn Sách</div>
        <div class="card-body table-responsive">
            <table class="table table-hover">
                <thead class="table-secondary">
                    <tr>
                        <th>ID</th>
                        <th>Tên Sách</th>
                        <th>Người Mượn</th>
                        <th>Ngày Mượn</th>
                        <th>Trạng thái</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if (empty($borrows)): ?>
                        <tr><td colspan="5" class="text-center">Chưa có dữ liệu mượn sách.</td></tr>
                    <?php else: ?>
                        <?php foreach ($borrows as $row): ?>
                        <tr>
                            <td><?= $row['id'] ?></td>
                            <td class="fw-bold"><?= htmlspecialchars($row['title']) ?></td>
                            <td><?= htmlspecialchars($row['borrower_name']) ?></td>
                            <td><?= date('d/m/Y', strtotime($row['borrow_date'])) ?></td>
                            <td><span class="badge bg-warning text-dark">Đang mượn</span></td>
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