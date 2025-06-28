@extends('layout')

@section('content')
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Sửa Miễn Giảm</title>
    <link rel="stylesheet" href="/css/dulieu.css?v={{ time() }}">
</head>

<div class="content">
    <div class="form-box login">
        <h2>Sửa Miễn Giảm</h2>
        <form id="editForm">
            @csrf
            <input type="hidden" id="ma_mien_giam" name="ma_mien_giam">

            <div class="input-box">
                <span class="icon">
                    <img src="{{ asset('Picture/Pic_login/user.png') }}" alt="" width="15px">
                </span>
                <input type="text" id="ma_sinh_vien" name="ma_sinh_vien" required>
                <label>Mã Sinh Viên</label>
            </div>

            <div class="input-box">
                <span class="icon">
                    <img src="{{ asset('Picture/Pic_login/soTien.png') }}" alt="" width="15px">
                </span>
                <input type="number" step="1" id="muc_tien" name="muc_tien" required>
                <label>Mức Tiền(0-100%)</label>
            </div>

            <label>Loại Miễn Giảm</label>
            <div class="input-box">
                <span class="icon">
                    <img src="{{ asset('Picture/Pic_login/category.png') }}" alt="" width="15px">
                </span>
                <select id="loai_mien_giam" name="loai_mien_giam" required style="text-align: center;">
                    <option value="">Chọn loại miễn giảm</option>
                   
                    {{-- Dữ liệu sẽ được đổ bằng JS --}}
                </select>
            </div>

            <div class="input-box">
                <span class="icon">
                    <img src="{{ asset('Picture/Pic_login/category.png') }}" alt="" width="15px">
                </span>
                <input type="text" id="ghi_chu" name="ghi_chu" required>
                <label>Ghi chú</label>
            </div>

            <button type="submit" class="btn">Lưu</button>
        </form>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/axios/dist/axios.min.js"></script>
<script>
    const id = window.location.pathname.split('/')[3];

    document.addEventListener('DOMContentLoaded', function () {
        // Gọi API lấy dữ liệu miễn giảm
        axios.get(`/api/admin/miengiam/${id}`, {
            headers: {
                Authorization: 'Bearer ' + localStorage.getItem('token')
            }
        })
        .then(response => {
            const data = response.data;
            document.getElementById('ma_mien_giam').value = data.ma_mien_giam;
            document.getElementById('ma_sinh_vien').value = data.ma_sinh_vien;
            document.getElementById('muc_tien').value = data.muc_tien;
            document.getElementById('ghi_chu').value = data.ghi_chu || '';

            // Load danh sách loại miễn giảm
            axios.get('/api/admin/khoanthu', {
                headers: {
                    Authorization: 'Bearer ' + localStorage.getItem('token')
                }
            }).then(res => {
                const select = document.getElementById('loai_mien_giam');
                res.data.forEach(item => {
                    const opt = document.createElement("option");
                    opt.value = item.loai_khoan_thu;
                    opt.textContent = item.loai_khoan_thu;
                    if (item.loai_khoan_thu === data.loai_mien_giam) {
                        opt.selected = true;
                    }
                    select.appendChild(opt);
                });
            });
        })
        .catch(error => {
            alert("Không tìm thấy miễn giảm");
        });
    });

    // Cập nhật miễn giảm
    document.getElementById('editForm').addEventListener('submit', function (e) {
        e.preventDefault();
        const data = {
            ma_sinh_vien: document.getElementById('ma_sinh_vien').value,
            muc_tien: document.getElementById('muc_tien').value,
            loai_mien_giam: document.getElementById('loai_mien_giam').value,
            ghi_chu: document.getElementById('ghi_chu').value,
        };

        axios.put(`/api/admin/miengiam/${id}`, data, {
            headers: {
                Authorization: 'Bearer ' + localStorage.getItem('token')
            }
        })
        .then(res => {
            alert("Cập nhật thành công");
            window.location.href = "/admin/miengiam";
        })
        .catch(err => {
            alert("Cập nhật thất bại");
            console.error(err);
        });
    });
</script>

@endsection