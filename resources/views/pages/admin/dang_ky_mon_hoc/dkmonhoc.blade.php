@extends('layout')

@section('title', 'Danh sách đăng ký môn học')

@section('content')
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
<link rel="stylesheet" href="/css/button.css?v={{ time() }}">
<link rel="stylesheet" href="/css/styleDT.css?v={{ time() }}">

<style>
    .btn_cn {
        display: flex;
        margin: 0;
    }
</style>

<main class="table" id="customers_table">
    <section class="table__header">
        <h1>Danh sách đăng ký môn học</h1>

        <div class="input-group">
            <input type="search" id="search_ma_sinh_vien" placeholder="Mã Sinh Viên">
        </div>
        <div class="input-group">
            <input type="search" id="search_ma_mon" placeholder="Mã Môn">
        </div>
        <button id="btnTimkiem" type="button" style="border: none; background: transparent;">
            <i class="fa fa-search"></i>
        </button>

        <div>
            <button class="button-85" id="btnHuyTatCa" role="button" onclick="huyTatCa()">Huỷ Tất Cả</button>
        </div>
    </section>

    <section class="table__body">
        <table>
            <thead>
                <tr>
                    <th>Mã Đăng Ký</th>
                    <th>Mã Môn</th>
                    <th>Mã Sinh Viên</th>
                    <th>Mã Lớp</th>
                    <th>Lịch Học Dự Kiến</th>
                    <th>Trạng Thái</th>
                </tr>
            </thead>
            <tbody id="dangKyTableBody">
                <!-- Dữ liệu sẽ được render bằng JavaScript -->
            </tbody>
        </table>
    </section>
</main>

<script src="https://cdn.jsdelivr.net/npm/axios/dist/axios.min.js"></script>
<script>
    async function fetchDangKy(maSV = '', maMon = '') {
        try {
            const response = await axios.get('/api/admin/dkmonhoc', {
                headers: {
                    Authorization: 'Bearer ' + localStorage.getItem('token')
                },
                params: {
                    ma_sinh_vien: maSV,
                    ma_mon: maMon
                }
            });

            const data = response.data;
            const tbody = document.getElementById('dangKyTableBody');
            tbody.innerHTML = '';

            data.forEach(row => {
                const tr = document.createElement('tr');
                tr.innerHTML = `
                    <td>${row.ma_dang_ky}</td>
                    <td>${row.ma_mon}</td>
                    <td>${row.ma_sinh_vien}</td>
                    <td>${row.ma_lop ?? ''}</td>
                    <td>${row.lich_hoc_du_kien ?? ''}</td>
                    <td>${row.trang_thai ?? ''}</td>
                `;
                tbody.appendChild(tr);
            });
        } catch (error) {
            console.error('Lỗi khi load danh sách đăng ký:', error);
        }
    }

    function huyTatCa() {
        if (!confirm('Bạn có chắc muốn huỷ tất cả?')) return;
        axios.post('/api/admin/dangky/huytatca', {}, {
            headers: {
                Authorization: 'Bearer ' + localStorage.getItem('token')
            }
        })
        .then(response => {
            alert(response.data.message || 'Huỷ tất cả thành công.');
            fetchDangKy();
        })
        .catch(error => {
            console.error('Lỗi huỷ tất cả:', error);
            alert('Huỷ tất cả thất bại.');
        });
    }

    document.getElementById('btnTimkiem').addEventListener('click', () => {
        const maSV = document.getElementById('search_ma_sinh_vien').value.trim();
        const maMon = document.getElementById('search_ma_mon').value.trim();
        fetchDangKy(maSV, maMon);
    });

    document.addEventListener('DOMContentLoaded', () => {
        fetchDangKy();
    });
</script>
@endsection
