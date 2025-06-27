@extends('layout')

@section('content')
<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <title>Chỉnh sửa thông tin sinh viên</title>
    <script src="https://cdn.jsdelivr.net/npm/axios/dist/axios.min.js"></script>
    <style>
        body {
            font-family: 'Poppins', sans-serif;
            background-color: #f9f9f9;
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
            margin: 0;
        }

        .container {
            background-color: #fff;
            padding: 30px;
            border-radius: 10px;
            box-shadow: 0 2px 10px rgba(0,0,0,0.1);
            width: 500px;
        }

        h2 {
            text-align: center;
            margin-bottom: 20px;
        }

        label {
            display: block;
            margin-top: 15px;
            font-weight: 500;
        }

        input, select {
            width: 100%;
            padding: 8px 12px;
            margin-top: 5px;
            border: 1px solid #ccc;
            border-radius: 6px;
        }

        input[readonly], select[disabled] {
            background-color: #f0f0f0;
        }

        button {
            margin-top: 20px;
            width: 100%;
            padding: 12px;
            border: none;
            background-color: #007bff;
            color: white;
            font-size: 16px;
            border-radius: 6px;
            cursor: pointer;
        }

        button:hover {
            background-color: #0056b3;
        }
    </style>
</head>
<body>
<div class="container">
    <h2>Chỉnh sửa thông tin cá nhân</h2>
    <form id="update-form">
        <label for="ho_ten">Họ và Tên</label>
        <input type="text" id="ho_ten" name="ho_ten" required>

        <label for="ngay_sinh">Ngày sinh</label>
        <input type="date" id="ngay_sinh" name="ngay_sinh" required>

        <label for="gioi_tinh">Giới tính</label>
        <select id="gioi_tinh" name="gioi_tinh">
            <option value="Nam">Nam</option>
            <option value="Nữ">Nữ</option>
        </select>

        <label for="que_quan">Quê quán</label>
        <input type="text" id="que_quan" name="que_quan" required>

        <label for="email">Email</label>
        <input type="email" id="email" name="email" required>

        <label for="so_dien_thoai">SĐT</label>
        <input type="text" id="so_dien_thoai" name="so_dien_thoai" required>

        <label for="khoa_hoc">Khóa học</label>
        <input type="number" id="khoa_hoc" name="khoa_hoc" required>

        <label for="ma_nganh">Ngành học</label>
        <select id="ma_nganh" name="ma_nganh" disabled></select>

        <label for="ma_khoa">Khoa</label>
        <select id="ma_khoa" name="ma_khoa" disabled></select>

        <button type="submit">Lưu thay đổi</button>
    </form>
</div>

<script>
    document.addEventListener('DOMContentLoaded', () => {
        const token = localStorage.getItem('token');
        if (!token) {
            alert("Chưa đăng nhập!");
            return;
        }

        axios.defaults.headers.common['Authorization'] = `Bearer ${token}`;

        // Lấy dữ liệu sinh viên
        axios.get('http://127.0.0.1:8000/api/thongtinsv')
            .then(res => {
                const sv = res.data;
                document.getElementById('ho_ten').value = sv.ho_ten;
                document.getElementById('ngay_sinh').value = sv.ngay_sinh;
                document.getElementById('gioi_tinh').value = sv.gioi_tinh;
                document.getElementById('que_quan').value = sv.que_quan;
                document.getElementById('email').value = sv.email;
                document.getElementById('so_dien_thoai').value = sv.so_dien_thoai;
                document.getElementById('khoa_hoc').value = sv.khoa_hoc;

                // Gán ngành
                const nganhSelect = document.getElementById('ma_nganh');
                nganhSelect.innerHTML = `<option value="${sv.ma_nganh}">${sv.ten_nganh}</option>`;

                // Gán khoa
                const khoaSelect = document.getElementById('ma_khoa');
                khoaSelect.innerHTML = `<option value="${sv.ma_khoa}">${sv.ten_khoa}</option>`;
            });

        // Xử lý gửi form
        document.getElementById('update-form').addEventListener('submit', function (e) {
            e.preventDefault();

            const data = {
                ho_ten: document.getElementById('ho_ten').value,
                ngay_sinh: document.getElementById('ngay_sinh').value,
                gioi_tinh: document.getElementById('gioi_tinh').value,
                que_quan: document.getElementById('que_quan').value,
                email: document.getElementById('email').value,
                so_dien_thoai: document.getElementById('so_dien_thoai').value,
                khoa_hoc: document.getElementById('khoa_hoc').value,
                ma_nganh: document.getElementById('ma_nganh').value,
                ma_khoa: document.getElementById('ma_khoa').value,
            };

            axios.put('http://127.0.0.1:8000/api/thongtinsv', data)
                .then(response => {
                    alert("Cập nhật thành công!");
                    window.location.href = "/sinhvien/thongtinsv";
                })
                .catch(error => {
                    alert("Lỗi khi cập nhật!");
                    console.error(error.response?.data || error);
                });
        });
    });
</script>
</body>
</html>
@endsection
