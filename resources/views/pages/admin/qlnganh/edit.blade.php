@extends('layout')

@section('content')
<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Sửa Thông Tin Ngành</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
    <link rel="stylesheet" href="/css/button.css?v={{ time() }}">
    <link rel="stylesheet" href="/css/styleDT.css?v={{ time() }}">
    <style>
        body { font-family: Arial, sans-serif; background-color: #f4f4f9; }
        .form-container {
            max-width: 700px; margin: auto; background-color: #fff;
            padding: 20px; border-radius: 10px; box-shadow: 0 2px 10px rgba(0,0,0,0.1);
        }
        .input-group {
            margin-bottom: 15px;
        }
        .input-group label {
            font-weight: bold; display: block; margin-bottom: 5px;
        }
        .input-group input, .input-group select {
            width: 100%; padding: 10px; font-size: 14px;
            border-radius: 5px; border: 1px solid #ccc;
        }
        .btn {
            width: 100%; padding: 12px; background-color: #007bff;
            color: white; font-size: 16px; border: none; border-radius: 5px;
            cursor: pointer;
        }
        .btn:hover { background-color: #0056b3; }
        .quaylai a {
            display: block; text-align: center; margin-top: 15px;
            background-color: #6c757d; color: #fff; padding: 10px;
            border-radius: 5px; text-decoration: none;
        }
        .quaylai a:hover { background-color: #5a6268; }
    </style>
</head>
<body>

<main>
    <h1 style="text-align: center;">Sửa Thông Tin Ngành</h1>

    <form id="editNganhForm">
        <div class="form-container">

            <div class="input-group">
                <label for="ma_nganh">Mã ngành</label>
                <input type="text" id="ma_nganh" value="{{ $nganh->ma_nganh }}" readonly>
            </div>

            <div class="input-group">
                <label for="ten_nganh">Tên ngành</label>
                <input type="text" id="ten_nganh" value="{{ $nganh->ten_nganh }}" required>
            </div>

            <div class="input-group">
    <label for="ma_khoa">Chọn khoa</label>
    <select id="ma_khoa" required>
        <option value="">-- Chọn khoa --</option>
        <!-- Option sẽ được render bằng JavaScript -->
    </select>
</div>


            <div class="input-group">
                <label for="thoi_gian_dao_tao">Thời gian đào tạo (năm)</label>
                <input type="number" id="thoi_gian_dao_tao" value="{{ $nganh->thoi_gian_dao_tao }}" required>
            </div>

            <div class="input-group">
                <label for="bac_dao_tao">Bậc đào tạo</label>
                <select id="bac_dao_tao" required>
                    <option value="">-- Chọn bậc --</option>
                    <option value="Đại học" {{ $nganh->bac_dao_tao == 'Đại học' ? 'selected' : '' }}>Đại học</option>
                    <option value="Cao đẳng" {{ $nganh->bac_dao_tao == 'Cao đẳng' ? 'selected' : '' }}>Cao đẳng</option>
                    <option value="Thạc sĩ" {{ $nganh->bac_dao_tao == 'Thạc sĩ' ? 'selected' : '' }}>Thạc sĩ</option>
                </select>
            </div>

            <button type="submit" class="btn">Cập nhật</button>

            <div class="quaylai">
                <a href="/admin/nganh">Quay lại</a>
            </div>

        </div>
    </form>
</main>
<script src="https://cdn.jsdelivr.net/npm/axios/dist/axios.min.js"></script>
<script>
    const token = localStorage.getItem('token');
    const currentMaKhoa = '{{ $nganh->ma_khoa }}';

    // Load danh sách khoa khi trang vừa load
    function loadDanhSachKhoa() {
        if (!token) {
            alert("Bạn chưa đăng nhập hoặc token không tồn tại!");
            return;
        }

        axios.get('/api/admin/dskhoa', {
            headers: { Authorization: `Bearer ${token}` }
        })
        .then(res => {
            const select = document.getElementById('ma_khoa');
            select.innerHTML = '<option value="">-- Chọn khoa --</option>'; // reset select

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
            alert('❌ Không thể tải danh sách khoa');
            console.error(err);
        });
    }

    // Gửi form cập nhật ngành
    document.getElementById('editNganhForm').addEventListener('submit', function(e) {
        e.preventDefault();

        if (!token) {
            alert("Bạn chưa đăng nhập hoặc token không tồn tại!");
            return;
        }

        const maNganh = document.getElementById('ma_nganh').value;

        const formData = {
            ten_nganh: document.getElementById('ten_nganh').value,
            ma_khoa: document.getElementById('ma_khoa').value,
            thoi_gian_dao_tao: document.getElementById('thoi_gian_dao_tao').value,
            bac_dao_tao: document.getElementById('bac_dao_tao').value
        };

        axios.put(`/api/admin/nganh/${maNganh}`, formData, {
            headers: { Authorization: `Bearer ${token}` }
        })
        .then(res => {
            alert('✅ Cập nhật ngành thành công!');
            window.location.href = '/admin/nganh';
        })
        .catch(err => {
            alert('❌ Lỗi khi cập nhật: ' + (err.response?.data?.message || err.message));
            console.error(err);
        });
    });

    // Chạy khi trang load
    window.onload = loadDanhSachKhoa;
</script>


</body>
</html>
@endsection
