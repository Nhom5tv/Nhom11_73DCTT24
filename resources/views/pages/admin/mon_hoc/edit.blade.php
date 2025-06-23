@extends('layout')

@section('content')

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Sửa Môn Học</title>
    
    <link rel="stylesheet" href="/css/dulieu.css?v={{ time() }}">
</head>
<div class="content">
    <div class="form-box login">
        <h2>Sửa Môn Học</h2>
        <form id="editForm">
            @csrf
            <input type="hidden" id="ma_mon" name="ma_mon">

            <div class="input-box">
                <span class="icon">
                    <img src="{{ asset('Picture/Pic_login/email.png') }}" alt="" width="15px">
                </span>
                <input type="text" id="ten_mon" name="ten_mon" required>
                <label>Tên Môn Học</label>
            </div>

            <div class="input-box">
                <span class="icon">
                    <img src="{{ asset('Picture/Pic_login/email.png') }}" alt="" width="15px">

                </span>
                <input type="number" id="ma_nganh" name="ma_nganh" required>
                <label>Mã ngành</label>
            </div>

            <div class="input-box">
                <span class="icon">
                    <img src="{{ asset('Picture/Pic_login/email.png') }}" alt="" width="15px">

                </span>
                <input type="number" id="so_tin_chi" name="so_tin_chi" required>
                <label>Số Tín Chỉ</label>
            </div>

            <div class="input-box">
                <span class="icon">
                    <img src="{{ asset('Picture/Pic_login/email.png') }}" alt="" width="15px">

                </span>
                <input type="number" id="so_tiet" name="so_tiet" required>
                <label>Số Tiết</label>
            </div>

            <button type="submit" class="btn">Lưu</button>
        </form>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/axios/dist/axios.min.js"></script>
<script>
   
    const maMon = window.location.pathname.split('/')[3];

    document.addEventListener('DOMContentLoaded', function () {
        axios.get(`/api/admin/monhoc/${maMon}`,{
            headers: {
            Authorization: 'Bearer ' + localStorage.getItem('token')
        }})
            .then(response => {
                const mon = response.data;
                document.getElementById('ma_mon').value = mon.ma_mon;
                document.getElementById('ten_mon').value = mon.ten_mon;
                document.getElementById('ma_nganh').value = mon.ma_nganh;
                document.getElementById('so_tin_chi').value = mon.so_tin_chi;
                document.getElementById('so_tiet').value = mon.so_tiet;
            })
            .catch(error => {
                alert("Không tìm thấy môn học");
                console.error(error);
            });
    });

    document.getElementById('editForm').addEventListener('submit', function (e) {
        e.preventDefault();

        const data = {
            ten_mon: document.getElementById('ten_mon').value,
            ma_nganh: document.getElementById('ma_nganh').value,
            so_tin_chi: document.getElementById('so_tin_chi').value,
            so_tiet: document.getElementById('so_tiet').value,
        };
        axios.put(`/api/admin/monhoc/${maMon}`, data, {
            headers: {
            Authorization: 'Bearer ' + localStorage.getItem('token')
        }
        })
        
            .then(response => {
                alert("Cập nhật thành công!");
                window.location.href = "/admin/monhoc";
            })
            .catch(error => {
                alert("Cập nhật thất bại!");
                console.error(error);
            });
    });
</script>
@endsection