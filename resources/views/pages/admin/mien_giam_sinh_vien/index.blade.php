@extends('layout')

@section('content')
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Quản lý miễn giảm sinh viên</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
    <link rel="stylesheet" href="/css/button.css?v={{ time() }}">
    <link rel="stylesheet" href="/css/styleDT.css?v={{ time() }}">
    <style>
        .btn_cn { display: flex; margin: 0; }
    </style>
</head>

<body>
    <main class="table" id="customers_table">
        <section class="table__header">
            <h1>Miễn giảm sinh viên</h1>

            <div class="input-group">
                <input type="search" id="txtTKMaSV" placeholder="Mã sinh viên">
            </div>
            <div class="input-group">
                <input type="search" id="txtTKLoaiMG" placeholder="Loại miễn giảm">
            </div>
            <button id="btnTimkiem" style="border: none; background: transparent;">
                <i class="fa fa-search"></i>
            </button>

            <div class="Insert">
                <button class="button-85" type="button" onclick="window.location.href='/admin/miengiam/create'">Thêm miễn giảm</button>
            </div>

            <div class="Upload">
                <input type="file" name="txtFile">
                <button class="button-85">Upload</button>
            </div>

            <div class="export__file">
                <label for="export-file" class="export__file-btn" title="Export File">
                    <img src="{{ asset('Public/Picture/export.png') }}" alt="" width="20">
                </label>
                <input type="checkbox" id="export-file">
                <div class="export__file-options">
                    <label>Export As &nbsp; &#10140;</label>
                    <form action="#" method="post">
                        @csrf
                        <button style="width: 176px;"><label for="export-file" id="toEXCEL">EXCEL</label></button>
                    </form>
                </div>
            </div>
        </section>

        <section class="table__body">
            <table>
                <thead>
                    <tr>
                        <th>Mã miễn giảm</th>
                        <th>Mã sinh viên</th>
                        <th>Mức tiền</th>
                        <th>Loại miễn giảm</th>
                        <th>Ghi chú</th>
                        <th style="padding-left:50px">Chức năng</th>
                    </tr>
                </thead>
                <tbody id="miengiam-body">
                    {{-- Dữ liệu sẽ được axios render --}}
                </tbody>
            </table>
        </section>
    </main>
</body>

<script src="https://cdn.jsdelivr.net/npm/axios/dist/axios.min.js"></script>
<script>
function loadMienGiam(maSV = '', loaiMG = '') {
    axios.get('/api/admin/miengiam', {
        headers: {
            Authorization: 'Bearer ' + localStorage.getItem('token')
        }
    })
    .then(response => {
        const tbody = document.getElementById("miengiam-body");
        tbody.innerHTML = "";

        response.data.forEach(item => {
            if ((maSV && !item.ma_sinh_vien.includes(maSV)) || 
                (loaiMG && !item.loai_mien_giam.includes(loaiMG))) return;

            let html = `<tr>
                <td>${item.ma_mien_giam}</td>
                <td>${item.ma_sinh_vien}</td>
                <td>${item.muc_tien}</td>
                <td>${item.loai_mien_giam ?? ''}</td>
                <td>${item.ghi_chu ?? ''}</td>
                <td class="btn_cn">
                    <a href="/admin/miengiam/${item.ma_mien_giam}/edit"><button class="button-85">Sửa</button></a>
                    <button class="button-85" onclick="deleteMienGiam(${item.ma_mien_giam})">Xóa</button>
                </td>
            </tr>`;
            tbody.insertAdjacentHTML('beforeend', html);
        });
    })
    .catch(error => {
        alert("Lỗi tải dữ liệu: " + error);
        if (error.response && error.response.status === 401) {
            alert("Phiên đăng nhập hết hạn. Vui lòng đăng nhập lại.");
            localStorage.removeItem("token");
            window.location.href = "/login";
        }
    });
}

function deleteMienGiam(ma_mien_giam) {
    if (confirm("Bạn có chắc muốn xóa?")) {
        axios.delete(`/api/admin/miengiam/${ma_mien_giam}`, {
            headers: {
                Authorization: 'Bearer ' + localStorage.getItem('token')
            }
        })
        .then(() => {
            alert('Đã xóa thành công');
            loadMienGiam();
        })
        .catch(() => {
            alert('Xóa thất bại');
        });
    }
}

document.getElementById("btnTimkiem").addEventListener("click", function () {
    const maSV = document.getElementById("txtTKMaSV").value;
    const loaiMG = document.getElementById("txtTKLoaiMG").value;
    loadMienGiam(maSV, loaiMG);
});

document.addEventListener("DOMContentLoaded", function () {
    loadMienGiam();
});
</script>
@endsection