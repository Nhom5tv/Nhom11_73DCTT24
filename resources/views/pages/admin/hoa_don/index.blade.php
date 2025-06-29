@extends('layout')

@section('content')
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
    <link rel="stylesheet" href="/css/button.css?v={{ time() }}">
    <link rel="stylesheet" href="/css/styleDT.css?v={{ time() }}">

    <style>
        .btn_cn {
            display: flex;
            gap: 5px;
        }
    </style>

    <main class="table" id="customers_table">
        <section class="table__header">
            <h1>Quản lý hóa đơn</h1>

            <div class="input-group">
                <input type="search" id="searchMaSV" placeholder="Mã sinh viên">
            </div>
            <div class="input-group">
                <input type="date" id="searchNgayThanhToan" placeholder="Ngày thanh toán">
            </div>

            <button id="btnTimKiem" style="border: none; background: transparent;"><i class="fa fa-search"></i></button>

            <div class="Insert">
                <a href="/admin/hoadon/create"><button class="button-85" role="button">Thêm hóa đơn</button></a>
            </div>
            <div class="export__file">
                <button class="button-85" onclick="exportExcel()">
                    <i class="fa fa-download"></i> Export
                </button>
            </div>
            <div class="export__file">
                <input type="file" id="excelFileInput" accept=".xlsx, .xls" style="display:none"
                    onchange="handleFileUpload(event)">
                <button class="button-85" onclick="document.getElementById('excelFileInput').click()">
                    <i class="fa fa-upload"></i> Upload
                </button>
            </div>

        </section>

        <section class="table__body">
            <table>
                <thead>
                    <tr>
                        <th>Mã hóa đơn</th>
                        <th>Mã sinh viên</th>
                        <th>Tên khoản thu</th>
                        <th>Số tiền đã nộp</th>
                        <th>Ngày thanh toán</th>
                        <th>Hình thức thanh toán</th>
                        <th>Nội dung</th>
                        <th>Chức năng</th>
                    </tr>
                </thead>
                <tbody id="table-body">
                    <!-- Dữ liệu JS tải về từ API -->
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
            const ngayTT = document.getElementById('searchNgayThanhToan').value;

            axios.get('/api/admin/hoadon', {
                params: {
                    ma_sinh_vien: maSV,
                    ngay_thanh_toan: ngayTT
                },
                headers: {
                    Authorization: 'Bearer ' + localStorage.getItem('token')
                }
            })
                .then(res => renderTable(res.data))
                .catch(err => alert('Lỗi khi tải dữ liệu!'));
        }

        function renderTable(data) {
            const tbody = document.getElementById('table-body');
            tbody.innerHTML = '';

            if (data.length === 0) {
                tbody.innerHTML = '<tr><td colspan="8">Không có dữ liệu</td></tr>';
                return;
            }

            data.forEach(row => {
                tbody.innerHTML += `
                            <tr>
                                <td>${row.ma_hoa_don}</td>
                                <td>${row.ma_sinh_vien}</td>
                                <td>${row.khoan_thu?.ten_khoan_thu ?? ''}</td>
                                <td>${formatCurrency(row.so_tien_da_nop)}</td>
                                <td>${row.ngay_thanh_toan}</td>
                                <td>${row.hinh_thuc_thanh_toan ?? ''}</td>
                                <td>${row.noi_dung ?? ''}</td>
                                <td class="btn_cn">
                                    <button class="button-85" onclick="huyHoaDon(${row.ma_hoa_don})">Hủy</button>
                                </td>
                            </tr>
                        `;
            });
        }

        function huyHoaDon(id) {
            if (!confirm('Bạn có chắc muốn hủy hóa đơn này?')) return;

            axios.put(`/api/admin/hoadon/${id}`, {}, {
                headers: {
                    Authorization: 'Bearer ' + localStorage.getItem('token')
                }
            })
                .then(res => {
                    alert(res.data.message);
                    fetchData();
                })
                .catch(err => alert('Không thể hủy hóa đơn!'));
        }
        function formatCurrency(value) {
            const number = parseFloat(value);
            if (isNaN(number)) return value;
            return number.toLocaleString('vi-VN');
        }

        function exportExcel() {
            const table = document.querySelector("table");

            // Chỉ định các cột muốn xuất 
            const selectedColumns = [0, 1, 2, 3, 4, 5, 6];

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
            XLSX.utils.book_append_sheet(workbook, worksheet, "Hóa đơn");

            // Xuất file
            XLSX.writeFile(workbook, `hoa_don_${new Date().toISOString().slice(0, 10)}.xlsx`);
        }
        function formatDate(value) {
            const date = new Date(value);
            if (isNaN(date)) return null;
            const yyyy = date.getFullYear();
            const mm = String(date.getMonth() + 1).padStart(2, '0');
            const dd = String(date.getDate()).padStart(2, '0');
            return `${yyyy}-${mm}-${dd}`;
        }


        function handleFileUpload(event) {
            const file = event.target.files[0];
            if (!file) return;

            const reader = new FileReader();
            reader.onload = function (e) {
                const data = new Uint8Array(e.target.result);
                const workbook = XLSX.read(data, { type: 'array' });
                const sheet = workbook.Sheets[workbook.SheetNames[0]];
                const rows = XLSX.utils.sheet_to_json(sheet, { header: 1 });

                // Bỏ dòng tiêu đề
               // Bỏ dòng tiêu đề + lọc dòng có Mã SV
                const dataRows = rows.slice(1).filter(row =>
                    row[0] !== undefined && row[0].toString().trim() !== ''
                );

                const hoaDons = dataRows.map(row => ({
                    ma_sinh_vien: row[0],
                    ma_khoan_thu: row[1],
                    ngay_thanh_toan: formatDate(row[2]),
                    so_tien_da_nop: parseFloat(row[3]),
                    hinh_thuc_thanh_toan: row[4],
                    noi_dung: row[5] ?? ''
                }));

                // Gửi từng hóa đơn (hoặc batch nếu backend hỗ trợ)
                let success = 0;
                let fail = 0;

                const token = localStorage.getItem('token');
                const promises = hoaDons.map(hd =>
                    axios.post('/api/admin/hoadon', hd, {
                        headers: {  Authorization: 'Bearer ' + localStorage.getItem('token') }
                    }).then(() => success++).catch(() => fail++)
                );

                Promise.all(promises).then(() => {
                    alert(`Tải lên hoàn tất: ${success} thành công, ${fail} lỗi.`);
                    fetchData();
                });
            };

            reader.readAsArrayBuffer(file);
        }



        fetchData();
    </script>
@endsection