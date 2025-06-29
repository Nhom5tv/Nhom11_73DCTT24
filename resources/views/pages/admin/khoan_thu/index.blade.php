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
             <div class="export__file">
                <button class="button-85" style="margin-left: 10px;" onclick="exportExcel()">
                    <i class="fa fa-download"></i> Export
                </button>
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
    <script src="https://cdnjs.cloudflare.com/ajax/libs/xlsx/0.18.5/xlsx.full.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/axios/dist/axios.min.js"></script>
<script>
    // Load dữ liệu khi trang vừa mở
    document.addEventListener("DOMContentLoaded", function () {
        loadData();
    });

    // Sự kiện nút "Tìm kiếm"
    document.getElementById('btnTimKiem').addEventListener('click', function () {
        loadData();
    });

    // Hàm gọi API để lấy danh sách khoản thu
    function loadData() {
        const ten = document.getElementById('searchTenKhoanThu').value;
        const from = document.getElementById('fromDate').value;
        const to = document.getElementById('toDate').value;

        axios.get('/api/admin/khoanthu', {
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
            console.error("Lỗi khi tải danh sách:", err);
            alert("Không thể tải dữ liệu.");
        });
    }

    // Hàm render bảng khoản thu
    function renderTable(data) {
        const tbody = document.getElementById('khoanThuTableBody');
        tbody.innerHTML = '';

        if (!Array.isArray(data) || data.length === 0) {
            tbody.innerHTML = "<tr><td colspan='7'>Không có dữ liệu</td></tr>";
            return;
        }

        data.forEach(item => {
            const row = document.createElement('tr');
            row.innerHTML = `
                <td>${item.ma_khoan_thu}</td>
                <td>${item.ten_khoan_thu}</td>
                <td>${item.loai_khoan_thu}</td>
                <td>${formatCurrency(item.so_tien)}</td>
                <td>${item.ngay_tao}</td>
                <td>${item.han_nop}</td>
                <td class="btn_cn">
                    <a href="/admin/khoanthu/${item.ma_khoan_thu}/edit">
                        <button class="button-85">Sửa</button>
                    </a>
                    <button class="button-85" onclick="xoa(${item.ma_khoan_thu})">Xóa</button>
                </td>
            `;
            tbody.appendChild(row);
        });
    }

    // Hàm xóa khoản thu
    function xoa(id) {
        if (confirm("Bạn có chắc muốn xóa?")) {
            axios.delete(`/api/admin/khoanthu/${id}`, {
                headers: {
                    Authorization: 'Bearer ' + localStorage.getItem('token')
                }
            })
            .then(() => {
                alert("Xóa thành công");
                loadData();
            })
            .catch(err => {
                alert("Xóa thất bại,Khoản thu đã có hóa đơn nộp tiền");
                console.error("Lỗi xóa:", err);
            });
        }
    }
    function formatCurrency(value) {
    const number = parseFloat(value);
    if (isNaN(number)) return value;
    return number.toLocaleString('vi-VN'); // hoặc + ' đ' nếu thích
}

    function exportExcel() {
        const table = document.querySelector("table");

        // Chỉ định các cột muốn xuất ( 0-5 = Mã, Tên, Loại, Số tiền, Ngày tạo, Hạn nộp)
        const selectedColumns = [0, 1, 2, 3,4, 5];

        let data = [];

        // Lấy tiêu đề
        const headerRow = table.querySelector("thead tr");
        const headers = [];
        headerRow.querySelectorAll("th").forEach((th, idx) => {
            if (selectedColumns.includes(idx)) {
                headers.push(th.innerText.trim());
            }
        });
        data.push(headers);

        // Lấy dữ liệu trong tbody
        const rows = table.querySelectorAll("tbody tr");
        rows.forEach(tr => {
            const row = [];
            tr.querySelectorAll("td").forEach((td, idx) => {
                if (selectedColumns.includes(idx)) {
                    row.push(td.innerText.trim());
                    
                }
            });
            data.push(row);
        });

        // Tạo worksheet và workbook
        const worksheet = XLSX.utils.aoa_to_sheet(data);
        const workbook = XLSX.utils.book_new();
        XLSX.utils.book_append_sheet(workbook, worksheet, "Khoản Thu");

        // Xuất file
        XLSX.writeFile(workbook, `khoan_thu_${new Date().toISOString().slice(0, 10)}.xlsx`);
    }


</script>
@endsection