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
            <label for="ma_mon_hoc">Mã Môn Học</label>
            <select id="ma_mon_hoc" required>
                <option value="">-- Chọn mã môn --</option>
            </select>
        </div>

        <div class="input-box">
            <label>Số Lượng Tối Đa</label>
            <input type="number" id="so_luong_toi_da" />
        </div>

        <div class="input-box">
            <label>Lịch Học</label>
            <input type="text" id="lich_hoc" />
        </div>

        <div class="input-box">
            <label>Trạng Thái</label>
            <select id="trang_thai">
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
    const token = localStorage.getItem('token');

    // Load mã môn học
    async function loadMaMonHoc() {
        try {
            const response = await axios.get('/api/monhoc', {
                headers: { Authorization: `Bearer ${token}` }
            });
            const select = document.getElementById('ma_mon_hoc');
            response.data.forEach(mon => {
                const option = document.createElement('option');
                option.value = mon.ma_mon;
                option.textContent = mon.ma_mon;
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
            console.error('Lỗi khi thêm lịch học:', error);
            alert('Thêm lịch học thất bại');
        }
    }

    document.addEventListener('DOMContentLoaded', () => {
        loadMaMonHoc();
    });
</script>
@endsection
