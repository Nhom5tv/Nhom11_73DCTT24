@extends('layout')

@section('content')
    <link rel="stylesheet" href="/css/styleDT.css?v={{ time() }}">
    <link rel="stylesheet" href="/css/button.css?v={{ time() }}">
    <style>
        .btn_cn {
            display: flex;
            margin: 0;
        }
    </style>

    <main class="table" id="customers_table">
        <section class="table__header">
            <h1>Quản lý khoản thu</h1>

            <div class="input-group">
                <input type="search" id="searchTenKhoanThu" placeholder="Tên khoản thu">
            </div>
            <div class="">
                <label for="fromDate">Từ ngày:</label>
                <input type="date" id="fromDate">
            </div>
            <div class="">
                <label for="toDate">Đến ngày:</label>
                <input type="date" id="toDate">
            </div>
            <button id="btnTimKiem" style="border: none; background: transparent;"><i class="fa fa-search"></i></button>

            <div class="Insert">
                <a href="/admin/khoanthu/create">
                    <button class="button-85" role="button">Thêm khoản thu</button>
                </a>
            </div>
        </section>

        <section class="table__body">
            <table>
                <thead>
                    <tr>
                        <th>Mã khoản thu</th>
                        <th>Tên khoản thu</th>
                        <th>Loại khoản thu</th>
                        <th>Số tiền</th>
                        <th>Ngày tạo</th>
                        <th>Hạn nộp</th>
                        <th style="padding-left:50px">Chức năng</th>
                    </tr>
                </thead>
                <tbody id="khoanThuTableBody">
                    <!-- Dữ liệu sẽ được render bằng JS -->
                </tbody>
            </table>
        </section>
    </main>

    <script src="https://cdn.jsdelivr.net/npm/axios/dist/axios.min.js"></script>
    <script>


        document.getElementById('btnTimKiem').addEventListener('click', function () {
            const ten = document.getElementById('searchTenKhoanThu').value;
            const from = document.getElementById('fromDate').value;
            const to = document.getElementById('toDate').value;

            axios.get('/api/admin/khoan-thu', {
                params: {
                    ten_khoan_thu: ten,
                    from_date: from,
                    to_date: to
                },
                headers: {
                     Authorization: 'Bearer ' + localStorage.getItem('token')
                }
            })
                .then(res => {
                    renderTable(res.data);
                })
                .catch(err => {
                    console.error("Lỗi tìm kiếm:", err);
                    alert("Không thể tìm dữ liệu.");
                });
        });


        function renderTable(data) {
            const tbody = document.querySelector('tbody');
            tbody.innerHTML = '';

            if (data.length === 0) {
                tbody.innerHTML = "<tr><td colspan='7'>Không có dữ liệu</td></tr>";
                return;
            }

            data.forEach(item => {
                tbody.innerHTML += `
                    <tr>
                        <td>${item.ma_khoan_thu}</td>
                        <td>${item.ten_khoan_thu}</td>
                        <td>${item.loai_khoan_thu}</td>
                        <td>${item.so_tien}</td>
                        <td>${item.ngay_tao}</td>
                        <td>${item.han_nop}</td>
                        <td class="btn_cn">
                            <a href="/admin/khoanthu/${item.ma_khoan_thu}/edit">
                                <button class="button-85">Sửa</button>
                            </a>
                            <button class="button-85" onclick="xoa(${item.ma_khoan_thu})">Xóa</button>
                        </td>
                    </tr>
                `;
            });
        }

        function xoa(id) {
            if (confirm("Bạn có chắc muốn xóa?")) {
                axios.delete(`/api/admin/khoanthu/${id}`, {
                    headers: {
                        Authorization: 'Bearer ' + localStorage.getItem('token')
                    }
                })
                    .then(() => {
                        alert("Xóa thành công");
                        document.getElementById('btnTimKiem').click(); // Load lại
                    })
                    .catch(err => {
                        alert("Xóa thất bại");
                        console.error(err);
                    });
            }
        }
    </script>

@endsection