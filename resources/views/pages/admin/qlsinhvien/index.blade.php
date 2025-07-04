@extends('layout')

@section('content')
<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Quản lý sinh viên</title>

    <!-- CSS -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
    <link rel="stylesheet" href="/css/button.css?v={{ time() }}">
    <link rel="stylesheet" href="/css/styleDT.css?v={{ time() }}">

    <style>
        .btn_cn {
            display: flex;
            margin: 0;
            gap: 5px;
        }
    </style>
</head>
<body>

<main class="table" id="students_table">
    <section class="table__header">
        <h1>Quản lý sinh viên</h1>
        <div class="input-group">
            <input type="search" id="searchMaSV" placeholder="Mã SV">
        </div>
        <div class="input-group">
            <input type="search" id="searchHoTen" placeholder="Họ tên">
        </div>
        <button id="btnSearch" style="border: none; background: transparent;"><i class="fa fa-search"></i></button>

        <div class="Insert">
            <form action="{{ url('/admin/sinhvien/create') }}" method="GET">
                <button class="button-85" role="button" type="submit">Thêm sinh viên</button>
            </form>
        </div>

        <div class="export__file">
            <button class="button-85" onclick="exportExcel()">
                <i class="fa fa-download"></i> Export
            </button>
        </div>

        <div class="export__file">
            <input type="file" id="excelFileInput" accept=".xlsx, .xls" style="display:none" onchange="handleFileUpload(event)">
            <button class="button-85" onclick="document.getElementById('excelFileInput').click()">
                <i class="fa fa-upload"></i> Upload
            </button>
        </div>
    </section>

    <section class="table__body">
        <table>
            <thead>
                <tr>
                    <th>Mã SV</th>
                    <th>Mã khoa</th>
                    <th>Mã ngành</th>
                    <th>Họ tên</th>
                    <th>Ngày sinh</th>
                    <th>Giới tính</th>
                    <th>Quê quán</th>
                    <th>Email</th>
                    <th>Số điện thoại</th>
                    <th>Khóa học</th>
                    <th>Chức năng</th>
                </tr>
            </thead>
            <tbody id="student-table-body">
                <!-- JS sẽ render dữ liệu tại đây -->
            </tbody>
        </table>
    </section>
</main>

<!-- JS -->
<script src="https://cdn.jsdelivr.net/npm/axios/dist/axios.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/xlsx/0.18.5/xlsx.full.min.js"></script>

<script>
    token = localStorage.getItem('token');
    axios.defaults.headers.common['Authorization'] = `Bearer ${token}`;

    function loadSinhVien() {
        let maSV = document.getElementById("searchMaSV").value;
        let hoTen = document.getElementById("searchHoTen").value;

        axios.get('/api/admin/sinhvien', {
            params: {
                ma_sinh_vien: maSV,
                ho_ten: hoTen
            }
        })
        .then(response => {
            const tbody = document.getElementById("student-table-body");
            tbody.innerHTML = "";

            response.data.forEach(sv => {
                let html = `<tr>
                    <td>${sv.ma_sinh_vien}</td>
                    <td>${sv.ten_khoa ?? ''}</td>
                    <td>${sv.ten_nganh ?? ''}</td>
                    <td>${sv.ho_ten}</td>
                    <td>${sv.ngay_sinh}</td>
                    <td>${sv.gioi_tinh}</td>
                    <td>${sv.que_quan}</td>
                    <td>${sv.email}</td>
                    <td>${sv.so_dien_thoai}</td>
                    <td>${sv.khoa_hoc}</td>
                    <td class="btn_cn">
                        <button class="button-85" onclick="editSinhVien('${sv.ma_sinh_vien}')">Sửa</button>
                        <button class="button-85" onclick="deleteSinhVien('${sv.ma_sinh_vien}')">Xóa</button>
                    </td>
                </tr>`;
                tbody.insertAdjacentHTML('beforeend', html);
            });
        })
        .catch(error => {
            alert("Lỗi tải danh sách sinh viên: " + (error.response?.data?.message || error.message));
        });
    }

    function editSinhVien(maSV) {
        window.location.href = `/admin/sinhvien/${maSV}/edit`;
    }

    async function deleteSinhVien(maSV) {
        if (!confirm('Bạn có chắc muốn xóa sinh viên này?')) return;
        try {
            await axios.delete(`/api/admin/sinhvien/${maSV}`);
            alert('✅ Đã xóa thành công');
            loadSinhVien();
        } catch (error) {
            alert('❌ Lỗi khi xóa sinh viên: ' + (error.response?.data?.message || error.message));
        }
    }

    function exportExcel() {
        const table = document.querySelector("table");
        const selectedColumns = [0,1,2,3,4,5,6,7,8,9];
        let data = [];

        const headers = [];
        table.querySelectorAll("thead th").forEach((th, idx) => {
            if (selectedColumns.includes(idx)) headers.push(th.innerText.trim());
        });
        data.push(headers);

        table.querySelectorAll("tbody tr").forEach(tr => {
            const row = [];
            tr.querySelectorAll("td").forEach((td, idx) => {
                if (selectedColumns.includes(idx)) row.push(td.innerText.trim());
            });
            data.push(row);
        });

        const worksheet = XLSX.utils.aoa_to_sheet(data);
        const workbook = XLSX.utils.book_new();
        XLSX.utils.book_append_sheet(workbook, worksheet, "SinhVien");

        XLSX.writeFile(workbook, `sinhvien_${new Date().toISOString().slice(0,10)}.xlsx`);
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
        
        // ✅ Dùng tên cột làm key
        const rows = XLSX.utils.sheet_to_json(sheet, { defval: '', raw: false });

        // Bắt buộc file phải có đúng các cột:
        // ma_sinh_vien, ma_khoa, ma_nganh, ho_ten, ngay_sinh, gioi_tinh, que_quan, email, so_dien_thoai, khoa_hoc

         // ✅ Map từ tên cột tiếng Việt sang key backend cần
        const sinhViens = rows.map(row => ({
            ma_sinh_vien: row["Mã SV"],
            ma_khoa: row["Mã Khoa"],
            ma_nganh: row["Mã Ngành"],
            ho_ten: row["Họ Tên"],
            ngay_sinh: formatDate(row["Ngày Sinh"]),
            gioi_tinh: row["Giới Tính"],
            que_quan: row["Quê Quán"],
            email: row["Email"],
            so_dien_thoai: row["Số Điện Thoại"],
            khoa_hoc: row["Khóa Học"]
        }));

        let success = 0, fail = 0;

        const promises = sinhViens.map(sv =>
            axios.post('/api/admin/sinhvien', sv)
                .then(() => success++)
                .catch(error => {
                    fail++;
                    console.error("❌ Lỗi import:", error.response?.data || error.message);
                })
        );

        Promise.all(promises).then(() => {
            alert(`Import hoàn tất: ${success} thành công, ${fail} lỗi.`);
            loadSinhVien();
        });
    };
    reader.readAsArrayBuffer(file);
}




    document.getElementById('btnSearch').addEventListener('click', loadSinhVien);
    window.onload = loadSinhVien;
</script>

</body>
</html>
@endsection
