@extends('layout')

@section('content')
<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <title>Chỉnh sửa thông tin giảng viên</title>
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
    <h2>Chỉnh sửa thông tin giảng viên</h2>
    <form id="update-form">
        <label for="ho_ten">Họ và Tên</label>
        <input type="text" id="ho_ten" name="ho_ten" required>

        <label for="email">Email</label>
        <input type="email" id="email" name="email" required>

        <label for="so_dien_thoai">SĐT</label>
        <input type="text" id="so_dien_thoai" name="so_dien_thoai">

        <label for="chuyen_nganh">Chuyên ngành</label>
        <input type="text" id="chuyen_nganh" name="chuyen_nganh">

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

        let maGiangVien = "";

        // Lấy dữ liệu giảng viên
        axios.get('http://127.0.0.1:8000/api/thongtingv')
            .then(res => {
                const gv = res.data;
                maGiangVien = gv.ma_giang_vien;

                document.getElementById('ho_ten').value = gv.ho_ten;
                document.getElementById('email').value = gv.email;
                document.getElementById('so_dien_thoai').value = gv.so_dien_thoai || "";
                document.getElementById('chuyen_nganh').value = gv.chuyen_nganh || "";

                // Khoa (readonly)
                const khoaSelect = document.getElementById('ma_khoa');
                khoaSelect.innerHTML = `<option value="${gv.ma_khoa}">${gv.ten_khoa}</option>`;
            })
            .catch(err => {
                alert("Không thể tải dữ liệu giảng viên.");
                console.error(err.response?.data || err);
            });

        // Gửi form cập nhật
        document.getElementById('update-form').addEventListener('submit', function (e) {
            e.preventDefault();

            const data = {
                ho_ten: document.getElementById('ho_ten').value,
                email: document.getElementById('email').value,
                so_dien_thoai: document.getElementById('so_dien_thoai').value,
                chuyen_nganh: document.getElementById('chuyen_nganh').value,
                ma_khoa: document.getElementById('ma_khoa').value,
            };

            axios.put(`http://127.0.0.1:8000/api/thongtingv`, data)
                .then(response => {
                    alert("Cập nhật thành công!");
                    window.location.href = "/giaovien/thongtingv";
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
