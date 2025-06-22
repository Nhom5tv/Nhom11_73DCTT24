@extends('layout')

@section('title', 'Sửa Thông Tin Lịch Học')

@section('content')
<link rel="stylesheet" href="/css/dulieu.css?v={{ time() }}">

<main class="content">
    <div class="form-box login">
        <h2>Sửa Thông Tin Lịch Học</h2>

        <!-- KHÔNG dùng form nếu gọi API bằng Axios -->
        <input type="hidden" id="id_lich_hoc" value="{{ $LichHoc->id_lich_hoc }}">

        <label>Mã Môn Học</label>
        <div class="input-box">
            <input type="text" id="txtmamon" readonly value="{{ $LichHoc->ma_mon_hoc }}">
        </div>

        <label>Số Lượng Tối Đa</label>
        <div class="input-box">
            <input type="text" id="txtmaxsoluong" readonly value="{{ $LichHoc->so_luong_toi_da }}">
        </div>

        <label>Lịch Học</label>
        <div class="input-box">
            <input type="text" id="txtlichhoc" readonly value="{{ $LichHoc->lich_hoc }}">
        </div>

        <label>Trạng Thái</label>
        <div class="input-box">
            <select id="trang_thai">
                <option value="Đang Mở" {{ $LichHoc->trang_thai === 'Đang Mở' ? 'selected' : '' }}>Đang Mở</option>
                <option value="Đóng" {{ $LichHoc->trang_thai === 'Đóng' ? 'selected' : '' }}>Đóng</option>
            </select>
        </div>

        <button type="button" class="btn" onclick="capNhatLichHoc()">Lưu</button>

        <div class="quaylai" style="padding-top:10px">
            <a href="{{ url('admin/dslichhoc') }}">Quay lại</a>
        </div>
    </div>
</main>

<script src="https://cdn.jsdelivr.net/npm/axios/dist/axios.min.js"></script>
<script>
    async function capNhatLichHoc() {
        const token = localStorage.getItem('token');
        const id = document.getElementById('id_lich_hoc').value;
        const trang_thai = document.getElementById('trang_thai').value;

        try {
            const response = await axios.put(`/api/admin/dslichhoc/${id}`, {
                trang_thai: trang_thai
            }, {
                headers: {
                    Authorization: `Bearer ${token}`
                }
            });

            alert('Cập nhật lịch học thành công!');
            window.location.href = '/admin/dslichhoc';
        } catch (error) {
            console.error('Lỗi cập nhật:', error);

            if (error.response?.data?.errors) {
                let msg = '';
                Object.values(error.response.data.errors).forEach(errArr => {
                    msg += errArr.join('\n');
                });
                alert('Lỗi:\n' + msg);
            } else if (error.response?.data?.message) {
                alert("Lỗi: " + error.response.data.message);
            } else {
                alert('Cập nhật thất bại.');
            }
        }
    }
</script>
@endsection
