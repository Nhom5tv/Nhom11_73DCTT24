@extends('layout')

@section('content')
<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Sửa Thông Tin Giảng Viên</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
    <link rel="stylesheet" href="/css/button.css?v={{ time() }}">
    <link rel="stylesheet" href="/css/styleDT.css?v={{ time() }}">
    <style>
        body { font-family: Arial, sans-serif; padding: 20px; background-color: #f4f4f9; }
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
    <h1 style="text-align: center; color: #333;">Sửa Thông Tin Giảng Viên</h1>

    <form id="editGiangVienForm">
        <div class="form-container">

            <div class="input-group">
                <label for="ma_giang_vien">Mã Giảng Viên</label>
                <input type="text" id="ma_giang_vien" value="{{ $giangvien->ma_giang_vien }}" readonly>
            </div>

           
            <div class="input-group">
                <label for="ma_khoa">Chọn Khoa</label>
                <select id="ma_khoa" required>
                    <option value="">-- Chọn khoa --</option>
                    <!-- Option sẽ được render qua JavaScript -->
                </select>
            </div>

            <div class="input-group">
                <label for="ho_ten">Họ Tên</label>
                <input type="text" id="ho_ten" value="{{ $giangvien->ho_ten }}" required>
            </div>

            <div class="input-group">
                <label for="email">Email</label>
                <input type="email" id="email" value="{{ $giangvien->email }}" required>
            </div>

            <div class="input-group">
                <label for="so_dien_thoai">Số Điện Thoại</label>
                <input type="text" id="so_dien_thoai" value="{{ $giangvien->so_dien_thoai }}">
            </div>

            <div class="input-group">
                <label for="chuyen_nganh">Chuyên Ngành</label>
                <input type="text" id="chuyen_nganh" value="{{ $giangvien->chuyen_nganh }}">
            </div>

            <button type="submit" class="btn">Cập nhật</button>

            <div class="quaylai">
                <a href="/admin/giangvien">Quay lại</a>
            </div>
        </div>
    </form>
</main>

<script src="https://cdn.jsdelivr.net/npm/axios/dist/axios.min.js"></script>
<script>
    const token = localStorage.getItem('token');
    const currentMaKhoa = `{{ $giangvien->ma_khoa }}`;

    if (!token) {
        alert('❌ Bạn chưa đăng nhập hoặc thiếu token!');
    } else {
        loadDanhSachKhoa();
    }

    function loadDanhSachKhoa() {
        axios.get('/api/admin/khoa', {
            headers: { Authorization: `Bearer ${token}` }
        })
        .then(res => {
            const select = document.getElementById('ma_khoa');
            res.data.forEach(khoa => {
                const option = document.createElement('option');
                option.value = khoa.ma_khoa;
                option.textContent = khoa.ten_khoa;

                if (khoa.ma_khoa == currentMaKhoa) {
                    option.selected = true;
                }

                select.appendChild(option);
            });
        })
        .catch(err => {
            alert('❌ Lỗi tải danh sách khoa!');
            console.error(err);
        });
    }

    document.getElementById('editGiangVienForm').addEventListener('submit', function (e) {
        e.preventDefault();

        if (!token) {
            alert('Bạn chưa đăng nhập hoặc thiếu token');
            return;
        }

        const maGV = document.getElementById('ma_giang_vien').value;

        const formData = {
            ma_khoa: document.getElementById('ma_khoa').value,
            ho_ten: document.getElementById('ho_ten').value,
            email: document.getElementById('email').value,
            so_dien_thoai: document.getElementById('so_dien_thoai').value,
            chuyen_nganh: document.getElementById('chuyen_nganh').value
        };

        axios.put(`/api/admin/giangvien/${maGV}`, formData, {
            headers: {
                Authorization: `Bearer ${token}`
            }
        })
        .then(response => {
            alert('✅ Cập nhật giảng viên thành công!');
            window.location.href = '/admin/giangvien';
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
