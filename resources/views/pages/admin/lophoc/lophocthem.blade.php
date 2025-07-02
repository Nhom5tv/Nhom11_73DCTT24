@extends('layout')

@section('title', 'Thêm lớp học')

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
        <h2>Thêm Lớp Học</h2>

        <div class="input-box">
            <select id="ma_mon" required>
                <option value="">-- Chọn mã môn --</option>
            </select>
        </div>

        <div class="input-box">
            <input type="text" id="hoc_ky" placeholder="VD: 2024-2025-HK1" required />
            <label for="hoc_ky">Học Kỳ</label>
        </div>

        <div class="input-box">
            <select id="ma_giang_vien" required>
                <option value="">-- Chọn giảng viên --</option>
            </select>
        </div>

        <div class="input-box">
            <input type="text" id="lich_hoc" placeholder="VD: Thứ 2, 3 tiết 1-3" />
            <label for="lich_hoc">Lịch Học</label>
        </div>

        <div class="input-box">
            <select id="trang_thai">
                <option value="">-- Chọn trạng thái --</option>
                <option value="Đang mở">Đang mở</option>
                <option value="Đã kết thúc">Đã kết thúc</option>
            </select>
        </div>

        <button class="btn" id="btnLuu" onclick="themLopHoc()">Lưu</button>

        <div class="quaylai">
            <a href="{{ url('admin/dslophoc') }}">Quay lại</a>
        </div>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/axios/dist/axios.min.js"></script>
<script>
    token = localStorage.getItem('token');

    // Tải dữ liệu mã môn học
    async function loadMaMonHoc() {
        try {
            const response = await axios.get('/api/admin/monhoc', {
                headers: { Authorization: `Bearer ${token}` }
            });
            const select = document.getElementById('ma_mon');
            response.data.forEach(mon => {
                const option = document.createElement('option');
                option.value = mon.ma_mon;
                option.textContent = `${mon.ma_mon} - ${mon.ten_mon}`;
                select.appendChild(option);
            });
        } catch (error) {
            console.error('Lỗi khi load mã môn:', error);
        }
    }

    // Tải dữ liệu mã giảng viên
    async function loadGiangVien() {
        try {
            const response = await axios.get('/api/admin/giangvien', {
                headers: { Authorization: `Bearer ${token}` }
            });
            const select = document.getElementById('ma_giang_vien');
            response.data.forEach(gv => {
                const option = document.createElement('option');
                option.value = gv.ma_giang_vien;
                option.textContent = `${gv.ma_giang_vien} - ${gv.ho_ten}`;
                select.appendChild(option);
            });
        } catch (error) {
            console.error('Lỗi khi load giảng viên:', error);
        }
    }

    // Gửi yêu cầu thêm lớp học
    async function themLopHoc() {
    const btn = document.getElementById('btnLuu');
    btn.disabled = true;
    btn.textContent = 'Đang lưu...';

    const data = {
        ma_mon: document.getElementById('ma_mon').value,
        hoc_ky: document.getElementById('hoc_ky').value,
        ma_giang_vien: document.getElementById('ma_giang_vien').value,
        lich_hoc: document.getElementById('lich_hoc').value,
        trang_thai: document.getElementById('trang_thai').value
    };

    try {
        await axios.post('/api/admin/dslophoc', data, {
            headers: { Authorization: `Bearer ${token}` }
        });
        alert('Thêm lớp học thành công!');
        window.location.href = '/admin/dslophoc';
    } catch (error) {
        if (error.response?.status === 422) {
            const errs = error.response.data.errors;
            alert("Lỗi nhập liệu:\n" + Object.entries(errs).map(([k, v]) => `${k}: ${v.join(', ')}`).join('\n'));
        } else {
            console.error(error);
            alert("Thêm lớp học thất bại.");
        }
        // Cho phép bấm lại nút nếu có lỗi
        btn.disabled = false;
        btn.textContent = 'Lưu';
    }
}

    document.addEventListener('DOMContentLoaded', () => {
        loadMaMonHoc();
        loadGiangVien();
    });
</script>
@endsection
