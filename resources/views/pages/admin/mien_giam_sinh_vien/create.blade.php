@extends('layout')

@section('content')
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Thêm Miễn Giảm</title>
    <style>
        .quaylai {
            text-align: center;
            justify-content: center;
            padding-top: 5px;
        }
    </style>
    <link rel="stylesheet" href="/css/dulieu.css?v={{ time() }}">
</head>

<div class="content">
    <div class="form-box login">
        <h2>Thêm Miễn Giảm</h2>
        <form id="addForm">
            @csrf

            <div class="input-box">
                <span class="icon">
                    <img src="/Public/Picture/Pic_login/user.png" alt="" width="15px">
                </span>
                <input type="text" id="ma_sinh_vien" name="ma_sinh_vien" required style="text-align: center;">
                <label>Mã Sinh Viên</label>
            </div>

            <div class="input-box">
                <span class="icon">
                    <img src="/Public/Picture/Pic_login/soTien.png" alt="" width="15px">
                </span>
                <input type="number" step="0.01" id="muc_tien" name="muc_tien" required style="text-align: center;">
                <label>Mức Tiền (0-100%)</label>
            </div>

            <label>Loại Miễn Giảm</label>
            <div class="input-box">
                <span class="icon">
                    <img src="/Public/Picture/Pic_login/category.png" alt="" width="15px">
                </span>
                <select id="loai_mien_giam" name="loai_mien_giam" required style="text-align: center;">
                    <option value="">Chọn loại miễn giảm</option>
                    <option value="BHYT">BHYT</option>
                    <option value="Học phí">HP</option>
                    {{-- Dữ liệu được đổ từ JS --}}
                </select>
            </div>

            <div class="input-box">
                <span class="icon">
                    <img src="/Public/Picture/Pic_login/category.png" alt="" width="15px">
                </span>
                <input type="text" id="ghi_chu" name="ghi_chu" required style="text-align: center;">
                <label>Ghi chú</label>
            </div>

            <div>
                <button type="submit" class="btn">Lưu</button>
            </div>

            <div class="quaylai">
                <a href="/admin/miengiam">Quay lại</a>
            </div>
        </form>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/axios/dist/axios.min.js"></script>
<script>
    // Load danh sách loại miễn giảm từ API
    document.addEventListener("DOMContentLoaded", function () {
        axios.get('/api/admin/khoanthu', {
            headers: {
                Authorization: 'Bearer ' + localStorage.getItem('token')
            }
        }).then(response => {
            const select = document.getElementById('loai_mien_giam');
            response.data.forEach(item => {
                const opt = document.createElement("option");
                opt.value = item.loai_khoan_thu;
                opt.textContent = item.loai_khoan_thu;
                select.appendChild(opt);
            });
        }).catch(error => {
            alert("Không tải được loại miễn giảm");
        });
    });

    // Gửi form thêm mới miễn giảm
    document.getElementById('addForm').addEventListener('submit', function (e) {
        e.preventDefault();

        const data = {
            ma_sinh_vien: document.getElementById('ma_sinh_vien').value,
            muc_tien: document.getElementById('muc_tien').value,
            loai_mien_giam: document.getElementById('loai_mien_giam').value,
            ghi_chu: document.getElementById('ghi_chu').value
        };

        axios.post('/api/admin/miengiam', data, {
            headers: {
                Authorization: 'Bearer ' + localStorage.getItem('token')
            }
        })
        .then(response => {
            alert("Thêm miễn giảm thành công!");
            window.location.href = "/admin/miengiam";
        })
        .catch(error => {
            alert("Thêm thất bại!");
            console.error(error);
        });
    });
</script>
@endsection