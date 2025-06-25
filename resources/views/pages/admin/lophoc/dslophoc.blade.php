@extends('layout')

@section('title', 'Quản lý Đăng Ký Lớp Học')

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
        <h1>Quản lý Đăng Ký Lớp Học</h1>

        <div class="input-group">
            <input type="search" id="search_ma_mon" placeholder="Mã Môn Học">
        </div>
        <div class="input-group">
            <input type="search" id="search_ma_giang_vien" placeholder="Mã Giảng Viên">
        </div>
        <button id="btnTimkiem" style="border: none; background: transparent;">
            <i class="fa fa-search"></i>
        </button>

        <div class="Insert">
            <a href="{{ url('admin/dslophoc/create') }}">
                <button class="button-85" role="button">Thêm Lớp Học</button>
            </a>
        </div>
    </section>

    <section class="table__body">
        <table>
            <thead>
                <tr>
                    <th>Mã Lớp</th>
                    <th>Mã Môn</th>
                    <th>Học Kỳ</th>
                    <th>Mã Giảng Viên</th>
                    <th>Lịch Học</th>
                    <th>Trạng Thái</th>
                    <th style="padding-left:50px">Chức Năng</th>
                </tr>
            </thead>
            <tbody id="lopHocTableBody">
                <!-- Dữ liệu sẽ được render bằng JS -->
            </tbody>
        </table>
    </section>
</main>

<script src="https://cdn.jsdelivr.net/npm/axios/dist/axios.min.js"></script>
<script>
    async function fetchLopHoc(maMon = '', maGV = '') {
        try {
            const response = await axios.get('/api/admin/dslophoc', {
                headers: {
                    Authorization: 'Bearer ' + localStorage.getItem('token')
                },
                params: {
                    ma_mon: maMon,
                    ma_giang_vien: maGV
                }
            });

            const data = response.data;
            const tbody = document.getElementById('lopHocTableBody');
            tbody.innerHTML = '';

            data.forEach(row => {
                const tr = document.createElement('tr');
                tr.innerHTML = `
                    <td>${row.ma_lop}</td>
                    <td>${row.ma_mon}</td>
                    <td>${row.hoc_ky}</td>
                    <td>${row.ma_giang_vien}</td>
                    <td>${row.lich_hoc ?? ''}</td>
                    <td>${row.trang_thai}</td>
                    <td class="btn_cn">
                        <a href="/admin/dslophoc/${row.ma_lop}/sua">
                            <button class="button-85" role="button">Sửa</button>
                        </a>
                    </td>
                `;
                tbody.appendChild(tr);
            });
        } catch (error) {
            console.error('Lỗi khi tải lớp học:', error);
        }
    }

    document.getElementById('btnTimkiem').addEventListener('click', () => {
        const maMon = document.getElementById('search_ma_mon').value;
        const maGV = document.getElementById('search_ma_giang_vien').value;
        fetchLopHoc(maMon, maGV);
    });

    document.addEventListener('DOMContentLoaded', () => {
        fetchLopHoc();
    });
</script>
@endsection
