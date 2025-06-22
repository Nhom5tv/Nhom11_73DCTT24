@extends('layout')

@section('title', 'Sửa Thông Tin Lịch Học')

@section('content')
<link rel="stylesheet" href="/css/dulieu.css?v={{ time() }}">

<main class="content">
    <div class="form-box login">
        <h2>Sửa Thông Tin Lịch Học</h2>
        <form method="POST" action="{{ url('admin/dslichhoc/' . $LichHoc->id_lich_hoc) }}">
            @csrf
            @method('PUT')

            <input type="hidden" name="txtidlichhoc" value="{{ $LichHoc->id_lich_hoc }}">

            <label>Mã Môn Học</label>
            <div class="input-box">
                <span class="icon">
                    <img src="/images/id-card.png" alt="" width="15px">
                </span>
                <input type="text" name="txtmamon" readonly value="{{ $LichHoc->ma_mon_hoc }}">
            </div>

            <label>Số Lượng Tối Đa</label>
            <div class="input-box">
                <span class="icon">
                    <img src="/images/id-card.png" alt="" width="15px">
                </span>
                <input type="text" name="txtmaxsoluong" readonly value="{{ $LichHoc->so_luong_toi_da }}">
            </div>

            <label>Lịch Học</label>
            <div class="input-box">
                <span class="icon">
                    <img src="/images/khoa.png" alt="" width="15px">
                </span>
                <input type="text" name="txtlichhoc" readonly value="{{ $LichHoc->lich_hoc }}">
            </div>

            <label>Trạng Thái</label>
            <div class="input-box">
                <select name="txttrangthai">
                    <option value="Đang Mở" {{ $LichHoc->trang_thai === 'Đang Mở' ? 'selected' : '' }}>Đang Mở</option>
                    <option value="Đóng" {{ $LichHoc->trang_thai === 'Đóng' ? 'selected' : '' }}>Đóng</option>
                </select>
            </div>

            <button type="submit" class="btn">Lưu</button>
            <br>
            <div class="quaylai">
                <a href="{{ url('admin/dslichhoc') }}">Quay lại</a>
            </div>
        </form>
    </div>
</main>
@endsection
