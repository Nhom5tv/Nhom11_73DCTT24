@extends('layout')
@section('title', 'Sửa điểm sinh viên')

@section('content')
<link rel="stylesheet" href="/css/button.css?v={{ time() }}">
<link rel="stylesheet" href="/css/styleForm.css?v={{ time() }}">

<div class="form-container">
    <h2>Sửa điểm sinh viên</h2>
    
    <form id="editDiemForm" class="diem-form">
        @csrf
        <input type="hidden" id="id" value="{{ $diem->id }}">
        <input type="hidden" id="ma_lop" value="{{ request()->query('class_id') }}">

        <div class="form-group">
            <label for="ma_sinh_vien">Mã sinh viên:</label>
            <input type="text" id="ma_sinh_vien" value="{{ $diem->ma_sinh_vien }}" readonly>
        </div>

        <div class="form-group">
            <label for="ho_ten">Họ tên:</label>
            <input type="text" id="ho_ten" value="{{ $diem->ho_ten }}" readonly>
        </div>

        <div class="form-group">
            <label for="diem_chuyen_can">Điểm chuyên cần:</label>
            <input type="number" id="diem_chuyen_can" value="{{ $diem->diem_chuyen_can }}" min="0" max="10" step="0.1">
        </div>

        <div class="form-group">
            <label for="diem_giua_ky">Điểm giữa kỳ:</label>
            <input type="number" id="diem_giua_ky" value="{{ $diem->diem_giua_ky }}" min="0" max="10" step="0.1">
        </div>

        <div class="form-group">
            <label for="diem_cuoi_ky">Điểm cuối kỳ:</label>
            <input type="number" id="diem_cuoi_ky" value="{{ $diem->diem_cuoi_ky }}" min="0" max="10" step="0.1">
        </div>

        <div class="form-actions">
            <button type="button" class="button-85" onclick="updateDiem()">Cập nhật</button>
            <button type="button" class="button-85" onclick="window.history.back()">Quay lại</button>
        </div>
    </form>
</div>

<script src="https://cdn.jsdelivr.net/npm/axios/dist/axios.min.js"></script>
<script>
    axios.defaults.headers.common['Authorization'] = `Bearer ${localStorage.getItem('token')}`;

    function updateDiem() {
        const ma_sinh_vien = document.getElementById('ma_sinh_vien').value;
        const ma_lop = document.getElementById('ma_lop').value;
        const diemData = {
            ma_lop: ma_lop,
            diem_chuyen_can: parseFloat(document.getElementById('diem_chuyen_can').value),
            diem_giua_ky: parseFloat(document.getElementById('diem_giua_ky').value),
            diem_cuoi_ky: parseFloat(document.getElementById('diem_cuoi_ky').value)
        };

        // Validate dữ liệu
        if (isNaN(diemData.diem_chuyen_can)) diemData.diem_chuyen_can = 0;
        if (isNaN(diemData.diem_giua_ky)) diemData.diem_giua_ky = 0;
        if (isNaN(diemData.diem_cuoi_ky)) diemData.diem_cuoi_ky = 0;

        axios.put(`/api/giaovien/diem-theo-lop/${ma_sinh_vien}`, diemData)
            .then(response => {
                alert(response.data.message);
                window.location.href = `/giaovien/diem-theo-lop/${ma_lop}`;
            })
            .catch(error => {
                console.error('Lỗi khi cập nhật điểm:', error);
                let errorMessage = error.response?.data?.message || 'Có lỗi xảy ra khi cập nhật điểm';
                alert(errorMessage);
            });
    }

    document.addEventListener('DOMContentLoaded', function() {
        document.getElementById('diem_chuyen_can').focus();
    });
</script>

<style>
    .form-container {
        max-width: 600px;
        margin: 20px auto;
        padding: 20px;
        background: #fff;
        border-radius: 8px;
        box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
    }

    .diem-form {
        display: flex;
        flex-direction: column;
        gap: 15px;
    }

    .form-group {
        display: flex;
        flex-direction: column;
    }

    .form-group label {
        margin-bottom: 5px;
        font-weight: bold;
    }

    .form-group input {
        padding: 8px;
        border: 1px solid #ddd;
        border-radius: 4px;
        font-size: 16px;
    }

    .form-group input[readonly] {
        background-color: #f5f5f5;
    }

    .form-actions {
        display: flex;
        justify-content: flex-end;
        gap: 10px;
        margin-top: 20px;
    }

    h2 {
        text-align: center;
        color: #333;
        margin-bottom: 20px;
    }
</style>
@endsection