@extends('layout')

@section('content')
<div class="container">
    <div class="avatar">Avatar</div>
    <h1>Thông Tin Sinh Viên</h1>

    <table id="sinhvien-info">
        <tr><td colspan="2">Đang tải dữ liệu...</td></tr>
    </table>

    <div class="buttons" id="action-buttons" style="display: none">
        <a href="#" id="edit-link" class="button">Chỉnh sửa</a>
        <a href="/sinhvien" class="button">Quay lại</a>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/axios/dist/axios.min.js"></script>
<script>
    document.addEventListener('DOMContentLoaded', function () {
        const token = localStorage.getItem('token');

        if (!token) {
            alert("Bạn chưa đăng nhập hoặc token không tồn tại!");
            return;
        }

        axios.defaults.headers.common['Authorization'] = `Bearer ${token}`;

        axios.get('http://127.0.0.1:8000/api/thongtinsv')
            .then(response => {
                const data = response.data;
                const table = document.getElementById('sinhvien-info');
                const buttons = document.getElementById('action-buttons');
                const editLink = document.getElementById('edit-link');

                table.innerHTML = `
                    <tr><td>Mã Sinh Viên:</td><td>${data.ma_sinh_vien}</td></tr>
                    <tr><td>Họ và Tên:</td><td>${data.ho_ten}</td></tr>
                    <tr><td>Ngày Sinh:</td><td>${data.ngay_sinh}</td></tr>
                    <tr><td>Giới Tính:</td><td>${data.gioi_tinh}</td></tr>
                    <tr><td>Quê Quán:</td><td>${data.que_quan}</td></tr>
                    <tr><td>Ngành Học:</td><td>${data.ten_nganh || '(chưa có)'}</td></tr>
                    <tr><td>Khoa:</td><td>${data.ten_khoa || '(chưa có)'}</td></tr>
                    <tr><td>Email:</td><td>${data.email}</td></tr>
                    <tr><td>SĐT:</td><td>${data.so_dien_thoai}</td></tr>
                `;

                editLink.href = `/sinhvien/thongtinsv/edit/${data.ma_sinh_vien}`;
                buttons.style.display = 'flex';
            })
            .catch(error => {
                document.getElementById('sinhvien-info').innerHTML = `<tr><td colspan="2">Không thể tải dữ liệu.</td></tr>`;
                console.error(error);
            });
    });
</script>

<style>
    @import url('https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600&display=swap');
    body {
        font-family: 'Poppins', sans-serif;
        background-color: #f9f9f9;
        margin: 0;
        padding: 0;
    }

    .container {
        max-width: 600px;
        background-color: #fff;
        padding: 30px;
        margin: 50px auto;
        border-radius: 10px;
        box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1);
        text-align: center;
    }

    .avatar {
        width: 120px;
        height: 120px;
        background-color: #eaeaea;
        border-radius: 50%;
        margin: 0 auto 20px;
        display: flex;
        justify-content: center;
        align-items: center;
        font-size: 14px;
        color: #999;
    }

    h1 {
        color: #333;
        margin-bottom: 20px;
        font-weight: 600;
    }

    table {
        width: 100%;
        border-collapse: collapse;
    }

    table td {
        padding: 10px;
        border-bottom: 1px solid #ddd;
        text-align: left;
    }

    table td:first-child {
        font-weight: 500;
        color: #555;
        width: 40%;
        text-align: right;
    }

    table td:last-child {
        width: 60%;
        text-align: left;
    }

    .buttons {
        display: flex;
        justify-content: center;
        gap: 15px;
        margin-top: 20px;
    }

    .button {
        padding: 10px 20px;
        background-color: #007bff;
        color: #fff;
        border: none;
        border-radius: 4px;
        font-size: 16px;
        cursor: pointer;
        text-decoration: none;
    }

    .button:hover {
        background-color: #0056b3;
    }
</style>
@endsection
