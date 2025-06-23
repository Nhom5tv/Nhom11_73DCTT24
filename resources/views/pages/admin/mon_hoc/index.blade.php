@extends('layout')

@section('content')
{{-- resources/views/monhocV.blade.php --}}
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Quản lý môn học</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
    <link rel="stylesheet" href="/css/button.css?v={{ time() }}">
    <link rel="stylesheet" type="text/css" href="/css/styleDT.css?v={{ time() }}">
    <style>
        .btn_cn {
            display: flex;
            margin: 0;
        }
    </style>
</head>


<body>
    <form method="post" action="#">
        @csrf
    </form>
    <main class="table" id="customers_table">
        <section class="table__header">
            <h1>Quản lý môn học</h1>
            <div class="input-group">
                <form action="#" method="post">
                    @csrf
                    <input type="search" placeholder="Tên môn" name="txtTKTenMon" value="">
                </form>
            </div>
            <div class="input-group">
                <form action="#" method="post">
                    @csrf
                    <input type="search" placeholder="Mã môn" name="txtTKMaMon" value="">
                </form>
            </div>
            <button style="border: none; background: transparent;" type="submit" name="btnTimkiem">
                <i class="fa fa-search"></i>
            </button>

            <div class="Insert">
    <button class="button-85" type="button" onclick="window.location.href='/admin/monhoc/create'">Thêm môn học</button>
</div>


            <div class="Upload">
                <form action="#" method="post" enctype="multipart/form-data">
                    @csrf
                    <input type="file" name="txtFile">
                    <button class="button-85" role="button">Upload</button>
                </form>
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
                        <button style="width: 176px;" name="btnXuatExcel">
                            <label for="export-file" id="toEXCEL">EXCEL</label>
                        </button>
                    </form>
                </div>
            </div>
        </section>

        <section class="table__body">
            <table>
                <thead>
                    <tr>
                        <th>Mã môn <span class="icon-arrow">&UpArrow;</span></th>
                        <th>Tên môn <span class="icon-arrow">&UpArrow;</span></th>
                        <th>Mã ngành <span class="icon-arrow">&UpArrow;</span></th>
                        <th>Số tín chỉ <span class="icon-arrow">&UpArrow;</span></th>
                        <th>Số tiết <span class="icon-arrow">&UpArrow;</span></th>
                        <th style="padding-left:50px">Chức năng <span class="icon-arrow">&UpArrow;</span></th>
                    </tr>
                </thead>
                <tbody id="table-body">
                    {{-- Dữ liệu sẽ được load bằng Axios sau --}}
                </tbody>
            </table>
        </section>
    </main>
</body>
</html>
<script src="https://cdn.jsdelivr.net/npm/axios/dist/axios.min.js"></script>
<script>

function loadMonHoc() {
    axios.get('/api/admin/monhoc', {
        headers: {
            Authorization: 'Bearer ' + localStorage.getItem('token')
        }
    })
    .then(response => {
        const tbody = document.getElementById("table-body");
        tbody.innerHTML = "";

        response.data.forEach(mon => {
            let html = `<tr>
                <td>${mon.ma_mon}</td>
                <td>${mon.ten_mon}</td>
                <td>${mon.ma_nganh}</td>
                <td>${mon.so_tin_chi ?? ''}</td>
                <td>${mon.so_tiet ?? ''}</td>
                <td class="btn_cn">
                    <a href="/admin/monhoc/${mon.ma_mon}/edit">
                        <button class="button-85">Sửa</button>
                    </a>
                    <button class="button-85" onclick="deleteMonHoc('${mon.ma_mon}')">Xóa</button>
                </td>
            </tr>`;
            tbody.insertAdjacentHTML('beforeend', html);
        });
    })
    .catch(error => {
        alert("Lỗi tải dữ liệu: " + error);
        if (error.response && error.response.status === 401) {
            alert("Phiên đăng nhập hết hạn. Vui lòng đăng nhập lại.");
            localStorage.removeItem("access_token");
            window.location.href = "/login";
        }
    });
}

function editMonHoc(ma_mon) {
    // Tùy bạn xử lý, ví dụ chuyển đến form sửa:
    window.location.href = `/admin/monhoc/${ma_mon}/edit`;
}

function deleteMonHoc(ma_mon) {
    if (confirm("Bạn có chắc muốn xóa?")) {
        axios.defaults.headers.common['Authorization'] = 'Bearer ' + localStorage.getItem('token');

        axios.delete(`/api/admin/monhoc/${ma_mon}`)
       
            .then(() => {
                alert('Đã xóa thành công');
                location.reload(); // Tải lại dữ liệu
            })
            .catch(() => {
                alert('Xóa thất bại');
            });
    }
}
document.addEventListener("DOMContentLoaded", function () {
    loadMonHoc();
});
</script>
@endsection
