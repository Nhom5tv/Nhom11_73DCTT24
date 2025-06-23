@extends('layout')

@section('content')
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
<link rel="stylesheet" href="/css/button.css?v={{ time() }}">
<link rel="stylesheet" href="/css/styleDT.css?v={{ time() }}">

<style>
    .btn_cn {
        display: flex;
        gap: 8px;
    }
</style>

<main class="table" id="customers_table">
    <section class="table__header">
        <h1>Quản lý tài khoản</h1>

        <div class="input-group">
            <input type="search" placeholder="Tên đăng nhập" id="filter-name">
        </div>

        <div class="input-group">
            <div style="position: relative;">
                <input type="text" id="txtTKQuyen" placeholder="Quyền" readonly>
                <select id="quyenDropdown" style="display: none; position: absolute; top: 100%; left: 0; width: 100%;">
                    <option value=""></option>
                    <option value="admin">Admin</option>
                    <option value="giang_vien">Giảng viên</option>
                    <option value="sinh_vien">Sinh viên</option>
                </select>
            </div>
        </div>

        <button style="border: none; background: transparent;" onclick="loadAccounts()">
            <i class="fa fa-search"></i>
        </button>

        <div class="Insert">
            <a href="/admin/taikhoan/create">
                <button class="button-85">Thêm tài khoản</button>
            </a>
        </div>

        {{-- <div class="Upload">
            <form action="{{ route('taikhoan.uploadExcel') }}" method="POST" enctype="multipart/form-data">
                @csrf
                <input type="file" name="txtFile">
                <button class="button-85" role="button">Upload</button>
            </form>
        </div> --}}

        {{-- <div class="export__file">
            <label for="export-file" class="export__file-btn" title="Export File">
                <img src="{{ asset('images/export.png') }}" alt="Export" width="20">
            </label>
            <input type="checkbox" id="export-file">
            <div class="export__file-options">
                <form action="{{ route('taikhoan.exportExcel') }}" method="POST">
                    @csrf
                    <button style="width: 176px;" name="btnXuatExcel">
                        <label for="export-file" id="toEXCEL">EXCEL</label>
                    </button>
                </form>
            </div>
        </div> --}}
    </section>

    <section class="table__body">
        <table>
            <thead>
                <tr>
                    <th> ID </th>
                    <th> Tên đăng nhập </th>
                    <th> Mật khẩu </th>
                    <th> Email </th>
                    <th> Quyền </th>
                    <th style="padding-left:50px"> Chức năng </th>
                </tr>
            </thead>
            <tbody id="account-table-body">
                {{-- JS sẽ đổ dữ liệu vào đây --}}
            </tbody>
        </table>
    </section>
</main>

<script src="https://cdn.jsdelivr.net/npm/axios/dist/axios.min.js"></script>
<script>
    function loadAccounts() {
        let nameFilter = document.getElementById("filter-name").value;
        let roleFilter = document.getElementById("txtTKQuyen").value;

        axios.get('/api/admin/taikhoan', {
            // xác thực token
              headers: {
                Authorization: 'Bearer ' + localStorage.getItem('token')
            },
            params: {
                name: nameFilter,
                role: roleFilter
            }
        })
        .then(response => {
            const tbody = document.getElementById("account-table-body");
            tbody.innerHTML = "";

            response.data.forEach(row => {
                let html = `<tr>
                    <td>${row.id}</td>
                    <td>${row.name}</td>  
                    <td>\u2022\u2022\u2022\u2022\u2022\u2022</td>  
                    <td>${row.email}</td>
                    <td>${row.role}</td>
                    <td class="btn_cn">`;

                if (row.role === 'giaovien' || row.role === 'sinhvien') {
                    html += `
                        <a href="/admin/taikhoan/${row.id}/edit">
                            <button class="button-85">Sửa</button>
                        </a>
                        <button class="button-85" onclick="xoaTaiKhoan(${row.id})">Xóa</button>`;
                }

                html += `</td></tr>`;
                tbody.insertAdjacentHTML('beforeend', html);
            });
        })
        .catch(error => {
            alert("Lỗi tải dữ liệu: " + error);
        });
    }

    function xoaTaiKhoan(id) {
        if (confirm("Bạn có chắc muốn xóa?")) {
            axios.delete(`/api/admin/taikhoan/${id}`, {
                headers: {
                    Authorization: 'Bearer ' + localStorage.getItem('token')
                }
            })
                .then(() => loadAccounts())
                .catch(error => alert("Xóa thất bại: " + error));
        }
    }

    // Dropdown quyền
    document.getElementById("txtTKQuyen").addEventListener("click", () => {
        document.getElementById("quyenDropdown").style.display = "block";
    });

    document.getElementById("quyenDropdown").addEventListener("change", () => {
        document.getElementById("txtTKQuyen").value = event.target.value;
        document.getElementById("quyenDropdown").style.display = "none";
    });

    document.addEventListener("click", (event) => {
        const input = document.getElementById("txtTKQuyen");
        const dropdown = document.getElementById("quyenDropdown");
        if (!input.contains(event.target) && !dropdown.contains(event.target)) {
            dropdown.style.display = "none";
        }
    });

    // Tải dữ liệu ban đầu
    document.addEventListener("DOMContentLoaded", loadAccounts);
</script>
@endsection
