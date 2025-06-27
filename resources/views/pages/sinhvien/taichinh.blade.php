@extends('layout')

@section('content')
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
<link rel="stylesheet" href="/css/button.css">
<link rel="stylesheet" href="/css/styleDT.css">
<link rel="stylesheet" href="/css/dulieu.css">
<link rel="stylesheet" href="/css/select2.css">
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

<style>
    .btn_cn { display: flex; margin: 0; }
    .main.table { width: 950px; }
    .quaylai { text-align: center; justify-content: center; padding-top: 5px; }
    .main-content { width: 100%; display: flex; }
    .content { width: 550px; margin: 10px; height: 600px; }
    .input-box input { width: 100%; }
    .status-label { display: inline-block !important; color: #fff !important; background-color: #28a745; padding: 5px 10px; font-weight: bold; border-radius: 4px; margin: 10px 0; }
    .status-complete { background-color: #28a745 !important; }
    .status-incomplete { background-color: #dc3545 !important; }
</style>

<main class="table" id="customers_table" style="margin-top: 30px;">
    <section class="table__header">
        <h1>Các khoản phải nộp</h1>
        <div id="tongTrangThai" style="text-align: center; margin: 10px 0;"></div>
    </section>
    <section class="table__body">
        <table>
            <thead>
                <tr>
                    <th>Tên khoản thu</th>
                    <th>Ngày tạo</th>
                    <th>Hạn nộp</th>
                    <th>Số tiền ban đầu</th>
                    <th>Số tiền miễn giảm</th>
                    <th>Số tiền phải nộp</th>
                    <th>Trạng thái</th>
                </tr>
            </thead>
            <tbody id="dsPhaiNop"></tbody>
        </table>
    </section>
</main>

<main class="table" id="customers_table" style="width: 750px; margin: 30px;">
    <section class="table__header">
        <h1>Thông tin hóa đơn</h1>
    </section>
    <section class="table__body">
        <table>
            <thead>
                <tr>
                    <th>Mã hóa đơn</th>
                    <th>Tên khoản thu</th>
                    <th>Số tiền đã nộp</th>
                    <th>Ngày thanh toán</th>
                    <th>Hình thức thanh toán</th>
                    <th>Nội dung</th>
                </tr>
            </thead>
            <tbody id="dsHoaDon"></tbody>
        </table>
    </section>
</main>
<script src="https://cdn.jsdelivr.net/npm/axios/dist/axios.min.js"></script>
<script>
    document.addEventListener('DOMContentLoaded', async () => {
        try {
            const token = localStorage.getItem('token');
            const config = { headers: { Authorization: `Bearer ${token}` } };

            const [hoaDonRes, khoanThuRes] = await Promise.all([
                axios.get('/api/sinhvien/hoadon', config),
                axios.get('/api/sinhvien/hoadon/khoannop', config)
            ]);

            const hoaDons = hoaDonRes.data;
            const khoanNops = khoanThuRes.data;

            let trangThaiTong = 'complete';
            let message = '';
            const today = new Date();

            khoanNops.forEach(row => {
                const hanNop = new Date(row.han_nop);
                if (row.trang_thai_thanh_toan !== 'Đã thanh toán') trangThaiTong = 'incomplete';
                if ((hanNop - today) / (1000 * 60 * 60 * 24) <= 7 && row.trang_thai_thanh_toan !== 'Đã thanh toán') {
                    message += `Khoản thu "${row.khoan_thu.ten_khoan_thu}" sắp đến hạn nộp vào ngày ${row.khoan_thu.han_nop}\n`;
                }

                document.getElementById('dsPhaiNop').innerHTML += `
                    <tr>
                        <td>${row.khoan_thu.ten_khoan_thu}</td>
                        <td>${row.khoan_thu.ngay_tao}</td>
                        <td>${row.khoan_thu.han_nop}</td>
                        <td>${row.so_tien_ban_dau}</td>
                        <td>${row.so_tien_mien_giam}</td>
                        <td>${row.so_tien_phai_nop}</td>
                        <td>${row.trang_thai_thanh_toan}</td>
                    </tr>`;
            });

            document.getElementById('tongTrangThai').innerHTML = `<span class="status-label ${trangThaiTong === 'complete' ? 'status-complete' : 'status-incomplete'}">${trangThaiTong === 'complete' ? 'Đã hoàn thành' : 'Chưa hoàn thành'}</span>`;

            if (message) {
                Swal.fire({ title: 'Thông Báo Quan Trọng!', text: message, icon: 'warning', confirmButtonText: 'OK' });
            }

            hoaDons.forEach(row => {
                document.getElementById('dsHoaDon').innerHTML += `
                    <tr>
                        <td>${row.ma_hoa_don}</td>
                        <td>${row.khoan_thu.ten_khoan_thu}</td>
                        <td>${row.so_tien_da_nop}</td>
                        <td>${row.ngay_thanh_toan}</td>
                        <td>${row.hinh_thuc_thanh_toan}</td>
                        <td>${row.noi_dung}</td>
                    </tr>`;
            });

        } catch (error) {
            console.error('Lỗi khi tải dữ liệu:', error);
            Swal.fire('Lỗi', 'Không thể tải dữ liệu', 'error');
        }
    });
</script>
@endsection
