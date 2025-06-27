@extends('layout')

@section('content')
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
<link rel="stylesheet" href="/css/button.css?v={{ time() }}">
<link rel="stylesheet" href="/css/styleDT.css?v={{ time() }}">

<style>
    .btn_cn { display: flex; gap: 5px; }
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
                    <td>${row.so_tien_da_nop}</td>
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

    fetchData();
</script>
@endsection
