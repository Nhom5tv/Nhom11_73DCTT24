@extends('layout')

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
    <main class="table">
        <section class="table__header">
            <h1>Danh sách Khoa</h1>
            <div class="Insert">
                <a href="{{ route('khoa.create') }}">
                    <button class="button-85">➕ Thêm Khoa</button>
                </a>
            </div>
            <div class="input-group" style="margin-left: 10px;">
                <input type="text" id="timkiem" placeholder="Tìm theo tên khoa..." />
            </div>
            <button onclick="timKiemKhoa()" class="button-85" style="border: none; background: transparent;">
                <i class="fa fa-search"></i>
            </button>
            <div class="export__file">
                <button class="button-85" style="margin-left: 10px;" onclick="exportCSV()">
                    <i class="fa fa-download"></i> Export
                </button>
            </div>
            {{-- <div class="Upload">
                <form id="upload-form" enctype="multipart/form-data" style="display: inline-block;">
                    <input type="file" id="excelFile" name="file" accept=".xlsx,.xls,.csv" required>
                    <button type="submit" class="button-85">⬆️ Upload</button>
                </form>
            </div> --}}
        </section>
        <section class="table__body">
            <!-- Bảng dữ liệu -->
            <table border="1" cellpadding="5">
                <thead>
                    <tr>
                        <th>Mã Khoa</th>
                        <th>Tên Khoa</th>
                        <th>Liên hệ</th>
                        <th>Ngày thành lập</th>
                        <th>Tiền/tín chỉ</th>
                        <th>Hành động</th>
                    </tr>
                </thead>
                <tbody id="bang-khoa">
                    <!-- JS render -->
                </tbody>
            </table>
        </section>
    </main>
@endsection
<script src="https://cdn.jsdelivr.net/npm/axios/dist/axios.min.js"></script>
<script>
    axios.defaults.headers.common['Authorization'] = `Bearer ${localStorage.getItem('token')}`;
    let allData = [];

    function taiDanhSach() {
        axios.get('/api/admin/dskhoa')
            .then(res => {
                allData = res.data;
                hienThiBang(res.data);
            })
            .catch(err => console.error('Lỗi tải danh sách:', err));
    }

    function hienThiBang(data) {
        const tbody = document.getElementById('bang-khoa');
        tbody.innerHTML = '';

        data.forEach(khoa => {
            const row = `<tr>
                <td>${khoa.ma_khoa}</td>
                <td>${khoa.ten_khoa}</td>
                <td>${khoa.lien_he || '-'}</td>
                <td>${khoa.ngay_thanh_lap || '-'}</td>
                <td>${khoa.tien_moi_tin_chi || '-'}</td>
                <td>
                    <a href="/admin/khoa/sua/${khoa.ma_khoa}" ><button class="button-85">✏️ Sửa</button></a>
                    <button class="button-85" onclick="xoaKhoa(${khoa.ma_khoa})">🗑 Xoá</button>
                </td>
            </tr>`;
            tbody.innerHTML += row;
        });
    }

    function xoaKhoa(id) {
        if (confirm('Bạn có chắc muốn xoá khoa này?')) {
            axios.delete(`/api/admin/dskhoa/${id}`)
                .then(() => {
                    alert('Đã xoá khoa');
                    taiDanhSach();
                })
                .catch(err => alert('Xoá thất bại: ' + err.response?.data?.message || err.message));
        }
    }

    function timKiemKhoa() {
        const q = document.getElementById('timkiem').value;
        axios.get(`/api/admin/timkiem-khoa?q=${q}`)
            .then(res => hienThiBang(res.data))
            .catch(err => console.error('Tìm kiếm lỗi:', err));
    }

    function exportCSV() {
        let csv = 'Mã Khoa,Tên Khoa,Liên hệ,Ngày thành lập,Tiền/tín chỉ\n';
        allData.forEach(k => {
            csv +=
                `${k.ma_khoa},${k.ten_khoa},${k.lien_he || ''},${k.ngay_thanh_lap || ''},${k.tien_moi_tin_chi || ''}\n`;
        });

        const blob = new Blob([csv], {
            type: 'text/csv'
        });
        const url = window.URL.createObjectURL(blob);
        const a = document.createElement('a');
        a.href = url;
        a.download = 'ds_khoa.csv';
        a.click();
    }

    document.getElementById('upload-form')?.addEventListener('submit', function(e) {
        e.preventDefault();
        const fileInput = document.getElementById('excelFile');
        if (!fileInput.files.length) {
            alert('Vui lòng chọn file trước khi upload');
            return;
        }

        const formData = new FormData();
        formData.append('file', fileInput.files[0]);

        axios.post('/api/admin/import-khoa', formData, {
                headers: {
                    'Content-Type': 'multipart/form-data',
                    'Authorization': `Bearer ${token}`
                }
            })
            .then(res => {
                alert(res.data.message || 'Tải file thành công');
                taiDanhSach();
                fileInput.value = ''; // Reset file input
            })
            .catch(err => {
                console.error(err);
                alert('Tải file thất bại: ' + (err.response?.data?.message || err.message));
            });
    });

    document.addEventListener('DOMContentLoaded', taiDanhSach);
</script>
