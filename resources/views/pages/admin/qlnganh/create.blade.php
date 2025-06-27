@extends('layout')

@section('content')
<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Thêm Ngành</title>
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
    <h1 style="text-align: center;">Thêm Ngành Mới</h1>

    <form id="createNganhForm">
        <div class="form-container">
            <div class="input-group">
                <label for="ten_nganh">Tên ngành</label>
                <input type="text" id="ten_nganh" required>
            </div>

           <div class="input-group">
    <label for="ma_khoa">Chọn khoa</label>
    <select id="ma_khoa" required>
        <option value="">-- Chọn khoa --</option>
        <!-- JS sẽ render option tại đây -->
    </select>
</div>


            <div class="input-group">
                <label for="thoi_gian_dao_tao">Thời gian đào tạo (năm)</label>
                <input type="number" id="thoi_gian_dao_tao" min="1" required>
            </div>

            <div class="input-group">
                <label for="bac_dao_tao">Bậc đào tạo</label>
                <select id="bac_dao_tao" required>
                    <option value="">-- Chọn bậc đào tạo --</option>
                    <option value="Đại học">Đại học</option>
                    <option value="Cao đẳng">Cao đẳng</option>
                    <option value="Thạc sĩ">Thạc sĩ</option>
                </select>
            </div>

            <button type="submit" class="btn">Lưu</button>

            <div class="quaylai">
                <a href="/admin/nganh">Quay lại</a>
            </div>
        </div>
    </form>
</main>

<script src="https://cdn.jsdelivr.net/npm/axios/dist/axios.min.js"></script>
<script>
    document.getElementById('createNganhForm').addEventListener('submit', function(e) {
        e.preventDefault();

        const token = localStorage.getItem('token');
        if (!token) {
            alert("Bạn chưa đăng nhập hoặc token không tồn tại!");
            return;
        }

        const formData = {
            ten_nganh: document.getElementById('ten_nganh').value,
            ma_khoa: document.getElementById('ma_khoa').value,
            thoi_gian_dao_tao: document.getElementById('thoi_gian_dao_tao').value,
            bac_dao_tao: document.getElementById('bac_dao_tao').value
        };

        axios.post('/api/admin/nganh', formData, {
            headers: { Authorization: `Bearer ${token}` }
        })
        .then(res => {
            alert('✅ Thêm ngành thành công!');
            window.location.href = '/admin/nganh';
        })
        .catch(err => {
            alert('❌ Lỗi: ' + (err.response?.data?.message || err.message));
            console.error(err);
        });
    });
    function loadDanhSachKhoa() {
    axios.get('/api/admin/khoa', {
        headers: { Authorization: `Bearer ${localStorage.getItem('token')}` }
    })
    .then(res => {
        const select = document.getElementById('ma_khoa');
        res.data.forEach(khoa => {
            const option = document.createElement('option');
            option.value = khoa.ma_khoa;
            option.textContent = khoa.ten_khoa;
            select.appendChild(option);
        });
    })
    .catch(err => {
        alert('❌ Không thể tải danh sách khoa');
        console.error(err);
    });
}

// Gọi khi trang load
window.onload = () => {
    loadDanhSachKhoa();
};

</script>

</body>
</html>
@endsection
