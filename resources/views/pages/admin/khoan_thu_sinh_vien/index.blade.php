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
                    <td>${item.so_tien_ban_dau}</td>
                    <td>${item.so_tien_mien_giam ?? 0}</td>
                    <td>${item.so_tien_phai_nop ?? 0}</td>
                    <td>${item.trang_thai_thanh_toan ?? ''}</td>
                   
                </tr>
            `;
        });
    }

    // Load dữ liệu ban đầu
    fetchData();
</script>
@endsection
