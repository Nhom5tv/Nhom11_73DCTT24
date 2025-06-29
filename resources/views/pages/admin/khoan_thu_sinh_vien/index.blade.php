@extends('layout')

@section('content')
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
<link rel="stylesheet" href="/css/styleDT.css?v={{ time() }}">
<link rel="stylesheet" href="/css/button.css?v={{ time() }}">

<style>
    .btn_cn { display: flex; margin: 0; gap: 5px; }
</style>

<main class="table" id="customers_table">
    <section class="table__header">
        <h1>Khoản thu sinh viên</h1>

        <div class="input-group">
            <input type="search" id="searchMaSV" placeholder="Mã sinh viên">
        </div>
        <div class="input-group">
            <input type="search" id="searchTenKhoanThu" placeholder="Tên khoản thu">
        </div>
        <label for="searchTrangThai">Trạng thái:</label>
        <select id="searchTrangThai">
            <option value="">Tất cả</option>
            <option value="Đã thanh toán">Đã thanh toán</option>
            <option value="Chưa thanh toán">Chưa thanh toán</option>
        </select>

        <button id="btnTimKiem" style="border: none; background: transparent;"><i class="fa fa-search"></i></button>
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
                    <th>Tên khoản thu</th>
                    <th>Mã sinh viên</th>
                    <th>Số tiền ban đầu</th>
                    <th>Số tiền miễn giảm</th>
                    <th>Số tiền phải nộp</th>
                    <th>Trạng thái</th>
                   
                </tr>
            </thead>
            <tbody id="table-body">
                <!-- Dữ liệu sẽ được thêm ở đây bằng JS -->
            </tbody>
        </table>
    </section>
</main>
<script src="https://cdnjs.cloudflare.com/ajax/libs/xlsx/0.18.5/xlsx.full.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/axios/dist/axios.min.js"></script>
<script>
    document.getElementById('btnTimKiem').addEventListener('click', fetchData);

    function fetchData() {
        const maSV = document.getElementById('searchMaSV').value;
        const tenKT = document.getElementById('searchTenKhoanThu').value;
        const trangThai = document.getElementById('searchTrangThai').value;

        axios.get('/api/admin/khoanthusv', {
            params: {
                ma_sinh_vien: maSV,
                ten_khoan_thu: tenKT,
                trang_thai: trangThai
            },
            headers: {
                Authorization: 'Bearer ' + localStorage.getItem('token')
            }
        })
        .then(res => {
            renderTable(res.data);
        })
        .catch(err => {
            console.error(err);
            alert('Không thể tải dữ liệu!');
        });
    }

    function renderTable(data) {
        const tbody = document.getElementById('table-body');
        tbody.innerHTML = '';

        if (data.length === 0) {
            tbody.innerHTML = `<tr><td colspan="7">Không có dữ liệu</td></tr>`;
            return;
        }

        data.forEach(item => {
            tbody.innerHTML += `
                <tr>
                    <td>${item.ten_khoan_thu}</td>
                    <td>${item.ma_sinh_vien}</td>
                    <td>${formatCurrency(item.so_tien_ban_dau)}</td>
                    <td>${formatCurrency(item.so_tien_mien_giam ?? 0)}</td>
                    <td>${formatCurrency(item.so_tien_phai_nop ?? 0)}</td>
                    <td>${item.trang_thai_thanh_toan ?? ''}</td>
                   
                </tr>
            `;
        });
    }
    function formatCurrency(value) {
        const number = parseFloat(value);
        if (isNaN(number)) return value;
        return number.toLocaleString('vi-VN'); // hoặc + ' đ' nếu thích
    }
    function exportExcel() {
        const table = document.querySelector("table");

        // Chỉ định các cột muốn xuất ( 0-5 = Mã, Tên, Loại, Số tiền, Ngày tạo, Hạn nộp)
        const selectedColumns = [0, 1, 2, 3, 4, 5];

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
        XLSX.utils.book_append_sheet(workbook, worksheet, "Khoản Thu Sinh Viên");

        // Xuất file
        XLSX.writeFile(workbook, `khoan_thu_sinh_vien_${new Date().toISOString().slice(0, 10)}.xlsx`);
    }

    // Load dữ liệu ban đầu
    fetchData();
</script>
@endsection
