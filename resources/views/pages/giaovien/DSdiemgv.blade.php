@extends('layout')
@section('title', 'Quản lý điểm sinh viên theo lớp')

@section('content')
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
    <link rel="stylesheet" href="/css/button.css?v={{ time() }}">
    <link rel="stylesheet" href="/css/styleDT.css?v={{ time() }}">
    <style>
        .btn_cn {
            display: flex;
            margin: 0;
        }
        .form-container {
            margin-bottom: 20px;
        }
        .select-class {
            padding: 10px;
            margin-right: 10px;
        }
        .flex-container {
            display: flex; /* Sử dụng flexbox */
            align-items: center; /* Căn giữa theo chiều dọc */
            gap: 10px; /* Khoảng cách giữa các phần tử */
        }
        .select-class {
            padding: 10px;
            font-size: 14px;
            border-radius: 5px;
            border: 1px solid #ccc;
        }
    </style>
<main class="table" id="customers_table">
    <section class="table__header">
        <div class="Insert">
            <div class="flex-container">
                <!-- Select box chọn lớp -->
                <select id="selectClass" class="select-class">
                    <option value="" disabled selected>Chọn lớp</option>
                </select>

                <!-- Tìm kiếm sinh viên -->
                <div class="input-group" style="margin-left: 150px;">
                    <input type="search" id="searchMaSV" placeholder="Mã SV">
                </div>
                <div class="input-group" style="margin-left: 150px;">
                    <input type="search" id="searchHoTen" placeholder="Họ tên">
                </div>
                <button style="border: none; background: transparent;" onclick="fetchBangDiem()">
                    <i class="fa fa-search"></i>
                </button>
                <button style="margin-left: 10px;" onclick="exportData()">
                    <i class="fa fa-download"></i> Export
                </button>
                <div style="margin-left: 10px;">
                    <input type="file" id="fileUpload" style="display: none;" accept=".csv,.xls,.xlsx" />
                    <button onclick="document.getElementById('fileUpload').click()">
                        <i class="fa fa-upload"></i> Upload
                    </button>
                </div>
            </div>
        </div>
    </section>

    <section class="table__body">
        <table>
            <thead>
                <tr>
                    <th>STT</th>
                    <th>Mã sinh viên</th>
                    <th>Tên sinh viên</th>
                    <th>Điểm chuyên cần</th>
                    <th>Điểm giữa kì</th>
                    <th>Điểm cuối kì</th>
                    <th style="padding-left:50px">Chức năng</th>
                </tr>
            </thead>
            <tbody id="bangdiem-body">
                {{-- Dữ liệu render từ JS --}}
            </tbody>
        </table>
    </section>
</main>

@endsection
<script src="https://cdn.jsdelivr.net/npm/axios/dist/axios.min.js"></script>
<script>
    let selectedClassId = '';

    axios.defaults.headers.common['Authorization'] = `Bearer ${localStorage.getItem('token')}`;

    async function loadDanhSachLop() {
        try {
            const response = await axios.get('/api/giaovien/dslophoc/{ma_giang_vien}');
            const classes = response.data;
            const selectBox = document.getElementById('selectClass');
            console.log("Lớp:", classes);
            selectBox.innerHTML += classes.map(cls =>
                `<option value="${cls.ma_lop}">Lớp ${cls.ma_lop}</option>`
            ).join('');
            const saved = localStorage.getItem('selectedClassId');
            if (saved) {
                selectBox.value = saved;
                fetchBangDiem();
            }
            selectBox.addEventListener('change', function () {
                selectedClassId = this.value;
                console.log("Selected Class ID: ", selectedClassId); 
                localStorage.setItem('selectedClassId', selectedClassId);
                fetchBangDiem();
            });
        } catch (error) {
            console.error("Lỗi khi tải lớp:", error);
            if (error.response && error.response.status === 401) {
                alert('Phiên đăng nhập hết hạn. Vui lòng đăng nhập lại.');
                // Chuyển hướng về trang đăng nhập nếu cần
                window.location.href = '/login';
            }
        }
    }
    async function fetchBangDiem() {
        const ma_sv = document.getElementById('searchMaSV').value;
        const ho_ten = document.getElementById('searchHoTen').value;

        try {
            const response = await axios.get(`/api/giaovien/diem-theo-lop/${selectedClassId}`, {
                params: {
                    ma_sinh_vien: ma_sv,
                    ho_ten: ho_ten
                }
            });

            const data = response.data;
            const tbody = document.getElementById('bangdiem-body');
            tbody.innerHTML = '';

            if (data.length === 0) {
                tbody.innerHTML = "<tr><td colspan='9'>Không có dữ liệu</td></tr>";
                return;
            }

            data.forEach((item, index) => {
                const row = `
                    <tr>
                        <td>${index + 1}</td>
                        <td>${item.ma_sinh_vien}</td>
                        <td>${item.ho_ten ?? 'Không có tên'}</td>
                        <td>${item.diem_chuyen_can}</td>
                        <td>${item.diem_giua_ky}</td>
                        <td>${item.diem_cuoi_ky}</td>
                        <td class="btn_cn">
                            <a href="/giaovien/diem-theo-lop/${item.ma_dct}/edit?class_id=${selectedClassId}">
                                <button class="button-85" role="button">Sửa</button>
                            </a>
                        </td>
                    </tr>`;
                tbody.insertAdjacentHTML('beforeend', row);
            });

        } catch (error) {
            console.error("Lỗi khi fetch dữ liệu:", error);
        }
    }

    function exportData() {
        if (!selectedClassId) {
            alert('Vui lòng chọn lớp học trước khi export');
            return;
        }

        const table = document.querySelector('table');
        let csv = [];
        const rows = table.querySelectorAll('tr');

        rows.forEach(row => {
            const cols = row.querySelectorAll('td:not(:last-child), th:not(:last-child)');
            let rowData = [];
            cols.forEach(col => rowData.push(col.innerText));
            csv.push(rowData.join(','));
        });

        // Thêm BOM và sử dụng Blob để tạo file
        const csvContent = "\uFEFF" + csv.join('\n'); // \uFEFF là BOM cho UTF-8
        const blob = new Blob([csvContent], { type: 'text/csv;charset=utf-8;' });
        
        const link = document.createElement('a');
        const url = URL.createObjectURL(blob);
        
        link.href = url;
        link.setAttribute('download', `bangdiem_${selectedClassId}.csv`);
        link.style.visibility = 'hidden';
        
        document.body.appendChild(link);
        link.click();
        document.body.removeChild(link);
        
        // Giải phóng bộ nhớ
        setTimeout(() => URL.revokeObjectURL(url), 100);
    }
    function uploadData() {
        const fileInput = document.createElement('input');
        fileInput.type = 'file';
        fileInput.accept = '.csv';  // Hoặc định dạng khác tùy theo yêu cầu
        fileInput.addEventListener('change', function () {
            const file = fileInput.files[0];
            if (file) {
                const formData = new FormData();
                formData.append('file', file);

                axios.post('/api/upload-diem', formData)
                    .then(response => {
                        alert('Tải lên thành công');
                        fetchBangDiem();  // Reload bảng điểm sau khi upload
                    })
                    .catch(error => {
                        console.error('Lỗi khi tải lên:', error);
                        alert('Có lỗi xảy ra khi tải lên');
                    });
            }
        });
        fileInput.click();
    }
    document.addEventListener('DOMContentLoaded', function () {
        loadDanhSachLop();
        const savedClassId = localStorage.getItem('selectedClassId');
        if (savedClassId) {
            selectedClassId = savedClassId;
        }
    });
</script>
