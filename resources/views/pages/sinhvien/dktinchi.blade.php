@extends('layout')

@section('title', 'Đăng ký tín chỉ')

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

<main class="table">
    <section class="table__header">
        <h1>Đăng Ký Tín Chỉ</h1>
        <div class="input-group">
            <input type="search" id="searchTenMon" placeholder="Tên môn học">
        </div>
        <div class="input-group">
            <input type="search" id="searchLichHoc" placeholder="Lịch học">
        </div>
        <button id="btnTimKiem" style="border: none; background: transparent;">
            <i class="fa fa-search"></i>
        </button>
    </section>

    {{-- Danh sách môn học có thể đăng ký --}}
    <section class="table__body">
        <h3>Danh sách môn học có thể đăng ký</h3>
        <table>
            <thead>
                <tr>
                    <th>Mã môn</th>
                    <th>Tên môn</th>
                    <th>Số TC</th>
                    <th>Số lượng</th>
                    <th>Còn lại</th>
                    <th>Lịch học</th>
                    <th>Thao tác</th>
                </tr>
            </thead>
            <tbody id="dsMonHoc">
                <tr><td colspan="7">Đang tải...</td></tr>
            </tbody>
        </table>
    </section>

    {{-- Danh sách đã đăng ký --}}
    <section class="table__body">
        <h3>Danh sách đã đăng ký</h3>
        <table>
            <thead>
                <tr>
                    <th>Mã môn</th>
                    <th>Tên môn</th>
                    <th>Số TC</th>
                    <th>Số lượng</th>
                    <th>Còn lại</th>
                    <th>Lịch học</th>
                    <th>Hủy</th>
                </tr>
            </thead>
            <tbody id="dsDaDangKy">
                <tr><td colspan="7">Đang tải...</td></tr>
            </tbody>
        </table>
    </section>
</main>

<script src="https://cdn.jsdelivr.net/npm/axios/dist/axios.min.js"></script>
<script>
    // token = localStorage.getItem('token');
    // axios.defaults.headers.common['Authorization'] = 'Bearer ' + token;
    // axios.defaults.headers.common['Authorization'] = `Bearer ${localStorage.getItem('token')}`;


    function loadMonHoc(ten = '', lich = '') {
        axios.get('/api/sinhvien/monhoc').then(res => {
            const ds = res.data;
            const tbody = document.getElementById('dsMonHoc');
            tbody.innerHTML = '';

            if (ds.length === 0) {
                tbody.innerHTML = `<tr><td colspan="7">Không có môn học nào</td></tr>`;
                return;
            }

            ds.forEach(mon => {
                const daDangKy = mon.trang_thai_dang_ky === 'Đang Chờ Duyệt';
                const hetCho = mon.con_lai <= 0;
                const disableBtn = daDangKy || hetCho;

                const btnLabel = daDangKy ? 'Đã đăng ký' : (hetCho ? 'Hết chỗ' : 'Đăng ký');
                const btnClass = disableBtn ? 'button-85 disabled' : 'button-85';

                tbody.innerHTML += `
                    <tr>
                        <td>${mon.ma_mon_hoc}</td>
                        <td>${mon.ten_mon_hoc}</td>
                        <td>${mon.so_tin_chi}</td>
                        <td>${mon.so_luong_toi_da}</td>
                        <td>${mon.con_lai}</td>
                        <td>${mon.lich_hoc_du_kien}</td>
                        <td class="btn_cn">
                            <button class="${btnClass}" ${disableBtn ? 'disabled' : ''} onclick="dangKy('${mon.ma_mon_hoc}', '${mon.lich_hoc_du_kien}')">${btnLabel}</button>
                        </td>
                    </tr>`;
            });
        });
    }

    function loadDaDangKy() {
        axios.get('/api/sinhvien/dadangky').then(res => {
            const ds = res.data;
            const tbody = document.getElementById('dsDaDangKy');
            tbody.innerHTML = '';

            if (ds.length === 0) {
                tbody.innerHTML = `<tr><td colspan="7">Không có môn nào đã đăng ký</td></tr>`;
                return;
            }

           ds.forEach(mon => {
    const isDaDuyet = mon.trang_thai === 'Đã Duyệt';

    tbody.innerHTML += `
        <tr>
            <td>${mon.ma_mon_hoc}</td>
            <td>${mon.ten_mon_hoc}</td>
            <td>${mon.so_tin_chi}</td>
            <td>${mon.so_luong_toi_da}</td>
            <td>${mon.con_lai}</td>
            <td>${mon.lich_hoc_du_kien}</td>
            <td class="btn_cn">
                ${
                    isDaDuyet
                        ? '<span style="color: green; font-weight: bold;">Đã duyệt</span>'
                        : `<button class="button-85" onclick="huyDangKy(${mon.ma_dang_ky})">Hủy</button>`
                }
            </td>
        </tr>`;
});
        });
    }
function dangKy(maMon, lichHoc) {
    if (!confirm('Bạn có chắc muốn đăng ký môn học này?')) return;

    axios.post('/api/sinhvien/monhoc', {
        ma_mon_hoc: maMon,
        lich_hoc_du_kien: lichHoc
    }).then(() => {
        alert('Đăng ký thành công');
        loadMonHoc();
        loadDaDangKy();
    }).catch(err => {
        console.error(err.response);
        alert('Lỗi khi đăng ký: ' + (err.response?.data?.message || 'Lỗi không xác định'));
    });
}


    function huyDangKy(id) {
        if (!confirm('Bạn có chắc muốn hủy đăng ký?')) return;

        axios.delete(`/api/sinhvien/huydangky/${id}`).then(() => {
            alert('Đã hủy đăng ký');
            loadMonHoc();
            loadDaDangKy();
        }).catch(() => alert('Lỗi khi hủy đăng ký'));
    }

    document.getElementById('btnTimKiem').addEventListener('click', () => {
        const ten = document.getElementById('searchTenMon').value;
        const lich = document.getElementById('searchLichHoc').value;
        loadMonHoc(ten, lich);
    });
    document.addEventListener('DOMContentLoaded', () => {
    const token = localStorage.getItem('token');
console.log('Token:', token);
if (!token) {
    alert('Vui lòng đăng nhập lại!');
    window.location.href = '/login';
}
    axios.defaults.headers.common['Authorization'] = `Bearer ${token}`

    loadMonHoc();
    loadDaDangKy();
});
</script>
@endsection
