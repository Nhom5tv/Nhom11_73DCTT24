@extends('layout')

@section('content')
<div class="container">
    <div class="avatar">Avatar</div>
    <h1>Thông Tin Giảng Viên</h1>

    <table id="giangvien-info">
        <tr><td colspan="2">Đang tải dữ liệu...</td></tr>
    </table>

    <div class="buttons" id="action-buttons" style="display: none">
        <a href="#" id="edit-link" class="button">Chỉnh sửa</a>
        <a href="/giangvien" class="button">Quay lại</a>
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

        axios.get('http://127.0.0.1:8000/api/thongtingv')
            .then(response => {
                const data = response.data;
                const table = document.getElementById('giangvien-info');
                const buttons = document.getElementById('action-buttons');
                const editLink = document.getElementById('edit-link');

                table.innerHTML = `
                    <tr><td>Mã Giảng Viên:</td><td>${data.ma_giang_vien}</td></tr>
                    <tr><td>Họ và Tên:</td><td>${data.ho_ten}</td></tr>
                    <tr><td>Email:</td><td>${data.email}</td></tr>
                    <tr><td>SĐT:</td><td>${data.so_dien_thoai}</td></tr>
                    <tr><td>Chuyên Ngành:</td><td>${data.chuyen_nganh}</td></tr>
                    <tr><td>Khoa:</td><td>${data.ten_khoa || '(chưa có)'}</td></tr>
                `;

editLink.href = `/giaovien/thongtingv/edit`;
                buttons.style.display = 'flex';
            })
            .catch(error => {
                document.getElementById('giangvien-info').innerHTML = `<tr><td colspan="2">Không thể tải dữ liệu.</td></tr>`;
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
        background-color: #28a745;
        color: #fff;
        border: none;
        border-radius: 4px;
        font-size: 16px;
        cursor: pointer;
        text-decoration: none;
    }

    .button:hover {
        background-color: #1e7e34;
    }
</style>
@endsection
