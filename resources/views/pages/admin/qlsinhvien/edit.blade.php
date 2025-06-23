@extends('layout')

@section('content')
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Sửa Thông Tin Sinh Viên</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
    <link rel="stylesheet" href="/css/button.css?v={{ time() }}">
    <link rel="stylesheet" href="/css/styleDT.css?v={{ time() }}">
    <style>
        body { font-family: Arial, sans-serif; background-color: #f4f4f9; }
        .form-container {
            display: flex; flex-wrap: wrap; gap: 15px; justify-content: space-between;
        }
        .input-group {
            width: 45%; margin-bottom: 15px;
            background-color: #fff; padding: 15px;
            border-radius: 8px; box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1);
        }
        .input-group label { font-weight: bold; color: #333; display: block; }
        .input-group input, .input-group select {
            width: 100%; padding: 10px; font-size: 14px; margin-top: 5px;
            border-radius: 5px; border: 1px solid #ddd;
        }
        .input-group input:focus, .input-group select:focus {
            border-color: #007bff; outline: none;
        }
        button.btn {
            padding: 12px 20px; background-color: #007bff; color: white;
            font-size: 16px; border: none; border-radius: 5px;
            cursor: pointer; width: 100%; margin-top: 20px;
        }
        button.btn:hover { background-color: #0056b3; }
        .quaylai a {
            display: block; text-align: center; margin-top: 15px;
            color: #fff; background-color: #6c757d; padding: 10px;
            border-radius: 5px; text-decoration: none;
        }
        .quaylai a:hover { background-color: #5a6268; }
    </style>
</head>
<body>
<main>
    <h1 style="text-align: center; color: #333;">Sửa Thông Tin Sinh Viên</h1>

    <form id="editStudentForm">
        <div class="form-container">

            <div class="input-group">
                <label for="ma_sinh_vien">Mã Sinh Viên</label>
                <input type="text" id="ma_sinh_vien" value="{{ $sinhvien->ma_sinh_vien }}" readonly>
            </div>

            <div class="input-group">
                <label for="user_id">User ID</label>
                <input type="text" id="user_id" value="{{ $sinhvien->user_id }}" required>
            </div>

            <div class="input-group">
                <label for="ma_khoa">Chọn Khoa</label>
                <select id="ma_khoa" required>
                    <option value="">-- Chọn khoa --</option>
                    <option value="1" {{ $sinhvien->ma_khoa == 1 ? 'selected' : '' }}>Công nghệ thông tin</option>
                    <option value="2" {{ $sinhvien->ma_khoa == 2 ? 'selected' : '' }}>Kinh tế</option>
                    <option value="3" {{ $sinhvien->ma_khoa == 3 ? 'selected' : '' }}>Ngoại ngữ</option>
                </select>
            </div>

            <div class="input-group">
                <label for="ma_nganh">Chọn ngành</label>
                <select id="ma_nganh" required>
                    <option value="">-- Chọn ngành --</option>
                    <option value="101" {{ $sinhvien->ma_nganh == 101 ? 'selected' : '' }}>Kỹ thuật phần mềm</option>
                    <option value="102" {{ $sinhvien->ma_nganh == 102 ? 'selected' : '' }}>Hệ thống thông tin</option>
                    <option value="201" {{ $sinhvien->ma_nganh == 201 ? 'selected' : '' }}>Quản trị kinh doanh</option>
                    <option value="301" {{ $sinhvien->ma_nganh == 301 ? 'selected' : '' }}>Tiếng Anh</option>
                </select>
            </div>

            <div class="input-group">
                <label for="ho_ten">Họ Tên</label>
                <input type="text" id="ho_ten" value="{{ $sinhvien->ho_ten }}" required>
            </div>

            <div class="input-group">
                <label for="ngay_sinh">Ngày Sinh</label>
                <input type="date" id="ngay_sinh" value="{{ $sinhvien->ngay_sinh }}" required>
            </div>

            <div class="input-group">
                <label for="gioi_tinh">Giới Tính</label>
                <select id="gioi_tinh" required>
                    <option value="Nam" {{ $sinhvien->gioi_tinh == 'Nam' ? 'selected' : '' }}>Nam</option>
                    <option value="Nữ" {{ $sinhvien->gioi_tinh == 'Nữ' ? 'selected' : '' }}>Nữ</option>
                </select>
            </div>

            <div class="input-group">
                <label for="que_quan">Quê Quán</label>
                <input type="text" id="que_quan" value="{{ $sinhvien->que_quan }}" required>
            </div>

            <div class="input-group">
                <label for="email">Email</label>
                <input type="email" id="email" value="{{ $sinhvien->email }}" required>
            </div>

            <div class="input-group">
                <label for="so_dien_thoai">Số Điện Thoại</label>
                <input type="text" id="so_dien_thoai" value="{{ $sinhvien->so_dien_thoai }}" required>
            </div>

            <div class="input-group">
                <label for="khoa_hoc">Khóa Học</label>
                <input type="text" id="khoa_hoc" value="{{ $sinhvien->khoa_hoc }}" required>
            </div>

            <button type="submit" class="btn">Cập nhật</button>

            <div class="quaylai">
                <a href="/admin/sinhvien">Quay lại</a>
            </div>
        </div>
    </form>
</main>

<script src="https://cdn.jsdelivr.net/npm/axios/dist/axios.min.js"></script>
<script>
    document.getElementById('editStudentForm').addEventListener('submit', function (e) {
        e.preventDefault();

        const token = localStorage.getItem('token');
        if (!token) {
            alert('Bạn chưa đăng nhập hoặc thiếu token');
            return;
        }

        const maSinhVien = document.getElementById('ma_sinh_vien').value;

        const formData = {
            user_id: document.getElementById('user_id').value,
            ma_khoa: document.getElementById('ma_khoa').value,
            ma_nganh: document.getElementById('ma_nganh').value,
            ho_ten: document.getElementById('ho_ten').value,
            ngay_sinh: document.getElementById('ngay_sinh').value,
            gioi_tinh: document.getElementById('gioi_tinh').value,
            que_quan: document.getElementById('que_quan').value,
            email: document.getElementById('email').value,
            so_dien_thoai: document.getElementById('so_dien_thoai').value,
            khoa_hoc: document.getElementById('khoa_hoc').value
        };

        axios.put(`/api/admin/sinhvien/${maSinhVien}`, formData, {
            headers: {
                Authorization: `Bearer ${token}`
            }
        })
        .then(response => {
            alert('✅ Cập nhật thành công!');
            window.location.href = '/admin/sinhvien';
        })
        .catch(error => {
            alert('❌ Lỗi khi cập nhật: ' + (error.response?.data?.message || error.message));
            console.error(error);
        });
    });
</script>
</body>
</html>
@endsection
