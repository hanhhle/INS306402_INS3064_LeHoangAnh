<?php

class RequestController 
{
    public function index() 
    {
        // 1. Lấy danh sách requests của sinh viên này từ RequestRepository
        // 2. Trả về file giao diện: Views/requests/index.php
    }

    // Hiển thị form tạo yêu cầu
    public function create() 
    {
        // 1. Trả về file giao diện form: Views/requests/create.php
    }

    // Xử lý lưu yêu cầu mới
    public function store() 
    {
        // 1. Đọc dữ liệu tiêu đề, mô tả từ form (POST)
        // 2. Gọi RequestService để lưu vào Database
        // 3. Chuyển hướng (redirect) về lại trang danh sách (/requests)
    }

    // Nhân viên cập nhật trạng thái
    public function updateStatus($id) 
    {
        // 1. Đọc trạng thái mới từ form gửi lên
        // 2. Gọi Service để đổi trạng thái: service.changeStatus(id, newStatus)
        // 3. Chuyển hướng về trang xem chi tiết (/requests/{id})
    }
}