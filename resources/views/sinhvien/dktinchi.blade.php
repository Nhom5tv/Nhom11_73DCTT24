@extends('layout')

@section('title', 'Đăng ký tín chỉ')

@section('content')
<div class="container">
    <h2 class="mb-4">Đăng ký tín chỉ</h2>

    {{-- Tìm kiếm --}}
    <div class="row mb-3">
        <div class="col-md-5">
            <input type="text" id="tenMonHoc" class="form-control" placeholder="Tên môn học">
        </div>
        <div class="col-md-5">
            <input type="text" id="lichHoc" class="form-control" placeholder="Lịch học dự kiến">
        </div>
        <div class="col-md-2">
            <button class="btn btn-primary w-100" onclick="timKiemMonHoc()">Tìm kiếm</button>
        </div>
    </div>

    {{-- Danh sách môn học --}}
    <h5>Danh sách môn học có thể đăng ký</h5>
    <div class="table-responsive">
        <table class="table table-bordered align-middle text-center">
            <thead class="table-secondary">
                <tr>
                    <th>Mã môn</th>
                    <th>Tên môn</th>
                    <th>Số tín chỉ</th>
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
    </div>

    {{-- Danh sách đã đăng ký --}}
    <h5 class="mt-5">Danh sách môn học đã đăng ký</h5>
    <div class="table-responsive">
        <table class="table table-bordered align-middle text-center">
            <thead class="table-secondary">
                <tr>
                    <th>Mã môn</th>
                    <th>Tên môn</th>
                    <th>Số tín chỉ</th>
                    <th>Số lượng</th>
                    <th>Còn lại</th>
                    <th>Lịch học</th>
                    <th>Thao tác</th>
                </tr>
            </thead>
            <tbody id="dsDaDangKy">
                <tr><td colspan="7">Đang tải...</td></tr>
            </tbody>
        </table>
    </div>
</div>

{{-- Axios script --}}
<script src="https://cdn.jsdelivr.net/npm/axios/dist/axios.min.js"></script>

<script>
    const token = localStorage.getItem('token');
    axios.defaults.headers.common['Authorization'] = 'Bearer ' + token;

    // Tải dữ liệu ban đầu
    document.addEventListener("DOMContentLoaded", () => {
        loadMonHoc();
        loadDaDangKy();
    });

    function loadMonHoc(tenMon = '', lichHoc = '') {
        axios.get('/api/monhoc', {
            params: {
                ten_mon_hoc: tenMon,
                lich_hoc_du_kien: lichHoc
            }
        })
        .then(res => {
            const data = res.data;
            const tbody = document.getElementById('dsMonHoc');
            tbody.innerHTML = '';

            if (data.length === 0) {
                tbody.innerHTML = `<tr><td colspan="7">Không có môn học nào</td></tr>`;
                return;
            }

            data.forEach(mon => {
                const btnText = mon.trang_thai_dang_ky === 'Đang Chờ Duyệt' ? 'Đã đăng ký' :
                                mon.con_lai === 0 ? 'Hết chỗ' : 'Đăng ký';
                const btnDisabled = (mon.trang_thai_dang_ky === 'Đang Chờ Duyệt' || mon.con_lai === 0);

                tbody.innerHTML += `
                    <tr>
                        <td>${mon.ma_mon_hoc}</td>
                        <td>${mon.ten_mon_hoc}</td>
                        <td>${mon.so_tin_chi}</td>
                        <td>${mon.so_luong_toi_da}</td>
                        <td>${mon.con_lai}</td>
                        <td>${mon.lich_hoc_du_kien}</td>
                        <td>
                            <button class="btn btn-${btnDisabled ? 'secondary' : 'success'}"
                                ${btnDisabled ? 'disabled' : ''}
                                onclick="dangKy('${mon.ma_mon_hoc}', '${mon.lich_hoc_du_kien}')">
                                ${btnText}
                            </button>
                        </td>
                    </tr>`;
            });
        });
    }

    function loadDaDangKy() {
        axios.get('/api/dadangky')
        .then(res => {
            const data = res.data;
            const tbody = document.getElementById('dsDaDangKy');
            tbody.innerHTML = '';

            if (data.length === 0) {
                tbody.innerHTML = `<tr><td colspan="7">Chưa có môn học nào</td></tr>`;
                return;
            }

            data.forEach(mon => {
                tbody.innerHTML += `
                    <tr>
                        <td>${mon.ma_mon_hoc}</td>
                        <td>${mon.ten_mon_hoc}</td>
                        <td>${mon.so_tin_chi}</td>
                        <td>${mon.so_luong_toi_da}</td>
                        <td>${mon.con_lai}</td>
                        <td>${mon.lich_hoc_du_kien}</td>
                        <td>
                            <button class="btn btn-danger" onclick="huyDangKy(${mon.ma_dang_ky})">Hủy</button>
                        </td>
                    </tr>`;
            });
        });
    }

    function timKiemMonHoc() {
        const ten = document.getElementById('tenMonHoc').value;
        const lich = document.getElementById('lichHoc').value;
        loadMonHoc(ten, lich);
    }

    function dangKy(maMon, lichHoc) {
        if (!confirm('Bạn có chắc muốn đăng ký môn học này?')) return;

        axios.post('/api/dangky', {
            ma_mon_hoc: maMon,
            lich_hoc_du_kien: lichHoc
        })
        .then(() => {
            alert('Đăng ký thành công!');
            loadMonHoc();
            loadDaDangKy();
        })
        .catch(() => alert('Lỗi khi đăng ký!'));
    }

    function huyDangKy(id) {
        if (!confirm('Bạn có chắc muốn hủy đăng ký?')) return;

        axios.delete(`/api/huydangky/${id}`)
        .then(() => {
            alert('Đã hủy đăng ký!');
            loadMonHoc();
            loadDaDangKy();
        })
        .catch(() => alert('Lỗi khi hủy!'));
    }
</script>

