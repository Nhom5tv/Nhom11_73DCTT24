@extends('layout')

@section('title', 'Thêm lịch học')

@section('content')
<link rel="stylesheet" href="/css/dulieu.css?v={{ time() }}">
<style>
    .quaylai {
        text-align: center;
        justify-content: center;
        padding-top: 5px;
    }
</style>

<div class="content">
    <div class="form-box login">
        <h2>Thêm Lịch Học</h2>

        <div class="input-box">
            <select id="ma_mon_hoc" required>
                <option value="">-- Chọn mã môn --</option>
            </select>
        </div>

        <div class="input-box">
            <input type="number" id="so_luong_toi_da" />
            <label>Số Lượng Tối Đa</label>
        </div>

        <div class="input-box">
            <input type="text" id="lich_hoc" />
            <label>Lịch Học</label>
        </div>

        <div class="input-box">
            <select id="trang_thai">
                <option value="Chọn Trạng Thái">--Chọn Trạng Thái--</option>
                <option value="Đang Mở">Đang Mở</option>
                <option value="Đóng">Đóng</option>
            </select>
        </div>

        <button class="btn" onclick="themLichHoc()">Lưu</button>

        <div class="quaylai">
            <a href="{{ url('admin/dslichhoc') }}">Quay lại</a>
        </div>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/axios/dist/axios.min.js"></script>
<script>
    token = localStorage.getItem('token');

    // Load mã môn học
    async function loadMaMonHoc() {
    try {
        const response = await axios.get('/api/admin/monhoc', {
            headers: { Authorization: `Bearer ${token}` }
        });
        console.log("Dữ liệu nhận được:", response.data); // kiểm tra thử
        const select = document.getElementById('ma_mon_hoc');
        response.data.forEach(mon => {
            const option = document.createElement('option');
            option.value = mon.ma_mon;
            option.textContent = mon.ma_mon + ' - ' + mon.ten_mon;
            select.appendChild(option);
        });
    } catch (error) {
        console.error("Lỗi khi load mã môn:", error);
    }
}

    // Thêm lịch học
    async function themLichHoc() {
        const data = {
            ma_mon_hoc: document.getElementById('ma_mon_hoc').value,
            so_luong_toi_da: document.getElementById('so_luong_toi_da').value,
            lich_hoc: document.getElementById('lich_hoc').value,
            trang_thai: document.getElementById('trang_thai').value,
            so_luong: 0
        };

        try {
            await axios.post('/api/admin/dslichhoc', data, {
                headers: { Authorization: `Bearer ${token}` }
            });
            alert('Thêm lịch học thành công');
            window.location.href = '/admin/dslichhoc';
        } catch (error) {
    if (error.response && error.response.status === 422) {
        // 👉 In chi tiết lỗi từ Laravel
        console.error('Lỗi xác thực:', error.response.data.errors);
        alert("Lỗi nhập liệu:\n" +
            Object.entries(error.response.data.errors)
                .map(([field, messages]) => `${field}: ${messages.join(', ')}`)
                .join('\n'));
    } else {
        console.error('Lỗi khác:', error);
        alert("Có lỗi xảy ra khi gửi dữ liệu.");
    }
}

    }

    document.addEventListener('DOMContentLoaded', () => {
        loadMaMonHoc();
    });
</script>
@endsection
